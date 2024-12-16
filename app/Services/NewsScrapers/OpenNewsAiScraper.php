<?php

namespace App\Services\NewsScrapers;

use App\DTOs\ArticleDto;
use App\Services\ArticleService;

class OpenNewsAiScraper extends BaseScraper
{
    private string $baseUrl;
    private string $apiKey;
    private string $source = 'NewsAPI';

    /**
     * Create a new class instance.
     */
    public function __construct(ArticleService $articleService)
    {
        $this->baseUrl = config('newsscrapers.newsapi_ai.base_url');
        $this->apiKey = config('newsscrapers.newsapi_ai.api_key');
        parent::__construct($articleService);

    }

    /**
     * Scrape articles currently on a section front or the home page of NY Times
     */
    public function scrape(): void
    {
        $queryParams = [
            'apiKey' => $this->apiKey,
            'keyword' => 'everything'
        ];
        $articles = $this->fetchArticles('/article/getArticles', $queryParams);
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

        return $response['articles']['results'] ?? [];
    }

    /**
     * Map data to ArticleDto.
     */
    private function mapToDtos(array $articles): array
    {
        return array_filter(array_map(function ($article) {
            return new ArticleDto(
                $article['title'] ?? 'No Title',
                $article['authors'][0]['name'] ?? 'Unknown Author',
                $article['body'] ?? null,
                $article['body'] ?? null,
                $article['url'] ?? null,
                $article['image'] ?? null,
                $this->source,
                $article['category'] ?? 'Uncategorized',
                $article['dateTimePub'] ?? null,
            );

        }, $articles));
    }

}
