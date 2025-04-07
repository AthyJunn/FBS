<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Booking Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Facility Booking Form</h2>
        <form method="POST" action="processRentFacility.php" id="bookingForm">
            <div class="form-group">
                <label for="bookingID">Booking ID:</label>
                <input type="text" id="bookingID" name="bookingID" required>
                <div class="error" id="bookingIDError">Please enter a valid booking ID</div>
            </div>

            <div class="form-group">
                <label for="customerID">Customer ID:</label>
                <input type="text" id="customerID" name="customerID" required>
                <div class="error" id="customerIDError">Please enter a valid customer ID</div>
            </div>

            <div class="form-group">
                <label for="facilityID">Facility ID:</label>
                <input type="text" id="facilityID" name="facilityID" required>
                <div class="error" id="facilityIDError">Please enter a valid facility ID</div>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
                <div class="error" id="dateError">Please select a valid date</div>
            </div>

            <div class="form-group">
                <label for="timeSlot">Time Slot:</label>
                <input type="time" id="timeSlot" name="timeSlot" required>
                <div class="error" id="timeSlotError">Please select a valid time slot</div>
            </div>

            <button type="submit" class="submit-btn">Book Facility</button>
        </form>
    </div>

    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset all error messages
            document.querySelectorAll('.error').forEach(error => error.style.display = 'none');
            
            // Validate Booking ID
            if (!document.getElementById('bookingID').value.trim()) {
                document.getElementById('bookingIDError').style.display = 'block';
                isValid = false;
            }
            
            // Validate Customer ID
            if (!document.getElementById('customerID').value.trim()) {
                document.getElementById('customerIDError').style.display = 'block';
                isValid = false;
            }
            
            // Validate Facility ID
            if (!document.getElementById('facilityID').value.trim()) {
                document.getElementById('facilityIDError').style.display = 'block';
                isValid = false;
            }
            
            // Validate Date
            if (!document.getElementById('date').value) {
                document.getElementById('dateError').style.display = 'block';
                isValid = false;
            }
            
            // Validate Time Slot
            if (!document.getElementById('timeSlot').value) {
                document.getElementById('timeSlotError').style.display = 'block';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
