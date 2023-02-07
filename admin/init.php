<?php
    // include database connection file
    include 'connect.php';
    // ++++++++++++++++++++ Project Routes ++++++++++++++++++++
    // 1- Template Directory route
    $tpl = "includes/templates/";
    // 2- languages Directory route
    $lang = "includes/languages/";
    // 3- Functions Directory route
    $func = "includes/functions/";
    // 4- Layout/css Directory route
    $cssUrl = "layout/css/";
    // 5- Layout/js Directory route
    $jsUrl = "layout/js/";
   
    // ++++++++++++++++++++ include $func."functions.php" ++++++++++++++++++++
    include $func."functions.php";
    // ++++++++++++++++++++ include $lang."english.php" ++++++++++++++++++++
    include $lang."english.php";
    // ++++++++++++++++++++ include "header.php" ++++++++++++++++++++
    include $tpl."header.php";
    //  include "navbar.php" on All pages "Except" The One With "$noNavbar" Variable
    if( !isset( $noNavbar) )
    {
        include $tpl . "navbar.php";
    }

    // ++++++++++++++++++++ include "header.php" ++++++++++++++++++++
    // include $tpl."footer.php";
    
?>
