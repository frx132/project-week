<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
    exit;
}
if (isset($_SESSION['user'])) {
    header("Location: ../functions/user_dashboard.php");
    exit;
}

require_once "../components/db_connect.php";





if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $pictures = fileUpload($_FILES['recipe_picture']);
    $prep_time = $_POST['prep_time'];
    $instructions = $_POST['instructions'];
    $difficulty = $_POST['difficulty'];
    $servings = $_POST['servings'];
    $ingredients = $_POST['ingredients'];
    $dietary_type = $_POST['dietary_type'];
    $category = $_POST['category'];
    $author = $_GET['user'];

    $sql_query = "INSERT INTO `recipes`(`title`, `description`, `recipe_picture`, `ingredients`, `instructions`, `prep_time`, `dietary_type`, `category`,`difficulty`, `servings`, `author_id`) VALUES ('$title','$description','$pictures[0]','$ingredients','$instructions', '$prep_time', '$dietary_type', '$category', '$difficulty', '$servings', '$author' )";

    $result = mysqli_query($connect, $sql_query);
    var_dump($_FILES['pictures']);
    if ($result) {
        echo "<div class='alert alert-success'>
                Congrats, you have added a new recipe!            
                 </div>
        ";
    } else {
        echo "<div class='alert alert-danger'>
                Something went wrong!
            </div>
        ";
    }
    // header("refresh: 3; url=recipe.php");

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">

        <div class="row">
            <div class="col col-md-6 mx-auto">
                <a href="recipe.php" class="btn btn-secondary my-4">Back</a>
                <h3>Create a new recipe</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pictures">Pictures</label>
                        <input type="file" class="form-control" id="pictures" name="pictures">
                    </div>
                    <div class="mb-3">
                        <label for="prep_time">Preparation time</label>
                        <input type="number" class="form-control" id="prep_time" name="prep_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="servings">Servings</label>
                        <input type="number" class="form-control" id="servings" name="servings">
                    </div>


                    <div class="mb-3">
                        <label for="dietary_type">Dietary type</label>
                        <select name="dietary_type" id="dietary_type" class="form-select" required>
                            <option value="null">Please select an option</option>
                            <option value="Vegan">Vegan</option>
                            <option value="Vegeterian">Vegerterian</option>
                            <option value="Non-vegeterian">Non-vegeterian</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="difficulty">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-select">
                            <option value="null">Please select an option</option>
                            <option value="Easy">Easy</option>
                            <option value="Medium">Medium</option>
                            <option value="Complicated">Complicated</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category">
                    </div>
                    <div class="mb-3">
                        <label for="ingredients">Ingredients</label>
                        <textarea class="form-control" id="ingredients" name="ingredients" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="instructions">Instructions</label>
                        <textarea class="form-control" id="instructions" name="instructions" required></textarea>
                    </div>

                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                </form>
            </div>
        </div>

    </div>
</body>

</html>