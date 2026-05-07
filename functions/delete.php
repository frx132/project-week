<?php
// DB Connection and session check
session_start();
require_once "../components/db_connect.php";

function showDeleteScreen($message, $redirect)
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Deleting...</title>
        <link rel="stylesheet" href="../css/main.css">
        <link rel="stylesheet" href="../css/delete.css?v=10">
        <meta http-equiv="refresh" content="2;url=<?= $redirect ?>">
    </head>

    <body>
        <div class="delete-screen">
            <h1><?= $message ?><span class="dots"></span></h1>
        </div>
    </body>

    </html>
<?php
    exit;
}

$backBtn = "../pages/landingPage.php";

if (!isset($_SESSION["adm"]) && !isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["adm"])) {
    $backBtn = "admin_dashboard.php";
} elseif (isset($_SESSION["user"])) {
    $backBtn = "user_dashboard.php";
}

$id = $_GET["id"] ?? "";
$type = $_GET["type"] ?? "";

if (!$id || !$type) {
    header("Location: $backBtn");
    exit;
}
$id = (int)$id;
// Only admins can delete users

if ($type == "user" && isset($_SESSION["adm"])) {
    $sql = "SELECT user_image FROM users WHERE id = {$id}";
    $res = mysqli_query($connect, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        if ($row["user_image"] != "avatar.jpg" && !empty($row["user_image"])) {
            @unlink("../pictures/{$row["user_image"]}");
        }

        $delSql = "DELETE FROM users WHERE id = {$id}";
        mysqli_query($connect, $delSql);
    }

    showDeleteScreen("Deleting user", "user_list.php");
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

    if (isset($_SESSION["adm"])) {
        showDeleteScreen("Deleting recipe", "admin_recipes.php");
    } else {
        showDeleteScreen("Deleting recipe", "../recipes/recipe.php");
    }
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

    showDeleteScreen("Deleting plan", "../mealPlan/planner.php");
}

header("Location: $backBtn");
exit;
