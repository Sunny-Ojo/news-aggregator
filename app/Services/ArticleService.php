<?php

namespace App\Services;

use App\DTOs\ArticleDto;
use App\Filters\ArticleFilter;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
     * @param \Illuminate\Http\Request $request
     */
    public function getPersonalizedFeed(Request $request)
    {
        $preferences = $request->user()->preferences;
        $query = Article::query()->inrandomOrder();
        if ($preferences) {
                $query->when($preferences->categories, fn($q) => $q->whereIn('category', $preferences->categories))
                    ->when($preferences->sources, fn($q) => $q->whereIn('source', $preferences->sources))
                    ->when($preferences->authors, fn($q) => $q->whereIn('author', $preferences->authors));
        }

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
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return Article::distinct()
        ->whereNotNull('category')
        ->where('category', '!=', '') //somehow, some of the api articles have empty string
        ->pluck('category');
    }

    /**
     * Get article sources
     *
     * @return Collection
     */
    public function getSources(): Collection
    {
        return Article::distinct()->whereNotNull('source')->pluck('source');
    }

    /**
     * Get article authors
     *
     * @return Collection
     */
    public function getAuthors(): Collection
    {
        return  Article::distinct()
        ->whereNotNull('author')
        ->where('author', '!=', '') //somehow, some of the api articles have empty string
        ->pluck('author');
    }

    /**
     * Apply common search filters to the article query. (personalized feed uses this too)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters($query, Request $request)
    {
        (new ArticleFilter($request))->apply($query);
        return $query;
    }
}
