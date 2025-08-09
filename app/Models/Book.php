<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Book extends Model
{
    //
    use HasFactory;

    public function reviews() {

        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title) : Builder {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null) : Builder {
        return $query->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null) : Builder {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
            ],
            'rating');
    }

    public function scopePopular(Builder $query, $from = null, $to = null) : Builder {
        return $query->withReviewsCount()->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null) : Builder {
        return $query->withAvgRating()->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeLowestRated(Builder $query) : Builder {
        return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating');
    }

    public function scopeMinReview(Builder $query, int $minReview) : Builder {
        return $query->having('reviews_count', '>=', $minReview);
    }

    private function dateRangeFilter(Builder $query, $from, $to) {
        
        if($from && !$to) {
            $query->where('created_at', '>=', $from);
        } else if (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } else if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query) : Builder {

        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReview(2);
    }

    public function scopePopularLast6Months(Builder $query) : Builder {

        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReview(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query) : Builder {

        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReview(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query) : Builder {

        return $query->highestRated(now()->subMonth(6), now())
            ->popular(now()->subMonth(6), now())
            ->minReview(5);
    }
}

