<!DOCTYPE html>
<html>
    <head>
        <title>Facility List</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
        <h1>Facility List</h1>

        <form method="GET" action="facilityList.php" class="w3-row">
            <div class="w3-col" style="width: 70%;">
                <input type="text" name="search" placeholder="Search by Name or Category"
                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" 
                    class="w3-input w3-border">
            </div>
            <div class="w3-col" style="width: 30%; padding-left: 10px;">
                <button type="submit" class="w3-button w3-blue">Search</button>
            </div>
        </form>



<?php
include_once "facility.php";

//fetch facility list with search filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$facilityListQry = getListOfFacility($search);

if (!$facilityListQry) {
    exit("Error: " . mysqli_error($con));
}

//display number of facilities
$numRows = mysqli_num_rows($facilityListQry);
echo "<br>Number of Facilities: " . $numRows;

//display table header
echo '<table class="w3-table w3-striped">';
    echo '<tr>
            <th>No</th>
            <th>Facility ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Capacity</th>
            <th>Detail</th>
            <th>Rate Per Day</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>';

$count = 1;

//add a row to display for each record
while ($row = mysqli_fetch_assoc($facilityListQry)) {
    echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $row['facilityId'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['category'] . '</td>';
        echo '<td>' . $row['capacity'] . '</td>';
        echo '<td>' . $row['facilityDetail'] . '</td>';
        echo '<td>' . $row['ratePerDay'] . '</td>';
        echo '<td>' . $row['status'] . '</td>';

        echo '<td>
                <form method="POST" action="updateFacilityForm.php" style="display:inline;">
                    <input type="hidden" name="facilityId" value="' . $row['facilityId'] . '">
                    <button type="submit" class="w3-button w3-blue">Update</button>
                </form>
                <form method="POST" action="processFacility.php" style="display:inline;" 
                      onsubmit="return confirm(\'Are you sure you want to delete this facility?\');">
                    <input type="hidden" name="deleteFacility" value="' . $row['facilityId'] . '">
                    <button type="submit" class="w3-button w3-red">Delete</button>
                </form>
              </td>';
    echo '</tr>';
    $count++;
}
echo '</table>';
?>

