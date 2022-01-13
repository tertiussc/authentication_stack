<?php

require_once("functions/init.php");

if (isset($_COOKIE['save_login'])) {
    
    // physically remove the cookie by putting the expiry time in the past
    setcookie('save_login', '', time() - 60);
}

session_destroy();
redirect("login.php");
