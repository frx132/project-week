<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Logging out...</title>
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/logout.css">

    <meta http-equiv="refresh" content="2;url=../pages/landingPage.php">
</head>

<body>

    <div class="logout-screen">
        <h1>Logging out<span class="dots"></span></h1>
    </div>

</body>

</html>