<?php
require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Security.php';
require_once __DIR__ . '/../Model/User.php';

class AuthController {
    public static function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Rate limiting
            if (!Security::checkRateLimit('login_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'))) {
                $_SESSION['error'] = "Trop de tentatives. Veuillez réessayer plus tard.";
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                if (!Security::isValidEmail($email)) {
                    $_SESSION['error'] = "Email invalide.";
                } elseif (!$password) {
                    $_SESSION['error'] = "Mot de passe requis.";
                } else {
                    $user = Auth::login($email, $password);
                    if ($user) {
                        header('Location: /dashboard');
                        exit;
                    } else {
                        $_SESSION['error'] = "Identifiants invalides.";
                    }
                }
            }
        }
        require __DIR__ . '/../../templates/auth/login.php';
    }

    public static function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification CSRF
            if (!Security::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = "Token CSRF invalide.";
            } else {
                $association = trim($_POST['association'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                if (!$association || !$email || !$password) {
                    $_SESSION['error'] = "Tous les champs sont obligatoires.";
                } elseif (!Security::isValidEmail($email)) {
                    $_SESSION['error'] = "Email invalide.";
                } elseif (!Security::isValidPassword($password)) {
                    $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
                } elseif (User::findByEmail($email)) {
                    $_SESSION['error'] = "Cet email est déjà utilisé.";
                } else {
                    $user = User::create($association, $email, $password);
                    Auth::login($email, $password);
                    header('Location: /dashboard');
                    exit;
                }
            }
        }
        require __DIR__ . '/../../templates/auth/register.php';
    }

    public static function handleLogout() {
        Auth::logout();
        header('Location: /');
        exit;
    }
}
