<!-- includes the grocery list for the user who is currently logged -->
<!-- will also pull from the ingredients list table -->

<!-- users will be able to scroll down grocery list, 
update the amounts of the groceries, and delete -->

<?php
       
// fetch grocery items from database
 $sql = "SELECT ingredient FROM grocery_list";
 $result = $db->query($sql);

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

    // manually add item to list

    // delete item from list

    $conn->close();
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grocery List</title>
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    <h1>My Grocery List</h1>
</html>
