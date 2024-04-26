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
                echo "<h2> Process </h2>";
                echo "<p>". 'Number of Steps: '. $row["number_Steps"]. "</p>";
                echo "<p>". $row["instructions"]. "</p>";
            }
        } else {
            echo '<p>Invalid recipe ID.</p>';
        }

        $stmt2 = $db->prepare("SELECT number_Steps, instructions FROM Recipe NATURAL JOIN Has_A NATURAL JOIN Process WHERE recipe_ID=$recipe_ID");
        $stmt2->execute();
        $result3 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if ($result2) {
            foreach ($result2 as $row) {
                echo "<h2> Process </h2>";
                echo "<p>". 'Number of Steps: '. $row["number_Steps"]. "</p>";
                echo "<p>". $row["instructions"]. "</p>";
            }
        } else {
            echo '<p>Invalid recipe ID.</p>';
        }



        ?>
    </ul>

<!-- Comments and Reviews -->
    <h2>Comments/Reviews</h2>
    <?php
    // retrieve and display comments from the database
    $comments_stmt = $db->prepare("SELECT username, star_Number, comment FROM Review WHERE recipe_ID = $recipe_ID");
    $comments_stmt->execute();
    $comments_result = $comments_stmt->fetchAll(PDO::FETCH_ASSOC); // idk if fetch is right

    if ($comments_result) {
        foreach ($comments_result as $row) {
            echo "<p>" . 'usename: '. $row['username']. "</p>";
            echo "<p>" . $row['comment']. "</p>";
        }
    }
    else {
        echo '<p>No comments yet. Be the first to leave a comment</p>';
    }
    // close cursor?
    ?>
    <!-- handle adding comments/reviews to database -->


<h2>Leave a Comment</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?recipe_ID=" . $recipe_ID); ?>">
    <label for="rating">Rating (1-5): </label>
    <select name="starNumber">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    </select> <br>
    <br>
    <label for="comment_text">Your comment:</label><br>
    <textarea id="comment_text" name="comment_text" rows="4" cols="50" required></textarea><br><br>
    <button type="submit" name="submit_comment">Submit Comment</button>
</form>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_text = $_POST["comment_text"];
    $starNumber = $_POST["starNumber"];
    $username = $_SESSION["username"];
}
    $sql_comment= $db->prepare("INSERT INTO Review VALUES (15, '$username', $recipe_ID, '$comment_text', $starNumber)");

    if ($sql_comment->execute())  {
        echo "record inserted successfully";
    } else {
        echo "Error: " . $sql_comment . "<br>" . $conn->error;
    }
    


$conn->close();

?>


</body>
</html>