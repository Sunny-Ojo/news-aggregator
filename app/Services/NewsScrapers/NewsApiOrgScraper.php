<?php

namespace App\Services\NewsScrapers;

use App\DTOs\ArticleDto;
use App\Services\ArticleService;

class NewsApiOrgScraper extends BaseScraper
{
    private string $baseUrl;

    private string $apiKey;

    private string $source = 'NewsAPI Org';

    private string $category = 'Top Headlines';

    /**
     * Create a new class instance.
     */
    public function __construct(ArticleService $articleService)
    {
        $this->baseUrl = config('newsscrapers.newsapi_org.base_url');
        $this->apiKey = config('newsscrapers.newsapi_org.api_key');
        parent::__construct($articleService);

    }

    /**
     * Scrape the latest articles from NewsApi.org.
     */
    public function scrape(): void
    {
        $queryParams = [
            'apiKey' => $this->apiKey,
            'country' => 'us',
        ];

        $articles = $this->fetchArticles('/top-headlines', $queryParams);

        $mappedArticles = $this->mapToDtos($articles);

        $this->saveArticles($mappedArticles);

    }

    /**
     * Fetch articles from the given endpoint.
     */
    private function fetchArticles(string $endpoint, array $queryParams): array
    {
        $url = $this->baseUrl.$endpoint;
        $response = $this->sendRequest($url, $queryParams);

        return $response['articles'] ?? [];
    }

    /**
     * Map data to ArticleDto.
     */
    private function mapToDtos(array $articles): array
    {
        return array_filter(array_map(function ($article) {
            if (empty($article['content']) && empty($article['description'])) {
                return null;
            }

            return new ArticleDto(
                $article['title'] ?? 'No Title',
                $article['author'] ?? null,
                $article['description'] ?? null,
                $article['content'] ?? null,
                $article['url'] ?? null,
                $article['urlToImage'] ?? null,
                $this->source,
                $this->category,
                $article['publishedAt'] ?? null
            );
        }, $articles));
    }
}
