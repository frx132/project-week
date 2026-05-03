<?php
session_start();

require_once "../components/db_connect.php";
$sql_query = "SELECT * FROM recipes";
$result = mysqli_query($connect, $sql_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Personal CSS -->
    <link rel="stylesheet" href="../css/allRecipies.css">
    <title>All Recipes</title>
</head>

<body>


</body>

</html>