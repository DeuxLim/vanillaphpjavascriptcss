<?php

namespace App\Controllers;

Class AuthController {

    public function showLoginForm(){
        view('authentication/login', []);
    }

    public function login(){

    }

    public function showRegistrationForm(){
        view('authentication/register');
    }

    public function register(){

    }
    
}