<?php

return [
    'newsapi' => [
        'base_url' => env('NEWSAPI_BASE_URL', 'https://newsapi.org/v2'),
        'api_key'  => env('NEWSAPI_KEY'),
    ],

    'opennews' => [
        'base_url' => env('OPENNEWS_BASE_URL', 'https://opennews.api'),
        'api_key'  => env('OPENNEWS_KEY'),
    ],

    'new_york_times' => [
        'base_url' => env('NEW_YORK_TIMES_BASE_URL', 'https://api.nytimes.com/svc'),
        'api_key'  => env('NEW_YORK_TIMES_KEY'),
    ],
];
