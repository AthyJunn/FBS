<?php
// Include database connection
include_once("checkLogin.php");

// Connect to database
$con = connectToDatabase();

// The plain text password
$plainPassword = "stafffbs";

// Hash the password
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Update the staff password
$query = "UPDATE staff SET staffPass = ? WHERE staffEmail = 'gojo@fbsstaff.com'";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $hashedPassword);

if (mysqli_stmt_execute($stmt)) {
    echo "Password updated successfully. You can now login with email: gojo@fbsstaff.com and password: gojofbs";
} else {
    echo "Error updating password: " . mysqli_error($con);
}

mysqli_close($con);
?> 