<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CampusPulse Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for CampusPulse application including freshness TTLs.
    |
    */

    'crowd_ttl' => env('CROWD_TTL_MINUTES', 60),
    'wifi_ttl' => env('WIFI_TTL_MINUTES', 45),
];