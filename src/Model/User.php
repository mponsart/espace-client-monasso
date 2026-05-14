<?php
require_once __DIR__ . '/../Database.php';

class User {
    public $id;
    public $association;
    public $email;
    public $password;
    public $is_admin;
    public $created_at;
    public $updated_at;

    public static function findByEmail($email) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        return $data ? self::fromArray($data) : null;
    }

    public static function findById($id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? self::fromArray($data) : null;
    }

    public static function create($association, $email, $password) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('INSERT INTO users (association, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$association, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return self::findById($db->lastInsertId());
    }

    public static function fromArray($data) {
        $user = new self();
        foreach ($data as $k => $v) {
            if (property_exists($user, $k)) $user->$k = $v;
        }
        return $user;
    }
    // Trouve un user par customer Stripe
    public static function findByStripeCustomerId($customerId) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT u.* FROM users u JOIN subscriptions s ON u.id = s.user_id WHERE s.stripe_customer_id = ? LIMIT 1');
        $stmt->execute([$customerId]);
        $data = $stmt->fetch();
        return $data ? self::fromArray($data) : null;
    }

    // Trouve un user par subscription Stripe
    public static function findByStripeSubscriptionId($subscriptionId) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT u.* FROM users u JOIN subscriptions s ON u.id = s.user_id WHERE s.stripe_subscription_id = ? LIMIT 1');
        $stmt->execute([$subscriptionId]);
        $data = $stmt->fetch();
        return $data ? self::fromArray($data) : null;
    }

    // Récupère tous les utilisateurs
    public static function getAll() {
        $db = (new Database())->pdo();
        $stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
        return array_map([self::class, 'fromArray'], $stmt->fetchAll());
    }

    // Anonymise les données d'un utilisateur (RGPD)
    public static function anonymize($id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('UPDATE users SET association = ?, email = ?, password = ? WHERE id = ?');
        $stmt->execute(['Utilisateur #' . $id, 'user' . $id . '@deleted.local', '', $id]);
    }
}
