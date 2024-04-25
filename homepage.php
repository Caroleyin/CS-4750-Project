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
</head>
<body>
    <div class="navbar">
        <a href="homepage.php">Home</a>
        <a href="grocery-list.php">Grocery List</a>
        <a href="my-recipes.php">My Recipes</a>
        <a href="profile.php">Profile</a>
    <h1>My Homepage</h1>

    <ul>
        <?php
        //database connection parameters
        //check connection
        if (db->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }

        $conn->close();
        ?>
    </ul>
    <script>


</body>
</html>
