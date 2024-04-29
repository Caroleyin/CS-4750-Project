<?php

global $stmt;

require("connect-db.php");
session_start();

if ($db->connect_error) {
    die("Connection failed: ". $db->connect_error);
}

$result = $db->query("SELECT username FROM Users");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_users"])) {
    foreach ($_POST['selected_users'] as $username) {
        $stmt = $db->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);

        if ($stmt->execute()) {
            echo "User with ID $username deleted successfully.<br>";
        }
        else {
            echo "Error deleting user with ID $username.</br>";
        }
        // $stmt->close();
    }

}
// $db->close();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Users</title>
</head>
<body>
    <h2>Delete Users</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <ul>
            <?php while ($row = $result->fetch()) { ?>
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
