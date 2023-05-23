<?php
require("connect-db.php");
session_start();


global $db;
// query
$query = "select * from RestaurantAddress where RestaurantID = :restaurantid";
// prepare
$statement = $db->prepare($query);
$statement->bindValue(':restaurantid', $_SESSION['current_user_id']);
// execute
$statement->execute();
// retrieve
$results = $statement->fetchAll();   // fetch()
// close cursor
$statement->closeCursor();



?>



<!-- 1. create HTML5 doctype -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="your name">
    <meta name="description" content="include some description about your page">

    <title>Main Page</title>

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

<!-- Body -->

<text></text>
<div class = "container"  style="padding-top: 50px;">

    <div class="row" style="padding-bottom: 20px;">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Menu</h5>
                    <p class="card-text">View Your Current Menu and Dish Availability</p>
                    <a href="https://www.cs.virginia.edu/~lhn2vm/menu.php" class="btn btn-primary">See Menu</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Reservations</h5>
                    <p class="card-text">View Active Reservation</p>
                    <a href="https://www.cs.virginia.edu/~lhn2vm/reservation.php" class="btn btn-primary">See Reservations</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="padding-bottom: 20px;">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Your Employee </h5>
                    <p class="card-text">Manage your active employees</p>
                    <a href="https://www.cs.virginia.edu/~lhn2vm/employee.php" class="btn btn-primary">See Employee</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customer Order Records</h5>
                    <p class="card-text">See All Orders From Your Customers and More</p>
                    <a href="https://www.cs.virginia.edu/~lhn2vm/customerorder.php" class="btn btn-primary">View Order History</a>
                </div>
            </div>
        </div>
    </div>
    <div>

        <div class="row" style="padding-bottom: 20px;">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Purchase History</h5>
                        <p class="card-text">View a Record of Your Business Transactions</p>
                        <a href="https://www.cs.virginia.edu/~lhn2vm/purchase.php" class="btn btn-primary">See Purchase</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Stock</h5>
                        <p class="card-text">View What's In Stock</p>
                        <a href="https://www.cs.virginia.edu/~lhn2vm/instock.php" class="btn btn-primary">See Stock</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</html>



