<!-- include database connection -->

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

<!-- php code to handle login -->
<?php
if ($_SERVER["REQUEST_METHOD" ] == "POST" && isset($_POST["login"])) {
    // get username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

}