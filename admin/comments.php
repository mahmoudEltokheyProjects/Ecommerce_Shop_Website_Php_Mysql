<?php
    /*
        ==================================================
        - Manage Comments Page
        - You Can Edit | Delete | Approve Comments From Here
        ==================================================
    */
    // Start Session
    session_start();
    // Page Title
    $pageTitle="Comments";
    // 1- if "user" is "Login" 
    if( isset($_SESSION['Username']) )
    {   
        include 'init.php';
        // Check The "Query String" of "URL"
        $do = isset( $_GET['do'] ) ? $_GET['do'] :  $do = 'Manage' ;        
        // ++++++++++++++++++++++++++++++++++++++++++ Manage Comments Page ++++++++++++++++++++++++++++++++++++++++++
        if( $do == 'Manage' ) 
        { 
            // Select "All Members" Except "Admins"
            $stmt = $con->prepare("SELECT 
                                        comments.*     ,
                                        users.Username ,
                                        items.Name
                                    FROM 
                                        comments
                                    INNER JOIN 
                                        items 
                                    ON 
                                        items.Item_ID = comments.item_id
                                    INNER JOIN 
                                        users 
                                    ON 
                                        users.UserID = comments.user_id
                                    ORDER BY 
                                        c_id DESC
                                 "); 
            // Execute the Statement
            $stmt->execute(); 
            // Assign "DB values" to "variables"
            $comments = $stmt->fetchAll();
            // Check if "There are any rows" in the DB
            if( !empty($comments) )
            {
       ?>
                <h1 class="text-center editFormHead">Manage Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center mainTable">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Comment</th>
                                    <th>Item Name</th>
                                    <th>User Name</th>
                                    <th>Added Date</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ( $comments as $comment )
                                    {
                                        echo"<tr>";
                                            echo"<td>".$comment['c_id']."</td>";
                                            echo"<td>".$comment['comment']."</td>";
                                            echo"<td>".$comment['Name']."</td>";
                                            echo"<td>".$comment['Username']."</td>";
                                            echo"<td>".$comment['comment_date']."</td>";
                                            echo"<td>
                                                        <a href='comments.php?do=Edit&commentid=".$comment['c_id']."' class='btn btn-success'>
                                                            <i class='fa fa-edit'></i> Edit
                                                        </a>
                                                        <a href='comments.php?do=Delete&commentid=".$comment['c_id']."' class='btn btn-danger confirm'>
                                                            <i class='fa fa-close'></i> Delete
                                                        </a>
                                                ";
                                                if( $comment['status'] == 0 )
                                                { 
                                                
                                                    echo"<a href='comments.php?do=Approve&commentid=".$comment['c_id']."' class='btn btn-info'>
                                                            <i class='fa fa-check-square-o'></i> Approve
                                                        </a>";
                                                } 
                                            echo "</td>";
                                        echo"</tr>";
                                    }
                                ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
      <?php }
            else
            {
                echo'<div class="container">';
                    echo"<div class='alert alert-info'>There's No Comments To Show</div>";
                echo'</div>';
            }
      ?>
  <?php }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Edit Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Edit' ) 
        {
            // I want to make sure that the "commentid" in "URL" is "Number" 
            // Check if [ Get Request "commentid" in "URL" is "not Empty" && "commentid" value is "numeric" ] and [ Get integer value of it ]
            $commentidVar = isset($_GET['commentid']) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ;
            // Select All Data Depend On 
            $stmt = $con->prepare("SELECT * FROM comments WHERE `c_id` = ?");
            // Execute Query
            $stmt->execute( array($commentidVar) );
            // Fetch The Data
            $row = $stmt->fetch();
            // if $count = 0 ["commentid" not exist in DB] then "Show Error Message" , else ["commentid" exist in DB] then "Show Form"
            $count = $stmt->rowCount();
            if( $count > 0 )
            {?>           
                <h1 class="text-center editFormHead">Edit Comment</h1>
                <div class="container">
                    <form action="?do=Update" class="form-horizontal" method="POST">
                        <!-- send 'comment id' to 'Update Page' using 'hidden inputField' -->
                        <input type="hidden" name="commentid" value="<?php echo $commentidVar ?>">
                        <!-- Start Comment Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Comment</label>
                            <div class="col-sm-10 col-md-10">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                            </div>
                        </div>
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
            <?php
                // Select "All Comments" 
                $stmt = $con->prepare(" SELECT 
                                            comments.*     ,
                                            users.Username ,
                                            items.Name
                                        FROM 
                                            comments
                                        INNER JOIN 
                                            items 
                                        ON 
                                            items.Item_ID = comments.item_id
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.UserID = comments.user_id
                                     "); 
            // Execute the Statement
            $stmt->execute(); 
            // Assign "DB values" to "variables"
            $rows = $stmt->fetchAll();
            ?>
            <?php 
            }
            // Else ["Comment id" doesn't exist in DB] then "Show Error Message"
            else
            {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There is no Such id</div>";
                redirectHome($theMsg,6);
                echo "</div>";
            }
        }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Update Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Update' ) 
        {
            // Check if 'user' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                echo '<h1 class="text-center updateFormHead">Update Comment</h1>';
                echo "<div class='container'>";
                // Get the "inputFields value" from the "Edit Form"
                // 1- get 'comment id' from 'hidden inputField' in Form
                $comidvar   = $_POST['commentid'];
                $comment    = $_POST['comment'];
                // Update The "DB" with "New comment Data"
                $stmt = $con->prepare("UPDATE comments SET `comment` = ? WHERE c_id = ? ");
                // Execute Query
                $stmt->execute( array($comment , $comidvar) );
                // if $count = 0 [ Query Not Execute in DB] then "Show Error Message" , else "Query is executed Successfully"
                $count = $stmt->rowCount();  
                if( $count > 0 )
                {
                    echo "<div class='container'>";
                        $theMsg = "<div class='alert alert-success'>".$count.' Record Updated Successfully </div>';
                        // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "6 seconds"]
                        redirectHome($theMsg,'back',6);
                    echo "</div>";
                }   
                else
                {
                    echo "<div class='container'>";
                        $theMsg = "<div class='alert alert-danger'>Record Update Failed </div>";
                        // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page"]
                        redirectHome($theMsg,'back');
                    echo "</div>";
                }   
                
            }
            else
            {
                $theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The Home Page" After "2 seconds"]
                redirectHome($theMsg,2);
            }
            echo "</div>";
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Delete Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Delete' ) 
        {
            echo '<h1 class="text-center updateFormHead">Delete Comment</h1>';
            echo "<div class='container'>";
                // Check if [Get Request "commentid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $comidVar = isset( $_GET['commentid'] ) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ;
                // Select "All comments data" Depend on This "ID"
                $check = checkItem("c_id","comments",$comidVar);
                // If There's Such ID , Show The Form
                if( $check > 0 )
                {
                    // Delete Comment with id = $comidVar
                    $stmt = $con->prepare("DELETE FROM comments WHERE c_id = ? ");
                    $stmt->execute(array($comidVar));
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Deleted Successfully </div>';
                    // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page"]
                    redirectHome($theMsg,'back');
                }
                else
                {
                    $theMsg = "<div class='alert alert-danger'>Sorry This Id is not Exist</div>";
                    // Appear [ErrorMessage=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg);
                }
            echo "</div>";
        }               
        // ++++++++++++++++++++++++++++++++++ Approve Page ++++++++++++++++++++++++++++++++++
        elseif( $do == "Approve")
        {
            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";
                // Check if [Get Request "commentid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $commentidVar = isset( $_GET['commentid'] ) && is_numeric($_GET['commentid']) ? intval($_GET['commentid']) : 0 ;
                // Select "All users data" Depend on This "ID"
                $check = checkItem("c_id","comments",$commentidVar);
                // If There's Such ID Show The Form
                if( $check > 0 )
                {
                    $stmt = $con->prepare("UPDATE comments SET `status` = 1 WHERE c_id = ?");
                    $stmt->execute( array($commentidVar) );
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Approved Successfully </div>';
                    // Appear [Message=$theMsg] and [Redirect To "The back Page"]
                    redirectHome($theMsg,'back');
                }
                else
                {
                    $theMsg = "<div class='alert alert-danger'>Sorry This Id is not Exist</div>";
                    // Appear [Message=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg);
                }
            echo "</div>"; 
        }
        // ++++++++++++++++ Footer Page ++++++++++++++++
        include $tpl.'footer.php'; 
        }
        else
        {
            header('Location:index.php');
            exit();
        }

    ?>