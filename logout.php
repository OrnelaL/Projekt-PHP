<?php

session_start();


$_SESSION = array();

// Fshin dhe cookises
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Shkaterro sesionin
session_destroy();

// Redirect
header("Location: login.php");
exit;
