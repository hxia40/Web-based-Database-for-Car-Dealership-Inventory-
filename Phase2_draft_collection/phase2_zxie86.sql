


/*
Add a new record into the repair table
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

INSERT INTO `Repair`
VALUES(
'$enteredVin', '$enteredStart_date', '$enteredEnd_date',
'$enteredRepair_status', '$enteredRepair_Description',  '$enteredVendor_name',
'$enteredRepair_cost', '$enteredNHTSA_recall_campagin_Number', '$enteredInventory_clerk_permssion'
);



/*
Display the repair table based on the entered VIN
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/
SELECT vin, start_date, end_date, repair_status, repair_description, vendor_name, repair_cost, nhtsa_recall_compaign_number, inventory_clerk_permission
FROM `Repair` WHERE Repair.VIN = '$enteredVIN'
ORDER BY Repair_Start_Date DESC;


/*
Update the repair table based on the entered VIN
*/
/*first select the columns in the Repair table*/
SELECT vin, start_date, end_date, repair_status, repair_description, vendor_name, repair_cost, nhtsa_recall_compaign_number, inventory_clerk_permission
FROM `Repair` WHERE Repair.VIN = '$enteredVIN'

/*Assume that the inputs are correct and the permission has been validated by the application.*/
UPDATE `Repair`
SET vin = '$enteredVin',
     	start_date = '$enteredStart_date',
     	end_date = '$enteredEnd_date',
     	repair_status  = '$enteredRepair_status',
     	repair_description  = '$enteredRepair_Description', 
     	vendor_name  =  '$enteredVendor_name',
     	repair_cost  = '$enteredRepair_cost',
     	nhtsa_recall_compaign_number  = '$enteredNHTSA_recall_campagin_Number',
     	inventory_clerk_permission = '$enteredInventory_clerk_permssion';


/*
Delete a record from the repair table
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

DELETE FROM `Repair` WHERE Repair.VIN = '$enteredVin' AND Repair.start_date = '$enteredStart_date';

/*----------------------------------------------------------------------------------------------------*/


/*
Add a new record into the Vehicle table
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/
INSERT INTO `Vehicle`
VALUES(
'$enteredVin', '$enteredvehicle_mileage', '$enteredvehicle_description', '$enteredmodel_name',
'$enteredmodel_year',  '$enteredtype_name', '$enteredmanufacturer_name', '$enteredsale_price',
'$enteredVehicle_Description'
);


/*
Delete a record from the repair table
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

DELETE FROM `Vehicle` WHERE Vehicle.vin = '$enteredVin';

/*
Dsisplay the Vehicle table based on the entered VIN
*/
/*
This query will be identical as the query in  View Vehicle Detail Form
Run view vehicle detail form task.
*/


/*
Update the Vehicle table based on the entered VIN
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

SELECT
vin, vehicle_mileage, vehicle_description,
model_name, model_year, type_name, manufacturer_name, sale_price
FROM ` Vehicle` WHERE Vehicle.vin = '$enteredvin';

  /*Assume that the inputs are correct and the permission has been validated by the application.*/

UPDATE `Vehicle`
SET vin = '$enteredvin',
     	vehicle_mileage = '$enteredvehicle_mileage',
     	vehicle_description = '$enteredvehicle_description',
     	model_name = '$enteredmodel_name',
     	model_year  = '$enteredmodel_year', 
     	type_name  = '$enteredtype_name',
     	manufacturer_name = '$enteredmanufacturer_name',
     	sale_price = '$enteredsale_price';

/*----------------------------------------------------------------------------------------------------*/

/*Add Recall Form*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

INSERT INTO `Recall`
VALUES(
'$enteredrecall_manufacturer',
'$enteredrecall_description', 
'$enteredNHTSA_recall_compaign_number'
);

/*
Delete a record in the Recall Table based on the entered NHTSA_Recall_Campagin_Number
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

DELETE FROM `Recall`
WHERE Recall.NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'
AND Recall.NHTSA_recall_compaign_number NOT IN (
SELECT DISTINCT(NHTSA_recall_compaign_number)
FROM Repair
WHERE Repair.NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'
);


/*
Display the Recall table based on entered NHTSA_Recall_Campagin_Number
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/
SELECT recall_manufacturer, recall_description, NHTSA_recall_compaign_number
FROM `Recall`
WHERE Recall. NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'



/*Update Recall Table*/
SELECT recall_manufacturer, recall_description, NHTSA_recall_compaign_number
FROM `Recall`
WHERE Recall. NHTSA_recall_compaign_number = '$enteredNHTSA_recall_compaign_number'
/*If there is no conflict for the NHTSA_Recall_Campagin_Number and assume that the inputs are correct and the permission has been validated by the application*/
UPDATE `Recall`
SET recall_manufacturer = '$enteredrecall_manufacturer',
     	recall_description = '$enteredrecall_description',
     	NHTSA_recall_compaign_number = 'enteredNHTSA_recall_compaign_number'

/*----------------------------------------------------------------------------------------------------*/

/*
Update the Recall Table based on the entered NHTSA_Recall_Campagin_Number
*/
/*Assume that the inputs are correct and the permission has been validated by the application.*/

SELECT Vehicle.type_name AS type_name, AVE(tbl.dateDiff) AS average_time_in_inventory
FROM (
        	SELECT Sell.vin AS vin, DATEDIFF(day, Buy.purchase_date, Sell.sale_date) AS dateDiff
FROM `Sell`
LEFT JOIN `Buy`
ON Sell.vin = Buy.vin
WHERE Buy.purchase_date IS NOT NULL AND Sell.sale_date IS NOT NULL;
)tbl
LEFT JOIN `Vehicle`
ON Vehicle.vin = tbl.vin
GROUP BY Vehicle.type_name
ORDER BY Vehicle.type_name;
