<?php
// DB Connection and session check
require_once "../components/db_connect.php";

// Initialize variables
$Mealplan = [];
$recipie = [];
$dayofweek = [];
$mealTime = [];
$time = ["Breakfast", "Lunch", "Dinner"];
$day = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
$user_Id = $_SESSION["user"] ?? $_SESSION["adm"];
$resultRecipes = mysqli_query($connect, "SELECT * FROM recipes");
$readMealPlan = "SELECT * FROM meal_plan WHERE user_id = $user_Id ORDER BY meal_date, meal_time";

// Function to delete a meal from the plan
if (isset($_GET["delete"])) {
    $mealPlan_id = $_GET["delete"];
    $sqlDelete = "DELETE FROM meal_plan WHERE id = $mealPlan_id";
    if (mysqli_query($connect, $sqlDelete)) {
        header("Location: planner.php");
        exit();
    } else {
        echo "Error deleting meal: " . mysqli_error($connect);
    }
}


// Update a meal in the plan
if (isset($_POST["update_meal"])) {
    $plan_id = $_POST["plan_id"];
    $meal_date = $_POST["meal_date"];
    $meal_time = $_POST["meal_time"];
    $meal = $_POST["meal"];
    $sqlUpdate = "UPDATE meal_plan SET meal = '$meal' WHERE id = $plan_id AND meal_date = '$meal_date' AND meal_time = '$meal_time'";
    if (mysqli_query($connect, $sqlUpdate)) {
        header("Location: planner.php");
        exit();
    } else {
        echo "Error updating meal: " . mysqli_error($connect);
    }
}




// Read meal plans for the user
$readMealPlan = "SELECT * FROM meal_plan";
$resultMealPlan = mysqli_query($connect, $readMealPlan);



// Delete a meal plan
if (isset($_GET["delete_plan"])) {
    $plan_id = $_GET["delete_plan"];
    $sqlDeletePlan = "DELETE FROM meal_plan WHERE id = $plan_id";
    if (mysqli_query($connect, $sqlDeletePlan)) {
        header("Location: planner.php");
        exit();
    } else {
        echo "Error deleting meal plan: " . mysqli_error($connect);
    }
}

// Fetch recipes for the user
$layoutRecipes = "";
if (mysqli_num_rows($resultRecipes) > 0) {
    while ($recipeRow = mysqli_fetch_assoc($resultRecipes)) {
        $rPic = !empty($recipeRow["recipe_picture"]) ?
            $recipeRow["recipe_picture"] : "default_recipe.jpg";

        $layoutRecipes = "
    <div class='col'>
        <div class='card h-100'>
            <img src='../pictures/{$rPic}' class='card-img-top' alt='{$recipeRow["title"]}'>
            <div class='card-body d-flex flex-column'>
                <h5 class='card-title'>{$recipeRow["title"]}</h5>
                <div class='mt-auto'>
                    <a href='update.php?id={$recipeRow["id"]}&type=recipe' class='btn btn-warning'>Update</a>
                </div>
            </div>
        </div>
    </div>";
    }
} else {
    $layoutRecipes = "<p class='text-center'>No recipes found.</p>";
}






// 
?>




<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mealplan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <?php include_once "../components/navbar.php"; ?>



    <!-- Edit Meal PLanner -->
    <div class="container mt-5 mb-5">
        <h1 class="text-center mb-4">Edit Meal Plan</h1>
        <form method="post" autocomplete="off">

            <!-- User ID -->
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <input type="number" class="form-control" id="user_id" name="user_id" value="
                <?= htmlspecialchars($row["user_id"] ?? '') ?>" required>
            </div>

            <!-- User name -->
            <div class="mb-3">
                <label for="user_name" class="form-label">User Name</label>
                <input type="text" class="form-control" id="user_name" name="user_name" value="
                <?= htmlspecialchars($row["user_name"] ?? '') ?>" required>
            </div>

            <!-- Start Date -->
            <div class="mb-3 mt-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="
                <?= htmlspecialchars($row["start_date"] ?? '') ?>" required>
            </div>

            <!-- End Date -->
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="
                <?= htmlspecialchars($row["end_date"] ?? '') ?>" required>
            </div>



            <button name="update_plan" type="submit" class="btn btn-warning">Update Meal Plan</button>
            <button type="delete" class="btn btn-danger">Delete Plans</button>

            <a href="<?= $backBtn ?>" class="btn btn-secondary">Back</a>
        </form>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>