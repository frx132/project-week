<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
    exit;
}
if (isset($_SESSION['adm'])) {
    header("Location: ../functions/admin_dashboard.php");
    exit;
}
require_once "../components/db_connect.php";

$recipeid = $_GET['recipeid'];
$sql_query = "SELECT * FROM `recipes` WHERE id = $recipeid ";
$result = mysqli_query($connect, $sql_query);


if (isset($_GET['recipeid']) && !empty($_GET['recipeid'])) {
    if (mysqli_num_rows($result) > 0) {
        $recipe = mysqli_fetch_assoc($result);
        if ($recipe['recipe_picture']) {
            unlink("../pictures/{$recipe['recipe_picture']}");
        }
        $sql_delete = "DELETE FROM `recipes` WHERE id = $recipeid ";
        $result_delete = mysqli_query($connect, $sql_delete);

        echo "<div class='alert alert-success'>The recipe has been deleted</div>";
        header("refresh: 3; url=recipe.php");
    }
} else {
    echo "<div class='alert alert-danger'>Something went wrong</div>";
}
