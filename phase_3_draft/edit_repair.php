<?php

include('lib/common.php');

if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
    header('Location: index.php');
    exit();
}

$query = " SELECT login_first_name, login_last_name " .
    " FROM Users WHERE Users.username = '{$_SESSION['username']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if(!is_bool($result) && (mysqli_num_rows($result) > 0)){
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    $user_name = $row['login_first_name'] . " " . $row['login_last_name'];
}else{
    array_push($error_msg, "Query Error: Failed to get User profile... <br>" . __FILE__ . "line:". __LINE__);
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
    $enteredStart_date = mysqli_real_escape_string($db, $_POST['start_date']);

    if(empty($enteredVin) || empty($enteredStart_date)){
        header('Location: view_repair.php');
        exit();
    }

    $t = mysqli_query($db, "SELECT repair_status from Repair WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date' AND repair_status == 'completed' ");
    if(mysqli_num_rows($t) > 0){//current repair is completed
        header('Location: view_repair.php');
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
    $enteredStart_date = mysqli_real_escape_string($db, $_POST['start_date']);

    if(empty($enteredVin) || empty($enteredStart_date)){
        header('Location: view_repair.php');
        exit();
    }

    $t = mysqli_query($db, "SELECT repair_status from Repair WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date' AND repair_status == 'completed' ");
    if(mysqli_num_rows($t) > 0){//current repair is completed
        header('Location: view_repair.php');
        exit();
    }

    $enteredEnd_date = mysqli_real_escape_string($db, $_POST['end_date']);
    $enteredRepair_status = mysqli_real_escape_string($db, $_POST['repair_status']);
    $enteredRepair_Description = mysqli_real_escape_string($db, $_POST['repair_description']);
    $enteredVendor_name = mysqli_real_escape_string($db, $_POST['vendor_name']);
    $enteredRepair_cost =$_POST['repair_cost'];
    $enteredNHTSA_recall_campagin_Number = mysqli_real_escape_string($db, $_POST['nhtsa_recall_compaign_number']);
    $enteredInventory_clerk_permssion = mysqli_real_escape_string($db, $_POST['inventory_clerk_permission']);

    if (empty($enteredVin) || empty($enteredStart_date)) {
        array_push($error_msg, "Please enter a validate VIN number and start date.");
    }else{
        if (!empty($enteredEnd_date)) {
            $query = "UPDATE Repair " . "SET end_date = '$enteredEnd_date' WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: End_Date Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
        if (!empty($enteredRepair_status)) {
            $query = "UPDATE Repair " . "SET repair_status = '$enteredRepair_status' WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Repair_status Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if (!empty($enteredRepair_Description)) {
            $query = "UPDATE Repair " . " SET repair_description = '$enteredRepair_Description' WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Repair_Description Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if (!empty($enteredVendor_name)) {
            $query = "UPDATE Repair" . " SET vendor_name = '$enteredVendor_name' WHERE vin = '$enteredVin'  AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Vendor_name Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if ($enteredRepair_cost > 0) {
            $temp_result = mysqli_query($db,"SELECT repair_cost from Repair WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date' ");
            $temp_row = mysqli_fetch_array($temp_result, MYSQLI_ASSOC);
            $previous_repair_cost = $temp_row['repair_cost'];

            $temp_result2 = mysqli_query($db, "SELECT sale_price FROM Vehicle WHERE vin = '$enteredVin' ");
            $temp_row2 = mysqli_fetch_array($temp_result2, MYSQLI_ASSOC);
            $previous_sale_price = $temp_row2['sale_price'];

                include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "Failed to get Previous Repair Price <br>" . __FILE__ . " line:" . __LINE__);
            }

            $query = "UPDATE Repair" . " SET repair_cost = $enteredRepair_cost WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Repair_cost Error... <br>" . __FILE__ . " line:" . __LINE__);
            }else{
                $change_repair_cost = ($enteredRepair_cost - $previous_repair_cost)*1.1;
                $new_sale_price = $previous_sale_price + $change_repair_cost;

                $query = "UPDATE Vehicle " . " SET sale_price = $new_sale_price WHERE vin = $enteredVin" ;
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
            }
        }

        if (!empty($enteredNHTSA_recall_campagin_Number)) {
            $query = "UPDATE Repair" . " SET nhtsa_recall_compaign_number = '$enteredNHTSA_recall_campagin_Number' WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: NHTSA_recall_campagin_Number Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }

        if (!empty($enteredInventory_clerk_permssion)) {
            $query = "UPDATE Repair " . " SET inventory_clerk_permission = '$enteredInventory_clerk_permssion' WHERE vin = '$enteredVin' AND start_date = '$enteredStart_date'";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');

            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "UPDATE ERROR: Inventory_clerk_permssion Error... <br>" . __FILE__ . " line:" . __LINE__);
            }
        }
    }
}//end of POST
?>

<?php include("lib/header.php"); ?>
<head>
    <title>Edit Repair Info</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['login_first_name'] . ' ' . $row['login_last_name']; ?></div>
            <div class="features">
                <div class = "profile_section">
                    <div class = "subtitle">Edit Repair Info</div>
                    <form name = "confirm_edit_repair" action = "edit_repair.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" id = "vin" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label"> Repair Status </td>
                                <td>
                                    <select name = "repair_status">
                                        <option value="pending" <?php if ($_GET['repair_status'] == 'pending' ) { print 'selected="true"';} ?> >pending</option>
                                        <option value="in progress" <?php if ($_GET['repair_status'] == 'in progress') { print 'selected="true"';} ?> >in progress</option>
                                        <option value="completed" <?php if ($_GET['repair_status'] == 'completed') { print 'selected="true"';} ?> >completed</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Start Date</td>
                                <td>
                                    <input type="date" name= "start_date" value="<?php if ($_GET['start_date']) { print $_GET['start_date']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Repair Description</td>
                                <td>
                                    <input type="text" name = "repair_description" value ="<?php if($_GET['repair_description']) {print $_GET['repair_description'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Vendor Name</td>
                                <td>
                                    <input type="text" name = "vendor_name" value ="<?php if($_GET['vendor_name']) {print $_GET['vendor_name'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Repair Cost</td>
                                <td>
                                    <input type="number" name = "repair_cost" value ="<?php if($_GET['repair_cost']) {print $_GET['repair_cost'];}?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class = "item_label">NHTSA Recall Campagin Number</td>
                                <td>
                                    <input type="text" name = "nhtsa_recall_compaign_number" value ="<?php if($_GET['nhtsa_recall_compaign_number']) {print $_GET['nhtsa_recall_compaign_number'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Inventory Clerk Permission</td>
                                <td>
                                    <input type="text" name = "inventory_clerk_permission" value ="<?php if($_GET['inventory_clerk_permission']) {print $_GET['inventory_clerk_permission'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class ="item_label">End Date</td>
                                <td>
                                    <input type="date" name= "end_date" value="<?php if ($_GET['end_date']) { print $_GET['end_date']; } ?>" />
                                </td>
                            </tr>
                            <tr>
                                <input name = "edit" type = "submit" id = "edit" value = "Confirmed and Edit">
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
</div>

<?php include("lib/footer.php"); ?>
</body>

</html>
