<!-- when user clicks on a recipe from the homepage or their own recipes, leads to here -->
<!-- includes the process, ingredients list, posted reviews, and ability to write a review if not your own recipe -->
<!-- included in navigation bar -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Page</title>
    <style>
         body {
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #e6e6ff;
            }
            .navbar {
            background-color: cornflowerblue;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            }

            .navbar a {
                color: #ffffff;
                text-decoration: none;
                margin: 0 10px;
            }
            .navbar a:hover {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: black;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            h1, h2 {
                margin-top: 20px;
                text-align: center;
            }
            form {
                margin-top: 20px;
                text-align: center;
            }
            label {
                display: block;
                margin-bottom: 5px;
            }
            textarea {
                width: 40%;
                padding: 10px;
                border: 1px solic #ccc;
                border-radius: 5px;
                resize: vertical;
            }
            button {
                padding: 10px 20px;
                background-color: cornflowerblue;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                text-align: center;
            }
            button: hover {
                background-color: darkblue;
            }
            .recipe-details {
                margin: 20px auto;
                max-width: 600px;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .comment {
                margin: 20px auto;
                max-width: 600px;
                padding: 20px;
                background-color: #cce6ff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .process {
                margin-top: 40px;
            }
            .comments {
                margin-top: 40px;
                text-align: center;
            }
            .leave-comment {
                margin-top: 40px;
                text-align: center;
            }
            .grocery-form {
                display: none;
            }
            
        </style>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
        </div>
    <!-- <h1>Recipe Page</h1> -->

    <ul>
        <div class="recipe-details">
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
//https://stackoverflow.com/questions/50567276/how-to-convert-time-format-mysql-to-hours-minutes 
        if ($result1) {
            foreach ($result1 as $row) {
                $time = explode(':', $row["prep_Time"]);
                $time_h_m = (int)$time[0] . ' hour(s) ' . (int)$time[1] . ' min(s)';
                echo "<h1>". htmlspecialchars($row["recipe_name"]). "</h1>";
                echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'] . "?recipe_ID=" . $recipe_ID) .'">';
                //echo '<input type="checkbox" name="save-recipe-check" id="save-recipe-check">';
                echo '<label for="save-recipe-check">Save Recipe?</label>';
                echo '<button type="submit" name="update-saved">Save</button>';
                // Check if the form is submitted and display a success message
                if (isset($_POST['update-saved'])) {
                    echo '<p>Saved successfully!</p>';
                }
                echo '</form>';
                echo "<p>". 'Calories: '. $row["calories"]. "</p>";
                echo "<p>". 'Prep Time: '. $time_h_m . "</p>";
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

        $stmt2 = $db->prepare("SELECT ingredient_ID, ingredient_Name, amount FROM Recipe NATURAL JOIN Has NATURAL JOIN Lists NATURAL JOIN Ingredients WHERE recipe_ID=$recipe_ID");

        $stmt2->execute();

        $result3 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2> Ingredient List </h2>";

        if ($result3) {
            echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'] . "?recipe_ID=" . $recipe_ID) .'">';
            foreach ($result3 as $row) {
                echo '<input type="checkbox" name="' . $row["ingredient_ID"] . '" value="' . $row["ingredient_Name"] . '">';
                echo '<label for="' . $row["ingredient_ID"] . '">' . $row["ingredient_Name"] . ' (' . (int)$row["amount"] . ')'. '</label>';
                echo '<br>';
             }
            $user_id = $_SESSION["username"];
            echo '</form>';
            $stmt3 = $db->prepare("SELECT grocery_list_name, grocery_List_ID FROM Grocery_List NATURAL JOIN Create_New WHERE username='$user_id'");
            $stmt3->execute();
            $result4 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            }

            ?>

        <script>
        function toggleForm() {
            var form = document.getElementById("groceryForm");
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }

        </script>

            <?php

            if ($result3) {
            //echo '<label for="grocery_list_select">Select Grocery List to add to</label>';
            echo '<form method="post" id=gform action="' . htmlspecialchars($_SERVER['PHP_SELF'] . "?recipe_ID=" . $recipe_ID) .'">';
            echo '<select name="grocery_list_select" id="grocery_list_select">';
            echo '<option value="">--Please choose a grocery list--</option>';
            foreach ($result4 as $row) {
                $g_name = $row['grocery_list_name'];
                $grocery_List_ID = $row["grocery_List_ID"];
                echo '<option value="' .$grocery_List_ID. '"> ' . $g_name. ' </option>';
               // $val += 1;
            }
            echo '</select>';

             echo '<br>';
             echo '<button type="submit" name="submit_grocery_items">Add to your Grocery List</button>';
             echo '</form>';
          
            }


            ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?recipe_ID=" . $recipe_ID); ?>">
            <p> ---- OR ---- </p>
            <button onclick="toggleForm()">Create New Grocery List</button>
            <div id="groceryForm" class="grocery-form">
                    <br>
                    <label for="g_list_name">Name:</label>
                    <input type="text" id="g_list_name" name="g_list_name" required><br><br>

                    <button type="submit" name="add_grocery_list">Create new grocery list</button>
                    <!-- <input type="submit" name="add_grocery_list" value="Create new grocery list"> -->
                <br><br>
            </div>
            </form>


        </div>
    </ul>

<!-- Comments and Reviews -->
    <div class="comments">
    <h2>Comments/Reviews</h2>
    <?php
    // retrieve and display comments from the database
    $comments_stmt = $db->prepare("SELECT username, star_Number, comment FROM Review WHERE recipe_ID = $recipe_ID");
    $comments_stmt->execute();
    $comments_result = $comments_stmt->fetchAll(PDO::FETCH_ASSOC); // idk if fetch is right

    //https://stackoverflow.com/questions/31202996/assigning-multiple-styles-on-an-html-element
    if ($comments_result) {
        foreach ($comments_result as $row) {
            echo '<div class="comment">';
            echo "<p style='font-family:verdana; color:#336699; font-size:20px'>" .  $row['username'] . "</p>";
            echo "<p>" . 'rating: '. $row['star_Number']. "</p>";
            echo "<p>" . $row['comment']. "</p>";
            echo '</div>';
        }
    }
    else {
        echo '<p>No comments yet. Be the first to leave a comment</p>';
    }
    // close cursor?
    ?>
    <!-- handle adding comments/reviews to database -->
    </div>

    <div class="leave-comment">
    <h2>Leave a Comment</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?recipe_ID=" . $recipe_ID); ?>">
        <label for="rating">Rating (1-5): </label>
        <select name="starNumber">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select> <br><br>
        <label for="comment_text">Your comment:</label><br>
        <textarea id="comment_text" name="comment_text" rows="4" cols="30" required></textarea><br><br>
        <button type="submit" name="submit_comment">Submit Comment</button>
    </form>
    </div>


    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_comment'])) {
        $comment_text = $_POST["comment_text"];
        $starNumber = $_POST["starNumber"];
        $username = $_SESSION["username"];

        
        
        $sql_comment= $db->prepare("INSERT INTO Review (username, recipe_ID, comment, star_Number) VALUES ('$username', $recipe_ID, '$comment_text', $starNumber)");
        if ($sql_comment->execute())  {
            echo "comment inserted successfully";
        } else {
            echo "insertion error";
        }
    } elseif (isset($_POST['submit_grocery_items'])) {
        echo "here";
        if ($result3) {
            echo "line1";
            foreach ($result3 as $row) {
                echo "line2";
                if ($_POST[$row["ingredient_ID"]]) {
                    echo "line3";
                    $ing_ID = $row["ingredient_ID"];
                    $id_grocery = $_POST['grocery_list_select'];
                    $sql_comment1= $db->prepare("INSERT INTO Contains (grocery_List_ID, ingredient_ID) VALUES ($id_grocery, $ing_ID)");
                    echo "line4";
                    echo $ing_ID;
                    echo $id_grocery;
                    if ($sql_comment1->execute())  {
                        echo "ingredient inserted successfully ";
                    } else {
                        echo "Error inserting ingredient";
                    }
                }
    }
}
    } elseif (isset($_POST['add_grocery_list'])) {
        $grocery_list_name = $_POST["g_list_name"];
        echo $grocery_list_name;
         $sql_g_list_name= $db->prepare("INSERT INTO Grocery_List (number_Of_Items, grocery_list_name) VALUES (0, '$grocery_list_name')");

        echo "he12";
        $sql_g_list_name->execute();
        //echo "heep";
        $grocery_list_ID = $db->lastInsertId();
        $username = $_SESSION["username"];
        $sql_g_list_create_new= $db->prepare("INSERT INTO Create_New (grocery_List_ID, username) VALUES ($grocery_list_ID,'$username')");
        if ($sql_g_list_create_new->execute())  {
            echo "grocery list creation successful";
            ?>
            <script>
            //https://stackoverflow.com/questions/18179067/select-from-drop-down-menu-and-reload-page

            document.getElementById("gform").submit();

            </script>
    <?php
        } else {
            echo "grocery list creation error";
        }


} elseif(isset($_POST['update-saved'])) {
   // echo "update saved";
    $user_id = $_SESSION["username"];
    $var = $_POST['save-recipe-check'];
    $recipe_ID = $_GET['recipe_ID'];
    //if (isset($var)) {
     //   echo "he12";
    //    echo $recipe_ID;
       // $user_id = $_SESSION["username"];
      //  echo $user_id;
        $sql_save_update= $db->prepare("INSERT INTO Saves (username, recipe_ID) VALUES ('$user_id', $recipe_ID)");
      //  echo "he13";
        $sql_save_update->execute();
     //   echo "yes";
   // }
    // } else {
    //     echo "keep";
    //     $recipe_ID = $_GET['recipe_ID'];
    //     //$username = $_SESSION["username"];
    //     echo $user_id;
    //     echo $recipe_ID;

    //     $sql_save_update1= $db->prepare("DELETE FROM Saves WHERE username=$user_id AND recipe_ID=$recipe_ID)");  
    //     $sql_save_update1->execute();     
    //     echo "yup";    
    // }
}
    }
    
$conn->close();

?>


</body>
</html>