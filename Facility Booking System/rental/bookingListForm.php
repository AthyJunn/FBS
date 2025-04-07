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
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-calendar-alt"></i> Booking List
            </h1>
            <a href="bookFacilityForm.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Booking
            </a>
        </div>
        
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
        include "booking.php";
        
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
                            <td>$<?php echo number_format($booking['Amount_Due'], 2); ?></td>
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
            echo "<p>No bookings found.</p>";
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