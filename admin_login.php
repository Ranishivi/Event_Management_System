<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
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
    <h2>Admin Login</h2>
    <form method="POST" action="">
        <label>Admin ID:</label>
        <input type="text" name="admin_id" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    
    <div class="links">
        <p>First-time Admin? <a href="admin_registration.php">Register here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    
</body>
</html>

<?php
session_start(); // Start the session
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM Admin WHERE ADMIN_ID = :admin_id");
    
    // Bind the parameter
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT); // Assuming ADMIN_ID is an integer

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $row['Password'])) {
            // Store admin_id in session
            $_SESSION['admin_id'] = $row['ADMIN_ID']; // Store the admin ID in the session

            header("Location: dashboard_admin.php");  // Redirect to admin dashboard
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password.</p>";
        }
    } else {
        echo "<p style='color:red;'>Admin not found. Please register first.</p>";
    }
}
?>
