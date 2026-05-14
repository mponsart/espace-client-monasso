<?php
require_once __DIR__ . '/../Database.php';

class Payment {
    public $id;
    public $user_id;
    public $stripe_payment_id;
    public $amount;
    public $currency;
    public $status;
    public $paid_at;
    public $created_at;

    public static function findByUserId($user_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM payments WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll();
        return array_map([self::class, 'fromArray'], $results);
    }

    public static function fromArray($data) {
        $p = new self();
        foreach ($data as $k => $v) {
            if (property_exists($p, $k)) $p->$k = $v;
        }
        return $p;
    }
}
