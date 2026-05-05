<?php
require_once "../components/db_connect.php";
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type === 'plan') {
        $sql_query = "SELECT * FROM `meal_plan` WHERE id = $id";
        $result = mysqli_query($connect, $sql_query);
        if (mysqli_num_rows($result) > 0) {
            $MealPlan = mysqli_fetch_assoc($result);
        } else {
            echo "Meal plan not found.";
            exit();
        }
    } else {
        echo "Invalid type specified.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
if (!isset($_GET['Mealplan']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type === 'plan') {
        $sql_delete = "DELETE FROM `meal_plan` WHERE id = $id";
        if (mysqli_query($connect, $sql_delete)) {
            header("Location: planner.php");
            exit();
        } else {
            echo "Error deleting meal plan: " . mysqli_error($connect);
        }
    } else {
        echo "Invalid type specified.";
    }
} else {
    echo "Invalid request.";
}

$display_data = "";
$edit_Mealplan = "";

$display_data = "
    <div class='card mb-3'>
        <div class='card-body'>
            <h5 class='card-title'>Meal Plan Details</h5>

           
        </div>
    </div>
    <a href='../functions/user_dashboard.php' class='btn btn-outline-dark'>← Go back to Dashboard</a>";
