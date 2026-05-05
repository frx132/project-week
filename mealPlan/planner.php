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
    <?php include "../components/head.php"; ?>
    <title>Meal Planner</title>
    <link rel="stylesheet" href="../css/landingPage.css">
    <link rel="stylesheet" href="../css/main.css">


</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <?php include "../components/navbar.php"; ?>

    <div class="container mt-4 mb-5 flex-grow-1">

        <!-- Plan Manager Section -->
        <div class="plan-manager">
            <div class="row align-items-center g-3">


                <!-- Plan Selector -->

                <div class="col-md-4">
                    <form method="get" action="planner.php" class="d-flex align-items-center gap-2">
                        <label for="plan_id" class="fw-bold mb-0 text-nowrap">Current Plan:</label>
                        <select class="form-select" name="plan_id" id="plan_id" onchange="this.form.submit()">
                            <?php foreach ($userPlans as $plan): ?>
                                <option value="<?= $plan['id'] ?>" <?= $plan['id'] == $mealPlanId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($plan['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <!-- Edit and Delete Plan Buttons -->
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editPlanModal">
                        <i class="fa-solid fa-pen"></i> Rename
                    </button>

                    <!-- Using the new delete.php script! -->
                    <a href="../functions/delete.php?id=<?= $mealPlanId ?>
                    &type=plan" class="btn btn-outline-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this entire meal plan?');">
                        <i class="fa-solid fa-trash"></i> Delete Plan
                    </a>
                </div>

                <!-- Create New Plan -->
                <div class="col-md-5">
                    <form method="post" action="planner.php" class="input-group">
                        <input type="text" name="plan_name" class="form-control form-control-sm" placeholder="New plan name..." required>
                        <button type="submit" name="create_plan" class="btn btn-dark btn-sm"><i class="fa-solid fa-plus"></i> Create</button>
                    </form>
                </div>


            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold" style="font-family: 'Poppins', sans-serif;"><?= htmlspecialchars($activePlanName) ?></h2>
            </div>
        </div>

        <div class="row g-4">
            <!-- Add to Plan Form -->
            <div class="col-lg-3">
                <div class="planner-card">
                    <h4 class="mb-4">Add a Meal</h4>
                    <form method="post" action="planner.php?plan_id=<?= $mealPlanId ?>">
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
                                                    <a href="planner.php?plan_id=<?= $mealPlanId ?>&delete_mpr=<?= $schedule[$day][$time]['mpr_id'] ?>" class="btn-remove" title="Remove"><i class="fa-solid fa-xmark"></i></a>
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

    <!-- Edit Plan Modal -->
    <div class="modal fade" id="editPlanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rename Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="planner.php?plan_id=<?= $mealPlanId ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Plan Name</label>
                            <input type="text" class="form-control" name="edit_plan_name" value="<?= htmlspecialchars($activePlanName) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="edit_plan" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../components/footer.php"; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>