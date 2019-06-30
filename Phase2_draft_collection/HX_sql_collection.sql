--View Price per Condition Report

--View Repair Statistics Report
SELECT 
	Repair.vendor_name, 
	COUNT(Repair.vendor_name) AS num_of_repairs, 
	SUM(Repair.repair_cost) AS total_repair_cost, 
	COUNT(Repair.vin)/COUNT(Repair.vendor_name) AS avg_repair_per_vehicle,
	AVG(DATE_PART('DAY', Repair.end_date - Repair.start_date)) AS avg_time_per_repair
FROM Repair
WHERE Repair.repair_status = 'complete'
GROUP BY Repair.vendor_name
ORDER BY Repair.vendor_name;


--Monthly Report, yearly sale summary page	
SELECT 
	COUNT(Sell.vin) AS Num_of_vehicle_sold,
	SUM(Sell.sale_price) AS total_sale_income,
	(SUM(Sell.sale_price)- SUM(Buy.purchase_price) - SUM(Repair.repair_cost)) AS net_income,
	LEFT(Sell.sale_date::text, 4) AS Sale_year
FROM Sell
JOIN Buy
ON Sell.vin = Buy.vin
JOIN Repair
ON Sell.vin = Repair.vin
GROUP BY Sale_year
ORDER BY Sale_year DESC; 
		      
--Monthly Report, monthly sale summary page		      
SELECT 
	COUNT(Sell.vin) AS Num_of_vehicle_sold,
	SUM(Sell.sale_price) AS total_sale_income,
	(SUM(Sell.sale_price)- SUM(Buy.purchase_price) - SUM(Repair.repair_cost)) AS net_income,
	LEFT(Sell.sale_date::text, 7) AS Sale_month
FROM Sell
JOIN Buy
ON Sell.vin = Buy.vin
JOIN Repair
ON Sell.vin = Repair.vin
GROUP BY Sale_month
ORDER BY Sale_month DESC;
							     
--Monthly Report , month sale drill down report

SELECT 
	Sell.vin,
	Sell.salesperson_permission,
	Sell.sale_price,
	Sell.sale_date,
	Salesperson.username,
	Users.login_first_name,
	Users.login_last_name
FROM Sell
JOIN Salesperson
ON Sell.salesperson_permission = Salesperson.salesperson_permission
JOIN Users
ON Salesperson.username = Users.username
WHERE LEFT(Sell.sale_date::text, 4) = '2019';
