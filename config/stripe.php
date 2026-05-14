<?php
require_once __DIR__ . '/../src/EnvLoader.php';
EnvLoader::load(__DIR__ . '/../.env');

return [
    'public_key' => EnvLoader::getRequired('STRIPE_PUBLIC_KEY'),
    'secret_key' => EnvLoader::getRequired('STRIPE_SECRET_KEY'),
    'webhook_secret' => EnvLoader::get('STRIPE_WEBHOOK_SECRET'),
];
