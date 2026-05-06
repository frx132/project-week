<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
    exit;
}
if (isset($_SESSION['adm'])) {
    header("Location: ../functions/admin_dashboard.php");
    exit;
}

require_once "../components/db_connect.php";

$search_query = $_GET['search'];

$sql_query = "SELECT * FROM `recipes` WHERE title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR ingredients LIKE '%$search_query%'";
$result = mysqli_query($connect, $sql_query);

$display_data = "";
if (mysqli_num_rows($result) > 0) {
    $recipes_search = mysqli_fetch_all($result, MYSQLI_ASSOC);



    foreach ($recipes_search as $recipe) {
        $display_data .= "
        <div class='card m-3'>
            <div class='card-body'>
            <img src='../pictures/{$recipe['recipe_picture']}' class='card-img-top' alt='{$recipe['title']}'>
            <h5 class='card-title mt-3'>{$recipe['title']}</h5>
            <p class='badge text-bg-success'>{$recipe['dietary_type']}</p>

            <p class='card-text'>{$recipe['description']} </p>
            <p>Preparation time: {$recipe['prep_time']} minutes </p>
            
           <a href='#' 
   class='btn btn-outline-dark open-modal' 
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
    $display_data = "There are no recipes found with this search keyword!";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search result for "<?= $search_query ?>" </title>
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/recipe.css">
    <link rel="stylesheet" href="../css/details.css">


</head>

<body>
    <div class="container d-flex justify-content-start align-items-start mt-5">
        <a href='recipe.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
    </div>
    <h1 class="text-center fw-semibold">Recipes with search result of "<?= $search_query ?>" </h1>
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

    <?php include "../components/footer.php"; ?>

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