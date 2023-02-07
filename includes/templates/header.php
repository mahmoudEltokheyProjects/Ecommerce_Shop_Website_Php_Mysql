<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title> <?php  getTitle() ?> </title>
        <!-- bootstrap css lib -->
        <link rel="stylesheet" href="<?php echo $cssUrl;?>bootstrap.css">
        <!-- fontawesome css lib -->
        <link rel="stylesheet" href="<?php echo $cssUrl;?>font-awesome.min.css">
        <!-- my style css  -->
        <link rel="stylesheet" href="<?php echo $cssUrl;?>frontend.css">
    </head>
    <body>  
        <!-- ************************************* Upper navbar section ************************************* -->
        <div class="upper-bar">
            <div class="container">
              <?php
                    // if "user" is logged in
                    if( isset($_SESSION['user']) )
                    {
              ?>
                    <div class="userAvatar pull-left">
                        <img src="img.png" class="my-image img-circle" alt="user image">
                    </div>
                    <div class="btn-group my-info pull-right">
                        <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?php echo $sessionUser ?>
                            <span class="caret"></span>
                        </span>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="newad.php">New Item</a></li>
                            <li><a href="profile.php#my-ads">My Items</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
              <?php
                    
                    }
                    // if "user" is Not logged in
                    else
                    {
              ?>
                        <a href="login.php">
                            <span class="pull-right">Login/Signup</span>
                        </a>
              <?php } ?>
            </div>
        </div>
        <!-- ************************************* navbar section ************************************* -->
        <nav class="navbar navbar-inverse" data-target="#ourNavbar" role="navigation">
            <div class="container">
                <div class="navbar-header"> 
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ourNavbar">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                    </button>
                    <!-- ////////////////// navbar : left part : brand name  ////////////////// -->
                    <a href="index.php" class="navbar-brand"> Homepage </a>
                </div>
                <!-- ******************** navbar : right part : links ********************** --> 
                <div class="collapse navbar-collapse navbar-right" id="ourNavbar">
                    <ul class="nav navbar-nav navbar-left">
                        <?php
                            // Appear only "main categories" [ parent = 0 ]
                            $categories = getAllFrom("*","categories","ID","where parent = 0" ,"", "ASC");
                            foreach( $categories as $category )
                            {
                                // Replace "space" with "-" in "category name" in "right links"
                                echo"<li> 
                                        <a href='categories.php?pageid=".$category['ID']."&pagename=".str_replace(' ','-',$category['Name'])."'>
                                            ".$category['Name']."
                                        </a> 
                                    <li/>";
                            }
                        ?>
                    </ul>                                                                    
                </div>
            </div>
        </nav>
        
    