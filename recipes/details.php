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

if (!isset($_GET['recipeid']) || empty($_GET['recipeid'])) {
    header("Location: recipe.php");
    exit();
}

$user_id = isset($_SESSION['adm']) ? $_SESSION['adm'] : $_SESSION['user'];
$recipe_id = $_GET['recipeid'];

$sql_query = "SELECT recipes.*, users.first_name, users.last_name 
              FROM recipes 
              LEFT JOIN users ON author_id = users.id 
              WHERE recipes.id = $recipe_id";

$result = mysqli_query($connect, $sql_query);

$display_data = "";
$edit_recipe = "";

if (mysqli_num_rows($result) > 0) {
    $recipe = mysqli_fetch_assoc($result);

    $display_data = "
    

    <div class='card m-3'>
        <div class='card-body'>
            <img src='../pictures/$recipe[recipe_picture]' class='card-img-top' alt='$recipe[title]'>

            <h3 class='card-title mt-3'>$recipe[title]</h3>
            <p class='card-text'>$recipe[description]</p>

            <p><strong>Preparation time:</strong> $recipe[prep_time]</p>
            <p><strong>Difficulty:</strong> $recipe[difficulty]</p>
            <p><strong>Servings: </strong>$recipe[servings]</p>
            <p><strong>Ingredients:</strong> $recipe[ingredients]</p>
            <p><strong>Dietary type:</strong> $recipe[dietary_type]</p>
            <p><strong>Category:</strong> $recipe[category]</p>
            <p><strong>Author:</strong> $recipe[first_name] $recipe[last_name]</p>
            <p><strong>Updated at:</strong> $recipe[updated_at]</p>
    ";


    if ($user_id == $recipe['author_id']) {
        $display_data .= "
            <div class='d-flex justify-content-end gap-2 mt-3'>
                <a href='edit.php?id={$recipe['id']}' class='btn btn-dark'>Edit</a>
                <a href='delete.php?id={$recipe['id']}' class='btn btn-outline-dark'>Delete</a>
            </div>
        ";
    }

    $display_data .= "
        </div>
    </div>
    ";
} else {
    header("Location: recipe.php");
    exit();
}
$display_data .= "
    <div class='d-flex justify-content-end gap-2 mt-3 flex-wrap'>
";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../components/head.php"; ?>
    <title>Recipe details</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/recipe.css">
    <link rel="stylesheet" href="../css/details.css">
</head>

<body>

    <main class="container py-5">
        <div class='container mt-3'>
            <a href='/functions/user_dashboard.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?= $display_data ?>
                <?= $edit_recipe ?>
            </div>
        </div>
    </main>
</body>

</html>