<!-- when user clicks on a recipe from the homepage or their own recipes, leads to here -->
<!-- includes the process, ingredients list, posted reviews, and ability to write a review if not your own recipe -->
<!-- included in navigation bar -->

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="recipe-info.css"> 
<title>Recipe Page</title>
    <ul>
        <?php
        //database connection parameters
        //check connection
        if (db->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }
        if ($result->num_rows > 0) {
            while ($result->num_rows > 0) {
                $recipe_ID = _GET['recipe_ID']; 
                $sql = "SELECT recipe_ID, recipe_name, calories, prep_time, type_Of_Meal FROM Recipe WHERE recipe_ID=$recipe_ID";
                $result = $db->query($sql);
            }
        }



        $conn->close();

        ?>
    </ul>
    <script>

</head>
<body>