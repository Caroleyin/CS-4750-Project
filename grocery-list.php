<!-- includes the grocery list for the user who is currently logged -->
<!-- will also pull from the ingredients list table -->

<!-- users will be able to scroll down grocery list, 
update the amounts of the groceries, and delete -->

<?php
       
require "connect-db.php";
session_start();

// fetch grocery items from database
$stmt = $db->prepare("SELECT * FROM Grocery_List WHERE grocery_List_ID=?");
// issue configuring with grocery list in database
        $stmt->bindParam(1, $grocery_List_ID);
        $stmt->execute();
        $result = $stmt->fetch(); // this works

// display grocery items in unordered list
if ($result) {
    while ($result->num_rows > 0) {
            echo "<li>". $row["ingredient"]. "</li>";
        }
    }
    else {
        echo "No items found in the grocery list";
    }

    // for each item in grocery list
        // display item

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
