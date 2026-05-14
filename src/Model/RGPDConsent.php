<?php
require_once __DIR__ . '/../Database.php';

class RGPDConsent {
    public $id;
    public $user_id;
    public $consented_at;

    public static function hasConsented($user_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('SELECT * FROM rgpd_consents WHERE user_id = ? LIMIT 1');
        $stmt->execute([$user_id]);
        return (bool)$stmt->fetch();
    }

    public static function create($user_id) {
        $db = (new Database())->pdo();
        $stmt = $db->prepare('INSERT INTO rgpd_consents (user_id) VALUES (?)');
        $stmt->execute([$user_id]);
    }
}
