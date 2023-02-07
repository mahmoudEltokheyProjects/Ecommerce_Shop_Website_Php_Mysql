<?php
    /*
        ==================================================
                        Template Page
        ==================================================
    */
    // Start Output Buffering
    ob_start();
    // Start Session
    session_start();
    // Page Title
    $pageTitle="";
    // Check if "Member" is "logged in" or "not"
    if( isset($_SESSION['Username']) )
    {
        // initilization Page
        include 'init.php';
        // Check The "Query String" of URL
        $do = isset( $_GET['do'] ) ? $_GET['do'] :  $do = 'Manage' ;
        // ++++++++++++++++++++++++++++++++++++++++++ Manage Page ++++++++++++++++++++++++++++++++++++++++++
        if( $do == 'Manage' ) 
        { 

        }
        // ++++++++++++++++++++++++++++++++++++++++++ Add Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Add')
        {
            
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Insert Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Insert')
        {
            
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Edit Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Edit')
        {
            
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Update Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Update')
        {
            
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Delete Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Delete')
        {
            
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Activate Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Activate')
        {
            
        }
        // Footer Page
        include $tpl.'footer.php';
    }
    else
    {
        header('location:index.php');
        exit();
    }


 // End Output Buffering
 ob_start_flush();
?>