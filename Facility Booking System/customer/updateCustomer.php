<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer</title>
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
            max-width: 800px;
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
        
        /* Form Styles */
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            min-height: 100px;
            resize: vertical;
        }
        
        .form-textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
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
        
        .btn-secondary {
            background-color: var(--light-color);
            color: var(--dark-color);
        }
        
        .btn-secondary:hover {
            background-color: #ddd;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-actions {
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
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user-edit"></i> Update Customer Information
            </h1>
        </div>
        
        <?php
        include_once "customer.php";
        
        // Check if customer ID is provided
        if (isset($_POST['customerId'])) {
            $customerId = $_POST['customerId'];
            $customerInfo = getCustomerInformation($customerId);
            
            if ($customerInfo) {
                $customerID = $customerInfo['customerID'];
                $customerName = $customerInfo['customerName'];
                $email = $customerInfo['Email'];
                $contact = $customerInfo['Contact'];
                $address = $customerInfo['Address'];
                $state = $customerInfo['State'];
                $postCode = $customerInfo['PostCode'];
                $payMethod = $customerInfo['PayMethod'];
                $sex = $customerInfo['Sex'];
                ?>
                
                <!-- Customer Form -->
                <div class="form-container">
                    <form action="processCustomer.php" method="POST">
                        <div class="form-group">
                            <label for="customerID" class="form-label">Customer ID</label>
                            <input type="text" id="customerID" name="customerID" class="form-input" 
                                   value="<?php echo $customerID; ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" id="customerName" name="customerName" class="form-input" 
                                   value="<?php echo $customerName; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Email" class="form-label">Email Address</label>
                            <input type="email" id="Email" name="Email" class="form-input" 
                                   value="<?php echo $email; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Contact" class="form-label">Phone Number</label>
                            <input type="tel" id="Contact" name="Contact" class="form-input" 
                                   value="<?php echo $contact; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Address" class="form-label">Address</label>
                            <textarea id="Address" name="Address" class="form-textarea" required><?php echo $address; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="State" class="form-label">State</label>
                            <input type="text" id="State" name="State" class="form-input" 
                                   value="<?php echo $state; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="PostCode" class="form-label">Post Code</label>
                            <input type="text" id="PostCode" name="PostCode" class="form-input" 
                                   value="<?php echo $postCode; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="PayMethod" class="form-label">Payment Method</label>
                            <select id="PayMethod" name="PayMethod" class="form-input" required>
                                <option value="Cash" <?php echo ($payMethod == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="Credit Card" <?php echo ($payMethod == 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
                                <option value="Bank Transfer" <?php echo ($payMethod == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="Sex" class="form-label">Sex</label>
                            <select id="Sex" name="Sex" class="form-input" required>
                                <option value="Male" <?php echo ($sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($sex == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($sex == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <a href="customerList.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" name="saveUpdateButton" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                <?php
            } else {
                echo '<div class="w3-panel w3-red">
                        <h3>Error</h3>
                        <p>Customer not found. Please return to the customer list.</p>
                        <p><a href="customerList.php" class="w3-button w3-white w3-border">Return to Customer List</a></p>
                      </div>';
            }
        } else {
            echo '<div class="w3-panel w3-red">
                    <h3>Error</h3>
                    <p>No customer ID provided. Please return to the customer list.</p>
                    <p><a href="customerList.php" class="w3-button w3-white w3-border">Return to Customer List</a></p>
                  </div>';
        }
        ?>
    </div>
</body>
</html> 