<?php
require_once "../../components/db_connect.php";
// Get Data
$MealPlan = $_GET['MealPlan'];
$sql_query = "SELECT * FROM `meal_plan` WHERE id = $MealPlan ";
$result = mysqli_query($connect, $sql_query);
$row = mysqli_fetch_assoc($result);

// Display Data
if (mysqli_num_rows($result) > 0) {
    $MealPlan = $row['id'];
    $user_id = $row['user_id'];
    $name = $row['name'];
    $meal = $row['meal'];
    $meal_date = $row['meal_date'];
    $meal_time = $row['meal_time'];
} else {
    echo 'Something went wrong';
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $MealPlan; ?></title>
</head>

<body>

</body>

</html>