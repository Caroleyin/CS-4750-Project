<!-- php code to handle login -->
<?php
require("connect-db.php");
// session_start();


if ($_SERVER["REQUEST_METHOD" ] == "POST" && isset($_POST["login"])) {
    // get username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // var_dump to inspect variables
    var_dump($username, $password);

    // prepare SQL statement to fetch user based on username
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    // check for errors
    if ($stmt->error) {
        die("Database error: ". $stmt->error);
    }
    $result = $stmt->get_result();
    echo ("testing results of database");
    var_dump($result);

    // validate username and password entered exist in database and are correct
    if ($result ->num_rows == 1) {
        // var dump to see query result
        var_dump($result);

        $user = $result->fetch_assoc();
        var_dump($user);

        // check if the password is correct
        if (password_verify($password, $user["password"])) {
            session_start();
            $_SESSION["username"] = $username;
            echo "Logged in successfully";
            header("Location: homepage.php"); // redirect to homepage
            exit();
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