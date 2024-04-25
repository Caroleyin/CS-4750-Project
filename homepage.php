<!-- this will have a gallery of recipes with photos and names -->
<!-- the recipes will be pulled from recipes other users have posted -->
<!-- included in navigation bar as Home -->
<!-- after login the user will be initially directed here -->
<!-- php code to handle login -->
<?php
require("connect-db.php");
?>

<!-- html code for home page -->
<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
</head>

<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    <h1>My Homepage</h1>

    <ul>
        <?php
        //database connection parameters
        //check connection
        if (db->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }

        $conn->close();

        $sql = "SELECT recipe_ID, recipe_name, calories, prep_time, type_Of_Meal FROM Recipe";
        $result = $db->query($sql);

        // display grocery items in unordered list
        if ($result->num_rows > 0) {
            while ($result->num_rows > 0) {
                <ul id="recipe-list" class="recipe-list">
                echo "<h1>". $row["recipe_name"]. "</h1>";
                echo "<l1>". $row["calories"]. "</l1>";
                echo "<l1>". $row["prep_time"]. "</l1>";
                echo "<l1>". $row["type_of_meal"]. "</l1>";
                </ul>
            }
        }
        else {
            echo "No items found in the recipe list";
        }

        $conn->close();

        ?>
    </ul>
    <script>


</body>
</html>

