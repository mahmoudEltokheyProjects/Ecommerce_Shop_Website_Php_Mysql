<?php 
	session_start();
	include 'init.php';
?>

<div class="container">
    <div class="row">
        <?php
            // Check if [ Get Request "Tag Name" in "URL" is "not Empty"
            if( isset($_GET['name']) )
            {
                // Get "Tag Name" from "URL"
                $tagNameVar = $_GET['name'] ;
                echo "<h1 class='text-center'>".$tagNameVar."</h1>";
                // Call "getAllFrom() Function" To Get "items" with the "same tagName"
                $tagItems = getAllFrom("*", "items" , "Cat_ID" , "where tags LIKE '%$tagNameVar%' " , "AND Approve = 1" , "ASC");
                // "tags name" of the "selected category"
                foreach( $tagItems as $item )
                {
                    echo"<div class='col-sm-6 col-md-4 col-lg-3'>";
                        echo"<div class='thumbnail item-box'>";
                            echo"<span class='price-tag'>$".$item['Price']."</span>";
                            echo"<img src='img.png' alt='image' class='img-responsive' />";
                            echo"<div class='caption'>";
                                echo"<h3><a href='items.php?itemid=".$item['Item_ID']."'>".$item['Name']."</a></h3>";
                                echo"<p>".$item['Description']."</p>";
                                echo"<div class='date'>".$item['Add_Date']."</div>";
                            echo"</div>";
                        echo"</div>";
                    echo'</div>';
                }
            }
            else
            {
                echo "<div class='alert alert-danger'>You Must Enter Tag Name</div>";
            }
        ?>
    </div>
</div>


<?php   include $tpl."footer.php";  ?>






