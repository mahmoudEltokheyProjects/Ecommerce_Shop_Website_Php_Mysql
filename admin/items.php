<?php
    /*
        ==================================================
                        items Page
        ==================================================
    */
    // Start Output Buffering
    ob_start();
    // Start Session
    session_start();
    // Page Title
    $pageTitle="items";
    // Check if "Member" is "logged in" or "not"
    if( isset( $_SESSION['Username'] ) )
    {
        // initilization Page
        include 'init.php';
        // Check The "Query String" of URL          
        $do = isset( $_GET['do'] ) ? $_GET['do'] :  $do = 'Manage' ;     
        // ++++++++++++++++++++++++++++++++++++++++++ Manage Page ++++++++++++++++++++++++++++++++++++++++++
        if( $do == 'Manage' ) 
        { 
            $query = '';
            // if [query string = "?page"] , Then Appear "pending items" [ items with 'Approve = 0' ]
            // To "Approve" or "Reject" item
            if( isset($_GET['page']) && $_GET['page'] == 'Pending')
            {   
                $query = 'AND Approve = 0';
            }       
            // Select "All Items" 
            $stmt = $con->prepare(" SELECT 
                                        items.* , 
                                        users.Username ,
                                        categories.Name as category_name 
                                    FROM 
                                        items 
                                    INNER JOIN 
                                        categories 
                                    ON 
                                        items.Cat_ID = categories.ID 
                                    INNER JOIN 
                                        users 
                                    ON 
                                        items.Member_ID = users.UserID
                                    ORDER BY 
                                        Item_ID DESC
                                    $query;
                                "); 
            // Execute the Statement
            $stmt->execute(); 
            // Assign "DB values" to "variables"
            $items = $stmt->fetchAll();
            // Check if "There are any rows" in the DB
            if( !empty($items) )
            {
  ?>
                <h1 class="text-center editFormHead">Manage Items</h1>
                <div class="container">
                    <div class="table-responsive"> 
                        <table class="table table-bordered text-center mainTable">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Adding Date</th>
                                    <th>Category Name</th>
                                    <th>Username</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ( $items as $item )
                                    {
                                        echo"<tr>";
                                            echo"<td>".$item['Item_ID']."</td>";
                                            echo"<td>".$item['Name']."</td>";
                                            echo"<td>".$item['Description']."</td>";
                                            echo"<td>".$item['Price']."</td>";
                                            echo"<td>".$item['Add_Date']."</td>";
                                            echo"<td>".$item['category_name']."</td>";
                                            echo"<td>".$item['Username']."</td>";
                                            echo"<td>
                                                        <a href='items.php?do=Edit&itemid=".$item['Item_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                                        <a href='items.php?do=Delete&itemid=".$item['Item_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>
                                                ";
                                            if( $item['Approve'] == 0 )
                                            { 
                                                echo"<a 
                                                        href='items.php?do=Approve&itemid=".$item['Item_ID']."' 
                                                        class='btn btn-info'>
                                                        <i class='fa fa-check-square-o'></i> Approve
                                                    </a>";
                                            } 
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-offset-5">
                        <a href='items.php?do=Add' class='btn btn-primary'><i class="fa fa-plus"></i> New Item</a>
                    </div>
                </div>
      <?php }
            else
            {
                echo'<div class="container">';
                    echo"<div class='alert alert-info'>There's No Items To Show</div>";
                    echo"<a href='items.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> New Item</a>";
                echo'</div>';
            }
      ?>
      
  <?php }
        // ++++++++++++++++++++++++++++++++++++++++++ Add Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Add')
        { 
  ?>
            <h1 class="text-center editFormHead">Add New Item</h1>
            <div class="container">
                <form action="?do=Insert" class="form-horizontal" method="POST">
                    <!-- Start "Item Name" Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <!-- name inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input  type="text" 
                                    name="name" 
                                    class="form-control" 
                                    required="required" 
                                    placeholder="Name of The Item" 
                            />
                        </div>
                    </div>
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-label">Description</label> 
                        <div class="col-sm-10 col-md-10">
                            <!-- Description inputField -->
                            <input  type="text" 
                                    name="description" 
                                    required="required" 
                                    class="form-control" 
                                    placeholder="Describe The Item" 
                            />
                        </div>
                    </div>
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Price</label>
                        <!-- Price inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input  type="text" 
                                    name="price" 
                                    required="required" 
                                    class="form-control" 
                                    placeholder="Price Of Item" 
                            />
                        </div>
                    </div>
                    <!-- Start Country Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-label">Country</label>
                        <!-- Country inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input  type="text" 
                                    name="country" 
                                    required="required" 
                                    class="form-control" 
                                    placeholder="Country Of Made" 
                            />
                        </div>
                    </div>
                    <!-- Start Status SelectBox Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <!-- Status inputField -->
                        <div class="col-sm-10 col-md-10">
                            <select class="form-control" name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- Start Members SelectBox Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <!-- Members inputField -->
                        <div class="col-sm-10 col-md-10">
                            <select class="form-control" name="member">
                                <option value="0">...</option>
                                <?php
                                    $allMembers = getAllFrom("*" , "users" , "UserID" , "" , "");
                                    foreach ( $allMembers as $user )
                                    {
                                        echo "<option value='".$user["UserID"]."'>".$user['Username']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Start Categories SelectBox Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <!-- Categories inputField -->
                        <div class="col-sm-10 col-md-10">
                            <select class="form-control" name="category">
                                <option value="0">...</option>
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
                        <label class="col-sm-2  control-label">Tags</label>
                        <!-- Country inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input  type="text" 
                                    name="tags" 
                                    class="form-control" 
                                    placeholder="Separate Tags With Comma(,)" 
                            />
                        </div>
                    </div>
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                </form>
            </div>
  <?php }
        // ++++++++++++++++++++++++++++++++++++++++++ Insert Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Insert')
        {
            // Check if 'item' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )  
            {
                echo '<h1 class="text-center updateFormHead">Insert Item</h1>';
                echo "<div class='container'>";
                // Get the variables from the Form
                $name         = $_POST['name'];
                $desc         = $_POST['description'];
                $price        = $_POST['price'];
                $country      = $_POST['country'];
                $status       = $_POST['status'];
                $memberId     = $_POST['member'];
                $categoryId   = $_POST['category'];
                $tags         = $_POST['tags'];
                // +++++++ Validate The Form +++++++
                $formErrors = array();
                // 1- Name Validation
                if( empty($name) )
                {
                    $formErrors[]="Name Can't Be <strong>Empty</strong>";
                }
                // 2- Description Validation
                if( empty($desc) )
                {
                    $formErrors[]="Description Can't Be <strong>Empty</strong>";
                }
                // 3- price Validation
                if( empty($price) )
                {
                    $formErrors[]="Price Can't Be <strong>Empty</strong>";
                }
                // 4- country Validation   
                if( empty($country) )
                { 
                    $formErrors[]="Country Can't Be <strong>Empty</strong>";
                }
                // 5- status Validation   
                if( $status == 0 )
                {
                    $formErrors[]="You Must Choose The <strong>Status</strong>";
                }
                // 6- memberID Validation   
                if( $memberId == 0 )
                {
                    $formErrors[]="You Must Choose The <strong>Member</strong>";
                }
                // 7- categoryID Validation   
                if( $categoryId == 0 )
                {
                    $formErrors[]="You Must Choose The <strong>category</strong>";
                }
                // Printing Array of Form Errors
                foreach( $formErrors as $error )
                {
                    echo "<div class='alert alert-danger'>".$error."</div>" ;
                } 
                // ++++++++++++++ if There are No errors , Execute The "Update" ++++++++++++++
                if( empty($formErrors) )
                {
                    // ------------ Insert "Userinfo" [ New Member ] in Database ------------
                    $stmt = $con->prepare("INSERT INTO 
                             items(Name , Description , Price , Country_Made , Status , Add_Date , Cat_ID , Member_ID , tags) 
                            VALUES( :namePara , :descPara , :pricePara , :countryPara , :statusPara , now() , :categoryIdPara , :memberIdPara , :tagsPara ) ");
                    // Execute Query
                    $stmt->execute( array(
                                            'namePara'        => $name       , 
                                            'descPara'        => $desc       , 
                                            'pricePara'       => $price      , 
                                            'countryPara'     => $country    ,
                                            'statusPara'      => $status     ,
                                            'categoryIdPara'  => $categoryId ,
                                            'memberIdPara'    => $memberId   ,
                                            'tagsPara'        => $tags  
                                        ) 
                                    ); 
                    // if $count = 0 [Query Not Execute in DB] then "Show Error Message" , else "Query is executed Successfully"
                    $count = $stmt->rowCount();  
                    if( $count > 0 )
                    {
                        $theMsg = "<div class='alert alert-success'>".$count.' Record Inserted Successfully </div>';
                        // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "3 seconds"]
                        redirectHome($theMsg,'back',3);
                    }   
                    else
                    {
                        echo "<div class='container'>";
                        $theMsg = "<div class='alert alert-danger'>Record Insert Failed </div>";
                        // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "5 seconds"]
                        redirectHome($theMsg,'back',5);
                        echo "</div>";
                    }   
                }
            }
            else
            {
                echo "<div class='container'>";
                $theMsg =  "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The Home Page" After "10 seconds"]
                redirectHome($theMsg,10);
                echo "</div>";
            }
            echo "</div>";
               
        }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Edit Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Edit' ) 
        {
            // I want to make sure that the "itemid" in "URL" is "Number" 
            // Check if [ Get Request "itemid" in "URL" is "not Empty" && "itemid" value is "numeric" ] and [ Get integer value of it ]
            $itemidVar = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
            // Select All Data Depend On 
            $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
            // Execute Query
            $stmt->execute( array($itemidVar) );
            // Fetch The Data
            $row = $stmt->fetch();
            // if $count = 0 ["itemid" not exist in DB] then "Show Error Message" , else ["itemid" exist in DB] then "Show Form"
            $count = $stmt->rowCount();
            if( $count > 0 )
            { ?>           
                <h1 class="text-center editFormHead">Edit Item</h1>
                <div class="container">
                    <form action="?do=Update" class="form-horizontal" method="POST">
                        <!-- send 'item id' to 'Update Page' using 'hidden inputField' -->
                        <input type="hidden" name="itemid" value="<?php echo $itemidVar ?>">
                        <!-- Start "Item Name" Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <!-- name inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="name" 
                                        class="form-control" 
                                        required="required" 
                                        placeholder="Name of The Item" 
                                        value = "<?php echo $row['Name'] ?>"
                                />
                            </div>
                        </div>
                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2  control-label">Description</label> 
                            <div class="col-sm-10 col-md-10">
                                <!-- Description inputField -->
                                <input  type="text" 
                                        name="description" 
                                        required="required" 
                                        class="form-control" 
                                        placeholder="Describe The Item" 
                                        value = "<?php echo $row['Description'] ?>"
                                />
                            </div>
                        </div>
                        <!-- Start Price Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Price</label>
                            <!-- Price inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="price" 
                                        required="required" 
                                        class="form-control" 
                                        placeholder="Price Of Item" 
                                        value = "<?php echo $row['Price'] ?>"
                                />
                            </div>
                        </div>
                        <!-- Start Country Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2  control-label">Country</label>
                            <!-- Country inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="country" 
                                        required="required" 
                                        class="form-control" 
                                        placeholder="Country Of Made" 
                                        value = "<?php echo $row['Country_Made'] ?>"
                                />
                            </div>
                        </div>
                        <!-- Start Status SelectBox Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Status</label>
                            <!-- Status inputField -->
                            <div class="col-sm-10 col-md-10">
                                <select class="form-control" name="status">
                                    <option value="1" <?php if( $row['Status'] == 1 ) { echo 'selected'; } ?> >New</option>
                                    <option value="2" <?php if( $row['Status'] == 2 ) { echo 'selected'; } ?> >Like New</option>
                                    <option value="3" <?php if( $row['Status'] == 3 ) { echo 'selected'; } ?> >Used</option>
                                    <option value="4" <?php if( $row['Status'] == 4 ) { echo 'selected'; } ?> >Very Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- Start Members SelectBox Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Member</label>
                            <!-- Members inputField -->
                            <div class="col-sm-10 col-md-10">
                                <select class="form-control" name="member">
                                    <?php
                                    // Get All "Members"
                                        $all_members = getAllFrom("*" , "users" , "UserID" , "" , "");
                                        foreach ( $all_members as $user )
                                        {
                                            echo "<option value='".$user['UserID']."' ";
                                            if( $row['Member_ID'] == $user['UserID'] ) { echo "selected"; }
                                            echo " >".$user['Username']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Start Categories SelectBox Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category</label>
                            <!-- categories Selectbox -->
                            <div class="col-sm-10 col-md-10">
                                <select class="form-control" name="category">
                                    <?php
                                        // Get
                                        $all_cats = getAllFrom("*" , "categories" , "ID" , "where parent=0" , "","ASC");
                                        foreach ( $all_cats as $category )
                                        {
                                            echo "<option value='".$category['ID']."'";
                                            if( $row['Cat_ID'] == $category['ID'] ){ echo "selected"; }
                                            echo ">".$category['Name']."</option>";
                                            // Get "Sub categories"
                                            $child_cats = getAllFrom("*" , "categories" , "ID" , "where parent= {$category['ID']}" , "");
                                            foreach ( $child_cats as $child )
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
                            <label class="col-sm-2  control-label">Tags</label>
                            <!-- Country inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="tags" 
                                        class="form-control" 
                                        placeholder="Separate Tags With Comma(,)" 
                                        value = "<?php echo $row['tags'] ?>"
                                />
                            </div>
                        </div>
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Item" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                    <?php
                        // Select "All Members" Except "Admins"
                        $stmt = $con->prepare("SELECT 
                                                    comments.*     ,
                                                    users.Username 
                                                FROM 
                                                    comments
                                                INNER JOIN 
                                                    users 
                                                ON 
                                                    users.UserID = comments.user_id
                                                WHERE   
                                                    comments.item_id = ? 
                                            "); 
                        // Execute the Statement [ i want to show comments of only the current item Not All Comments]
                        $stmt->execute(array($itemidVar)); 
                        // Assign "DB values" to "variables"
                        $rows = $stmt->fetchAll();
                        // Check if "There are comments to show" Or "Not"
                        if(!empty($rows))
                        {
                            ?>
                            <h1 class="text-center editFormHead">Manage [ <?php echo $row['Name'] ?> ] Comments</h1>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center mainTable">
                                    <thead>
                                        <tr>
                                            <th>Comment</th>
                                            <th>User Name</th>
                                            <th>Added Date</th>
                                            <th>Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ( $rows as $row )
                                            {
                                                echo"<tr>";
                                                    echo"<td>".$row['comment']."</td>";
                                                    echo"<td>".$row['Username']."</td>";
                                                    echo"<td>".$row['comment_date']."</td>";
                                                    echo"<td>
                                                                <a href='comments.php?do=Edit&commentid=".$row['c_id']."' class='btn btn-success'>
                                                                    <i class='fa fa-edit'></i> Edit
                                                                </a>
                                                                <a href='comments.php?do=Delete&commentid=".$row['c_id']."' class='btn btn-danger confirm'>
                                                                    <i class='fa fa-close'></i> Delete
                                                                </a>
                                                        ";
                                                        if( $row['status'] == 0 )
                                                        { 
                                                        
                                                            echo"<a href='comments.php?do=Approve&commentid=".$row['c_id']."' class='btn btn-info'>
                                                                    <i class='fa fa-check-square-o'></i> Approve
                                                                </a>";
                                                        } 
                                                    echo "</td>";
                                                echo"</tr>";
                                            }
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                  <?php } ?>
                </div>
            <?php 
            }
            // Else ["item id" doesn't exist in DB] then "Show Error Message"
            else
            {
                echo "<div class='container'>";
                $theMsg = "<div class='alert alert-danger'>There is no Such id</div>";
                redirectHome($theMsg,6);
                echo "</div>";
            }
        }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Update Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Update' ) 
        {
            // Check if 'item' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                echo '<h1 class="text-center updateFormHead">Update Item</h1>';
                echo "<div class='container'>";
                    // Get the "inputFields value" from the "Edit Form"
                    // 1- get 'item id' from 'hidden inputField' in Form
                    $id         = $_POST['itemid'];
                    $name       = $_POST['name'];
                    $desc       = $_POST['description'];
                    $price      = $_POST['price'];
                    $country    = $_POST['country'];
                    $status     = $_POST['status'];
                    $member     = $_POST['member'];
                    $cat        = $_POST['category'];
                    $tags       = $_POST['tags'];
                    // +++++++ Validate The Form +++++++
                    $formErrors = array();
                    // 1- Name Validation
                    if( empty($name) )
                    {
                        $formErrors[]="Name Can't Be <strong>Empty</strong>";
                    }
                    // 2- Description Validation
                    if( empty($desc) )
                    {
                        $formErrors[]="Description Can't Be <strong>Empty</strong>";
                    }
                    // 3- price Validation
                    if( empty($price) )
                    {
                        $formErrors[]="Price Can't Be <strong>Empty</strong>";
                    }
                    // 4- country Validation   
                    if( empty($country) )
                    { 
                        $formErrors[]="Country Can't Be <strong>Empty</strong>";
                    }
                    // 5- status Validation   
                    if( $status == 0 )
                    {
                        $formErrors[]="You Must Choose The <strong>Status</strong>";
                    }
                    // 6- memberID Validation   
                    if( $member == 0 )
                    {
                        $formErrors[]="You Must Choose The <strong>Member</strong>";
                    }
                    // 7- categoryID Validation   
                    if( $cat == 0 )
                    {
                        $formErrors[]="You Must Choose The <strong>category</strong>";
                    }
                    // Printing Array of Form Errors
                    foreach( $formErrors as $error )
                    {
                        echo "<div class='alert alert-danger'>".$error."</div>" ;
                    } 
                    // ++++++++++++++ if There are No errors , Execute The "Update" ++++++++++++++
                    if( empty($formErrors) )
                    {
                        // Update The "DB" with "New Item Data"
                        $stmt = $con->prepare(" UPDATE 
                                                    items 
                                                SET 
                                                    `Name`         = ?   , 
                                                    `Description`  = ?   , 
                                                    `Price`        = ?   , 
                                                    `Country_Made` = ?   ,
                                                    `Status`       = ?   ,
                                                    `Member_ID`    = ?   ,
                                                    `Cat_ID`       = ?   ,
                                                    `tags`         = ? 

                                                WHERE 
                                                    `Item_ID`      = ? 
                                            ");
                        // Execute Query
                        $stmt->execute( array($name , $desc , $price , $country , $status , $member , $cat , $tags , $id) );
                        // if $count = 0 [ Query Not Execute in DB] then "Show Error Message" , else "Query is executed Successfully"
                        $count = $stmt->rowCount();  
                        if( $count > 0 )
                        {
                            echo "<div class='container'>";
                                $theMsg = "<div class='alert alert-success'>".$count.' Record Updated Successfully </div>';
                                // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page" After "6 seconds"]
                                redirectHome($theMsg,'back',6);
                            echo "</div>";
                        }   
                        else
                        {
                            echo "<div class='container'>";
                                $theMsg = "<div class='alert alert-danger'>Record Update Failed </div>";
                                // Appear [ErrorMessage=$theMsg] and [Redirect To "The back Page"]
                                redirectHome($theMsg,'back');
                            echo "</div>";
                        }   
                    }
                echo "</div>";
            }
            else
            {
                echo "<div class='container'>";
                $theMsg =  "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The Home Page" After "10 seconds"]
                redirectHome($theMsg,10);
                echo "</div>";
            }
            echo "</div>";
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Delete Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Delete' ) 
        {
            echo '<h1 class="text-center updateFormHead">Delete Item</h1>';
            echo "<div class='container'>";
                // Check if [Get Request "itemid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $itemidVar = isset( $_GET['itemid'] ) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
                // Select "All items data" Depend on This "ID"
                $check = checkItem("Item_ID","items",$itemidVar);
                // If There's Such ID , Show The Form
                if( $check > 0 )
                {
                    $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = ? ");
                    $stmt->execute(array($itemidVar));
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Deleted Successfully </div>';
                    // Appear [ErrorMessage=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg,'back');
                }
                else
                {
                    $theMsg = "<div class='alert alert-danger'>Sorry This Id is not Exist</div>";
                    // Appear [ErrorMessage=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg);
                }
            echo "</div>";
        }      
        // ++++++++++++++++++++++++++++++++++++++++++ Approve Page ++++++++++++++++++++++++++++++++++++++++++
        // To "Accept" or "Reject" The "Ads" on "items"
        elseif( $do == 'Approve')
        {
            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";
                // Check if [Get Request "itemid" Is "Numeric"] & [Get The "Integer Value" Of It]
                $itemidVar = isset( $_GET['itemid'] ) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;
                // Select "All items data" Depend on This "ID"
                $check = checkItem("Item_ID","items",$itemidVar);
                // If There's Such ID Show The Form
                if( $check > 0 )
                {
                    // make the "Approve" value of "item" = 1 
                    $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
                    $stmt->execute( array($itemidVar) );
                    $theMsg = "<div class='alert alert-success'>".$check.' Record Approve Successfully </div>';
                    // Appear [Message=$theMsg] and [Redirect To "back Page"]
                    redirectHome($theMsg,'back');
                }
                else
                {
                    $theMsg = "<div class='alert alert-danger'>Sorry This Id is not Exist</div>";
                    // Appear [Message=$theMsg] and [Redirect To "The Home Page"]
                    redirectHome($theMsg);
                }
            echo "</div>"; 
        }
        // Footer Page
        include $tpl.'footer.php';
    }
    else
    {
        header('location:index.php');
        exit();
    }




    ob_end_flush();
?>