<?php
include('db.php');

session_start(); // Start the session to manage user sessions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $participant_id = $_POST['participant_id'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Participant WHERE Participant_ID = :participant_id");
    $stmt->bindValue(':participant_id', $participant_id); // Use bindValue for PDO
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        // User exists, set session variable
        $_SESSION['participant_id'] = $participant_id; // Store participant ID in session
        header("Location: participant_dashboard.php"); // Redirect to welcome page
        exit();
    } else {
        $error_message = "User not found. Please register.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Participant Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Participant Login</h2>
    
    <h3>Login Yourself to check the status of your ticket!</h3>
    <form method="POST" action="">
        <label>ID:</label>
        <input type="number" name="participant_id" required><br>
        <button type="submit">Login</button>
    </form>

    <?php
    // Display error message if exists
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <p>Not registered? <a href="participant_register.php">Register here</a></p>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
