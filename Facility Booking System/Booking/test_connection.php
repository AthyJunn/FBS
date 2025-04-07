<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facility_booking";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Database connection successful!<br>";
    echo "Server info: " . $conn->server_info . "<br>";
    echo "Host info: " . $conn->host_info . "<br>";
    
    // Test query
    $result = $conn->query("SHOW TABLES");
    echo "<br>Tables in database:<br>";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?> 