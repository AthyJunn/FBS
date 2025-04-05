<?php
//processCustomer.php
include "customer.php";

//1. Add new customer
if (isset($_POST['addCustomer'])) {
    echo 'Saving new customer record...';
    addCustomer();
    header("refresh:1; url=customerList.php"); // Redirect after adding
}

//2. Delete customer record
else if (isset($_POST['deleteCustomer'])) {
    echo 'Deleting customer...';
    deleteCustomer($_POST['deleteCustomer']);
    header("refresh:1; url=customerList.php"); // Redirect after deleting
}

//3. Update customer info
else if (isset($_POST['saveUpdateButton'])) {
    updateCustomerInfo();
    header("refresh:1; url=customerList.php"); // Redirect after update
}
?> 