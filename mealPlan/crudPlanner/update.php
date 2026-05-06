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
// Load Data for Plan
$queryEntries = "SELECT mpr.*, r.title FROM meal_plan_recipe mpr 
                 JOIN recipes r ON mpr.recipe_id = r.id 
                 WHERE mpr.meal_plan_id = $meal_plan_id";
$resultEntries = mysqli_query($connect, $queryEntries);
$myPlan = [];
while ($row = mysqli_fetch_assoc($resultEntries)) {
    $myPlan[$row['meal_date']][$row['meal_time']] = $row['title'];
}

// Logic to save/update the plan name
if (isset($_POST['save_plan_changes'])) {
    $newPlanName = mysqli_real_escape_string($connect, $_POST['plan_name']);
    $targetPlanId = (int)$_POST['meal_plan_id'];

    $updateSql = "UPDATE meal_plan SET name = ? WHERE id = ? AND user_id = ?";
    $stmt = $connect->prepare($updateSql);
    $stmt->bind_param("sii", $newPlanName, $targetPlanId, $user_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="card mx-auto">

                <div class="card-header">
                    <h3 class="mb-0">Edit your Mealplan</h3>
                </div>

                <div class="card-body">
                    <input type="hidden" name="meal_plan_id" value="<?= $meal_plan_id ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Plan Name</label>
                        <div class="input-group">
                            <input type="text" name="plan_name" class="form-control"
                                value="<?= htmlspecialchars($planname) ?>" required>
                        </div>
                    </div>

                    <div class="mt-5">
                        <span class="text"><?= htmlspecialchars($planname) ?></span>
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
                                                    <?= isset($myPlan[$day][$meal_time]) ? "<strong>"
                                                        . htmlspecialchars($myPlan[$day][$meal_time]) .
                                                        "</strong>" : '<small class="text-muted">Recipe</small>' ?>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" name="save_plan_changes" class="btn btn-success">
                        Save Plan Name
                    </button>

                    <a onclick="history.go(-1); return false;" class="btn btn-secondary">Back</a>

                    <a href="crudPlanner/delete.php?id=<?= $meal_plan_id ?>&type=plan"
                        class="btn btn-danger" onclick="return confirm('Delete whole plan?')">
                        Delete Mealplan
                    </a>
                </div>
            </div>
        </form>
    </div>

</body>

</html>