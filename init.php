<?php
    // Error Reporting
    ini_set('display_errors','On');
    // Display All Error Types
    error_reporting(E_ALL);
    // include database connection file
    include 'admin/connect.php';
    // Storing "user session" 
    $sessionUser = '' ;
    if( isset($_SESSION['user']) )
    {
        $sessionUser = $_SESSION['user'];
    }
    // ++++++++++++++++++++ Project Routes ++++++++++++++++++++
    // 1- Template Directory route
    $tpl = "includes/templates/";
    // 2- languages Directory route
    $lang = "includes/languages/";
    // 3- Functions Directory route
    $func = "includes/functions/";
    $cssUrl = "layout/css/";
    // 4- Layout/css Directory route
    // 5- Layout/js Directory route
    $jsUrl = "layout/js/";
   
    // ++++++++++++++++++++ include $func."functions.php" ++++++++++++++++++++
    include $func."functions.php";
    // ++++++++++++++++++++ include $lang."english.php" ++++++++++++++++++++
    include $lang."english.php";
    // ++++++++++++++++++++ include "header.php" ++++++++++++++++++++
    include $tpl."header.php";

    
?>
