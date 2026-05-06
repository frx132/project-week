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
//Example for Mealplan, if none is available
$Mealplan = $connect->prepare("SELECT name FROM meal_plan WHERE id = ? AND user_id = ?");
$Mealplan->bind_param("ii", $plan_id, $user_id);
$Mealplan->execute();
$result = $Mealplan->get_result();
$planData = $result->fetch_assoc();
$planname = $planData['name'] ?? "No Name added yet";


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
                        <input type="text" name="plan_name" class="form-control" placeholder="Sports Prep" required>
                    </div>


                    <!-- Table -->
                    <div class=container mt-5>
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
                                                        "</strong>" : '<small class="text-muted">Recipie</small>' ?>

                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- Back Button -->
                    <a onclick="history.go(-1); return false;" class="btn btn-secondary">Back</a>

                    <!-- Delete Mealplan -->
                    <a href="crudPlanner/delete.php?id=<?= $meal_plan_id ?>&type=plan"
                        class="btn btn-danger"> Delete Mealplan
                    </a>
                </div>
            </div>

</body>

</html>