<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;


//Update functionality



function DeleteItem($itemid)
{
    global $db;
    $query = "DELETE FROM Inventory WHERE ItemID = :itemid";
    $statement = $db->prepare($query);
    $statement->bindValue(':itemid', $itemid);
    $statement->execute();
    $statement->closeCursor();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Delete'))
    {
        DeleteItem($_POST['itemid']);

    }




}

// query
$query = "select * from Inventory";
// prepare
$statement = $db->prepare($query);
$statement->bindValue(':restaurantid', $_SESSION['current_user_id']);
// execute
$statement->execute();
// retrieve
$results1 = $statement->fetchAll();   // fetch()

// close cursor
$statement->closeCursor();

// query
$query = "select * from Login";
// prepare
$statement = $db->prepare($query);
// execute
$statement->execute();
// retrieve
$results2 = $statement->fetchAll();   // fetch()

// close cursor
$statement->closeCursor();








?>

<?php
if ($_SESSION['admin'] == true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">

        <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="your name">
        <meta name="description" content="include some description about your page">

        <title>CS 4750: POTD 5</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />


    </head>

    <!-- Navigation Bar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="https://www.cs.virginia.edu/~lhn2vm/userpage.php">Restaurant Automatique</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
            </ul>
            <span class="navbar-text mr-sm-2" >
      Welcome <?php echo $_SESSION['current_user_username'] ?>
    </span>
        </div>
    </nav>



    <body>
    <div class="row justify-content-center">
        <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
            <thead>
            <tr style="background-color:#B0B0B0">
                <th>Item ID</th>
                <th>Item Name</th>

            </tr>
            </thead>
            <?php foreach ($results1 as $item): ?>
                <tr>
                    <form action="admin.php" method="POST">
                        <td>
                            <text><?php echo $item['ItemID']; ?></text>
                        </td>
                        <td>
                            <text><?php echo $item['ItemName']; ?></text>
                        </td>


                    </form>
                    <form action = "admin.php" method ="POST">
                        <td>
                            <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" />
                            <input type="hidden" name="itemid"
                                   value= "<?php echo $item['ItemID']; ?>"/>


                        </td>
                    </form>

                </tr>
            <?php endforeach; ?>

        </table>

    </div>


    <div class="row justify-content-center">
        <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
            <thead>
            <tr style="background-color:#B0B0B0">
                <th>Username</th>
                <th>Password</th>

            </tr>
            </thead>
            <?php foreach ($results2 as $item): ?>
                <tr>
                    <form action="admin.php" method="POST">
                        <td>
                            <text><?php echo $item['Username']; ?></text>
                        </td>
                        <td>
                            <text><?php echo $item['Pass_word']; ?></text>
                        </td>


                    </form>

                </tr>
            <?php endforeach; ?>

        </table>

    </div>

    </body>

    </html>

<?php } ?>