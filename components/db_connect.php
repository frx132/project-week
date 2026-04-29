<?php
// Database connection parameters
$hostname = "IP Adress"; // this is the hostname that you can find in the PhpMyAdmin and you can write "localhost" too
$username = "root"; // be default the userName for the databases is root
$password = ""; // by default there is no password in the databases
$dbname = "name of DB"; // here we need to write the Database name

// create connection, you need to be aware of the order of the parameters
$connect = new mysqli($hostname, $username, $password, $dbname);

// check connection
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}


// File Upload
function fileUpload($picture)
{

    if ($picture["error"] == 4) { // checking if a file has been selected, it will return 0 if you choose a file, and 4 if you didn't
        $pictureName = "avatar.png"; // the file name will be product.png (default picture for a product)
        $message = "No picture has been chosen, but you can upload an image later :)";
    } else {
        $checkIfImage = getimagesize($picture["tmp_name"]); // checking if you selected an image, return false if you didn't select an image
        $message = $checkIfImage ? "Ok" : "Not an image";
    }

    if ($message == "Ok") {
        $ext = strtolower(pathinfo($picture["name"], PATHINFO_EXTENSION)); // taking the extension data from the image
        $pictureName = uniqid("") . "." . $ext; // changing the name of the picture to random string and numbers
        $destination = "pictures/{$pictureName}"; // where the file will be saved
        move_uploaded_file($picture["tmp_name"], $destination); // moving the file to the pictures folder
    }

    return [$pictureName, $message]; // returning the name of the picture and the message
}
