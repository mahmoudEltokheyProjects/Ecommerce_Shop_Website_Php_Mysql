<?php
    /*
        ============================================================================================
        ** "getAllFrom" Function v2.0 ( version 2.0 ) 
        ** Function To Get All Records From Any Table in Database
        ============================================================================================
    */
    function getAllFrom($field , $table , $orderField , $where=NULL , $and=NULL , $ordering="DESC")
    {
        // make "DB Connection variable" global to access it from anywhere
        global $con;
        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");
        $getAll->execute();
        $all = $getAll->fetchAll();
        return $all;
    }
    /*
        ============================================================================================
        ** getTitle Function v1.0 ( version 1.0 ) 
        ** Title Function that Print The Page Tilte In Case Of the page has The Variable $pageTitle And
        ** Print the "Default Title" For other page 
        ============================================================================================
    */
    function getTitle()
    {
        // make the "$pageTitle" is "global variable" to be "Accessible" For "All Pages"
        global $pageTitle ;
        if ( isset($pageTitle) )
        {
            echo $pageTitle;
        }
        else
        {
            echo "Default";
        }
    }
    /*
        ============================================================================================
        ** Home Redirect Function  v2.0 ( Version 2.0 )
        ** This function Accept Parameters 
            1- $theMsg = Echo the message [ Error | Warning | Success | .... ]
            2- $seconds  = Echo the seconds Before Redirect To "Home Page" , "default value" = 3 seconds
        ============================================================================================
    */
    function redirectHome( $theMsg , $url = null , $seconds=3 )
    {
        $link = '';
        // +++++++++++++++ Parameter 2 : $url +++++++++++++++
        // if user doesn't send value for the $url , the default value of $url = "page.php"
        if( $url === null )
        {
            $url = "index.php";
            $link = "HomePage";
        }
        else
        {
            // if $_SERVER['HTTP_REFERER'] "has value" and "is not empty"
            if( isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "" )
            {
                $url = $_SERVER['HTTP_REFERER'];
                $link = "Previous Page";
            }
            else
            {
                $url = "index.php";
                $link = "HomePage"; 
            }
        }
        // +++++++++++++++ Parameter 1 : $theMsg +++++++++++++++
        echo $theMsg;
        // +++++++++++++++ Parameter 3 : $seconds +++++++++++++++
        echo "<div class='alert alert-info'>You Will be Redirected to $link After $seconds Seconds</div>";
        // Redirect to the home page after $seconds Seconds
        header("refresh:$seconds;url=$url");
        // Exit From Function
        exit();
    }
    /*
        ============================================================================================
        ** Check Items Function v1.0 ( Version 1 )
        ** Function To Check Item Exists In Database or Not [ Function Accept Parameters ]
            1- $select  = The Item To Select ( columnName ) [ Example : id , username , email , * , .... ]
            2- $from    = The Table To Select From ( tableName ) [ Example : users , items , categories , .... ]
            3- $value   = The Value Of Select From ( tableName ) [ Example : osama , osama@gmail.com , .... ]
        ============================================================================================
    */
    function checkItem($select , $from , $value)
    {
        // make "DB connection variable" global to access it from anywhere
        global $con ;
        // Query statement
        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statement->execute(array($value));
        $count = $statement->rowCount();
        return $count;  
    }
    /*
        ============================================================================================
        ** "countItems" Function v1.0 ( version 1.0 ) 
        ** Function To Count Number Of Items Rows in Database 
        ** Function Parameters:
            1- $item  = The item to count
            2- $table = The table to choose from
        ============================================================================================
    */
    function countItems($item,$table)
    {
        // make "DB connection variable" global to access it from anywhere
        global $con ;
        // Get "Members Number" in Database Through "Number of rows of $from column"
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table"); 
        // Execute the query
        $stmt2->execute();
        // Fetch the "Result Column" Data
        $itemsNum = $stmt2->fetchColumn();

        return $itemsNum;
    }
    /*
        ============================================================================================
        ** "getLatest" Function v1.0 ( version 1.0 ) 
        ** Function To Get Latest Items From Database [ Users , Items , Comments , ...]
        ** Function Parameters:
            1- $select  = The column To Select
            2- $table = The table to choose from
            3- $order = The Descending Order
            4- $limit = Number of Records(Rows) to Get from Database
        ============================================================================================
    */
    function getLatest( $select , $table , $order , $limit=5 )
    {
        // make "DB Connection variable" global to access it from anywhere
        global $con;
        $getStmt = $con->prepare("SELECT $select FROM $table  ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();
        $rows = $getStmt->fetchAll();
        return $rows;
    }

?>
