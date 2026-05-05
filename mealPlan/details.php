<?php
require_once "../components/db_connect.php";

$planId = $_GET["id"] ?? null;

if (!$planId) {
    header("Location: ../Pages/landingPage.php");
    exit;
}

$sql = "SELECT * FROM meal_plan WHERE id = $planId";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);



$display_MealPlan = "";
if ($row) {
    $display_MealPlan = "
    <div class='container mt-3'>
        <a href='../Pages/landingPage.php' class='btn btn-outline-dark'>← Go back</a>
    </div>

    <div class='card m-3'>
        <div class='card-body'>
            <h5 class='card-title'>{$row['name']}</h5>
            <p class='card-text'>Date: {$row['meal_date']}</p>
            <p class='card-text'>Time: {$row['meal_time']}</p>
            <p class='card-text'>Meal: {$row['meal']}</p>
        </div>
    </div>
    ";
} else {
    $display_MealPlan = "<div class='alert alert-danger' role='alert'>Meal Plan not found.</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/main.css">

</head>

<body>
    <?= $display_MealPlan ?>


    <!-- Bootstrap -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js">
    </script>
    <!-- Footer -->
    <?php include "../components/footer.php"; ?>
</body>

</html>