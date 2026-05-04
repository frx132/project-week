<?php
session_start();

require_once "../components/db_connect.php";

$MealPlan = $_GET['MealPlan'];
$sql_query = "SELECT * FROM `meal_plan` WHERE id = $MealPlan ";
$result = mysqli_query($connect, $sql_query);

if (isset($_GET['MealPlan']) && !empty($_GET['MealPlan'])) {
    if (mysqli_num_rows($result) > 0) {
        $Row = mysqli_fetch_assoc($result);
        if ($Row['meal_picture']) {
            unlink("../pictures/{$Row['meal_picture']}");
        }
        $sql_delete = "DELETE FROM `meal_plan` WHERE id = $MealPlan ";
        $result_delete = mysqli_query($connect, $sql_delete);
        header("refresh: 1; url=planner.php");
    }
} else {
    echo 'Something went wrong';
}
