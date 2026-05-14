<?php
require_once __DIR__ . '/../Database.php';

class Notification {
    public $id;
    public $user_id;
    public $type;
    public $message;
    public $is_read;
    public $created_at;

    public static function findByUserId($user_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll();
        return array_map([self::class, 'fromArray'], $results);
    }

    public static function fromArray($data) {
        $n = new self();
        foreach ($data as $k => $v) {
            if (property_exists($n, $k)) $n->$k = $v;
        }
        return $n;
    }
}
