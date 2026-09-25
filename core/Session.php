<?php
//handel the user session logic 
// has 5 methods.
class Session
{
    //start the session if there is none so the session can store info about the user
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    //method recive a user metadata start a session and store his data.
    public static function login(array $user)
    {
        self::start();
        //create new session id .
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
    }
    /**
     * handel logout logic deleting and destroying the session.
     * Parameters:$params store cookie stteing
     */
    public static function logout()
    {
        self::start();
        //remove the data stored in the session.
        session_unset();
        //check if session store the session id in a cookie.
        if (ini_get("session.use_cookies")) {
            //get the session cookie setting
            $params = session_get_cookie_params();
            //deleting the session cookie.
            setcookie(session_name(), '', time() - 42000,$params["path"], $params["domain"],$params["secure"], $params["httponly"]);
        }
        //destroy or delete session data on the server
        session_destroy();
    }
    //checks if the user is logged in 
    //return a boolien.
    public static function isLoggedIn(): bool
    {
        self::start();
        //check if there is a value return true otherwise false.
        return isset($_SESSION['user_id']);
    }
    //retrive the user id of this session
    public static function getCurrentUserId(): ?int
    {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }
}