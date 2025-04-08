<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Booking - Login</title>
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

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
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

        .register-link {
            margin-top: 1.5rem;
            color: var(--text-color);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
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
    
    // Process login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $email = mysqli_real_escape_string($con, $_POST['username']);
        $password = $_POST['password'];
        
        // First check in customer table
        $customerQuery = "SELECT * FROM customer WHERE Email = ?";
        $customerStmt = mysqli_prepare($con, $customerQuery);
        mysqli_stmt_bind_param($customerStmt, "s", $email);
        mysqli_stmt_execute($customerStmt);
        $customerResult = mysqli_stmt_get_result($customerStmt);
        
        // Then check in staff table
        $staffQuery = "SELECT * FROM staff WHERE staffEmail = ?";
        $staffStmt = mysqli_prepare($con, $staffQuery);
        mysqli_stmt_bind_param($staffStmt, "s", $email);
        mysqli_stmt_execute($staffStmt);
        $staffResult = mysqli_stmt_get_result($staffStmt);
        
        if (mysqli_num_rows($customerResult) == 1) {
            // User found in customer table
            $user = mysqli_fetch_assoc($customerResult);
            
            // Verify password
            if (password_verify($password, $user['cPassword'])) {
                // Start session and set session variables
                session_start();
                $_SESSION['customerID'] = $user['customerID'];
                $_SESSION['username'] = $user['Email'];
                $_SESSION['userType'] = 'customer';
                
                // Redirect to dashboard
                header("Location: MainPage.php");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } elseif (mysqli_num_rows($staffResult) == 1) {
            // User found in staff table
            $user = mysqli_fetch_assoc($staffResult);
            
            // Verify password
            if (password_verify($password, $user['staffPass'])) {
                // Start session and set session variables
                session_start();
                $_SESSION['staffID'] = $user['staffID'];
                $_SESSION['username'] = $user['staffEmail'];
                $_SESSION['userType'] = $user['userType'];
                
                // Redirect to dashboard
                header("Location: rental/MainPage.php");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "Email not found. Please check your email or register.";
        }
    }
    ?>
    
    <div class="login-container">
        <h1 class="system-title">
            <i class="fas fa-calendar-alt"></i>
            Facility Booking
        </h1>
        
        <?php
        if (isset($error)) {
            echo '<div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> ' . 
                    htmlspecialchars($error) . 
                  '</div>';
        }
        
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div class="success-message" style="color: var(--primary-color); margin-top: 1rem; font-size: 0.9rem; padding: 0.8rem; background-color: #e8f5e9; border-radius: 4px; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle"></i> 
                    Registration successful! Please login with your credentials.
                  </div>';
        }
        ?>

        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">Email Address</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="username" name="username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="register-link">
            Don't have an account? 
            <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
