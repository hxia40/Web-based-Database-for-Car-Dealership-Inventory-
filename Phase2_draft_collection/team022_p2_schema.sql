-- tables for users
CREATE TABLE User (
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
  FOREIGN KEY (username)
    REFERENCES User (username)
);

CREATE TABLE InventoryClerk (
  username varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  FOREIGN KEY (username)
    REFERENCES User (username)
);

CREATE TABLE Salesperson (
  username varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  PRIMARY KEY (username),
  FOREIGN KEY (username)
    REFERENCES User (username)
);

-- tables for customer

CREATE TABLE Customer (
  customer_id varchar(50) NOT NULL,
  phone_number int NOT NULL,
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

-- buy and sell transactions
CREATE TABLE Buy (
  VIN varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  purchase_date timestamp NOT NULL,
  purchase_price decimal NOT NULL,
  purchase_condition varchar(50) NOT NULL,
  KBB_value decimal NOT NULL,
  UNIQUE (VIN, inventory_clerk_permission, customer_id)
  PRIMARY KEY (VIN),
  FOREIGN KEY (VIN)
    REFERENCES Vehicle (VIN),
  FOREIGN KEY (inventory_clerk_permission)
    REFERENCES InventoryClerk (inventory_clerk_permission),
  FOREIGN KEY (customer_id)
    REFERENCES Customer (customer_id)
);

CREATE TABLE Sell (
  VIN varchar(50) NOT NULL,
  customer_id varchar(50) NOT NULL,
  salesperson_permission varchar(50) NOT NULL,
  sale_date timestamp NOT NULL,
  sale_price decimal NOT NULL,
  UNIQUE (VIN, salesperson_permission, customer_id)
  PRIMARY KEY (VIN),
  FOREIGN KEY (VIN)
    REFERENCES Vehicle (VIN),
  FOREIGN KEY (salesperson_permission)
    REFERENCES Salesperson (salesperson_permission),
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
  VIN varchar(50) NOT NULL,
  vehicle_mileage int NOT NULL,
  vehicle_description varchar(250) NULL,
  model_name varchar(50) NOT NULL,
  model_year int NOT NULL,
  type_name varchar(50) NOT NULL,
  manufacturer_name varchar(50) NOT NULL,
  PRIMARY KEY (VIN),
  FOREIGN KEY (type_name)
    REFERENCES VehicleType (type_name),
  FOREIGN KEY (manufacturer_name)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE VehicleColor (
  VIN varchar(50) NOT NULL,
  vehicle_color varchar(50) NOT NULL,
  PRIMARY KEY (VIN, vehicle_color),
  FOREIGN KEY (VIN)
    REFERENCES Vehicle (VIN),
);

CREATE TABLE Recall (
  recall_manufacturer varchar(50) NOT NULL,
  recall_description varchar(250) NULL,
  NHTSA_recall_compaign_number varchar(50) NULL,
  PRIMARY KEY (NHTSA_recall_compaign_number),
  FOREIGN KEY (recall_manufacturer)
    REFERENCES Manufacturer (manufacturer_name)
);

CREATE TABLE Repair (
  VIN varchar(50) NOT NULL,
  start_date timestamp NOT NULL,
  end_date timestamp NOT NULL,
  repair_status varchar(50) NOT NULL,
  repair_description varchar(250) NULL,
  vendor_name varchar(50) NOT NULL,
  repair_cost decimal NOT NULL,
  NHTSA_recall_compaign_number varchar(50) NULL,
  inventory_clerk_permission varchar(50) NOT NULL,
  PRIMARY KEY (VIN),
  UNIQUE (VIN, start_date),
  FOREIGN KEY (VIN)
    REFERENCES Vehicle (VIN),
  FOREIGN KEY (vendor_name)
    REFERENCES Vendor (vendor_name),
  FOREIGN KEY (NHTSA_recall_compaign_number)
    REFERENCES Recall (NHTSA_recall_compaign_number)
);
