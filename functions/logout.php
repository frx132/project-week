<?php
require_once "../components/db_connect.php";
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['adm']);
    session_unset();
    session_destroy();
    header("Location: ../Pages/landingPage.php?logout=success");
    exit();
}
