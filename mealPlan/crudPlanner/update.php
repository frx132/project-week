<?php
session_start();
require_once "../../components/db_connect.php";

$user_Id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;

// Time vars
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


?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->



    <!-- Table -->
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
    <a onclick="history.go(-1); return false;" class="btn btn-secondary">Back</a>

</body>

</html>