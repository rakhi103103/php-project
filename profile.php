<?php
require "functions.php";

check_login();

if($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['action']) && $_POST['action']=='post_delete')
{                   //delete post-------------------------------------------------
    $id = $_GET['id'] ?? 0;//THIS MEANS NONE WAS DELET IF ID IS NOT FOUND
    $user_id=$_SESSION['info']['id'];//so that user cannot delet other id's post

    $q="select * from posts where id='$id' && user_id='$user_id' limit 1";
    $result=mysqli_query($con,$q);
    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);
        if(file_exists($row['image']))
    {
        unlink($_SESSION['info']['image']);
    }
    }

    $q="delete from posts where id='$id' && user_id='$user_id' limit 1";
    $result=mysqli_query($con,$q);
    

    header("location:profile.php");
    die;

}
elseif($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['action']) && $_POST['action']=="post_edit")
{
    //edit post-------------
    $id = $_GET['id'] ?? 0;//THIS MEANS NONE WAS DELET IF ID IS NOT FOUND
    $user_id=$_SESSION['info']['id'];//so that user cannot delet other id's post

    $image_added = false;
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error']==0 )
    {
        //file uploaded
        $folder= "uploads/";
        if(!file_exists($folder))
        {
            mkdir($folder,0777,true);
        }
        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$image);

        $q="select * from posts where id='$id' && user_id='$user_id' limit 1";
            $result=mysqli_query($con,$q);
            if(mysqli_num_rows($result)>0)
            {
                $row=mysqli_fetch_assoc($result);
                if(file_exists($row['image']))
            {
                unlink($_SESSION['info']['image']);
            }
            }

        $image_added = true;
    }
    $post = addslashes($_POST['post']) ;//this willl help to keep spexialcharxter if user put it in name

    if($image_added==true)
    {
        $q= "update posts set post='$post',image='$image' where id='$id' && user_id='$user_id' limit 1";

    }
    else
    {
        $q= "update posts set post='$post' where id='$id' && user_id='$user_id' limit 1";

    }

    $result = mysqli_query($con,$q);


    header("location:profile.php");
    die;

}



elseif($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['action']) && $_POST['action']=='delete')
{                   //delete profile-------------------------------------------------
    $id = $_SESSION['info']['id'];
    $q="delete from user where id='$id' limit 1";

    $result=mysqli_query($con,$q);
    if(file_exists($_SESSION['info']['image']))
    {
        unlink($_SESSION['info']['image']);
    }

    $q="delete from posts where user_id='$id'";
    $result=mysqli_query($con,$q);

    header("location:logout.php");
    die;

}
elseif($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['username']))
{
    //profile edit
   
    $image_added = false;
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error']==0 && $_FILES['image']['type']=="image/jpeg || image/png || image/jpg")
    {
        //file uploaded
        $folder= "uploads/";
        if(!file_exists($folder))
        {
            mkdir($folder,0777,true);
        }
        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$image);

        if(file_exists($_FILES['image']['image']))
        {
            unlink($_FILES['image']['image']);
        }

        $image_added = true;
    }
    $username = addslashes($_POST['username']) ;//this willl help to keep spexialcharxter if user put it in name
    $email = addslashes($_POST['email']);
    $password = addslashes($_POST['password']);
    $id = $_SESSION['info']['id'];

    if($image_added==true)
    {
        $q= "update user set username='$username',email='$email',password='$password',image='$image' where id='$id' limit 1";

    }
    else
    {
        $q= "update user set username='$username',email='$email',password='$password' where id='$id' limit 1";

    }

    $result = mysqli_query($con,$q);

    $q="select * from user where id = '$id' limit 1";
    $result = mysqli_query($con,$q);
    if(mysqli_num_rows($result)>0)
    {
        $rows=mysqli_fetch_assoc($result);
        $_SESSION['info']=$rows;

    }

    header("location:profile.php");
    die;

}

elseif($_SERVER['REQUEST_METHOD']=="POST" && !empty($_POST['post']))
{
    //padding post
    $image ="";
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error']==0 && $_FILES['image']['type']=="image/jpeg || image/png || image/jpg" )

    {
        //file uploaded
        $folder= "uploads/";
        if(!file_exists($folder))
        {
            mkdir($folder,0777,true);
        }
        $image = $folder . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],$image);

       
    }
    $post = addslashes($_POST['post']) ;//this willl help to keep spexialcharxter if user put it in name
    $user_id = $_SESSION['info']['id'];
    $date= date('Y-m-d H-i-s');

    $q= "insert into posts (user_id,post,image,date) values ('$user_id','$post','$image','$date')";    

    $result = mysqli_query($con,$q);

    header("location:profile.php");
    die;
    

}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>profile-mywebsite</title>
    </head>
    <body>
        <?php require "header.php"; ?>
        <div style="margin:auto; max-width:600px;">

                <?php if(!empty($_GET['action']) && $_GET['action']=='post_delete' && !empty($_GET['id'])):?> <!--post_delete--------->
                    <?php
                     $id=(int)$_GET['id'];
                        $q="select * from posts where id='$id' limit 1";
                        $result = mysqli_query($con,$q);
                        ?>

                    <?php if(mysqli_num_rows($result)>0):?>
                        <?php $row=mysqli_fetch_assoc($result);?>
                        <h3>Are you sure you want to delete this post?!</h3>
                        <form method="post" enctype="multipart/form-data" style="margin:auto; padding:10px;">
                        <input type="hidden" name="action" value="post_delete">
                        
                        <img src="<?=$row['image']?>" style="width:100%;height:200px;object-fit:cover;"><br>
                        <div><?=$row['post']?></div><br>
                        
                        <button>Delete</button>
                        <a href="profile.php">
                                <button type="button">Cancel</button>
                        </a>
                        </form>
                    <?php endif;?>

                
                <?php elseif(!empty($_GET['action']) && $_GET['action']=='post_edit' && !empty($_GET['id'])):?> <!--post_edit--------->
                    <?php
                     $id=(int)$_GET['id'];
                        $q="select * from posts where id='$id' limit 1";
                        $result = mysqli_query($con,$q);
                        ?>

                    <?php if(mysqli_num_rows($result)>0):?>
                        <?php $row=mysqli_fetch_assoc($result);?>
                        <h4>Edit a Post</h4>
                        <form method="post" enctype="multipart/form-data" style="margin:auto; padding:10px;">
                        <input type="hidden" name="action" value="post_edit">
                        
                        <img src="<?=$row['image']?>" style="width:100%;height:200px;object-fit:cover;"><br>

                        image: <input type="file" name="image"><br>
                        <textarea name="post" rows="8"><?=$row['post']?></textarea><br>
                        
                        <button>Save</button>
                        <a href="profile.php">
                                <button type="button">Cancel</button>
                        </a>
                        </form>
                    <?php endif;?>


                <?php elseif(!empty($_GET['action']) && $_GET['action']=='edit'):?> <!--edit---------------->
                    
                    <div style="margin:auto; max-width:600px;">
                        <h2 style="text-align:center;">User Profile</h2>

                        <form method="post" enctype="multipart/form-data" style="margin:auto; padding:10px;">
                            <img src="<?php echo $_SESSION['info']['image']?>" alt="" style="width:100px; height:100px; object-fit:cover;margin:auto;display:block;">
                            image:<input type="file" name="image"><br>
                            <input value="<?php echo $_SESSION['info']['username']?>" type="text" name="username" placeholder="username" required><br>
                            <input value="<?php echo $_SESSION['info']['email']?>" type="email" name="email" placeholder="email" required><br>
                            <input value="<?php echo $_SESSION['info']['password']?>" type="password" name="password" placeholder="password" required><br>

                            <button>Save</button>
                            <a href="profile.php">
                                <button type="button">Cancel</button>
                            </a>
                        </form>
                    </div>
                <?php elseif(!empty($_GET['action']) && $_GET['action']=='delete'):?>  <!--delete-->
                    
                    <div style="margin:auto; max-width:600px;">
                        <h2 style="text-align:center;">are you sure you want to delete your profile??</h2>

                        <form method="post" style="margin:auto; padding:10px;">
                        <div style="margin:auto;max-width:600px;text-align:center;">
                            <img src="<?php echo $_SESSION['info']['image']?>" alt="" style="width:100px; height:100px; object-fit:cover;margin:auto;display:block;">
                            <div> <?php echo $_SESSION['info']['username']?></div>
                            <div> <?php echo $_SESSION['info']['email']?></div>
                            <input type="hidden" name="action" value="delete">

                            <button>Delete</button>
                            <a href="profile.php">
                                <button type="button">Cancel</button>
                            </a>
                      </div>
                        </form>
                    </div>

             <?php else:?>   
                    <h2 style="text-align:center;">User Profile</h2><br>

                    <div style="margin:auto;max-width:600px;text-align:center;">
                        <div>
                            <td><img src="<?php echo $_SESSION['info']['image']?>" alt="" style="width:100px; height:100px; object-fit:cover;"></td>
                        </div>
                        <div>
                            <td><?php echo $_SESSION['info']['username']?></td>
                        </div>
                        <div>
                            <td><?php echo $_SESSION['info']['email']?></td>
                        </div>
                        <a href="profile.php?action=edit"><br>
                        <button>Edit Profile</button>
                        </a>
                        <a href="profile.php?action=delete">
                        <button>Delete Profile</button>
                        </a>
                    </div>
                <br>
                <hr>
                <h4>Create a Post</h4>
                <form method="post" enctype="multipart/form-data" style="margin:auto; padding:10px;">
                    
                image: <input type="file" name="image"><br>
                <textarea name="post" rows="8"></textarea><br>
                
                <button>Post</button>
                </form>
                <hr>
                <posts>
                    <?php
                    $id = $_SESSION['info']['id'];
                    $q="select * from posts where user_id = '$id' order by id desc limit 10";

                    $result=mysqli_query($con,$q);
                    
                    
                    ?>
                    <?php if(mysqli_num_rows($result)>0):?>

                        <?php while($row=mysqli_fetch_assoc($result)):?>
                            <?php
                            $user_id = $row['user_id'];
                            $q="select username,image from user where id='$user_id' limit 1";
                            $result2=mysqli_query($con,$q);
                            $user_row=mysqli_fetch_assoc($result2);

                            ?>
                            <div style="background-color:white;display:flex;border:solid thin #aaa;border-radius:10px;margin-bottom:10px;margin-top:10px;">
                                <div style="flex:1;text-align:center;">
                                <img src="<?=$user_row['image']?>" style="border-radius:50%;margin:10px;width:100px;height:100px;object-fit:cover;"> 
                                <br>
                                <?=$user_row['username']?>  
                                </div>
                                <div style="flex:8">
                                    <?php if(file_exists($row['image'])):?>
                                        <div style="">
                                            <img src="<?= $row['image']?>" style="width:100%;height:200px;object-fit:cover;">
                                        </div>
                                    <?php endif;?>
                                    <div >
                                    <div style="color:#888;"><?=date("jS M,Y",strtotime($row['date']))?></div> 

                                        <?php echo $row['post']?>

                                        <br><br>

                                        <a href="profile.php?action=post_edit&id=<?=$row['id']?>"><br>
                                        <button> Edit </button>
                                        </a>
                                        <a href="profile.php?action=post_delete&id=<?=$row['id']?>">
                                        <button>Delete</button>
                                        </a>
                                        <br><br>
                                    </div>
                                </div>

                            </div>
                        <?php endwhile;?>
                        <?php endif;?>
               </posts>
        <?php endif;?>
        </div>
        
        <?php include "footer.php";?>
    </body>
</html>