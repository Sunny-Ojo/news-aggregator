<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleFilter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply all filters to the query
     */
    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('keyword'), fn ($q) => $this->filterByKeyword($q))
            ->when($this->request->filled('date'), fn ($q) => $this->filterByDate($q))
            ->when($this->request->filled('category'), fn ($q) => $this->filterByCategory($q))
            ->when($this->request->filled('source'), fn ($q) => $this->filterBySource($q))
            ->when($this->request->filled('author'), fn ($q) => $this->filterByAuthor($q));
    }

    /**
     * Filter by keyword
     */
    protected function filterByKeyword(Builder $query): Builder
    {
        $keyword = $this->request->input('keyword');

        return $query->whereAny([
            'title',
            'content',
            'description',
        ], 'like', "%{$keyword}%");

    }

    /**
     * Filter by date
     */
    protected function filterByDate(Builder $query): Builder
    {
        $date = $this->request->input('date');
        $dateRanges = [
            'today' => [Carbon::today(), Carbon::today()->endOfDay()],
            'this_week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'this_month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        ];

        if (isset($dateRanges[$date])) {
            $query->whereBetween('published_at', $dateRanges[$date]);
        }

        return $query;
    }

    /**
     * Filter by category
     */
    protected function filterByCategory(Builder $query): Builder
    {
        return $query->where('category', trim($this->request->input('category')));
    }

    /**
     * Filter by source
     */
    protected function filterBySource(Builder $query): Builder
    {
        return $query->where('source', trim($this->request->input('source')));
    }

    /**
     * Filter by author
     */
    protected function filterByAuthor(Builder $query): Builder
    {
        return $query->where('author', trim($this->request->input('author')));
    }
}
