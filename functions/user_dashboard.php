<?php
// DB Connection and session check
session_start();

if (isset($_SESSION["adm"])) {
    header("Location: admin_dashboard.php");
}
// var_dump($_SESSION);
require_once "../components/db_connect.php";
$user_id = $_SESSION["user"];
$sqlUser = "SELECT * FROM users WHERE id = {$user_id}";
$sqlRecipes = "SELECT * FROM recipes WHERE author_id = {$user_id}";
$sqlMealPlans = "SELECT * FROM meal_plan WHERE user_id = {$user_id}";
$sqlmeal_plan_recipe = "SELECT * FROM meal_plan_recipe WHERE meal_plan_id = {$user_id}";
$resUser = mysqli_query($connect, $sqlUser);
$userRow = mysqli_fetch_assoc($resUser);
$resMealPlans = mysqli_query($connect, $sqlMealPlans);

// User's recipes

$resultRecipes = mysqli_query($connect, $sqlRecipes);




$layoutRecipes = "";
if (mysqli_num_rows($resultRecipes) > 0) {
    while ($recipeRow = mysqli_fetch_assoc($resultRecipes)) {
        $rPic = !empty($recipeRow["recipe_picture"]) ? $recipeRow["recipe_picture"] : "default_recipe.jpg";
        $layoutRecipes .= "<div class='col'>
           <div class='card h-100 shadow-sm'>
               <img src='../pictures/{$rPic}' class='card-img-top' alt='Recipe Image' style='object-fit: cover; height: 200px;'>
               <div class='card-body d-flex flex-column'>
              
               <h5 class='card-title'>{$recipeRow["title"]}</h5>
               <p class='card-text text-muted mb-1'><small>Category: {$recipeRow["category"]}</small></p>
               <p class='card-text text-muted'><small>Type: {$recipeRow["dietary_type"]}</small></p>
              
               <div class='mt-auto'>
                   <a href='../recipes/details.php?recipeid={$recipeRow["id"]}' class='btn btn-outline-dark btn-sm'>View</a>
                   <a href='../recipes/edit.php?id={$recipeRow["id"]}&type=recipe' class='btn btn-warning btn-sm'>Update</a>
               </div>
           </div>
        </div>
      </div>";
    }
} else {
    $layoutRecipes .= "<div class='col-12'><p class='text-muted'>You haven't created any recipes yet.</p></div>";
}

$userPic = !empty($userRow["user_image"]) ? $userRow["user_image"] : "avatar.png";

mysqli_close($connect);


// User's meal plans
$layoutMealPlans = "";
if (mysqli_num_rows($resMealPlans) > 0) {
    while ($planRow = mysqli_fetch_assoc($resMealPlans)) {
        $layoutMealPlans .= "
        <div class='col'>      
           <div class='card h-100 shadow-sm'>
               <div class='card-body d-flex flex-column'>
               <h5 class='card-title'>
               {$planRow["name"]}</h5>
               <p class='card-text text-muted mb-1'><small>
               Plan name: {$planRow["name"]}</small>
               </p>
               <p class='card-text text-muted'><small>
               Created on: {$planRow["created_at"]}
               </small></p>
               <div class='mt-auto'>

                   <a href='../mealPlan/crudPlanner/details.php?id={$planRow["id"]}' class='btn btn-outline-dark btn-sm'>View</a>
                   <a href='../mealPlan/crudPlanner/update.php?id={$planRow["id"]}' class='btn btn-warning btn-sm'>Update</a>
               </div>
           </div>
        </div>
      </div>";
    }
} else {
    $layoutMealPlans .= "<div class='col-12'><p class='text-muted'>You haven't created any meal plans yet.</p></div>";
}



?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Dashboard - Meal Planner</title>
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dashboard.css">

</head>

<body class="bg-light">
    <!-- Navbar -->

    <main class="container mb-5">
        <!-- Profile Header -->
        <div class="profile-header shadow-sm d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start">
            <img src="../pictures/<?= $userPic ?>" alt="Profile Picture" class="profile-img">
            <div>
                <h2 class="fw-bold mb-1">Welcome back, <?= htmlspecialchars($userRow["first_name"]) ?>!</h2>
                <p class="text-muted mb-0"><i class="fa-regular fa-envelope me-2"></i><?= htmlspecialchars($userRow["email"]) ?></p>

            </div>
            <div class="container d-flex justify-content-end ">
                <a href="../functions/logout.php?logout" class="btn btn-outline-danger btn-sm me-3">Logout</a>
                <a href="../functions/update.php?id=<?= $user_id ?>&type=user" class="btn btn-outline-dark btn-sm">Edit Profile</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <h4 class="mb-3">Quick Actions</h4>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="../mealPlan/planner.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <h5 class="fw-bold">Meal Planner</h5>
                    <p class="text-muted mb-0 small">Organize your weekly meals</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="../recipes/recipe.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-book-open"></i></div>
                    <h5 class="fw-bold">Browse Recipes</h5>
                    <p class="text-muted mb-0 small">Discover new meal ideas</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="../recipes/create.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-plus-circle"></i></div>
                    <h5 class="fw-bold">Create Recipe</h5>
                    <p class="text-muted mb-0 small">Share your culinary creations</p>
                </a>
            </div>
        </div>

        <!-- My Recipes -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h4 class="mb-0">My Recipes</h4>
            <a href="../recipes/create.php" class="btn btn-sm btn-dark">+ New Recipe</a>
        </div>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?= $layoutRecipes ?>
        </div>
        <!-- My Meal Plans -->
        <div class="container my-5 d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h4 class="mb-3">My Meal Plans</h4>
            <a href="../mealPlan/crudPlanner/create.php" class="btn btn-sm btn-dark">+ New Meal Plan</a>
        </div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            <?= $layoutMealPlans ?>
        </div>
    </main>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>