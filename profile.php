<!-- user information is here - username, password, reset password, logout button, etc. -->
<!-- included in navigation bar -->

<?php
require("connect-db.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Retrieve user information from database
$username = $_SESSION["username"];
$stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close statement and database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    <h1>Welcome, <?php echo $user["username"]; ?></h1>
    <p>Your Profile:</p>
    <ul>
        <li>Username: <?php echo $user["username"]; ?></li>
        <li>Password: **********</li> <!-- For security reasons, don't display the password -->
    </ul>
    
    <h3>Change Password</h3>
    <form method="post" action="change_password.php">
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