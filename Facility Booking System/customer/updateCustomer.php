<!DOCTYPE html>
<html>
<head>
    <title>Update Customer</title>
</head>
<body>
    <div>
        <h1>Update Customer Information</h1>
        <?php
        include "customer.php";

        if (isset($_POST['customerID'])) {
            $customerID = $_POST['customerID'];
            $customerInfo = getCustomerInformation($customerID);

            if ($customerInfo) {
                $customerID = $customerInfo['customerID'];
                $name = $customerInfo['customerName'];
                $email = $customerInfo['Email'];
                $phone = $customerInfo['Contact'];
                $address = $customerInfo['Address'];
                $state = $customerInfo['State'];
                $postCode = $customerInfo['PostCode'];
                $PayMethod = $customerInfo['PayMethod'];
                $Sex = $customerInfo['Sex'];

                echo '<form action="processCustomer.php" method="POST">';
                echo "<label>Customer ID:</label><br>";
                echo "<input type='text' name='customerID' value='$customerID' readonly><br>";

                echo "<label>Name:</label><br>";
                echo "<input type='text' name='customerName' value='$name'><br>";

                echo "<label>Email:</label><br>";
                echo "<input type='text' name='Email' value='$email'><br>";

                echo "<label>Phone:</label><br>";
                echo "<input type='text' name='Contact' value='$phone'><br>";

                echo "<label>Address:</label><br>";
                echo "<input type='text' name='Address' value='$address'><br>";

                echo "<label>State:</label><br>";
                echo "<input type='text' name='State' value='$state'><br>";
                
                echo "<label>Post Code:</label><br>";
                echo "<input type='text' name='PostCode' value='$postCode'><br>";

                echo "<label>Payment Method:</label><br>";
                echo "<select name='PayMethod'>";
                echo $PayMethod == 'Cash' ? "<option value='Cash' selected>Cash</option>" : "<option value='Cash'>Cash</option>";
                echo $PayMethod == 'Credit Card' ? "<option value='Credit Card' selected>Credit Card</option>" : "<option value='Credit Card'>Credit Card</option>";
                echo $PayMethod == 'Bank Transfer' ? "<option value='Bank Transfer' selected>Bank Transfer</option>" : "<option value='Bank Transfer'>Bank Transfer</option>";
                echo "</select><br>";

                echo "<label>Sex:</label><br>";
                echo "<select name='Sex'>";
                echo $Sex == 'Male' ? "<option value='Male' selected>Male</option>" : "<option value='Male'>Male</option>";
                echo $Sex == 'Female' ? "<option value='Female' selected>Female</option>" : "<option value='Female'>Female</option>";
                echo $Sex == 'Other' ? "<option value='Other' selected>Other</option>" : "<option value='Other'>Other</option>";
                echo "</select><br>";

                echo '<br><input type="submit" name="saveUpdateButton" value="Save">';
                echo '</form>';
            } else {
                echo "<p>Customer not found.</p>";
            }
        } else {
            echo "<p>No customer ID provided.</p>";
        }
        ?>
    </div>
</body>
</html>