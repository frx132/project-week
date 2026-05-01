<?php
session_start();
require_once "../components/db_connect.php";
if (!isset($_SESSION["adm"]) && !isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
$backBtn = "../Pages/landingPage.php";
if (isset($_SESSION["adm"])) {
    $backBtn = "admin_dashboard.php";
} elseif (isset($_SESSION["user"])) {
    $backBtn = "user_dashboard.php";
}
$id = $_GET["id"] ?? '';
$type = $_GET["type"] ?? '';
if (!$id || !$type) {
    header("Location: $backBtn");
    exit;
}

if ($type == "user" && isset($_SESSION["adm"])) { // Only admins can delete users
    $sql = "SELECT user_image FROM users WHERE id = {$id}";
    $res = mysqli_query($connect, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if ($row["user_image"] != "avatar.png" && !empty($row["user_image"])) {
            @unlink("../pictures/{$row["user_image"]}");
        }
        $delSql = "DELETE FROM users WHERE id = {$id}";
        mysqli_query($connect, $delSql);
    }
    header("Location: admin_dashboard.php");
    exit;

} elseif ($type == "recipe") {
    $sql = "SELECT recipe_picture, author_id FROM recipes WHERE id = {$id}";
    $res = mysqli_query($connect, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $current_user = isset($_SESSION["adm"]) ? $_SESSION["adm"] : $_SESSION["user"];
        
        // Only allow deletion if admin OR if the user is the author
        if (isset($_SESSION["adm"]) || $current_user == $row["author_id"]) {
            if ($row["recipe_picture"] != "default_recipe.jpg" && !empty($row["recipe_picture"])) {
                @unlink("../pictures/{$row["recipe_picture"]}");
            }
            $delSql = "DELETE FROM recipes WHERE id = {$id}";
            mysqli_query($connect, $delSql);
        }
    }
    header("Location: $backBtn");
    exit;

} elseif ($type == "plan") {
    // Delete meal plan
    $sql = "SELECT user_id FROM meal_plan WHERE id = {$id}";
    $res = mysqli_query($connect, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $current_user = isset($_SESSION["user"]) ? $_SESSION["user"] : $_SESSION["adm"];
        
        if ($current_user == $row["user_id"]) {
            $delSql = "DELETE FROM meal_plan WHERE id = {$id}";
            mysqli_query($connect, $delSql);
        }
    }
    header("Location: ../mealPlan/planner.php");
    exit;
}

header("Location: $backBtn");
exit;
?>
