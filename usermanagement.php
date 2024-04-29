<?php

global $stmt;

require("connect-db.php");
session_start();

if ($db->connect_error) {
    die("Connection failed: ". $db->connect_error);
}

if (!isset($S_SESSION['admin'])) { // if user is not an admin}
    header("Location: login.php;");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_users"])) {
    foreach ($_POST['selected"_users'] as $username) {
        $stmt = $db->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);

        if ($stmt->execute()) {
            echo "User with ID $username delted successfully.<br>";
        }
        else {
            echo "Error deleting user with ID $username.</br>";
        }
        $stmt->close();
    }

}

$result = $db->query("SELECT user_id, username FROM Users");

$db->close();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Users</title>
</head>
<body>
    <<h2>Delete Users</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <ul>
            <?php while ($row = $result->fetchAll()) { ?>
                <li>
                    <label>
                        <input type="checkbox" name="selected_users[]" value="<?php echo $row['username']; ?>">
                        <?php echo $row['username']; ?>
                     </label>
                </li>
            <?php } ?>
        </ul>
        <button type="submit" name="delete_users">Delete Selected Users</button>
    </form>
 </body>
 </html>
