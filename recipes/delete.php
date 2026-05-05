<?php
require_once "../components/db_connect.php";

$recipeid = $_GET['recipeid'];
$sql_query = "SELECT * FROM `recipes` WHERE id = $recipeid ";
$result = mysqli_query($connect, $sql_query);


if (isset($_GET['recipeid']) && !empty($_GET['recipeid'])) {
    if (mysqli_num_rows($result) > 0) {
        $recipe = mysqli_fetch_assoc($result);
        if ($recipe['recipe_picture']) {
            unlink("../pictures/{$recipe['recipe_picture']}");
        }
        $sql_delete = "DELETE FROM `recipes` WHERE id = $recipeid ";
        $result_delete = mysqli_query($connect, $sql_delete);
        header("refresh: 1; url=recipe.php");
    }
} else {
    echo 'Something went wrong';
}
