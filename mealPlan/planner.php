<?php
session_start();

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../functions/login.php");
    exit;
}

require_once "../components/db_connect.php";

$userId = isset($_SESSION["user"]) ? $_SESSION["user"] : $_SESSION["adm"];

// 1. Get or create meal_plan for the user
$sqlPlan = "SELECT * FROM meal_plan WHERE user_id = {$userId}";
$resPlan = mysqli_query($connect, $sqlPlan);

if (mysqli_num_rows($resPlan) == 0) {
    // create a meal plan
    $planName = "My Weekly Plan";
    $sqlInsertPlan = "INSERT INTO meal_plan (user_id, name) VALUES ({$userId}, '$planName')";
    mysqli_query($connect, $sqlInsertPlan);
    $mealPlanId = mysqli_insert_id($connect);
} else {
    $planRow = mysqli_fetch_assoc($resPlan);
    $mealPlanId = $planRow["id"];
}

// 2. Handle POST for adding to meal plan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_plan"])) {
    $recipe_id = $_POST["recipe_id"];
    $meal_date = $_POST["meal_date"];
    $meal_time = $_POST["meal_time"];

    if (!empty($recipe_id) && !empty($meal_date) && !empty($meal_time)) {
        // Check if there's already an entry for this exact day and time
        $checkExist = "SELECT id FROM meal_plan_recipe WHERE meal_plan_id = {$mealPlanId} AND meal_date = '$meal_date' AND meal_time = '$meal_time'";
        $resExist = mysqli_query($connect, $checkExist);
        if (mysqli_num_rows($resExist) > 0) {
            // update existing
            $existing = mysqli_fetch_assoc($resExist);
            $updateSql = "UPDATE meal_plan_recipe SET recipe_id = {$recipe_id} WHERE id = {$existing['id']}";
            mysqli_query($connect, $updateSql);
        } else {
            // insert new
            $insertSql = "INSERT INTO meal_plan_recipe (recipe_id, meal_plan_id, meal_date, meal_time) VALUES ({$recipe_id}, {$mealPlanId}, '$meal_date', '$meal_time')";
            mysqli_query($connect, $insertSql);
        }
    }
}

// 3. Handle deletion
if (isset($_GET["delete_mpr"])) {
    $mpr_id = $_GET["delete_mpr"];
    // ensure the mpr belongs to the user's meal plan
    $delSql = "DELETE FROM meal_plan_recipe WHERE id = {$mpr_id} AND meal_plan_id = {$mealPlanId}";
    mysqli_query($connect, $delSql);
    header("Location: planner.php");
    exit;
}

// 4. Fetch all recipes for the dropdown
$sqlRecipes = "SELECT id, title FROM recipes";
$resRecipes = mysqli_query($connect, $sqlRecipes);
$recipeOptions = "";
while ($row = mysqli_fetch_assoc($resRecipes)) {
    $recipeOptions .= "<option value='{$row['id']}'>{$row['title']}</option>";
}

// 5. Fetch current meal plan schedule
$scheduleSql = "
    SELECT mpr.id as mpr_id, mpr.meal_date, mpr.meal_time, r.title, r.id as recipe_id 
    FROM meal_plan_recipe mpr
    JOIN recipes r ON mpr.recipe_id = r.id
    WHERE mpr.meal_plan_id = {$mealPlanId}
";
$resSchedule = mysqli_query($connect, $scheduleSql);

// Initialize a structure to hold the schedule easily
$schedule = [];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];

foreach ($days as $d) {
    foreach ($times as $t) {
        $schedule[$d][$t] = null;
    }
}

while ($row = mysqli_fetch_assoc($resSchedule)) {
    $schedule[$row['meal_date']][$row['meal_time']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meal Planner</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/landingPage.css">

</head>

<body class="bg-light">



    <?php include "../components/navbar.php"; ?>
    <div class="container mt-5 mb-5 flex-grow-1">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-5 fw-bold" style="font-family: 'Poppins', sans-serif;">Weekly Meal Planner</h1>
                <p class="text-muted">Organize your meals for the week easily.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Add to Plan Form -->
            <div class="col-lg-3">
                <div class="planner-card">
                    <h4 class="mb-4">Add a Meal</h4>
                    <form method="post" action="planner.php">
                        <div class="mb-3">
                            <label for="recipe_id" class="form-label">Select Recipe</label>
                            <select class="form-select" id="recipe_id" name="recipe_id" required>
                                <option value="" disabled selected>Choose a recipe...</option>
                                <?= $recipeOptions ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="meal_date" class="form-label">Day of the Week</label>
                            <select class="form-select" id="meal_date" name="meal_date" required>
                                <?php foreach ($days as $day): ?>
                                    <option value="<?= $day ?>"><?= $day ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="meal_time" class="form-label">Meal Time</label>
                            <select class="form-select" id="meal_time" name="meal_time" required>
                                <?php foreach ($times as $time): ?>
                                    <option value="<?= $time ?>"><?= $time ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="add_to_plan" class="btn btn-warning w-100 fw-bold">Add to Plan</button>
                    </form>
                </div>
            </div>

            <!-- Weekly Grid -->
            <div class="col-lg-9">
                <div class="planner-card overflow-auto">
                    <table class="table table-bordered align-middle m-0" style="min-width: 800px;">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <?php foreach ($days as $day): ?>
                                    <th><?= substr($day, 0, 3) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($times as $time): ?>
                                <tr>
                                    <td class="row-header"><?= $time ?></td>
                                    <?php foreach ($days as $day): ?>
                                        <td>
                                            <div class="grid-cell">
                                                <?php if ($schedule[$day][$time]): ?>
                                                    <span class="meal-title"><?= htmlspecialchars($schedule[$day][$time]['title']) ?></span>
                                                    <a href="planner.php?delete_mpr=<?= $schedule[$day][$time]['mpr_id'] ?>" class="btn-remove" title="Remove"><i class="fa-solid fa-xmark"></i></a>
                                                <?php else: ?>
                                                    <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                                <?php endif; ?>
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
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 w-100 mt-auto">
        <p class="mb-0">&copy; 2026 MealPlanner. All rights reserved.</p>
    </footer>

</body>

</html>