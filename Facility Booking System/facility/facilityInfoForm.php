<!DOCTYPE html>
<html>
    <head>
        <title>Facility Management</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>
        <h1>Add New Facility</h1>        

        <form action="processFacility.php" method="POST">
            <label for="facID">Facility ID: </label>
            <input type="text" id="facID" name="facID" required>
            <br><br>

            <label for="name">Facility Name: </label>
            <input type="text" id="name" name="name" required>
            <br><br>

            <label for="category">Category: </label>
            <input type="text" id="category" name="category" required>
            <br><br>

            <label for="cap">Capacity: </label>
            <input type="number" id="cap" name="cap" required>
            <br><br>

            <label for="detail">Facility Detail: </label>
            <input type="text" id="detail" name="detail" required>
            <br><br>

            <label for="rate">Rate Per Day: </label>
            <input type="number" id="rate" name="rate" step="0.01" required>
            <br><br>
            
            <label for="status">Status: </label>
            <select id="status" name="status">
                <option value="Available">Available</option>
                <option value="Under Maintenance">Under Maintenance</option>
            </select>
            <br><br>
            
            <input type="submit" name="addFacility" value="Add Facility" class="w3-button w3-green">
            
        </form>
            
    </body>
</html>

