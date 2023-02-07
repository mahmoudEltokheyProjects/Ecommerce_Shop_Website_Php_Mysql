<?php
    // start session 
    session_start();
    // $noNavbar variable
    $noNavbar = "";
    // $pageTitle variable
    $pageTitle = "Login";
    // if "user" was "login" , Go to 'dashboard.php' and don't show "login page"
    if( isset($_SESSION['Username']) )
    {
        header('location: dashboard.php');
    } 
    // include init.php file
    include "init.php";
    // 1- Check if "user" comming from "Http Post" Request
    if( $_SERVER['REQUEST_METHOD'] == 'POST' )
    {
        $usernameVar = $_POST['usernameInp'];
        $passwordVar = $_POST['passInp'];
        // hashing password and store it in DB
        $hashedPass = sha1($passwordVar);
        // Check if ["person" Exist in DB] And ["person" is "Admin" ( GroupID = 1 ) ]
        $stmt = $con->prepare(" SELECT 
                                    `UserID` , `Username` , `Password` 
                                FROM 
                                    users 
                                WHERE 
                                    `Username` = ? 
                                AND 
                                    `Password` = ? 
                                AND 
                                    `GroupID` = 1 
                                LIMIT 1  "  
                                );
        $stmt->execute(array($usernameVar,$hashedPass));
        // Fetch Row
        $row = $stmt->fetch();
        // if $count = 0 then "username and password" not exist in DB , else "username and password" exist in DB
        $count = $stmt->rowCount();
        if( $count > 0 )
        {
            // 1- store "username" in session
            $_SESSION['Username'] = $usernameVar;    // Register "session Username" with "User Name"
            $_SESSION['ID'] = $row['UserID'];        // Register "session ID" with "UserID"             
            // 2- When clicking "Login btn" , Redirect to "dashboard page"
            header('location: dashboard.php');
            // 3- exit from condition
            exit();
            print_r($row);
        }
        else 
        {

        }
    }
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="login">
    <h4 class="text-center">Admin Login</h4>
    <input type="text"        class="form-control" name="usernameInp"   placeholder="Username" autocomplete="off" >
    <input type="password"    class="form-control" name="passInp"   placeholder="Password" autocomplete="new-password" >
    <input type="submit"      class="btn btn-primary btn-lg btn-block"    value="Login" /> 
</form>


