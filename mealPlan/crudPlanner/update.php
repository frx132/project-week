<?php
session_start();
require_once "../../components/db_connect.php";

// Session
$user_id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];

if (!$user_id) {
    echo "User not logged in.";
    exit();
}

// Check the Plan from DB Mealplan
$checkPlan = mysqli_query($connect, "SELECT id, name FROM meal_plan WHERE user_id = $user_id LIMIT 1");
if (mysqli_num_rows($checkPlan) > 0) {
    $planRow = mysqli_fetch_assoc($checkPlan);
    $meal_plan_id = $planRow['id'];
    $planname = $planRow['name'];
} else {
    mysqli_query($connect, "INSERT INTO meal_plan (user_id, name) VALUES ($user_id, 'My Weekly Plan')");
    $meal_plan_id = mysqli_insert_id($connect);
    $planname = "My Weekly Plan";
}

// Logic to save/update the plan name
if (isset($_POST['save_plan_changes'])) {
    $newPlanName = mysqli_real_escape_string($connect, $_POST['plan_name']);
    $targetPlanId = (int)$_POST['meal_plan_id'];

    $updateSql = "UPDATE meal_plan SET name = ? WHERE id = ? AND user_id = ?";
    $stmt = $connect->prepare($updateSql);
    $stmt->bind_param("sii", $newPlanName, $targetPlanId, $user_id);
    $stmt->execute();

    if (isset($_POST['plan_data']) && is_array($_POST['plan_data'])) {
        $deleteOld = $connect->prepare("DELETE FROM meal_plan_recipe WHERE meal_plan_id = ?");
        $deleteOld->bind_param("i", $targetPlanId);
        $deleteOld->execute();

        $insertSql = "INSERT INTO meal_plan_recipe (meal_plan_id, recipe_id, meal_date, meal_time) VALUES (?, ?, ?, ?)";
        $insertStmt = $connect->prepare($insertSql);
        foreach ($_POST['plan_data'] as $day => $timeSlots) {
            foreach ($timeSlots as $time => $recipeId) {
                if (!empty($recipeId)) {
                    $recipeId = (int)$recipeId;
                    $insertStmt->bind_param("iiss", $targetPlanId, $recipeId, $day, $time);
                    $insertStmt->execute();
                }
            }
        }
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?success=updated");
    exit;
}

// Mealplan Display for Table 
$query = "SELECT mpr.*, r.title FROM meal_plan_recipe mpr JOIN recipes r ON mpr.recipe_id = r.id WHERE mpr.meal_plan_id = ?";
$data = $connect->prepare($query);
$data->bind_param("i", $meal_plan_id);
$data->execute();
$result = $data->get_result();

$myPlan = [];
while ($row = $result->fetch_assoc()) {
    $myPlan[$row['meal_date']][$row['meal_time']] = [
        'id' => $row['recipe_id'],
        'title' => $row['title']
    ];
}
// Storage 
$allRecipes = [];
// 
$resultRecipes = mysqli_query($connect, "SELECT id, title FROM recipes");
while ($rowR = mysqli_fetch_assoc($resultRecipes)) {
    $allRecipes[] = $rowR;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
    <?php include "../../components/head.php"; ?>

</head>

<body>

    <div class="container mt-5">
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="card mx-auto shadow">

                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Edit your Mealplan</h3>
                </div>

                <div class="card-body">
                    <input type="hidden" name="meal_plan_id" value="<?= $meal_plan_id ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Plan Name</label>
                        <input type="text" name="plan_name" class="form-control"
                            value="<?= htmlspecialchars($planname) ?>" required>
                    </div>

                    <div class="mt-4">
                        <h5 class="text-secondary">Current Schedule: <?= htmlspecialchars($planname) ?></h5>
                        <table class="table table-bordered table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Day</th><?php foreach ($times as $m): ?><th><?= $m ?></th><?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($days as $day): ?>
                                    <tr>
                                        <td class="fw-bold text-center align-middle"><?= $day ?></td>
                                        <?php foreach ($times as $meal_time): ?>
                                            <td>
                                                <select name="plan_data[<?= $day ?>][<?= $meal_time ?>]" class="form-select form-select-sm">
                                                    <option value="">-- No Recipe --</option>
                                                    <?php
                                                    foreach ($allRecipes as $recipe):
                                                        // Prüfen, ob dieses Rezept bereits für diesen Slot gespeichert ist
                                                        $selected = (isset($myPlan[$day][$meal_time]) && $myPlan[$day][$meal_time]['id'] == $recipe['id']) ? "selected" : "";
                                                    ?>
                                                        <option value="<?= $recipe['id'] ?>" <?= $selected ?>>
                                                            <?= htmlspecialchars($recipe['title']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <!-- Save Plan -->
                        <button type="submit" name="save_plan_changes" class="btn btn-success">Save Plan Name</button>
                        <!-- Back button -->
                        <a href="../planner.php" class="btn btn-secondary">Back to Planner</a>
                        <!-- Delete plan  -->
                        <a href="delete.php?id=<?= $meal_plan_id ?>&type=plan"
                            class="btn btn-outline-danger" onclick="return confirm('Delete whole plan?')">
                            Delete Entire Plan
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

</body>

</html>