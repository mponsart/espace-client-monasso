<?php
// Chargeur de variables d'environnement (.env)
class EnvLoader {
    private static $values = [];

    public static function load($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("Fichier .env introuvable : {$filePath}");
        }
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Ignore les commentaires
            if (strpos(trim($line), '#') === 0) continue;
            // Parse key=value
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, '"\'');
                // Gère les références ${VAR}
                $value = preg_replace_callback('/\$\{([^}]+)\}/', function($m) {
                    return self::$values[$m[1]] ?? getenv($m[1]);
                }, $value);
                self::$values[$key] = $value;
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }

    public static function get($key, $default = null) {
        return self::$values[$key] ?? getenv($key) ?: $default;
    }

    public static function getRequired($key) {
        $value = self::get($key);
        if ($value === null) {
            throw new Exception("Variable d'environnement requise manquante : {$key}");
        }
        return $value;
    }
}
