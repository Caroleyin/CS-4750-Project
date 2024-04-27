<!-- includes the grocery list for the user who is currently logged -->
<!-- will also pull from the ingredients list table -->

<!-- users will be able to scroll down grocery list, 
update the amounts of the groceries, and delete -->

<?php

    require "connect-db.php";
    session_start();

    $username = $_SESSION["username"];

    // fetch the grocery list items for a user
    $grocery_list = getListItemsForUser($username);

    // get the grocery list items for a user
     function getListItemsForUser($username) {
        global $db;
        $query = "select * from (((Grocery_List natural join Create_New) natural join Contains) natural join Ingredients) where username=:username";

        $statement = $db->prepare($query);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();

        return $result;
     }

    // delete item from grocery list by the ingredient_ID
    function deleteListItem($grocery_list_ID, $ingredient_ID) {
        global $db;
        $query = "delete from Contains where grocery_list_ID=:grocery_list_ID and ingredient_ID=:ingredient_ID";

        $statement = $db->prepare($query);
        $statement->bindValue(":grocery_list_ID", $grocery_list_ID);
        $statement->bindValue(":ingredient_ID", $ingredient_ID);
        $statement->execute();
        $statement->closeCursor();
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Grocery List</title>
        <style>
            body {
                font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
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

            .container {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                padding: 10px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #333;
                color: #fff;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            .center {
                margin: auto;
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
        <div class="container">
            <h1>My Grocery List</h1>

            <table class="w3-table w3-bordered w3-card-4 center">
                <thead>
                    <tr style="background-color:#B0B0B0">
                        <th><b>Ingredient name</b></th>
                        <th><b>Amount</b></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($grocery_list as $grocery_list_item): ?>
                    <tr>
                        <td><?php echo $grocery_list_item['ingredient_Name']; ?></td>
                        <td><?php echo $grocery_list_item['amount']; ?></td>
                    </tr>
                <?php endforeach; ?>  
            </table>
        </div>
    </body>
    </html>