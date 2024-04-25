<!-- this will have a gallery of recipes with photos and names -->
<!-- the recipes will be pulled from recipes other users have posted -->
<!-- included in navigation bar as Home -->
<!-- after login the user will be initially directed here -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
            while ($row = fetch_assoc()) {
                $recipe_ID = $row["recipe_ID"];
                echo "<h1>". $row["recipe_name"]. "</h1>";
                echo '<a href="recipe-info.php?val=' . $recipe_ID. '">go to recipe page!</a>';
                echo "<ul id=recipe-list class=recipe-list>";
                echo "<li>". $row["calories"]. "</li>";
                echo "<li>". $row["prep_time"]. "</li>";
                echo "<li>". $row["type_of_meal"]. "</li>";
                "</ul>";
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


