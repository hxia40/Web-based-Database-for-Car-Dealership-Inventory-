<?php
include('lib/common.php');
// written by czhang613

// setup permission and login info
if (!isset($_SESSION['username'])) {
	header('Location: public_search.php');
	exit();
} else {
    if($_SESSION['permission'] == 2){
        header("Location: employee_search_salesperson.php");
        exit();
    }
    if($_SESSION['permission'] == 3){
        header("Location: employee_search_manager.php");
        exit();
    }
		if($_SESSION['permission'] == 4){
        header("Location: employee_search_owner.php");
        exit();
    }
}


    $enteredVIN = $_GET['vin'];
    $query = "SELECT Vehicle.vin, vehicle_mileage, vehicle_description, model_name, model_year,
    manufacturer_name, GROUP_CONCAT(DISTINCT vehicle_color SEPARATOR ', ') AS color, sale_price
    FROM Vehicle JOIN VehicleColor ON Vehicle.vin = VehicleColor.vin
    JOIN Repair ON Vehicle.vin = Repair.vin
    WHERE Vehicle.vin = '$enteredVIN'";

    $query2 = "SELECT start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, Repair.nhtsa_recall_compaign_number, Buy.inventory_clerk_permission, purchase_price
    FROM Vehicle JOIN Buy on Vehicle.vin = Buy.vin
    JOIN Repair on Vehicle.vin = Repair.vin
    WHERE Vehicle.vin = '$enteredVIN' ORDER BY start_date DESC";

    $result = mysqli_query($db, $query);
    $result2 = mysqli_query($db, $query2);
    include('lib/show_queries.php');

    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to fetch Vehicle Detail Information... <br>".  __FILE__ ." line:". __LINE__ );
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
					<!-- <div class="title_name"><?php print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
					<div class="features">
                           <div class="View vehicle detail section">
							<div class="subtitle">View Vehicle Detail</div>
              <table>
                <th class="subtitle">Basic Information</th>
                  <tr>
                      <td class="item_label">VIN</td>
                      <td>
                          <?php print $row['vin']; ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Mileage</td>
                      <td>
                          <?php print $row['vehicle_mileage'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Description</td>
                      <td>
                          <?php print $row['vehicle_description'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model Name</td>
                      <td>
                          <?php print $row['model_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Model Year</td>
                      <td>
                          <?php print $row['model_year'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Manufacturer Name</td>
                      <td>
                          <?php print $row['manufacturer_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vehicle Color</td>
                      <td>
                          <?php print $row['color'];?>
                      </td>
                  </tr>


                  <tr>
                      <td class="item_label">Sale Price</td>
                      <td>
                          <?php print $row['sale_price'];?>
                      </td>
                  </tr>
									<?php
									print "<tr>";
									$get_url3="view_vehicle.php?view=View&vin={$enteredVIN}";
									print "<td><a href={$get_url3}>Edit This Vehicle</a></td>";
									print "</tr>";
									?>




                  <th class="subtitle">Most Recent Repair</th>



                  <tr>
                      <td class="item_label">Repair Start Date</td>
                      <td>
                          <?php print $row2['start_date'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Repair End Date</td>
                      <td>
                          <?php
													if ($row2['end_date'] != '1970-01-01 00:00:00'){
													print $row2['end_date'];
												} else {
													print "N/A";
												}
												?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Repair Status</td>
                      <td>
                          <?php print $row2['repair_status'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Repair Description</td>
                      <td>
                          <?php print $row2['repair_description'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Repair Cost</td>
                      <td>
                          <?php print $row2['repair_cost'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Vendor Name</td>
                      <td>
                          <?php print $row2['vendor_name'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Recall Number</td>
                      <td>
                          <?php print $row2['nhtsa_recall_compaign_number'];?>
                      </td>
                  </tr>

                  <th class="subtitle">Purhcase Information</th>
                  <tr>
                      <td class="item_label">Purchased Clerk Permission</td>
                      <td>
                          <?php print $row2['inventory_clerk_permission'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchase Price</td>
                      <td>
                          <?php print $row2['purchase_price'];?>
                      </td>
                  </tr>



									<?php
                  // setup links to view, add repair, edit repair

									print "<tr>";
                  $get_url0="view_repair.php?vin={$enteredVIN}";
                  print "<td><a href={$get_url0}>View Repair History</a></td>";
                  print "</tr>";

									if ($row2['repair_status'] == 'complete') {
										print "<tr>";
	                  $get_url1="add_repair.php?vin={$enteredVIN}&repair_status={$row2['repair_status']}";
	                  print "<td><a href={$get_url1}>Add Repair</a></td>";
	                  print "</tr>";
									}
									if ($row2['repair_status'] != 'complete' ) {
										print "<tr>";
	                  $get_url2="edit_repair.php?vin={$enteredVIN}&repair_status={$row2['repair_status']}".
	                      "&start_date={$row2['start_date']}&repair_description={$row2['repair_description']}&vendor_name={$row2['vendor_name']}".
	                      "&repair_cost={$row2['repair_cost']}&nhtsa_recall_compaign_number={$row2['nhtsa_recall_compaign_number']}".
	                      "&inventory_clerk_permission = {$row2['inventory_clerk_permission']}&end_date ={$row2['end_date']}";
	                  print "<td><a href={$get_url2}>Edit Ongoing Repair</a></td>";
	                  print "</tr>";
	                  print "<tr>";
	                  $get_url3="delete_repair.php?vin={$enteredVIN}&start_date={$row2['start_date']}";
	                  print "<td><a href={$get_url3}>Delete Ongoing Repair</a></td>";
	                  print "</tr>";
									}


                  // echo $_SESSION['permission']
?>


              </table>
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
