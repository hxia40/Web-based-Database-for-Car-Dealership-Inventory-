DROP TABLE IF EXISTS Users, Manager, InventoryClerk, Salesperson, Customer, Person, Business, VehicleType, Manufacturer, Vehicle, VehicleColor, Recall, Vendor, Repair, Buy, Sell;

-- tables for users
CREATE TABLE Users (
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  login_first_name varchar(50) NOT NULL,
  login_last_name varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE Manager (
  username varchar(50) NOT NULL,
  manager_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (manager_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

CREATE TABLE InventoryClerk (
  username varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (inventory_clerk_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

CREATE TABLE Salesperson (
  username varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  UNIQUE (salesperson_permission),
  FOREIGN KEY (username)
    REFERENCES Users (username)
);

-- tables for customer

CREATE TABLE Customer (
  customer_id varchar(50) NOT NULL,
  phone_number varchar(50) NOT NULL,
  email varchar(50) NULL,
  customer_street varchar(50) NOT NULL,
  customer_city varchar(50) NOT NULL,
  customer_state varchar(50) NOT NULL,
  customer_zip int NOT NULL,
  PRIMARY KEY (customer_id)
);

CREATE TABLE Person (
  customer_id varchar(50) NOT NULL,
  driver_license_number varchar(50) NOT NULL,
  customer_first_name varchar(50) NOT NULL,
  customer_last_name varchar(50) NOT NULL,
  PRIMARY KEY (driver_license_number),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

CREATE TABLE Business (
  customer_id varchar(50) NOT NULL,
  tax_identification_number varchar(50) NOT NULL,
  business_name varchar(50) NOT NULL,
  primary_contact_name varchar(50) NOT NULL,
  primary_contact_title varchar(50) NOT NULL,
  PRIMARY KEY (tax_identification_number),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

-- vehicle and repair table
CREATE TABLE VehicleType (
  type_name varchar(50) NOT NULL,
  PRIMARY KEY (type_name)
);

CREATE TABLE Manufacturer (
  manufacturer_name varchar(50) NOT NULL,
  PRIMARY KEY (manufacturer_name)
);

CREATE TABLE Vehicle (
  vin varchar(50) NOT NULL,
  vehicle_mileage int NOT NULL,
  vehicle_description varchar(250) NULL,
  model_name varchar(50) NOT NULL,
  model_year int NOT NULL,
  type_name varchar(50) NOT NULL,
  manufacturer_name varchar(50) NOT NULL,
  PRIMARY KEY (vin),
  FOREIGN KEY (type_name)
    REFERENCES VehicleType (type_name),
  FOREIGN KEY (manufacturer_name)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE VehicleColor (
  vin varchar(50) NOT NULL,
  vehicle_color varchar(50) NOT NULL,
  PRIMARY KEY (vin, vehicle_color),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin)
);

CREATE TABLE Recall (
  recall_manufacturer varchar(50) NOT NULL,
  recall_description varchar(250) NULL,
  nhtsa_recall_compaign_number varchar(50) NULL,
  PRIMARY KEY (nhtsa_recall_compaign_number),
  FOREIGN KEY (recall_manufacturer)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE Vendor (
  vendor_name varchar(50) NOT NULL,
  vendor_phone_number varchar(50) NOT NULL,
  vendor_street varchar(50) NOT NULL,
  vendor_city varchar(50) NOT NULL,
  vendor_state varchar(50) NOT NULL,
  vendor_zip int NOT NULL,
  PRIMARY KEY (vendor_name)
);

CREATE TABLE Repair (
  vin varchar(50) NOT NULL,
  start_date timestamp NOT NULL,
  end_date timestamp NOT NULL,
  repair_status varchar(50) NOT NULL,
  repair_description varchar(250) NULL,
  vendor_name varchar(50) NOT NULL,
  repair_cost decimal NOT NULL,
  nhtsa_recall_compaign_number varchar(50) NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  PRIMARY KEY (vin),
  UNIQUE (vin, start_date),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (vendor_name)
    REFERENCES Vendor (vendor_name),
  FOREIGN KEY (nhtsa_recall_compaign_number)
    REFERENCES Recall (nhtsa_recall_compaign_number)
);

-- buy and sell transactions
CREATE TABLE Buy (
  vin varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  purchase_date timestamp NOT NULL,
  purchase_price decimal NOT NULL,
  purchase_condition varchar(50) NOT NULL,
  KBB_value decimal NOT NULL,
  UNIQUE (vin, inventory_clerk_permission, customer_id),
  PRIMARY KEY (vin),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (inventory_clerk_permission)
    REFERENCES InventoryClerk (inventory_clerk_permission),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

CREATE TABLE Sell (
  vin varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  sale_date timestamp NOT NULL,
  sale_price decimal NOT NULL,
  UNIQUE (vin, salesperson_permission, customer_id),
  PRIMARY KEY (vin),
  FOREIGN KEY (vin)
    REFERENCES Vehicle (vin),
  FOREIGN KEY (salesperson_permission)
    REFERENCES Salesperson (salesperson_permission),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);


INSERT INTO VehicleType(type_name)
VALUES ('SUV');

INSERT INTO VehicleType(type_name)
VALUES ('Sedan');

INSERT INTO VehicleType(type_name)
VALUES ('Bus');

INSERT INTO Manufacturer(manufacturer_name)
VALUES ('JEEP CO.');

INSERT INTO Manufacturer(manufacturer_name)
VALUES ('Geely CO.');

INSERT INTO Manufacturer(manufacturer_name)
VALUES ('BYD CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (1111111, 150000, 'first car', 'Cherokee', 1999, 'SUV', 'JEEP CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (2222222, 180000, 'second car', 'BORUI GC-9', 2015, 'Sedan', 'Geely CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (3333333, 200000, 'third car', 'BYD K9', 2018, 'Bus', 'BYD CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (4444444, 210000, 'fourth car', 'Grand Cherokee', 2000, 'SUV', 'JEEP CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (5555555, 220000, 'fifth car', 'BORUI GC-10', 2016, 'Sedan', 'Geely CO.');

INSERT INTO Vehicle(vin, vehicle_mileage, vehicle_description, model_name, model_year, type_name, manufacturer_name)
VALUES (6666666, 230000, 'sixth car', 'BYD K10', 2019, 'Bus', 'BYD CO.');

INSERT INTO Customer(customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip)
VALUES ('Rich101', '3181112222', 'hsr@gmail.com', '333 Gold Blvd.', 'Houston', 'TX', 77202);

INSERT INTO Customer(customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip)
VALUES ('Rich102', '3183334444', 'lwc@gmail.com', '8888 Goodman Rd.', 'Houston', 'TX', 77205);

INSERT INTO Customer(customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip)
VALUES ('Poor101', '3185556666', 'ybl@gmail.com', '444 Crap St.', 'Houston', 'TX', 77208);

INSERT INTO Customer(customer_id, phone_number, email, customer_street, customer_city, customer_state, customer_zip)
VALUES ('Poor102', '3187778888', 'gyb@gmail.com', '9999 Main St.', 'Houston', 'TX', 77209);

INSERT INTO Users(username, password, login_first_name, login_last_name)
VALUES ('sales1','123456','Tianming', 'Yun');

INSERT INTO Users(username, password, login_first_name, login_last_name)
VALUES ('sales2','123456','Yifan', 'Guan');

INSERT INTO Salesperson(username, salesperson_permission)
VALUES ('sales1','salesperson_permission1');

INSERT INTO Salesperson(username, salesperson_permission)
VALUES ('sales2','salesperson_permission2');

INSERT INTO Users(username, password, login_first_name, login_last_name)
VALUES ('clerk1','123456','Xin', 'Cheng');

INSERT INTO Users(username, password, login_first_name, login_last_name)
VALUES ('clerk2','123456','AA', 'Ai');

INSERT INTO InventoryClerk(username, inventory_clerk_permission)
VALUES ('clerk1','inventory_clerk_permission1');

INSERT INTO InventoryClerk(username, inventory_clerk_permission)
VALUES ('clerk2','inventory_clerk_permission2');

INSERT INTO Sell(vin, sale_date, sale_price, salesperson_permission, customer_id)
VALUES ('1111111', '2019-01-01 00:00:01',10000,'salesperson_permission2','Poor101');

INSERT INTO Sell(vin, sale_date, sale_price, salesperson_permission, customer_id)
VALUES ('2222222', '2019-01-09 00:00:01',8000,'salesperson_permission1','Poor102');

INSERT INTO Sell(vin, sale_date, sale_price, salesperson_permission, customer_id)
VALUES ('3333333', '2019-02-01 00:00:01',12000,'salesperson_permission2','Poor101');

INSERT INTO Buy(vin, purchase_date, purchase_price, purchase_condition, kbb_value, inventory_clerk_permission, customer_id)
VALUES ('1111111', '2018-01-01 00:00:01',5000,'Excellent',5000,'inventory_clerk_permission1','Rich101');

INSERT INTO Buy(vin, purchase_date, purchase_price, purchase_condition, kbb_value, inventory_clerk_permission, customer_id)
VALUES ('2222222', '2018-01-09 00:00:01',4000,'Very Good',4000,'inventory_clerk_permission2','Rich101');

INSERT INTO Buy(vin, purchase_date, purchase_price, purchase_condition, kbb_value, inventory_clerk_permission, customer_id)
VALUES ('3333333', '2018-02-01 00:00:01',6000,'Fair',6000,'inventory_clerk_permission1','Rich102');

INSERT INTO Vendor(vendor_name, vendor_phone_number, vendor_street, vendor_city, vendor_state, vendor_zip)
VALUES ('Wangermazi big repair CO.', '3187778888','222 Big St.', 'Houston', 'TX', 77210);

INSERT INTO Vendor(vendor_name, vendor_phone_number, vendor_street, vendor_city, vendor_state, vendor_zip)
VALUES ('Zhangsanmazi big repair CO.', '3189990000','333 Big St.', 'Houston', 'TX', 77211);

INSERT INTO Vendor(vendor_name, vendor_phone_number, vendor_street, vendor_city, vendor_state, vendor_zip)
VALUES ('Lisimazi big repair CO.', '3180001111','444 Big St.', 'Houston', 'TX', 77212);

INSERT INTO Repair(vin, start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, inventory_clerk_permission)
VALUES (1111111,  '2018-03-09 00:00:01', '2018-03-10 00:00:01', 'complete', 'repair_description1', 500, 'Lisimazi big repair CO.', 'inventory_clerk_permission2');

INSERT INTO Repair(vin, start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, inventory_clerk_permission)
VALUES (2222222,  '2018-04-09 00:00:01', '2018-04-10 00:00:01', 'complete', 'repair_description2', 800, 'Wangermazi big repair CO.', 'inventory_clerk_permission1');

INSERT INTO Repair(vin, start_date, end_date, repair_status, repair_description, repair_cost, vendor_name, inventory_clerk_permission)
VALUES (3333333,  '2018-04-11 00:00:01', '2018-04-12 00:00:01', 'complete', 'repair_description3', 1000, 'Zhangsanmazi big repair CO.', 'inventory_clerk_permission1');

INSERT INTO Recall(nhtsa_recall_compaign_number, recall_description, recall_manufacturer)
VALUES ('99887766', 'Jipuche big recall', 'JEEP CO.');





