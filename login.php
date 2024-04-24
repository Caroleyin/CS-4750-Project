<!-- php code to handle login -->
<?php

global $stmt;

require("connect-db.php");
session_start();

if ($_SERVER["REQUEST_METHOD" ] == "POST" && isset($_POST["login"])) {
        // get username and password
        $username = $_POST["username"];
        $password = $_POST["password"];

        // prepare SQL statement to fetch user based on username
        $stmt = $db->prepare("SELECT * FROM Users WHERE username=?");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $user = $result;

            // check if the password is correct
            // incorporate later for hashing: if (password_verify($password, $user["password"])) {
            if ($password === $user["password"]) {
                $_SESSION["username"] = $username;
                header("Location: homepage.php"); // redirect to homepage
                exit;
            } else {
                echo "Password entered is invalid";
            } 
        }
        else {
            echo "This account does not exist or there is an invalid username or password";
        }
        
        // close statment and database connection
        $stmt->close();
        $conn->close();
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