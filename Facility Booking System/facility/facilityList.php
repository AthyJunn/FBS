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
    </body>
</html>

