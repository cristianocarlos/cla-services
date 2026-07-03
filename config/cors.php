<?php

// config/cors.php

return [
    'allowed_origins' => [
        'https://*.analise.com',
        'https://*.analise-com.test',
        'http://*.analise-com.test',
        //
        'https://*.legalexperience.com.br',
        'https://*.legalexperience-com-br.test',
        'http://*.legalexperience-com-br.test',
        //
        'https://*.mdmed.clinic',
        'https://*.mdmed-clinic.test',
        'http://*.mdmed-clinic.test',
    ],

    'allowed_methods' => [
        'POST',
        'GET',
        'OPTIONS',
        'PUT',
        'PATCH',
        'DELETE',
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        'Accept',
        'Origin',
    ],

    'exposed_headers' => [
        'Authorization',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
    ],

    'max_age' => 86400,

    'supports_credentials' => true,
];
