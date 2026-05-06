<?php
session_start();
require_once "../components/db_connect.php";

// if (!isset($_SESSION["adm"])) {
//     header("Location: login.php");
//     exit;
// }

$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
$sqlUsers = "SELECT * FROM users WHERE role != 'Admin'";
$resultUsers = mysqli_query($connect, $sqlUsers);

$layout = "";

if (mysqli_num_rows($resultUsers) > 0) {
    while ($userRow = mysqli_fetch_assoc($resultUsers)) {
        $pic = !empty($userRow["user_image"]) ? $userRow["user_image"] : "avatar.jpg";
        $status_badge = "";

        if ($userRow['status'] == "Active") {
            $status_badge = "<span class='badge badge-success'>{$userRow['status']}</span>";
        } else {
            $status_badge = "<span class='badge badge-secondary'>{$userRow['status']}</span>";
        }

        $layout .= "<div class='col'>
           <div class='card h-100'>
               <img src='../pictures/{$pic}' class='card-img-top' alt='User Image' style='object-fit: cover; height: 200px;'>
               <div class='card-body d-flex flex-column'>
               <h5 class='card-title'>{$userRow['first_name']} {$userRow['last_name']}</h5>
               <p class='card-text'>{$userRow['email']}</p>
               <?= $status_badge ?>
               <div class='mt-auto'>
                   <a href='update.php?id={$userRow['id']}&type=user' class='btn btn-warning'>Update</a>
                   <a href='delete.php?id={$userRow['id']}&type=user' class='btn btn-outline-danger'>Delete</a>
               </div>
           </div>
       </div>
     </div>";
    }
} else {
    $layout .= "<p>No users found!</p>";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../components/head.php"; ?>
    <title>Admin Dashboard - Users</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/dashboard.css">

</head>
<!-- Navbar -->

<body class="bg-light">

    <!-- Body -->
    <!-- <div class="container d-flex align-items-end justify-content-end">
        <a href="../functions/logout.php?logout" class="btn btn-outline-danger btn-sm ">Logout</a>
    </div> -->
    <div class="container d-flex justify-content-start align-items-start mt-5">
        <a href='/functions/admin_dashboard.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
    </div>

    <div class="container mb-5">
        <h3 class="my-3">Manage Users</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?= $layout ?>
            <?= $status_badge ?>

        </div>
    </div>
    <!-- Quick Actions -->
    <div class="container mb-5">

        <h4 class="mb-3">Quick Actions</h4>
        <div class="row g-4 mb-5">


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
                    <h5 class="fw-bold">Recipes</h5>
                    <p class="text-muted mb-0 small">View, edit and delete all recipes</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>