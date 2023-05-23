<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;
$showtransaction = False;


//Update functionality



// query
$query = "select * from Ordered_By where RestaurantID = :restaurantid";
// prepare
$statement = $db->prepare($query);
$statement->bindValue(':restaurantid', $_SESSION['current_user_id']);
// execute
$statement->execute();
// retrieve
$results = $statement->fetchAll();   // fetch()

// close cursor
$statement->closeCursor();



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'See More'))
    {
        $showtransaction = True;


    }





}



$query = "select * from Customer where CustomerID = :customerid";
// prepare
$statement = $db->prepare($query);
$statement->bindValue(':customerid', $_POST['customerid']);
// execute
$statement->execute();
// retrieve
$customertransaction = $statement->fetch();   // fetch()

// close cursor
$statement->closeCursor();










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
                <a class="nav-link" href="https://www.cs.virginia.edu/~lhn2vm/login.php"><span class="sr-only">Logout</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="https://www.cs.virginia.edu/~lhn2vm/userpage.php"><span class="sr-only">Main Menu</span></a>
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
            <th>Customer ID</th>
            <th>Dish</th>
            <th>Quantity</th>
            <th>Date</th>
        </tr>
        </thead>
        <?php foreach ($results as $item): ?>
            <tr>
                <form action="customerorder.php" method="POST">
                    <td>
                        <text type="text"><?php echo $item['CustomerID']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['MenuDish']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['Quantity']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['Date']; ?></text>
                    </td>
                    <td>
                        <input type="submit"   name="actionBtn" value='See More' class="btn btn-dark" />
                        <input type="hidden" name="customerid"
                               value= "<?php echo $item['CustomerID']; ?>"/>
                    </td>

                </form>

            </tr>
        <?php endforeach; ?>

    </table>

    <?php if ($showtransaction == True): ?>
        <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
            <thead>
            <tr style="background-color:#B0B0B0">
                <th>Customer ID</th>
                <th>Payment Information</th>
            </tr>
            </thead>
                <tr>
                    <td>
                        <text type="text"><?php echo $customertransaction['CustomerID']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $customertransaction['PaymentInfo']; ?></text>
                    </td>



                </tr>

        </table>
    <?php endif; ?>

</div>

</body>

</html>

