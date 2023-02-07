<?php
    // start session
    session_start();
    // page name
    $pageTitle = "Profile";
    // include "init.php" file
    include "init.php";
    // if "user" is "logged in" , "Profile Page" will "Appear" To User
    if( isset($_SESSION['user']) )
    {
        // Check if "logged in user" [ Exists ] in DB or [ Not ]
        $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
        // Execute the query : Note >> $sessionUser = $_SESSION['user'] 
        $getUser->execute( array($sessionUser) );
        // Get "user data" from DB
        $userInfo = $getUser->fetch();
?>
        <!-- "Profile Page" Heading -->
        <h1 class="text-center">My Profile</h1>
        <!-- +++++++++ Block 1 : information +++++++++ -->
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <!-- panel heading -->
                    <div class="panel-heading">Information</div>
                    <!-- panel body -->
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <span>Login Name</span> : <?php echo $userInfo['Username'] ?> 
                            </li>
                            <li>
                                <i class="fa fa-envelope fa-fw"></i>
                                <span>Email</span> : <?php echo $userInfo['Email'] ?> 
                            </li>
                            <li>
                                <i class="fa fa-user fa-fw"></i>
                                <span>Full Name</span> : <?php echo $userInfo['FullName'] ?> 
                            </li>
                            <li>
                                <i class="fa fa-calendar fa-fw"></i>
                                <span>Registeration Date</span> : <?php echo $userInfo['RegisterDate'] ?> 
                            </li>
                            <li>
                                <i class="fa fa-tags fa-fw"></i>
                                <span>Favourite Category</span> : 
                            </li> 
                        </ul>
                        <a href="#" class="btn btn-primary editInfoBtn">Edit Information</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- +++++++++ Block 2 : Ads +++++++++ -->
        <div class="my-ads block" id="my-ads">
            <div class="container">
                <div class="panel panel-primary">
                    <!-- panel heading -->
                    <div class="panel-heading">My Items</div>
                    <!-- panel body -->
                    <div class="panel-body block">
                        <?php
                            // Call "getItems() Function" To Get "items" of the "category link"
                            $items = getAllFrom("*","items","Item_ID","where Member_ID = {$userInfo['UserID']}" , "");
                            // If User Has items in DB
                            if( !empty($items) )
                            {
                                echo "<div class='row'>";
                                    foreach( $items as $item )
                                    {
                                        echo"<div class='col-sm-6 col-md-4 col-lg-3'>";
                                            echo"<div class='thumbnail item-box'>";
                                                if( $item['Approve'] == 0 )
                                                {
                                                    echo "<span class='approve-status'>Waiting Approval</span>";
                                                }
                                                echo"<span class='price-tag'>$".$item['Price']."</span>";
                                                echo"<img src='img.png' alt='image' class='img-responsive' />";
                                                echo"<div class='caption'>";
                                                    echo"<h3><a href='items.php?itemid=".$item['Item_ID']."'>".$item['Name']."</a></h3>";
                                                    echo"<p>".$item['Description']."</p>";
                                                    echo"<div class='date'>".$item['Add_Date']."</div>";
                                                echo"</div>";
                                            echo"</div>";
                                        echo'</div>';
                                    }
                                echo'</div>';
                            }
                            else
                            {
                                echo "Sorry There is No Ads To Show , Create <a href='newad.php'>New Ad</a> ";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- +++++++++ Block 3 : Latest comments +++++++++ -->
        <div class="my-comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <!-- panel heading -->
                    <div class="panel-heading">Latest Comments</div>
                    <!-- panel body -->
                    <div class="panel-body block">
                        <?php
                            // Select "All Comments" 
                            $myComments = getAllFrom("comment","comments","c_id","where user_id = {$userInfo['UserID']}" ,"", "");
                            if(!empty($myComments) )
                            {
                                foreach( $myComments as $comment )
                                {
                                    echo "<p>".$comment['comment']."</p>";
                                }
                            }
                            else
                            {
                                echo"There's No Comments To Show";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php
        }
        else
        {
            // Redirect to "login.php" page
            header('location:login.php');    
            exit();
        }
        // include "footer.php" file
        include $tpl."footer.php";   
?>



