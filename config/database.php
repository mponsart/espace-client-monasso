<?php
require_once __DIR__ . '/../src/EnvLoader.php';
EnvLoader::load(__DIR__ . '/../.env');

return [
    'host' => EnvLoader::get('DB_HOST', 'localhost'),
    'database' => EnvLoader::getRequired('DB_DATABASE'),
    'username' => EnvLoader::getRequired('DB_USERNAME'),
    'password' => EnvLoader::getRequired('DB_PASSWORD'),
    'charset' => EnvLoader::get('DB_CHARSET', 'utf8mb4'),
];
