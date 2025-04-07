<?php
include "booking.php";

// Handle customer check
if (isset($_POST['action'])){
    switch ($_POST['action']) {
        case 'checkCustomer':
            if (isset($_POST['customerID'])) {
                $result = checkCustomerExists($_POST['customerID']);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit();
            }
            break;
    }
}

// Handle booking submission
if (isset($_POST['submitBooking'])) {
    if (addNewBookingRecord()) {
        header("Location: bookingListForm.php?success=1&message=Booking created successfully");
        exit();
    } else {
        header("Location: bookFacilityForm.php?error=1&message=Failed to create booking");
        exit();
    }
}

// Handle status update
if (isset($_GET['action']) && $_GET['action'] === 'updateStatus' && 
    isset($_GET['bookingRef']) && isset($_GET['status'])) {
    
    $bookingRef = $_GET['bookingRef'];
    $status = $_GET['status'];
    
    if (updateBookingStatus($bookingRef, $status)) {
        header("Location: bookingListForm.php?success=1&message=Booking status updated successfully");
        exit();
    } else {
        header("Location: bookingListForm.php?error=1&message=Failed to update booking status");
        exit();
    }
}

// Handle payment update
if (isset($_GET['action']) && $_GET['action'] === 'updatePayment' && isset($_GET['bookingRef'])) {
    $bookingRef = $_GET['bookingRef'];
    
    if (updatePaymentStatus($bookingRef, 1)) {
        header("Location: bookingListForm.php?success=1&message=Payment status updated successfully");
        exit();
    } else {
        header("Location: bookingListForm.php?error=1&message=Failed to update payment status");
        exit();
    }
}

// If accessed directly without proper parameters
header("Location: bookingListForm.php");
exit();
?> 