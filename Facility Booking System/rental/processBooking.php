<?php
include_once("booking.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

// Check if the form was submitted
if (isset($_POST['submitBooking'])) {
    // Check if user is logged in as customer
    if (isset($_SESSION['userType']) && $_SESSION['userType'] == 'customer') {
        // For logged-in customers, we don't need to check customer existence
        // as we're using the customerID from the session
        $result = addNewBookingRecord();
        
        if ($result) {
            // Redirect to booking list with success message
            header("Location: bookingListForm.php?success=1");
            exit();
        } else {
            // Redirect with error message
            header("Location: bookFacilityForm.php?error=3&message=Customer or facility not found. Please check your input.");
            exit();
        }
    } else {
        // For non-logged-in users or staff, check if customer exists
        $customerId = $_POST['customerID'];
        $customerExists = checkCustomerExists($customerId);
        
        if ($customerExists) {
            // Add new booking record
            $result = addNewBookingRecord();
            
            if ($result) {
                // Redirect to booking list with success message
                header("Location: bookingListForm.php?success=1");
                exit();
            } else {
                // Redirect with error message
                header("Location: bookFacilityForm.php?error=3&message=Customer or facility not found. Please check your input.");
                exit();
            }
        } else {
            // Redirect with error message
            header("Location: bookFacilityForm.php?error=2&message=Customer not found.");
            exit();
        }
    }
}

// Handle status update
if (isset($_POST['updateStatus'])) {
    $bookingRef = $_POST['bookingRef'];
    $newStatus = $_POST['newStatus'];
    
    if (updateBookingStatus($bookingRef, $newStatus)) {
        header("Location: bookingListForm.php?statusUpdated=1");
    } else {
        header("Location: bookingListForm.php?statusError=1");
    }
    exit();
}

// Handle payment update
if (isset($_POST['updatePayment'])) {
    $bookingRef = $_POST['bookingRef'];
    $newPaidStatus = $_POST['newPaidStatus'];
    
    if (updateBookingPayment($bookingRef, $newPaidStatus)) {
        header("Location: bookingListForm.php?paymentUpdated=1");
    } else {
        header("Location: bookingListForm.php?paymentError=1");
    }
    exit();
}

// If accessed directly without proper parameters
header("Location: bookingListForm.php");
exit();
?> 