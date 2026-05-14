<?php
require_once __DIR__ . '/../Database.php';

class Subscription {
    public $id;
    public $user_id;
    public $stripe_subscription_id;
    public $status;
    public $current_period_end;
    public $created_at;
    public $updated_at;

    public static function findByUserId($user_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM subscriptions WHERE user_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$user_id]);
        $data = $stmt->fetch();
        return $data ? self::fromArray($data) : null;
    }

    public static function fromArray($data) {
        $sub = new self();
        foreach ($data as $k => $v) {
            if (property_exists($sub, $k)) $sub->$k = $v;
        }
        return $sub;
    }
    // Active l’abonnement
    public static function activate($user_id, $stripe_subscription_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('UPDATE subscriptions SET status = ?, updated_at = NOW() WHERE user_id = ? AND stripe_subscription_id = ?');
        $stmt->execute(['active', $user_id, $stripe_subscription_id]);
    }

    // Suspend l’abonnement
    public static function suspend($user_id, $stripe_subscription_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('UPDATE subscriptions SET status = ?, updated_at = NOW() WHERE user_id = ? AND stripe_subscription_id = ?');
        $stmt->execute(['suspended', $user_id, $stripe_subscription_id]);
    }

    // Trouve un user par customer Stripe
    public static function findByStripeCustomerId($customerId) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT u.* FROM users u JOIN subscriptions s ON u.id = s.user_id WHERE s.stripe_customer_id = ? LIMIT 1');
        $stmt->execute([$customerId]);
        $data = $stmt->fetch();
        return $data ? User::fromArray($data) : null;
    }

    // Trouve un user par subscription Stripe
    public static function findByStripeSubscriptionId($subscriptionId) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT u.* FROM users u JOIN subscriptions s ON u.id = s.user_id WHERE s.stripe_subscription_id = ? LIMIT 1');
        $stmt->execute([$subscriptionId]);
        $data = $stmt->fetch();
        return $data ? User::fromArray($data) : null;
    }
}
