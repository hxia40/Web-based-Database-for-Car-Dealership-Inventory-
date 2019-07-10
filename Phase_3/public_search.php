<?php
include('lib/common.php');
// written by GTusername1

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}
                     
/* if form was submitted, then execute query to search for friends */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
	$entered_type_name = mysqli_real_escape_string($db, $_POST['type_name']);
	$entered_manufacturer_name = mysqli_real_escape_string($db, $_POST['manufacturer_name']);
    $entered_model_year = mysqli_real_escape_string($db, $_POST['model_year']);
    $entered_vehicle_color = mysqli_real_escape_string($db, $_POST['vehicle_color']);

    $query = "SELECT Vehicle.vin, type_name, model_year, manufacturer_name, vehicle_mileage, sale_price " .
             "FROM Vehicle " .
             "WHERE Vehicle.type_name = '{$_SESSION['type_name']}'";
    
    /*
	if (!empty($name) or !empty($email) or !empty($home_town)) {
		$query = $query . " AND (1=0 ";
		
		if (!empty($name)) {
			$query = $query . " OR first_name LIKE '%$name%' OR last_name LIKE '%$name%' ";
		}
		if (!empty($email)) {
			$query = $query . " OR RegularUser.email LIKE '%$email%' ";
		}
		if (!empty($home_town)) {
			$query = $query . " OR home_town LIKE '%$home_town%' ";
		}
		$query = $query . ") ";
	}
	
    $query = $query . " ORDER BY last_name, first_name";
    
    */
    
	$result = mysqli_query($db, $query);
    
    include('lib/show_queries.php');

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        //$user_name = $row['first_name'] . " " . $row['last_name'];
    } else {
        array_push($error_msg,  "SELECT ERROR: User profile <br>" . __FILE__ ." line:". __LINE__ );
    }

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "SELECT ERROR:Failed to find friends ... <br>" . __FILE__ ." line:". __LINE__ );
    }
}

?>

<?php include("lib/header.php"); ?>
<title>"Vehicle Search for Public"</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/gtonline_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/>
            </div>
        </div>

        <div class="center_content">
			<div class="center_left">
                <div class="features">
					<div class="profile_section">
						<div class="subtitle">Search for Vehicles</div> 	
							<form name="searchform" action="search_friends.php" method="POST">
                                <table>	
                                    <tr>
                                        <td class="item_label">Vehicle Type</td>
                                        <td>
                                            <select name="type_name">
                                                <?php
                                                    for ($type_n = 0, $type_n <= count($VEHICLE_TYPES_LIST)-1, $type_n++) {
                                                        if ($row['type_name'] == $VEHICLE_TYPES_LIST[$type_n]) {
                                                            $if_selected = 'selected="true"';
                                                        } else {
                                                            $if_selected = '';
                                                        }
                                                        echo "<option value='{$VEHICLE_TYPES_LIST[$type_n]}' " . $if_selected .  
                                                            '>' . $VEHICLE_TYPES_LIST[$type_n]) '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Manufacturer</td>
                                        <td>
                                            <select name="manufacturer_name">
                                            <?php
                                                for ($manu_n = 0, $manu_n <= count($MANUFACTURER_LIST)-1, $manu_n++) {
                                                    if ($row['type_name'] == $MANUFACTURER_LIST[$manu_n]) {
                                                        $if_selected = 'selected="true"';
                                                    } else {
                                                        $if_selected = '';
                                                    }
                                                    echo "<option value='{$MANUFACTURER_LIST[$manu_n]}' " . $if_selected .  
                                                        '>' . $MANUFACTURER_LIST[$manu_n]) '</option>';
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Model year</td>
                                        <td>
                                            <select name="model_year">
                                            <?php
                                                for ($year_n = 1900, $year_n <= 2020, $year_n++) {
                                                    if ($row['model_year'] == $year_n) {
                                                        $if_selected = 'selected="true"';
                                                    } else {
                                                        $if_selected = '';
                                                    }
                                                    echo "<option value='$year_n' " . $if_selected .  
                                                        '>' . $year_n '</option>';
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item_label">Color</td>
                                        <td>
                                            <select name="vehicle_color">
                                            <?php
                                                for ($color_n = 0, $color_n <= count($COLORS_LIST)-1, $color_n++) {
                                                    if ($row['type_name'] == $COLORS_LIST[$color_n]) {
                                                        $if_selected = 'selected="true"';
                                                    } else {
                                                        $if_selected = '';
                                                    }
                                                    echo "<option value='{$COLORS_LIST[$color_n]}' " . $if_selected .  
                                                        '>' . $COLORS_LIST[$color_n]) '</option>';
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
									<tr>
										<td class="item_label">keyword</td>
										<td><input type="text" name="keyword" /></td>
									</tr>
									
								</table>
									<a href="javascript:searchform.submit();" class="fancy_button">Search</a> 					
							</form>
						</div>
					</div>	
					<div class='profile_section'>
						<div class='subtitle'>Search Results</div>
					    <table>
						    <tr>
							    <td class='heading'>VIN</td>
                                <td class='heading'>Vehicle Type</td>
                                <td class='heading'>Model Year</td>
                                <td class='heading'>Manufacturer</td>
                                <!-- <td class='heading'>Model</td> -->
                                <!-- <td class='heading'>Color</td> -->
                                <td class='heading'>Mileage</td>
                                <td class='heading'>Sale Price</td>
							</tr>
							<?php
								if (isset($result)) {
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
										$friend_email = urlencode($row['email']);
										print "<tr>";
										//print "<td><a href='request_friend.php?friend_email=$friend_email'>{$row['first_name']} {$row['last_name']}</a></td>";
                                        print "<td>{$row['vin']}</td>";
                                        print "<td>{$row['type_name']}</td>";	
                                        print "<td>{$row['model_year']}</td>";	
                                        print "<td>{$row['manufacturer_name']}</td>";
                                        print "<td>{$row['vehicle_mileage']}</td>";
                                        print "<td>{$row['sale_price']}</td>";								
										print "</tr>";
									}
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