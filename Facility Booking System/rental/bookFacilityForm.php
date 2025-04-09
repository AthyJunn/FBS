<?php
include_once "../login/checkLogin.php";

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: ../index.php");
    exit();
}

$isStaff = isStaff();
include "booking.php";

// Initialize variables
$errorMessage = '';
$successMessage = '';
$facilityDetails = null;
$checkDateParam = '';

// Handle error messages
if (isset($_GET['error'])) {
    $messages = [
        1 => "Failed to create booking. Please try again.",
        2 => $_GET['message'] ?? "Customer not found. Please check the customer ID.",
        3 => $_GET['message'] ?? "Customer or facility not found. Please check your input."
    ];
    $errorMessage = $messages[$_GET['error']] ?? "An error occurred. Please try again.";
}

// Handle success messages
if (isset($_GET['success'])) {
    $successMessage = $_GET['message'] ?? "Operation completed successfully.";
}

// Handle customer check AJAX request
if (isset($_POST['action']) && $_POST['action'] === 'checkCustomer') {
    $customerID = $_POST['customerID'] ?? '';
    $result = checkCustomerExists($customerID);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Prepare check date parameter if available
if (isset($_GET['checkDate'])) {
    $checkDateParam = '&checkDate=' . urlencode($_GET['checkDate']);
}
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
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --border-color: #ddd;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
            color: var(--dark-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: var(--dark-color);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .error-message {
            background-color: #fde8e8;
            color: var(--danger-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success-message {
            background-color: #def7ec;
            color: var(--success-color);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .availability-section {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .availability-section h3 {
            color: var(--dark-color);
            margin-top: 0;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .facility-list {
            margin-top: 20px;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .facility-list table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .facility-list th {
            background: var(--light-color);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark-color);
        }

        .facility-list td {
            padding: 15px;
            border-top: 1px solid var(--border-color);
        }

        .facility-list tr:hover {
            background-color: #f8f9fa;
        }

        .book-now-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--success-color) 0%, #27ae60 100%);
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 8px;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .book-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .facility-details {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .facility-details h3 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 20px;
        }

        .facility-details p {
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .facility-details strong {
            min-width: 120px;
            display: inline-block;
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .input-group input {
            flex: 1;
        }

        .input-group button {
            padding: 12px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-group button:hover {
            background: var(--secondary-color);
        }

        .input-group button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .customer-info {
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .w3-panel {
            margin-top: 10px;
            padding: 15px;
            border-radius: 8px;
        }

        .w3-pale-blue {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .w3-pale-red {
            background-color: #ffebee;
            color: #c62828;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-cancel {
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            color: var(--dark-color);
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .input-group {
                flex-direction: column;
            }

            .input-group button {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-submit, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }

        /* Update Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
            padding: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .close-modal:hover {
            color: var(--danger-color);
        }

        .facility-summary {
            background: linear-gradient(to right, #f8f9fa, #ffffff);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }

        .facility-summary h4 {
            margin: 0 0 10px 0;
            color: var(--primary-color);
        }

        .rental-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            color: var(--primary-color);
        }

        /* Ensure modal is usable on mobile */
        @media (max-width: 768px) {
            .modal {
                padding: 10px;
            }
            
            .modal-content {
                margin: 20px auto;
                padding: 20px;
                width: 95%;
            }

            .close-modal {
                position: sticky;
                top: 0;
                right: 0;
                background: white;
                padding: 10px;
                z-index: 1;
                margin-bottom: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        }

        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-calendar-plus"></i> Book Facility</h2>

        <?php if ($errorMessage): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="success-message"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

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
                                <?php if (!$isStaff): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($facility = mysqli_fetch_assoc($result)): ?>
                                <?php 
                                $availabilityClass = strtolower($facility['availability'] ?? '') === 'available' ? 'w3-text-green' : 'w3-text-red';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($facility['name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($facility['category'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($facility['capacity'] ?? '') ?> persons</td>
                                    <td>RM<?= htmlspecialchars($facility['ratePerDay'] ?? '') ?></td>
                                    <td class="<?= $availabilityClass ?> w3-bold"><?= htmlspecialchars($facility['availability'] ?? '') ?></td>
                                    <?php if (!$isStaff && strtolower($facility['availability'] ?? '') === 'available'): ?>
                                        <td>
                                            <button onclick="openBookingModal('<?= htmlspecialchars($facility['facilityID'] ?? '') ?>', 
                                                '<?= htmlspecialchars($facility['name'] ?? '') ?>', 
                                                '<?= htmlspecialchars($facility['ratePerDay'] ?? '') ?>')" 
                                                class="book-now-btn">
                                                <i class="fas fa-calendar-plus"></i> Book Now
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>

        <!-- Booking Modal -->
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeBookingModal()">&times;</span>
                <h3><i class="fas fa-calendar-plus"></i> Book Facility</h3>
                
                <div class="facility-summary">
                    <h4 id="modalFacilityName"></h4>
                    <p>Rate per Day: RM<span id="modalRatePerDay"></span></p>
                </div>

                <form id="bookingForm" action="processBooking.php" method="POST">
                    <input type="hidden" id="modalFacilityId" name="facilityID">
                    <input type="hidden" id="rentalDays" name="rentalDays">
                    <input type="hidden" id="totalCost" name="totalCost">
                    
                    <div class="form-group">
                        <label for="modalCustomerId">Customer ID:</label>
                        <input type="text" id="modalCustomerId" name="customerID" 
                            value="<?= htmlspecialchars($_SESSION['customerID'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="modalReservedBy">Reserved By:</label>
                        <input type="text" id="modalReservedBy" name="reservedBy" required 
                            value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="modalStartDate">Rental Start Date:</label>
                        <input type="text" id="modalStartDate" name="dateRent_start" class="flatpickr-modal" required>
                    </div>

                    <div class="form-group">
                        <label for="modalEndDate">Rental End Date:</label>
                        <input type="text" id="modalEndDate" name="dateRent_end" class="flatpickr-modal" required>
                    </div>

                    <div class="form-group">
                        <label for="modalPurpose">Purpose:</label>
                        <textarea id="modalPurpose" name="purpose" rows="3" required></textarea>
                    </div>

                    <div class="rental-summary">
                        <div class="summary-item">
                            <span>Duration:</span>
                            <span id="rentalDuration">0 days</span>
                        </div>
                        <div class="summary-item">
                            <span>Total Amount:</span>
                            <span id="totalAmount">RM 0.00</span>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Confirm Booking
                        </button>
                        <button type="button" onclick="closeBookingModal()" class="btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize date pickers
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        flatpickr(".flatpickr-modal", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (instance.element.id === 'modalStartDate' || instance.element.id === 'modalEndDate') {
                    calculateTotal();
                }
            }
        });

        let currentRatePerDay = 0;

        function openBookingModal(facilityId, facilityName, ratePerDay) {
            document.getElementById('modalFacilityId').value = facilityId;
            document.getElementById('modalFacilityName').textContent = facilityName;
            document.getElementById('modalRatePerDay').textContent = ratePerDay;
            currentRatePerDay = parseFloat(ratePerDay);
            
            // Reset form
            document.getElementById('bookingForm').reset();
            document.getElementById('rentalDuration').textContent = '0 days';
            document.getElementById('totalAmount').textContent = 'RM 0.00';
            
            // Show modal and prevent body scrolling
            document.getElementById('bookingModal').style.display = 'block';
            document.body.classList.add('modal-open');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').style.display = 'none';
            document.body.classList.remove('modal-open');
        }

        function calculateTotal() {
            const startDate = document.getElementById('modalStartDate')._flatpickr.selectedDates[0];
            const endDate = document.getElementById('modalEndDate')._flatpickr.selectedDates[0];
            
            if (startDate && endDate) {
                // Calculate number of days
                const diffTime = Math.abs(endDate - startDate);
                const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                // Update duration display
                document.getElementById('rentalDuration').textContent = days + ' day' + (days > 1 ? 's' : '');
                
                // Calculate and update total amount
                const total = currentRatePerDay * days;
                document.getElementById('totalAmount').textContent = 'RM ' + total.toFixed(2);
                
                // Update hidden fields
                document.getElementById('rentalDays').value = days;
                document.getElementById('totalCost').value = total.toFixed(2);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeBookingModal();
            }
        }

        // Add this function to generate booking reference
        function generateBookingReference() {
            const datePrefix = new Date().toISOString().slice(0,10).replace(/-/g,'');
            return 'BK' + datePrefix + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        }

        // Modify the form submission handler
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            
            const startDate = document.getElementById('modalStartDate')._flatpickr.selectedDates[0];
            const endDate = document.getElementById('modalEndDate')._flatpickr.selectedDates[0];
            const purpose = document.getElementById('modalPurpose').value;
            
            
            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }
            
            if (startDate > endDate) {
                alert('End date must be after start date');
                return;
            }
            
            if (!purpose.trim()) {
                alert('Please enter a purpose for the booking');
                return;
            }
            
            // Generate booking reference
            const bookingRef = generateBookingReference();
            
            // Create hidden input for booking reference
            const regNumberInput = document.createElement('input');
            regNumberInput.type = 'hidden';
            regNumberInput.name = 'regNumber';
            regNumberInput.value = bookingRef;
            this.appendChild(regNumberInput);
            
            // Submit the form
            this.submit();
        });
    </script>
</body>
</html>