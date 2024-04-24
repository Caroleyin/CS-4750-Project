<!-- includes the grocery list for the user who is currently logged -->
<!-- will also pull from the ingredients list table -->
<!-- included in navigation bar -->

<!-- users will be able to scroll down grocery list, update the amounts of the groceries, and delete -->

<!-- update amounts -->

<!-- delete item from list-->

<!-- manually add item to list -->

<!-- order list by category? -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grocery List</title>
</head>
<body>
    <h1>My Grocery List</h1>

    <ul>
        <?php
        //database connection parameters
        //check connection
        if ($conn->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }
        // fetch grocery items from database
        $sql = "SELECT ingredient FROM grocery_list";
        $result = $conn->query($sql);

        // display grocery items in unordered list
        if ($result->num_rows > 0) {
            while ($result->num_rows > 0) {
                echo "<li>". $row["ingredient"]. "</li>";
            }
        }
        else {
            echo "No items found in the grocery list";
        }

        // fetch grocery items from grocery page
        // add to unordered list


        $conn->close();
        ?>
    </ul>
    <script>


</body>
</html>
