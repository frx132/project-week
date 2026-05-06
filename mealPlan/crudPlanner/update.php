<?php
session_start();
require_once "../../components/db_connect.php";

$user_Id = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
$row = null;
$times = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $sql = "SELECT * FROM meal_plan WHERE id = $id AND user_id = $user_Id";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Plan not found or you don't have permission to edit it.";
        exit();
    }
} else {
    header("Location: ../planner.php");
    exit();
}

if (isset($_POST["update_meal"])) {
    $plan_id   = mysqli_real_escape_string($connect, $_POST["id"]);
    $meal_name = mysqli_real_escape_string($connect, $_POST["user_id"]); // Name des Plans
    $meal_text = mysqli_real_escape_string($connect, $_POST["meal"]);      // Das Gericht
    $meal_date = mysqli_real_escape_string($connect, $_POST["created_at"]);
    $meal_time = mysqli_real_escape_string($connect, $_POST["name"]);

    $sqlUpdate = "UPDATE meal_plan 
                  SET name = '$meal_name', meal = '$meal_text', meal_date = '$meal_date', meal_time = '$meal_time' 
                  WHERE id = $plan_id AND user_id = $user_Id";

    if (mysqli_query($connect, $sqlUpdate)) {
        header("Location: ../planner.php?status=updated");
        exit();
    } else {
        echo "Error updating plan: " . mysqli_error($connect);
    }
}
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <title>Update Meal Plan</title>
    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include_once "../../components/navbar.php"; ?>

    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-warning">
                <h3 class="mb-0">Update Meal Plan</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Name of Meal Plan</label>
                        <input type="text" name="meal_name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Meal</label>
                        <input type="text" name="meal" class="form-control" value="<?= htmlspecialchars($row['meal']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="meal_date" class="form-control" value="<?= $row['meal_date'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time Slot</label>
                        <select name="meal_time" class="form-select">
                            <?php foreach ($times as $t): ?>
                                <option value="<?= $t ?>" <?= ($row['meal_time'] == $t) ? 'selected' : '' ?>><?= $t ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
              

                    <div class="d-grid gap-2">
                        <button name="update_meal" type="submit" class="btn btn-warning">Save</button>
                        <a href="../planner.php" class="btn btn-secondary">Back to Plan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>