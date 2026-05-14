<?php
require_once __DIR__ . '/../../Auth.php';
require_once __DIR__ . '/../../Model/User.php';
require_once __DIR__ . '/../../Model/Subscription.php';
require_once __DIR__ . '/../../Service/StripeService.php';

class AdminController {
    public static function index() {
        if (!Auth::check() || !Auth::user()->is_admin) {
            header('Location: /');
            exit;
        }
        $users = User::getAll();
        require __DIR__ . '/../../../templates/admin/index.php';
    }

    public static function viewUser($userId) {
        if (!Auth::check() || !Auth::user()->is_admin) {
            header('Location: /');
            exit;
        }
        $user = User::findById($userId);
        if (!$user) {
            header('Location: /admin');
            exit;
        }
        $subscription = Subscription::findByUserId($user->id);
        require __DIR__ . '/../../../templates/admin/user.php';
    }

    public static function updateSuspensionReason() {
        if (!Auth::check() || !Auth::user()->is_admin) {
            header('Location: /');
            exit;
        }
        $subscriptionId = $_POST['subscription_id'] ?? null;
        $reason = $_POST['suspension_reason'] ?? null;
        if ($subscriptionId) {
            $stripe = new StripeService();
            $stripe->updateSuspensionMetadata($subscriptionId, $reason);
        }
        header('Location: /admin/user/' . (int)$_POST['user_id']);
        exit;
    }
}
