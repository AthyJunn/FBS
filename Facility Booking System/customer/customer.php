<?php

function getCustomerInformation($Cust_no)
{
//create connection
$con=mysqli_connect("localhost","web2025","web2025","facilitydb");
if(!$con)
	{
	echo  mysqli_connect_error(); 
	exit;
	}
$sql = "select * from customer where customerID = '".$Cust_no."'";
$qry = mysqli_query($con,$sql);//run query
if(mysqli_num_rows($qry) == 1)
	{
	$row=mysqli_fetch_assoc($qry);
	return $row;
	}
else
	return false;
}

//Function to get list of all customer with optional search filter
function getListOfCustomer($searchQuery = "") {
    //create connection
    $con = mysqli_connect("localhost", "web2025", "web2025", "facilitydb");
    if (!$con) {
        echo mysqli_connect_error();
        exit;
    }
    
    $sql = "SELECT * FROM customer";
    
    //Apply search filter if keyword exists
    if (!empty($searchQuery)) {
        $sql .= " WHERE customerName LIKE '%$searchQuery%' OR Email LIKE '%$searchQuery%' OR Contact LIKE '%$searchQuery%'";
    }
    
    return mysqli_query($con, $sql);
}

//Function to add new customer
function addCustomer() {
    //create connection
    $con = mysqli_connect("localhost", "web2025", "web2025", "facilitydb");
    if (!$con) {
        echo mysqli_connect_error();
        exit;
    }

    // Generate a unique customerID
    $sql = "SELECT MAX(CAST(SUBSTRING(customerID, 2) AS UNSIGNED)) as max_id FROM customer";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_id = $row['max_id'] + 1;
    $customerID = 'C' . str_pad($next_id, 4, '0', STR_PAD_LEFT);

    $customerName = $_POST['customerName'];
    $Email = $_POST['Email'];
    $Contact = $_POST['Contact'];
    $Address = $_POST['Address'];
    $State = $_POST['State'];
    $PostCode = $_POST['PostCode'];
    $PayMethod = $_POST['PayMethod'];
    $Sex = $_POST['Sex'];
    
    $sql = "INSERT INTO customer (customerID, customerName, Email, Contact, Address, State, PostCode, PayMethod, Sex) 
            VALUES ('$customerID', '$customerName', '$Email', '$Contact', '$Address', '$State', '$PostCode', '$PayMethod', '$Sex')";
    
    if (mysqli_query($con, $sql)) {
        echo "New customer added successfully.";
    } else {
        echo "Error adding customer: " . mysqli_error($con);
    }
}

//Function to delete customer
function deleteCustomer($customerID) {
    //create connection
    $con = mysqli_connect("localhost", "web2025", "web2025", "facilitydb");
    if (!$con) {
        echo mysqli_connect_error();
        exit;
    }
    
    $sql = "DELETE FROM customer WHERE customerID = '$customerID'";
    
    if (mysqli_query($con, $sql)) {
        echo "Customer deleted successfully.";
    } else {
        echo "Error deleting customer: " . mysqli_error($con);
    }
}

//Function to update customer information
function updateCustomerInfo() {
    //create connection
    $con = mysqli_connect("localhost", "web2025", "web2025", "facilitydb");
    if (!$con) {
        echo mysqli_connect_error();
        exit;
    }
    
    $customerID = $_POST['customerID'];
    $customerName = $_POST['customerName'];
    $Address = $_POST['Address'];
    $State = $_POST['State'];
    $PostCode = $_POST['PostCode'];
    $Contact = $_POST['Contact'];
    $PayMethod = $_POST['PayMethod'];
    $Email = $_POST['Email'];
    $Sex = $_POST['Sex'];
    
    $sql = "UPDATE customer
            SET customerName = '$customerName', Address = '$Address', State = '$State', PostCode = '$PostCode', Contact = '$Contact', PayMethod = '$PayMethod', Email = '$Email', Sex = '$Sex'
            WHERE customerID = '$customerID'";
    
    if (mysqli_query($con, $sql)) {
        echo "Customer updated successfully.";
    } else {
        echo "Error updating customer: " . mysqli_error($con);
    }
}

?>