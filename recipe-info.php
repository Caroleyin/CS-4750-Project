<!-- when user clicks on a recipe from the homepage or their own recipes, leads to here -->
<!-- includes the process, ingredients list, posted reviews, and ability to write a review if not your own recipe -->
<!-- included in navigation bar -->

<!DOCTYPE html>
<html>
<head>
    <title></title>

    <ul>
        <?php
        //check connection
        if (db->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }

        $conn->close();

        ?>
    </ul>
    <script>

</head>
<body>
</html>

// comments/reviews
// add ingredients from recipe to grocery list 
        // for each ingredient in recipe
        // add to grocery list