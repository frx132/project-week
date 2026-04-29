<?php
session_start();
// if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
//     header("Location: ../functions/login.php");
//     exit;
// }
// if (isset($_SESSION['user'])) {
//     header("Location: ../functions/user_home.php");
//     exit;
// }

require_once "../components/db_connect.php";
