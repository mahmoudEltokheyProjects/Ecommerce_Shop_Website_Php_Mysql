<?php
    // start session
    session_start();
    // page name
    $pageTitle = "Create New Item";
    // include "init.php" file
    include "init.php";
    // if "user" is "logged in" , "Profile Page" will "Appear" To "User"
    if( isset($_SESSION['user']) )
    {
        
        // If "User" Comes From "newad.php Page"
        if( $_SERVER['REQUEST_METHOD'] == 'POST' )
        {
            // Errors Array
            $formErrors    = array();
            // 1- item name
            $itemName     = strip_tags( $_POST['name'] );
            // 2- item description
            $itemDesc      = strip_tags( $_POST['description'] );
            // 3- item price
            $itemPrice     = filter_var( $_POST['price'] , FILTER_SANITIZE_NUMBER_INT );
            // 4- item country
            $itemCountry   = strip_tags( $_POST['country'] );
            // 5- item status
            $itemStatus    = filter_var( $_POST['status'] , FILTER_SANITIZE_NUMBER_INT );
            // 6- item category
            $itemCategory  = filter_var( $_POST['category'], FILTER_SANITIZE_NUMBER_INT );
            // 7- item tags
            $tags          = strip_tags( $_POST['tags'] );
            // +++++++++++++++ Validation ++++++++++++++++
            // 1- item name
            if( strlen($itemName) < 4 )
            {
                $formErrors[]='Item Title must be At least 4 characters';
            }
            // 2- item description
            if( strlen($itemDesc) < 10 )
            {
                $formErrors[]='Item Description must be At least 10 characters';
            }
            // 3- item country
            if( strlen($itemCountry) < 3 )
            {
                $formErrors[]='Item Country must be At least 3 characters';
            }
            // 4- item price
            if( empty($itemPrice) )
            {
                $formErrors[]='Item Price must be Not Empty';
            }
            // 5- item status
            if( empty($itemStatus) )
            {
                $formErrors[]='Item Status must be Not Empty';
            }
            // 6- item category
            if( empty($itemCategory) )
            {
                $formErrors[]='Item Category must be Not Empty';
            }
            if( empty($formErrors) )
            {
                // ------------ Insert "Iteminfo" [ New Item ] in Database ------------
                $stmt = $con->prepare("INSERT INTO 
                                            items( 
                                                    Name , Description , Price , Country_Made , Status , 
                                                    Add_Date , Cat_ID , Member_ID , tags
                                                ) 
                                            VALUES( :namePara   , :descPara , :pricePara , :countryPara , 
                                                    :statusPara , now() , :categoryIdPara , :memberIdPara , :tagsPara 
                                                  ) 
                                    ");
                // Execute Query
                $stmt->execute( array(
                                        'namePara'        => $itemName        , 
                                        'descPara'        => $itemDesc        , 
                                        'pricePara'       => $itemPrice       , 
                                        'countryPara'     => $itemCountry     ,
                                        'statusPara'      => $itemStatus      ,
                                        'categoryIdPara'  => $itemCategory    ,
                                        // Get "user id" from "session"
                                        'memberIdPara'    => $_SESSION['uid'] ,
                                        'tagsPara'        => $tags              
                                    ) 
                                ); 
                // if $count = 0 [Query Not Execute in DB] then "Show Error Message" , else "Query is executed Successfully"
                $count = $stmt->rowCount();  
                if( $count > 0 )
                {
                    // Appear Successfully Message
                    $successMsg = $count." Item Inserted Successfully</div>";
                }   
            }
        }
?>
        <!-- "Create New Ad Page" Heading -->
        <h1 class="text-center"><?php echo $pageTitle; ?></h1>
        <!-- +++++++++ Block 1 : Create New Ad +++++++++ -->
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <!-- panel heading -->
                    <div class="panel-heading"><?php echo $pageTitle; ?></div>
                    <!-- panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form-horizontal main-form" method="POST">
                                    <!-- Start "Item Name" Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <!-- name inputField -->
                                        <div class="col-sm-10 col-md-9">
                                            <input  type="text"
                                                    pattern=".{4,}"
                                                    title="This Field Required At Least 4 Characters" 
                                                    name="name" 
                                                    class="form-control live" 
                                                    placeholder="Name of The Item" 
                                                    data-class=".live-name"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <!-- Start Description Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3  control-label">Description</label> 
                                        <div class="col-sm-10 col-md-9">
                                            <!-- Description inputField -->
                                            <input  type="text" 
                                                    pattern=".{10,}"
                                                    title="This Field Required At Least 10 Characters" 
                                                    name="description" 
                                                    class="form-control live" 
                                                    placeholder="Describe The Item" 
                                                    data-class=".live-desc"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <!-- Start Price Field -->
                                    <div class="form-group form-group-lg">
                                        <label  class="col-sm-3  control-label">Price</label>
                                        <!-- Price inputField -->
                                        <div class="col-sm-10 col-md-9">
                                            <input  type="text" 
                                                    name="price" 
                                                    class="form-control live" 
                                                    placeholder="Price Of Item" 
                                                    data-class=".live-price"
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <!-- Start Country Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3  control-label">Country</label>
                                        <!-- Country inputField -->
                                        <div class="col-sm-10 col-md-9">
                                            <input  type="text" 
                                                    pattern=".{3,}"
                                                    title="This Field Required At Least 3 Characters" 
                                                    name="country" 
                                                    class="form-control" 
                                                    placeholder="Country Of Made" 
                                                    required
                                            />
                                        </div>
                                    </div>
                                    <!-- Start Status SelectBox Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Status</label>
                                        <!-- Status inputField -->
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" name="status" required>
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option> 
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Start Categories SelectBox Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Category</label>
                                        <!-- Members inputField -->
                                        <div class="col-sm-10 col-md-9">
                                            <select class="form-control" name="category"  required>
                                                <option value="">...</option>
                                                <?php
                                                    // Get Only "main categories"
                                                    $allCats = getAllFrom("*" , "categories" , "ID" , "where parent = 0" , "");
                                                    foreach ( $allCats as $cat )
                                                    {
                                                        echo "<option value='".$cat["ID"]."'>".$cat['Name']."</option>";
                                                        // Get "Sub categories"
                                                        $childCats = getAllFrom("*" , "categories" , "ID" , "where parent= {$cat['ID']}" , "");
                                                        foreach ( $childCats as $child )
                                                        {
                                                            echo "<option value='".$child["ID"]."'>--- ".$child['Name']."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Start Tags Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3  control-label">Tags</label>
                                        <!-- Country inputField -->
                                        <div class="col-sm-9 col-md-9">
                                            <input  type="text" 
                                                    name="tags" 
                                                    class="form-control" 
                                                    placeholder="Separate Tags With Comma(,)" 
                                            />
                                        </div>
                                    </div>
                                    <!-- Start Submit Button -->
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <div class='thumbnail item-box live-preview'>
                                    <span class='price-tag'>
                                        $<span class='live-price'>0</span>
                                    </span>
                                    <img src='img.png' alt='image' class='img-responsive' />
                                    <div class='caption'>
                                        <h3 class="live-name">Title</h3>
                                        <p class="live-desc">Description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ++++++++++++++++ Start Looping Through Messages ++++++++++++++++ -->
                        <?php
                            // If "Item" Is Added Failed
                            if( !empty($formErrors) )
                            {
                                foreach( $formErrors as $error )
                                {
                                    echo "<div class='alert alert-danger'>".$error."</div>";
                                }
                            }
                            // If "Item" Is Added Successfully
                            if( isset($successMsg) )
                            {
                                echo "<div class='alert alert-success'>".$successMsg."</div>";
                            }
                        ?>
                        <!-- ++++++++++++++++ End Looping Through Messages ++++++++++++++++ -->
                    </div>
                </div>
            </div>
        </div>
<?php
    }
    else
    {
        // Redirect to "login.php" page
        header('location:login.php');    
        exit();
    }
    // include "footer.php" file
    include $tpl."footer.php";   
?>



