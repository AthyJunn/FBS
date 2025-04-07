<?php
include 'rentFacility.php';

$customerID = $_GET['customerID'];
$result = getFacilitiesByCustomer($customerID);

echo "<h2>Facilities rented by Customer ID: $customerID</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<p>{$row['name']} - {$row['category']} ({$row['ratePerDay']} RM/day)</p>";
}
?>
