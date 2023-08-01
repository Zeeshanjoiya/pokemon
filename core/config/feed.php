<?php

return [
    'feeds' => [
        'main' => [
            /*
             * Here you can specify which class and method will return
             * the items that should appear in the feed. For example:
             * 'App\Model@getAllFeedItems'
             *
             * You can also pass an argument to that method:
             * ['App\Model@getAllFeedItems', 'argument']
             */
            'items' => ['App\Models\Topic@getFeedTopics'],

            /*
             * The feed will be available on this url.
             */
            'url' => '/rss',

            'title' => env("APP_NAME"),
            'description' => 'All topics on '.env("APP_NAME"),
            'language' => 'en-US',

            /*
             * The view that will render the feed.
             */
            'view' => 'feed::rss',

            /*
             * The type to be used in the <link> tag
             */
            'type' => 'application/atom+xml',
        ],
    ],
];
