<?php
session_start();

$_SESSION = array();

session_destroy();

//briše remember me
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
}

header("Location: index.php");
exit;
