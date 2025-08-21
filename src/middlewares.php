<?php
$middleware_mapping = [
    'auth' => function () {
        if(!isset($_SESSION['user_id'])){
            header("Location: /login");
            exit();
        }
    },
    'guest' => function () {
        if(isset($_SESSION['user_id'])){
            header("Location: /");
            exit();
        }
    }
];