<?php
    /*
        ==================================================
        - Manage Members Page
        - You Can Add | Edit | Delete Members From Here
        ==================================================
    */
    // Start Session
    session_start();
    // Page Title
    $pageTitle="Members";
    // 1- if "user" is "Login" 
    if( isset($_SESSION['Username']) )
    {
        include 'init.php';
        // Check The "Query String" of URL
        $do = isset( $_GET['do'] ) ? $_GET['do'] :  $do = 'Manage' ;        
        // ++++++++++++++++++++++++++++++++++++++++++ Manage Members Page ++++++++++++++++++++++++++++++++++++++++++
        if( $do == 'Manage' ) 
        { 
            $query = '';
            // if [query string = "?page"] , Then Appear "pending members" [ members with 'RegStatus = 0' ]
            if( isset($_GET['page']) && $_GET['page'] == 'Pending')
            {   
                $query = 'AND RegStatus = 0';
            }
            // Select "All Members" Except "Admins"
            $stmt = $con->prepare(" SELECT 
                                        * 
                                    FROM 
                                        users 
                                    WHERE 
                                        GroupID != 1  
                                    $query
                                    ORDER BY 
                                        UserID DESC
                                 "); 
            // Execute the Statement
            $stmt->execute(); 
            // Assign "DB values" to "variables"
            $rows = $stmt->fetchAll();
            // Check if "There are any rows" in the DB
            if( !empty($rows) )
            {
      ?>
                <h1 class="text-center editFormHead">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center mainTable manage-members">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Avatar</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Registered Date</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ( $rows as $row )
                                    {
                                        echo"<tr>";
                                            echo"<td>".$row['UserID']."</td>";
                                            echo"<td>";
                                                if( empty($row['avatar']) )
                                                {
                                                    echo"<img src='uploads/avatars/avatar.png' alt='avatar image'/>";
                                                }
                                                else
                                                {
                                                    echo"<img src='uploads/avatars/".$row['avatar']."' alt='avatar image'/>";
                                                }
                                            echo"</td>";
                                            echo"<td>".$row['Username']."</td>";
                                            echo"<td>".$row['Email']."</td>";
                                            echo"<td>".$row['FullName']."</td>";
                                            echo"<td>".$row['RegisterDate']."</td>";
                                            echo"<td>
                                                        <a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                        <a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>
                                                ";
                                            if( $row['RegStatus'] == 0 )
                                            { 
                                                echo "<a href='members.php?do=Activate&userid=".$row['UserID']."' class='btn btn-info'><i class='fa fa-check-square-o'></i> Activate</a>";
                                            } 
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-offset-5">
                        <a href='?do=Add' class='btn btn-primary'><i class="fa fa-plus"></i> New Member</a>
                    </div>
                </div>
      <?php }
            else
            {
                echo'<div class="container">';
                    echo"<div class='alert alert-info'>There's No Members To Show</div>";
                    echo"<a href='?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> New Member</a>";
                echo'</div>';
            }
      ?>
  <?php }
        // ++++++++++++++++++++++++++++++++++++++++++ Add Members Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Add' ) 
        { ?>
            <h1 class="text-center editFormHead">Add New Member</h1>
            <div class="container">
                <form action="?do=Insert" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 col-md-2 control-label">Username</label>
                        <!-- Username -->
                        <div class="col-sm-10 col-md-10">
                            <input type="text" name="username" class="form-control" required="required" placeholder="Username To Login Into Shop" />
                        </div>
                    </div>
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 col-md-2 control-label">Password</label> 
                        <div class="col-sm-10 col-md-10">
                            <!-- password -->
                            <input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="Password Must Be Hard & Complex" />
                            <!-- Show/Hide Password -->
                            <i class="fa fa-eye fa-2x" id="showPass"></i>
                        </div>
                    </div>
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 col-md-2 control-label">Email</label>
                        <!-- Email -->
                        <div class="col-sm-10 col-md-10">
                            <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
                        </div>
                    </div>
                    <!-- Start FullName Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 col-md-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page" />
                        </div>
                    </div>
                    <!-- Start "Profile Image" Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 col-md-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="file" name="avatar" class="form-control" required="required" />
                        </div>
                    </div>
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                </form>
            </div>
  <?php }
        // ++++++++++++++++++++++++++++++++++++++++++ Insert Members Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Insert' ) 
        {            
            // Check if 'user' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )  
            {
                echo '<h1 class="text-center updateFormHead">Insert Member</h1>';
                echo "<div class='container'>";
                // Upload "Avatar Image"
                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTmp  = $_FILES['avatar']['tmp_name'];
                $avatarType = $_FILES['avatar']['type'];
                // List of Allowed Image File Types To Upload
                $avatarAllowedExtension = array('jpg', 'jpeg', 'png', 'gif');
                // Get "Avatar Extension" by convert "imageType" to "array" and Get the "second element" of "array"
                $avatarExtensionArr = explode('.',$_FILES['avatar']['name']);
                // if User Upload "Avatar Image"
                if( !empty($avatarName) )
                {
                    // Convert "avatar extension" to "lowercase" 
                    $avatarExtension = strtolower($avatarExtensionArr[1]);
                }                
                // Get the variables from the Form
                $user  = $_POST['username'];
                $pass  = $_POST['password'];
                $email = $_POST['email'];
                $name  = $_POST['full'];
                $hashedPass = sha1($_POST['password']);
                // +++++++ Validate The Form +++++++
                $formErrors = array();
                // 1- Username Validation
                if( empty($user) )
                {
                    $formErrors[]="Username Can't Be <strong>Empty</strong>";
                }
                // 1.1- Minimum number of characters of username is 4 characters
                if( strlen($user) < 4 )
                {
                    $formErrors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
                }
                // 1.2- Maximum number of characters of username is 20 characters
                if( strlen($user) > 20 )
                {
                    $formErrors[] = "Username Can't Be More Than <strong>20 Characters</strong>";
                }
                // 2- FullName Validation
                if( empty($name) )
                {
                    $formErrors[]="FullName Can't Be <strong>Empty</strong>";
                }
                // 3- Password Validation
                if( empty($pass) )
                {
                    $formErrors[]="Password Can't Be <strong>Empty</strong>";
                }
                // 4- Email Validation   
                if( empty($email) )
                {
                    $formErrors[]="Email Can't Be <strong>Empty</strong>";
                }
                // 5- if User "Not Upload Avatar Image" 
                if( empty($avatarName) )
                {
                    $formErrors[] = "You Must Upload <strong>Avatar</strong>";
                }
                // 6- if User Upload Avatar Image with  "Size > 4 MegaBytes" [ Max-size = 4 MB] 
                if( $avatarSize > 4194304 )
                {
                    $formErrors[] = "Avatar Size Can't Be Larger Than <strong>4MB</strong>";
                }
                // 7- if User "Upload Avatar Image" And "avatar extension" Not exists in "allowed extensions array"
                if( ! empty($avatarName) && ! in_array( $avatarExtension , $avatarAllowedExtension ) )
                {
                    $formErrors[] = "This Avatar Extension is Not <strong>Allowed</strong>";
                }
                // Printing Array of Form Errors
                foreach( $formErrors as $error )
                {
                    echo "<div class='alert alert-danger'>".$error."</div>" ;
                } 
                // ++++++++++++++ if There are No errors , Execute The "Update" ++++++++++++++
                if( empty($formErrors) )
                {
                    // "User Avatar Image" , "avatar image name" = "randomNumberFrom0To1000000_$avatarName" 
                    $avatar = rand(0,1000000)."_".$avatarName;
                    // Move "Avatar Image" From "Temporary Folder" To "uploads/avatars Folder"
                    move_uploaded_file($avatarTmp , "uploads/avatars/".$avatar);
                    // +++++ Before Inserting in DB : Check if "username" Exists in DB Previously Or Not +++++
                    $checkRes = checkItem("Username","users",$user);
                    // Username exists Previously in DB
                    if( $checkRes > 0 ) 
                    {
                        $theMsg = "<div class='alert alert-danger'>Sorry ): This User is Existing</div>";
                        // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "3 seconds"]
                        redirectHome($theMsg,'back',3);
                    }
                    // Username Not exist Previously in DB
                    else
                    {
                        // ------------ Insert "Userinfo" [ New Member ] in Database ------------
                        $stmt = $con->prepare(" INSERT INTO 
                                                    users(Username , Password , Email , FullName , RegStatus , RegisterDate , avatar) 
                                                VALUES( :usernamePara , :passwordPara , :emailPara , :fullNamePara , 1 , now() , :avatarPara ) ");
                        // Execute Query
                        $stmt->execute( array(
                                                'usernamePara' => $user        , 
                                                'passwordPara' => $hashedPass  , 
                                                'emailPara'    => $email       , 
                                                'fullNamePara' => $name        ,
                                                'avatarPara'   => $avatar 
                                            ) 
                                        ); 
                        // if $count = 0 [Query Not Execute in DB] then "Show Error Message" , else "Query is executed Successfully"
                        $count = $stmt->rowCount();  
                        if( $count > 0 )
                        {
                            $theMsg = "<div class='alert alert-success'>".$count.' Record Inserted Successfully </div>';
                            // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "3 seconds"]
                            redirectHome($theMsg,'back',3);
                        }   
                        else
                        {
                            echo "<div class='container'>";
                            $theMsg = "<div class='alert alert-danger'>Record Insert Failed </div>";
                            // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "5 seconds"]
                            redirectHome($theMsg,'back',5);
                            echo "</div>";
                        }   
                    }
                }
            }
            else
            {
                echo "<div class='container'>";
                $theMsg =  "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The Home Page" After "10 seconds"]
                redirectHome($theMsg,10);
                echo "</div>";
            }
            echo "</div>";
        }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Edit Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Edit' ) 
        {
            // I want to make sure that the "userid" in "URL" is "Number" 
            // Check if [ Get Request "userid" in "URL" is "not Empty" && "userid" value is "numeric" ] and [ Get integer value of it ]
            $useridVar = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
            // Select All Data Depend On 
            $stmt = $con->prepare("SELECT * FROM users WHERE `UserID` = ? LIMIT 1 ");
            // Execute Query
            $stmt->execute( array($useridVar) );
            // Fetch The Data
            $row = $stmt->fetch();
            // if $count = 0 ["userid" not exist in DB] then "Show Error Message" , else ["userid" exist in DB] then "Show Form"
            $count = $stmt->rowCount();
            if( $count > 0 )
            { ?>           
                <h1 class="text-center editFormHead">Edit Member</h1>
                <div class="container">
                    <form action="?do=Update" class="form-horizontal" method="POST">
                        <!-- send 'user id' to 'Update Page' using 'hidden inputField' -->
                        <input type="hidden" name="userid" value="<?php echo $useridVar ?>">
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Username</label>
                            <div class="col-sm-10 col-md-10">
                                <input type="text" name="username" required="required" class="form-control" value="<?php echo $row['Username'] ?>" />
                            </div>
                        </div>
                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Password</label> 
                            <div class="col-sm-10 col-md-10">
                                <!-- old password -->
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                <!-- new password -->
                                <input type="password" name="newpassword" class="form-control" placeholder="Leave Blank If You Don't Want To Change" autocomplete="new-password" />
                            </div>
                        </div>
                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Email</label>
                            <div class="col-sm-10 col-md-10">
                                <input type="email" name="email" required="required" value="<?php echo $row['Email'] ?>" class="form-control" />
                            </div>
                        </div>
                        <!-- Start FullName Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Full Name</label>
                            <div class="col-sm-10 col-md-10">
                                <input type="text" name="full" required="required" value="<?php echo $row['FullName'] ?>" class="form-control" />
                            </div>
                        </div>
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>
            <?php 
            }
            // Else ["userid" doesn't exist in DB] then "Show Error Message"
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
                echo '<h1 class="text-center updateFormHead">Update Member</h1>';
                echo "<div class='container'>";
                // Get the "inputFields value" from the "Edit Form"
                // 1- get 'user id' from 'hidden inputField' in Form
                $id    = $_POST['userid'];
                $user  = $_POST['username'];
                $email = $_POST['email'];
                $name  = $_POST['full'];
                $pass = '';
                // +++++++ Password inputField Trick +++++++
                // ["new password" inputField is "Empty" Then $pass = "oldpassword inputField"] Else [ Encrypt the new password and store it in $pass ]
                $pass = empty( $_POST['newpassword'] ) ? $_POST['oldpassword'] : sha1($_POST['newpassword']); 
                // +++++++ Validate The Form +++++++
                $formErrors = array();
                // 1- Username Validation
                if( empty($user) )
                {
                    $formErrors[]="Username Can't Be <strong>Empty</strong>";
                }
                // 1.1- Minimum number of characters of username is 4 characters
                if( strlen($user) < 4 )
                {
                    $formErrors[] = "Username Can't Be Less Than <strong>4 Characters</strong>";
                }
                // 1.2- Maximum number of characters of username is 20 characters
                if( strlen($user) > 20 )
                {
                    $formErrors[] = "Username Can't Be More Than <strong>20 Characters</strong>";
                }
                // 2- FullName Validation
                if( empty($name) )
                {
                    $formErrors[]="FullName Can't Be <strong>Empty</strong>";
                }
                if( empty($email) )
                {
                    $formErrors[]="Email Can't Be <strong>Empty</strong>";
                }
                // Printing Array of Form Errors
                foreach( $formErrors as $error )
                {
                    echo "<div class='alert alert-danger'>".$error."</div>" ;
                } 
                //  if There are No errors , Execute The "Update" 
                if( empty($formErrors) )
                {
                    $stmt = $con->prepare("SELECT 
                                                * 
                                            FROM 
                                                users 
                                            WHERE 
                                                Username = ? 
                                            AND 
                                                UserID != ?");
                    $stmt->execute( array($user,$id) );
                    $count = $stmt->rowCount();
                    if( $count == 1 )
                    {
                        echo "<div class='container'>";
                                $theMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                                // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page"]
                                redirectHome($theMsg,'back');
                            echo "</div>";
                    }
                    else
                    {
                            // Update The "DB" with "New User Data"
                        $stmt = $con->prepare("UPDATE users SET `Username` = ? , `Email` = ? , `FullName` = ? , `Password` = ? WHERE UserID = ? ");
                        // Execute Query
                        $stmt->execute( array($user , $email , $name , $pass , $id) );
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
            echo '<h1 class="text-center updateFormHead">Delete Member</h1>';
            echo "<div class='container'>";
                // Check if [Get Request "userid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $useridVar = isset( $_GET['userid'] ) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
                // Select "All users data" Depend on This "ID"
                $check = checkItem("UserID","users",$useridVar);
                // If There's Such ID , Show The Form
                if( $check > 0 )
                {
                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = ? ");
                    $stmt->execute(array($useridVar));
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Deleted Successfully </div>';
                    // Appear [ErrorMessage=$theMsg] and [Redirect To "The Back Page"]
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
        // ++++++++++++++++++++++++++++++++++ Activate Page ++++++++++++++++++++++++++++++++++
        elseif( $do == "Activate")
        {
            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";
                // Check if [Get Request "userid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $useridVar = isset( $_GET['userid'] ) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
                // Select "All users data" Depend on This "ID"
                $check = checkItem("UserID","users",$useridVar);
                // If There's Such ID Show The Form
                if( $check > 0 )
                {
                    $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
                    $stmt->execute( array($useridVar) );
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Activity Successfully </div>';
                    // Appear [Message=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg);
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