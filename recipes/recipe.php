<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {

    header("Location: ../functions/login.php");
    exit;
}

require_once "../components/db_connect.php";
$sql_query = "SELECT * FROM `recipes`";
$result = mysqli_query($connect, $sql_query);


$display_data = "";
if (mysqli_num_rows($result) > 0) {
    $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // var_dump($recipes);

    foreach ($recipes as $recipe) {
        $display_data .= "
        <div class='card m-3'>
            <div class='card-body'>
            <img src='../pictures/{$recipe['recipe_picture']}' class='card-img-top' alt='{$recipe['title']}'>
            <h5 class='card-title'>{$recipe['title']}</h5>
            <p class='badge text-bg-success'>{$recipe['dietary_type']}</p>

            <p class='card-text'>{$recipe['description']} </p>
            <p>Preparation time: {$recipe['prep_time']} minutes </p>
            
           <a href='#' 
   class='btn btn-warning open-modal' 
   data-id='{$recipe['id']}'
   data-bs-toggle='modal' 
   data-bs-target='#recipeModal'>
   Details
</a>

            

            </div>
        </div>
    ";
    }
} else {
    $display_data = "There are no recipes found!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/recipe.css">
    <link rel="stylesheet" href="../css/details.css">


</head>

<body>
    <div class="container d-flex justify-content-start align-items-start mt-5">
        <a href='/functions/user_dashboard.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
    </div>
    <div class="container mb-5 mt-3">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 d-flex justify-content-center">
            <?= $display_data ?>
        </div>
    </div>
    <div class="modal fade" id="recipeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Recipe Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modalContent">
                    Loading...
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.open-modal').forEach(button => {
            button.addEventListener('click', function() {
                let recipeId = this.getAttribute('data-id');

                fetch(`/recipes/details.php?recipeid=${recipeId}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('modalContent').innerHTML = data;
                    });
            });
        });
    </script>
</body>

</html>