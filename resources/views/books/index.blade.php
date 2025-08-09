@extends('layouts.app')

@section('content')

<h1 class="text-xl mb-2">Book Lists Review</h1>

<form method="GET" action="{{ route('book.index') }}" class="mb-4 flex items-center space-x-2">
    <input type="text" name="title" placeholder="Search By Book Title" value = " {{ request('title') }}" class="input">
    <input type="hidden" name="filter" value=" {{ request('filter') }}" >
    <button type="submit" class="btn">Search</button>
    <a href=" {{ route('book.index') }}">Cancel</a>
</form>

<div class="filter-container mb-4 flex">
    @php
        $filters = [
            '' => 'Latest',
            'popular_last_month' => 'Popular Last Month',
            'popular_last_6months' => 'Popular Last 6 Months',
            'highest_rated_last_month' => 'Highest Rated Last Month',
            'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
        ]   
    @endphp

    @foreach ($filters as $key => $label)
        <a href="{{ route('book.index', [...request()->query(), 'filter' => $key])}}" 
            class={{ $key === request('filter') || (request('filter') == null && $key === '') ? 'filter-item-active' : 'filter-item' }}
        >
            {{ $label }}
        </a>
    @endforeach
</div>

<ul>
    @forelse ($books as $book)
      <li class="mb-4">
        <div class="book-item">
          <div
            class="flex flex-wrap items-center justify-between">
            <div class="w-full flex-grow sm:w-auto">
              <a href="{{ route('book.show', $book) }}" class="book-title">{{ $book->title }}</a>
              <span class="book-author">by {{ $book->author }}</span>
            </div>
            <div>
              <div class="book-rating">
                {{ number_format($book->reviews_avg_rating , 1) }}
                <x-star-rating :rating="$book->reviews_avg_rating" />
              </div>
              <div class="book-review-count">
                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
              </div>
            </div>
          </div>
        </div>
      </li>
    @empty
      <li class="mb-4">
        <div class="empty-book-item">
          <p class="empty-text">No books found</p>
          <a href="{{ route('book.index') }}" class="reset-link">Reset criteria</a>
        </div>
      </li>
    @endforelse
  </ul>

@endsection