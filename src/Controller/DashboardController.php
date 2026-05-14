<?php
require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Model/Subscription.php';
require_once __DIR__ . '/../Model/Payment.php';
require_once __DIR__ . '/../Model/Notification.php';

class DashboardController {
    public static function show() {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }
        $user = Auth::user();
        $subscription = Subscription::findByUserId($user->id);
        $payments = Payment::findByUserId($user->id);
        $notifications = Notification::findByUserId($user->id);
        require __DIR__ . '/../../templates/dashboard.php';
    }
}
