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
     ";
    } else {
        $display_data .= "
          <div class='container d-flex gap-2'>
    <a href='create.php' class='btn btn-dark'>Create new recipe</a>
    <a href='../functions/user_home.php' class='btn btn-outline-dark'>User homepage</a>
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
       <div class='container d-flex justify-content-end'>
    <a href='recipe.php' class='btn btn-primary'>Go back</a>
</div>
        
    ";
    }
} else {
    $display_data = "The recipe you are looking for is not found, please get in contact with us for more information.";
    header("Location: recipe.php");
}




?>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?= $display_data ?>
        </div>
    </div>
</div>