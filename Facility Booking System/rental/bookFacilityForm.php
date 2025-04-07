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

        // Display error/success messages
        if (isset($_GET['error'])) {
            echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_GET['message']) . '</div>';
        }
        if (isset($_GET['success'])) {
            echo '<div class="success-message"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_GET['message']) . '</div>';
        }

        // Add this at the top of the file, after the first PHP tag
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
                         GROUP BY f.facilityID, f.name, f.category, f.capacity, f.ratePerDay, f.status";
                
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
                            <?php while ($facility = mysqli_fetch_assoc($result)) { 
                                $availabilityClass = strtolower($facility['availability']) === 'available' ? 'w3-text-green' : 'w3-text-red';
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($facility['name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($facility['category'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($facility['capacity'] ?? ''); ?> persons</td>
                                    <td>RM<?php echo htmlspecialchars($facility['ratePerDay'] ?? ''); ?></td>
                                    <td class="<?php echo $availabilityClass; ?> w3-bold">
                                        <?php echo htmlspecialchars($facility['availability'] ?? ''); ?>
                                    </td>
                                    <td>
                                        <?php if ($facility['availability'] === 'Available'): ?>
                                            <button type="button" onclick="openBookingModal('<?php echo htmlspecialchars($facility['facilityID'] ?? ''); ?>', 
                                                '<?php echo htmlspecialchars($facility['name'] ?? ''); ?>', 
                                                '<?php echo htmlspecialchars($facility['ratePerDay'] ?? ''); ?>', 
                                                '<?php echo htmlspecialchars($checkDate); ?>')" 
                                                class="w3-button w3-green w3-round-large w3-small">
                                                <i class="fas fa-calendar-plus"></i> Book Now
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>

        <?php
        // Get facility details if facilityId is provided
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
            <h3><?php echo htmlspecialchars($facilityDetails['name']); ?></h3>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($facilityDetails['category']); ?></p>
            <p><strong>Capacity:</strong> <?php echo htmlspecialchars($facilityDetails['capacity']); ?> persons</p>
            <p><strong>Rate per Day:</strong> RM<?php echo htmlspecialchars($facilityDetails['ratePerDay']); ?></p>
        </div>

        <form action="processBooking.php" method="POST">
            <input type="hidden" name="facilityID" value="<?php echo htmlspecialchars($facilityId); ?>">
            
            <div class="form-group">
                <label for="customerID">Customer ID:</label>
                <div class="input-group">
                    <input type="text" id="customerID" name="customerID" required>
                    <button type="button" onclick="validateCustomer()" class="w3-button w3-blue w3-round" style="margin-left: 10px;">
                        <i class="fas fa-search"></i> Check
                    </button>
                </div>
                <div id="customerInfo" class="w3-panel w3-pale-blue w3-round" style="display: none; margin-top: 10px;">
                    <p id="customerDetails"></p>
                </div>
                <div id="customerError" class="w3-panel w3-pale-red w3-round" style="display: none; margin-top: 10px;">
                    <p><i class="fas fa-exclamation-circle"></i> Customer ID not found.</p>
                </div>
            </div>

            <div class="form-group">
                <label for="reservedBy">Reserved By:</label>
                <input type="text" id="reservedBy" name="reservedBy" required>
            </div>

            <div class="form-group">
                <label for="regNumber">Registration Number:</label>
                <input type="text" id="regNumber" name="regNumber" required>
            </div>

            <div class="date-inputs">
                <div class="form-group">
                    <label for="DateRent_start">Rental Start Date:</label>
                    <input type="text" id="DateRent_start" name="DateRent_start" class="flatpickr" required 
                           value="<?php echo isset($_GET['checkDate']) ? htmlspecialchars($_GET['checkDate']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="DateRent_end">Rental End Date:</label>
                    <input type="text" id="DateRent_end" name="DateRent_end" class="flatpickr" required>
                </div>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <textarea id="purpose" name="purpose" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="submitBooking" class="btn btn-primary">
                    <i class="fas fa-save"></i> Submit Booking
                </button>
                <button type="button" onclick="window.location.href='bookingListForm.php'" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
        <?php else: ?>
            <div class="availability-section">
                <h3><i class="fas fa-info-circle"></i> Facility Booking</h3>
                <p>Please check facility availability using the form above to proceed with booking.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeBookingModal()">&times;</span>
            <h3><i class="fas fa-calendar-plus"></i> Book Facility</h3>
            
            <div class="rental-summary">
                <h4 id="modalFacilityName"></h4>
                <p id="modalRateDisplay"></p>
            </div>

            <form id="bookingForm" action="processBooking.php" method="POST" onsubmit="return validateForm()">
                <input type="hidden" id="modalFacilityId" name="facilityID">
                <input type="hidden" id="regNumber" name="regNumber">
                <input type="hidden" id="rentalPeriodInput" name="RentalPeriod">
                <input type="hidden" id="amountDueInput" name="Amount_Due">
                
                <div class="form-group">
                    <label for="Booking_Ref">Booking Reference:</label>
                    <input type="text" id="Booking_Ref" name="Booking_Ref" required>
                </div>

                <div class="form-group">
                    <label for="customerID">Customer ID:</label>
                    <div class="input-group">
                        <input type="text" id="customerID" name="customerID" required>
                        <button type="button" onclick="validateCustomer()" class="w3-button w3-blue w3-round" style="margin-left: 10px;">
                            <i class="fas fa-search"></i> Check
                        </button>
                    </div>
                    <div id="customerInfo" class="w3-panel w3-pale-blue w3-round" style="display: none; margin-top: 10px;">
                        <p id="customerDetails"></p>
                    </div>
                    <div id="customerError" class="w3-panel w3-pale-red w3-round" style="display: none; margin-top: 10px;">
                        <p><i class="fas fa-exclamation-circle"></i> Customer ID not found.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reservedBy">Reserved By:</label>
                    <input type="text" id="reservedBy" name="Reserved_By" required>
                </div>

                <div class="date-inputs">
                    <div class="form-group">
                        <label for="DateRent_start">Rental Start Date:</label>
                        <input type="text" id="DateRent_start" name="DateRent_start" class="flatpickr" required>
                    </div>

                    <div class="form-group">
                        <label for="DateRent_end">Rental End Date:</label>
                        <input type="text" id="DateRent_end" name="DateRent_end" class="flatpickr" required>
                    </div>
                </div>

                <div class="rental-summary">
                    <p>Rental Period: <span id="rentalPeriod">0</span> days</p>
                    <p>Amount Due: RM<span id="amountDue">0.00</span></p>
                    <p>Registration Number: <span id="displayRegNumber">Will be auto-generated</span></p>
                </div>

                <div class="form-group">
                    <button type="submit" class="w3-button w3-green w3-round">
                        <i class="fas fa-save"></i> Confirm Booking
                    </button>
                    <button type="button" onclick="closeBookingModal()" class="w3-button w3-grey w3-round">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Flatpickr date pickers
        flatpickr(".flatpickr", {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        // Calculate rental period and amount due when dates change
        document.querySelectorAll('.flatpickr').forEach(input => {
            input.addEventListener('change', calculateRental);
        });

        function calculateRental() {
            const startDate = document.getElementById('DateRent_start')?.value;
            const endDate = document.getElementById('DateRent_end')?.value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                // You can add additional logic here to display rental period and calculate amount
            }
        }

        let currentFacilityRate = 0;
        let isValidCustomer = false;

        function validateForm() {
            // Update hidden fields before submission
            const rentalPeriod = document.getElementById('rentalPeriod').textContent;
            const amountDue = document.getElementById('amountDue').textContent;
            document.getElementById('rentalPeriodInput').value = rentalPeriod;
            document.getElementById('amountDueInput').value = amountDue;

            // Check if customer is validated
            if (!isValidCustomer) {
                alert('Please validate the Customer ID first');
                return false;
            }

            // Check if dates are selected
            const startDate = document.getElementById('DateRent_start').value;
            const endDate = document.getElementById('DateRent_end').value;
            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return false;
            }

            // Check if booking reference is provided
            const bookingRef = document.getElementById('Booking_Ref').value;
            if (!bookingRef) {
                alert('Please enter a Booking Reference');
                return false;
            }

            return true;
        }

        function validateCustomer() {
    const customerID = document.getElementById('customerID').value;
    if (!customerID) {
        alert('Please enter a Customer ID');
        return;
    }

    // Create form data
    const formData = new FormData();
    formData.append('action', 'checkCustomer');
    formData.append('customerID', customerID);

    // Send POST request
    fetch('processBooking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        const customerInfo = document.getElementById('customerInfo');
        const customerError = document.getElementById('customerError');
        const reservedByInput = document.getElementById('reservedBy');

        if (result.exists) {
            customerInfo.style.display = 'block';
            customerError.style.display = 'none';
            document.getElementById('customerDetails').innerHTML = `
                <p><strong>Customer Name:</strong> ${result.customerName}</p>
                <p><strong>Contact:</strong> ${result.Contact}</p>
                <p><strong>Email:</strong> ${result.Email}</p>
            `;
            isValidCustomer = true;
            
            // Auto-fill the reserved by field if it exists
            if (reservedByInput) {
                reservedByInput.value = result.customerName;
            }
        } else {
            customerInfo.style.display = 'none';
            customerError.style.display = 'block';
            isValidCustomer = false;
            
            // Clear the reserved by field if it exists
            if (reservedByInput) {
                reservedByInput.value = '';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('customerError').style.display = 'block';
        document.getElementById('customerInfo').style.display = 'none';
        isValidCustomer = false;
        
        // Clear the reserved by field if it exists
        const reservedByInput = document.getElementById('reservedBy');
        if (reservedByInput) {
            reservedByInput.value = '';
        }
    });
}

        function generateRegNumber() {
            return Math.floor(10000000 + Math.random() * 90000000).toString();
        }

        function openBookingModal(facilityId, facilityName, ratePerDay, checkDate) {
            document.getElementById('bookingModal').style.display = 'block';
            document.getElementById('modalFacilityId').value = facilityId;
            document.getElementById('modalFacilityName').textContent = facilityName;
            document.getElementById('modalRateDisplay').textContent = 'Rate per Day: RM' + ratePerDay;
            document.getElementById('DateRent_start').value = checkDate;
            currentFacilityRate = parseFloat(ratePerDay);
            
            // Generate registration number
            const regNum = generateRegNumber();
            document.getElementById('regNumber').value = regNum;
            document.getElementById('displayRegNumber').textContent = regNum;
            
            // Reset customer validation
            isValidCustomer = false;
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('customerInfo').style.display = 'none';
            document.getElementById('customerError').style.display = 'none';
            
            // Initialize date pickers
            initializeDatePickers();
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').style.display = 'none';
        }

        function initializeDatePickers() {
            flatpickr("#DateRent_start", {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: calculateRental
            });

            flatpickr("#DateRent_end", {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: calculateRental
            });
        }

        function calculateRental() {
            const startDate = document.getElementById('DateRent_start').value;
            const endDate = document.getElementById('DateRent_end').value;
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                document.getElementById('rentalPeriod').textContent = diffDays;
                const amountDue = (diffDays * currentFacilityRate).toFixed(2);
                document.getElementById('amountDue').textContent = amountDue;
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeBookingModal();
            }
        }
    </script>
</body>
</html>
