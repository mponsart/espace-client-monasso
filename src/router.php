<?php
// Routeur principal MonAsso (PHP natif)

session_start();

// Headers de sécurité
require_once __DIR__ . '/Security.php';
Security::sendSecurityHeaders();

// Chargement config
$app = require __DIR__ . '/../config/app.php';

// Routage simple (exemple)
$uri = strtok($_SERVER['REQUEST_URI'], '?');

switch ($uri) {
    case '/':
        require __DIR__ . '/../templates/home.php';
        break;
    case '/login':
        require_once __DIR__ . '/Controller/AuthController.php';
        AuthController::handleLogin();
        break;
    case '/register':
        require_once __DIR__ . '/Controller/AuthController.php';
        AuthController::handleRegister();
        break;
    case '/logout':
        require_once __DIR__ . '/Controller/AuthController.php';
        AuthController::handleLogout();
        break;
    case '/dashboard':
        require_once __DIR__ . '/Controller/DashboardController.php';
        DashboardController::show();
        break;
    case '/admin':
        require_once __DIR__ . '/Controller/Admin/AdminController.php';
        AdminController::index();
        break;
    case '/admin/user':
        if (isset($_GET['id'])) {
            require_once __DIR__ . '/Controller/Admin/AdminController.php';
            AdminController::viewUser((int)$_GET['id']);
        } else {
            header('Location: /admin');
            exit;
        }
        break;
    case '/admin/user/update-suspension':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/Controller/Admin/AdminController.php';
            AdminController::updateSuspensionReason();
        } else {
            header('Location: /admin');
            exit;
        }
        break;
    case '/rgpd/export':
        require_once __DIR__ . '/Controller/RGPDController.php';
        RGPDController::export();
        break;
    case '/rgpd/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/Controller/RGPDController.php';
            RGPDController::delete();
        } else {
            header('Location: /dashboard');
            exit;
        }
        break;
    case '/faq':
        require __DIR__ . '/../templates/faq/index.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/../templates/errors/404.php';
        break;
}
