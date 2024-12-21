<?php

namespace App\Services\NewsScrapers;

use App\Services\ArticleService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseScraper
{
    public function __construct(private readonly ArticleService $articleService) {}

    /**
     * Scrape articles from the source. This ensures all extending classes have this same method
     */
    abstract public function scrape(): void;

    /**
     * Send an HTTP GET request to the given URL with optional query params.
     *
     * @throws Exception
     */
    protected function sendRequest(string $url, array $queryParams = []): array
    {
        try {
            $response = Http::acceptJson()->get($url, $queryParams);

            if ($response->failed()) {
                throw new Exception("Request to {$url} failed with status: {$response->status()}");
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error("Error in BaseScraper::sendRequest — {$e->getMessage()}", [
                'url' => $url,
                'params' => $queryParams,
            ]);
            throw $e;
        }
    }

    /**
     * Save the scraped articles.
     *
     * @param array
     */
    protected function saveArticles(array $articles): void
    {
        foreach ($articles as $article) {
            $this->articleService->store($article);
        }
    }
}
