<?php
session_start();
require_once "../../components/db_connect.php";

$userId = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
if (!$userId) {
    die("Zugriff verweigert.");
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'] ?? 'plan';

    if ($type === 'plan') {
        $sql_delete_mpr = "DELETE FROM `meal_plan_recipe` WHERE meal_plan_id = $id";
        mysqli_query($connect, $sql_delete_mpr);
        $sql_delete_plan = "DELETE FROM `meal_plan` WHERE id = $id AND user_id = $userId";

        if (mysqli_query($connect, $sql_delete_plan)) {
            header("Location: ../planner.php?success=plan_deleted");
        } else {
            echo "Fehler beim Löschen des Plans: " . mysqli_error($connect);
        }
    } elseif ($type === 'entry') {

        $sql_delete_entry = "DELETE mpr FROM meal_plan_recipe mpr 
                             JOIN meal_plan mp ON mpr.meal_plan_id = mp.id 
                             WHERE mpr.id = $id AND mp.user_id = $userId";

        if (mysqli_query($connect, $sql_delete_entry)) {
            header("Location: " . $_SERVER['HTTP_REFERER'] ?: "../planner.php");
        } else {
            echo "failed to delete Coloum";
        }
    }
} else {
    header("Location: ../planner.php");
}
