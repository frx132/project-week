<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../components/db_connect.php"; // Pfad anpassen falls nötig

$user_id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
if (!$user_id) {
    die("Zugriff verweigert. Bitte einloggen.");
}

// Plan-ID aus der URL holen
$plan_id = $_GET['id'] ?? null;

if (!$plan_id) {
    header("Location: planner.php");
    exit();
}

$checkOwner = mysqli_query($connect, "SELECT * FROM meal_plan WHERE id = $plan_id AND user_id = $user_id");
if (mysqli_num_rows($checkOwner) == 0) {
    die("Plan nicht gefunden oder keine Berechtigung.");
}
$planData = mysqli_fetch_assoc($checkOwner);

$query = "SELECT mpr.meal_date, mpr.meal_time, r.title, r.id as recipe_id, r.description 
          FROM meal_plan_recipe mpr 
          JOIN recipes r ON mpr.recipe_id = r.id 
          WHERE mpr.meal_plan_id = $plan_id 
          ORDER BY FIELD(mpr.meal_date, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
                   FIELD(mpr.meal_time, 'Breakfast', 'Lunch', 'Dinner', 'Snack')";

$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Details: <?= htmlspecialchars($planData['name']) ?></title>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Details: <?= htmlspecialchars($planData['name']) ?></h1>
            <a href="planner.php" class="btn btn-outline-secondary">Back to Planner</a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Geplante Mahlzeiten</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-secondary me-2"><?= $row['meal_date'] ?></span>
                                        <span class="badge bg-info text-dark me-3"><?= $row['meal_time'] ?></span>
                                        <strong><?= htmlspecialchars($row['title']) ?></strong>
                                    </div>
                                    <a href="recipe_details.php?id=<?= $row['recipe_id'] ?>" class="btn btn-sm btn-outline-primary">Rezept ansehen</a>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Noch keine Mahlzeiten für diesen Plan hinzugefügt.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>


        </div>
    </div>
</body>

</html>