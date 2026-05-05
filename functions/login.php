<?php
// DB Connection
require_once "../components/db_connect.php";
$backBtn = "../Pages/landingPage.php";

// Login functionality
$error = false;
$input = "";
$email = "";
$emailError = $passError = "";


if (isset($_POST["login"])) {
    $email = cleanInputs($_POST["email"]);
    $password = cleanInputs($_POST["password"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Please enter a valid email address";
    }
    if (empty($password)) {
        $error = true;
        $passError = "Password can't be empty!";
    }
    if (!$error) {
        $password = hash("sha256", $password);
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($connect, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) == 1) {
            if ($row["role"] == "Admin") {
                $_SESSION["adm"] = $row["id"];
                header("Location: admin_dashboard.php");
            } else {
                $_SESSION["user"] = $row["id"];
                header("Location: ../Pages/landingPage.php");
            }
        } else {
            echo "<div class='alert alert-danger'>
                       <p>Something went wrong, please try again later ...</p>
                     </div>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Login page</title>
    <?php include "../components/head.php"; ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>

    <!-- Login Form -->
    <div class="container">
        <h1 class="text-center">Login page</h1>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email address"
                    value="<?= $email ?>">
                <span class="text-danger"><?= $emailError ?></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?= $passError ?></span>
            </div>
            <button name="login" type="submit" class="btn btn-outline-dark">Login</button>
            <div class="mt-3"><span>You don't have an account? <a href="register.php">Sign up here</a></span></div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>