<?php
require_once __DIR__ . '/../src/EnvLoader.php';
EnvLoader::load(__DIR__ . '/../.env');

return [
    'name' => EnvLoader::get('APP_NAME', 'MonAsso'),
    'env' => EnvLoader::get('APP_ENV', 'production'),
    'debug' => EnvLoader::get('APP_DEBUG', false),
    'url' => EnvLoader::get('APP_URL', 'https://monasso.eu'),
    'support_email' => EnvLoader::get('SUPPORT_EMAIL', 'support@monasso.eu'),
    'annual_price' => 19.00,
    'currency' => 'EUR',
    'locale' => 'fr',
    'database' => require __DIR__ . '/database.php',
    'stripe' => require __DIR__ . '/stripe.php',
    'cpanel' => require __DIR__ . '/cpanel.php',
];
