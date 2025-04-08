<?php
// Start session safely
function ensureSessionStarted() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Database connection
function connectToDatabase() {
    $con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');

    if (!$con) {
        die("Connection Error: " . mysqli_connect_error());
    }

    return $con;
}

// Process login form submission
function processLogin($email, $password) {
    $con = connectToDatabase();
    $error = null;

    // Check in customer table
    $customerQuery = "SELECT * FROM customer WHERE Email = ?";
    $customerStmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($customerStmt, "s", $email);
    mysqli_stmt_execute($customerStmt);
    $customerResult = mysqli_stmt_get_result($customerStmt);

    // Check in staff table
    $staffQuery = "SELECT * FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);

    if (mysqli_num_rows($customerResult) == 1) {
        $user = mysqli_fetch_assoc($customerResult);
        if (password_verify($password, $user['cPassword'])) {
            ensureSessionStarted();
            $_SESSION['customerID'] = $user['customerID'];
            $_SESSION['username'] = $user['Email'];
            $_SESSION['userType'] = 'customer';

            header("Location: MainPage.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } elseif (mysqli_num_rows($staffResult) == 1) {
        $user = mysqli_fetch_assoc($staffResult);
        if (password_verify($password, $user['staffPass'])) {
            ensureSessionStarted();
            $_SESSION['staffID'] = $user['staffID'];
            $_SESSION['username'] = $user['staffEmail'];
            $_SESSION['userType'] = $user['userType'];

            header("Location: MainPage.php");
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
    ensureSessionStarted();
    return isset($_SESSION['userType']);
}

// Check if user is staff
function isStaff() {
    ensureSessionStarted();
    return isset($_SESSION['staffID']);
}

// Check if user is customer
function isCustomer() {
    ensureSessionStarted();
    return isset($_SESSION['customerID']);
}

// Get user type
function getUserType() {
    ensureSessionStarted();
    return $_SESSION['userType'] ?? null;
}

// Get user ID
function getUserID() {
    ensureSessionStarted();
    return $_SESSION['customerID'] ?? $_SESSION['staffID'] ?? null;
}

// Get username
function getUsername() {
    ensureSessionStarted();
    return $_SESSION['username'] ?? null;
}

// Check user type by email
function checkUserTypeByEmail($email) {
    $con = connectToDatabase();

    $customerQuery = "SELECT customerID FROM customer WHERE Email = ?";
    $customerStmt = mysqli_prepare($con, $customerQuery);
    mysqli_stmt_bind_param($customerStmt, "s", $email);
    mysqli_stmt_execute($customerStmt);
    $customerResult = mysqli_stmt_get_result($customerStmt);

    if (mysqli_num_rows($customerResult) == 1) {
        return 'customer';
    }

    $staffQuery = "SELECT staffID, userType FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);

    if (mysqli_num_rows($staffResult) == 1) {
        $staff = mysqli_fetch_assoc($staffResult);
        return $staff['userType'];
    }

    return null;
}

// Get user details by email
function getUserDetailsByEmail($email) {
    $con = connectToDatabase();

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

    $staffQuery = "SELECT staffID, staffName, staffEmail, userType FROM staff WHERE staffEmail = ?";
    $staffStmt = mysqli_prepare($con, $staffQuery);
    mysqli_stmt_bind_param($staffStmt, "s", $email);
    mysqli_stmt_execute($staffStmt);
    $staffResult = mysqli_stmt_get_result($staffStmt);

    if (mysqli_num_rows($staffResult) == 1) {
        return mysqli_fetch_assoc($staffResult);
    }

    return null;
}
?>
