<?php
//class handling the logick of a cookie has 2 function for set and get the cookie.
class Cookie
{
    /**set the last login cookie wich will show the time of last login.
     * Params:
     * $cookieName store the cookie name
     * $cookieValue store the value of it in date.
     * $cookieExpire store the expire date
    */

    public static function setLastLoginCookie()
    {
        $cookieName = 'last_login';
        $cookieValue = date('Y-m-d H:i:s'); 
        $cookieExpire = time() + (7 * 24 * 60 * 60); // 7 days
        setcookie($cookieName, $cookieValue, $cookieExpire, "/");
    }
    //return the cookie of last login and null if there is not last cookie.
    public static function getLastLoginCookie(): ?string
    {
        return $_COOKIE['last_login'] ?? null;
    }
}