<?php

namespace App\Services;

use App\DTOs\ArticleDto;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    /**
     * Get all articles
     * @param \Illuminate\Http\Request $request
     */
    public function getAll(Request $request)
    {
        $query = Article::query()->inrandomOrder();
        $this->applyFilters($query, $request);
        return $query->whereNotNull('description')->paginate();
    }

    /**
     * Get personalized feed of articles
     * @param $preferences
     * @param \Illuminate\Http\Request $request
     */
    public function getPersonalizedFeed($preferences, Request $request)
    {
        $query = Article::query()->inrandomOrder();

        $query->when($preferences->categories, fn($q) => $q->whereIn('category', $preferences->categories))
              ->when($preferences->sources, fn($q) => $q->whereIn('source', $preferences->sources))
              ->when($preferences->authors, fn($q) => $q->whereIn('author', $preferences->authors));

        $this->applyFilters($query, $request);

        return $query->whereNotNull('description')->latest()->paginate();
    }

    /**
     * Save an article using an ArticleDto.
     *
     * @param ArticleDto $articleDto
     * @return Article
     */
    public function store(ArticleDto $articleDto): Article
    {
        $data = $articleDto->toArray();
        return Article::updateOrCreate(['url' => $data['url']], $data);
    }

    /**
     * Get article categories
     *
     * @return Article
     */
    public function getCategories(): Collection
    {
        return Article::distinct()->pluck('category');
    }

    /**
     * Get article sources
     *
     * @return Article
     */
    public function getSources(): Collection
    {
        return Article::distinct()->pluck('source');
    }

    /**
     * Apply common search filters to the article query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters($query, Request $request)
    {

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                Log::info($request->keyword);
            return $q->whereAny([
                    'title',
                    'content',
                    'description',
                ], 'like', "%{$request->keyword}%");

            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('published_at', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('category')) {
            $query->where('category', trim($request->category));
        }

        if ($request->filled('source')) {
            $query->where('source', trim($request->source));
        }

        if ($request->filled('author')) {
            $query->where('author', trim($request->author));
        }

        return $query;
    }
}
