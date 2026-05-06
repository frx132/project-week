<?php
// DB Connection and session check
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: login.php");
    exit;
}
require_once "../components/db_connect.php";

// Redirect back button based on user role
$backBtn = "../Pages/landingpage.php";
if (isset($_SESSION["adm"])) {
    $backBtn = "admin_dashboard.php";
} elseif (isset($_SESSION["user"])) {
    $backBtn = "user_dashboard.php";
}

$id = $_GET["id"] ?? '';
$type = $_GET["type"] ?? '';

if (!$id || !$type) {
    header("Location: $backBtn");
    exit;
}

// Handle Update Logic
if ($type == 'user') {
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    $display_status = "";

    //Only for Admins to see the status on edit form
    if (isset($_SESSION["adm"])) {
        $display_status = " 
        <div class='mb-3'>
            <label for='status' class='form-label'>Status</label>
            <select class='form-control' id='status' name='status'>
                <option value='Active'" . (($row['status'] ?? '') == 'Active' ? ' selected' : '') . ">Active</option>
                <option value='Blocked'" . (($row['status'] ?? '') == 'Blocked' ? ' selected' : '') . ">Blocked</option>
            </select>
        </div>";
    }
    if (isset($_POST["update"])) {
        $fname = mysqli_real_escape_string($connect, $_POST["fname"]);
        $lname = mysqli_real_escape_string($connect, $_POST["lname"]);
        $email = mysqli_real_escape_string($connect, $_POST["email"]);
        $status = $_POST["status"] ?? 'users.status';
        $picture = fileUpload($_FILES["picture"]);




        if ($_FILES["picture"]["error"] == 0) {
            if ($row["user_image"] != "avatar.png" && !empty($row["user_image"])) {
                $filePath = "../pictures/{$row["user_image"]}";
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', user_image = '$picture[0]', email = '$email', status = '$status' WHERE id = {$id}";
        } else {
            $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', email = '$email', status = '$status' WHERE id = {$id}";
        }

        if (mysqli_query($connect, $sql)) {
            echo "<div class='alert alert-success' role='alert'>User profile has been Updated</div>";
            header("refresh: 3; url=$backBtn");
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error found, {$picture[1]}</div>";
        }
    }
} elseif ($type == 'recipe') {
    $sql = "SELECT * FROM recipes WHERE id = $id";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    if (isset($_POST["update"])) {
        $title = mysqli_real_escape_string($connect, $_POST["title"]);
        $description = mysqli_real_escape_string($connect, $_POST["description"]);
        $ingredients = mysqli_real_escape_string($connect, $_POST["ingredients"]);
        $instructions = mysqli_real_escape_string($connect, $_POST["instructions"]);
        $prep_time = $_POST["prep_time"];
        $dietary_type = mysqli_real_escape_string($connect, $_POST["dietary_type"]);
        $category = mysqli_real_escape_string($connect, $_POST["category"]);
        $difficulty = mysqli_real_escape_string($connect, $_POST["difficulty"]);
        $servings = $_POST["servings"];

        $picture = fileUpload($_FILES["recipe_picture"], "recipe");

        if ($_FILES["recipe_picture"]["error"] == 0) {
            if ($row["recipe_picture"] != "default_recipe.jpg" && !empty($row["recipe_picture"])) {
                $filePath = "../pictures/{$row["recipe_picture"]}";
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $sql = "UPDATE recipes SET title = '$title', description = '$description', ingredients = '$ingredients', instructions = '$instructions', prep_time = '$prep_time', dietary_type = '$dietary_type', category = '$category', difficulty = '$difficulty', servings = '$servings', recipe_picture = '$picture[0]' WHERE id = {$id}";
        } else {
            $sql = "UPDATE recipes SET title = '$title', description = '$description', ingredients = '$ingredients', instructions = '$instructions', prep_time = '$prep_time', dietary_type = '$dietary_type', category = '$category', difficulty = '$difficulty', servings = '$servings' WHERE id = {$id}";
        }

        if (mysqli_query($connect, $sql)) {
            echo "<div class='alert alert-success' role='alert'>Recipe has been updated, {$picture[1]}</div>";
            header("refresh: 3; url=$backBtn");
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error found, {$picture[1]}</div>";
        }
    }
}


// // FOR CHETAN TO REMOVE: Mealplan Display for Table 
// $query = "SELECT mpr.*, r.title FROM meal_plan_recipe mpr JOIN recipes r ON mpr.recipe_id = r.id WHERE mpr.meal_plan_id = ?";
// $stmt = $connect->prepare($query);
// $stmt->bind_param("i", $meal_plan_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $myPlan = [];
// // Gets Data from Database
// $readMealplan = "SELECT mpr.meal_date, mpr.meal_time, r.title, r.id as recipe_id, r.description 
//           FROM meal_plan_recipe mpr 
//           JOIN recipes r ON mpr.recipe_id = r.id 
//           WHERE mpr.meal_plan_id = ? 
//           ORDER BY FIELD(mpr.meal_date, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), 
//                    FIELD(mpr.meal_time, 'Breakfast', 'Lunch', 'Dinner', 'Snack')";


// $connection = $connect->prepare($readMealplan);
// $connection->bind_param("i", $plan_id);
// $connection->execute();
// $result = $connection->get_result();



// while ($row = $result->fetch_assoc()) {
//     $myPlan[$row['meal_date']][$row['meal_time']][] = $row['title'];
// }
// $resultRecipes = mysqli_query($connect, "SELECT id, title FROM recipes");
// $recipeOptions = "";
// while ($rowR = mysqli_fetch_assoc($resultRecipes)) {
//     $recipeOptions .= "<option value='{$rowR['id']}'>{$rowR['title']}</option>";
// }

?>

<!doctype html>
<html lang="en">

<head>

    <?php include "../components/head.php"; ?>
    <title>Edit <?= ucfirst($type) ?></title>
    <link rel="stylesheet" href="../css/update.css">
</head>

<body>


    <!-- Edit Profile -->
    <div class="container my-5">
        <?php if ($type == 'user'): ?>
            <h1 class="text-center mb-4">Edit Profile</h1>

            <form method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                    <label for="fname" class="form-label">First name</label>
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?= htmlspecialchars($row["first_name"] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="lname" class="form-label">Last name</label>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?= htmlspecialchars($row["last_name"] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="picture" class="form-label">Profile picture</label>
                    <input type="file" class="form-control" id="picture" name="picture">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= htmlspecialchars($row["email"] ?? '') ?>" required>
                </div>
                <?= $display_status ?>
                <button name="update" type="submit" class="btn btn-warning">Update Profile</button>
                <a href="<?= $backBtn ?>" class="btn btn-secondary">Back</a>
            </form>
        <?php elseif ($type == 'recipe'): ?>
            <h1 class="text-center mb-4">Edit Recipe</h1>
            <form method="post" autocomplete="off" enctype="multipart/form-data">
                <div class="mb-3 mt-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Recipe Title" value="<?= htmlspecialchars($row["title"] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($row["description"] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="ingredients" class="form-label">Ingredients</label>
                    <textarea class="form-control" id="ingredients" name="ingredients" rows="3" required><?= htmlspecialchars($row["ingredients"] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="instructions" class="form-label">Instructions</label>
                    <textarea class="form-control" id="instructions" name="instructions" rows="4" required><?= htmlspecialchars($row["instructions"] ?? '') ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prep_time" class="form-label">Prep Time (minutes)</label>
                        <input type="number" class="form-control" id="prep_time" name="prep_time" value="<?= htmlspecialchars($row["prep_time"] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="servings" class="form-label">Servings</label>
                        <input type="number" class="form-control" id="servings" name="servings" value="<?= htmlspecialchars($row["servings"] ?? '') ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dietary_type" class="form-label">Dietary Type</label>
                        <select class="form-control" id="dietary_type" name="dietary_type">
                            <option value="Vegeterian" <?= (isset($row["dietary_type"]) && $row["dietary_type"] == 'Vegeterian') ? 'selected' : '' ?>>Vegeterian</option>
                            <option value="Non-vegeterian" <?= (isset($row["dietary_type"]) && $row["dietary_type"] == 'Non-vegeterian') ? 'selected' : '' ?>>Non-vegeterian</option>
                            <option value="Vegan" <?= (isset($row["dietary_type"]) && $row["dietary_type"] == 'Vegan') ? 'selected' : '' ?>>Vegan</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category">
                            <option value="Chicken meals" <?= (isset($row["category"]) && $row["category"] == 'Chicken meals') ? 'selected' : '' ?>>Chicken meals</option>
                            <option value="Vegetables" <?= (isset($row["category"]) && $row["category"] == 'Vegetables') ? 'selected' : '' ?>>Vegetables</option>
                            <option value="Desserts" <?= (isset($row["category"]) && $row["category"] == 'Desserts') ? 'selected' : '' ?>>Desserts</option>
                            <option value="Fish meals" <?= (isset($row["category"]) && $row["category"] == 'Fish meals') ? 'selected' : '' ?>>Fish meals</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="difficulty" class="form-label">Difficulty</label>
                        <select class="form-control" id="difficulty" name="difficulty">
                            <option value="Easy" <?= (isset($row["difficulty"]) && $row["difficulty"] == 'Easy') ? 'selected' : '' ?>>Easy</option>
                            <option value="Medium" <?= (isset($row["difficulty"]) && $row["difficulty"] == 'Medium') ? 'selected' : '' ?>>Medium</option>
                            <option value="Complicated" <?= (isset($row["difficulty"]) && $row["difficulty"] == 'Complicated') ? 'selected' : '' ?>>Complicated</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="recipe_picture" class="form-label">Recipe Picture</label>
                    <input type="file" class="form-control" id="recipe_picture" name="recipe_picture">
                </div>
                <div class="container d-flex align-items-center justify-content-end">
                    <button name="update" type="submit" class="btn btn-warning mb-5 me-1">Update Recipe</button>
                    <a href="<?= $backBtn ?>" class="btn btn-secondary mb-5 ms-1">Back</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <!-- Update Mealplans  -->



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>