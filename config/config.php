<?php
    session_start();

    require 'vendor/autoload.php';

    ini_set("pcre.jit", "0");
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    // Trimming the development url to function with the router
    $path = $_SERVER['REQUEST_URI'];
    $server_len = strlen($path);
    $_SERVER['REQUEST_URI'] = substr($path, 10, $server_len);

    if($_SERVER['REQUEST_URI'] === "/login" || $_SERVER['REQUEST_URI'] === "/login?action="):
        session_destroy();
    endif;

    //print_r($_COOKIE);

?>