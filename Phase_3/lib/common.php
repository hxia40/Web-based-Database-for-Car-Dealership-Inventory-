<?php
if (!isset($_SESSION)) {
    session_start();
}

/*
All team members are expected to code for p3 submission. 
It is recommended that team members code separate files and then merge them together via pull requests.  
For this to work the core /lib source files need to be agreed up in the beginning of the collaboration.  This will simplify development and avoid excessive merge conflicts in GitHub.   
Please use the provided phpMyAdmin/pgAdmin username and password below for all queries and place this db connection in one php file then call that file on each dependent source file using include('lib/common.php' or db.php);

// NotePad++ Fix Source Formatting:  Ctrl+Alt+Shift+B
// PHPStorm Fix Source Formatting: Ctrl+Alt+L
Case sensitive table names: 
# The MySQL server add the following line to my.ini 
[mysqld]
lower_case_table_names = 2

Note: after making changes to source code locally, you will likely need to restrate the Binatmio apache server to see the changes. 
*/

// Allow back button without reposting data
header("Cache-Control: private, no-cache, no-store, proxy-revalidate, no-transform");
//session_cache_limiter("private_no_expire");
date_default_timezone_set('America/New_York');

$error_msg = [];
$query_msg = [];
$showQueries = true; 
$showCounts = false; 
$dumpResults = false;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')           
    define("SEPARATOR", "\\");
else 
    define("SEPARATOR", "/");

//show cause of HTTP : 500 Internal Server Error
error_reporting(E_ALL);
ini_set('display_errors', 'off');
ini_set("log_errors", 'on');
ini_set("error_log", getcwd() . SEPARATOR ."error.log");

define('NEWLINE',  '<br>' );
define('REFRESH_TIME', 'Refresh: 1; ');

$encodedStr = basename($_SERVER['REQUEST_URI']); 
//convert '%40' to '@'  example: request_friend.php?friendemail=pam@dundermifflin.com
$current_filename = urldecode($encodedStr);
	
if($showQueries){
    array_push($query_msg, "<b>Current filename: ". $current_filename . "</b>"); 
}

define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_USER', "gatechUser");
define('DB_PASS', "gatech123");
define('DB_SCHEMA', "cs6400_sm19_team22");

define('MANUFACTURER_LIST', array(
    "Acura", "Alfa Romeo", "Aston Martin", "Audi",
    "Bentley", "BMW", "Buick", "Cadillac",
    "Chevrolet", "Chrysler", "Dodge", "Ferrari",
    "FIAT", "Ford", "Freightliner", "Genesis",
    "GMC", "Honda", "Hyundai", "INFINITI",
    "Jaguar", "Jeep", "Kia", "Lamborghini",
    "Land Rover", "Lexus", "Lincoln", "Lotus",
    "Maserati", "MAZDA", "McLaren", "Mercedes-Benz",
    "MINI", "Mitsubishi", "Nissan", "Porsche",
    "Ram", "Rolls-Royce", "smart", "Subura",
    "Tesla", "Toyota", "Volkswagen", "Volvo"
));

define('COLORS_LIST', array(
    "Aluminum", "Beige", "Black", "Blue","Brown", "Bronze", "Claret",
    "Copper", "Cream", "Gold", "Gray", "Green", "Maroon", "Metallic",
    "Navy", "Orange", "Pink", "Purple", "Red", "Rose", "Rust",
    "Silver", "Tan", "Turquoise", "White", "Yellow"
))

define("VEHICLE_TYPES_LIST", array(
    "Sedan",
    "Coupe",
    "Convertible",
    "Truck",
    "Van",
    "Minivan",
    "SUV"
))

$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_SCHEMA, DB_PORT);

if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error() . NEWLINE;
    echo "Running on: ". DB_HOST . ":". DB_PORT . '<br>' . "Username: " . DB_USER . '<br>' . "Password: " . DB_PASS . '<br>' ."Database: " . DB_SCHEMA;
    phpinfo();   //unsafe, but verbose for learning. 
    exit();
}

?>
