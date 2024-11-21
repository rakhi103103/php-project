<?php
require "functions.php";
if($_SERVER['REQUEST_METHOD']=="POST")
{
    $username = addslashes($_POST['username']) ;//this willl help to keep spexialcharxter if user put it in name
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $date=date('Y-m-d H:i:s');
          
    $q="insert into user(username,email,password,date) values('$username','$email','$password','$date')";

    $result = mysqli_query($con,$q);

    header("location:login.php");
    die;

}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>signup-mywebsite</title>
    </head>
    <body>
        <?php require "header.php"; ?>
        <div style="margin:auto; max-width:600px;">
            <h2 style="text-align:center;">Signup</h2>

        <form method="post" style="margin:auto; padding:10px;">
            <input type="text" name="username" placeholder="username" required><br>
            <input type="email" name="email" placeholder="email" required><br>
            <input type="password" name="password" placeholder="password" required><br>
            <button>Signup</button>
        </form>
        </div>
        
        <?php include "footer.php";?>
    </body>
</html>