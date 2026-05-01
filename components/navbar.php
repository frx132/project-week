<?php
$isLoggedIn = isset($_SESSION["user"]) || isset($_SESSION["adm"]);
$dashboardLink = isset($_SESSION["adm"]) ? "../functions/admin_dashboard.php" : "../functions/user_dashboard.php";
?>
<!-- navbar  -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../Pages/landingPage.php">Meal Planner</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <?php if ($isLoggedIn): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $dashboardLink ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../mealPlan/planner.php">Planner</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../recipes/recipe.php">Recipes</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../Pages/landingPage.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Pages/about.php">About</a>
          </li>
        <?php endif; ?>
      </ul>
      
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <?php if ($isLoggedIn): ?>
          <?php 
             $navUserId = isset($_SESSION["user"]) ? $_SESSION["user"] : $_SESSION["adm"];
          ?>
          <a href="../functions/update.php?id=<?= $navUserId ?>&type=user" class="btn btn-outline-warning btn-sm">Edit Profile</a>
          <a href="../functions/logout.php?logout" class="btn btn-outline-danger btn-sm">Logout</a>
        <?php else: ?>
          <a href="../functions/login.php" class="btn btn-dark btn-sm">Login</a>
          <a href="../functions/register.php" class="btn btn-dark btn-sm">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<!-- navbar end -->