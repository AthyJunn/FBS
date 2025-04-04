<!DOCTYPE html>
<html>
    <head>
        <title>Facility Booking System</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <style>
            .dropdown{
                position: relative;
                display: inline-block;
            }
            .dropdown-content{
                display: none;
                position: absolute;
                background-color: lightcyan;
                min-width: 160 px;
                box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
                z-index: 1;
            }
            .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            }
            .dropdown-content a:hover { background-color: #f1f1f1; }
            .dropdown:hover .dropdown-content { display: block; }
            iframe {
                width: 100%;
                height: 500px;
                border: none;
            }
        </style>

    </head>

    <body>     
        <div class="w3-container">
        <h1>Facility Booking System</h1>

        <!-- Navigation Bar -->
        <div class="w3-bar w3-blue-grey">
            <!-- Home Button -->
            <a href="#" class="w3-bar-item w3-button">Home</a>

            <!-- Facility Information Dropdown -->
            <div class="w3-dropdown-hover">
                <button class="w3-button">Facility Information</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="facility/facilityList.php" class="w3-bar-item w3-button" target="iframeStaff">View Facility List</a>
                    <a href="facility/facilityInfoForm.php" class="w3-bar-item w3-button" target="iframeStaff">Add New Facility</a>
                </div>
            </div>

            <!-- Customer Information Dropdown -->
            <div class="w3-dropdown-hover">
                <button class="w3-button">Customer Information</button>
                <div class="w3-dropdown-content w3-bar-block w3-card-4">
                    <a href="customer/customerInfoForm.php" class="w3-bar-item w3-button" target="iframeStaff">Add New Customer</a>
                    <a href="customer/customerList.php" class="w3-bar-item w3-button" target="iframeStaff">View Customer List</a>
                </div>
            </div>

            <!-- Logout Button -->
            <a href="login/logout.php" class="w3-bar-item w3-button w3-right" target="iframeStaff">Logout</a>
        </div>

        <br>

        <!-- Iframe to Display Content -->
        <iframe src="facility/facilityList.php" 
                height="1000px" width="1400px" 
                name="iframeStaff">
        </iframe>
    </div>
            
    </body>
    
</html>

