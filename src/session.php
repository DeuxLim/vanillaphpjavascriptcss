<?php

namespace App;

class Session
{
    private static ?Session $instance = null;

    /* 
    * Get instance or create one
    */
    public function Instance()
    {
        if(self::$instance === NULL){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /* 
    * Start session
    */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* 
    * Get a session data 
    */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /* 
    * Set a session data 
    */
    public function set(string $key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /* 
    * Get the current user
    */
    public static function getCurrentUser()
    {
        return $_SESSION['user_id'] ?? null;
    }
}
