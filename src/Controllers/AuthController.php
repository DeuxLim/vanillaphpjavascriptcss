<?php

namespace App\Controllers;

use App\Request;
use App\Database;
use App\Session;
use Exception;
use PDOException;
use PDO;

class AuthController
{
    private $DB;
    
    protected const ALLOWED_INPUTS = [
        "firstName",
        "lastName",
        "email",
        "password",
        "confirmPassword",
        "rememberMe"
    ];

    public function __construct()
    {
        $this->DB = Database::instance()->getConnection();
    }

    public function showLoginForm()
    {
        view('authentication/login', []);
    }

    public function showRegistrationForm()
    {
        view('authentication/register');
    }

    public function register(Request $request)
    {
        $requestData = $request->post();
        $errors = [];

        // Validate inputs
        $expectedInputs = [
            "firstName",
            "lastName",
            "email",
            "password",
            "confirmPassword"
        ];

        $requestData = array_intersect_key(
            $requestData,
            array_flip(self::ALLOWED_INPUTS)
        );

        foreach ($requestData as $key => $data) {
            $data = trim($data);

            switch ($key) {
                case "firstName":
                    if (empty($data)) {
                        $errors['firstName'] = "First name is required.";
                    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $data)) {
                        $errors['firstName'] = "First name must only contain letters, spaces, or hyphens.";
                    }
                    break;

                case "lastName":
                    if (empty($data)) {
                        $errors['lastName'] = "Last name is required.";
                    } elseif (!preg_match("/^[a-zA-Z\s\-]+$/", $data)) {
                        $errors['lastName'] = "Last name must only contain letters, spaces, or hyphens.";
                    }
                    break;

                case "email":
                    if (empty($data)) {
                        $errors['email'] = "Email is required.";
                    } elseif (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = "Invalid email format.";
                    }
                    break;

                case "password":
                    if (empty($data)) {
                        $errors['password'] = "Password is required.";
                    } elseif (strlen($data) < 8) {
                        $errors['password'] = "Password must be at least 8 characters.";
                    }
                    break;

                case "confirmPassword":
                    if (empty($data)) {
                        $errors['confirmPassword'] = "Please confirm your password.";
                    } elseif ($data !== ($requestData['password'] ?? null)) {
                        $errors['confirmPassword'] = "Passwords do not match.";
                    }
                    break;
            }
        }

        // Save user, Extract values safely from $requestData
        if (empty($errors)) {
            $firstName = $requestData['firstName'];
            $lastName  = $requestData['lastName'];
            $email     = $requestData['email'];
            $password  = $requestData['password'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->DB->prepare(
                "INSERT INTO users (first_name, last_name, email, password) 
                VALUES (:first_name, :last_name, :email, :password)"
            );

            try {
                $stmt->execute([
                    ':first_name' => $firstName,
                    ':last_name'  => $lastName,
                    ':email'      => $email,
                    ':password'   => $hashedPassword
                ]);

                $userid = $this->DB->lastInsertId();

                if ($userid) {
                    // Success
                    $_SESSION['success_message'] = 'Registration successful! Please log in.';
                    header("Location: /login");
                    exit();
                } else {
                    throw new Exception("Failed to create user");
                }
            } catch (PDOException $e) {
                error_log("DB Error: " . $e->getMessage());
                $errors[] = "Registration failed. Please try again.";
            }
        }

        // Handle errors
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header("Location: /register");
            exit();
        }
    }

    public function login(Request $request)
    {
        $requestData = $request->post();
        $errors = [];

        $requestData = array_intersect_key(
            $requestData,
            array_flip(self::ALLOWED_INPUTS)
        );

        foreach ($requestData as $key => $data) {
            $data = trim($data);

            switch ($key) {
                case "email":
                    if (empty($data)) {
                        $errors['email'] = "Email is required.";
                    } elseif (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                        $errors['email'] = "Invalid email format.";
                    }
                    break;

                case "password":
                    if (empty($data)) {
                        $errors['password'] = "Password is required.";
                    }
                    break;
            }
        }

        if (empty($errors)) {
            $query = $this->DB->prepare("SELECT * FROM users WHERE email = :email");
            $query->execute(['email' => $requestData['email']]);
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // check if password matches
                if (password_verify($requestData['password'], $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'];
                } else {
                    $errors['user'] = "Invalid email or password.";
                }
            } else {
                $errors['user'] = "Invalid email or password.";
            }

            // Handle Remember Token
            if(!empty($requestData['rememberMe']) && $user && empty($errors)){
                // Generate random token
                $token = bin2hex(random_bytes(32));
                
                // Hash token for DB (safer than storing plain token)
                $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    
                $query = $this->DB->prepare("UPDATE users SET remember_token = :token WHERE id = :userid");
                $query->execute([
                    ":token" => $hashedToken,
                    ":userid" => $user['id']
                ]);

                setcookie("remember_me", $user['id'] . ":" . $token, time() + (86400 * 30), '/');
            }
        }
        
        // Handle errors
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header("Location: /login");
            exit();
        }

        header("Location: /dashboard");
    }

    public function logout()
    {
        // Unset all session variables
        $_SESSION = [];

        // Delete session cookie (simplified)
        setcookie(session_name(), '', time() - 3600, '/');

        // Delete remember me token
        setcookie("remember_me", "", time() - 3600, '/');
        $query = $this->DB->prepare("UPDATE users SET remember_token = NULL WHERE id = :userid");
        $query->execute([":userid" => Session::get("user_id")]);

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header("Location: /login");
        exit();
    }
}
