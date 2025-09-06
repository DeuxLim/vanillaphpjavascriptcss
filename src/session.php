<?php

namespace App;

use App\Database;

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

        if(!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])){
            // Get the value
            list($userid, $token) = explode(":", $_COOKIE['remember_me']);

            $DB = Database::instance()->getConnection();
            $query = $DB->prepare("SELECT * FROM users WHERE id = :userid");
            $query->execute([
                ":userid" => $userid
            ]);
            $user = $query->fetch();

            if($user && password_verify($token, $user['remember_token'])){
                self::set("user_id", $user['id']);

                // Refresh token
                $token = bin2hex(random_bytes(32));                
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                $query = $DB->prepare("UPDATE users SET remember_token = :token WHERE id = :userid");
                $query->execute([
                    ":token" => $hashedToken,
                    ":userid" => $user['id']
                ]);

                setcookie("remember_me", $user['id'] . $token, time() + (86400 * 30), '/');
            }
        }
    }

    /* 
    * Get a session data 
    */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /* 
    * Set a session data 
    */
    public static function set(string $key, $value)
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
