<?php
session_start();
// if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
//     header("Location: ../login.php");
//     exit;
// }
// if (isset($_SESSION['user'])) {
//     header("Location: ../home.php");
//     exit;
// }

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
            
            <a href='/recipes/details.php?recipeid={$recipe['id']}' class='btn btn-warning'>Details</a>

            

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <div class="container mb-5 mt-3">
        <div class="row row-cols-1 row-cols-md2 row-cols-lg-3 row-cols-xl-4">
            <?= $display_data ?>
        </div>
    </div>
</body>

</html>