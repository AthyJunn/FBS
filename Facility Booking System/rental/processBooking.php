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
    // Validate required fields
    $requiredFields = ['DateRent_start', 'DateRent_end', 'purpose'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        header("Location: bookFacilityForm.php?error=1&message=" . urlencode("Missing required fields: " . implode(", ", $missingFields)));
        exit();
    }
    
    // Check if user is logged in as customer
    if (isset($_SESSION['userType']) && $_SESSION['userType'] == 'customer') {
        // For logged-in customers, we don't need to check customer existence
        // as we're using the customerID from the session
        $customerId = $_SESSION['customerID'];
        
        // Add new booking record
        $result = addNewBookingRecord();
        
        if ($result) {
            // Redirect to booking list with success message
            header("Location: bookingListForm.php?success=1&message=" . urlencode("Booking created successfully!"));
            exit();
        } else {
            // Redirect with error message
            header("Location: bookFacilityForm.php?error=1&message=" . urlencode("Failed to create booking. Please try again."));
            exit();
        }
    } else {
        // For non-logged-in users or staff, check if customer exists
        $customerId = $_POST['customerID'];
        $customerExists = checkCustomerExists($customerId);
        
        if ($customerExists['exists']) {
            // Generate a unique booking reference
            $datePrefix = date('Ymd');
            $query = "SELECT MAX(SUBSTRING(Booking_Ref, -4)) as max_sequence 
                     FROM booking 
                     WHERE Booking_Ref LIKE 'BK" . $datePrefix . "%'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $sequence = str_pad((intval($row['max_sequence'] ?? '0') + 1), 4, '0', STR_PAD_LEFT);
            $_POST['regNumber'] = 'BK' . $datePrefix . $sequence;
            
            // Add new booking record
            $result = addNewBookingRecord();
            
            if ($result) {
                // Redirect to booking list with success message
                header("Location: bookingListForm.php?success=1&message=" . urlencode("Booking created successfully!"));
                exit();
            } else {
                // Redirect with error message
                header("Location: bookFacilityForm.php?error=1&message=" . urlencode("Failed to create booking. Please try again."));
                exit();
            }
        } else {
            // Redirect with error message
            header("Location: bookFacilityForm.php?error=2&message=" . urlencode("Customer not found."));
            exit();
        }
    }
}

// Handle status update via GET parameters (for action buttons)
if (isset($_GET['action']) && $_GET['action'] == 'updateStatus' && isset($_GET['bookingRef']) && isset($_GET['status'])) {
    $bookingRef = $_GET['bookingRef'];
    $newStatus = $_GET['status'];
    
    if (updateBookingStatus($bookingRef, $newStatus)) {
        header("Location: bookingListForm.php?statusUpdated=1&status=" . $newStatus);
    } else {
        header("Location: bookingListForm.php?statusError=1");
    }
    exit();
}

// Handle status update via POST (for form submissions)
if (isset($_POST['updateStatus'])) {
    $bookingRef = $_POST['bookingRef'];
    $newStatus = $_POST['newStatus'];
    
    if (updateBookingStatus($bookingRef, $newStatus)) {
        header("Location: bookingListForm.php?statusUpdated=1&status=" . $newStatus);
    } else {
        header("Location: bookingListForm.php?statusError=1");
    }
    exit();
}

// Handle payment update
if (isset($_GET['action']) && $_GET['action'] == 'updatePayment' && isset($_GET['bookingRef'])) {
    $bookingRef = $_GET['bookingRef'];
    
    if (updatePaymentStatus($bookingRef, 1)) {
        header("Location: bookingListForm.php?paymentUpdated=1");
    } else {
        header("Location: bookingListForm.php?paymentError=1");
    }
    exit();
}

// Handle payment update via POST
if (isset($_POST['updatePayment'])) {
    $bookingRef = $_POST['bookingRef'];
    $newPaidStatus = $_POST['newPaidStatus'];
    
    if (updatePaymentStatus($bookingRef, $newPaidStatus)) {
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