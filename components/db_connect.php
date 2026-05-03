<?php
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
    // File Upload
    function fileUpload($picture, $source = "user")
    {
        $defaultPicture = ($source === "recipe") ? "default_recipe.jpg" : "avatar.png";

        if ($picture["error"] == 4) { 
            $pictureName = $defaultPicture; 
            $message = "No picture has been chosen, but you can upload an image later :)";
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

        return [$pictureName, $message]; 
    }

    function cleanInputs($input)
    {
        $data = trim($input);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }

