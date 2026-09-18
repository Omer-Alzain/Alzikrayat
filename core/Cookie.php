<?php
class Cookie
{
    public static function setLastLoginCookie()
    {
        $cookieName = 'last_login';
        $cookieValue = date('Y-m-d H:i:s'); 
        $cookieExpire = time() + (7 * 24 * 60 * 60); // 7 days
        setcookie($cookieName, $cookieValue, $cookieExpire, "/");
    }

    public static function getLastLoginCookie(): ?string
    {
        return $_COOKIE['last_login'] ?? null;
    }
}