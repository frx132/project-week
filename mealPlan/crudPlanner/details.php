<?php

require_once "../../components/db_connect.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
if (!$user_id) {
    die("Zugriff verweigert.");
}

// If no Planner 
$plan_id = $_GET['id'] ?? null;
if (!$plan_id) {
    header("Location: planner.php");
    exit();
}

//Example for Mealplan, if none is available
$Mealplan = $connect->prepare("SELECT name FROM meal_plan WHERE id = ? AND user_id = ?");
$Mealplan->bind_param("ii", $plan_id, $user_id);
$Mealplan->execute();
$result = $Mealplan->get_result();
$planData = $result->fetch_assoc();
$planname = $planData['name'] ?? "Unknown";


// Gets Data from Database
$readMealplan = "SELECT mpr.meal_date, mpr.meal_time, r.title, r.id as recipe_id, r.description 
          FROM meal_plan_recipe mpr 
          JOIN recipes r ON mpr.recipe_id = r.id 
          WHERE mpr.meal_plan_id = ? 
          ORDER BY FIELD(mpr.meal_date, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
                   FIELD(mpr.meal_time, 'Breakfast', 'Lunch', 'Dinner', 'Snack')";


$connection = $connect->prepare($readMealplan);
$connection->bind_param("i", $plan_id);
$connection->execute();
$result = $connection->get_result();


// Displays the Data
$myPlan = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Wir nutzen [], um mehrere Einträge pro Slot zu erlauben
        $myPlan[$row['meal_date']][$row['meal_time']][] = $row['title'];
    }
}

// Time vars
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Details: <?= htmlspecialchars($planname) ?></title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Plan name: <span class="text"><?= htmlspecialchars($planname) ?></span></h2>

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
                                    <?php if (isset($myPlan[$day][$meal_time])): ?>
                                        <?php foreach ($myPlan[$day][$meal_time] as $title): ?>
                                            <div class="mb-1">
                                                <strong><?= htmlspecialchars($title) ?></strong>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <small class="text-muted">Empty</small>
                                    <?php endif; ?>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a onclick="history.go(-1); return false;" class="btn btn-secondary">Back</a>
    </div>
</body>

</html>