<?php
// DB Connection and session check
session_start();

require_once "../components/db_connect.php";

// SQL queries for admin dashboard
$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}";
$sqlUsers = "SELECT * FROM users WHERE role != 'Admin'";
$sqlRecipes = "SELECT * FROM recipes";
$sqlMealPlans = "SELECT * FROM `meal_plan`";

// var_dump($sqlUsers);
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
$resultUsers = mysqli_query($connect, $sqlUsers);
$resultRecipes = mysqli_query($connect, $sqlRecipes);
$resultMealPlans = mysqli_query($connect, $sqlMealPlans);


// Users for admin dashboard 
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

// Recipies for admin dashboard 
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


// Meal plans for admin dashboard  
$layoutMealPlans = "";
if (mysqli_num_rows($resultMealPlans) > 0) {
    while ($Row = mysqli_fetch_assoc($resultMealPlans)) {
        $layoutMealPlans .= "
        <div class='col'>
           <div class='card h-100'>
               <div class='card-body d-flex flex-column'>
             

                <p class='card-text'>User ID: {$Row["user_id"]}
                </p>
                <p class='card-text'>Plan Name: {$Row["name"]}
                </p>
                
                
                <p class='card-text'>Start 
                Date: {$Row["created_at"]}
                </p>
                <div class='mt-auto'>
                     <a href='update.php?id={$Row["id"]}&type=plan' class='btn btn-warning'>Update</a>
                </div>
              
           </div>
        </div>
      </div>";
    }
} else {
    $layoutMealPlans .= "<p>No meal plans found!</p>";
}



mysqli_close($connect);
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dashboard.css">

</head>
<!-- Navbar -->
<?php include_once "../components/navbar.php"; ?>

<body class="bg-light">

    <!-- Body -->

    <h2 class="text-center my-4">Welcome <?= $row["first_name"]  ?></h2>
    <div class="container d-flex align-items-end justify-content-end">
        <a href="../functions/logout.php?logout" class="btn btn-outline-danger btn-sm ">Logout</a>
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
            <div class="col-md-4">
                <a href="../functions/admin_recipes.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-book-open"></i></div>
                    <h5 class="fw-bold">Edit all recipes</h5>
                    <p class="text-muted mb-0 small">View, edit and delete all recipes</p>
                </a>
            </div>

        </div>

        <!-- Manage Plans -->
        <h3 class="mb-3">Manage Plans</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?= $layoutMealPlans ?>
        </div>

    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>