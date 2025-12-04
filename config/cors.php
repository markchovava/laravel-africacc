<?php
// config/cors.php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login/*', 'register/*', "schedule-of-customer-unauth/*"],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'https://next-recycle-center-olive.vercel.app',
        'https://next-recycle-center-olive.vercel.app/login',
        'https://fldesigners.xyz',
        '',
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];