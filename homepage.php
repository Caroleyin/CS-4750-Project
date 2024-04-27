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
    <style>
        body {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            background-color: bisque;
            margin: 0;
            padding: 0;
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
</head>

<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    </div>
    <h1>My Homepage</h1>

    <form method="post" action="homepage.php">
        <label for="meal_type">Filter by Meal Type:</label>
        <select name="meal_type" id="meal_type">
            <option value="">All</option>
            <option value="Breakfast">Breakfast</option>
            <option value="Lunch">Lunch</option>
            <option value="Dinner">Dinner</option>
            <option value="Snack">Snack</option>
         </select>
        <button type="submit">Apply Filter</button>
    </form>

    <ul>
        <?php
        require('connect-db.php');
        session_start();

        //check connection
        if ($db->connect_error) {
            die("Connection failed: ". $db->connect_error);
        }
        
        // initialize SQL query
        $sql = "SELECT recipe_ID, recipe_name FROM Recipe";

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["meal_type"])) {
            $meal_type = $_POST["meal_type"];
            if (!empty($meal_type)) {
                $sql .= " WHERE type_Of_Meal = ?";
            }
        }

        $stmt = $db->prepare($sql);

        if (!empty($meal_type)) {
            $stmt->bindParam(1, $meal_type);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        //$stmt = $db->prepare("SELECT recipe_ID, recipe_name FROM Recipe");
        //$stmt->execute();
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        // prepare SQL statement to fetch user based on username
       // $stmt = $db->prepare("SELECT recipe_ID, recipe_name FROM Recipe");
       // $stmt->execute();
       // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as $row) {
                $recipe_ID = $row["recipe_ID"];
                echo "<li class='recipe-item'>";
                echo "<h2>". $row["recipe_name"]. "</h2>";
                echo "<a href='recipe-info.php?recipe_ID=$recipe_ID' class='recipe-link'>View Recipe</a>";
                echo "</li>";
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
