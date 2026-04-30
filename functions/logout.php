<?php
session_start();
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['adm']);
    session_unset();
    session_destroy();
    echo "<h2>You are logged out<h2/>";
    header("refresh:3; url=../pages/landingPage.php");
    exit();
}
