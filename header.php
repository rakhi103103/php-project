<style>
            *{
                padding:0px;
                margin:0px;
                box-sizing:border-box;
            }
            a{
                text-decoration:none;
            }
            body{
                background-color:#ffffe0;
                font-family:tahoma;
            }
            header div{
                padding:20px;
            }
            header a{
                color:white;
            }
            header{
                background-color:#565b95;
                display:flex;
                color:white;
                justify-content:center;
                align-items:center;
            }
            footer{
                padding:20px;
                text-align:center;
                background-color:#eee;
            }
            input{
                margin:4px;
                padding:8px;
                width:100%;
            }
            textarea{
                margin:4px;
                padding:8px;
                width:100%;
            }
            button{
                padding:10px;
                cursor:pointer;
               
                
            }

        </style>

        <header>
            <div><a href="index.php">Home</a></div>
            <div><a href="profile.php">Profile</a></div>
            <?php if(empty($_SESSION['info'])):?>
              <div><a href="login.php">login</a></div>
              <div><a href="signup.php">Signup</a></div>
            <?php else:?>
            <div><a href="logout.php">logout</a></div>
            <?php endif;?>
        </header>