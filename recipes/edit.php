<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header("Location: ../functions/login.php");
    exit;
}

// add if directly go to update.php, redirect to index, because we need the id to get there

require_once "../components/db_connect.php";
