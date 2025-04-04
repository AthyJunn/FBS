<?php
//Connect to database
$con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');

//Check connection
if (!$con) {
    die("Connection Error: " . mysqli_connect_error());
}

//Fetch facility list with optional search filter
function getListOfFacility($searchQuery = ""){
    global $con;
    $sql = "SELECT * FROM facility";

    //Apply search filter if keyword exists
    if (!empty($searchQuery)) {
        $sql .= " WHERE name LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%'";
    }

    return mysqli_query($con, $sql);
}

//Add new facility
function addFacility(){
    global $con;

    $facilityId = $_POST['facID'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $capacity = $_POST['cap'];
    $rate = $_POST['rate'];
    $status = $_POST['status'];
    $facilityDetail = isset($_POST['detail']) ? $_POST['detail'] : ''; 

    $sql = "INSERT INTO facility (facilityId, name, category, capacity, ratePerDay, status, facilityDetail) 
            VALUES ('$facilityId', '$name', '$category', '$capacity', '$rate', '$status', '$facilityDetail')";

    if (mysqli_query($con, $sql)) {
        echo "New facility added successfully.";
    } else {
        echo "Error adding facility: " . mysqli_error($con);
    }
}


//Delete facility
function deleteFacility($facilityId){
    global $con;
    $sql = "DELETE FROM facility WHERE facilityId = '$facilityId'";

    if (mysqli_query($con, $sql)) {
        echo "Facility deleted successfully.";
    } else {
        echo "Error deleting facility: " . mysqli_error($con);
    }
}

//Update facility info
function updateFacilityInfo(){
    global $con;

    $facilityId = $_POST['facID'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $capacity = $_POST['cap'];
    $facilityDetail = $_POST['detail'];
    $rate = $_POST['rate'];
    $status = $_POST['status'];

    $sql = "UPDATE facility
            SET name = '$name', category = '$category', capacity = '$capacity', facilityDetail = '$facilityDetail',
                        ratePerDay = '$rate', status = '$status' 
            WHERE facilityId = '$facilityId'";

    if (mysqli_query($con, $sql)) {
        echo "Facility updated successfully.";
    } else {
        echo "Error updating facility: " . mysqli_error($con);
    }
}

//Get facility by ID
function getFacilityInfoByID($facilityId) {
    global $con;
    $sql = "SELECT * FROM facility WHERE facilityId = '$facilityId'";  
    $result = mysqli_query($con, $sql);

    if (!$result) {
        echo "Query Error: " . mysqli_error($con);
        return false;  // Return false if there's an SQL error
    }

    if (mysqli_num_rows($result) == 0) {
        return false;  // Return false if no record found
    }

    return $result;
}
?>
