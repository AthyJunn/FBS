<?php
include 'rentFacility.php';

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'errors' => array()
);

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input data
    $bookingID = trim($_POST['bookingID'] ?? '');
    $customerID = trim($_POST['customerID'] ?? '');
    $facilityID = trim($_POST['facilityID'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $timeSlot = trim($_POST['timeSlot'] ?? '');

    // Validate inputs
    if (empty($bookingID)) {
        $response['errors'][] = "Booking ID is required";
    }
    if (empty($customerID)) {
        $response['errors'][] = "Customer ID is required";
    }
    if (empty($facilityID)) {
        $response['errors'][] = "Facility ID is required";
    }
    if (empty($date)) {
        $response['errors'][] = "Date is required";
    } elseif (!isValidDate($date)) {
        $response['errors'][] = "Invalid date format";
    }
    if (empty($timeSlot)) {
        $response['errors'][] = "Time slot is required";
    } elseif (!isValidTimeSlot($timeSlot)) {
        $response['errors'][] = "Invalid time slot format";
    }

    // If no validation errors, proceed with booking
    if (empty($response['errors'])) {
        if (isFacilityAvailable($facilityID, $date, $timeSlot)) {
            if (bookFacility($bookingID, $customerID, $facilityID, $date, $timeSlot)) {
                $response['success'] = true;
                $response['message'] = "Facility booked successfully!";
                
                // Get booking details for confirmation
                $facility = getFacilityDetails($facilityID);
                $customer = getCustomerDetails($customerID);
                
                $response['booking_details'] = array(
                    'facility_name' => $facility['facilityName'] ?? 'Unknown Facility',
                    'customer_name' => $customer['name'] ?? 'Unknown Customer',
                    'date' => $date,
                    'time_slot' => $timeSlot
                );
            } else {
                $response['message'] = "Booking failed. Please try again.";
            }
        } else {
            $response['message'] = "Facility is not available at that time.";
        }
    } else {
        $response['message'] = "Please correct the following errors:";
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
