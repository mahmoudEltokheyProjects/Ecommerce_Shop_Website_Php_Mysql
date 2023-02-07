<?php
    // Start Output Buffering
    ob_start();
    // start session 
    session_start();
    // $pageTitle variable
    $pageTitle = "Login";
    // User Session : if "user" was "login" , Go to 'index.php' and don't show "login page"
    if( isset($_SESSION['user']) )
    {
        header('location: index.php');
    } 
    // include "init.php" File
    include "init.php";
    // 1- Check if "user" comming from "Http Post" Request [ Login Form ] Then Create Session For User
    if( $_SERVER['REQUEST_METHOD'] == 'POST' )
    {
        // +++++++++++++ if user comes from "login Form" +++++++++++++
        if( isset($_POST['login']) )
        {
            $userVar = $_POST['username'];
            $passVar = $_POST['password'];
            // hashing password and store it in DB
            $hashedPassVar = sha1($passVar);
            // Check if ["person" Exist in DB] And ["person" is "Admin" ( GroupID = 1 ) ]
            $stmt = $con->prepare("SELECT 
                                        `UserID` , `Username` , `Password` 
                                    FROM 
                                        users 
                                    WHERE 
                                        `Username` = ? 
                                    AND 
                                        `Password` = ? ");
            // Execute the query
            $stmt->execute(array($userVar,$hashedPassVar));
            // Store "User data" 
            $get = $stmt->fetch();
            // Store "Number Of Effected Rows" 
            $count = $stmt->rowCount();
            // if $count = 0 then "username and password" [ not exist ] in DB ,     
            if( $count > 0 )
            {
                // 1- store "username" in session
                $_SESSION['user'] = $userVar;    // Register "session Username" with "UserName from Login Form"
                // 2- store "UserID" in session
                $_SESSION['uid'] = $get['UserID'];  // Register "session UserID" with "User ID From DB"
                // 3- When clicking "Login btn" , Redirect to "index page"
                header('location: index.php');
                // 4- exit from condition
                exit();
            }
            // else "username and password" [ exist ] in DB
            else 
            {
            }
        }
        // +++++++++++++ if "user" comes from "Signup Form" +++++++++++++
        else 
        {
            // Error List
            $formErrors = [];
            // Form Data
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $email = $_POST['email'];
            // 1- Username Filteration
            if( isset($username) )
            {
                $filterdUsername = strip_tags( $username );
                if( strlen($filterdUsername) < 4 )
                {
                    $formErrors[] = "Username must be at least 4 characters <br/>";
                }
            }
            // 2- Password Filteration
            if( isset($password) && isset($password2) )
            {
                // Check if "password inputField" is empty or "Not" 
                if( empty($password) )
                {
                    $formErrors[] = 'Sorry Password Can\'t be empty <br/>';
                }
                else
                {
                    // if "password1" is not equal to "password2"
                    if( sha1($password) !== sha1($password2) )
                    {
                        $formErrors[] = 'Sorry Password is Not Match <br/>';
                    }
                }
            }
            // 3- Email Filteration
            if( isset($email) )
            {
                // Remove All Characters Except [ letters From A-Z : a-z ] and Special Characters [ @$#^ ....]
                $filterdEmail = filter_var($email , FILTER_SANITIZE_EMAIL);
                // Check The Format of Email
                $validEmail = filter_var($email , FILTER_VALIDATE_EMAIL );
                if( $validEmail != true )
                {
                    $formErrors[] = "This Email is Not Valid <br/>";
                }
            }
            // ++++++++++++++ if There are No errors , Execute The "User Adding in DB" ++++++++++++++
            if( empty($formErrors) )
            {
                // +++++ Before Inserting in DB : Check if "username" Exists in DB Previously Or Not +++++
                $checkRes = checkItem("Username","users",$username);
                // "Username" exists Previously in DB
                if( $checkRes > 0 ) 
                {
                    $formErrors[] = "Sorry This User Is Exists";

                }
                // Username Not exist Previously in DB
                else
                {
                    // ------------ Insert "Userinfo" [ New Member ] in Database ------------
                     $stmt = $con->prepare(" INSERT INTO 
                                                 users(Username , Password , Email ,  RegStatus , RegisterDate) 
                                             VALUES( :usernamePara , :passwordPara , :emailPara , 0 , now() ) ");
                    // Execute Query
                    $stmt->execute( array(
                                             'usernamePara' => $username , 
                                             'passwordPara' => sha1($password) , 
                                             'emailPara'    => $email , 
                                         ) 
                                   ); 
                    // User Registered Successfully
                    $successMsg = 'Congratulations Your Are Now Registered User';
                }
            }
        }

    }
?>

<div class="container login-page">
    <h1 class="text-center"> 
        <span class="selected" data-class="login">Login</span> | 
        <span data-class="signup">Signup</span>
    </h1>
    <!-- +++++++++++++++ Start : Login Form +++++++++++++++ -->
    <form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
        <div class="input-container">
            <input 
                type="text"     
                class="form-control"    
                name="username" 
                autocomplete="off" 
                placeholder="Type Your Username"
                required="required"
            />
        </div>
        <div class="input-container">
            <input 
                type="password" 
                class="form-control"    
                name="password" 
                autocomplete="new-password" 
                placeholder="Type Your Password" 
                required="required"
            />
        </div>
        <input type="submit" class="btn btn-primary btn-block" name="login" value="Login" />
    </form>
    <!-- +++++++++++++++ End : Login Form +++++++++++++++ -->
    <!-- +++++++++++++++ Start : Signup Form +++++++++++++++ -->
    <form class="signup" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
        <div class="input-container">
            <input 
                pattern=".{4,8}"
                title="Username Must Be Between 4 & 8 characters"
                type="text"     
                class="form-control"    
                name="username" 
                autocomplete="off" 
                placeholder="Type Your Username"
                required
            />
        </div>
        <div class="input-container">
            <input 
                type="password" 
                class="form-control"    
                name="password" 
                autocomplete="new-password" 
                placeholder="Type a Complex Password" 
                minlength="4" 
                required
            />  
        </div>
        <div class="input-container">
            <input 
                type="password" 
                class="form-control"    
                name="password2" 
                autocomplete="new-password" 
                placeholder="Type a Password Again"
                minlength="4" 
                required
            />  
        </div>
        <div class="input-container">
            <input 
                type="email" 
                class="form-control"    
                name="email" 
                placeholder="Type a Valid Email" 
                required="required"
            />
        </div>
        <input type="submit" class="btn btn-success btn-block" name="signup" value="Signup" />
    </form>
    <!-- +++++++++++++++ End : Signup Form +++++++++++++++ -->
    <div class="the-errors text-center">
        <?php
            if( !empty($formErrors) )
            {
                    foreach( $formErrors as $error )
                    {
                        echo "<div class='msg'>".$error."</div>"; 
                    }
            }
            // if User "Registered Successfully" Then Appear success Message
            if( isset($successMsg) )
            {
                echo "<div class='msg success'>".$successMsg."</div>";
            }
        ?>
    </div>
</div>



<?php
    // include "footer.php" File
    include $tpl."footer.php";


    // End Output Buffering
    ob_end_flush();
?>