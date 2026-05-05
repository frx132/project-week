<?php

require_once "../components/db_connect.php";

$recipeid = $_GET['recipeid'];


$sql_query = "SELECT * FROM `recipes` WHERE id = $recipeid ";
$result = mysqli_query($connect, $sql_query);
$recipe = mysqli_fetch_assoc($result);
// var_dump($recipe);



if (isset($_POST['save'])) {
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
        $sql_query = "UPDATE recipes SET title = '$title', description = '$description', ingredients = '$ingredients', instructions = '$instructions', prep_time = '$prep_time', dietary_type = '$dietary_type', category = '$category', difficulty = '$difficulty', servings = '$servings' WHERE id = {$recipeid}";
    } else {
        // var_dump($_FILES['pictures']);
        if (isset($recipe['recipe_picture']) && !empty($recipe['recipe_picture'])) {

            try {
                unlink("../pictures/{$recipe['recipe_picture']}");
            } catch (Exception $e) {
                echo "Error: can't delete the picture " . $e->getMessage();
            }
        }

        // var_dump($pictures);
        $sql_query = "UPDATE recipes SET title = '$title', description = '$description', ingredients = '$ingredients', instructions = '$instructions', prep_time = '$prep_time', dietary_type = '$dietary_type', category = '$category', difficulty = '$difficulty', servings = '$servings', recipe_picture = '$pictures[0]' WHERE id = {$recipeid}";
    }
    $result = mysqli_query($connect, $sql_query);
    if ($result) {
        echo "<div class='alert alert-success'>
                The recipe is edited successfully!            
                 </div>
        ";
    } else {
        echo "<div class='alert alert-danger'>
                Something went wrong! {$pictures[1]}
            </div>
        ";
    }
    header("refresh: 3; url=recipe.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <?php include "../components/navbar.php"; ?>
    <div class="container my-5">

        <div class="row">
            <div class="col col-md-6 mx-auto">
                <a href="recipe.php" class="btn btn-secondary my-4">Back</a>
                <h3>Create a new recipe</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= $recipe['title'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"><?= $recipe['description'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pictures">Pictures</label>
                        <input type="file" class="form-control" id="pictures" name="pictures">
                    </div>
                    <div class="mb-3">
                        <label for="prep_time">Preparation time (Minutes)</label>
                        <input type="number" class="form-control" id="prep_time" name="prep_time" value="<?= $recipe['prep_time'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="servings">Servings</label>
                        <input type="number" class="form-control" id="servings" name="servings" value="<?= $recipe['servings'] ?>">
                    </div>


                    <div class="mb-3">
                        <label for="dietary_type">Dietary type</label>
                        <select name="dietary_type" id="dietary_type" class="form-select" required>
                            <option value="null">Please select an option</option>
                            <option value="Vegeterian" <?= (isset($recipe["dietary_type"]) && $recipe["dietary_type"] == 'Vegeterian') ? 'selected' : '' ?>>Vegeterian</option>
                            <option value="Non-vegeterian" <?= (isset($recipe["dietary_type"]) && $recipe["dietary_type"] == 'Non-vegeterian') ? 'selected' : '' ?>>Non-vegeterian</option>
                            <option value="Vegan" <?= (isset($recipe["dietary_type"]) && $recipe["dietary_type"] == 'Vegan') ? 'selected' : '' ?>>Vegan</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="difficulty">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-select">
                            <option value="null">Please select an option</option>
                            <option value="Easy" <?= (isset($recipe["difficulty"]) && $recipe["difficulty"] == 'Easy') ? 'selected' : '' ?>>Easy</option>
                            <option value="Medium" <?= (isset($recipe["difficulty"]) && $recipe["difficulty"] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                            <option value="Complicated" <?= (isset($recipe["difficulty"]) && $recipe["difficulty"] == 'Complicated') ? 'selected' : '' ?>>Complicated</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="null">Please select an option</option>
                            <option value="Chicken meals" <?= (isset($recipe["category"]) && $recipe["category"] == 'Chicken meals') ? 'selected' : '' ?>>Chicken meals</option>
                            <option value="Vegetables" <?= (isset($recipe["category"]) && $recipe["category"] == 'Vegetables') ? 'selected' : '' ?>>Vegetables</option>
                            <option value="Desserts" <?= (isset($recipe["category"]) && $recipe["category"] == 'Desserts') ? 'selected' : '' ?>>Desserts</option>
                            <option value="Fish meals" <?= (isset($recipe["category"]) && $recipe["category"] == 'Fish meals') ? 'selected' : '' ?>>Fish meals</option>
                        </select>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ingredients">Ingredients</label>
                        <textarea class="form-control" id="ingredients" name="ingredients" required><?= $recipe['ingredients'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="instructions">Instructions</label>
                        <textarea class="form-control" id="instructions" name="instructions" required><?= $recipe['instructions'] ?></textarea>
                    </div>

                    <input type="submit" class="btn btn-primary" name="save" value="Save">
                </form>
            </div>
        </div>

    </div>
    <?php include "../components/footer.php"; ?>
</body>

</html>