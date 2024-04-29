<?php

// global $stmt;

require("connect-db.php");
session_start();

if ($db->connect_error) {
    die("Connection failed: ". $db->connect_error);
}

$result = $db->query("SELECT username FROM Users");
var_dump($result);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_recipes"])) {
    foreach ($_POST['selected_users'] as $username) {
        $stmt = $db->prepare("DELETE FROM Recipe WHERE recipe_id IN ( SELECT recipe_id FROM Creates WHERE username = ?)");
        $stmt->bindParam(1, $username);
        echo "1";
        if ($stmt->execute()) {
            echo "2";
            echo "Recipes from user $username deleted successfully.<br>";
            header("refresh:3;url=user-management.php");
        }
        else {
            echo "Error deleting recipes from user $username:</br>";
        }
        // $stmt->close();
    }
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Recipes</title>
</head>
<body>
    <h2>Delete Recipes</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <ul>
            <?php while ($row = $result->fetch()) { ?>
                <li>
                    <label>
                        <input type="checkbox" name="selected_users[]" value="<?php echo htmlspecialchars($row['username']); ?>">
                        <?php echo htmlspecialchars($row['username']); ?>
                     </label>
                </li>
            <?php } ?>
        </ul>
        <button type="submit" name="delete_users">Delete Selected Users</button>
    </form>
 </body>
 </html>
