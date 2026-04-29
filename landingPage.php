<?php ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Meal Planner</title>
</head>

<body>
  <!-- navbar  -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Meal Planner</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Planner</a>
          </li>
        </ul>
        <div class="d-flex gap-2">
          <a href="#" class="btn btn-primary">Login</a>
          <a href="#" class="btn btn-primary">Sign Up</a>
        </div>
      </div>
    </div>
  </nav>
  <!-- navbar end -->

  <!-- Hero  -->
  <div class="heroSection ">
    <div class="container py-5">
      <h1 class="my-5">Plan your Meals. Eat better. Save time.</h1>
      <p class="mt-4">
        Organize your recipes, plan breakfast, lunch, and dinner for the week,
        and discover meal ideas shared by other users.
      </p>
      <div class="mt-4 d-flex gap-3 flex-wrap justify-content-end mb-4 pb-5">
        <a href=# class="btn btn-primary">Get Started</a>
        <a href=# class="btn btn-primary">Browse Recipes</a>
      </div>
    </div>
  </div>

  <!-- Hero end  -->
  <!-- Features  -->
  <div class="container">
    <div class="row g-4">

      <div class="col-md-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5>Weekly Planner</h5>
            <p class="text-muted">Plan meals by day and meal time.</p>
          </div>
        </div>
      </div>


      <div class="col-md-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5>Recipe Library</h5>
            <p class="text-muted">Browse recipes from all users.</p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5>Create Recipes</h5>
            <p class="text-muted">Add, edit, and delete your own recipes.</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5>Diet Filters</h5>
            <p class="text-muted">Filter by vegetarian, vegan, and more.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="#" class="btn btn-primary">Learn More</a>
    </div>
  </div>
  <!-- Features end -->
  <!-- Planner Preview -->
  <section id="planner" class="py-5 my-5 ">
    <div class="container">
      <div class="row align-items-center g-5">

        <div class="col-lg-5">
          <h2 class="fw-bold mb-3">Plan your whole week</h2>
          <p class="text-muted">
            Add recipes to breakfast, lunch, and dinner slots. Stay organized and avoid last-minute decisions.
          </p>
          <a href=# class="btn btn-primary mt-3">Open Planner</a>
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
                    <a href="#" class="btn btn-sm btn-outline-primary">+ Add Meal</a>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  <!-- CTA -->
  <section class="py-5 bg-white">
    <div class="container text-center">
      <h2 class="section-title">Ready to organize your meals?</h2>
      <p class="text-muted">Create an account and start planning your week today.</p>
      <a href="#" class="btn btn-primary mt-3">Create Account</a>
    </div>
  </section>
  <!-- CTA end -->
  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 w-100">
    <p class="mb-0">&copy; 2026 MealPlanner</p>
  </footer>
  <!-- Footer end -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>