<?php
// Database connection
function connectToDatabase() {
    $con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');
    
    // Check connection
    if (!$con) {
        die("Connection Error: " . mysqli_connect_error());
    }
    
    return $con;
}

// Process login form submission
function processLogin($email, $password) {
    $con = connectToDatabase();
    $error = null;
    
    // First check in customer table
    $customerQuery = "SELECT * FROM customer WHERE Email = ?";
    $customerStmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($customerStmt, "s", $email);
    mysqli_stmt_execute($customerStmt);
    $customerResult = mysqli_stmt_get_result($customerStmt);
    
    // Then check in staff table
    $staffQuery = "SELECT * FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);
    
    if (mysqli_num_rows($customerResult) == 1) {
        // User found in customer table
        $user = mysqli_fetch_assoc($customerResult);
        
        // Verify password
        if (password_verify($password, $user['cPassword'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['customerID'] = $user['customerID'];
            $_SESSION['username'] = $user['Email'];
            $_SESSION['userType'] = 'customer';
            
            // Redirect to dashboard
            header("Location: ../MainPage.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } elseif (mysqli_num_rows($staffResult) == 1) {
        // User found in staff table
        $user = mysqli_fetch_assoc($staffResult);
        
        // Verify password
        if (password_verify($password, $user['staffPass'])) {
            // Start session and set session variables
            session_start();
            $_SESSION['staffID'] = $user['staffID'];
            $_SESSION['username'] = $user['staffEmail'];
            $_SESSION['userType'] = $user['userType'];
            
            // Redirect to dashboard
            header("Location: ../MainPage.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Email not found. Please check your email or register.";
    }
    
    return $error;
}

// Check if user is logged in
function isLoggedIn() {
    session_start();
    return isset($_SESSION['userType']);
}

// Check if user is staff
function isStaff() {
    session_start();
    return isset($_SESSION['staffID']);
}

// Check if user is customer
function isCustomer() {
    session_start();
    return isset($_SESSION['customerID']);
}

// Get user type
function getUserType() {
    session_start();
    return isset($_SESSION['userType']) ? $_SESSION['userType'] : null;
}

// Get user ID
function getUserID() {
    session_start();
    if (isset($_SESSION['customerID'])) {
        return $_SESSION['customerID'];
    } elseif (isset($_SESSION['staffID'])) {
        return $_SESSION['staffID'];
    }
    return null;
}

// Get username
function getUsername() {
    session_start();
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

// Check user type by email
function checkUserTypeByEmail($email) {
    $con = connectToDatabase();
    
    // Check in customer table
    $customerQuery = "SELECT customerID FROM customer WHERE Email = ?";
    $customerStmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($customerStmt, "s", $email);
    mysqli_stmt_execute($customerStmt);
    $customerResult = mysqli_stmt_get_result($customerStmt);
    
    if (mysqli_num_rows($customerResult) == 1) {
        return 'customer';
    }
    
    // Check in staff table
    $staffQuery = "SELECT staffID, userType FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);
    
    if (mysqli_num_rows($staffResult) == 1) {
        $staff = mysqli_fetch_assoc($staffResult);
        return $staff['userType'];
    }
    
    return null; // User not found
}

// Get user details by email
function getUserDetailsByEmail($email) {
    $con = connectToDatabase();
    
    // Check in customer table
    $customerQuery = "SELECT customerID, customerName, Email, Contact, PayMethod FROM customer WHERE Email = ?";
    $customerStmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($customerStmt, "s", $email);
    mysqli_stmt_execute($customerStmt);
    $customerResult = mysqli_stmt_get_result($customerStmt);
    
    if (mysqli_num_rows($customerResult) == 1) {
        $user = mysqli_fetch_assoc($customerResult);
        $user['userType'] = 'customer';
        return $user;
    }
    
    // Check in staff table
    $staffQuery = "SELECT staffID, staffName, staffEmail, userType FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);
    
    if (mysqli_num_rows($staffResult) == 1) {
        $user = mysqli_fetch_assoc($staffResult);
        return $user;
    }
    
    return null; // User not found
}
?> 