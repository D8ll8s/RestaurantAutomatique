
<?php
require("connect-db.php");
// include("connect-db.php");

// include("friend-db.php");
//require("checkLogin-db.php")

// var_dump($friends);


session_start();
$_SESSION['isloggedin'] = FALSE;
$_SESSION['admin'] = FALSE;
function checkLogin($username)
{
    global $db;
    // query
    $query = "SELECT * FROM Login WHERE username = :username LIMIT 1";
    // prepare
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    // execute
    $statement->execute();
    // retrieve

    $result = $statement->fetch();
    // OUTPUT DATA OF EACH ROW



    // return result

    $statement->closeCursor();
    return $result;


}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "login"))
   {

       $userinfo = checkLogin($_POST['username']);
       if (( $_POST['password'] == "admin123") && ( $_POST['username'] == "admin") && !empty($_POST['password']) ){


           $_SESSION['admin'] = TRUE;
           $_SESSION['current_user_id'] = 0;
           $_SESSION['current_user_username'] =  $_POST['username'];

           //track timestamp of login
           global $db;
           // query
           $query = "INSERT INTO LoginRecord values( :username,CURRENT_TIMESTAMP)";
           // prepare
           $statement = $db->prepare($query);
           $statement->bindValue(':username', $_SESSION['current_user_username']);
           // execute
           $statement->execute();
           $statement->closeCursor;



           header('Location: https://www.cs.virginia.edu/~lhn2vm/admin.php');
           exit();
       }
       if (($userinfo['Pass_word'] == $_POST['password']) && !empty($_POST['password']) ){


           $_SESSION['isloggedin'] = TRUE;
           $_SESSION['current_user_id'] = $userinfo['ownerid'];
           $_SESSION['current_user_username'] = $userinfo['Username'];

           //track timestamp of login
           global $db;
           // query
           $query = "INSERT INTO LoginRecord values( :username,CURRENT_TIMESTAMP)";
           // prepare
           $statement = $db->prepare($query);
           $statement->bindValue(':username', $_SESSION['current_user_username']);
           // execute
           $statement->execute();
           $statement->closeCursor;



           header('Location: https://www.cs.virginia.edu/~lhn2vm/userpage.php');
           exit();
       }





   }
       //if($password == $_POST['password']){
           //header('simpleform.php');
           //exit();
       //}




}

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

  <title>CS 4750: POTD 5</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />


</head>

<body>
<form action = "login.php" method = 'Post'>
  <div class="form-group" >
    <label>Username</label>
    <input class="form-control" name="username" aria-describedby="emailHelp" placeholder="Enter username">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input class="form-control" name="password" placeholder="Password">
  </div>
  <button name = "actionBtn" value = "login" type="submit" class="btn btn-primary">Log In</button>
</form>


<small id="noAccountHelp" class="form-text text-muted">Don't have an account? Sign up for one now</small>
<form action="signup.php" method="POST">
    <button type="submit" class="btn btn-warning"> Sign Up</button>
</form>

</body>

</html>