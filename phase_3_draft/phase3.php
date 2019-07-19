<option value="pending" <?php if ($_GET['repair_status'] == 'pending' ) { print 'selected="true"';} ?> >pending</option>


<tr>
	<td class="item_label">Recall Manufacturer</td>
	<td>
		<select name="recall_manufacturer">
			<option value='select' selected="true">Please select</option>
			<?php
				foreach($MANUFACTURER_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['recall_manufacturer'] == $var) { print 'selected="true"';}else if($_POST['recall_manufacturer'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</select>
	</td>
</tr>

<tr>
	<td class="item_label">Vehicle Type</td>
	<td>
		<select name="type_name">
			<option value='select' selected="true">Please select</option>
			<?php
				foreach($VEHICLE_TYPES_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['type_name'] == $var) { print 'selected="true"';}else if($_POST['type_name'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</select>
	</td>
</tr>

<tr>
	<td class="item_label">Manufacturer Name</td>
	<td>
		<select name="manufacturer_name">
			<option value='select' selected="true">Please select</option>
			<?php
				foreach($MANUFACTURER_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['manufacturer_name'] == $var) { print 'selected="true"';}else if($_POST['manufacturer_name'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</select>
	</td>
</tr>

<tr>
	<td class="item_label">Purchase Condition</td>
	<td>
		<select name="purchase_condition">
			<option value='select' selected="true">Please select</option>
			<?php
				foreach($PURCHASE_CONDITION_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['purchase_condition'] == $var) { print 'selected="true"';}else if($_POST['purchase_condition'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</select>
	</td>
</tr>

<tr>
	<td class="item_label">Model year</td>
	<td>
		<select name="model_year">
			<option value=0 selected="true">Please select</option>
			<?php
				for($n_year=2020; $n_year>=1900; $n_year--) {
			?>
			<option value= '<?php echo $n_year;?>'><?php echo $n_year;?></option>
			<?php
				}
			?>
		</select>
	</td>
 </tr>
 
 $PURCHASE_CONDITION_LIST=array(
    "Excellent",
    "Very Good",
    "Good",
    "Fair",
);

$pull_customer_id_query = "SELECT customer_id FROM Customer "
$pull_customer_id_results = mysqli_query($db, $pull_customer_id_query);
$CUSTOMER_ID_LIST = [];
while($CUSTOMER_ID = mysqli_fetch_assoc(pull_customer_id_results)){
	$CUSTOMER_ID_LIST[] = $CUSTOMER_ID;
}

<tr>
	<td class="item_label">Customer ID</td>
	<td>
		<datalist id = 'customer_id'>
			<?php
				foreach($CUSTOMER_ID_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['customer_id'] == $var) { print 'selected="true"';}else if($_POST['customer_id'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</datalist>
	</td>
</tr>

$pull_vendor_query = "SELECT vendor_name FROM Vendor "
$pull_venodr_results = mysqli_query($db, $pull_vendor_query);
$VENDOR_LIST = [];
while($VENDOR = mysqli_fetch_assoc(pull_venodr_results)){
	$VENDOR_LIST[] = $VENDOR;
}

<tr>
	<td class="item_label">Vendor Name</td>
	<td>
		<datalist id = 'vendor_name'>
			<?php
				foreach($VENDOR_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['vendor_name'] == $var) { print 'selected="true"';}else if($_POST['vendor_name'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</datalist>
	</td>
</tr>


$pull_model_name_query = "SELECT model_name FROM Vehicle "
$pull_model_name_results = mysqli_query($db, $pull_model_name_query);
$MODEL_NAME_LIST = [];
while($MODEL_NAME = mysqli_fetch_assoc(pull_model_name_results)){
	$MODEL_NAME_LIST[] = $MODEL_NAME;
}

<tr>
	<td class="item_label">Model Name</td>
	<td>
		<datalist id = 'model_name'>
			<?php
				foreach($MODEL_NAME_LIST as $var) {
			?>
			<option value= '<?php echo $var;?>' <?php if ($_GET['model_name'] == $var) { print 'selected="true"';}else if($_POST['model_name'] == $var){print 'selected="true"'} ?> ><?php echo $var;?></option>
			<?php
				}
			?>
		</datalist>
	</td>
</tr>


<tr>
	<td class="item_label">Model year</td>
	<td>
		<datalist id="model_year">
			<option value=0 selected="true">Please select</option>
			<?php
				for($n_year=2020; $n_year>=1900; $n_year--) {
			?>
			<option value= '<?php echo $n_year;?>'><?php echo $n_year;?></option>
			<?php
				}
			?>
		</datalist>
	</td>
 </tr>





