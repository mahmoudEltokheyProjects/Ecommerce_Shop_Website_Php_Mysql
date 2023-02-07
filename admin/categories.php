<?php
    /*
        ==================================================
                        Category Page
        ==================================================
    */
    // Start Output Buffering
    ob_start();
    // Start Session
    session_start();
    // Page Title
    $pageTitle="Categories";
    // 1- if "user" is "Login" 
    if( isset($_SESSION['Username']) )
    {
        // initilization Page
        include 'init.php';
        // Check The "Query String" of URL
        $do = isset( $_GET['do'] ) ? $_GET['do'] :  $do = 'Manage' ;
        // ++++++++++++++++++++++++++++++++++++++++++ Manage Page ++++++++++++++++++++++++++++++++++++++++++
        if( $do == 'Manage' ) 
        { 
            // Sorting variable
            $sortVar = "ASC";
            // if "Url" contains '?sort=ASC' or "?sort=DESC" And $_GET['sort'] in $sortArr then $sortVar = $_GET["sort"]
            $sortArr = array("ASC", "DESC");
            if( isset( $_GET['sort'] ) && in_array( $_GET['sort'] , $sortArr ) )
            {
                $sortVar = $_GET['sort'];
            }
            // prepare statement : Appear "Main Categories" Only
            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sortVar");
            // execute query    
            $stmt2->execute();
            // Get All Categories
            $cats = $stmt2->fetchAll();
?>
            <h1 class="text-center">Manage Categories</h1>  
            <div class="container categories">
                <div class="panel panel-default">
                    <!-- Panel Heading -->
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories 
                        <!-- Ordering Categories -->
                        <div class="option pull-right"> 
                            <!-- Categories Ordering -->
                            <i class="fa fa-sort"></i> Ordering: 
                            [ 
                                <a href="categories.php?sort=ASC"class="<?php if( $sortVar == 'ASC' )  { echo "active";} ?>">Asc</a> | 
                                <a href="categories.php?sort=DESC"class="<?php if( $sortVar == 'DESC' ) { echo "active";} ?>">Desc</a>
                            ]
                            <!-- Categories View -->
                            <i class="fa fa-eye"></i> View:
                            [
                                <span class="active" data-view="full">Full</span>   | 
                                <span data-view="classic">Classic</span> 
                            ]
                        </div>
                    </div>
                    <!-- Panel Body -->
                    <div class="panel-body">
                        <?php
                            foreach ( $cats as $cat )
                            {
                                echo"<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                            echo"<a href='categories.php?do=Edit&catid=".$cat['ID']."' class='btn btn-primary btn-xs'> <i class='fa fa-edit'></i> Edit</a>";
                                            echo"<a href='categories.php?do=Delete&catid=".$cat['ID']."' class='confirm btn btn-danger btn-xs'><i class='fa fa-close'></i> Delete</a>";
                                    echo "</div>";
                                    echo "<h3>".$cat['Name']."</h3>";
                                    // ++++++++++++++ Category Description +++++++++++
                                    echo "<div class='full-view'>";
                                        echo "<p>"; 
                                                if( $cat['Description'] == "" ) 
                                                {
                                                    echo "The Category Has No Description";
                                                }
                                                else
                                                {
                                                    echo $cat['Description'];
                                                }
                                        echo "</p>";
                                        if( $cat['Visibility'] == 1 ) 
                                        { 
                                            echo "<span class='visibility globalSpan'><i class='fa fa-eye'></i> Hidden</span>"; 
                                        }
                                        if( $cat['Allow_Comment'] == 1 ) 
                                        { 
                                            echo "<span class='commenting globalSpan'><i class='fa fa-close'></i> Comment Disabled</span>"; 
                                        }     
                                        if( $cat['Allow_Ads'] == 1 ) 
                                        { 
                                            echo "<span class='advertises globalSpan'><i class='fa fa-close'></i> Ads Disabled</span>"; 
                                        }  
                                    echo "</div>";                                  
                                    // +++++++++++++++ Get the "Child-categories" +++++++++++++++
                                    // The "sub-category" has column "parent" = the "ID of Parnt Category" , 
                                    // Appear only "main categories" [ parent = 0 ]
                                    $childCats = getAllFrom("*","categories","ID","where parent = {$cat['ID']}" ,"", "ASC");
                                    // If There are "child categories"
                                    if( !empty($childCats) )
                                    {
                                        echo "<h4 class='child-head'>Child Categories</h4>";
                                        echo "<ul class='list-unstyled child-cats'>";
                                            foreach( $childCats as $c )
                                            {
                                                    echo "<li class='child-link'>";
                                                        echo"<a href='categories.php?do=Edit&catid=".$c['ID']."'>".$c['Name']."</a>";
                                                        echo"<a href='categories.php?do=Delete&catid=".$c['ID']."' class='show-delete confirm'> Delete</a>";
                                                    echo "</li>"; 
                                            }
                                        echo "</ul>";
                                    }
                                echo "</div>";
                                echo "<hr>";
                            }
                        ?>
                    </div>
                </div>
                <!-- Add New Category -->
                <a href="categories.php?do=Add" class="btn btn-primary addCategory"><i class="fa fa-plus"></i> Add New Category</a>
            </div> 
<?php   }
        // ++++++++++++++++++++++++++++++++++++++++++ Add Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Add')
        {
?>
            <h1 class="text-center editFormHead">Add New Category</h1>
            <div class="container">
                <form action="?do=Insert" class="form-horizontal" method="POST">
                    <!-- Start "Category Name" Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Name</label>
                        <!-- name inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Category" />
                        </div>
                    </div>
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Description</label> 
                        <div class="col-sm-10 col-md-10">
                            <!-- Description inputField -->
                            <input type="text" name="description" class="form-control" placeholder="Describe The Category" />
                        </div>
                    </div>
                    <!-- Start Ordering Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Ordering</label>
                        <!-- Ordering inputField -->
                        <div class="col-sm-10 col-md-10">
                            <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange Category" />
                        </div>
                    </div>
                    <!-- Start Category Type [ category is parent or child ] -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-label">Parent ?</label>
                        <!-- "Category Type" inputField -->
                        <div class="col-sm-10 col-md-10">
                            <select name="parent"  class="form-control">
                                <option value="0">None</option>
                                <?php 
                                    $allCats = getAllFrom('*','categories','ID','where parent = 0','','ASC');
                                    foreach( $allCats as $cat )
                                    {
                                        echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Visible</label>
                        <div class="col-sm-10 col-md-10">
                            <!-- "Yes" RadioButton -->
                            <div>
                                <input type="radio" name="visibility" value="0" id="visible-yes" checked>
                                <label for="visible-yes">Yes</label>
                            </div>
                            <!-- "No" RadioButton -->
                            <div>
                                <input type="radio" name="visibility" value="1" id="visible-no">
                                <label for="visible-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- Start Commenting Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-10">
                            <!-- "Yes" RadioButton -->
                            <div>
                                <input type="radio" name="commenting" value="0" id="comment-yes" checked>
                                <label for="comment-yes">Yes</label>
                            </div>
                            <!-- "No" RadioButton -->
                            <div>
                                <input type="radio" name="commenting" value="1" id="comment-no">
                                <label for="comment-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- Start Ads Field -->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2  control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-10">
                            <!-- "Yes" RadioButton -->
                            <div>
                                <input type="radio" name="ads" value="0" id="ads-yes" checked>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <!-- "No" RadioButton -->
                            <div>
                                <input type="radio" name="ads" value="1" id="ads-no">
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                </form>
            </div>
<?php    
        }
        // ++++++++++++++++++++++++++++++++++++++++++ Insert Page ++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Insert' ) 
        {            
            // Check if 'user' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )  
            {
                echo '<h1 class="text-center updateFormHead">Insert Category</h1>';
                echo "<div class='container'>";
                // Get the variables from the Form
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $parent   = $_POST['parent'];
                $order    = $_POST['ordering'];
                $visible  = $_POST['visibility'];
                $comment  = $_POST['commenting'];
                $ads      = $_POST['ads'];                
                // +++++ Before Inserting in DB : Check if "Category" Exists in DB Previously Or Not +++++
                $checkRes = checkItem("Name","categories",$name);
                // Category "Exists" Previously in DB
                if( $checkRes > 0 ) 
                {
                    $theMsg = "<div class='alert alert-danger'>Sorry ): This Category is Existing</div>";
                    // Appear [ Message = $theMsg ] and [ Redirect To "The back Page" After "3 seconds" ]
                    redirectHome($theMsg,'back',3);
                }
                // Category "Not Exist" Previously in DB
                else
                {
                    // ------------ Insert "Category info" [ New Category ] in Database ------------
                    $stmt = $con->prepare(" INSERT INTO 
                                                categories(Name , Description , parent , Ordering , Visibility , Allow_Comment , Allow_Ads) 
                                            VALUES( :namePara , :descPara , :parentPara , :orderPara , :visiblePara , :commentPara , :adsPara ) ");
                    // Execute Query
                    $stmt->execute( array(
                                            'namePara'     => $name    , 
                                            'descPara'     => $desc    , 
                                            'parentPara'   => $parent  , 
                                            'orderPara'    => $order   , 
                                            'visiblePara'  => $visible ,
                                            'commentPara'  => $comment ,
                                            'adsPara'      => $ads 
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
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The back Page" After "10 seconds"]
                redirectHome($theMsg,'back',10);
                echo "</div>";
            }
            echo "</div>";
        }
        // ++++++++++++++++++++++++++++++++++++++++++++++++ Edit Page ++++++++++++++++++++++++++++++++++++++++++++++++
        elseif( $do == 'Edit' ) 
        {
            // I want to make sure that the "catid" in "URL" is "Number" 
            // Check if [ Get Request "catid" in "URL" is "not Empty" && "userid" value is "numeric" ] and [ Get integer value of it ]
            $catidVar = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
            // Select All Data Depend On 
            $stmt = $con->prepare("SELECT * FROM categories WHERE `ID` = ? ");
            // Execute Query
            $stmt->execute( array($catidVar) );
            // Fetch The Data
            $cat = $stmt->fetch();
            // if $count = 0 ["userid" not exist in DB] then "Show Error Message" , else ["userid" exist in DB] then "Show Form"
            $count = $stmt->rowCount();
            if( $count > 0 )
            { ?>           
                <h1 class="text-center editFormHead">Edit Category</h1>
                <div class="container">
                    <form action="categories.php?do=Update" class="form-horizontal" method="POST">
                        <!-- send 'user id' to 'Update Page' using 'hidden inputField' -->
                        <input type="hidden" name="catid" value="<?php echo $catidVar ?>">
                        <!-- Start "Category Name" Field -->
                        <div class="form-group form-group-lg">
                            <label  class="col-sm-2  control-label">Name</label>
                            <!-- name inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="name" 
                                        class="form-control" 
                                        required='required' 
                                        value="<?php echo $cat['Name'] ?>" 
                                />
                            </div>
                        </div>
                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Description</label> 
                            <div class="col-sm-10 col-md-10">
                                <!-- Description inputField -->
                                <input  type="text" 
                                        name="description" 
                                        class="form-control" 
                                        value="<?php echo $cat['Description'] ?>" 
                                />
                            </div>
                        </div>
                        <!-- Start Ordering Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Ordering</label>
                            <!-- Ordering inputField -->
                            <div class="col-sm-10 col-md-10">
                                <input  type="text" 
                                        name="ordering" 
                                        class="form-control" 
                                        value="<?php echo $cat['Ordering'] ?>" 
                                />
                            </div>
                        </div>
                        <!-- Start Category Type [ category is parent or child ] -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2  control-label">Parent ? </label>
                            <!-- "Category Type" inputField -->
                            <div class="col-sm-10 col-md-10">
                                <select name="parent"  class="form-control">
                                    <option value="0">None</option>
                                    <?php 
                                        $allCats = getAllFrom('*','categories','ID','where parent = 0','','ASC');
                                        foreach( $allCats as $c )
                                        {
                                            echo "<option value='".$c['ID']."'";
                                                if( $cat['parent'] == $c['ID'] )
                                                {
                                                    echo 'selected';
                                                }
                                            echo ">".$c['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Start Visibility Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Visible</label>
                            <div class="col-sm-10 col-md-10">
                                <!-- "Yes" RadioButton -->
                                <div>
                                    <input  type="radio"
                                            name="visibility" 
                                            value="0" 
                                            id="visible-yes" 
                                            <?php if( $cat['Visibility'] == 0 ) { echo "checked"; } ?>   
                                    />
                                    <label for="visible-yes">Yes</label>
                                </div>
                                <!-- "No" RadioButton -->
                                <div>
                                    <input  type="radio" 
                                            name="visibility" 
                                            value="1" 
                                            id="visible-no"  
                                            <?php if( $cat['Visibility'] == 1 ) { echo "checked"; } ?>  
                                    />
                                    <label for="visible-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Start Commenting Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-10">
                                <!-- "Yes" RadioButton -->
                                <div>
                                    <input  type="radio" 
                                            name="commenting" 
                                            value="0" id="comment-yes" 
                                            <?php if( $cat['Allow_Comment'] == 0 ) { echo "checked"; } ?> 
                                    />
                                    <label for="comment-yes">Yes</label>
                                </div>
                                <!-- "No" RadioButton -->
                                <div>
                                    <input  type="radio" 
                                            name="commenting" 
                                            value="1" id="comment-no" 
                                            <?php if( $cat['Allow_Comment'] == 1 ) { echo "checked"; } ?>  
                                    />
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Start Ads Field -->
                        <div class="form-group form-group-lg">
                            <label for="" class="col-sm-2  control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-10">
                                <!-- "Yes" RadioButton -->
                                <div>
                                    <input  type="radio" 
                                            name="ads" 
                                            value="0"
                                            id="ads-yes" 
                                            <?php if( $cat['Allow_Ads'] == 0 ) { echo "checked"; } ?> 
                                    />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <!-- "No" RadioButton -->
                                <div>
                                    <input  type="radio" 
                                            name="ads" 
                                            value="1" 
                                            id="ads-no" 
                                            <?php if( $cat['Allow_Ads'] == 1 ) { echo "checked"; } ?> 
                                    />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Edit Category" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>
            <?php 
            }
            // Else ["userid" doesn't exist in DB] then "Show Error Message"
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
            // Check if 'user' come from 'Post method'
            if( $_SERVER['REQUEST_METHOD'] == 'POST' )
            {
                echo '<h1 class="text-center updateFormHead">Update Category</h1>';
                echo "<div class='container'>";
                // Get the "inputFields value" from the "Edit Form"
                // 1- get 'category id' from 'hidden inputField' in Form
                $id      = $_POST['catid'];
                $name    = $_POST['name'];
                $desc    = $_POST['description'];
                $order   = $_POST['ordering'];
                $parent  = $_POST['parent'];
                $visible = $_POST['visibility'];
                $comment = $_POST['commenting'];
                $ads     = $_POST['ads'];
                // Update The "DB" with "New Category Data"
                $stmt = $con->prepare("UPDATE 
                                            categories 
                                       SET 
                                            `Name` = ?          , 
                                            `Description` = ?   , 
                                            `Ordering` = ?      , 
                                            `parent` = ?      , 
                                            `Visibility` = ?    ,  
                                            `Allow_Comment` = ? , 
                                            `Allow_Ads` = ? 
                                       WHERE 
                                            ID = ? "
                                     );
                // Execute Query
                $stmt->execute( array($name , $desc , $order , $parent , $visible , $comment , $ads , $id) );
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
            else
            {
                $theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                // Appear [ErrorMessage=$errorMsg] and [Redirect To "The Home Page" After "2 seconds"]
                redirectHome($theMsg,2);
            }
            echo "</div>";
        }
        elseif( $do == 'Delete')
        {
            
            echo '<h1 class="text-center updateFormHead">Delete Member</h1>';
            echo "<div class='container'>";
            // Check if [Get Request "catid" Is "Numeric"] & [Get The "Integer Value" Of It]
            $catidVar = isset( $_GET['catid'] ) && is_numeric($_GET['catid'])  ? intval($_GET['catid']) : 0 ;
            // Select "All users data" Depend on This "ID"
            $check = checkItem("ID","categories",$catidVar);
            // If There's Such ID , Show The Form
            if( $check > 0 )
            {
                $stmt = $con->prepare("DELETE FROM categories WHERE ID = ? ");
                $stmt->execute(array($catidVar));
                $theMsg = "<div class='alert alert-success'>".$check.' Record Deleted Successfully </div>';
                // Appear [Message=$theMsg] and [Redirect To "The back   Page"]
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
        // Footer Page
        include $tpl.'footer.php';
    }
    else
    {
        header('location:index.php');
        exit();
    }


 // End Output Buffering
 ob_end_flush();
?>