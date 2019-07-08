<?php

include('lib/common.php');
// written by GTusername4
 
    $query = "SELECT login_first_name, login_last_name " .
		 "FROM Users " .
		 "WHERE Users.username = '{$_SESSION['username']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User Profile... <br>".  __FILE__ ." line:". __LINE__ );
    }
?>

<?php
    if(isset($_POST['add'])){
        $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
        $enteredStart_date = mysqli_real_escape_string($db, $_POST['start_date']);
        $enteredEnd_date = mysqli_real_escape_string($db, $_POST['end_date']);
        $enteredRepair_status = mysqli_real_escape_string($db, $_POST['repair_status']);
        $enteredRepair_Description = mysqli_real_escape_string($db, $_POST['repair_description']);
        $enteredVendor_name = mysqli_real_escape_string($db, $_POST['vendor_name']);
        $enteredRepair_cost = mysqli_real_escape_string($db, $_POST['repair_cost']);
        $enteredNHTSA_recall_campagin_Number = mysqli_real_escape_string($db, $_POST['nhtsa_recall_compaign_number']);
        $enteredInventory_clerk_permssion = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);

        if (empty($enteredVin)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate VIN number... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredStart_date)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Start date... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredRepair_status)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Status... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredRepair_Description)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Description... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredVendor_name)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Vendor Name... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredRepair_cost)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Cost... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (empty($enteredNHTSA_recall_campagin_Number)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Repair Cost... <br>" . __FILE__ . " line: " . __LINE__);
        }else if (!empty($enteredinventory_clerk_permssion)) {
            array_push($error_msg, "ADD ERROR: Please enter a validate Inventory Clerk Permssion <br>" . __FILE__ . " line: " . __LINE__);
        }else{
            $query = "INSERT INTO Repair VALUES('$enteredvin', '$enteredstart_date', '$enteredend_date', ".
                "'$enteredrepair_status', '$enteredrepair_description', '$enteredvendor_name',".
                "'$enteredrepair_cost', '$enteredNHTSA_recall_campagin_Number', '$enteredinventory_clerk_permssion');";
            $result = mysqli_query($db, $query);

            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR:  Repair Table form   <br>".  __FILE__ ." line:". __LINE__ );
            }
        }
    }
?>


<?php include("lib/header.php"); ?>
		<title>GTOnline Edit Profile</title>
	</head>
	
	<body>
    	<div id="main_container">
        <?php include("lib/menu.php"); ?>
    
			<div class="center_content">	
				<div class="center_left">
					<div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div>          
					<div class="features">   
						
                        <div class="Add Repair section">
							<div class="subtitle">Add Repair Info</div>

                            <form name = "add" action = "add_repair.php" method="post">
                                <table>
                                    <tr>
                                        <td class ="item_label">VIN Number</td>
                                        <td>
                                            <input type="text" name = "vin" value="<?php if ($row['vin']) { print $row['vin']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label"> Repair Status </td>
                                        <td>
                                            <select name = "repair_status">
                                                <option value="Pending" <?php if ($row['repair_status'] == 'Pending') { print 'selected="true"';} ?> >Pending</option>
                                                <option value="In Progress" <?php if ($row['repair_status'] == 'In_Progress') { print 'selected="true"';} ?> >In Progress</option>
                                                <option value="Completed" <?php if ($row['repair_status'] == 'Completed') { print 'selected="true"';} ?> >Completed</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Start Date</td>
                                        <td>
                                            <input type="datetime-local" name= "start_date" value="<?php if ($row['start_date']) { print $row['start_date']; } ?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Repair Description</td>
                                        <td>
                                            <input type="text" name = "repair_description" value ="<?php if($row['repair_description']) {print $row['repair_description'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Vendor Name</td>
                                        <td>
                                            <input type="text" name = "vendor_name" value ="<?php if($row['vendor_name']) {print $row['vendor_name'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Repair Cost</td>
                                        <td>
                                            <input type="number" name = "repair_cost" value ="<?php if($row['repair_cost']) {print $row['repair_cost'];}?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class = "item_label">NHTSA Recall Campagin Number</td>
                                        <td>
                                            <input type="text" name = "repair_cost" value ="<?php if($row['nhtsa_recall_compaign_number']) {print $row['nhtsa_recall_compaign_number'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class = "item_label">Inventory Clerk Permission</td>
                                        <td>
                                            <input type="text" name = "inventory_clerk_permission" value ="<?php if($row['inventory_clerk_permission']) {print $row['inventory_clerk_permission'];}?>" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class ="item_label">End Date</td>
                                        <td>
                                            <input type="datetime-local" name= "end_date" value="<?php echo date('YYYY-MM-DD'); ?>";
                                        </td>
                                    </tr>
                                    <tr>
                                        <input name = "add" type = "submit" id = "add" value = "Add">
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
