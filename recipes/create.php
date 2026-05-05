<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../functions/login.php");
    exit;
}


require_once "../components/db_connect.php";





if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $pictures = fileUpload($_FILES['pictures'], "recipe");
    $prep_time = $_POST['prep_time'];
    $instructions = $_POST['instructions'];
    $difficulty = $_POST['difficulty'];
    $servings = $_POST['servings'];
    $ingredients = $_POST['ingredients'];
    $dietary_type = $_POST['dietary_type'];
    $category = $_POST['category'];
    $author = $_SESSION['user'];

    if ($_FILES['pictures']['error'] == 4) {
        $sql_query = "INSERT INTO `recipes`(`title`, `description`, `ingredients`, `instructions`, `prep_time`, `dietary_type`, `category`,`difficulty`, `servings`, `author_id`) VALUES ('$title','$description','$ingredients','$instructions', '$prep_time', '$dietary_type', '$category', '$difficulty', '$servings', '$author' )";
        // var_dump($_FILES['picture']);

    } else {
        $picture = fileUpload($_FILES['pictures'], "../pictures");

        $sql_query = "INSERT INTO `recipes`(`title`, `description`, `recipe_picture`, `ingredients`, `instructions`, `prep_time`, `dietary_type`, `category`,`difficulty`, `servings`, `author_id`) VALUES ('$title','$description','$pictures[0]','$ingredients','$instructions', '$prep_time', '$dietary_type', '$category', '$difficulty', '$servings', '$author' )";
    }


    $result = mysqli_query($connect, $sql_query);
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
    header("refresh: 3; url=recipe.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create</title>
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/recipeForm.css">
</head>

<body>
    <div class="container my-5">
        <div class="container d-flex justify-content-start align-items-start mt-5">
            <a href='/functions/user_dashboard.php' class='btn btn-outline-dark'><i class="fa-solid fa-arrow-left"></i> Go back</a>
        </div>
        <div class="row">
            <div class="col col-md-6 mx-auto">
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
                        <label for="prep_time">Preparation time (Minutes)</label>
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
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="null">Please select an option</option>
                            <option value="Chicken meals">Chicken meals</option>
                            <option value="Vegetables">Vegetables</option>
                            <option value="Desserts">Desserts</option>
                            <option value="Fish meals">Fish meals</option>
                        </select>
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
    <?php include "../components/footer.php"; ?>
</body>

</html>