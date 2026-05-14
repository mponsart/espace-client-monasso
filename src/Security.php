<?php
// Sécurité : CSRF, validation, anti-bot

class Security {
    // Génère un token CSRF
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Vérifie le token CSRF
    public static function checkCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Nettoie une entrée utilisateur (XSS)
    public static function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // Valide un email
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Valide un mot de passe (min 8 caractères, 1 maj, 1 min, 1 chiffre)
    public static function isValidPassword($password) {
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password);
    }

    // Rate limiting simple (anti-bot / brute-force)
    public static function checkRateLimit($key, $maxAttempts = 5, $windowSeconds = 300) {
        $file = sys_get_temp_dir() . '/monasso_ratelimit_' . md5($key);
        $now = time();
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : ['count' => 0, 'reset' => $now + $windowSeconds];
        if ($now > $data['reset']) {
            $data = ['count' => 0, 'reset' => $now + $windowSeconds];
        }
        $data['count']++;
        file_put_contents($file, json_encode($data));
        return $data['count'] <= $maxAttempts;
    }

    // Headers de sécurité HTTP
    public static function sendSecurityHeaders() {
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
    }
}
