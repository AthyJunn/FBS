<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Booking - Register</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --error-color: #f44336;
            --text-color: #333;
            --bg-color: #f4f4f4;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin: 2rem;
        }

        .system-title {
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }

        .btn {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        .login-link {
            margin-top: 1.5rem;
            color: var(--text-color);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: var(--error-color);
            margin-top: 1rem;
            font-size: 0.9rem;
            padding: 0.8rem;
            background-color: #ffebee;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 2.5rem;
        }

        .input-icon i {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
    </style>
</head>
<body>
    <?php
    // Database connection
    $con = mysqli_connect('localhost', 'web2025', 'web2025', 'facilitydb');
    
    // Check connection
    if (!$con) {
        die("Connection Error: " . mysqli_connect_error());
    }
    
    // Process registration form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $Email = mysqli_real_escape_string($con, $_POST['Email']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        
        // Validate password match
        if ($password !== $confirmPassword) {
            $error = "Passwords do not match!";
        } 
        // Validate password length
        elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long!";
        }
        // Validate email format
        elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address!";
        }
        else {
            // Check if email already exists in customer table
            $checkEmailQuery = "SELECT * FROM customer WHERE Email = ?";
            $checkEmailStmt = mysqli_prepare($con, $checkEmailQuery);
            mysqli_stmt_bind_param($checkEmailStmt, "s", $Email);
            mysqli_stmt_execute($checkEmailStmt);
            $checkEmailResult = mysqli_stmt_get_result($checkEmailStmt);
            
            if (mysqli_num_rows($checkEmailResult) > 0) {
                $error = "Email already registered. Please use another email.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Get the next customerID in C0001 format
                $idQuery = "SELECT MAX(CAST(SUBSTRING(customerID, 2) AS UNSIGNED)) as maxID FROM customer";
                $idResult = mysqli_query($con, $idQuery);
                $idRow = mysqli_fetch_assoc($idResult);
                $nextID = $idRow['maxID'] + 1;
                $formattedID = 'C' . str_pad($nextID, 4, '0', STR_PAD_LEFT);
                
                // Insert new customer
                $insertQuery = "INSERT INTO customer (customerID, customerName, Email, cPassword) VALUES (?, ?, ?, ?)";
                $insertStmt = mysqli_prepare($con, $insertQuery);
                mysqli_stmt_bind_param($insertStmt, "ssss", $formattedID, $username, $Email, $hashedPassword);
                
                if (mysqli_stmt_execute($insertStmt)) {
                    // Registration successful, redirect to login page
                    header("Location: index.php?success=1");
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
    ?>
    
    <div class="register-container">
        <h1 class="system-title">
            <i class="fas fa-user-plus"></i>
            Create Account
        </h1>
        
        <?php
        if (isset($error)) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> ' . 
                    htmlspecialchars($error) . 
                  '</div>';
        }
        ?>

        <form method="POST" id="registrationForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username">Full Name</label>
                <div class="input-icon">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="Email">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="Email" name="Email" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </div>

            <button type="submit" name="register" class="btn">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>

        <div class="login-link">
            Already have an account? 
            <a href="index.php">Login here</a>
        </div>
    </div>

    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const email = document.getElementById('Email').value;

            // Password validation
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long!');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address!');
                return false;
            }

            return true;
        }
    </script>
</body>
</html> 