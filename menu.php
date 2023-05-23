<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;


//Update functionality

function UpdateMenu($truedish,$id,$availability,$newdish,$price)
{
    global $db;
    $query = "UPDATE Menu SET Menu.MenuDish = :newdish, Menu.Price = :price, Menu.Availability = :avail
            WHERE Menu.RestaurantID = :id AND Menu.MenuDish = :olddish";
    $statement = $db->prepare($query);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':avail', $availability);
    $statement->bindValue(':newdish', $newdish);
    $statement->bindValue(':olddish', $truedish);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function DeleteMenu($truedish,$id)
{
    global $db;
    $query = "DELETE FROM Menu WHERE Menu.MenuDish = :dishid AND Menu.RestaurantID = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':dishid', $truedish);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function AddMenu($truedish,$id,$price,$availability)
{
    global $db;
    $query = "insert into Menu values (:id,:dish, :price, :availability)";
    $statement = $db->prepare($query);
    $statement->bindValue(':dish', $truedish);
    $statement->bindValue(':id', $id);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':availability', $availability);
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Update'))
   {
       UpdateMenu($_POST['dishid'],$_SESSION['current_user_id'],$_POST['availability'],$_POST['dish'],$_POST['price']);

   }

   elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Delete'))
   {

       DeleteMenu($_POST['dishid'],$_SESSION['current_user_id']);

   }
   elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Add'))
   {

       AddMenu($_POST['dish'],$_SESSION['current_user_id'],$_POST['price'],$_POST['availability']);

   }



}

// query
$query = "select * from Menu where RestaurantID = :restaurantid";
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
if ($_SESSION['isloggedin'] == TRUE) {
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
            <th>Menu Item</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Update?</th>
            <th>Delete?</th>
        </tr>
        </thead>
        <?php foreach ($results as $item): ?>
            <tr>
                <form action="menu.php" method="post">
                <td>
                    <input type="text" name = "dish" value = "<?php echo $item['MenuDish']; ?>">
                </td>
                <td>
                    <input type="text" name = "price" value = "<?php echo $item['Price']; ?>">
                </td>

                <td>
                    <input type="text" name = "availability" value = "<?php echo $item['Availability']; ?>">
                </td>

                <td>
                    <input type="submit"   name="actionBtn" value='Update' class="btn btn-dark" />
                    <input type="hidden" name="dishid"
                           value= "<?php echo $item['MenuDish']; ?>  "/>
                </td>
                </form>
                <form action = "menu.php" method ="POST">
                <td>
                    <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" />
                    <input type="hidden" name="dishid"
                           value= "<?php echo $item['MenuDish']; ?>"/>


                </td>
                </form>

            </tr>
        <?php endforeach; ?>

    </table>
    <form action = "menu.php" method = "POST" >
        <div class="form-group">
            <label for="exampleInputEmail1">Name of Dish</label>
            <input name = "dish" class="form-control"  aria-describedby="emailHelp" placeholder="Pizza">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Price</label>
            <input  name = "price" class="form-control" id="exampleInputPassword1" placeholder="19.99">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Availability</label>
            <input name = "availability" class="form-control" placeholder="5">
        </div>
        <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Add">Add Item</button>
    </form>
</div>

</body>

</html>

<?php }?>