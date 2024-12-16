## News Aggregator

This is a news aggregator API project that pulls articles from various sources and store them locally, below are the APIs integrated....

## Integrated News Sources

-   [NewsApi.ai](https://newsapi.ai/).
-   [New York Times](https://developer.nytimes.com/).
-   [NewsApi.org](https://newsapi.org/).

### Installation

Clone the repository:

git clone https://github.com/Sunny-Ojo/news-aggregator.git

cd news-aggregator

composer install

cp .env.example .env

php artisan key:generate

./vendor/bin/sail up -d

./vendor/bin/sail artisan migrate

### API keys needs to be generated from the sources listed above and then add them to the .env respectively

## Scrapping the News

./vendor/bin/sail php artisan news:scrape
