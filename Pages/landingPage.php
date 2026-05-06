<?php
require_once "../components/db_connect.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Meal Planner</title>
  <?php include "../components/head.php"; ?>
  <link rel="stylesheet" href="../css/landingPage.css">
  <link rel="stylesheet" href="../css/main.css">

</head>

<body>
  <?php include "../components/navbar.php"; ?>
  <main>
    <!-- Hero  -->
    <section class="heroSection">
      <div class="container py-5">
        <div class="row align-items-center">

          <div class="col-lg-6">
            <h1 class="my-4">Plan your Meals. Eat better. Save time.</h1>
            <p class="mt-3">
              Organize your recipes, plan breakfast, lunch, and dinner for the week,
              and discover meal ideas shared by other users.
            </p>

            <div class="mt-4 d-flex gap-3 flex-wrap">
              <a href="../functions/register.php" class="btn btn-dark">Get Started</a>
              <a href="../recipes/recipe.php" class="btn btn-dark">Browse Recipes</a>
            </div>
          </div>

          <!-- <div class="col-lg-6 d-flex justify-content-end">
            <div class="hero-card">
              <h5>Today's Plan</h5>
              <p>🥣 Breakfast: Oats</p>
              <p>🥗 Lunch: Salad Bowl</p>
              <p>🍝 Dinner: Pasta</p>
            </div>
          </div> -->

        </div>
      </div>
    </section>
    <!-- Hero end  -->
    <!-- Features  -->
    <div class="container">
      <div class="row g-4 my-5">

        <div class="col-md-3">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5><i class="fa-solid fa-calendar"></i> Weekly Planner</h5>
              <p class="text-muted">Plan meals by day and meal time.</p>
            </div>
          </div>
        </div>


        <div class="col-md-3">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5><i class="fa-solid fa-book"></i> Recipe Library</h5>
              <p class="text-muted">Browse recipes from all users.</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5><i class="fa-solid fa-square-plus"></i> Create Recipes</h5>
              <p class="text-muted">Add, edit, and delete your own recipes.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card h-100">
            <div class="card-body text-center">
              <h5><i class="fa-solid fa-sliders"></i> Diet Filters</h5>
              <p class="text-muted">Filter by vegetarian, vegan, and more.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center mt-4">
        <a href="../Pages/about.php" class="btn btn-dark mb-5">Learn More</a>
      </div>
    </div>
    <!-- Features end -->
    <!-- Planner preview -->
    <section id="planner" class="py-5 ">
      <div class="container">
        <div class="row align-items-center g-5">

          <div class="col-lg-5">
            <h2 class="fw-bold mb-3">Plan your whole week</h2>
            <p class="text-muted">
              Add recipes to breakfast, lunch, and dinner slots. Stay organized and avoid last-minute decisions.
            </p>
            <a href="../functions/login.php" class="btn btn-dark mt-3">Open Planner</a>
          </div>

          <div class="col-lg-7">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="row g-3">

                  <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                      <h6 class="fw-bold">Monday</h6>
                      <p class="mb-1"><strong>Breakfast:</strong> Oats</p>
                      <p class="mb-1"><strong>Lunch:</strong> Chicken Rice</p>
                      <p class="mb-0"><strong>Dinner:</strong> Steak</p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                      <h6 class="fw-bold">Tuesday</h6>
                      <p class="mb-1"><strong>Breakfast:</strong> Smoothie</p>
                      <p class="mb-1"><strong>Lunch:</strong> Salad</p>
                      <p class="mb-0"><strong>Dinner:</strong> Pasta</p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                      <h6 class="fw-bold">Wednesday</h6>
                      <p class="mb-1"><strong>Breakfast:</strong> Eggs</p>
                      <p class="mb-1"><strong>Lunch:</strong> Wrap</p>
                      <p class="mb-0"><strong>Dinner:</strong> Curry</p>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="border rounded p-3 h-100 text-center">
                      <h6 class="fw-bold">Thursday</h6>
                      <p class="text-muted">No meals planned</p>
                      <a href="#" class="btn btn-sm btn-outline-dark">+ Add Meal</a>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- Planner preview end-->
    <!-- CTA -->
    <section class="py-5 bg-white">
      <div class="container text-center">
        <h2 class="section-title">Ready to organize your meals?</h2>
        <p class="text-muted">Create an account and start planning your week today.</p>
        <a href="../functions/register.php" class="btn btn-dark mt-3">Create Account</a>
      </div>
    </section>

    <!-- CTA end -->
  </main>
  <?php include "../components/footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>