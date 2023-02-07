<?php
    // Start Output Buffering
    ob_start();
    // start session
    session_start();
    $pageTitle = "Homepage";
    // include "init.php" file
    include "init.php";
?>
    <div class="container">
    <div class="row">
        <?php
            // Call "getAllFrom() Function" To Get "All items" with Approve = 1 
            // columns : [ $field = * , $table = items , $orderField = Item_ID , $ordering = DESC ]
            $allItems = getAllFrom('*', 'items' , 'Item_ID' , 'where Approve = 1' , '' , 'DESC');
            // "items name" of the "selected category"
            foreach( $allItems as $item )
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
        ?>
    </div>
</div>
    
<?php
    // include "footer.php" file
    include $tpl."footer.php";   

    // End Output Buffering
    ob_end_flush();
?>



