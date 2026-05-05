<?php
session_start();
require_once "../components/db_connect.php";

if (!isset($_SESSION["adm"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);


mysqli_close($connect);





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dashboard.css">

</head>
<!-- Navbar -->

<body class="bg-light">

    <!-- Body -->

    <h2 class="text-center my-4">Welcome <?= $row["first_name"]  ?></h2>
    <div class="container d-flex align-items-end justify-content-end">
        <a href="../functions/logout.php?logout" class="btn btn-outline-danger btn-sm ">Logout</a>
    </div>


    <div class="container mb-5">
        <!-- Quick Actions -->
        <h4 class="mb-3">Quick Actions</h4>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="../functions/user_list.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-plus-circle"></i></div>
                    <h5 class="fw-bold">Users</h5>
                    <p class="text-muted mb-0 small">View, edit and delete all users</p>
                </a>
            </div>

            <div class="col-md-4">
                <a href="../mealPlan/planner.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <h5 class="fw-bold">Meal Planners</h5>
                    <p class="text-muted mb-0 small">View, edit and delete all planners</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="../functions/admin_recipes.php" class="card quick-action-card h-100 shadow-sm border-0 text-center p-4">
                    <div class="action-icon"><i class="fa-solid fa-book-open"></i></div>
                    <h5 class="fw-bold">Edit all recipes</h5>
                    <p class="text-muted mb-0 small">View, edit and delete all recipes</p>
                </a>
            </div>

        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>