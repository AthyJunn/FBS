<?php
include_once "../login/checkLogin.php";
$isStaff = isStaff();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facility List</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
                --available-color: #2ecc71;
                --maintenance-color: #f39c12;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f7fa;
                color: #333;
            }
            
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 20px;
            }
            
            /* Header Styles */
            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .page-title {
                font-size: 1.8rem;
                font-weight: bold;
                color: var(--dark-color);
                margin: 0;
                display: flex;
                align-items: center;
            }
            
            .page-title i {
                margin-right: 10px;
                color: var(--primary-color);
            }
            
            /* Search Form Styles */
            .search-container {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .search-form {
                display: flex;
                gap: 10px;
            }
            
            .search-input {
                flex: 1;
                padding: 12px 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
                transition: border-color 0.3s ease;
            }
            
            .search-input:focus {
                border-color: var(--primary-color);
                outline: none;
            }
            
            .search-button {
                background-color: var(--primary-color);
                color: white;
                border: none;
                border-radius: 4px;
                padding: 12px 20px;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .search-button:hover {
                background-color: var(--secondary-color);
            }
            
            /* Table Styles */
            .table-container {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                overflow: hidden;
                margin-bottom: 20px;
            }
            
            .facility-table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .facility-table th {
                background-color: var(--light-color);
                color: var(--dark-color);
                font-weight: 600;
                text-align: left;
                padding: 15px;
                border-bottom: 2px solid #ddd;
            }
            
            .facility-table td {
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
            }
            
            .facility-table tr:last-child td {
                border-bottom: none;
            }
            
            .facility-table tr:hover {
                background-color: #f9f9f9;
            }
            
            /* Status Badge Styles */
            .status-badge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 500;
            }
            
            .status-available {
                background-color: rgba(46, 204, 113, 0.2);
                color: var(--available-color);
            }
            
            .status-maintenance {
                background-color: rgba(243, 156, 18, 0.2);
                color: var(--maintenance-color);
            }
            
            .status-unavailable {
                background-color: rgb(255, 185, 185);
                color: rgb(255, 25, 0);
            }
            
            /* Action Button Styles */
            .action-buttons {
                display: flex;
                gap: 8px;
            }
            
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 0.9rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                border: none;
                text-decoration: none;
                gap: 5px;
            }
            
            .btn-update {
                background-color: var(--primary-color);
                color: white;
            }
            
            .btn-update:hover {
                background-color: var(--secondary-color);
            }
            
            .btn-delete {
                background-color: var(--danger-color);
                color: white;
            }
            
            .btn-delete:hover {
                background-color: #c0392b;
            }
            
            /* Summary Styles */
            .summary-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .facility-count {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 15px 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .count-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: rgba(52, 152, 219, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                font-size: 1.2rem;
            }
            
            .count-text {
                font-size: 1rem;
                font-weight: 500;
            }
            
            .count-number {
                font-size: 1.5rem;
                font-weight: bold;
                color: var(--primary-color);
            }
            
            /* Add Facility Button */
            .add-facility-btn {
                background-color: var(--success-color);
                color: white;
                border: none;
                border-radius: 4px;
                padding: 12px 20px;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                display: flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
            }
            
            .add-facility-btn:hover {
                background-color: #27ae60;
            }
            
            /* Responsive Styles */
            @media (max-width: 992px) {
                .facility-table {
                    display: block;
                    overflow-x: auto;
                }
                
                .search-form {
                    flex-direction: column;
                }
                
                .search-button {
                    width: 100%;
                }
                
                .summary-container {
                    flex-direction: column;
                    gap: 15px;
                }
                
                .add-facility-btn {
                    width: 100%;
                    justify-content: center;
                }
            }

            /* Modal Styles */
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

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: var(--dark-color);
            }

            .form-group input[type="text"],
            .form-group textarea {
                width: 100%;
                padding: 12px;
                border: 2px solid var(--border-color);
                border-radius: 8px;
                font-size: 16px;
                transition: all 0.3s ease;
            }

            .form-group input[type="text"]:focus,
            .form-group textarea:focus {
                border-color: var(--primary-color);
                outline: none;
                box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
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
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-building"></i> Facility List
                </h1>
                <?php if ($isStaff): ?>
                <a href="facilityInfoForm.php" class="add-facility-btn">
                    <i class="fas fa-plus"></i> Add New Facility
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Search Form -->
            <div class="search-container">
                <form method="GET" action="facilityList.php" class="search-form">
                <input type="text" name="search" placeholder="Search by Name or Category"
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                        class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>

<?php
include_once "facility.php";

//fetch facility list with search filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$facilityListQry = getListOfFacility($search);

if (!$facilityListQry) {
    exit("Error: " . mysqli_error($con));
}

//display number of facilities
$numRows = mysqli_num_rows($facilityListQry);
            ?>
            
            <!-- Summary -->
            <div class="summary-container">
                <div class="facility-count">
                    <div class="count-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="count-text">Total Facilities</div>
                        <div class="count-number"><?php echo $numRows; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Facility Table -->
            <div class="table-container">
                <table class="facility-table">
                    <thead>
                        <tr>
            <th>No</th>
            <th>Facility ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Capacity</th>
            <th>Detail</th>
            <th>Rate Per Day</th>
            <th>Status</th>
            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$count = 1;

//add a row to display for each record
while ($row = mysqli_fetch_assoc($facilityListQry)) {
                            $statusClass = 'status-maintenance'; // default
                            if ($row['status'] == 'Available') {
                                $statusClass = 'status-available';
                            } else if ($row['status'] == 'Unavailable') {
                                $statusClass = 'status-unavailable';
                            }
                            
    echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $row['facilityId'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['category'] . '</td>';
        echo '<td>' . $row['capacity'] . '</td>';
        echo '<td>' . $row['facilityDetail'] . '</td>';
                                echo '<td>$' . number_format($row['ratePerDay'], 2) . '</td>';
                                echo '<td><span class="status-badge ' . $statusClass . '">' . $row['status'] . '</span></td>';

                                echo '<td class="action-buttons">';
                                if ($isStaff) {
                                    echo '<form method="POST" action="updateFacilityForm.php" style="display:inline;">
                    <input type="hidden" name="facilityId" value="' . $row['facilityId'] . '">
                                            <button type="submit" class="btn btn-update">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                </form>
                <form method="POST" action="processFacility.php" style="display:inline;" 
                      onsubmit="return confirm(\'Are you sure you want to delete this facility?\');">
                    <input type="hidden" name="deleteFacility" value="' . $row['facilityId'] . '">
                                            <button type="submit" class="btn btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>';
                                } else if ($row['status'] == 'Available') {
                                    echo '<button onclick="openBookingModal(\'' . $row['facilityId'] . '\', \'' . 
                                         htmlspecialchars($row['name']) . '\', \'' . $row['ratePerDay'] . '\')" class="btn btn-update">
                                            <i class="fas fa-calendar-plus"></i> Book Now
                                          </button>';
                                }
                                echo '</td>';
    echo '</tr>';
    $count++;
}
                        ?>
                    </tbody>
                </table>
            </div>
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

                <form id="bookingForm" action="../rental/processBooking.php" method="POST">
                    <input type="hidden" id="modalFacilityId" name="facilityID">
                    <input type="hidden" name="submitBooking" value="1">
                    
                    <div class="form-group">
                        <label for="modalCustomerId">Customer ID:</label>
                        <input type="text" id="modalCustomerId" name="customerID" 
                               value="<?= htmlspecialchars($_SESSION['customerID'] ?? '') ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="modalStartDate">Rental Start Date:</label>
                        <input type="text" id="modalStartDate" name="DateRent_start" class="flatpickr" required>
                    </div>

                    <div class="form-group">
                        <label for="modalEndDate">Rental End Date:</label>
                        <input type="text" id="modalEndDate" name="DateRent_end" class="flatpickr" required>
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
                        <button type="submit" name="submitBooking" class="btn btn-update">
                            <i class="fas fa-save"></i> Confirm Booking
                        </button>
                        <button type="button" onclick="closeBookingModal()" class="btn btn-delete">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Initialize date pickers
            flatpickr(".flatpickr", {
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
                console.log("Opening modal for facility:", facilityId); // Debug line
                document.getElementById('modalFacilityId').value = facilityId;
                document.getElementById('modalFacilityName').textContent = facilityName;
                document.getElementById('modalRatePerDay').textContent = ratePerDay;
                currentRatePerDay = parseFloat(ratePerDay);
                
                // Reset form
                document.getElementById('bookingForm').reset();
                // Don't reset the facility ID when resetting the form
                document.getElementById('modalFacilityId').value = facilityId;
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

