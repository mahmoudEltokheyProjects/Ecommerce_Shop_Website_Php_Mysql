<?php
    // output buffering start , "ob_gzhandler" to Compress "php file content" on "server"
    ob_start("ob_gzhandler");
    // "start session" to access the "session data"
    session_start();
    // $pageTitle variable
    $pageTitle = "Dashboard"; 
    // if you are "logged in" , You can access "dashboard page"
    if( isset($_SESSION['Username']) )
    {
        include "init.php";
        // +++++++++++++++++++++++++ Start Dashboard Page +++++++++++++++++++++++++
        // 1- The "Latest Registered Users" of "Panel 1 body" [ Number of Latest Users ]
        $latestUsersNum = 4 ;
        // 1- Latest Users Array 
        $theLatestUsers = getLatest("*","users","UserID",$latestUsersNum);
        // 2- The "Latest Registered Items" of "Panel 1 body" [ Number of Latest Items ]
        $latestItemsNum = 3 ;
        // 2- Latest Items Array 
        $theLatestItems = getLatest("*","items","Item_ID",$latestItemsNum);
        // 3- The "Latest Registered Comments" of "Panel 2 body" [ Number of Latest Comments ]
        $latestCommentsNum = 2 ;
        // 3- Latest Comments Array 
        $theLatestComments = getLatest("*","comments","c_id",$latestCommentsNum);
?>
        <!-- ++++++++++++++ Statistics ++++++++++++++ -->
        <div class="home-statis">
            <div class='container text-center'>
                <div class='row'>
                    <h1>Dashboard</h1> 
                    <div class="row">
                        <!-- Box 1 -->
                        <div class="col-sm-6 col-md-3">
                            <div class="statis st-members">
                                <i class="fa fa-users"></i>
                                <div class="info">
                                    Total Members
                                    <span>
                                        <a href="members.php"> <?php echo countItems('UserID','users') ?> </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Box 2 -->
                        <div class="col-sm-6 col-md-3">
                            <div class="statis st-pending">
                                <i class="fa fa-user-plus"></i>
                                <div class="info">
                                    Pending Members
                                    <span> 
                                        <a href="members.php?do=Manage&page=Pending"><?php echo checkItem('RegStatus','users',0) ?></a> 
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Box 3 -->
                        <div class="col-sm-6 col-md-3">
                            <div class="statis  st-items">
                                <i class="fa fa-tag"></i>
                                <div class="info">
                                    Total Items
                                    <span>
                                        <a href="items.php"> <?php echo countItems('Item_ID','items') ?> </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Box 4 -->
                        <div class="col-sm-6 col-md-3">
                            <div class="statis  st-comments">
                                <i class="fa fa-comments"></i>
                                <div class="info">
                                    Total Comments
                                    <span>
                                        <a href="comments.php"> <?php echo countItems('c_id','comments') ?> </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ++++++++++++++ Panel ++++++++++++++ -->
        <div class="latest">
            <div class="container">
                <!-- ######### Row 1 : Latest [ Registered Users ] && [ Added Items ] ######### -->
                <div class="row">
                    <!-- ========= panel 1 ========= -->
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="panel panel-default">
                            <!-- panel heading -->
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> 
                                Latest <?php echo $latestUsersNum ?> Registered Users
                                <!-- Toggle Icon -->
                                <span class="toggleInfo pull-right">
                                    <i class="fa fa-minus fa-lg"></i>
                                </span>
                            </div>
                            <!-- panel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                        // Check if There are "latestUsers" or Not
                                        if( !empty( $theLatestUsers ) )
                                        {
                                            foreach( $theLatestUsers as $user )
                                            {
                                                echo"<li>".$user['Username'].
                                                       "<a href='members.php?do=Edit&userid=".$user['UserID']."'>
                                                            <span class='btn btn-success pull-right'>
                                                                <i class='fa fa-edit'></i> 
                                                                Edit
                                                            </span> 
                                                        </a>";
                                                            
                                                        if( $user['RegStatus'] == 0 )
                                                        { 
                                                            echo "<a href='members.php?do=Activate&userid=".$user['UserID']."'>
                                                                    <span class='btn btn-info pull-right'>
                                                                        <i class='fa fa-edit'></i> 
                                                                        Activate
                                                                    </span> 
                                                                  </a>";
                                                        } 
                                                        echo "<a href='members.php?do=Delete&userid=".$user['UserID']."'>
                                                                    <span class='btn btn-danger pull-right'>
                                                                        <i class='fa fa-delete'></i> 
                                                                        Delete
                                                                    </span> 
                                                              </a>";
                                                echo"</li>";
                                            }      
                                        }
                                        else
                                        {
                                            echo "There's No Record To Show";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div> 
                    <!-- ========= panel 2 ========= -->
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="panel panel-default">
                            <!-- panel heading -->
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i>  
                                Latest <?php echo $latestItemsNum ?> Added Items
                                <!-- Toggle Icon -->
                                <span class="toggleInfo pull-right">
                                    <i class="fa fa-minus fa-lg"></i>
                                </span>
                            </div>
                            <!-- panel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                        // Check if There are "latestUsers" or Not
                                        if( !empty( $theLatestItems ) )
                                        {
                                            foreach( $theLatestItems as $item )
                                            {
                                                echo"<li>".$item['Name'].
                                                    "<a href='items.php?do=Edit&itemid=".$item['Item_ID']."'>
                                                            <span class='btn btn-success pull-right'>
                                                                <i class='fa fa-edit'></i> 
                                                                Edit
                                                            </span> 
                                                        </a>";
                                                            
                                                        if( $item['Approve'] == 0 )
                                                        { 
                                                            echo "<a href='items.php?do=Approve&itemid=".$item['Item_ID']."'>
                                                                    <span class='btn btn-info pull-right'>
                                                                        <i class='fa fa-edit'></i> 
                                                                        Approve
                                                                    </span> 
                                                                </a>";
                                                        } 
                                                        echo "<a href='items.php?do=Delete&itemid=".$item['Item_ID']."'>
                                                                    <span class='btn btn-danger pull-right'>
                                                                        <i class='fa fa-delete'></i> 
                                                                        Delete
                                                                    </span> 
                                                            </a>";
                                                echo"</li>";
                                            }   
                                        }   
                                        else
                                        {
                                            echo "There's No Record To Show";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>  
                    </div>
                </div>
                <!-- ######### Row 2 : Latest [ Comments ] ######### -->
                <div class="row">
                    <!-- ========= panel 1 ========= -->
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="panel panel-default">
                            <!-- panel heading -->
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i> 
                                Latest <?php echo $latestCommentsNum ?>  Comments
                                <!-- Toggle Icon -->
                                <span class="toggleInfo pull-right">
                                    <i class="fa fa-minus fa-lg"></i>
                                </span>
                            </div>
                            <!-- panel body -->
                            <div class="panel-body">
                                <?php
                                    // Select "All Comments" 
                                    $stmt = $con->prepare("SELECT 
                                                                comments.*     ,
                                                                users.Username 
                                                            FROM 
                                                                comments
                                                            INNER JOIN 
                                                                users 
                                                            ON 
                                                                users.UserID = comments.user_id
                                                            ORDER BY 
                                                                c_id DESC 
                                                            LIMIT $latestCommentsNum
                                                         "); 
                                        // Execute the Statement 
                                        $stmt->execute(); 
                                        // Assign "DB values" to "variables"
                                        $comments = $stmt->fetchAll();
                                        // Check if There are "latestUsers" or Not
                                        if( !empty( $theLatestItems ) )
                                        {
                                            foreach($comments as $comment)
                                            {
                                                echo"<div class='comment-box'>";
                                                    echo "<a href='members.php?do=Edit&userid=".$comment['user_id']."' >
                                                            <span class='member-n'>".$comment['Username']."</span>
                                                        </a>";
                                                    echo '<p class="member-c">'.$comment['comment'].'</p>';
                                                echo"</div>";
                                            }
                                        }   
                                        else
                                        {
                                            echo "There's No Record To Show";
                                        }
                                ?>
                            </div>
                        </div>
                    </div>      
                </div>
            </div> 
        </div>
<?php
        // +++++++++++++++++++++++++ End Dashboard Page +++++++++++++++++++++++++

        include $tpl."footer.php";

    } 
    // if you aren't "logged in" , You can't access "dashboard page" Then Go to "login page"
    else 
    {
        header('location:index.php');
        exit();
    }
    // output buffering end
    ob_end_flush();
?>