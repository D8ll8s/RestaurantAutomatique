<?php

require("connect-db.php");

//Display all the menu item
session_start();
global $db;


//Update functionality
$filter = FALSE;
function UpdatePurchase($name,$date,$starttime,$endtime,$empid)
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

function DeletePurchase($purchaseid,$itemid, $quantity,$userid)
{
    global $db;
    $query = "DELETE FROM Purchase WHERE PurchaseID = :purchaseid;";
    $statement = $db->prepare($query);
    $statement->bindValue(':purchaseid', $purchaseid);
    $statement->execute();
    $statement->closeCursor();
    # Also update in_stock
    $query = "Update In_stock set Quantity = Quantity - :quantity where RestaurantID = :userid and 
                             ItemID = :itemid";
    $statement = $db->prepare($query);
    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':userid', $userid);
    $statement->bindValue(':itemid', $itemid);
    $statement->execute();
    $statement->closeCursor();

}

function AddPurchase($vendorid, $itemid, $itemname,$date,$quantity,$price,$userid)
{
    global $db;

    #Satisfy constraint for Vendor
    $query = "insert into Vendor(VendorID,ItemID) 
        values(:vendorid, :itemid)";
    $statement = $db->prepare($query);
    $statement->bindValue(':vendorid', $vendorid);
    $statement->bindValue(':itemid', $itemid);
    $statement->execute();
    $statement->closeCursor();

    $query = "insert into Purchase(RestaurantID,VendorID,ItemID,Date,Quantity,Price) 
        values(:userid,:vendorid, :itemid,:date,:quantity,:price)";
    $statement = $db->prepare($query);
    $statement->bindValue(':vendorid', $vendorid);
    $statement->bindValue(':itemid', $itemid);
    $statement->bindValue(':date', $date);
    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':userid', $userid);
    $statement->execute();
    $statement->closeCursor();


    # Also update what's instock
    $query = "update In_stock set Quantity = Quantity + :quantity where RestaurantID = :restaurantid and ItemID = :itemid" ;
    $statement = $db->prepare($query);
    $statement->bindValue(':restaurantid', $userid);
    $statement->bindValue(':itemid', $itemid);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor();

    # If it's new add it to instock
    $query = "insert into In_stock values(:userid, :itemid,:quantity)" ;
    $statement = $db->prepare($query);
    $statement->bindValue(':userid', $userid);
    $statement->bindValue(':itemid', $itemid);
    $statement->bindValue(':quantity', $quantity);
    $statement->execute();
    $statement->closeCursor();

    # If it's new, then add to inventory
    $query = "insert into Inventory(ItemID,ItemName) values(:itemid,:itemname)" ;
    $statement = $db->prepare($query);
    $statement->bindValue(':itemname', $itemname);
    $statement->bindValue(':itemid', $itemid);
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

        DeletePurchase($_POST['purchaseid'],$_POST['itemid'],$_POST['quantity'],$_SESSION['current_user_id']);
        header("Location: https://www.cs.virginia.edu/~lhn2vm/purchase.php");

    }
    elseif (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Add'))
    {

        AddPurchase($_POST['vendorid'],$_POST['itemid'],$_POST['itemname'],$_POST['date'],$_POST['quantity'],$_POST['price'],
            $_SESSION['current_user_id']);
        header("Location: https://www.cs.virginia.edu/~lhn2vm/purchase.php");

    }



}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Filter')) {
        $filter = TRUE;
    }
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == 'Reset')) {
        $filter = FALSE;
    }
}

if ($filter == TRUE){

    $query = "select * from Purchase where VendorID = :vendor and RestaurantID = :restaurantid";
// prepare
    $statement = $db->prepare($query);
    $statement->bindValue(':vendor', $_POST['vendor']);
    $statement->bindValue(':restaurantid', $_SESSION['current_user_id']);
// execute
    $statement->execute();
// retrieve
    $results = $statement->fetchAll();   // fetch()

// close cursor
    $statement->closeCursor();
}

else{

    $query = "select * from Purchase where RestaurantID = :restaurantid";
// prepare
    $statement = $db->prepare($query);
    $statement->bindValue(':restaurantid', $_SESSION['current_user_id']);
// execute
    $statement->execute();
// retrieve
    $results = $statement->fetchAll();   // fetch()

// close cursor
    $statement->closeCursor();
}









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

    <title>Restaurant Automatique</title>

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

<form action = purchase.php method = "POST" >
    <div class="form-group">
        <label >Filter by Vendor ID</label>
        <input name = "vendor" class="form-control" placeholder="19">
    </div>
    <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Filter">Filter</button>
    <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Reset">Show ALL</button>

</form>
<div class="row justify-content-center">
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
        <thead>
        <tr style="background-color:#B0B0B0">
            <th>VendorID</th>
            <th>ItemID</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <?php foreach ($results as $item): ?>
            <tr>
                <form action="purchase.php" method="POST">
                    <td>
                        <text type="text"><?php echo $item['VendorID']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['ItemID']; ?></text>
                    </td>

                    <td>
                        <text type="text"><?php echo $item['Date']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['Quantity']; ?></text>
                    </td>
                    <td>
                        <text type="text"><?php echo $item['Price']; ?></text>
                    </td>

                </form>
                <form action = "purchase.php" method ="POST">
                    <td>
                        <input type="submit" name="actionBtn" value="Delete" class="btn btn-danger" />
                        <input type="hidden" name="purchaseid"
                               value= "<?php echo $item['PurchaseID']; ?>"/>
                        <input type="hidden" name="quantity"
                               value= "<?php echo $item['Quantity']; ?>"/>
                        <input type="hidden" name="itemid"
                               value= "<?php echo $item['ItemID']; ?>"/>



                    </td>
                </form>

            </tr>
        <?php endforeach; ?>

    </table>
    <form action = "purchase.php" method = "POST" >

        <div class="form-group">
            <label >VendorID</label>
            <input name = "vendorid" class="form-control"  aria-describedby="emailHelp" placeholder="55">
        </div>
        <div class="form-group">
            <label> ItemID</label>
            <input  name = "itemid" class="form-control" placeholder="101">
        </div>
        <div class="form-group">
            <label> Item Name</label>
            <input  name = "itemname" class="form-control" placeholder="Olives">
        </div>
        <div class="form-group">
            <label>Date Purchased</label>
            <input name = "date" class="form-control" placeholder="yyyymmdd">
        </div>
        <div class="form-group">
            <label >Quantity</label>
            <input name = "quantity" class="form-control" placeholder="20">
        </div>
        <div class="form-group">
            <label >Price</label>
            <input name = "price" class="form-control" placeholder="19.00">
        </div>


        <button type="submit" class="btn btn-primary" name = "actionBtn" value = "Add">Add Item</button>
    </form>
</div>

</body>

</html>


    <?php
}
?>