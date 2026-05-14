<?php
require_once __DIR__ . '/Model/User.php';

class Auth {
    public static function login($email, $password) {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            session_regenerate_id(true);
            return $user;
        }
        return null;
    }

    public static function logout() {
        session_unset();
        session_destroy();
    }

    public static function user() {
        if (!empty($_SESSION['user_id'])) {
            return User::findById($_SESSION['user_id']);
        }
        return null;
    }

    public static function check() {
        return self::user() !== null;
    }
}
