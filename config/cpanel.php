<?php
require_once __DIR__ . '/../src/EnvLoader.php';
EnvLoader::load(__DIR__ . '/../.env');

return [
    'host' => EnvLoader::get('CPANEL_HOST'),
    'port' => (int) EnvLoader::get('CPANEL_PORT', 2083),
    'username' => EnvLoader::get('CPANEL_USERNAME', 'monasso'),
    'token' => EnvLoader::get('CPANEL_TOKEN'),
    // Chemin absolu vers le dossier des utilisateurs sur Paheko
    'users_path' => EnvLoader::get('CPANEL_USERS_PATH', '/home/monasso/users'),
];
