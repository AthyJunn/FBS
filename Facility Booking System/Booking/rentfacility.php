<?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "facility_booking";

// Create connection with error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");
    
    // Test the connection
    if ($conn->ping()) {
        // Connection is working
        error_log("Database connection successful");
    }
} catch (Exception $e) {
    // Log the error
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

/**
 * Check if a facility is available at the specified date and time
 */
function isFacilityAvailable($facilityID, $date, $timeSlot) {
    global $conn;
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM rentals 
                           WHERE facilityID = ? AND date = ? AND timeSlot = ?");
    $stmt->bind_param("iss", $facilityID, $date, $timeSlot);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    
    // Return true if no bookings exist for that time slot
    return $row['count'] == 0;
}

/**
 * Book a facility for a customer
 */
function bookFacility($bookingID, $customerID, $facilityID, $date, $timeSlot) {
    global $conn;
    
    // Validate inputs
    if (empty($bookingID) || empty($customerID) || empty($facilityID) || 
        empty($date) || empty($timeSlot)) {
        return false;
    }
    
    // Check if customer exists
    $stmt = $conn->prepare("SELECT customerID FROM customers WHERE customerID = ?");
    $stmt->bind_param("s", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt->close();
        return false;
    }
    $stmt->close();
    
    // Check if facility exists
    $stmt = $conn->prepare("SELECT facilityID FROM facilities WHERE facilityID = ?");
    $stmt->bind_param("s", $facilityID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt->close();
        return false;
    }
    $stmt->close();
    
    // Insert the booking
    $stmt = $conn->prepare("INSERT INTO rentals (bookingID, customerID, facilityID, date, timeSlot) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $bookingID, $customerID, $facilityID, $date, $timeSlot);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

/**
 * Get facility details
 */
function getFacilityDetails($facilityID) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM facilities WHERE facilityID = ?");
    $stmt->bind_param("s", $facilityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $facility = $result->fetch_assoc();
    $stmt->close();
    
    return $facility;
}

/**
 * Get customer details
 */
function getCustomerDetails($customerID) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customerID = ?");
    $stmt->bind_param("s", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
    $stmt->close();
    
    return $customer;
}

/**
 * Validate date format
 */
function isValidDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validate time slot format
 */
function isValidTimeSlot($timeSlot) {
    return preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", $timeSlot);
}

// Close the database connection when the script ends
register_shutdown_function(function() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
});
?>
