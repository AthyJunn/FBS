<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facility Booking System</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary-color: #3498db;
                --secondary-color: #2980b9;
                --accent-color: #e74c3c;
                --light-color: #ecf0f1;
                --dark-color: #2c3e50;
                --success-color: #2ecc71;
                --warning-color: #f39c12;
                --danger-color: #e74c3c;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f5f7fa;
                color: #333;
            }
            
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            /* Header Styles */
            .header {
                background-color: var(--primary-color);
                color: white;
                padding: 1rem 0;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .logo {
                font-size: 1.8rem;
                font-weight: bold;
                display: flex;
                align-items: center;
            }
            
            .logo i {
                margin-right: 10px;
            }
            
            /* Navigation Styles */
            .nav {
                background-color: white;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                position: sticky;
                top: 0;
                z-index: 100;
            }
            
            .nav-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .nav-links {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .nav-links li {
                position: relative;
            }
            
            .nav-links a {
                display: block;
                padding: 15px 20px;
                color: var(--dark-color);
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .nav-links a:hover {
                background-color: var(--light-color);
                color: var(--primary-color);
            }
            
            .nav-links a.active {
                background-color: var(--primary-color);
                color: white;
            }
            
            /* Dropdown Styles */
            .dropdown {
                position: relative;
            }
            
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: white;
                min-width: 200px;
                box-shadow: 0 8px 16px rgba(0,0,0,0.1);
                z-index: 1;
                border-radius: 4px;
                overflow: hidden;
            }
            
            .dropdown-content a {
                color: var(--dark-color);
                padding: 12px 16px;
                text-decoration: none;
                display: block;
                transition: all 0.2s ease;
            }
            
            .dropdown-content a:hover {
                background-color: var(--light-color);
                color: var(--primary-color);
            }
            
            .dropdown:hover .dropdown-content {
                display: block;
            }
            
            /* Dashboard Cards */
            .dashboard {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 20px 0;
            }
            
            .card {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                padding: 20px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .card-header {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .card-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: var(--light-color);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                color: var(--primary-color);
                font-size: 1.5rem;
            }
            
            .card-title {
                font-size: 1.2rem;
                font-weight: 600;
                margin: 0;
            }
            
            .card-content {
                color: #666;
            }
            
            /* Iframe Styles */
            .iframe-container {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                overflow: hidden;
                margin: 20px 0;
            }
            
            iframe {
                width: 100%;
                height: 700px;
                border: none;
            }
            
            /* Footer Styles */
            .footer {
                background-color: var(--dark-color);
                color: white;
                padding: 30px 0;
                margin-top: 30px;
            }
            
            .footer-content {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 30px;
            }
            
            .footer-section h3 {
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 1.2rem;
            }
            
            .footer-section p {
                margin: 0;
                line-height: 1.6;
            }
            
            .footer-bottom {
                text-align: center;
                padding-top: 20px;
                margin-top: 20px;
                border-top: 1px solid rgba(255,255,255,0.1);
            }
            
            /* Responsive Styles */
            @media (max-width: 768px) {
                .nav-container {
                    flex-direction: column;
                }
                
                .nav-links {
                    flex-direction: column;
                    width: 100%;
                }
                
                .dropdown-content {
                    position: static;
                    width: 100%;
                    box-shadow: none;
                }
                
                .dashboard {
                    grid-template-columns: 1fr;
                }
            }
            
            /* Button Styles */
            .btn {
                display: inline-block;
                padding: 8px 16px;
                background-color: var(--primary-color);
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                font-weight: 500;
                transition: background-color 0.3s ease;
            }
            
            .btn:hover {
                background-color: var(--secondary-color);
            }
            
            .btn-danger {
                background-color: var(--danger-color);
            }
            
            .btn-danger:hover {
                background-color: #c0392b;
            }
            
            .btn-success {
                background-color: var(--success-color);
            }
            
            .btn-success:hover {
                background-color: #27ae60;
            }
        </style>
    </head>

    <body>     
        <!-- Header -->
        <header class="header">
            <div class="container header-content">
                <div class="logo">
                    <i class="fas fa-building"></i>
                    <span>Facility Booking System</span>
                </div>
                <div>
                    <a href="login/logout.php" class="btn btn-danger" target="iframeStaff">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="nav">
            <div class="container nav-container">
                <ul class="nav-links">
                    <li>
                        <a href="#" class="active">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    
                    <!-- Facility Information Dropdown -->
                    <li class="dropdown">
                        <a href="#">
                            <i class="fas fa-building"></i> Facility Information
                            <i class="fas fa-chevron-down" style="margin-left: 5px; font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="facility/facilityList.php" target="iframeStaff">
                                <i class="fas fa-list"></i> View Facility List
                            </a>
                            <a href="facility/facilityInfoForm.php" target="iframeStaff">
                                <i class="fas fa-plus-circle"></i> Add New Facility
                            </a>
                        </div>
                    </li>
                    
                    <!-- Customer Information Dropdown -->
                    <li class="dropdown">
                        <a href="#">
                            <i class="fas fa-users"></i> Customer Information
                            <i class="fas fa-chevron-down" style="margin-left: 5px; font-size: 0.8rem;"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="customer/customerInfoForm.php" target="iframeStaff">
                                <i class="fas fa-user-plus"></i> Add New Customer
                            </a>
                            <a href="customer/customerList.php" target="iframeStaff">
                                <i class="fas fa-list"></i> View Customer List
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container">
            <!-- Dashboard Cards -->
            <div class="dashboard">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="card-title">Facilities</h3>
                    </div>
                    <div class="card-content">
                        <p>Manage your facility information, add new facilities, or update existing ones.</p>
                        <a href="facility/facilityList.php" class="btn" target="iframeStaff">View Facilities</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="card-title">customer</h3>
                    </div>
                    <div class="card-content">
                        <p>Manage customer information, add new customer, or view the customer list.</p>
                        <a href="customer/customerList.php" class="btn" target="iframeStaff">View customer</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="card-title">Bookings</h3>
                    </div>
                    <div class="card-content">
                        <p>View and manage facility bookings, check availability, and process reservations.</p>
                        <a href="#" class="btn" target="iframeStaff">View Bookings</a>
                    </div>
                </div>
            </div>
            
            <!-- Iframe Container -->
            <div class="iframe-container">
                <iframe src="facility/facilityList.php" name="iframeStaff"></iframe>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>Facility Booking System</h3>
                        <p>A comprehensive system for managing facilities, customer, and bookings efficiently.</p>
                    </div>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <p>
                            <a href="facility/facilityList.php" style="color: white; text-decoration: none;">Facilities</a><br>
                            <a href="customer/customerList.php" style="color: white; text-decoration: none;">Customers</a><br>
                            <a href="#" style="color: white; text-decoration: none;">Bookings</a>
                        </p>
                    </div>
                    <div class="footer-section">
                        <h3>Contact Us</h3>
                        <p>
                            <i class="fas fa-envelope"></i> support@facilitybooking.com<br>
                            <i class="fas fa-phone"></i> +60 (10) 123-4567
                        </p>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 Facility Booking System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>

