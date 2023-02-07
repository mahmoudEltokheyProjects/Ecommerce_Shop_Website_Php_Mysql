<?php
    // start the session
    session_start();
    // unset the session data( variables) [ make the session is empty ]
    session_unset();
    // destroy the session
    session_destroy();
    // Redirect to the "login page"
    header('Location:index.php');
    // Prevent showing any errors
    exit();

?>