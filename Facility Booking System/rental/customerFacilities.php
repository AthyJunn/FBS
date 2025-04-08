<?php
include_once("../login/checkLogin.php");
include_once("booking.php");
$isStaff = isStaff();

// Database connection
$con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');

// Get list of customers for staff
$customersQuery = "SELECT customerID, customerName FROM customer ORDER BY customerName";
$customersResult = mysqli_query($con, $customersQuery);

// Get selected customer (if any)
$selectedCustomer = $_GET['customer'] ?? '';
$facilities = [];

if (!empty($selectedCustomer)) {
    $facilities = getFacilitiesRentedByCustomer($selectedCustomer);
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
    <title>Customer Facilities</title>
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
        
        .customer-info {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .customer-info h2 {
            color: var(--dark-color);
            margin-top: 0;
            font-size: 1.5rem;
        }
        
        .customer-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 1rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .facility-table {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .facility-table th,
        .facility-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .facility-table th {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-weight: 600;
        }
        
        .facility-table tr:hover {
            background-color: #f8f9fa;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-building"></i> Customer Facilities
            </h1>
        </div>
        
        <!-- Customer Selection -->
        <div class="search-container">
            <form action="" method="GET" style="width: 100%; display: flex; gap: 10px;">
                <select name="customer" class="search-input" style="flex: 1;">
                    <option value="">Select a customer to view facilities...</option>
                    <?php while ($customer = mysqli_fetch_assoc($customersResult)): ?>
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

        <?php if ($customerDetails): ?>
        <!-- Customer Information -->
        <div class="customer-info">
            <h2><i class="fas fa-user"></i> Customer Information</h2>
            <div class="customer-details">
                <div class="detail-item">
                    <span class="detail-label">Customer ID</span>
                    <span class="detail-value"><?php echo $customerDetails['customerID']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value"><?php echo $customerDetails['customerName']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact</span>
                    <span class="detail-value"><?php echo $customerDetails['Contact']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?php echo $customerDetails['Email']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value"><?php echo $customerDetails['PayMethod']; ?></span>
                </div>
            </div>
        </div>

        <?php if (mysqli_num_rows($facilities) > 0): ?>
        <!-- Facilities Table -->
        <table class="facility-table">
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
                <?php while ($facility = mysqli_fetch_assoc($facilities)): ?>
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
        <?php else: ?>
        <div class="alert alert-info" style="background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; padding: 15px; border-radius: 4px;">
            <i class="fas fa-info-circle"></i> This customer has not rented any facilities yet.
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html> 