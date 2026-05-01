<?php
session_start();
require_once "../components/db_connect.php";

if (!isset($_SESSION["adm"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
$sqlUsers = "SELECT * FROM users WHERE role != 'Admin'";
$resultUsers = mysqli_query($connect, $sqlUsers);


$layout = "";

if (mysqli_num_rows($resultUsers) > 0) {
    while ($userRow = mysqli_fetch_assoc($resultUsers)) {
        $pic = !empty($userRow["user_image"]) ? $userRow["user_image"] : "default_user.png";
        $layout .= "<div class='col'>
           <div class='card h-100'>
               <img src='../pictures/{$pic}' class='card-img-top' alt='User Image' style='object-fit: cover; height: 200px;'>
               <div class='card-body d-flex flex-column'>
               <h5 class='card-title'>{$userRow["first_name"]} {$userRow["last_name"]}</h5>
               <p class='card-text'>{$userRow["email"]}</p>
               <div class='mt-auto'>
                   <a href='update.php?id={$userRow["id"]}&type=user' class='btn btn-warning'>Update</a>
               </div>
           </div>
       </div>
     </div>";
    }
} else {
    $layout .= "<p>No users found!</p>";
}

$sqlRecipes = "SELECT * FROM recipes";
$resultRecipes = mysqli_query($connect, $sqlRecipes);
$layoutRecipes = "";

if (mysqli_num_rows($resultRecipes) > 0) {
    while ($recipeRow = mysqli_fetch_assoc($resultRecipes)) {
        $rPic = !empty($recipeRow["recipe_picture"]) ? $recipeRow["recipe_picture"] : "default_recipe.jpg";
        $layoutRecipes .= "<div class='col'>
           <div class='card h-100'>
               <img src='../pictures/{$rPic}' class='card-img-top' alt='Recipe Image' style='object-fit: cover; height: 200px;'>
               <div class='card-body d-flex flex-column'>
               <h5 class='card-title'>{$recipeRow["title"]}</h5>
               <p class='card-text'>Category: {$recipeRow["category"]}<br>Type: {$recipeRow["dietary_type"]}</p>
               <div class='mt-auto'>
                   <a href='update.php?id={$recipeRow["id"]}&type=recipe' class='btn btn-warning'>Update</a>
               </div>
           </div>
        </div>
      </div>";
    }
} else {
    $layoutRecipes .= "<p>No recipes found!</p>";
}

mysqli_close($connect);





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<!-- Navbar -->

<body>
    <?php include "../components/navbar.php"; ?>

    <!-- Body -->
    <h2 class="text-center my-4">Welcome <?= $row["first_name"]  ?></h2>

    <div class="container mb-5">
        <h3 class="mb-3">Manage Users</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?= $layout ?>
        </div>


        <h3 class="mb-3">Manage Recipes</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?= $layoutRecipes ?>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>