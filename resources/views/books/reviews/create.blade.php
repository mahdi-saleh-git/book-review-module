@extends('layouts.app')

@section('content')
    <form method="POST" action="{{route('books.reviews.store', $book)}}">
        @csrf
        <label for="review">Review</label>
        <textarea name="review" id="review" cols="30" rows="5"></textarea>

        <label for="rating">Rating</label>
        <select name="rating" id="rating">
            <option value="">Select a Rating</option>
            @for ($i = 1; $i <= 5; $i++)
            <option value="{{$i}}">{{$i}}</option>
            @endfor
        </select>

        <button type="submit">Save</button>
    </form>
@endsection