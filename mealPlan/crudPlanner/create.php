<?php
require_once "../../components/db_connect.php";

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];
$user_id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;


// Fetch meal plans for the logged-in user
if ($user_id) {
    $mealPlanQuery = "SELECT * FROM meal_plan WHERE user_id = $user_id";
    $mealPlanResult = mysqli_query($connect, $mealPlanQuery);
} else {
    echo "User not logged in.";
    exit();
}
// 
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $res = mysqli_query($connect, "SELECT * FROM meal_plan WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
}

// Create a Meal plan
if (isset($_POST["create_Mealplan"])) {
    $planName  = mysqli_real_escape_string($connect, $_POST["plan_name"]);
    $meal = mysqli_real_escape_string($connect, $_POST["meal"]);
    $meal_date = mysqli_real_escape_string($connect, $_POST["meal_date"]);
    $meal_time = mysqli_real_escape_string($connect, $_POST["meal_time"]);
    $user_Id   = $_SESSION["user"] ?? $_SESSION["adm"];
    // Insert meal plan into the database

    $sql = "INSERT INTO `meal_plan` (user_id, name, meal, meal_date, meal_time) 
            VALUES ('$user_Id', '$planName', '$meal', '$meal_date', '$meal_time')";
    if (mysqli_query($connect, $sql)) {
        header("Location: planner.php?success=1");
        exit();
    } else {
        echo "Failed to create meal plan: " . mysqli_error($connect);
    }
}


// Select a Recipie for the meal plan
if (isset($_POST["add_to_plan"])) {
    $planName = cleanInputs($_POST["plan_name"]);
    $user_Id = $_SESSION["user"] ?? $_SESSION["adm"];
    $recipeIds = $_POST["recipe_ids"];
    $meal = $_POST["meal"];
    $plan_id = $_POST["plan_id"];
    $meal_date = $_POST["meal_date"];
    $meal_time = $_POST["meal_time"];
}

// Recipie Loading Data
$resultRecipes = mysqli_query($connect, "SELECT id, title FROM recipes");
$recipeOptions = "";
while ($rowR = mysqli_fetch_assoc($resultRecipes)) {
    $recipeOptions .= "<option value='{$rowR['id']}'>{$rowR['title']}</option>";
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Mealplan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="card mx-auto">
            <div class="card-header">
                <h3 class="mb-0">Create a Mealplan</h3>
            </div>
            <!-- Name -->
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Name of your Plan</label>
                        <input type="text" name="plan_name" class="form-control" placeholder="Diet Plan" required>
                    </div>
                    <!-- Meal -->
                    <div class="mb-3">
                        <label for="recipe_id" class="form-label">Select Recipe</label>
                        <select class="form-select" id="recipe_id" name="recipe_id" required>
                            <option value="" disabled selected>Choose a recipe...</option>
                            <?= $recipeOptions ?>
                        </select>
                    </div>
            </div>
        </div>

        <!-- Date  Select Weekday-->
        <div class="mb-3">
            <label for="weekday">Select Weekday</label>
            <select name="meal_date" class="form-control" required>
                <option value="" selected disabled>Choose a day...</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>


        <!-- Mealtime -->
        <div class="mb-3">
            <label for="meal_time">Meal Time</label>
            <select name="meal_time" class="form-select" required>
                <?php foreach ($times as $t): ?>
                    <option value="<?= $t ?>"><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>


        <!-- Meal Plan -->
        <div class="container mt-5">
            <h2 class="mb-4">Weekly Meal Planner</h2>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Day</th> <?php foreach ($times as $meal): ?>
                            <th><?= $meal ?></th> <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($days as $day): ?>
                        <tr>
                            <td class="fw-bold"><?= $day ?></td>
                            <?php foreach ($times as $meal): ?>
                                <td>
                                    <div class="p-2 border rounded bg-light" style="min-height: 50px;">
                                        <small class="text-muted">Empty</small>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
    </div>


    <div class="mt-4 d-flex gap-2">
        <button type="submit" name="create_Mealplan" class="btn btn-primary">Save Plan</button>
        <button type="reset" class="btn btn-secondary">Reset Plan</button>
    </div>
    </div>
    </div>
</body>

</html>