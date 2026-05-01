<?php ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/about.css">

  <title>About | Meal Planner</title>
</head>

<body class="about-page">
  <?php include "../components/navbar.php"; ?>
  <main>
    <!-- About hero  -->
    <section class="about-hero">
      <div class="container text-center my-4">
        <h1>About Meal Planner</h1>
        <p>
          Meal Planner helps users organize recipes, plan weekly meals,
          and make healthier food choices with less stress.
        </p>
      </div>
    </section>
    <!-- About hero  end -->

    <!-- Text section  -->
    <section class="container py-5">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <h2>Why this project exists</h2>
          <p>
            Meal Planner is a web application designed to help users explore meals,
            discover new recipes, and organize their weekly food planning in a simple
            and intuitive way. The goal is to make everyday meal decisions easier,
            faster, and more enjoyable.
          </p>

          <p>
            The application was developed collaboratively by
            <strong>Kair</strong>, <strong>Chetan</strong>, and <strong>Ozge</strong>.
          </p>

          <div class="col-lg-6">
            <div class="about-card">
              <h5>What you can do</h5>
              <p>Plan breakfast, lunch, and dinner.</p>
              <p>Save and browse recipes.</p>
              <p>Filter meals by dietary type.</p>
              <p>Create your own recipe collection.</p>
            </div>
          </div>
        </div>
    </section>
    <!-- Text section end -->

    <!-- Cards -->
    <section class="container pb-5">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5>Simple</h5>
              <p class="text-muted">Easy to use and beginner-friendly.</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5>Organized</h5>
              <p class="text-muted">Keep your recipes and weekly plans together.</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <h5>Helpful</h5>
              <p class="text-muted">Save time and avoid last-minute meal decisions.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Cards end -->

  </main>
  <?php include "../components/footer.php"; ?>

</body>

</html>