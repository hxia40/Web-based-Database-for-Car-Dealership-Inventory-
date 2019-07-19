<?php
include('lib/common.php');
// written by czhang613
    $enteredVIN = '2222222';
    $query = "SELECT Vehicle.vin, vehicle_mileage, vehicle_description, model_name, model_year,
    manufacturer_name, GROUP_CONCAT(vehicle_color SEPARATOR ', ') AS color, sale_price
    FROM Vehicle JOIN VehicleColor ON Vehicle.vin = VehicleColor.vin
    JOIN Repair ON Vehicle.vin = Repair.vin
    WHERE Vehicle.vin = $enteredVIN";

    $query2 = "SELECT start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, Repair .nhtsa_recall_compaign_number, Buy.inventory_clerk_permission, purchase_price
    FROM Vehicle JOIN Buy on Vehicle.vin = Buy.vin
    JOIN Repair on Vehicle.vin = Repair.vin
    WHERE Vehicle.vin = $enteredVIN";

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
                  <tr>
                      <td class="item_label">Repair Start Date</td>
                      <td>
                          <?php print $row2['start_date'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Repair End Date</td>
                      <td>
                          <?php print $row2['end_date'];?>
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
