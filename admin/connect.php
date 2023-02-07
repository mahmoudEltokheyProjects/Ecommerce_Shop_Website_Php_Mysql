<?php
    $host = "localhost";
    $dbName = "shop";
    $dbUsername = "root";
    $dbPassword = "";
    // Support "Arabic Language" in "Database insert"
    $option = array( PDO:: MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    // data source name
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    // 1- connect with database
    try
    {
        $con = new PDO($dsn,$dbUsername,$dbPassword,$option);    
        // make "error mode" is "exception mode"
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Database connection Successfully <br/>";
    }
    catch (PDOException $errorVar)
    {
        // echo "Database connection Failed ".$errorVar->getMessage();
    }
    
    // // 3- insert data using "prepare method"
    // $q = $con->prepare("INSERT INTO `users`(`Username`,`Password`,`Email`,`FullName`,`GroupID`,`TrustStatus`,`RegStatus`)
    //                     VALUES (:usernameVar , :passwordVar , :emailVar , :fullNameVar , :groupIDVar , :trustStatus ,:regStatus)");
    // // 4- execute insertion using "execute method"
    // $q->execute(["usernameVar"=>"ahmed","passwordVar"=>"123","emailVar"=>"ahmed@yahoo.com","fullNameVar"=>"ahmed hesham","groupIDVar"=>0,"trustStatus"=>0,"regStatus"=>0]);
    // // 5- check if insert data is successful or failed
    // if( $q->rowCount() > 0 )
    // {
    //     echo "Insert Successfully <br/>";
    // }
    // else
    // {
    //     echo "Insert Failed <br/>";
    // }

?>