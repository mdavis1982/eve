<?php

return [
    'auth' => [
        'token' => env('EVE_SLACK_TOKEN'),
    ],

    'handlers' => [
        App\Handlers\Fun\PunHandler::class,
        App\Handlers\Fun\SlapHandler::class,
        App\Handlers\Fun\SandwichHandler::class,
        App\Handlers\Fun\EightBallHandler::class,
        App\Handlers\Human\HelloHandler::class,
        App\Handlers\Human\ThanksHandler::class,
        App\Handlers\Media\GiphyHandler::class,
        App\Handlers\Moderation\WarnHandler::class,
        App\Handlers\Moderation\UserJoinsChannelHandler::class,
        App\Handlers\Reference\LaravelHandler::class,
        App\Handlers\Reference\ImdbHandler::class,
        App\Handlers\Utility\PingHandler::class,
        App\Handlers\Utility\CalculateHandler::class,
        App\Handlers\Utility\HelpHandler::class
    ],

    'services' => [
        'giphy' => [
            'base_url' => env('GIPHY_BASE_URL'),
            'api_key'  => env('GIPHY_API_KEY'),
        ],
    ],
];
