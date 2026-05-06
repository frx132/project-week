<?php
require_once "../../components/db_connect.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];
$user_id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
if (!$user_id) {
    echo "User not logged in.";
    exit();
}
// Check the Plan from DB Mealplan
$checkPlan = mysqli_query($connect, "SELECT id FROM meal_plan WHERE user_id = $user_id LIMIT 1");
if (mysqli_num_rows($checkPlan) > 0) {
    $planRow = mysqli_fetch_assoc($checkPlan);
    $meal_plan_id = $planRow['id'];
} else {
    mysqli_query($connect, "INSERT INTO meal_plan (user_id, name) VALUES ($user_id, 'My Weekly Plan')");
    $meal_plan_id = mysqli_insert_id($connect);
}

// Create Mealplan and push Data to DB 
if (isset($_POST["create_Mealplan"])) {
    $recipe_id = mysqli_real_escape_string($connect, $_POST["recipe_id"]);
    $meal_date = mysqli_real_escape_string($connect, $_POST["meal_date"]);
    $meal_time = mysqli_real_escape_string($connect, $_POST["meal_time"]);
    $sql = "INSERT INTO `meal_plan_recipe` (recipe_id, meal_plan_id, meal_date, meal_time) VALUES ('$recipe_id', '$meal_plan_id', '$meal_date', '$meal_time')";
    if (mysqli_query($connect, $sql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    }
}

$query = "SELECT mpr.*, r.title FROM meal_plan_recipe mpr JOIN recipes r ON mpr.recipe_id = r.id WHERE mpr.meal_plan_id = $meal_plan_id";
$result = mysqli_query($connect, $query);
$myPlan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $myPlan[$row['meal_date']][$row['meal_time']] = $row['title'];
}

// Shows Recipies Recipies
$resultRecipes = mysqli_query($connect, "SELECT id, title FROM recipes");
$recipeOptions = "";
while ($rowR = mysqli_fetch_assoc($resultRecipes)) {
    $recipeOptions .= "<option value='{$rowR['id']}'>{$rowR['title']}</option>";
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create a Mealplan</title>
</head>

<body>

    <div class="container mt-5">
        <form method="post">
            <div class="card mx-auto">

                <div class="card-header">
                    <h3 class="mb-0">Create a Mealplan</h3>
                </div>

                <!-- Name of Plan -->
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name of your Plan</label>
                        <input type="text" name="plan_name" class="form-control" placeholder="Diet Plan" required>
                    </div>
                    <!-- Recipies -->
                    <div class="mb-3">
                        <label for="recipe_id" class="form-label">Select Recipe</label>
                        <select class="form-select" id="recipe_id" name="recipe_id" required>
                            <option value="" disabled selected>Choose a recipe...</option>
                            <?= $recipeOptions ?>
                        </select>
                    </div>
                    <!-- Weekday to Select -->
                    <div class="mb-3">
                        <label>Select Weekday</label>
                        <select name="meal_date" class="form-select" required>
                            <option value="" selected disabled>Choose a day...</option>
                            <?php foreach ($days as $d): ?><option value="<?= $d ?>"><?= $d ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Time to Select -->
                    <div class="mb-3">
                        <label>Meal Time</label>
                        <select name="meal_time" class="form-select" required>
                            <option value="" selected disabled>Choose a Mealtime...</option>
                            <?php foreach ($times as $t): ?><option value="<?= $t ?>"><?= $t ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" name="create_Mealplan" class="btn btn-primary">Add to Plan</button>
                    <button type="reset" name="Reset_Mealplan" class="btn btn-danger">Reset Plan</button>
                </div>
            </div>

            <div class="container mt-5">
                <h2 class="mb-4">Weekly Meal Planner</h2>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Day</th><?php foreach ($times as $m): ?><th><?= $m ?></th><?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($days as $day): ?>
                            <tr>
                                <td class="fw-bold"><?= $day ?></td>
                                <?php foreach ($times as $meal_time): ?>
                                    <td>
                                        <div class="p-2 border rounded bg-light" style="min-height: 50px;">
                                            <?= isset($myPlan[$day][$meal_time]) ? "<strong>" . htmlspecialchars($myPlan[$day][$meal_time]) . "</strong>" : '<small class="text-muted">Empty</small>' ?>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</body>

</html>