<?php
require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Model/User.php';
require_once __DIR__ . '/../Model/Subscription.php';
require_once __DIR__ . '/../Model/Payment.php';
require_once __DIR__ . '/../Model/Notification.php';
require_once __DIR__ . '/../Model/RGPDConsent.php';

class RGPDController {
    // Export des données utilisateur (JSON)
    public static function export() {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        $user = Auth::user();
        $data = [
            'user' => $user,
            'subscription' => Subscription::findByUserId($user->id),
            'payments' => Payment::findByUserId($user->id),
            'notifications' => Notification::findByUserId($user->id),
            'consents' => RGPDConsent::findByUserId($user->id),
        ];
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="export_monasso_' . $user->id . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Suppression des données utilisateur (anonymisation)
    public static function delete() {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        $user = Auth::user();
        // Anonymisation
        User::anonymize($user->id);
        // Déconnexion
        session_destroy();
        header('Location: /?deleted=1');
        exit;
    }
}
