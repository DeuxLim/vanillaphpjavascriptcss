<?php

function dd($dump){
    echo "<pre>";
    print_r($dump);
    echo "<pre>";

    die();
}

function d($dump){
    echo "<pre>";
    print_r($dump);
    echo "<pre>";
}

function view($viewFile, $data = []){
    extract($data);
    $path = VIEW_PATH . $viewFile . ".php";
    include($path);
}