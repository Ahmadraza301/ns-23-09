<?php
$host = 'localhost:3307';     
$dbname = '23-09'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set character set to UTF-8 (optional but recommended)
    $conn->exec('SET NAMES utf8mb4');

    // Additional configuration options can be set here

    // If we reach this point, the connection is successful
    // echo "Database connection successful.";
} catch (PDOException $e) {
    // Handle database connection errors
    die("Database connection failed: " . $e->getMessage());
}


// try{
//     // Connect to the database
//     $conn = mysqli_connect($host, $username, $password, $dbname);
// }
// catch(mysqli_sql_exception){
//     echo "Could not connect!";
// }

?>

