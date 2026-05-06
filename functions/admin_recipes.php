<?php
session_start();
require_once "../components/db_connect.php";

if (!isset($_SESSION["adm"])) {
    header("Location: login.php");
    exit;
}


$sqlUsers = "SELECT * FROM users WHERE role != 'Admin'";
$resultUsers = mysqli_query($connect, $sqlUsers);




$sqlRecipes = "SELECT * FROM recipes";
$resultRecipes = mysqli_query($connect, $sqlRecipes);
$layoutRecipes = "";

if (mysqli_num_rows($resultRecipes) > 0) {
    while ($recipeRow = mysqli_fetch_assoc($resultRecipes)) {
        $rPic = !empty($recipeRow["recipe_picture"]) ? $recipeRow["recipe_picture"] : "default_recipe.png";
        $layoutRecipes .= "<div class='col'>
           <div class='card h-100'>
               <img src='../pictures/{$rPic}' class='card-img-top' alt='Recipe Image' style='object-fit: cover; height: 200px;'>
               <div class='card-body d-flex flex-column'>
               <h5 class='card-title'>{$recipeRow["title"]}</h5>
               <p class='card-text'>Category: {$recipeRow["category"]}<br>Type: {$recipeRow["dietary_type"]}</p>
               <div class='mt-auto'>
                   <a href='update.php?id={$recipeRow["id"]}&type=recipe' class='btn btn-warning'>Update</a>
                   <a href='delete.php?id={$recipeRow["id"]}&type=recipe' class='btn btn-danger'>Delete</a>

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
    <?php include "../components/head.php"; ?>
    <title>Admin Dashboard - Recipes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dashboard.css">

</head>
<!-- Navbar -->

<body class="bg-light">

    <!-- Body -->
    <div class="container d-flex justify-content-start align-items-start mt-5">
        <a href='/functions/admin_dashboard.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
    </div>
    <div class="container my-5">

        <div class="container mb-5">
            <h3 class="mb-3">Manage Recipes</h3>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?= $layoutRecipes ?>
            </div>
        </div>

        <div class="container mb-5">
            <!-- Quick Actions -->
            <h4 class="mb-3">Quick Actions</h4>
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <a href="../functions/user_list.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                        <div class="action-icon"><i class="fa-solid fa-plus-circle"></i></div>
                        <h5 class="fw-bold">Users</h5>
                        <p class="text-muted mb-0 small">View, edit and delete all users</p>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="../mealPlan/planner.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                        <div class="action-icon"><i class="fa-solid fa-calendar-days"></i></div>
                        <h5 class="fw-bold">Meal Planners</h5>
                        <p class="text-muted mb-0 small">View, edit and delete all planners</p>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>