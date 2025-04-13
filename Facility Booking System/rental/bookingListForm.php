<?php
include_once("../login/checkLogin.php");
include_once("booking.php");
$isStaff = isStaff();

// Database connection
$con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');

// Get list of facilities
$facilitiesQuery = "SELECT facilityID, name FROM facility ORDER BY name";
$facilitiesResult = mysqli_query($con, $facilitiesQuery);

// Get list of customers for staff
$customersQuery = "SELECT customerID, customerName FROM customer ORDER BY customerName";
$customersResult = mysqli_query($con, $customersQuery);

// Get selected facility (if any)
$selectedFacility = $_GET['facility'] ?? '';
$customers = [];

if ($isStaff && !empty($selectedFacility)) {
    $customers = getCustomersByFacility($selectedFacility);
}

// Get selected customer (if any)
$selectedCustomer = $_GET['customer'] ?? '';
$customerFacilities = [];

if (!empty($selectedCustomer)) {
    $customerFacilities = getFacilitiesRentedByCustomer($selectedCustomer);
}

// Get customer details if selected
$customerDetails = null;
if (!empty($selectedCustomer)) {
    $customerQuery = "SELECT * FROM customer WHERE customerID = ?";
    $stmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($stmt, "s", $selectedCustomer);
    mysqli_stmt_execute($stmt);
    $customerDetails = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking List</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-size: 2rem;
            color: var(--dark-color);
            margin: 0;
        }
        
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            gap: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .booking-table {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .booking-table th,
        .booking-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .booking-table th {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .booking-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: var(--warning-color);
            color: white;
        }
        
        .status-confirmed {
            background-color: var(--success-color);
            color: white;
        }
        
        .status-cancelled {
            background-color: var(--danger-color);
            color: white;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .payment-paid {
            background-color: var(--success-color);
            color: white;
        }
        
        .payment-unpaid {
            background-color: var(--warning-color);
            color: white;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 1200px) {
            .booking-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php
    $isStaff = isStaff();
    ?>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-calendar-alt"></i> 
                <?php echo $isStaff ? 'All Bookings' : 'My Bookings'; ?>
            </h1>
            <?php if (!$isStaff): ?>
            <a href="bookFacilityForm.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
            <?php endif; ?>
        </div>
        
        <?php if (!$isStaff): ?>
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i> You are viewing your booking history. Only bookings made by you will be displayed.
        </div>
        <?php endif; ?>
        
        <?php
        // Display success messages
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Booking created successfully!
                  </div>';
        }
        
        if (isset($_GET['statusUpdated'])) {
            $status = isset($_GET['status']) ? $_GET['status'] : 'updated';
            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Booking status has been ' . $status . ' successfully!
                  </div>';
        }
        
        if (isset($_GET['statusError'])) {
            echo '<div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Failed to update booking status. Please try again.
                  </div>';
        }
        
        if (isset($_GET['paymentUpdated'])) {
            echo '<div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Payment status has been updated successfully!
                  </div>';
        }
        
        if (isset($_GET['paymentError'])) {
            echo '<div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Failed to update payment status. Please try again.
                  </div>';
        }
        ?>
        
        <?php if ($isStaff): ?>
        <!-- Facility and Customer Filter for Staff -->
        <div class="search-container" style="margin-bottom: 30px;">
            <form action="" method="GET" style="width: 100%; display: flex; gap: 10px;">
                <select name="facility" class="search-input" style="flex: 1;">
                    <option value="">Select a facility to view customers...</option>
                    <?php while ($facility = mysqli_fetch_assoc($facilitiesResult)): ?>
                    <option value="<?php echo $facility['facilityID']; ?>" 
                            <?php echo $selectedFacility == $facility['facilityID'] ? 'selected' : ''; ?>>
                        <?php echo $facility['name']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> View Customers
                </button>
            </form>
        </div>

        <!-- Customer Selection -->
        <div class="search-container" style="margin-bottom: 30px;">
            <form action="" method="GET" style="width: 100%; display: flex; gap: 10px;">
                <select name="customer" class="search-input" style="flex: 1;">
                    <option value="">Select a customer to view facilities...</option>
                    <?php 
                    // Reset the customers result pointer
                    mysqli_data_seek($customersResult, 0);
                    while ($customer = mysqli_fetch_assoc($customersResult)): 
                    ?>
                    <option value="<?php echo $customer['customerID']; ?>" 
                            <?php echo $selectedCustomer == $customer['customerID'] ? 'selected' : ''; ?>>
                        <?php echo $customer['customerName']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> View Facilities
                </button>
            </form>
        </div>

        <?php if (!empty($selectedFacility) && mysqli_num_rows($customers) > 0): ?>
        <!-- Customers List Table -->
        <div style="margin-bottom: 30px;">
            <h2 style="color: var(--dark-color); font-size: 1.5rem; margin-bottom: 15px;">
                <i class="fas fa-users"></i> Customers Using This Facility
            </h2>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($customer = mysqli_fetch_assoc($customers)): ?>
                    <tr>
                        <td><?php echo $customer['customerID']; ?></td>
                        <td><?php echo $customer['customerName']; ?></td>
                        <td><?php echo $customer['Contact']; ?></td>
                        <td><?php echo $customer['Email']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php elseif (!empty($selectedFacility)): ?>
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; margin-bottom: 30px;">
            <i class="fas fa-info-circle"></i> No customers found for this facility.
        </div>
        <?php endif; ?>

        <?php if ($customerDetails): ?>
        <!-- Customer Information -->
        <div class="customer-info" style="margin-bottom: 30px;">
            <h2 style="color: var(--dark-color); font-size: 1.5rem; margin-bottom: 15px;">
                <i class="fas fa-user"></i> Customer Information
            </h2>
            <div class="customer-details">
                <div class="detail-item">
                    <span class="detail-label">Customer ID: </span>
                    <span class="detail-value"><?php echo $customerDetails['customerID']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Name: </span>
                    <span class="detail-value"><?php echo $customerDetails['customerName']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact: </span>
                    <span class="detail-value"><?php echo $customerDetails['Contact']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email: </span>
                    <span class="detail-value"><?php echo $customerDetails['Email']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method: </span>
                    <span class="detail-value"><?php echo $customerDetails['PayMethod']; ?></span>
                </div>
            </div>
        </div>

        <?php if (mysqli_num_rows($customerFacilities) > 0): ?>
        <!-- Customer Facilities Table -->
        <div style="margin-bottom: 30px;">
            <h2 style="color: var(--dark-color); font-size: 1.5rem; margin-bottom: 15px;">
                <i class="fas fa-building"></i> Facilities Rented by Customer
            </h2>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Facility ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Rate Per Day</th>
                        <th>Total Bookings</th>
                        <th>Confirmed Bookings</th>
                        <th>Last Rent Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($facility = mysqli_fetch_assoc($customerFacilities)): ?>
                    <tr>
                        <td><?php echo $facility['facilityID']; ?></td>
                        <td><?php echo $facility['facilityName']; ?></td>
                        <td><?php echo $facility['category']; ?></td>
                        <td>$<?php echo number_format($facility['ratePerDay'], 2); ?></td>
                        <td><?php echo $facility['totalBookings']; ?></td>
                        <td><?php echo $facility['confirmedBookings']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($facility['lastRentDate'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; margin-bottom: 30px;">
            <i class="fas fa-info-circle"></i> This customer has not rented any facilities yet.
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
        
        <div class="search-container">
            <form action="" method="GET" style="width: 100%; display: flex; gap: 10px;">
                <input type="text" name="search" class="search-input" 
                       placeholder="Search by facility name, customer name, or booking reference..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
        
        <?php
        // Get and display bookings
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $bookings = getListOfBooking($search);
        
        if (mysqli_num_rows($bookings) > 0) {
            ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Facility</th>
                        <th>Customer</th>
                        <th>Reserved Date</th>
                        <th>Rental Period</th>
                        <th>Amount Due</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($booking = mysqli_fetch_assoc($bookings)) {
                        ?>
                        <tr>
                            <td><?php echo $booking['Booking_Ref']; ?></td>
                            <td><?php echo $booking['facilityName']; ?></td>
                            <td><?php echo $booking['customerName']; ?></td>
                            <td>
                                <?php 
                                echo date('M j, Y', strtotime($booking['DateReserved'])) . '<br>';
                                echo '<small>Start: ' . date('M j, Y', strtotime($booking['DateRent_start'])) . '</small><br>';
                                echo '<small>End: ' . date('M j, Y', strtotime($booking['DateRent_end'])) . '</small>';
                                ?>
                            </td>
                            <td><?php echo $booking['RentalPeriod']; ?> days</td>
                            <td>$<?php 
                                // Check which column name exists for amount due
                                if (isset($booking['amountDue'])) {
                                    echo number_format($booking['amountDue'], 2);
                                } elseif (isset($booking['Amount_Due'])) {
                                    echo number_format($booking['Amount_Due'], 2);
                                } else {
                                    // Calculate amount based on rental period and facility rate if available
                                    echo "N/A";
                                }
                            ?></td>
                            <td>
                                <span class="payment-badge payment-<?php echo $booking['Paid'] ? 'paid' : 'unpaid'; ?>">
                                    <?php echo $booking['Paid'] ? 'Paid' : 'Unpaid'; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($booking['bookingStatus']); ?>">
                                    <?php echo $booking['bookingStatus']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($booking['bookingStatus'] == 'Pending') { ?>
                                    <button onclick="updateStatus('<?php echo $booking['Booking_Ref']; ?>', 'Confirmed')" 
                                            class="btn btn-primary" style="padding: 6px 12px; font-size: 0.875rem;">
                                        <i class="fas fa-check"></i> Confirm
                                    </button>
                                    <button onclick="updateStatus('<?php echo $booking['Booking_Ref']; ?>', 'Cancelled')" 
                                            class="btn btn-danger" style="padding: 6px 12px; font-size: 0.875rem;">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                <?php } ?>
                                <?php if ($booking['bookingStatus'] == 'Confirmed' && !$booking['Paid']) { ?>
                                    <button onclick="updatePayment('<?php echo $booking['Booking_Ref']; ?>')" 
                                            class="btn btn-primary" style="padding: 6px 12px; font-size: 0.875rem;">
                                        <i class="fas fa-dollar-sign"></i> Mark as Paid
                                    </button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<div class="alert alert-info" style="background-color: #f8f9fa; color: #383d41; border: 1px solid #d6d8db; margin-top: 20px;">
                    <i class="fas fa-info-circle"></i> ' . 
                    ($isStaff ? 'No bookings found matching your search criteria.' : 'You haven\'t made any bookings yet.') .
                  '</div>';
        }
        ?>
    </div>
    
    <script>
    function updateStatus(bookingRef, status) {
        if (confirm('Are you sure you want to ' + status.toLowerCase() + ' this booking?')) {
            window.location.href = 'processBooking.php?action=updateStatus&bookingRef=' + 
                                 bookingRef + '&status=' + status;
        }
    }
    
    function updatePayment(bookingRef) {
        if (confirm('Mark this booking as paid?')) {
            window.location.href = 'processBooking.php?action=updatePayment&bookingRef=' + bookingRef;
        }
    }
    </script>
</body>
</html> 