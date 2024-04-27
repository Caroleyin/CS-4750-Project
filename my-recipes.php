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
    $number_Steps = $_POST['number_steps'];
    $instructions = $_POST['instructions'];
    $ingredients = $_POST['ingredient_name']; // Array of ingredient names
    $amounts = $_POST['ingredient_amount']; // Array of ingredient amounts
    $number_of_items = $_POST['number_of_items'];

    $username = $_SESSION['username'];

    // insert new recipe into the database
    $stmt = $db->prepare("INSERT INTO Recipe (calories, prep_time, type_of_meal, recipe_name) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $calories);
    $stmt->bindParam(2, $prep_time);
    $stmt->bindParam(3, $type_of_meal);
    $stmt->bindParam(4, $recipe_name);
    $result = $stmt->execute();

    if ($result) {
        $recipe_ID = $db->lastInsertId();

        // Insert into the "Process" table
        $stmt_process = $db->prepare("INSERT INTO Process (number_Steps, instructions) VALUES (?, ?)");
        $stmt_process->bindParam(1, $number_steps);
        $stmt_process->bindParam(2, $instructions);
        $stmt_process->execute();
        $process_id = $db->lastInsertId();

        // Insert into the "Ingredients_List" table
        $stmt_ingredients_list = $db->prepare("INSERT INTO Ingredients_List (number_Of_Items) VALUES (?)");
        $stmt_ingredients_list->bindParam(1, $number_of_items);
        $stmt_ingredients_list->execute();
        $ingredients_list_id = $db->lastInsertId();

        // Insert ingredients into the "Ingredients" table and connect them to the "Lists" table
        for ($i = 0; $i < count($ingredients); $i++) {
            $ingredient_name = $ingredients[$i];
            $ingredient_amount = $amounts[$i];

            $stmt_ingredients = $db->prepare("INSERT INTO Ingredients (name, amount) VALUES (?, ?)");
            $stmt_ingredients->bindParam(1, $ingredient_name);
            $stmt_ingredients->bindParam(2, $ingredient_amount);
            $stmt_ingredients->execute();
            $ingredient_id = $db->lastInsertId();

            // Insert into the "Lists" table
            $stmt_lists = $db->prepare("INSERT INTO Lists (ingredient_ID, ingredients_List_ID) VALUES (?, ?)");
            $stmt_lists->bindParam(1, $ingredient_id);
            $stmt_lists->bindParam(2, $ingredients_list_id);
            $stmt_lists->execute();
        }

        // Insert into the "Creates" table
        $stmt_creates = $db->prepare("INSERT INTO Creates (username, recipe_ID) VALUES (?, ?)");
        $stmt_creates->bindParam(1, $username);
        $stmt_creates->bindParam(2, $recipe_ID);
        $stmt_creates->execute();

        echo "<script>alert('Recipe published successfully!');</script>";
        exit();
    } else {
        echo "<script>alert('Error publishing recipe. Please try again.');</script>";
    }
}

$username = $_SESSION['username'];
$stmt2 = $db->prepare("SELECT R.recipe_ID, R.calories, R.prep_time, R.type_of_meal, R.recipe_name FROM Recipe R JOIN Creates C ON R.recipe_ID = C.recipe_ID WHERE C.username = ?");
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
        .recipe-form {
            display: none;
        }
    </style>
    <script>
        function toggleForm() {
            var form = document.getElementById("recipeForm");
            form.style.display = (form.style.display === "none") ? "block" : "none";
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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

            <!-- Ingredients Fields -->
            <label>Ingredients:</label><br>
            <div id="ingredients">
                <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
                <input type="text" name="ingredient_amount[]" placeholder="Amount" required>
                <br><br>
            </div>
            <button type="button" onclick="addIngredient()">Add Ingredient</button>
            <br><br>

            <!-- Ingredients List Fields -->
            <label for="number_of_items">Number of Items:</label><br>
            <input type="number" id="number_of_items" name="number_of_items" required><br><br>

            <input type="submit" value="Publish your recipe">
        </form>
    </div>
    <h2>Published Recipes:</h2>
    <?php
    if (!empty($recipes)) {
        echo "<ul>";
        foreach ($recipes as $recipe) {
            echo "<li>{$recipe['recipe_name']} - Calories: {$recipe['calories']}, Prep Time: {$recipe['prep_time']} minutes, Type of Meal: {$recipe['type_of_meal']}</li>";
        }
        echo "</ul>";
    } else {
        echo "You haven't published any recipes yet.";
    }
    ?>
</body>
</html>