<?php
session_start();
require_once "../components/db_connect.php";

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <?php include "../components/head.php"; ?>
    <title>Meal Planner</title>
    <link rel="stylesheet" href="../css/landingPage.css">
    <link rel="stylesheet" href="../css/main.css">


</head>

<body>

    <!-- Navbar -->
    <?php include "../components/navbar.php"; ?>

    <!-- Meal Plan Schedule -->
    <?php include "crudPlanner/create.php"; ?>

    <!-- Footer -->
    <?php include "../components/footer.php"; ?>

</body>

</html>