<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Customer Information</title>
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
            text-align: center;
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
        
        .form-input[readonly] {
            background-color: #f9f9f9;
            cursor: not-allowed;
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
        
        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
            background-color: white;
        }
        
        .form-select:focus {
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
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        include "customer.php";

        if (isset($_POST['customerID'])) {
            $customerID = $_POST['customerID'];
            $customerInfo = getCustomerInformation($customerID);

            if ($customerInfo) {
                $customerID = $customerInfo['customerID'];
                $name = $customerInfo['customerName'];
                $email = $customerInfo['Email'];
                $phone = $customerInfo['Contact'];
                $address = $customerInfo['Address'];
                $state = $customerInfo['State'];
                $postCode = $customerInfo['PostCode'];
                $PayMethod = $customerInfo['PayMethod'];
                $Sex = $customerInfo['Sex'];
                ?>
                
                <!-- Customer Form -->
                <div class="form-container">
                    <form action="processCustomer.php" method="POST">
                        <div class="form-group">
                            <label for="customerID" class="form-label">Customer ID</label>
                            <input type="text" id="customerID" name="customerID" value="<?php echo $customerID; ?>" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="customerName" class="form-label">Customer Name</label>
                            <input type="text" id="customerName" name="customerName" value="<?php echo $name; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Email" class="form-label">Email Address</label>
                            <input type="email" id="Email" name="Email" value="<?php echo $email; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Contact" class="form-label">Phone Number</label>
                            <input type="tel" id="Contact" name="Contact" value="<?php echo $phone; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="Address" class="form-label">Address</label>
                            <textarea id="Address" name="Address" class="form-textarea" required><?php echo $address; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="State" class="form-label">State</label>
                            <input type="text" id="State" name="State" value="<?php echo $state; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="PostCode" class="form-label">Post Code</label>
                            <input type="text" id="PostCode" name="PostCode" value="<?php echo $postCode; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="PayMethod" class="form-label">Payment Method</label>
                            <select id="PayMethod" name="PayMethod" class="form-select" required>
                                <option value="">Select Payment Method</option>
                                <option value="Cash" <?php echo ($PayMethod == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="Credit/Debit Card" <?php echo ($PayMethod == 'Credit/Debit Card') ? 'selected' : ''; ?>>Credit/Debit Card</option>
                                <option value="Bank Transfer" <?php echo ($PayMethod == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="Sex" class="form-label">Sex</label>
                            <select id="Sex" name="Sex" class="form-select" required>
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo ($Sex == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($Sex == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($Sex == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <a href="customerList.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" name="saveUpdateButton" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Customer not found.
                </div>
                <div class="form-actions">
                    <a href="customerList.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Customer List
                    </a>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> No customer ID provided.
            </div>
            <div class="form-actions">
                <a href="customerList.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Customer List
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>