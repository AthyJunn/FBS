<?php
include_once "../login/checkLogin.php";

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: ../index.php");
    exit();
}

$isStaff = isStaff();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Facility</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --error-color: #f44336;
            --success-color: #4CAF50;
            --text-color: #333;
            --border-color: #ddd;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        .container {
            width: 95%;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 40px);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--text-color);
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: #808080;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #666;
        }

        .error-message {
            color: var(--error-color);
            margin-bottom: 10px;
        }

        .success-message {
            color: var(--success-color);
            margin-bottom: 10px;
        }

        .date-inputs {
            display: flex;
            gap: 15px;
        }

        .date-inputs > div {
            flex: 1;
        }

        .facility-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .facility-details h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .facility-list {
            margin-top: 20px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            overflow-x: auto;
        }

        .facility-list table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .btn.action-btn {
            padding: 6px 12px;
            font-size: 14px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 0;
        }

        .availability-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #000;
        }

        .rental-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .rental-summary h4 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .amount-display {
            font-size: 1.2em;
            font-weight: bold;
            color: var(--primary-color);
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-calendar-plus"></i> Book Facility</h2>

        <?php
        include "booking.php";

        if (isset($_GET['error'])) {
            $messages = [
                1 => "Failed to create booking. Please try again.",
                2 => $_GET['message'] ?? "Customer not found. Please check the customer ID.",
                3 => $_GET['message'] ?? "Customer or facility not found. Please check your input."
            ];
            echo '<div class="alert alert-danger">' . ($messages[$_GET['error']] ?? "An error occurred. Please try again.") . '</div>';
        }

        if (isset($_GET['success'])) {
            echo '<div class="success-message"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_GET['message']) . '</div>';
        }

        if (isset($_POST['action']) && $_POST['action'] === 'checkCustomer') {
            $customerID = $_POST['customerID'];
            $result = checkCustomerExists($customerID);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
        ?>

        <!-- Availability Check Section -->
        <div class="availability-section">
            <h3><i class="fas fa-search"></i> Check Facility Availability</h3>
            <form action="" method="GET" id="availabilityForm">
                <div class="date-inputs">
                    <div class="form-group">
                        <label for="checkDate">Select Date to Check:</label>
                        <input type="text" id="checkDate" name="checkDate" class="flatpickr" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary check-availability-btn">
                    <i class="fas fa-search"></i> Check Availability
                </button>
            </form>

            <?php
            if (isset($_GET['checkDate'])) {
                $checkDate = $_GET['checkDate'];
                $query = "SELECT f.*, 
                            CASE 
                                WHEN f.status = 'Unavailable' THEN 'Unavailable'
                                WHEN COUNT(b.Booking_Ref) > 0 THEN 'Unavailable'
                                ELSE 'Available' 
                            END as availability
                        FROM facility f
                        LEFT JOIN booking b ON f.facilityID = b.facilityID 
                            AND ? BETWEEN b.DateRent_start AND b.DateRent_end
                            AND b.bookingStatus != 'Cancelled'
                        GROUP BY f.facilityID";

                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "s", $checkDate);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            ?>
                <div class="facility-list">
                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>Facility Name</th>
                                <th>Category</th>
                                <th>Capacity</th>
                                <th>Rate/Day</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($facility = mysqli_fetch_assoc($result)): ?>
                                <?php 
                                $availabilityClass = strtolower($facility['availability']) === 'available' ? 'w3-text-green' : 'w3-text-red';
                                $checkDateParam = isset($checkDate) ? '&checkDate=' . htmlspecialchars($checkDate) : '';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($facility['name']) ?></td>
                                    <td><?= htmlspecialchars($facility['category']) ?></td>
                                    <td><?= htmlspecialchars($facility['capacity']) ?> persons</td>
                                    <td>RM<?= htmlspecialchars($facility['ratePerDay']) ?></td>
                                    <td class="<?= $availabilityClass ?> w3-bold"><?= htmlspecialchars($facility['availability']) ?></td>
                                    <td>
                                        <?php if ($facility['availability'] === 'Available'): ?>
                                            <a href="?facilityId=<?= htmlspecialchars($facility['facilityID']) ?><?= $checkDateParam ?>" 
                                               class="w3-button w3-green w3-round-large w3-small">
                                                <i class="fas fa-calendar-plus"></i> Book Now
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>

        <?php
        $facilityDetails = null;
        if (isset($_GET['facilityId'])) {
            $facilityId = $_GET['facilityId'];
            $query = "SELECT * FROM facility WHERE facilityID = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "s", $facilityId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $facilityDetails = mysqli_fetch_assoc($result);
        }
        ?>

        <?php if ($facilityDetails): ?>
        <div class="facility-details">
            <h3><?= htmlspecialchars($facilityDetails['name']) ?></h3>
            <p><strong>Category:</strong> <?= htmlspecialchars($facilityDetails['category']) ?></p>
            <p><strong>Capacity:</strong> <?= htmlspecialchars($facilityDetails['capacity']) ?> persons</p>
            <p><strong>Rate per Day:</strong> RM<?= htmlspecialchars($facilityDetails['ratePerDay']) ?></p>
        </div>

        <form action="processBooking.php" method="POST">
            <input type="hidden" name="facilityID" value="<?= htmlspecialchars($facilityId) ?>">
            
            <div class="form-group">
                <label for="customerID">Customer ID:</label>
                <div class="input-group">
                    <?php if (!$isStaff): ?>
                        <input type="text" id="customerID" name="customerID" value="<?= htmlspecialchars($_SESSION['customerID'] ?? '') ?>" readonly>
                        <button type="button" class="w3-button w3-blue w3-round" disabled><i class="fas fa-search"></i> Check</button>
                    <?php else: ?>
                        <input type="text" id="customerID" name="customerID" required>
                        <button type="button" onclick="validateCustomer()" class="w3-button w3-blue w3-round"><i class="fas fa-search"></i> Check</button>
                    <?php endif; ?>
                </div>
                <div id="customerInfo" class="w3-panel w3-pale-blue w3-round" style="display:none;">
                    <p id="customerDetails"></p>
                </div>
                <div id="customerError" class="w3-panel w3-pale-red w3-round" style="display:none;">
                    <p><i class="fas fa-exclamation-circle"></i> Customer ID not found.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="reservedBy">Reserved By:</label>
                <input type="text" id="reservedBy" name="reservedBy" required>
            </div>

            <div class="form-group">
                <label for="DateRent_start">Rental Start Date:</label>
                <input type="text" id="DateRent_start" name="DateRent_start" class="flatpickr" required value="<?= htmlspecialchars($_GET['checkDate'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="DateRent_end">Rental End Date:</label>
                <input type="text" id="DateRent_end" name="DateRent_end" class="flatpickr" required>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <textarea id="purpose" name="purpose" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="submitBooking" class="btn btn-primary"><i class="fas fa-save"></i> Submit Booking</button>
                <button type="button" onclick="window.location.href='bookingListForm.php'" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button>
            </div>
        </form>
        <?php else: ?>
            <div class="availability-section">
                <h3><i class="fas fa-info-circle"></i> Facility Booking</h3>
                <p>Please check facility availability using the form above to proceed with booking.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- JavaScript -->
    <script>
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });

<<<<<<< HEAD
        flatpickr(".flatpickr-modal", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });

=======
>>>>>>> parent of 8d0544e (fix book now)
        function validateCustomer() {
            const customerID = document.getElementById('customerID').value;
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({'action': 'checkCustomer', 'customerID': customerID})
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    document.getElementById('customerDetails').innerText = 'Customer exists: ' + data.name;
                    document.getElementById('customerInfo').style.display = 'block';
                    document.getElementById('customerError').style.display = 'none';
                } else {
                    document.getElementById('customerInfo').style.display = 'none';
                    document.getElementById('customerError').style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>
