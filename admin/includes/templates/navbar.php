
    <!-- ************************************* navbar section ************************************* -->
    <nav class="navbar navbar-inverse" data-target="#ourNavbar" role="navigation">
        <div class="container">
            <div class="navbar-header"> 
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ourNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                </button>
                <!-- ////////////////// brand name : website name  ////////////////// -->
                <a href="dashboard.php" class="navbar-brand"> <?php echo langFunc('HOME_ADMIN') ?> </a>
            </div>
            <!-- ******************** collection of links ********************** --> 
            <div class="collapse navbar-collapse" id="ourNavbar">
                <ul class="nav navbar-nav navbar-left">
                    <li> <a href="categories.php"> <?php echo langFunc('CATEGORIES') ?> </a>   </li>
                    <li> <a href="items.php"> <?php echo langFunc('ITEMS') ?> </a>   </li>
                    <li> <a href="members.php"> <?php echo langFunc('MEMBERS') ?> </a>   </li>
                    <li> <a href="comments.php"> <?php echo langFunc('COMMENTS') ?> </a>   </li>
                </ul>      
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"> 
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Osama<span class="caret"></span>
                        </a>   
                        <ul class="dropdown-menu">
                            <li>  <a href="../index.php">Visit Shop</a>   </li>
                            <li>  <a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a> </li>
                            <li>  <a href="#">Settings</a>  </li>
                            <li>  <a href="logout.php">Logout</a>  </li>
                        </ul>
                    </li>
                </ul>                                                                   
            </div>
        </div>
    </nav>
