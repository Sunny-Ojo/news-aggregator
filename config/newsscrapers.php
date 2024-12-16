<?php

return [
    'newsapi_org' => [
        'base_url' => env('NEWSAPI_BASE_URL', 'https://newsapi.org/v2'),
        'api_key'  => env('NEWSAPI_KEY'),
    ],

    'newsapi_ai' => [
        'base_url' => env('NEWSAPI_AI_BASE_URL', 'https://eventregistry.org/api/v1'),
        'api_key'  => env('NEWSAPI_AI_API_KEY'),
    ],

    'new_york_times' => [
        'base_url' => env('NEW_YORK_TIMES_BASE_URL', 'https://api.nytimes.com/svc/search/v2/'),
        'api_key'  => env('NEW_YORK_TIMES_API_KEY'),
        'image_base_url'  => env('NEW_YORK_TIMES_IMAGE_BASE_URL', 'https://static01.nyt.com/'),
    ],
];
