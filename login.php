<!-- php code to handle login -->
<?php

global $stmt;

require("connect-db.php");
session_start();

if ($_SERVER["REQUEST_METHOD" ] == "POST" && isset($_POST["login"])) {
        // get username and password
        $username = $_POST["username"];
        $password = $_POST["password"];

        // password hashing for database security
        // $hashpassword = password_hash($password, PASSWORD_DEFAULT);

        // prepare SQL statement to fetch user based on username
        $stmt = $db->prepare("SELECT * FROM Users WHERE username=?");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $user = $result;

            // check if the password is correct
            // if (password_verify($password, $user["password"])) {
            if ($password === $user["password"]) { // for non-hashed password
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
        $db->close();
    }

?>

<!-- html code for login page -->
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif
            background-color: bisque;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;jkhy 
            margin: 0;
            padding: 0;
        }
        .login-container {
            background-color: brown;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: darkgray;
        }
        .login-container button {
            font-family: Georgia, 'Times New Roman', Times, serif
            background-color: wheat;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container .register-link {
            margin-top: 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }
        .login-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
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