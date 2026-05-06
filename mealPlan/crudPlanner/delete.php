<?php
session_start();
require_once "../../components/db_connect.php";

$userId = $_SESSION["user"] ?? $_SESSION["adm"] ?? null;
if (!$userId) {
    die("No active session found.");
}


if (isset($_GET['id']) && !empty($_GET['id'])) {

    $targetId = (int)$_GET['id'];
    $requestType = $_GET['type'] ?? 'plan';

    // SCENARIO A: Delete an entire meal plan and all its associated recipes
    if ($requestType === 'plan') {

        // First, clear all recipes linked to this plan to maintain database integrity
        $clearRecipes = $connect->prepare("DELETE FROM meal_plan_recipe WHERE meal_plan_id = ?");
        $clearRecipes->bind_param("i", $targetId);
        $clearRecipes->execute();

        // Then, delete the actual plan header, ensuring it belongs to the logged-in user
        $deletePlan = $connect->prepare("DELETE FROM meal_plan WHERE id = ? AND user_id = ?");
        $deletePlan->bind_param("ii", $targetId, $userId);

        if ($deletePlan->execute()) {
            header("Location: ../planner.php?status=plan_removed");
            exit;
        } else {
            echo "Error: Could not remove the plan header.";
        }
    }
    // SCENARIO B: Delete only a single recipe entry from a plan
    elseif ($requestType === 'entry') {

        // Use a JOIN to verify that the entry belongs to a plan owned by this user
        $deleteEntry = $connect->prepare("
            DELETE mpr FROM meal_plan_recipe mpr 
            JOIN meal_plan mp ON mpr.meal_plan_id = mp.id 
            WHERE mpr.id = ? AND mp.user_id = ?
        ");
        $deleteEntry->bind_param("ii", $targetId, $userId);

        if ($deleteEntry->execute()) {
            // Redirect back to the previous page (details or planner)
            $destination = $_SERVER['HTTP_REFERER'] ?? "../planner.php";
            header("Location: " . $destination);
            exit;
        } else {
            echo "Error: Could not remove the specific meal entry.";
        }
    }
}

/**
 * ALTERNATIVE LOGIK: Single recipe deletion via specific parameter
 * This handles cases where only 'delete_mpr' is passed in the URL
 */
if (isset($_GET["delete_mpr"])) {
    $mprId = (int)$_GET["delete_mpr"];

    $secureDelete = $connect->prepare("
        DELETE mpr FROM meal_plan_recipe mpr 
        JOIN meal_plan mp ON mpr.meal_plan_id = mp.id 
        WHERE mpr.id = ? AND mp.user_id = ?
    ");
    $secureDelete->bind_param("ii", $mprId, $userId);

    if ($secureDelete->execute()) {
        header("Location: ../planner.php?status=entry_deleted");
        exit;
    }
}

// Fallback: If no valid parameters are provided, return to the planner dashboard
header("Location: ../planner.php");
exit;
