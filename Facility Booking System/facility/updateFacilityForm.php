<!DOCTYPE html>
<html>
<head>
    <title>Update Facility</title>
</head>
<body>
    <div>
        <h1>Update Facility Information</h1>
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

                echo '<form action="processFacility.php" method="POST">';
                echo "<label>Facility ID:</label><br>";
                echo "<input type='text' name='facID' value='$facID' readonly><br>";

                echo "<label>Name:</label><br>";
                echo "<input type='text' name='name' value='$name'><br>";

                echo "<label>Category:</label><br>";
                echo "<input type='text' name='category' value='$category'><br>";

                echo "<label>Capacity:</label><br>";
                echo "<input type='number' name='cap' value='$capacity'><br>";

                echo "<label>Facility Detail:</label><br>";
                echo "<input type='text' name='detail' value='$detail'><br>"; // Corrected line

                echo "<label>Rate Per Day:</label><br>";
                echo "<input type='number' step='0.01' name='rate' value='$rate'><br>";

                echo "<label>Status:</label><br>";
                echo "<select name='status'>";
                echo $status == 'Available' ? "<option value='Available' selected>Available</option>" : "<option value='Available'>Available</option>";
                echo $status == 'Under Maintenance' ? "<option value='Under Maintenance' selected>Under Maintenance</option>" : "<option value='Under Maintenance'>Under Maintenance</option>";
                echo "</select><br>";

                echo '<br><input type="submit" name="saveUpdateButton" value="Save">';
                echo '</form>';
            } else {
                echo "<p>Facility not found.</p>";
            }
        } else {
            echo "<p>No facility ID provided.</p>";
        }
        ?>
    </div>
</body>
</html>



