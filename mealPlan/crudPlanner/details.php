<?php
require_once "../../components/db_connect.php";


// Display Data
$result = mysqli_query($connect, "SELECT * FROM meal_plan");
$row = null;
if (isset($_GET['MealPlan'])) {
    $MealPlan = mysqli_real_escape_string($connect, $_GET['MealPlan']);
    $sql_query = "SELECT * FROM `meal_plan` WHERE id = $MealPlan ";
    $result = mysqli_query($connect, $sql_query);
    if (mysqli_num_rows($result) > 0) {
        $MealPlan = $_GET['MealPlan'];
        $sql_query = "SELECT * FROM `meal_plan` WHERE id = $MealPlan ";
        $result = mysqli_query($connect, $sql_query);
        $row = mysqli_fetch_assoc($result);
        $MealPlan = $row['id'];
        $user_id = $row['user_id'];
        $name = $row['name'];
        $meal = $row['meal'];
        $meal_date = $row['meal_date'];
        $meal_time = $row['meal_time'];
    } else {
        echo 'Something went wrong';
    }
}

$displayMealPlan = "";
if ($row) {
    $displayMealPlan = "
    <div class='card mb-3'>
        <div class='card-body'>     
            <h5 class='card-title'>Plan Name: {$row['name']}</h5>
            <p class='card-text'><strong>Meal:</strong> {$row['meal']}</p>
            <p class='card-text'><strong>Date:</strong> {$row['meal_date']}</p>
            <p class='card-text'><strong>Time:</strong> {$row['meal_time']}</p>
        </div>
    </div>";
} else {
    $displayMealPlan = "<p class='text-center'>Meal Plan not found.</p>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Meal Plan Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once "../../components/navbar.php"; ?>

    <div class="container mt-5">
        <div class="card" style="max-width: 600px;">
            <div class="card-header">
                <h3 class="mb-0">Meal Plan Details</h3>
            </div>
            <div class="card-body">
                <?php echo $displayMealPlan; ?>
                <a href="../planner.php" class="btn btn-secondary">Back to Planner</a>
            </div>
        </div>
    </div>
</body>

</html>