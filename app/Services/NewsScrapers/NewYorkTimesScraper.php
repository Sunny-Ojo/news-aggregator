<?php

namespace App\Services\NewsScrapers;

use App\DTOs\ArticleDto;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Log;

class NewYorkTimesScraper extends BaseScraper
{
    private string $baseUrl;
    private string $apiKey;
    private string $source = 'New York Times';
    /**
     * Create a new class instance.
     */
    public function __construct(ArticleService $articleService)
    {
        $this->baseUrl = config('newsscrapers.new_york_times.base_url');
        $this->apiKey = config('newsscrapers.new_york_times.api_key');
        parent::__construct($articleService);

    }

    /**
     * Scrape articles currently on a section front or the home page of NY Times
     */
    public function scrape(): void
    {
        $queryParams = [
            'api-key' => $this->apiKey,
        ];
        $articles = $this->fetchArticles('/topstories/v2/world.json', $queryParams);
       $mappedArticles = $this->mapToDtos($articles);
       $this->saveArticles($mappedArticles);

    }

    /**
     * 	Fetch the articles
     *
     */
    private function fetchArticles(string $endpoint, array $queryParams): array
    {
        $url = $this->baseUrl . $endpoint;
        $response = $this->sendRequest($url, $queryParams);

        return $response['results'] ?? [];
    }

    /**
     * Map data to ArticleDto.
     */
    private function mapToDtos(array $articles): array
    {
        return array_filter(array_map(function ($article) {
            return new ArticleDto(
                $article['title'] ?? 'No Title',
                $article['byline'] ?? 'null',
                $article['abstract'] ?? null,
                $article['lead_paragraph'] ?? null,
                $article['url'] ?? null,
                !empty($article['multimedia']) ? end($article['multimedia'])['url'] : null,
                $this->source,
                $article['section'] ?? null,
                $article['published_date'] ?? null
            );

        }, $articles));
    }

}
