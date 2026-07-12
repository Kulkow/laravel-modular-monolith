<?php
return [
    'driver' => env('CORE_CACHE_DRIVER', 'redis'),
    'path' => storage_path('framework/cache/supply'),
    'connection' => env('SUPPLY_CACHE_CONNECTION'),
    'host' => env('SUPPLY_CACHE_HOST', '127.0.0.1'),
    'port' => env('SUPPLY_CACHE_PORT', 6379),
    'database' => env('SUPPLY_CACHE_DB', 1),
    'password' => env('SUPPLY_CACHE_PASSWORD'),
    'prefix' => env('SUPPLY_CACHE_PREFIX', 'core'),
    'ttl' => env('SUPPLY_CACHE_TTL', 3600),
];
