<?php 
	session_start();
	include 'init.php';
?>

<div class="container">
    <h1 class='text-center'>Show Category Items</h1>
    <div class="row">
        <?php

            // Check if [ Get Request "category id" in "URL" is "not Empty" && "category id" value is "numeric" ] and [ Get integer value of it ]
            if( isset($_GET['pageid']) && is_numeric($_GET['pageid']) )
            {
                $catIdVar = intval($_GET['pageid']) ;
                // Call "getAllFrom() Function" To Get "items" of the "category link"
                $items = getAllFrom("*", "items" , "Cat_ID" , "where Cat_ID = {$catIdVar}" , "AND Approve = 1" , "ASC");
                // "items name" of the "selected category"
                foreach( $items as $item )
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
                echo "<div class='alert alert-danger'>You Must Add Page ID</div>";
            }
        ?>
    </div>
</div>


<?php   include $tpl."footer.php";  ?>






