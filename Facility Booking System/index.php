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
    // Include the checkLogin.php file
    include_once("login/checkLogin.php");
    
    // Check if user is already logged in
    if (isLoggedIn()) {
        // Redirect to appropriate page based on user type
        if (isStaff()) {
            header("Location: MainPage.php");
        } else {
            header("Location: MainPage.php");
        }
        exit();
    }
    
    // Process login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $email = $_POST['username'];
        $password = $_POST['password'];
        
        // Check user type by email before processing login
        $userType = checkUserTypeByEmail($email);
        
        if ($userType) {
            // User exists, process login
            $error = processLogin($email, $password);
        } else {
            // User not found
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
