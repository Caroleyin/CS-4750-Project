<?php

require("connect-db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register-user"])) {
    echo ('in first if');
    // get username and password
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Get today's date
    $account_creation_date = date("Y-m-d");

    // Store the hashed password, username, email, and creation date in the database
    $stmt = $db->prepare("INSERT INTO Users (username, password, email, account_Creation_Date) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $hashed_password);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $account_creation_date);
    $stmt->execute();

    // Close statement and database connection
    // $stmt->close();
    // $db->close();
    
    // Redirect to second factor authentication page
    $_SESSION["username"] = $username;
    header("Location: homepage.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New User</title>
    <style>
        body {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;jkhy 
            margin: 0;
            padding: 0;
        }
        .register-container {
            background-color: cornflowerblue;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
        }
        .register-container h2 {
            margin-bottom: 20px;
            color: darkgray;
        }
        .register-container button {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color: wheat;
            color: black;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .register-container button:hover {
            background-color: #0056b3;
        }
        .register-container .register-link {
            margin-top: 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }
        .register-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register New User</h2>
        <form method="post" action="register-user.php">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" name="register-user">Register</button>
        </form>
    </div>
</body>
</html>
