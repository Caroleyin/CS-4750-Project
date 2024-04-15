<!-- php code to handle login -->
<?php
require("connect-db.php");
session_start();


if ($_SERVER["REQUEST_METHOD" ] == "POST" && isset($_POST["login"])) {
    // get username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // continue the validating username + password and checking

}
?>

<!-- html code for login page -->
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>