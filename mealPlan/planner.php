<?php
session_start();
require_once "../components/db_connect.php";

$userId = isset($_SESSION["user"]) ? $_SESSION["user"] : $_SESSION["adm"];

// --- 1. Handle Plan Creation ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_plan"])) {
    $new_plan_name = mysqli_real_escape_string($connect, trim($_POST["plan_name"]));
    if (!empty($new_plan_name)) {
        $sqlInsertPlan = "INSERT INTO meal_plan (user_id, name) VALUES ({$userId}, '$new_plan_name')";
        mysqli_query($connect, $sqlInsertPlan);
        $new_plan_id = mysqli_insert_id($connect);
        header("Location: planner.php?plan_id={$new_plan_id}");
        exit;
    }
}

// --- 2. Fetch all plans for this user ---
$sqlAllPlans = "SELECT * FROM meal_plan WHERE user_id = {$userId} ORDER BY created_at DESC";
$resAllPlans = mysqli_query($connect, $sqlAllPlans);

// If user has NO plans, create a default one
if (mysqli_num_rows($resAllPlans) == 0) {
    $defaultPlanName = "My Weekly Plan";
    $sqlInsertDefault = "INSERT INTO meal_plan (user_id, name) VALUES ({$userId}, '$defaultPlanName')";
    mysqli_query($connect, $sqlInsertDefault);
    // Refetch
    $resAllPlans = mysqli_query($connect, $sqlAllPlans);
}

$userPlans = [];
while ($row = mysqli_fetch_assoc($resAllPlans)) {
    $userPlans[] = $row;
}

// Determine active plan
$mealPlanId = null;
$activePlanName = "";

if (isset($_GET["plan_id"])) {
    // Validate that the requested plan belongs to the user
    $requestedId = (int)$_GET["plan_id"];
    foreach ($userPlans as $plan) {
        if ($plan["id"] == $requestedId) {
            $mealPlanId = $plan["id"];
            $activePlanName = $plan["name"];
            break;
        }
    }
}

// If no valid plan_id was found, default to the first one
if ($mealPlanId === null) {
    $mealPlanId = $userPlans[0]["id"];
    $activePlanName = $userPlans[0]["name"];
}

// --- 3. Handle Plan Editing ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_plan"])) {
    $edited_name = mysqli_real_escape_string($connect, trim($_POST["edit_plan_name"]));
    if (!empty($edited_name)) {
        $sqlUpdatePlan = "UPDATE meal_plan SET name = '$edited_name' WHERE id = {$mealPlanId} AND user_id = {$userId}";
        mysqli_query($connect, $sqlUpdatePlan);
        header("Location: planner.php?plan_id={$mealPlanId}");
        exit;
    }
}

// --- 4. Handle POST for adding to meal plan ---
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
        header("Location: planner.php?plan_id={$mealPlanId}");
        exit;
    }
}

// --- 5. Handle MPR deletion ---
if (isset($_GET["delete_mpr"])) {
    $mpr_id = $_GET["delete_mpr"];
    $delSql = "DELETE FROM meal_plan_recipe WHERE id = {$mpr_id} AND meal_plan_id = {$mealPlanId}";
    mysqli_query($connect, $delSql);
    header("Location: planner.php?plan_id={$mealPlanId}");
    exit;
}

// --- 6. Fetch recipes for the dropdown ---
$sqlRecipes = "SELECT id, title FROM recipes";
$resRecipes = mysqli_query($connect, $sqlRecipes);
$recipeOptions = "";
while ($row = mysqli_fetch_assoc($resRecipes)) {
    $recipeOptions .= "<option value='{$row['id']}'>{$row['title']}</option>";
}

// --- 7. Fetch current meal plan schedule ---
$scheduleSql = "
    SELECT mpr.id as mpr_id, mpr.meal_date, mpr.meal_time, r.title, r.id as recipe_id 
    FROM meal_plan_recipe mpr
    JOIN recipes r ON mpr.recipe_id = r.id
    WHERE mpr.meal_plan_id = {$mealPlanId}
";
$resSchedule = mysqli_query($connect, $scheduleSql);

// Initialize schedule grid



?>

<!DOCTYPE html>
<html lang="en">


<head>
    <?php include "../components/head.php"; ?>
    <title>Meal Planner</title>
    <link rel="stylesheet" href="../css/landingPage.css">
    <link rel="stylesheet" href="../css/main.css">


</head>

<body>

    <!-- Navbar -->
    <?php include "../components/navbar.php"; ?>

    <!-- Meal Plan Schedule -->
    <?php include "crudPlanner/create.php"; ?>


    <!-- Footer -->
    <?php include "../components/footer.php"; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>