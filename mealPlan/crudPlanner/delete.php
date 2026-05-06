<?php
session_start();
require_once "../../components/db_connect.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {    
    $id = (int)$_GET['id'];
    $type = $_GET['type'] ?? 'plan'; 

    if ($type === 'plan') {
        $sql_query = "SELECT * FROM `meal_plan` WHERE id = $id";
        $result = mysqli_query($connect, $sql_query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);            
            if (!empty($row['meal_picture'])) {
                $filePath = "../../pictures/" . $row['meal_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $sql_delete = "DELETE FROM `meal_plan` WHERE id = $id";
            if (mysqli_query($connect, $sql_delete)) {
                header("Location: ../planner.php?success=deleted");
            } else {
                echo "Error deleting plan: " . mysqli_error($connect);
            }
        }
    } 
} else {
    echo "No ID provided.";
    header("refresh:2; url=../planner.php");
}
?>