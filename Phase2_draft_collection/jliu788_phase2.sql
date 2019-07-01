SELECT COUNT(vin) 
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND repair_status != 'pending' AND repair_status != 'In progress';

SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND repair_status != 'pending' AND repair_status != 'In progress'
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC;

SELECT password FROM Users WHERE Users.username = '$username';

// show number of vehicles with repairs pending
SELECT Count(vin) FROM Repair WHERE repair_status ='pending';
// show number of vehicles with repairs in progress
SELECT Count(vin) FROM Repair WHERE repair_status ='In progress';
// show number of vehicles available for purchase
SELECT COUNT(vin) 
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND repair_status != 'pending' AND repair_status != 'In progress';

SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND vin='$entered_VIN'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC;

SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND repair_status != 'pending' AND repair_status != 'In progress'
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND vin='$entered_VIN'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC;

// show number of vehicles with repairs pending
SELECT Count(vin) FROM Repair WHERE repair_status ='pending';
// show number of vehicles with repairs in progress
SELECT Count(vin) FROM Repair WHERE repair_status ='In progress';
// show number of vehicles available for purchase
SELECT COUNT(vin) 
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND repair_status != 'pending' AND repair_status != 'In progress';

SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND vin='$entered_VIN'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC

SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
WHERE vin IN (SELECT vin FROM Sell)
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND vin='$entered_VIN'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC;


SELECT vin, type_name, model_year, manufacturer_name, vehicle_color, vehicle_mileage, sale_price
FROM Vehicle LEFT JOIN Repair ON Vehicle.vin=Repair.vin
LEFT JOIN VehicleColor ON VehicleColor.vin=Vehicle.vin
WHERE vin NOT IN (SELECT vin FROM Sell)
AND
(
type_name='$entered_type_name'
AND manufacturer_name='$entered_manufacturer_name'
AND model_year='$entered_model_year'
AND vehicle_color='$entered_vehicle_color'
AND vin='$entered_VIN'
AND
(
manufacturer_name LIKE '%$keyword%'
OR model_year LIKE '%$keyword%'
OR model_name LIKE '%$keyword%'
OR vehicle_description LIKE '%$keyword%'
)
)
ORDER BY vin ASC;
