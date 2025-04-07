<?php
//Connect to database
$con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');

//Check connection
if (!$con) {
    die("Connection Error: " . mysqli_connect_error());
}

//Fetch booking list with optional search filter
function getListOfBooking($searchQuery = "") {
    global $con;
    $sql = "SELECT b.Booking_Ref, b.customerID, b.DateReserved, b.Reserved_By, 
                   b.DateRent_start, b.DateRent_end, b.RentalPeriod, 
                   b.facilityID, b.Amount_Due, b.Paid, b.bookingStatus,
                   f.name as facilityName, c.customerName 
            FROM booking b 
            JOIN facility f ON b.facilityID = f.facilityID 
            JOIN customer c ON b.customerID = c.customerID";

    //Apply search filter if keyword exists
    if (!empty($searchQuery)) {
        $searchQuery = mysqli_real_escape_string($con, $searchQuery);
        $sql .= " WHERE f.name LIKE '%$searchQuery%' 
                  OR c.customerName LIKE '%$searchQuery%'
                  OR b.Booking_Ref LIKE '%$searchQuery%'";
    }

    $sql .= " ORDER BY b.DateRent_start DESC";

    return mysqli_query($con, $sql);
}

// Function to add new booking record
function addNewBookingRecord() {
    global $con;
    
    // Collect data from post array
    $customerId = $_POST['customerID'];
    $facilityId = $_POST['facilityID'];
    $dateReserved = date("Y-m-d"); // Current date
    $reservedBy = $customerId; // Same as customer ID
    $dateRentStart = $_POST['DateRent_start'];
    $dateRentEnd = $_POST['DateRent_end'];
    
    // Calculate rental period
    $start = new DateTime($dateRentStart);
    $end = new DateTime($dateRentEnd);
    $rentalPeriod = $end->diff($start)->days + 1;
    
    // Generate booking reference (CustomerID + FacilityID + Date)
    $bookingRef = $customerId . $facilityId . date('Ymd');
    
    // Get facility rate and calculate amount
    $rateQuery = "SELECT ratePerDay FROM facility WHERE facilityID = ?";
    $stmt = mysqli_prepare($con, $rateQuery);
    mysqli_stmt_bind_param($stmt, "s", $facilityId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $facility = mysqli_fetch_assoc($result);
    $amountDue = $facility['ratePerDay'] * $rentalPeriod;
    
    // Insert booking record
    $sql = "INSERT INTO booking (Booking_Ref, customerID, DateReserved, Reserved_By, 
                                DateRent_start, DateRent_end, RentalPeriod, facilityID, 
                                Amount_Due, Paid, bookingStatus) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $paid = 0; // Default to unpaid
    $status = 'Pending'; // Default status
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssissss", 
        $bookingRef,
        $customerId,
        $dateReserved,
        $reservedBy,
        $dateRentStart,
        $dateRentEnd,
        $rentalPeriod,
        $facilityId,
        $amountDue,
        $paid,
        $status
    );
    
    return mysqli_stmt_execute($stmt);
}

// Function to get future bookings by customer
function getListOfFutureBookingByCustomer($customerId) {
    global $con;
    
    $sql = "SELECT b.*, f.name as facilityName 
            FROM booking b 
            JOIN facility f ON b.facilityID = f.facilityID 
            WHERE b.customerID = ? 
            AND b.DateRent_start >= CURDATE() 
            ORDER BY b.DateRent_start";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $customerId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

// Function to get past bookings by customer
function getListOfPastBookingByCustomer($customerId) {
    global $con;
    
    $sql = "SELECT b.*, f.name as facilityName 
            FROM booking b 
            JOIN facility f ON b.facilityID = f.facilityID 
            WHERE b.customerID = ? 
            AND b.DateRent_end < CURDATE() 
            ORDER BY b.DateRent_start DESC";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $customerId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

// Function to check facility availability
function checkFacilityAvailability($facilityId, $startDate, $endDate) {
    global $con;
    
    $sql = "SELECT COUNT(*) as bookingCount 
            FROM booking 
            WHERE facilityID = ? 
            AND ((DateRent_start BETWEEN ? AND ?) 
            OR (DateRent_end BETWEEN ? AND ?))
            AND bookingStatus != 'Cancelled'";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", 
        $facilityId, 
        $startDate, 
        $endDate,
        $startDate,
        $endDate
    );
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    return $row['bookingCount'] == 0;
}

// Function to update booking status
function updateBookingStatus($bookingRef, $status) {
    global $con;
    
    $sql = "UPDATE booking SET bookingStatus = ? WHERE Booking_Ref = ?";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $status, $bookingRef);
    
    return mysqli_stmt_execute($stmt);
}

// Function to update payment status
function updatePaymentStatus($bookingRef, $paid) {
    global $con;
    
    $sql = "UPDATE booking SET Paid = ? WHERE Booking_Ref = ?";
    
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "is", $paid, $bookingRef);
    
    return mysqli_stmt_execute($stmt);
}

// Function to cancel booking
function cancelBooking($bookingRef) {
    global $con;
    
    return updateBookingStatus($bookingRef, 'Cancelled');
}

// Function to calculate rental period
function calculateRentalPeriod($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    return $end->diff($start)->days + 1;
}

// Function to get list of all bookings
function getBookingList($search = "") {
    global $conn;
    
    $query = "SELECT b.*, f.name as facilityName, c.customerName 
              FROM booking b 
              JOIN facility f ON b.facilityID = f.facilityID 
              JOIN customer c ON b.customerID = c.customerID";
    
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $query .= " WHERE f.name LIKE '%$search%' 
                    OR c.customerName LIKE '%$search%' 
                    OR b.bookingID LIKE '%$search%'
                    OR b.purpose LIKE '%$search%'";
    }
    
    $query .= " ORDER BY b.rentDate DESC";
    
    return mysqli_query($conn, $query);
}

// Function to get bookings by facility
function getBookingsByFacility($facilityId) {
    global $conn;
    
    $query = "SELECT b.*, c.customerName, c.phoneNo, c.email 
              FROM booking b 
              JOIN customer c ON b.customerID = c.customerID 
              WHERE b.facilityID = ? 
              ORDER BY b.rentDate DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $facilityId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

// Function to get bookings by customer
function getBookingsByCustomer($customerId) {
    global $conn;
    
    $query = "SELECT b.*, f.name as facilityName, f.category 
              FROM booking b 
              JOIN facility f ON b.facilityID = f.facilityID 
              WHERE b.customerID = ? 
              ORDER BY b.bookingDate DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $customerId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

// Function to get booking details by ID
function getBookingById($bookingId) {
    global $conn;
    
    $query = "SELECT b.*, f.name as facilityName, f.category, f.capacity, 
                     c.customerName, c.phoneNo, c.email 
              FROM booking b 
              JOIN facility f ON b.facilityID = f.facilityID 
              JOIN customer c ON b.customerID = c.customerID 
              WHERE b.bookingID = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $bookingId);
    mysqli_stmt_execute($stmt);
    
    return mysqli_stmt_get_result($stmt);
}

// Function to add new booking
function addBooking($facilityId, $customerId, $bookingDate, $purpose) {
    global $conn;
    
    // Check if facility is available
    if (!checkFacilityAvailability($facilityId, $bookingDate, $bookingDate)) {
        return ["success" => false, "message" => "Facility is not available on the selected date"];
    }
    
    // Get facility rate
    $facilityQuery = "SELECT ratePerDay FROM facility WHERE facilityID = ?";
    $stmt = mysqli_prepare($conn, $facilityQuery);
    mysqli_stmt_bind_param($stmt, "s", $facilityId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $facility = mysqli_fetch_assoc($result);
    $ratePerDay = $facility['ratePerDay'];
    
    // Generate booking ID (Format: B + YYYYMMDD + 4-digit sequence)
    $datePrefix = date('Ymd', strtotime($bookingDate));
    $query = "SELECT MAX(SUBSTRING(bookingID, -4)) as max_sequence 
              FROM booking 
              WHERE bookingID LIKE 'B" . $datePrefix . "%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $sequence = str_pad((intval($row['max_sequence']) + 1), 4, '0', STR_PAD_LEFT);
    $bookingId = 'B' . $datePrefix . $sequence;
    
    // Insert booking record
    $query = "INSERT INTO booking (bookingID, facilityID, customerID, bookingDate, purpose, ratePerDay) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssd", 
        $bookingId,
        $facilityId,
        $customerId,
        $bookingDate,
        $purpose,
        $ratePerDay
    );
    
    if (mysqli_stmt_execute($stmt)) {
        return ["success" => true, "bookingId" => $bookingId];
    } else {
        return ["success" => false, "message" => "Failed to create booking"];
    }
}

// Function to check customer existence
function checkCustomerExists($customerID) {
    global $con;
    
    $query = "SELECT customerID, customerName, Contact, Email 
              FROM customer 
              WHERE customerID = ?";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($customer = mysqli_fetch_assoc($result)) {
        return [
            'exists' => true,
            'customerName' => $customer['customerName'],
            'Contact' => $customer['Contact'],
            'Email' => $customer['Email']
        ];
    }
    
    return ['exists' => false];
}
?> 