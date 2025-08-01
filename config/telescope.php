<?php

use Laravel\Telescope\Telescope;
use Illuminate\Support\Str;

return [
    'id' => env('TELESCOPE_ID', 'telescope_'.Str::random(40)),
    'driver' => env('TELESCOPE_DRIVER', 'database'),
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'chunk' => 1000,
        ],
    ],
    'path' => env('TELESCOPE_PATH', 'telescope'),

    'middleware' => [
        'web',
        // Comment out or remove the default gate middleware
        // \Laravel\Telescope\Http\Middleware\Authorize::class,
    ],

    'gate' => [
        'enabled' => env('TELESCOPE_GATE_ENABLED', true),
    ],

    'watchers' => [
        \Laravel\Telescope\Watchers\RequestWatcher::class => [
            'enabled' => true,
            'size_limit' => env('TELESCOPE_REQUEST_SIZE_LIMIT', 64),
        ],
        \Laravel\Telescope\Watchers\CommandWatcher::class => [
            'enabled' => true,
            'ignore' => [],
        ],
        \Laravel\Telescope\Watchers\ScheduleWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\JobWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\ExceptionWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\DumpWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\LogWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\ModelWatcher::class => [
            'enabled' => true,
            'events' => ['eloquent.*'],
        ],
        \Laravel\Telescope\Watchers\GateWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\MailWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\NotificationWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\CacheWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\QueryWatcher::class => [
            'enabled' => true,
            'ignore_packages' => true,
            'slow' => 100,
        ],
        \Laravel\Telescope\Watchers\RedisWatcher::class => [
            'enabled' => true,
        ],
        \Laravel\Telescope\Watchers\EventWatcher::class => [
            'enabled' => true,
            'ignore' => [],
        ],
        \Laravel\Telescope\Watchers\ViewWatcher::class => [
            'enabled' => true,
        ],
        // \Laravel\Telescope\Watchers\BroadcastWatcher::class => [
        //     'enabled' => true,
        // ],
    ],
];
