<?php
class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(array $user)
    {
        self::start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
    }
    public static function logout()
    {
        self::start();
        session_unset();
        session_destroy();
    }
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }
    public static function getCurrentUserId(): ?int
    {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }
}