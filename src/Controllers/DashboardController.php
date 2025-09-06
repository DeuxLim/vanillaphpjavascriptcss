<?php

namespace App\Controllers;

use App\Database;
use App\Session;

Class DashboardController {

    private $DB;

    public function __construct()
    {
        $this->DB = Database::instance()->getConnection();
    }

    public function index()
    {
        // Get current user 
        $userId = Session::getCurrentUser();
        $query = $this->DB->prepare("SELECT first_name FROM users WHERE id = :userId");
        $query->execute([":userId" => $userId]);
        $user = $query->fetch();

        view('dashboard/home', [
            "user" => $user
        ]);
    }

}