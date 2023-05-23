<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;


//Update functionality

function UpdateRev($phone,$size,$time,$date,$id)
{
    global $db;
    $query = "UPDATE Reservation SET Reservation.Time = :newtime, Reservation.Phone = :newphone, Reservation.Size = :newsize,
                  Reservation.Date = :newdate 
            WHERE Reservation.ReservationID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':newphone', $phone);
    $statement->bindValue(':newsize', $size);
    $statement->bindValue(':newtime', $time);
    $statement->bindValue(':newdate', $date);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function DeleteRev($userid)
{
    global $db;
    $query = "DELETE FROM Reservation WHERE Reservation.ReservationID = :userid";
    $statement = $db->prepare($query);
    $statement->bindValue(':userid', $userid);
    $statement->execute();
    $statement->closeCursor();
}

function AddRev($phone,$size,$time,$date,$userid)
{
    global $db;
    $query = "insert into Reservation(Phone,Time,Size,Date,RestaurantID) values (:newphone, :newtime,
                                :newsize, :newdate, :restaurantid)";
    $statement = $db->prepare($query);
    $statement->bindValue(':newphone', $phone);
    $statement->bindValue(':newtime', $time);
    $statement->bindValue(':newsize', $size);
    $statement->bindValue(':newdate', $date);
    $statement->bindValue(':restaurantid', $userid);
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Update'))
    {
        UpdateRev($_POST['phone'],$_POST['size'],$_POST['time'],$_POST['date'],$_POST['reservationid']);

    }

    elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Delete'))
    {

        DeleteRev($_POST['reservationid']);
    }
    elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Add'))
    {

        AddRev($_POST['phone'],$_POST['size'],$_POST['time'],$_POST['date'],$_SESSION['current_user_id']);
        header("Location: https://www.cs.virginia.edu/~lhn2vm/reservation.php");

    }



}

// query
$query = "select * from Reservation where RestaurantID = :restaurantid";
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

<?php
if ($_SESSION['isloggedin'] == true) {
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
            <th>Time</th>
            <th>Phone Number</th>
            <th>Party Size</th>
            <th>Date</th>
            <th>Update?</th>
            <th>Delete?</th>
        </tr>
        </thead>
        <?php foreach ($results as $item): ?>
            <tr>
                <form action="reservation.php" method="POST">
                    <td>
                        <input type="text" name = "time" value = "<?php echo $item['Time']; ?>">
                    </td>
                    <td>
                        <input type="text" name = "phone" value = "<?php echo $item['Phone']; ?>">
                    </td>

                    <td>
                        <input type="text" name = "size" value = "<?php echo $item['Size']; ?>">
                    </td>
                    <td>
                        <input type="text" name = "date" value = "<?php echo $item['Date']; ?>">
                    </td>

                    <td>
                        <input type="submit"   name="actionBtn" value='Update' class="btn btn-dark" />
                        <input type="hidden" name="reservationid"
                               value= "<?php echo $item['ReservationID']; ?>  "/>
                    </td>
                </form>
                <form action = "reservation.php" method ="POST">
                    <td>
                        <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" />
                        <input type="hidden" name="reservationid"
                               value= "<?php echo $item['ReservationID']; ?>"/>


                    </td>
                </form>

            </tr>
        <?php endforeach; ?>

    </table>
    <form action = "reservation.php" method = "POST" >
        <div class="form-group">
            <label >Time</label>
            <input name = "time" class="form-control"  aria-describedby="emailHelp" placeholder="19:00:00">
        </div>
        <div class="form-group">
            <label> Phone</label>
            <input  name = "phone" class="form-control" placeholder="804-555-4567">
        </div>
        <div class="form-group">
            <label>Size</label>
            <input name = "size" class="form-control" placeholder="5">
        </div>
        <div class="form-group">
            <label >Date</label>
            <input name = "date" class="form-control" placeholder="yyyy-mm-dd">
        </div>

        <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Add">Add Item</button>
    </form>
</div>

</body>

</html>

<?php } ?>