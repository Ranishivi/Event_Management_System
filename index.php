<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ease</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styles for the container */
        .container {
            text-align: center;
            margin-top: 50px;
        }

        .button-group {
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            align-items: center; /* Center buttons horizontally */
            margin: 20px;
        }

        .dropdown {
            position: relative;
            margin: 10px; /* Add some space between buttons */
        }

        .dropbtn {
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 15px 30px; /* Increase button size */
            font-size: 18px; /* Increase font size */
            border: none;
            border-radius: 5px; /* Add rounded corners */
            cursor: pointer;
            width: 200px; /* Set a fixed width for uniformity */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px; /* Add rounded corners to dropdown */
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block; /* Show the dropdown on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WELCOME TO EVENT EASE!</h1>
        <BR>
        <div class="events-button">
            <a href="events_in_audi.php">
                <button class="button">View Scheduled Events</button>
            </a>
        </div>
        <br>
        <BR>
        <div class="button-group">
            <div class="dropdown">
                <button class="dropbtn">Admin</button>
                <div class="dropdown-content">
                    <a href="admin_login.php">Login</a>
                    <a href="admin_registration.php">Register</a>
                </div>
            </div>

            <div class="dropdown">
                <button class="dropbtn">Participant</button>
                <div class="dropdown-content">
                    <a href="participant_login.php">Login</a>
                    <a href="participant_register.php">Register</a>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>
