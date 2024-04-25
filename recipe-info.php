<!-- when user clicks on a recipe from the homepage or their own recipes, leads to here -->
<!-- includes the process, ingredients list, posted reviews, and ability to write a review if not your own recipe -->
<!-- included in navigation bar -->

<!DOCTYPE html>
<html lang="en">>
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
        echo "here";
        require('connect-db.php');
        session_start();
        //database connection parameters
        //check connection
        if ($db->connect_error) {
            die("Connection failed: ". $db->connect_error);
        }
        echo "here";
        $recipe_ID = $_GET['recipe_ID']; 
        echo "here";

        $stmt = $db->prepare("SELECT recipe_ID, recipe_name FROM Recipe WHERE recipe_ID = ?");
        echo "123";
        $stmt->bind_param("i", $recipe_ID);
        echo "234";
        $recipe_ID = $_GET['recipe_ID']; 
        $stmt->execute();
        $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "123";
        $stmt1 = $db->prepare("SELECT numSteps, instructions FROM Recipe NATURAL JOIN HAS_A NATURAL JOIN Process WHERE recipe_ID=$recipe_ID");
        $stmt1->execute();
        $result2 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        echo "234";
        if ($result1) {
            foreach ($result1 as $row) {
                echo "<h1>". $row["recipe_name"]. "</h1>";
                echo "<ul id=recipe-list class=recipe-list>";
                echo "<li>". $row["calories"]. "</li>";
                echo "<li>". $row["prep_time"]. "</li>";
                echo "<li>". $row["type_of_meal"]. "</li>";
                "</ul>";
            }
        }

        if ($result2) {
            foreach ($result2 as $row) {
                echo "<h1> process </h1>";
                echo "<ul id=process-list class=process-list>";
                echo "<li>". $row["numSteps"]. "</li>";
                echo "<li>". $row["instructions"]. "</li>";
                "</ul>";
            }
        }



        $conn->close();

        ?>
    </ul>
    <script>

</body>
</html>