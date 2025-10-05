<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Identity Module Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the Identity module.
    |
    */

    'user' => [
        'default_role' => 'aluno',
        'default_status' => 'active',
        'password_min_length' => 8,
    ],

    'classroom' => [
        'default_year' => now()->year,
    ],

    'series' => [
        'default_order' => 1,
    ],
];
