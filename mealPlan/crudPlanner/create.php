<?php
require_once "../../components/db_connect.php";


// Create a Meal plan
if (isset($_POST["create_plan"])) {
    $planName = cleanInputs($_POST["plan_name"]);
    $userId = $_SESSION["user"] ?? $_SESSION["adm"];
    $recipeIds = $_POST["recipe_ids"];
    $meal = $_POST["meal"];
    $plan_id = $_POST["plan_id"];
    $meal_date = $_POST["meal_date"];
    $meal_time = $_POST["meal_time"];
    $readMealPlan = "SELECT * FROM meal_plan WHERE user_id = $user_Id ORDER BY meal_date, meal_time";
    $row = mysqli_fetch_assoc(mysqli_query($connect, $readMealPlan));


    // Insert meal plan into the database
    $sql = "INSERT INTO `meal_plan` (user_id, name, meal, meal_date, meal_time) 
VALUES ('$userId', '$planName', '$meal', '$meal_date', '$meal_time')";
    if (mysqli_query($connect, $sql)) {
        header("Location: planner.php");
        echo "Meal plan created successfully!";
        return $readMealPlan;
    } else {
        echo "Error creating meal plan: " . mysqli_error($connect);
    }
}

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
    <?php include_once "../../components/navbar.php"; ?>



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
            <!-- Delete Button -->
            <a href="/delete.php?id=<?= $row["id"] ?>" class="btn btn-danger  class=" btn btn-danger">Delete Meal Plan</a>
            <!-- Back Button -->
            <a href="../planner.php" class="btn btn-secondary">Back</a>
        </form>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>