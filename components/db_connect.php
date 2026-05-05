<?php
session_start();
$hostname = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "menu_planner";

// create connection
$connect = new mysqli($hostname, $username, $password, $dbname);

// check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Session check
$currentUser = $_SESSION['user'] ?? $_SESSION['adm'] ?? null;
$CurrentPage = basename($_SERVER['PHP_SELF']);
if (!$currentUser && !in_array($CurrentPage, ['login.php', 'register.php'])) {
    header("Location: login.php");
    exit();
}
function isAdmin()
{
    return isset($_SESSION["adm"]);
}



// File Upload
function fileUpload($picture, $source = "user")
{
    $defaultPicture = ($source === "recipe") ? "default_recipe.jpg" : "avatar.png";

    if ($picture["error"] == 4) {
        $pictureName = $defaultPicture;
        $message = "No picture has been chosen";
    } else {
        $checkIfImage = @getimagesize($picture["tmp_name"]);
        $message = $checkIfImage ? "Ok" : "Not an image";
    }

    if ($message == "Ok") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION));
        $pictureName = uniqid("") . "." . $ext;
        $destination = "../pictures/{$pictureName}";
        if (!move_uploaded_file($picture["tmp_name"], $destination)) {
            $message = "Error moving uploaded file";
            $pictureName = $defaultPicture;
        }
    } elseif ($message == "Not an image") {
        $pictureName = $defaultPicture;
    }
    return [$defaultPicture, $message];
}

// Input Sanitization
function cleanInputs($value)
{
    $data = trim($value);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return htmlspecialchars(strip_tags(trim($data)));
}
