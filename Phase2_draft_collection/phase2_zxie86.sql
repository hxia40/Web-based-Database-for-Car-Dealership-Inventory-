/*
Add a new record into the repair table
*/
INSERT INTO `Repair`
VALUES(
'$enteredVIN', '$enteredRepair_Vendor_Name', '$enteredRepair_Vendor_Address', 
'$enteredRepair_Vendor_Phone_Number', '$enteredRepair_Description',  '$enteredRepair_Start_Date', 
'$enteredRepair_Cost', '$enteredNHTSA_Recall_Campagin_Number', '$enteredRepair_Status'
);


/*
Dsisplay the repair table based on the entered VIN
*/
SELECT VIN, Repair_Vendor_Name, Repair_Vendor_Address, Repair_Vendor_Phone_Number,
Repair_Description, Repair_Start_Date, Repair_Cost, NHTSA_Recall_Campagin_Number 
FROM `Repair` WHERE Repair.VIN = '$enteredVIN'


/*
Update the repair table based on the entered VIN
*/
UPDATE `Repair`
SET VIN = '$enteredVIN', 
         Repair_Vendor_Name = '$enteredRepair_Vendor_Name', 
         Repair_Vendor_Address = 'enteredRepair_Vendor_Address', 
         Repair_Vendor_Phone_Number  = 'enteredRepair_Vendor_Phone_Number', 
         Repair_Description  = 'enteredRepair_Description',  
         Repair_Start_Date  = 'enteredRepair_Start_Date',
         Repair_Cost  = 'enteredRepair_Cost', 
         NHTSA_Recall_Campagin_Number  = 'enteredNHTSA_Recall_Campagin_Number', 
         Repair_Status = 'enteredRepair_Status';

/*
Delete a record from the repair table
*/
DELETE FROM `Repair` WHERE Repair.VIN = '$enteredVIN' AND Repair.Start_Date = '$enteredStart_Date';



/*
Add a new record into the Vehicle table
*/
INSERT INTO `Vehicle`
VALUES(
'$enteredVIN', '$enteredVehicle_Type', '$enteredVehicle_Manufacturer', '$enteredModel_Name',
'$enteredModel_Year',  '$enteredVehicle_Color', '$enteredMileage', '$enteredSales_Price',
'$enteredVehicle_Description'
);

/*
Delete a record from the repair table
*/
DELETE FROM `Vehicle` WHERE Vehicle.VIN = '$enteredVIN';


/*
Dsisplay the Vehicle table based on the entered VIN
*/
SELECT 
VIN, Vehicle_Type, Vehicle_Manufacturer,
Model_Name, Model_Year, Color, Mileage, Vehicle_Description 
FROM ` Vehicle` WHERE Vehicle.VIN = '$enteredVIN';

/*
Update the Vehicle table based on the entered VIN
*/
UPDATE `Vehicle`
SET   VIN = '$enteredVIN', 
      Vehicle_Type = '$enteredVehicle_Type ', 
      Vehicle_Manufacturer = 'enteredVehicle_Manufacturer', 
      Model_Name = 'enteredModel_Name', 
      Model_Year  = 'enteredModel_Year',  
      Color  = 'enteredColor',
      Mileage = 'enteredMileage', 
      Vehicle_Description = 'enteredVehicle_Description';

/*
Add a record to Recall Table
*/
INSERT INTO `Recall`
   VALUES(
   '$enteredNHTSA_Recall_Campagin_Number', 
   '$enteredRecall_Description', '$enteredRecall_Manufacture', 
);

/*
Delete a record in the Recall Table based on the entered NHTSA_Recall_Campagin_Number
*/
DELETE FROM `Recall`
WHERE Recall.NHTSA_Recall_Campagin_Number = '$enteredNHTSA_Recall_Campagin_Number'
AND Recall.NHTSA_Recall_Campagin_Number NOT IN (
   SELECT DISTINCT(NHTSA_Recall_Campagin_Number) 
   FROM Repair
   WHERE Repair.NHTSA_Recall_Campagin_Number = '$enteredNHTSA_Recall_Campagin_Number'
);

/*
Display the Recall table based on entered NHTSA_Recall_Campagin_Number
*/
SELECT NHTSA_Recall_Campagin_Number, Recall_Description, Recall_Manufacture
FROM `Recall` 
WHERE Recall. NHTSA_Recall_Campagin_Number = '$enteredNHTSA_Recall_Campagin_Number'


/*
Update the Recall Table based on the entered NHTSA_Recall_Campagin_Number
*/
UPDATE `Recall`
SET NHTSA_Recall_Campagin_Number = '$enteredNHTSA_Recall_Campagin_Number',
    Recall_Description = '$enteredRecall_Description ', 
    Recall_Manufacture = 'entered Recall_Manufacture';

/*Get Average Time In Inventory*/
SELECT Vehicle.Vehicle_Type AS Vehicle_Type, AVE(tbl.DateDiff) AS Average_Time_In_Inventory
FROM (
   SELECT sell.VIN AS VIN, DATEDIFF(day,buy.Purchase_date, sell.Sale_date) AS DateDiff
   FROM `Sell_transaction` AS sell
   LEFT JOIN Buy_transaction AS buy
   ON sell.VIN = buy.VIN
   WHERE buy.Purchase_date IS NOT NULL AND sell.Sale_date IS NOT NULL;
)tbl
LEFT JOIN `Vehicle`
ON Vehicle.VIN = tbl.VIN
GROUP BY Vehicle. Vehicle_Type
ORDER BY Vehicle. Vehicle_Type;