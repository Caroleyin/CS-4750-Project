<?php

// global $stmt;

require("connect-db.php");
session_start();

if ($db->connect_error) {
    die("Connection failed: ". $db->connect_error);
}

$result = $db->query("SELECT username FROM Users");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_recipes"])) {
    foreach ($_POST['selected_users'] as $username) {
        $stmt = $db->prepare("DELETE FROM Recipe WHERE recipe_id IN ( SELECT recipe_id FROM Creates WHERE username = ?)");
        $stmt->bindParam(1, $username);
        echo ("We are unable to delete from the database at this time.");
        $stmt->execute(); 
        $stmt->closeCursor();
        echo "Recipes from user $username deleted successfully.<br>";
        header("refresh:3;url=user-management.php");
    }
}

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Recipes</title>
    <style>
        body {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .deletion-container {
            background-color: cornflowerblue;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
        }
        .deletion-container h2 {
            margin-bottom: 20px;
            color: darkgray;
        }
        .deletion-container button {
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
        .deletion-container button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style-type: none; /* remove bullet points */
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="deletion-container">
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
            <button type="submit" name="delete_recipes">Delete Selected Recipes</button>
        </form>
    </div>
 </body>
 </html>
