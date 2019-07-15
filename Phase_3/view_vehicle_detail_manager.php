<?php
include('lib/common.php');
// written by czhang613

// setup permission and login info
if (!isset($_SESSION['username'])) {
	header('Location: public_search.php');
	exit();
} else {
    if($_SESSION['permission'] == 1){
        header("Location: employee_search_clerk.php");
        exit();
    }
    if($_SESSION['permission'] == 2){
        header("Location: employee_search_salesperson.php");
        exit();
    }
    if($_SESSION['permission'] == 4){
        header("Location: employee_search_owner.php");
        exit();
    }
}


    $enteredVIN = $_GET['vin'];
    $query = "SELECT Vehicle.vin, vehicle_mileage, vehicle_description, model_name, model_year,
    manufacturer_name, GROUP_CONCAT(vehicle_color SEPARATOR ', ') AS color, sale_price
    FROM Vehicle
    JOIN VehicleColor ON Vehicle.vin = VehicleColor.vin
    JOIN Repair ON Vehicle.vin = Repair.vin
    WHERE Vehicle.vin = $enteredVIN";

    $query2 = "SELECT start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, Repair .nhtsa_recall_compaign_number, Buy.inventory_clerk_permission, purchase_price, purchase_condition, Buy.customer_id AS seller_customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip,
    Users.login_first_name AS login_first_name1, Users.login_last_name AS login_last_name1
    FROM Vehicle
    JOIN Buy ON Vehicle.vin = Buy.vin
    JOIN Repair ON Vehicle.vin = Repair.vin
    JOIN Customer ON Buy.customer_id = Customer.customer_id
    JOIN InventoryClerk ON InventoryClerk.inventory_clerk_permission= Buy.inventory_clerk_permission
    JOIN Users ON InventoryClerk.username = Users.username
    WHERE Vehicle.vin = $enteredVIN";

    $query3 = "SELECT Vehicle.vin,
    Sell.salesperson_permission, Sell.customer_id AS buyer_customer_id, sale_date, phone_number, email, customer_street, customer_city, customer_state, customer_zip,
    login_first_name AS login_first_name2, login_last_name AS login_last_name2
    FROM Vehicle
    JOIN Sell ON Vehicle.vin = Sell.vin
    JOIN Customer ON Sell.customer_id = Customer.customer_id
    JOIN Salesperson ON Salesperson.salesperson_permission = Sell.salesperson_permission
    JOIN Users ON Salesperson.username = Users.username
    WHERE Vehicle.vin = $enteredVIN";

// setup queries to get SELLERS name, ethier a person or business:
    $query_seller_person = "SELECT customer_first_name, customer_last_name FROM Person
    JOIN BUY ON Buy.customer_id = Person.customer_id
    JOIN Vehicle ON Buy.vin = Vehicle.vin
    WHERE Vehicle.vin = $enteredVIN";
    $result_seller_person = mysqli_query($db, $query_seller_person);
    $array_seller_person = mysqli_fetch_array($result_seller_person, MYSQLI_ASSOC);

    $query_seller_business = "SELECT business_name, primary_contact_name, primary_contact_title FROM Business
    JOIN BUY ON Buy.customer_id = Business.customer_id
    JOIN Vehicle ON Buy.vin = Vehicle.vin
    WHERE Vehicle.vin = $enteredVIN";
    $result_seller_business = mysqli_query($db, $query_seller_business);
    $array_seller_business = mysqli_fetch_array($result_seller_business, MYSQLI_ASSOC);

// setup queries to get BUYERS name, ethier a person or business:
    $query_buyer_person = "SELECT customer_first_name, customer_last_name FROM Person
    JOIN Sell ON Sell.customer_id = Person.customer_id
    JOIN Vehicle ON Sell.vin = Vehicle.vin
    WHERE Vehicle.vin = $enteredVIN";
    $result_buyer_person = mysqli_query($db, $query_buyer_person);
    $array_buyer_person = mysqli_fetch_array($result_buyer_person, MYSQLI_ASSOC);

    $query_buyer_business = "SELECT business_name, primary_contact_name, primary_contact_title FROM Business
    JOIN Sell ON Sell.customer_id = Business.customer_id
    JOIN Vehicle ON Sell.vin = Vehicle.vin
    WHERE Vehicle.vin = $enteredVIN";
    $result_buyer_business = mysqli_query($db, $query_buyer_business);
    $array_buyer_business = mysqli_fetch_array($result_buyer_business, MYSQLI_ASSOC);


    $result = mysqli_query($db, $query);
    $result2 = mysqli_query($db, $query2);
    $result3 = mysqli_query($db, $query3);
    include('lib/show_queries.php');

    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
        $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
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
					<!-- <div class="title_name"><?php //print $row['first_name'] . ' ' . $row['last_name']; ?></div> -->
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
                  <th class="subtitle">Repair Information</th>
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


                  <th class="subtitle">Purhcase Information</th>
                  <tr>
                  <td class="item_label">Inventory Clerk's Name></td>
                    <td>
                      <?php
                      echo $row2['login_first_name1'] . " " . $row2['login_last_name1'];
                      ?>
                    </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchase Price</td>
                      <td>
                          <?php print $row2['purchase_price'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Purchased Condition</td>
                      <td>
                          <?php print $row2['purchase_condition'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Seller's Customer ID</td>
                      <td>
                          <?php print $row2['seller_customer_id'];?>
                      </td>
                  </tr>

                  <tr>
                  <td class="item_label">Seller's Name</td>
                  <td>
                      <?php
                      // echo var_dump($array_seller_person);
                      // echo "next<br>";
                      // echo var_dump($array_seller_business);
                      if (!is_null($array_seller_person['customer_first_name'])) {
                        echo ("Person Name: ". $array_seller_person['customer_first_name'] . " " . $array_seller_person['customer_last_name']);
                      }
                      else {
                        echo ("Business Name: " . $array_seller_business['business_name']. ",<br> " . $array_seller_business['primary_contact_name'] . ",<br> " . $array_seller_business['primary_contact_title']);
                      }
                      ?>
                  </tr>

                  <tr>
                      <td class="item_label">Seller's Phone Number</td>
                      <td>
                          <?php print $row2['phone_number'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Seller's Email</td>
                      <td>
                          <?php print $row2['email'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Seller's Address</td>
                      <td>
                          <?php
                          echo ($row2['customer_street'] . "<br>" . $row2['customer_city'] . ", " . $row2['customer_state']. ", " . $row2['customer_zip']);
                          ?>
                      </td>
                  </tr>


                  <th class="subtitle">Sell Information</th>
                  <tr>
                      <td class="item_label">Buyer's Customer ID</td>
                      <td>
                          <?php print $row3['buyer_customer_id'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Salesperson's Name</td>
                      <td>
                          <?php
                          echo $row3['login_first_name2'] . " " . $row3['login_last_name2'];
                          ?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Sale Date</td>
                      <td>
                          <?php print $row3['sale_date'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Buyer's Phone Number</td>
                      <td>
                          <?php print $row3['phone_number'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Buyer's Street Address</td>
                      <td>
                          <?php print $row3['customer_street'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Buyer's City</td>
                      <td>
                          <?php print $row3['customer_city'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Buyer's State</td>
                      <td>
                          <?php print $row3['customer_state'];?>
                      </td>
                  </tr>
                  <tr>
                      <td class="item_label">Buyer's Zip Code</td>
                      <td>
                          <?php print $row3['customer_zip'];?>
                      </td>
                  </tr>
                  <!-- <tr>
                      <td class="item_label">Buyer's Address</td>
                      <td>
                          <?php
                          // echo ($row3['customer_street'] . "<br>" . $row3['customer_city'] . ", " . $row3['customer_state']. ", " . $row3['customer_zip']);
                          ?>
                      </td>
                  </tr> -->


                  <?php
                  // print "<tr>";
                  if (!is_null($array_buyer_person['customer_first_name'])) {
                    echo "<tr>";
                    echo  "<td class=\"item_label\">Buyer's First Name</td>";
                    echo "<td>";
                    echo $array_buyer_person['customer_first_name'];
                    print "</td>";
                    echo "</tr>";

										echo "<tr>";
                    echo  "<td class=\"item_label\">Buyer's Last Name</td>";
                    echo "<td>";
                    echo $array_buyer_person['customer_last_name'];
                    print "</td>";
                    echo "</tr>";
                  } else if (!is_null($array_buyer_business['business_name'])) {
										echo "<tr>";
                    echo  "<td class=\"item_label\">Buyer's Business Name</td>";
                    echo "<td>";
                    echo $array_buyer_business['business_name'];
                    print "</td>";
                    echo "</tr>";

										echo "<tr>";
                    echo  "<td class=\"item_label\">Buyer's Primary Contact Name</td>";
                    echo "<td>";
                    echo $array_buyer_business['primary_contact_name'];
                    print "</td>";
                    echo "</tr>";
										echo "<tr>";

                    echo  "<td class=\"item_label\">Buyer's Primary Contact Title</td>";
                    echo "<td>";
                    echo $array_buyer_business['primary_contact_title'];
                    print "</td>";
                    echo "</tr>";
									}

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
