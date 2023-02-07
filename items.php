<?php
    // Start Output Buffering
    ob_start();
    // start session
    session_start();
    // page name
    $pageTitle = "Show Items";
    // include "init.php" file
    include "init.php";
    // I want to make sure that the "itemid" in "URL" is "Number" 
    // Check if [ Get Request "itemid" in "URL" is "not Empty" && "itemid" value is "numeric" ] and [ Get integer value of it ]
    $itemidVar = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
    // Select All Data Depend On 
    $stmt = $con->prepare("SELECT 
                                items.*         , 
                                categories.Name As category_name ,
                                users.Username
                            FROM 
                                items
                            INNER JOIN 
                                categories
                            ON 
                                categories.ID = items.Cat_ID 
                            INNER JOIN 
                                users
                            ON 
                                users.UserID = items.Member_ID 
                            WHERE 
                                Item_ID = ?
                            AND 
                                Approve = 1
                         ");
    // Execute Query
    $stmt->execute( array($itemidVar) );
    // Number of Effected Rows
    $count = $stmt->rowCount();
    // if $count = 0 ["itemid" not exist in DB] then "Show Error Message" , else ["itemid" exist in DB] then "Show Form"
    if( $count > 0 )
    {
        // Fetch The Data
        $item = $stmt->fetch();
?>
    <!-- "Profile Page" Heading -->
    <h1 class="text-center"> <?php echo $item['Name'] ?> </h1>
    <div class="container">
        <!-- "Item Info" Row -->
        <div class="row">
            <div class="col-md-3">
                <img src='img.png' alt='image' class='img-responsive img-thumbnail center-block' />
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span> : 
                        <?php echo $item['Add_Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price</span> : $<?php echo $item['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-map-marker fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span> : 
                        <a href='categories.php?pageid=<?php echo $item['Cat_ID']; ?>'>
                            <?php echo $item['category_name']; ?>
                        </a> 
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span> : 
                        <a href="profile.php"> <?php echo $item['Username'] ?> </a>
                    </li>
                    <li>
                        <i class="fa fa-link fa-fw"></i>
                        <span>Tags</span> : 
                        <?php
                            // Convert "Tags string" To "Array"
                            $allTags = explode(',', $item['tags']);
                            foreach ( $allTags as $tag )
                            {
                                // Remove "space" from any tag
                                $tag = str_replace(' ','',$tag);
                                // Convert Tag to lowercase
                                $lowerTag = strtolower($tag);
                                // if $tag is Not empty
                                if(!empty($tag))
                                {
                                    echo "<a class='tagName' href='tags.php?name={$lowerTag}'>".$tag."</a>";
                                }
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <!-- If "User" is "Login" in Your Website , Allow User To Make Comment -->
    <?php 
        if( isset($_SESSION['user']) ) 
        { ?>
            <!-- ++++++++++++++++++++++++++++ Start "Add Comment" Section ++++++++++++++++++++++++++++ -->
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'].'?itemid='.$item['Item_ID'] ?>" method="Post">
                            <textarea name="comment" required></textarea>
                            <input type="submit" class="btn btn-primary" value="Add Comment">
                        </form>
                        <!-- if "User" comes from "comment Form" -->
                        <?php
                            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
                            {
                                $comment = strip_tags($_POST['comment']);
                                $itemid  = $item['Item_ID'];
                                // "User" That Write The Comment Not "The owner of item"
                                $userid  = $_SESSION['uid'];
                                // If "textarea of comment" is Not Empty , Store "The Comment" in DB
                                if( !empty($comment) )
                                {
                                    $stmt = $con->prepare('INSERT INTO 
                                                                comments(comment,status,comment_date,item_id,user_id) 
                                                            VALUES( :commentParam , 0 , now() , :itemIdParam , :userIdParam) 
                                                         ');
                                    $stmt->execute( array(
                                                            'commentParam' => $comment  ,
                                                            'itemIdParam'  => $itemid   ,
                                                            'userIdParam'  => $userid
                                                         )
                                                  );
                                    $count = $stmt->rowCount();
                                    if( $count > 0 )
                                    {
                                        echo '<div class="alert alert-success">Comment Added Successfully</div>';
                                    }
                                    else
                                    {
                                        echo '<div class="alert alert-danger">Comment Added Faild</div>';
                                    }
                                }
                                else
                                {
                                    echo '<div class="alert alert-danger">You Must Add Comment</div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- ++++++++++++++++++++++++++++ End "Add Comment" Section ++++++++++++++++++++++++++++ -->
    <?php }
          else
          {
            echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>
                                <a href='login.php'>Login</a> Or <a href='register.php'>Register</a> To Add Comment
                            </div>";
            echo "</div>";
          } 
    ?>
        <hr class="custom-hr">
        <?php 
            // Select "All Members" Except "Admins"
            $stmt = $con->prepare("SELECT 
                                        comments.*     ,
                                        users.Username  As  Member
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.UserID = comments.user_id
                                    WHERE
                                        item_id = ?
                                    AND 
                                        status = 1
                                    ORDER BY 
                                        c_id DESC
                                "); 
            // Execute the Statement
            $stmt->execute( array($item['Item_ID']) ); 
            // Assign "DB values" to "variables"
            $comments = $stmt->fetchAll();
        ?>
        <!-- "Item Comments" Row -->
        <?php
            // ++++++++++++++++++++++++++++ Start "Users Comments" Section ++++++++++++++++++++++++++++ 
            foreach ( $comments as $comment )
            {
                echo "<div class='comment-box'>";
                    echo "<div class='row'>";
                        // ++++++++++++++++ Left Part : Comment Image ++++++++++++++++
                        echo "<div class='col-xs-2 text-center'>";
                            echo"<img src='img.png' alt='image' class='img-responsive img-thumbnail img-circle center-block' />";
                            echo "<span>".$comment['Member']."</span>";
                        echo "</div>";
                        // ++++++++++++++++ Right Part : Comment Info ++++++++++++++++
                        echo "<div class='col-xs-10'>";
                            echo "<p class='lead'>".$comment['comment']."</p>";
                        echo "</div>"; 
                    echo "</div>";
                echo "</div>";
                echo "<hr class='custom-hr' />";
            }
            // ++++++++++++++++++++++++++++ End "Users Comments" Section ++++++++++++++++++++++++++++ 
        ?>
    </div>    
<?php
    }
    // Else ["item id" doesn't exist in DB] then "Show Error Message"
    else
    {
        echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>There is no Such id Or This Item Is Waiting Approval</div>";
            redirectHome($theMsg,6);
        echo "</div>";
    }
    // include "footer.php" file
    include $tpl."footer.php";   
    // End Output Buffering
    ob_end_flush();

?>



