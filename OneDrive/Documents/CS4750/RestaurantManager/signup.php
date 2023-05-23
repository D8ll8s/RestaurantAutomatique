
<?php
require("connect-db.php");
// include("connect-db.php");
function addUser($username, $pass_word,$id)
{
   global $db;
  //  $query = "insert into friends values ($name, $major, $year)";
  //  $statement = $db->query($query);   // compile and exe

  $query = 'insert into Login values(:username, :password,:id)';
  $statement = $db->prepare($query);
  $statement->bindValue(':username', $username);

  $statement->bindValue(':password', $pass_word);
  $statement->bindValue(':id', $id);
  $statement->execute();
  $statement->closeCursor();


}



if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
   if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "signup"))
   {
       addUser($_POST['username'],$_POST['password'],$_POST['restaurantid']);
       header('Location: https://www.cs.virginia.edu/~lhn2vm/login.php');
   }

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

  <title>Sign Up Form</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />


</head>
<body>
<form action = "signup.php" method="post">
  <div class="form-group">
    <label>Username</label>
    <input type = 'text' class="form-control" name="username" aria-describedby="emailHelp" placeholder="Enter username">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type = 'text' class="form-control" name="password" placeholder="Password">
  </div>
    <div class="form-group">
        <label >Restaurant ID</label>
        <input type = 'text' class="form-control" name="restaurantid" placeholder="Enter your unique assigned restaurant ID">
    </div>
  <button name = "actionBtn" type="submit" class="btn btn-warning" value = 'signup'> Sign Up</button>
</form>



<form action = "login.php" method="post">
    <button name = "actionBtn" type="submit" class="btn btn-warning" value = 'login'> Go back</button>
</form>

</body>

</html