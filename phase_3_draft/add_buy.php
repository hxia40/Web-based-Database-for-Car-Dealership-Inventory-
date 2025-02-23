<?php

include('lib/common.php');

if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
    header('Location: index.php');
    exit();
}
 
    $query = "SELECT login_first_name, login_last_name " .
		 " FROM Users " .
		 " WHERE Users.username = '{$_SESSION['username']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User Profile... <br>".  __FILE__ ." line:". __LINE__ );
    }
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
        $enteredCustomer_id = mysqli_real_escape_string($db, $_POST['customer_id']);
        $enteredInventory_clerk_permission = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);
        $enteredPurchase_date = mysqli_real_escape_string($db, $_POST['purchase_date']);
        $enteredPurchase_price = $_POST['purchase_price'];
        $enteredPurchase_condition = mysqli_real_escape_string($db, $_POST['purchase_condition']);
        $enteredKBB_value = $_POST['KBB_value'];

        if (empty($enteredVin)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate VIN number... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredCustomer_id)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Customer ID... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredInventory_clerk_permission)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Inventory Clerk Permission... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredPurchase_date)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Purchase Date... <br>" . __FILE__ . " line: " . __LINE__);
        }else if($enteredPurchase_price < 0){
            array_push($error_msg, "ADD ERROR: Please enter a validate Purchase Price... <br>" . __FILE__ . " line: " . __LINE__);
        }else if(empty($enteredPurchase_condition)){
            array_push($error_msg, "ADD ERROR: Please enter a validate Purchase Condition... <br>" . __FILE__ . " line: " . __LINE__);
        }else if(empty($enteredKBB_value)){
            array_push($error_msg, "ADD ERROR: Please enter a validate KBB Value... <br>" . __FILE__ . " line: " . __LINE__);

        }else{
            $query = "INSERT INTO Buy (vin, customer_id, inventory_clerk_permission, purchase_date, purchase_price, purchase_condition, KBB_value) "
                    ." VALUES('$enteredVin', '$enteredCustomer_id', '$enteredInventory_clerk_permission', '$enteredPurchase_date', $enteredPurchase_price, ".
                "' $enteredPurchase_condition', $enteredKBB_value ) ";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR:  Buy Table form   <br>".  __FILE__ ." line:". __LINE__ );
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>Add Buy Info</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $_row['first_name'] . ' ' . $_row['last_name']; ?></div>
					<div class="features">   
						
                        <div class="profile_section">
							<div class="subtitle">Add Buy Info</div>

                            <form name = "add_buy" action = "add_buy.php" method="post">
                                <table>
                                    <tr>
                                        <td class ="item_label">VIN Number</td>
                                        <td>
                                            <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Customer ID </td>
                                        <td>
                                            <input type="text" name = "customer_id" value="<?php if ($_GET['customer_id']) { print $_GET['customer_id']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Inventory Clerk Permission </td>
                                        <td>
                                            <input type="text" name = "inventory_clerk_permission" value="<?php if ($_GET['inventory_clerk_permission']) { print $_GET['inventory_clerk_permission']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Date</td>
                                        <td>
                                            <input type="date" name= "purchase_date" value="<?php if ($_GET['purchase_date']) { print $_GET['purchase_date']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Price</td>
                                        <td>
                                            <input type="number" name= "purchase_price" value="<?php if ($_GET['sale_price']) { print $_GET['sale_price']; } ?>" readonly/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Purchase Condition</td>
                                        <td>
                                            <input type="text" name= "purchase_condition" value="<?php if ($_GET['purchase_condition']) { print $_GET['purchase_condition']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">KBB Value</td>
                                        <td>
                                            <input type="number" name= "KBB_value" value="<?php if ($_GET['KBB_value']) { print $_GET['KBB_value']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <input name = "add" type = "submit" id = "add" value = "Add">
                                        <input type="button" value="Cancel" onclick="history.go(-1)">
                                        <button type="reset" value="Reset">Reset</button>
                                    </tr>
                                </table>
                            </form>
                        </div>
					 </div>
				</div> 
                
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>
