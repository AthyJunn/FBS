<?php
//processFacility.php
include "facility.php";

print_r($_POST);

//1. Add new facility
if (isset($_POST['addFacility'])) {
    echo 'Saving new facility record...';
    addFacility();
}

//2. Delete facility record
else if (isset($_POST['deleteFacility'])) {  // Fixed: Use POST instead of GET
    echo 'Deleting facility...';
    deleteFacility($_POST['deleteFacility']);
}

//3. Update facility info
else if (isset($_POST['saveUpdateButton'])) { // Fixed: Corrected variable name
    updateFacilityInfo();
    header("refresh:1; url=facilityList.php"); // Redirect after update
}
?>
