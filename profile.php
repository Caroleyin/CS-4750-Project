<!-- user information is here - username, password, reset password, logout button, etc. -->
<!-- included in navigation bar -->

<?php

global $stmt;

require("connect-db.php");
session_start();

if ($_SESSION["username"]) {
    // Retrieve user information from database
    $username = $_SESSION["username"];
    $stmt = $db->prepare("SELECT * FROM Users WHERE username=?");
    $stmt->bindParam(1, $username);
    $stmt->execute();
    $result = $stmt->fetch();
    $user = $result;

    // Close statement and database connection
    //$stmt->close();
   // $db->close();
}
else { // user is not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name=viewport" content"width-device-width, initial-scale=1.0>
    <title>Profile Page</title>
    <style>
         body {
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
            }
            .navbar {
            background-color: cornflowerblue;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            }

            .navbar a {
                color: #ffffff;
                text-decoration: none;
                margin: 0 10px;
            }
            .navbar a:hover {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: black;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            h1 {
                margin-top: 20px;
                text-align: center;
            }
            h3 {
                margin-top: 20px;
                text-align: center;
            }
            ul {
                list-style-type: none;
                padding: 0;
                text-align: center;
            }
            li {
                margin-bottom: 10px;
            }
            form {
                margin-top: 20px;
                text-align: center;
            }
            label {
                display: block;
                margin-bottom: 5px;
            }
            input[type="password"] {
                padding: 8px;
                width: 200px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                padding 10px 20px;
                background-color: cornflowerblue;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: darkblue;
            }
        </style>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    </div>
    <h1>Welcome, <?php echo $user["username"]; ?>
        <p>Your Profile</p>
        </h1>
    <ul>
        <li>Username: <?php echo $user["username"]; ?></li>
        <li>Password: **********</li> <!-- security: don't display the password -->
    </ul>
    
    <h3>Change Password</h3>
    <form method="post" action="change-password.php">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit" name="change_password">Change Password</button>
    </form>
    
    <form method="post" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>