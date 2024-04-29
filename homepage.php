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
            background-color: #e6e6ff;
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
        h1 {
            text-align: center;
            margin-top: 30px;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        select, button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            background-color: #007bff;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        select: hover,button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 20px auto;
            max-width: 800px;
        }
        .recipe-item {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .recipe-item: hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }


        .recipe-item h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 24px;
            color: #343a40;
        }
        .recipe-link {
            text-align: center;
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
            margin-left: 20px;
        }
        .recipe-link:hover {
            background-color: #0056b3;
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
        $sql = "SELECT recipe_ID, recipe_name, file_name FROM Recipe";

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
            echo "<a>";
            if ($row['file_name'])
                echo "<img src='./recipeImages/". $row['file_name'] . "' style='width:30%; height:auto; text-align: center;'>";
            echo "<br>";
            echo "<a href='recipe-info.php?recipe_ID=$recipe_ID' class='recipe-link'>View Recipe</a>";
            echo "</a>";
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
