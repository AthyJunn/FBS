<?php
function dbConnect() {
    return mysqli_connect("localhost", "web2025", "web2025", "facilitydb");
}

// Check if a facility is available
function isFacilityAvailable($facilityID, $date, $timeSlot) {
    $con = dbConnect();
    if (!$con) { echo mysqli_connect_error(); exit; }

    $sql = "SELECT * FROM booking WHERE facilityID = '$facilityID' AND date = '$date' AND timeSlot = '$timeSlot'";
    $result = mysqli_query($con, $sql);
    return mysqli_num_rows($result) == 0;
}

// Book a facility
function bookFacility($bookingID, $customerID, $facilityID, $date, $timeSlot) {
    $con = dbConnect();
    if (!$con) { echo mysqli_connect_error(); exit; }

    $sql = "INSERT INTO booking (bookingID, customerID, facilityID, date, timeSlot) 
            VALUES ('$bookingID', '$customerID', '$facilityID', '$date', '$timeSlot')";

    return mysqli_query($con, $sql);
}

// Get customers who booked a facility
function getCustomersByFacility($facilityID) {
    $con = dbConnect();
    if (!$con) { echo mysqli_connect_error(); exit; }

    $sql = "SELECT customer.* FROM customer 
            JOIN booking ON customer.customerID = booking.customerID 
            WHERE booking.facilityID = '$facilityID'";
    return mysqli_query($con, $sql);
}

// Get facilities rented by a customer
function getFacilitiesByCustomer($customerID) {
    $con = dbConnect();
    if (!$con) { echo mysqli_connect_error(); exit; }

    $sql = "SELECT facility.* FROM facility 
            JOIN booking ON facility.facilityID = booking.facilityID 
            WHERE booking.customerID = '$customerID'";
    return mysqli_query($con, $sql);
}
?>
