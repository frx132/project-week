<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
    exit;
}
if (isset($_SESSION['user'])) {
    header("Location: ../pages/UserDashboard.php");
    exit;
}
require_once "../components/db_connect.php";


if (!isset($_GET['recipeid']) || empty($_GET['recipeid'])) {
    header("Location: recipe.php");
    exit();
}

// var_dump($_GET);

$user_id = $_SESSION['user'];
$recipe_id = $_GET['recipeid'];

$sql_query = "SELECT recipes.*, users.first_name, users.last_name FROM `recipes` LEFT JOIN users on author_id=users.id WHERE recipes.id= $recipe_id ";
$result = mysqli_query($connect, $sql_query);




$display_data = "";
if (mysqli_num_rows($result) > 0) {
    $recipe = mysqli_fetch_assoc($result);
    // var_dump($recipe);

    if ($user_id == $recipe['author_id']) {
        // After creating the crud for recipes, add test the code below
        $display_data .= "
    <div class='container mt-3'>
        <a href='create.php' class='btn btn-success'>Create new recipe</a>
        <a href='../functions/user_home.php' class='btn btn-primary'>User homepage</a>
    </div>
        <div class='card m-3'>
            <div class='card-body'>
            <img src='../pictures/$recipe[recipe_picture]' class='card-img-top' alt='$recipe[title]'>
            <h5 class='card-title'>$recipe[title]</h5>
            <p class='card-text'>$recipe[description] </p>
            <p>Preparation time: $recipe[prep_time] 
            <p>Difficulty: $recipe[difficulty]</p> 
            <p>$recipe[servings] Servings </p>
            <p>Ingredients: $recipe[ingredients] </p>
            <p>Dietary type: $recipe[dietary_type] </p>
            <p>Category: $recipe[category] </p>
            <p>Author: $recipe[first_name] $recipe[last_name] </p>
            <p>Updated at: $recipe[updated_at] </p>
            <a href='edit.php' class='btn btn-warning'>Edit</a>
            <a href='delete.php' class='btn btn-danger'>Delete</a>

            </div>
            

        </div>
      <div class='container'>
        <a href='recipe.php' class='btn btn-primary'>Go back</a>
        </div>    ";
    } else {
        $display_data .= "
           <div class='container mt-3'>
        <a href='create.php' class='btn btn-success'>Create new recipe</a>
        <a href='../functions/user_home.php' class='btn btn-primary'>User homepage</a>
    </div>
        <div class='card m-3'>
            <div class='card-body'>
            <img src='../pictures/$recipe[recipe_picture]' class='card-img-top' alt='$recipe[title]'>
            <h5 class='card-title'>$recipe[title]</h5>
            <p class='card-text'>$recipe[description] </p>
            <p>Preparation time: $recipe[prep_time] 
            <p>Difficulty: $recipe[difficulty]</p> 
            <p>$recipe[servings] Servings </p>
            <p>Ingredients: $recipe[ingredients] </p>
            <p>Dietary type: $recipe[dietary_type] </p>
            <p>Category: $recipe[category] </p>
            <p>Author: $recipe[first_name] $recipe[last_name] </p>
            <p>Updated at: $recipe[updated_at] </p>
            </div>

        </div>
        <div class='container'>
        <a href='recipe.php' class='btn btn-primary'>Go back</a>
        </div>
        
    ";
    }
} else {
    $display_data = "The recipe you are looking for is not found, please get in contact with us for more information.";
    header("Location: recipe.php");
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="row">
        <div class="col col-md-6 ">
            <?= $display_data ?>
        </div>
    </div>
</body>

</html>