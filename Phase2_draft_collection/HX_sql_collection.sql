--View Price per Condition Report
SELECT 
	Repair.vendor_name, 
	COUNT(Repair.vendor_name) AS num_of_repairs, 
	SUM(Repair.repair_cost) AS total_repair_cost, 
	COUNT(Repair.vin)/COUNT(Repair.vendor_name) AS avg_repair_per_vehicle,
	AVG(DATE_PART('DAY', Repair.end_date - Repair.start_date)) AS avg_time_per_repair
FROM Repair
GROUP BY Repair.vendor_name
ORDER BY Repair.vendor_name;
