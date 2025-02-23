<?php

include('lib/common.php');

if (!isset($_SESSION['username']) OR ($_SESSION['permission'] != 1 && $_SESSION['permission'] != 4)) {
    header('Location: index.php');
    exit();
}


$query = "SELECT login_first_name, login_last_name " .
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredVin = mysqli_real_escape_string($db, $_POST['vin']);
    $enteredvehicle_mileage =  $_POST['vehicle_mileage'];
    $enteredvehicle_description = mysqli_real_escape_string($db, $_POST['vehicle_description']);
    $enteredmodel_name = mysqli_real_escape_string($db, $_POST['model_name']);
    $enteredmodel_year = $_POST['model_year'];
    $enteredtype_name = mysqli_real_escape_string($db, $_POST['type_name']);
    $enteredmanufacturer_name = mysqli_real_escape_string($db, $_POST['manufacturer_name']);
    $enteredsale_price = $_POST['sale_price'];

    if (empty($enteredVin)) {
        array_push($error_msg, "Please enter a validate VIN number.");
    }

    if (!empty($enteredvehicle_mileage)) {
        $query = "UPDATE Vehicle " . "SET vehicle_mileage = '$enteredvehicle_mileage' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Vehicle Mileage Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredvehicle_description)) {
        $query = "UPDATE Vehicle " . "SET vehicle_description = '$enteredvehicle_description' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: End Date Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredmodel_name)) {
        $query = "UPDATE Vehicle " . "SET model_name = '$enteredmodel_name' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Model Name Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredmodel_year)) {
        $query = "UPDATE Vehicle " . "SET model_year = '$enteredmodel_year' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Model Year Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if (!empty($enteredVendor_name)) {
        $query = "UPDATE Vehicle " . " SET manufacturer_name = '$enteredmanufacturer_name' WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Manufacturer Name Error... <br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    if ($enteredsale_price > 0) {
        $temp_result1 = mysqli_query($db, "SELECT purchase_price from Buy WHERE vin = '$enteredVin'");
        $temp_row1 = mysqli_fetch_array($temp_result1, MYSQLI_ASSOC);
        $previous_purchase_price = $temp_row1['purchase_price'];
        $change_purchase_price = floatval($enteredsale_price - $previous_purchase_price)*1.25;

        $temp_result2 = mysqli_query($db, "SELECT sale_price from Vehicle WHERE vin = '$enteredVin'");
        $temp_row2 = mysqli_fetch_array($temp_result2, MYSQLI_ASSOC);
        $previous_sale_price = $temp_row2['sale_price'];
        $new_sale_price = $previous_sale_price + $change_purchase_price;

        $query = "UPDATE Vehicle " . " SET sale_price = $new_sale_price WHERE vin = '$enteredVin'";
        $result = mysqli_query($db, $query);

        include('lib/show_queries.php');
        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "UPDATE ERROR: Sale Price Error... <br>" . __FILE__ . " line:" . __LINE__);
        }else{
            mysqli_query($db, "UPDATE Buy SET purchase_price = $enteredsale_price WHERE vin = '$enteredVin'");
            include('lib/show_queries.php');
        }
    }
}//end of POST
?>

<?php include("lib/header.php"); ?>
<head>
    <title>Edit Vehicle Info</title>
</head>
<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['login_first_name'] . ' ' . $row['login_last_name']; ?></div>
            <div class="features">
                <div class = "profile_section">
                    <div class = "subtitle">Edit Vehicle Info</div>
                    <form name = "confirm_edit_vehicle" action = "edit_vehicle.php" method="post">
                        <table>
                            <tr>
                                <td class ="item_label">VIN Number</td>
                                <td>
                                    <input type="text" name = "vin" value="<?php if ($_GET['vin']) { print $_GET['vin']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label"> Vehicle Mileage </td>
                                <td>
                                    <input type="number" name= "vehicle_mileage" value="<?php if ($_GET['vehicle_mileage']) { print $_GET['vehicle_mileage']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Vehicle Description</td>
                                <td>
                                    <input type="text" name= "vehicle_description" value="<?php if ($_GET['vehicle_description']) { print $_GET['vehicle_description']; } ?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Model Name</td>
                                <td>
                                    <input type="text" name = "model_name" value ="<?php if($_GET['model_name']) {print $_GET['model_name'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Model Year</td>
                                <td>
                                    <input type="number" name = "model_year" value ="<?php if($_GET['model_year']) {print $_GET['model_year'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Type Name</td>
                                <td>
                                    <input type="text" name = "type_name" value ="<?php if($_GET['type_name']) {print $_GET['type_name'];}?>" />
                                </td>
                            </tr>
                            <tr>
                                <td class = "item_label">Manufacturer Name</td>
                                <td>
                                    <input type="text" name = "manufacturer_name" value ="<?php if($_GET['manufacturer_name']) {print $_GET['manufacturer_name'];}?>" />
                                </td>
                            </tr>

                            <tr>
                                <td class = "item_label">Purchase Price (Sale Price will be automatically calculated)</td>
                                <td>
                                    <input type="number" name = "sale_price" value ="<?php if($_GET['sale_price']) {print $_GET['sale_price'];}?>" />
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
