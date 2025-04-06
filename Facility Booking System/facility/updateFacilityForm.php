<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Facility Information</title>
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
            --unavailable-color:rgb(255, 25, 0);
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
            color: var(--success-color);
        }
        
        .status-maintenance {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }
        
        .status-unavailable {
            background-color:  rgb(255, 185, 185);
            color: var(--unavailable-color);
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
                <i class="fas fa-building"></i> Update Facility Information
            </h1>
        </div>
        
        <?php
        include "facility.php";

        if (isset($_POST['facilityId'])) {
            $facilityId = $_POST['facilityId'];
            $qry = getFacilityInfoByID($facilityId);

            if ($qry) {
                $facilityRecord = mysqli_fetch_assoc($qry);
                $facID = $facilityRecord['facilityId'];
                $name = $facilityRecord['name'];
                $category = $facilityRecord['category'];
                $capacity = $facilityRecord['capacity'];
                $rate = $facilityRecord['ratePerDay'];
                $status = $facilityRecord['status'];
                $detail = isset($facilityRecord['facilityDetail']) ? $facilityRecord['facilityDetail'] : '';
                ?>
                
                <!-- Facility Form -->
                <div class="form-container">
                    <form action="processFacility.php" method="POST">
                        <div class="form-group">
                            <label for="facID" class="form-label">Facility ID</label>
                            <input type="text" id="facID" name="facID" value="<?php echo $facID; ?>" class="form-input" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Facility Name</label>
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" id="category" name="category" value="<?php echo $category; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cap" class="form-label">Capacity</label>
                            <input type="number" id="cap" name="cap" value="<?php echo $capacity; ?>" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="detail" class="form-label">Facility Detail</label>
                            <textarea id="detail" name="detail" class="form-textarea"><?php echo $detail; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="rate" class="form-label">Rate Per Day</label>
                            <input type="number" id="rate" name="rate" value="<?php echo $rate; ?>" step="0.01" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="Available" <?php echo ($status == 'Available') ? 'selected' : ''; ?>>Available</option>
                                <option value="Under Maintenance" <?php echo ($status == 'Under Maintenance') ? 'selected' : ''; ?>>Under Maintenance</option>
                                <option value="Unavailable" <?php echo ($status == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <a href="facilityList.php" class="btn btn-secondary">
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
                    <i class="fas fa-exclamation-circle"></i> Facility not found.
                </div>
                <div class="form-actions">
                    <a href="facilityList.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Facility List
                    </a>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> No facility ID provided.
            </div>
            <div class="form-actions">
                <a href="facilityList.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Facility List
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>



