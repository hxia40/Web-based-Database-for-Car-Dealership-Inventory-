<?php
include('lib/common.php');
// written by czhang613

// setup permission and login info
if ($_SESSION['permission'] != 2) {
	header('Location: public_search.php');
	exit();
}
$enteredVIN = $_GET['vin'];
$enteredcustomer_id = 'Rich101';
?>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $enteredsalesperson_permission = mysqli_real_escape_string($db, $_POST['salesperson_permission']);
        $enteredsale_date = mysqli_real_escape_string($db, $_POST['sale_date']);


            $query = "INSERT INTO Sell (vin, customer_id, salesperson_permission, sale_date)"
                    ."VALUES('$enteredVIN', '$enteredcustomer_id', '$enteredsalesperson_permission', '$enteredsale_date')";
            $result = mysqli_query($db, $query);
            include('lib/show_queries.php');
            if (mysqli_affected_rows($db) == -1) {
                array_push($error_msg, "ADD ERROR: Customer Table form  <br>".  __FILE__ ." line:". __LINE__ );
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
					<div class="features">

            <div class="Sale Order section">
							<div class="subtitle">Sale Order Form</div>

                            <form name = "sale_order" action = "sale_order.php" method="post">
                                <table>
                                    <tr>
                                        <td class ="item_label">Salesperson Permission</td>
                                        <td>
                                            <input type="text" name = "salesperson_permission" value="<?php if ($_POST['salesperson_permission']) { print $_POST['salesperson_permission']; } ?>" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class ="item_label">Salesperson Permission</td>
                                        <td>
                                            <?php
                                            echo $enteredVIN;
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="item_label">Sale Date</td>
                                        <td>
                                            <input name = "sale_date" type = "datetime-local" id = "sale_date" value="<?php if ($_POST['sale_date']) { print $_POST['sale_date']; } ?>" >
                                        </td>
                                    </tr>




                                    <tr>
                                        <input name = "add" type = "submit" id = "add" value = "Confirm Sale! ">
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
