<!-- a page only will recipes the user signed in has published to the website -->
<!-- ability to edit/delete and post more to the site from here -->
<!-- included in navigation bar -->

<?php
require('connect-db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get recipe details from form
    $calories = $_POST['calories'];
    $prep_time = $_POST['prep_time'];
    $type_of_meal = $_POST['type_of_meal'];
    $recipe_name = $_POST['recipe_name'];
    $number_steps = $_POST['number_steps'];
    $instructions = $_POST['instructions'];
    $ingredients = $_POST['ingredient_name']; // Array of ingredient names
    $amounts = $_POST['ingredient_amount']; // Array of ingredient amounts
    $number_of_items = $_POST['number_of_items'];

    $username = $_SESSION['username'];

    // insert new recipe into the database
    // image processing
    $filename = $_FILES["currFile"]["name"];
    $tempname = $_FILES["currFile"]["tmp_name"];
    $folder = "./recipeImages/" . $filename;
  
    $stmt = $db->prepare("INSERT INTO Recipe (calories, prep_Time, type_Of_Meal, recipe_name, file_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $calories);
    $stmt->bindParam(2, $prep_time);
    $stmt->bindParam(3, $type_of_meal);
    $stmt->bindParam(4, $recipe_name);
    $stmt->bindParam(5, $filename);
    $result = $stmt->execute();

    if (move_uploaded_file($tempname, $folder)) {
        echo "<h3>  Image uploaded successfully!</h3>";
    }
    else {
        echo "<h3>  Image not uploaded successfully</h3>";
    }


    if ($result) {
        $recipe_ID = $db->lastInsertId();

        // Insert into the "Process" table
        $stmt_process = $db->prepare("INSERT INTO Process (number_steps, instructions) VALUES (?, ?)");
        $stmt_process->bindParam(1, $number_steps);
        $stmt_process->bindParam(2, $instructions);
        $stmt_process->execute();
        $process_ID = $db->lastInsertId();

        // Insert into the "Has_A" table
        $stmt_has_a = $db->prepare("INSERT INTO Has_A (recipe_ID, process_ID) VALUES (?, ?)");
        $stmt_has_a->bindParam(1, $recipe_ID);
        $stmt_has_a->bindParam(2, $process_ID);
        $stmt_has_a->execute();

        // Insert into the "Ingredients_List" table
        $stmt_ingredients_list = $db->prepare("INSERT INTO Ingredients_List (number_of_items) VALUES (?)");
        $stmt_ingredients_list->bindParam(1, $number_of_items);
        $stmt_ingredients_list->execute();
        $ingredient_list_id = $db->lastInsertId();

        // Insert ingredients into the "Ingredients" table and connect them to the "Lists" table
        for ($i = 0; $i < count($ingredients); $i++) {
            $ingredient_name = $ingredients[$i];
            $ingredient_amount = $amounts[$i];

            $stmt_ingredients = $db->prepare("INSERT INTO Ingredients (ingredient_Name, amount) VALUES (?, ?)");
            $stmt_ingredients->bindParam(1, $ingredient_name);
            $stmt_ingredients->bindParam(2, $ingredient_amount);
            $stmt_ingredients->execute();
            $ingredient_id = $db->lastInsertId();

            // Insert into the "Lists" table
            $stmt_lists = $db->prepare("INSERT INTO Lists (ingredient_ID, ingredient_List_ID) VALUES (?, ?)");
            $stmt_lists->bindParam(1, $ingredient_id);
            $stmt_lists->bindParam(2, $ingredient_list_id);
            $stmt_lists->execute();

            // Insert into the "Has" table
            $stmt_has = $db->prepare("INSERT INTO Has (recipe_ID, ingredient_List_ID) VALUES (?, ?)");
            $stmt_has->bindParam(1, $recipe_ID);
            $stmt_has->bindParam(2, $ingredient_list_id);
            $stmt_has->execute();
        }

        // Insert into the "Creates" table
        $stmt_creates = $db->prepare("INSERT INTO Creates (username, recipe_ID) VALUES (?, ?)");
        $stmt_creates->bindParam(1, $username);
        $stmt_creates->bindParam(2, $recipe_ID);
        $stmt_creates->execute();

        echo "<script>alert('Recipe published successfully!');</script>";
        header("Location: my-recipes.php");
        exit();
    } else {
        echo "<script>alert('Error publishing recipe. Please try again.');</script>";
    }
}

$username = $_SESSION['username'];
$stmt2 = $db->prepare("SELECT R.recipe_ID, R.calories, R.prep_time, R.type_of_meal, R.recipe_name, R.file_name FROM Recipe R JOIN Creates C ON R.recipe_ID = C.recipe_ID WHERE C.username = ?");
$stmt2->bindParam(1, $username);
$stmt2->execute();
$recipes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Recipes</title>
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
            h1 {
                margin-top: 20px;
                text-align: center;
            }
            h3 {
                margin-top: 20px;
                text-align: center;
            }
            ul {
                list-style-type: none;
                padding: 0;
                text-align: center;
            }
            li {
                margin-bottom: 10px;
            }
            form {
                margin-top: 20px;
                text-align: center;
            }
            label {
                display: block;
                margin-bottom: 5px;
            }
            input[type="password"] {
                padding: 8px;
                width: 200px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            button {
                padding 10px 20px;
                background-color: cornflowerblue;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: darkblue;
            }
            /* Add this CSS to hide the form by default */
            .recipe-form {
                display: none;
            }
            .recipe-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            }
            .recipe-item h2 {
                margin-top: 0;
                margin-bottom: 10px;
                font-size: 24px;
                color: #343a40;
            }
            .recipe-link {
                display: inline-block;
                padding: 8px 16px;
                background-color: #007bff;
                color: #ffffff;
                text-decoration: none;
                border-radius: 4px;
            }
            .recipe-link:hover {
                background-color: black;
            }
        </style>
    <script>
        function toggleForm() {
            var form = document.getElementById("recipeForm");
            form.style.display = (form.style.display === "none") ? "block" : "none";
        }

        function addIngredientInputs() {
            var numberOfItems = document.getElementById("number_of_items").value;
            var ingredientsDiv = document.getElementById("ingredients");
            ingredientsDiv.innerHTML = ""; // Clear previous inputs

            for (var i = 0; i < numberOfItems; i++) {
                var ingredientInputs = document.createElement("div");
                ingredientInputs.innerHTML = `
                    <label>Ingredient ${i + 1}:</label><br>
                    <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
                    <input type="text" name="ingredient_amount[]" placeholder="Amount" required>
                    <br><br>
                `;
                ingredientsDiv.appendChild(ingredientInputs);
            }
        }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    </div>
    <h1>My Recipes</h1>
    <button onclick="toggleForm()">Add Recipe</button>
    <div id="recipeForm" class="recipe-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="calories">Calories:</label><br>
            <input type="text" id="calories" name="calories" required><br><br>

            <label for="prep_time">Prep Time (minutes):</label><br>
            <input type="number" id="prep_time" name="prep_time" required><br><br>

            <label for="type_of_meal">Type of Meal:</label><br>
            <input type="text" id="type_of_meal" name="type_of_meal" required><br><br>

            <label for="recipe_name">Recipe Name:</label><br>
            <input type="text" id="recipe_name" name="recipe_name" required><br><br>

            <!-- Process Fields -->
            <label for="number_steps">Number of Steps:</label><br>
            <input type="number" id="number_steps" name="number_steps" required><br><br>

            <label for="instructions">Instructions:</label><br>
            <textarea id="instructions" name="instructions" rows="4" cols="50" required></textarea><br><br>

            <!-- Ingredients List Fields -->
            <label for="number_of_items">Number of Items:</label><br>
            <input type="number" id="number_of_items" name="number_of_items" onchange="addIngredientInputs()" required><br><br>
            <span style="font-size: 0.8em; color: #666;">(Note: Entering the number of items will open input fields for the corresponding number of ingredients to enter)</span><br><br>
            <!-- Ingredients Fields -->
            <div id="ingredients"></div>

            <!-- Image -->
            <input type="file" class="form-control" value="Upload picture" name="currFile"/>

            <input type="submit" name="submit" value="Publish your recipe">
        </form>
    </div>
    <h2>Published Recipes:</h2>
    <?php
    if (!empty($recipes)) {
        echo "<ul>";
        foreach ($recipes as $recipe) {
            echo "<li>{$recipe['recipe_name']} - Calories: {$recipe['calories']}, Prep Time: {$recipe['prep_time']} minutes, Type of Meal: {$recipe['type_of_meal']}</li>";
            if ($recipe['file_name'])
                echo "<li><img src='./recipeImages/". $recipe['file_name'] . "' style='width:20%; height:auto;'></li>";
            }
        echo "</ul>";
    } else {
        echo "You haven't published any recipes yet.";
    }
    ?>
    <h2>Saved Recipes:</h2>
    <?php
    $username = $_SESSION["username"];
    

    $sql_saved_recipes = $db->prepare("SELECT recipe_ID, recipe_name FROM Recipe NATURAL JOIN Saves WHERE username='$username'");
    $sql_saved_recipes->execute();
    $result_saved = $sql_saved_recipes->fetchAll(PDO::FETCH_ASSOC);

    if ($result_saved) {
        foreach ($result_saved as $row) {
            $recipe_ID = $row["recipe_ID"];
            echo "<div class='recipe-item'>";
            echo "<h2>". $row["recipe_name"]. "</h2>";
            echo "<a href='recipe-info.php?recipe_ID=$recipe_ID' class='recipe-link'>View Recipe</a>";
            echo "</div>";
        }
    }
    else {
        echo "You have no recipes saved";
    }
    
    ?>
</body>
</html>
