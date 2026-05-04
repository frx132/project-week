<?php
session_start();

if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
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
    <div class='container mt-3'>
    <a href='recipe.php' class='btn btn-outline-dark'>Close</a>
</div>

    <div class='card m-3'>
        <div class='card-body'>
            <img src='../pictures/$recipe[recipe_picture]' class='card-img-top' alt='$recipe[title]'>

            <h5 class='card-title'>$recipe[title]</h5>
            <p class='card-text'>$recipe[description]</p>

            <p>Preparation time: $recipe[prep_time]</p>
            <p>Difficulty: $recipe[difficulty]</p>
            <p>$recipe[servings] Servings</p>
            <p>Ingredients: $recipe[ingredients]</p>
            <p>Dietary type: $recipe[dietary_type]</p>
            <p>Category: $recipe[category]</p>
            <p>Author: $recipe[first_name] $recipe[last_name]</p>
            <p>Updated at: $recipe[updated_at]</p>
    ";


    if ($user_id == $recipe['author_id']) {
        $display_data .= "
            <div class='d-flex gap-2 mt-3'>
                <a href='edit.php?recipeid={$recipe['id']}' class='btn btn-dark'>Edit</a>
                <a href='delete.php?recipeid={$recipe['id']}' class='btn btn-outline-dark'>Delete</a>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/recipe.css">
    <link rel="stylesheet" href="../css/details.css">
</head>

<body>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?= $display_data ?>
                <?= $edit_recipe ?>
            </div>
        </div>
    </main>
</body>

</html>