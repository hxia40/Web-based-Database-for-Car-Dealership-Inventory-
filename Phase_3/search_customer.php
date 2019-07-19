<?php
include('lib/common.php');
// written by czhang613, salesperson search customer

// setup permissino
if (!isset($_SESSION['username'])) {
	header('Location: public_search.php');
	exit();
}else {
    if($_SESSION['permission'] == 3){
        header("Location: employee_search_manager.php");
        exit();
    }
}

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

// query to get a list of id
// $query = "SELECT driver_license_number " .
//      "FROM Person UNION " . "SELECT tax_identification_number " . "FROM Business";
// $result = mysqli_query($db, $query);
// include('lib/show_queries.php');
// $idlist = array('ID');

while ($row = mysqli_fetch_assoc($result)) {
  // echo $row['driver_license_number'];
  array_push($idlist, $row['driver_license_number']);
  // echo "<br>";
}
// var_dump($idlist);

// $typelist = array('Person', 'Business');
// var_dump($typelist);
// $entered_id = 'DL1234567';
/* if form was submitted, then execute query to search for vehicles */

$enteredVIN = $_GET['vin'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$entered_id = mysqli_real_escape_string($db, $_POST['id']);

  $query_p ="SELECT customer_id FROM Person WHERE driver_license_number = '$entered_id'";
  $result_p = mysqli_query($db, $query_p);
  $row_p = mysqli_fetch_assoc($result_p);

  $query_b ="SELECT customer_id FROM Business WHERE tax_identification_number = '$entered_id'";
  $result_b = mysqli_query($db, $query_b);
  $row_b = mysqli_fetch_assoc($result_b);

}
?>

<?php include("lib/header.php"); ?>
<title>Vehicle Search for Salesperson</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/team22_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="Team 22 Logo"/>
            </div>
        </div>
        <div class="center_content">
			<div class="center_left">
                <div class="features">
                <div class='profile_section'>
					    <div class='subtitle'>Your are a salesperson</div>
					    <tr> <a href='logout.php'>Logout</a></tr>
				    </div>

					<div class="profile_section">
						<div class="subtitle">Search Customer</div>
						<form name="searchform" action="search_customer.php?vin=<?php echo $enteredVIN;?>" method="POST">
                            <table>
                                <!-- <tr>
                                    <td class="item_label">Customer Type</td>
                                    <td>
                                        <select name="type_name">
                                            <option value='select' selected="true">Please select</option>
                                            <?php
                                                foreach($typelist as $var) {
                                            ?>
                                            <option value='<?php echo $var;?>'><?php echo $var;?></option>
                                            <?php
                                                }


                                            ?>
                                        </select>
                                    </td>
                                </tr> -->

                                <tr>
									<td class="item_label">Enter ID</td>
									<td><input type="text" name="id" value="(input valid ID)"
										onclick="if(this.value=='(input VIN)'){this.value=''}"/></td>
								</tr>

							</table>
							<a href="javascript:searchform.submit();" class="fancy_button">Search</a>
						</form>
					</div>

				    <div class='profile_section'>
					    <div class='subtitle'>Search Results</div>
                        <?php

                            if(!empty($row_p)) {
                                echo "Existing Customer!<br>";
                                print "<tr>";
                                $get_url1="sale_order.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
                                // $get_url1="edit_customer.php?vin={$enteredVIN}&customer_id={$row_p['customer_id']}";
                                print "<td><a href={$get_url1}>Sell This Car</a></td>";
                                // print "<td><a href={$get_url4}>Edit Customer</a></td>";
                                print "</tr>";
                            }
														else if(!empty($row_b)) {
                                echo "Existing Customer!<br>";
                                print "<tr>";
                                $get_url2="sale_order.php?vin={$enteredVIN}&customer_id={$row_b['customer_id']}";
                                print "<td><a href={$get_url2}>Next</a></td>";
                                print "</tr>";

                            }else if (!empty($entered_id)) {
                              // echo $entered_id;
                              // var_dump($idlist);
                                echo "New Customer!<br>";
                                print "<tr>";
                                $get_url3="add_customer.php?vin={$enteredVIN}";
                                print "<td><a href={$get_url3}>Next</a></td>";
                                print "</tr>";


                            }


                        ?>
				    </div>
                </div>
            </div>
        <?php include("lib/error.php"); ?>
		<div class="clear"></div>
	</div>

	<?php include("lib/footer.php"); ?>
</body>
</html>
