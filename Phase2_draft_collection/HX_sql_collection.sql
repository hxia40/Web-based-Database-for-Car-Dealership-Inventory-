--View Inventory Age Report

WITH tbl AS(
SELECT 
	Vehicle.type_name, 
	AVG(DATE_PART('DAY', CURRENT_DATE - Buy.purchase_date)) AS avg_inventory_age,
	MAX(DATE_PART('DAY', CURRENT_DATE - Buy.purchase_date)) AS max_inventory_age,
	MIN(DATE_PART('DAY', CURRENT_DATE - Buy.purchase_date)) AS min_inventory_age
FROM Vehicle
LEFT OUTER JOIN Buy
ON Vehicle.vin = Buy.vin
RIGHT JOIN VehicleType
ON Vehicle.type_name = VehicleType.type_name
WHERE Vehicle.vin NOT IN (SELECT Sell.vin FROM Sell)
GROUP BY Vehicle.type_name
)


SELECT VehicleType.type_name, avg_inventory_age, max_inventory_age, min_inventory_age
FROM VehicleType
LEFT JOIN tbl
ON VehicleType.type_name = tbl.type_name
ORDER BY tbl.type_name
;

SELECT Vehicle_Type,
  COALESCE(ROUND(AVG(
	CASE 
	WHEN Vehicle_Condition = 'Excellent' 
	THEN ROUND(Purchase_price,0) 
	ELSE null
	END
  ),2),0.00) As Excellent,
  COALESCE(ROUND(AVG(
  	CASE
    WHEN Vehicle_Condition = 'Very Good'
    THEN Purchase_price
    ELSE null
    END
  ),2),0.00) As very_good,
  COALESCE(ROUND(AVG(
  	CASE
    WHEN Vehicle_Condition = 'Good'
    THEN Purchase_price
    ELSE null
   END
  ),2),0.00) As good,
  COALESCE(ROUND(AVG(
  	CASE
    WHEN Vehicle_Condition = 'Fair'
    THEN Purchase_price
    ELSE null
   	END
  ),2),0.00) As fair
FROM Vehicle_Sales_Table
GROUP BY Vehicle_Type
ORDER BY Vehicle_Type;

--View Repair Statistics Report
SELECT 
	Repair.vendor_name, 
	COUNT(Repair.vendor_name) AS num_of_repairs, 
	SUM(Repair.repair_cost) AS total_repair_cost, 
	COUNT(Repair.vin)/COUNT(Repair.vendor_name) AS avg_repair_per_vehicle,
	AVG(DAY(Repair.end_date) - DAY(Repair.start_date)) AS avg_time_per_repair
FROM Repair
WHERE Repair.repair_status = 'complete'
GROUP BY Repair.vendor_name
ORDER BY Repair.vendor_name;


--Monthly Report
--Yearly sale summary page	
SELECT 
	COUNT(Sell.vin) AS Num_of_vehicle_sold,
	SUM(Vehicle.sale_price) AS total_sale_income,
	(SUM(Vehicle.sale_price)- SUM(Buy.purchase_price) - SUM(Repair.repair_cost)) AS net_income,
	LEFT(Sell.sale_date::text, 4) AS Sale_year
FROM Sell
JOIN Buy
ON Sell.vin = Buy.vin
JOIN Repair
ON Sell.vin = Repair.vin
JOIN Vehicle
ON Sell.vin = Vehicle.vin
GROUP BY Sale_year
ORDER BY Sale_year DESC; 

							     
--Year sale drill down report
--//upon: user clicks a given $Sale_year from the yearly sale summary page							     
SELECT 
	MAX(Users.login_first_name) AS top_seller_first_name,
	MAX(Users.login_last_name) AS top_seller_last_name,
	COUNT(Sell.vin) AS num_vehicle_sold,	
	SUM(Vehicle.sale_price) AS total_sales
FROM Sell
JOIN Vehicle
ON Vehicle.vin = Sell.vin
JOIN Salesperson
ON Sell.salesperson_permission = Salesperson.salesperson_permission
JOIN Users
ON Salesperson.username = Users.username
WHERE LEFT(Sell.sale_date::text, 4) = '$Sale_year'
GROUP BY Salesperson.username
ORDER BY 
num_vehicle_sold DESC,
total_sales DESC
LIMIT 1;   
		      
--Monthly sale summary page		      
SELECT 
	COUNT(Sell.vin) AS Num_of_vehicle_sold,
	SUM(Vehicle.sale_price) AS total_sale_income,
	(SUM(Vehicle.sale_price)- SUM(Buy.purchase_price) - SUM(Repair.repair_cost)) AS net_income,
	LEFT(Sell.sale_date::text, 7) AS Sale_month
FROM Sell
JOIN Buy
ON Sell.vin = Buy.vin
JOIN Repair
ON Sell.vin = Repair.vin
JOIN Vehicle
ON Sell.vin = Vehicle.vin
GROUP BY Sale_month
ORDER BY Sale_month DESC; 
							     

							     
--Monthly sale drill down report
--//upon: user clicks a given $Sale_month from the monthly sale summary page
SELECT 
	MAX(Users.login_first_name) AS top_seller_first_name,
	MAX(Users.login_last_name) AS top_seller_last_name,
	COUNT(Sell.vin) AS num_vehicle_sold,	
	SUM(Vehicle.sale_price) AS total_sales
FROM Sell
JOIN Vehicle
ON Vehicle.vin = Sell.vin
JOIN Salesperson
ON Sell.salesperson_permission = Salesperson.salesperson_permission
JOIN Users
ON Salesperson.username = Users.username
WHERE LEFT(Sell.sale_date::text, 7) = '$Sale_month'
GROUP BY Salesperson.username
ORDER BY 
num_vehicle_sold DESC,
total_sales DESC
LIMIT 1;
