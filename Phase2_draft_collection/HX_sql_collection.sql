--View seller history report


CREATE TEMPORARY TABLE aaa
SELECT 
Buy.vin, 
COUNT(repair.vin) as number_of_repair_for_this_vehicle
FROM Buy 
LEFT OUTER JOIN Repair
ON Buy.vin = Repair.vin
GROUP BY Buy.vin;

SELECT 
Customer.customer_id, 
COUNT( Buy .vin) AS total_vehicle_number_sold_to_us,
ROUND(AVG(aaa.number_of_repair_for_this_vehicle),1) AS repairs_per_vehicle,

ROUND(AVG(Buy.purchase_price),2) AS avg_purchase_price
FROM Buy 
LEFT OUTER JOIN Repair
ON Buy.vin = Repair.vin
LEFT OUTER JOIN aaa
ON Buy.vin = aaa.vin
JOIN Customer
ON Buy.customer_id = Customer.customer_id
GROUP BY Customer.customer_id;

--JOIN (SELECT CONCAT(ISNULL(Person.last_name, ''), ISNULL(Business.business_name, '')) AS name,


--View Average Time in Inventory Report

SELECT vehicletype.type_name AS AAA, IFNULL(otbl.average_time_in_inventory, 'N/A') AS BBB
FROM vehicletype
LEFT JOIN (
    SELECT Vehicle.type_name AS type_name, 
	ROUND(AVG(tbl.dateDiff),1) AS average_time_in_inventory 
	FROM (SELECT Sell.vin AS vin, DATEDIFF(Sell.sale_date, Buy.purchase_date) AS dateDiff 
      FROM Sell LEFT JOIN Buy ON Sell.vin = Buy.vin 
      WHERE Buy.purchase_date IS NOT NULL AND Sell.sale_date IS NOT NULL)tbl 
	LEFT JOIN Vehicle ON Vehicle.vin = tbl.vin 
	GROUP BY Vehicle.type_name 
	ORDER BY Vehicle.type_name
    )otbl
ON vehicletype.type_name = otbl.type_name


--View Inventory Age Report

CREATE TEMPORARY TABLE tbl
SELECT
Vehicle .type_name,
ROUND(AVG(DATEDIFF(CURRENT_DATE, Buy.purchase_date)),1) AS avg_inventory_age,
MAX(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS max_inventory_age,
MIN(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS min_inventory_age
FROM Vehicle
LEFT OUTER JOIN Buy
ON Vehicle.vin = Buy .vin
RIGHT JOIN VehicleType
ON Vehicle .type_name = VehicleType .type_name
WHERE Vehicle .vin NOT IN (SELECT Sell .vin FROM Sell)
GROUP BY Vehicle .type_name;

SELECT VehicleType .type_name, avg_inventory_age, max_inventory_age, min_inventory_age
FROM VehicleType
LEFT JOIN tbl
ON VehicleType .type_name = tbl.type_name
ORDER BY tbl.type_name;

============
--View Inventory Age Report, with derived table
SELECT VehicleType.type_name, IFNULL(avg_inventory_age,'N/A') AS avginventoryage, 
IFNULL(max_inventory_age, 'N/A') AS maxinventoryage, 
IFNULL(min_inventory_age, 'N/A') AS mininventoryage 
FROM VehicleType LEFT JOIN ( 
    SELECT Vehicle.type_name, 
    ROUND(AVG(DATEDIFF(CURRENT_DATE, Buy.purchase_date)),1) AS avg_inventory_age, 
    MAX(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS max_inventory_age, 
    MIN(DATEDIFF(CURRENT_DATE, Buy.purchase_date)) AS min_inventory_age 
    FROM Vehicle LEFT OUTER JOIN Buy ON Vehicle.vin = Buy.vin 
    RIGHT JOIN VehicleType ON Vehicle.type_name = VehicleType .type_name 
    WHERE Vehicle.vin NOT IN (SELECT Sell .vin FROM Sell) 
    GROUP BY Vehicle .type_name)tbl 
ON VehicleType.type_name = tbl.type_name 
ORDER BY tbl.type_name
=============

--View Price per Condition Report

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
