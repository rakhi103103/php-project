<?php
require "functions.php";

if($_SERVER['REQUEST_METHOD']=="POST")
{
    //this willl help to keep spexialcharxter if user put it in name
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    
          
    $q="select * from user where email = '$email' && password = '$password' limit 1";

    $result = mysqli_query($con,$q);
    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);
        $_SESSION['info'] =$row;
        header("location:profile.php");
        die;
    }
    else
    {
       $error= "wrong email or password";
    }

    

}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>login-mywebsite</title>
    </head>
    <body>
        <?php require "header.php"; ?>
        <div style="margin:auto; max-width:600px;">

        <?php
        if(!empty($error))
        {
            echo "<div>".$error."</div>";
        }
        ?>
            <h2 style="text-align:center;">Login</h2>
        <form method="post" style="margin:auto; padding:10px;">
            <input type="email" name="email" placeholder="email" required><br>
            <input type="password" name="password" placeholder="password" required><br>
            <button>Login</button>
        </form>
        </div>
        
        <?php include "footer.php";?>
    </body>
</html>