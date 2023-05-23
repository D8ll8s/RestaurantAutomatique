<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;


//Update functionality

function UpdateEmp($name,$date,$starttime,$endtime,$empid)
{
    global $db;
    $query = "UPDATE Employee SET Name = :name, DateStarted = :date, Start_time = :starttime, End_time = :endtime
            WHERE EmployeeID = :empid";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':starttime', $starttime);
    $statement->bindValue(':endtime', $endtime);
    $statement->bindValue(':empid', $empid);
    $statement->execute();
    $statement->closeCursor();
}

function DeleteEmp($empid)
{
    global $db;
    $query = "DELETE FROM Employee WHERE EmployeeID = :empid;";
    $statement = $db->prepare($query);
    $statement->bindValue(':empid', $empid);
    $statement->execute();
    $statement->closeCursor();
    # Also delete from work for
    $query = "DELETE FROM Works_for WHERE EmployeeID = :empid;";
    $statement = $db->prepare($query);
    $statement->bindValue(':empid', $empid);
    $statement->execute();
    $statement->closeCursor();

}

function AddEmp($name,$datestarted,$starttime,$endtime,$userid)
{
    global $db;
    $query = "insert into Employee(Name,DateStarted,Start_time,End_time) values (:name, :datestarted,
                                :starttime, :endtime)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':datestarted', $datestarted);
    $statement->bindValue(':starttime', $starttime);
    $statement->bindValue(':endtime', $endtime);
    $statement->execute();
    $statement->closeCursor();


    # Also update Works_for with the new employee id
    $query = "insert into Works_for values(:userid,last_insert_id())" ;
    $statement = $db->prepare($query);
    $statement->bindValue(':userid', $userid);
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Update'))
    {
        UpdateEmp($_POST['name'],$_POST['datestarted'],$_POST['starttime'],$_POST['endtime'],$_POST['employeeid']);

    }

    elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Delete'))
    {

        DeleteEmp($_POST['employeeid']);
    }
    elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Add'))
    {

        AddEmp($_POST['name'],$_POST['datestarted'],$_POST['starttime'],$_POST['endtime'],
            $_SESSION['current_user_id']);
        header("Location: https://www.cs.virginia.edu/~lhn2vm/employee.php");

    }



}

// query
$query = "select * from Employee where EmployeeID in 
                             (select EmployeeID from Works_for where RestaurantID = :restaurantid)";
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
            <th>EmployeeID</th>
            <th>Name</th>
            <th>Date Started</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        </thead>
        <?php foreach ($results as $item): ?>
            <tr>
                <form action="employee.php" method="POST">
                    <td>
                        <text type="text"><?php echo $item['EmployeeID']; ?></text>
                    </td>
                    <td>
                        <input type="text" name = "name" value = "<?php echo $item['Name']; ?>">
                    </td>

                    <td>
                        <input type="text" name = "datestarted" value = "<?php echo $item['DateStarted']; ?>">
                    </td>
                    <td>
                        <input type="text" name = "starttime" value = "<?php echo $item['Start_time']; ?>">
                    </td>
                    <td>
                        <input type="text" name = "endtime" value = "<?php echo $item['End_time']; ?>">
                    </td>

                    <td>
                        <input type="submit"   name="actionBtn" value='Update' class="btn btn-dark" />
                        <input type="hidden" name="employeeid"
                               value= "<?php echo $item['EmployeeID']; ?>  "/>
                    </td>
                </form>
                <form action = "employee.php" method ="POST">
                    <td>
                        <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" />
                        <input type="hidden" name="employeeid"
                               value= "<?php echo $item['EmployeeID']; ?>"/>


                    </td>
                </form>

            </tr>
        <?php endforeach; ?>

    </table>
    <form action = "employee.php" method = "POST" >

        <div class="form-group">
            <label >Name</label>
            <input name = "name" class="form-control"  aria-describedby="emailHelp" placeholder="John Smith">
        </div>
        <div class="form-group">
            <label> Date Started</label>
            <input  name = "datestarted" class="form-control" placeholder="yyyy-mm-dd">
        </div>
        <div class="form-group">
            <label>Start Time</label>
            <input name = "starttime" class="form-control" placeholder="19:00:00">
        </div>
        <div class="form-group">
            <label >End Time</label>
            <input name = "endtime" class="form-control" placeholder="19:00:00">
        </div>

        <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Add">Add Item</button>
    </form>
</div>

</body>

</html>

<?php } ?>