<!-- when user clicks on a recipe from the homepage or their own recipes, leads to here -->
<!-- includes the process, ingredients list, posted reviews, and ability to write a review if not your own recipe -->
<!-- included in navigation bar -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Page</title>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    <h1>Recipe Page</h1>

    <ul>
        <?php
        require('connect-db.php');
        session_start();
        //database connection parameters
        //check connection
        if ($db->connect_error) {
            die("Connection failed: ". $db->connect_error);
        }
        $recipe_ID = $_GET['recipe_ID']; 
        $stmt = $db->prepare("SELECT recipe_ID, recipe_name, calories, prep_Time, recipe_name, type_Of_Meal FROM Recipe WHERE recipe_ID = $recipe_ID");
        //$stmt->bind_param("i", $recipe_ID);
        $stmt->execute();
        $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result1) {
            foreach ($result1 as $row) {
                echo "<h1>". htmlspecialchars($row["recipe_name"]). "</h1>";
                echo "<p>". 'Calories: '. $row["calories"]. "</p>";
                echo "<p>". 'Prep Time: '. $row["prep_Time"]. "</p>";
                echo "<p>". 'Meal Type: '. $row["type_Of_Meal"]. "</p>";
            }
        } else {
            echo '<p>Invalid recipe ID.</p>';
        }

        $stmt1 = $db->prepare("SELECT number_Steps, instructions FROM Recipe NATURAL JOIN Has_A NATURAL JOIN Process WHERE recipe_ID=$recipe_ID");
        $stmt1->execute();
        $result2 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if ($result2) {
            foreach ($result2 as $row) {
                echo "<h1> Process </h1>";
                echo "<p>". 'Number of Steps: '. $row["number_Steps"]. "</p>";
                echo "<p>". $row["instructions"]. "</p>";
            }
        } else {
            echo '<p>Invalid recipe ID.</p>';
        }

        $conn->close();


        ?>
    </ul>
    <script>

</body>
</html>