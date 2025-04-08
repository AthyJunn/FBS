<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer List</title>
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
            
            .customer-table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .customer-table th {
                background-color: var(--light-color);
                color: var(--dark-color);
                font-weight: 600;
                text-align: left;
                padding: 15px;
                border-bottom: 2px solid #ddd;
            }
            
            .customer-table td {
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
            }
            
            .customer-table tr:last-child td {
                border-bottom: none;
            }
            
            .customer-table tr:hover {
                background-color: #f9f9f9;
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
            
            .customer-count {
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
            
            /* Add Customer Button */
            .add-customer-btn {
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
            
            .add-customer-btn:hover {
                background-color: #27ae60;
            }
            
            /* Contact Info Styles */
            .contact-info {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
            
            .contact-item {
                display: flex;
                align-items: center;
                gap: 5px;
                color: #666;
            }
            
            .contact-item i {
                color: var(--primary-color);
                width: 16px;
            }
            
            /* Responsive Styles */
            @media (max-width: 992px) {
                .customer-table {
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
                
                .add-customer-btn {
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
                    <i class="fas fa-users"></i> Customer List
                </h1>
                <a href="customerInfoForm.php" class="add-customer-btn">
                    <i class="fas fa-user-plus"></i> Add New Customer
                </a>
            </div>
            
            <!-- Search Form -->
            <div class="search-container">
                <form method="GET" action="customerList.php" class="search-form">
                    <input type="text" name="search" placeholder="Search by Name, Email or Phone"
                        value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                        class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>
            </div>
            
            <?php
            include_once "customer.php";
            
            //fetch customer list with search filter
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $customerListQry = getListOfCustomer($search);
            
            if (!$customerListQry) {
                exit("Error: " . mysqli_error($con));
            }
            
            //display number of customer
            $numRows = mysqli_num_rows($customerListQry);
            ?>
            
            <!-- Summary -->
            <div class="summary-container">
                <div class="customer-count">
                    <div class="count-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="count-text">Total customer</div>
                        <div class="count-number"><?php echo $numRows; ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Table -->
            <div class="table-container">
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Payment Method</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 1;
                        
                        //add a row to display for each record
                        while ($row = mysqli_fetch_assoc($customerListQry)) {
                            echo '<tr>';
                                echo '<td>' . $count . '</td>';
                                echo '<td>' . $row['customerID'] . '</td>';
                                echo '<td>' . $row['customerName'] . '</td>';
                                echo '<td>' . $row['Contact'] . '</td>';
                                echo '<td>' . $row['PayMethod'] . '</td>';
                                echo '<td>' . $row['Email'] . '</td>';
                                
                                echo '<td class="action-buttons">
                                        <form method="POST" action="updateCustomer.php" style="display:inline;">
                                            <input type="hidden" name="customerID" value="' . $row['customerID'] . '">
                                            <button type="submit" class="btn btn-update">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                        </form>
                                        <form method="POST" action="processCustomer.php" style="display:inline;" 
                                              onsubmit="return confirm(\'Are you sure you want to delete this customer?\');">
                                            <input type="hidden" name="deleteCustomer" value="' . $row['customerID'] . '">
                                            <button type="submit" class="btn btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                      </td>';
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