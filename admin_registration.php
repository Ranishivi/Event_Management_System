<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Admin Registration</h2>
    <form method="POST" action="">
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Contact:</label><input type="text" name="contact" required><br>
        <label>Password:</label><input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>

    <div class="links">
        <p>Already have an account? <a href="admin_login.php">Login here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>

<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO Admin (Name, ADMIN_ID, Email_ID, Contact, Password) VALUES (?, NULL, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $email);
    $stmt->bindParam(3, $contact);
    $stmt->bindParam(4, $password);

    // Execute the statement
    if ($stmt->execute()) {
        $admin_id = $conn->lastInsertId();  // Get the auto-generated ADMIN_ID
        echo "<h3 style='text-align:center;'>Registration successful! Your Admin ID for login is: $admin_id</h3>";
        echo "<div class='links' style='text-align:center;'><a href='admin_login.php'>Proceed to Login</a></div>";
    } else {
        // Get the error information
        $errorInfo = $stmt->errorInfo();
        echo "<p style='color:red; text-align:center;'>Error Code: " . $errorInfo[0] . " - " . $errorInfo[2] . "</p>"; // Display the error code and message
    }
}
?>
