<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsScrapers\NewsApiOrgScraper;
use App\Services\NewsScrapers\NewYorkTimesScraper;
use App\Services\NewsScrapers\OpenNewsAiScraper;
use Exception;
use Illuminate\Support\Facades\Log;

class ScrapNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and process news articles from various sources';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting news scraping...');

        try {
            app(NewsApiOrgScraper::class)->scrape();
            app(NewYorkTimesScraper::class)->scrape();
            app(OpenNewsAiScraper::class)->scrape();
            $this->info('Completed news scraping...');

        } catch (Exception $e) {
            Log::error('Error scraping news: ' . $e->getMessage());
            $this->error('An error occurred while scraping news. Check the logs for details.');
        }
    }
    


}
