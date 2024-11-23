<?php
include('db.php'); // Include your database connection file

// Initialize feedback message variable
$feedback_message = "";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $audi_id = $_POST['audi_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $projector = $_POST['projector'];
    $sound_sys = $_POST['sound_sys'];

    // Prepare SQL statement to insert new auditorium
    $sql = "INSERT INTO Auditorium (Audi_ID, Name, Location, Capacity, Projector, Sound_Sys) VALUES (:audi_id, :name, :location, :capacity, :projector, :sound_sys)";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':audi_id', $audi_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':projector', $projector);
    $stmt->bindParam(':sound_sys', $sound_sys);

    // Execute the statement
    if ($stmt->execute()) {
        $feedback_message = "Auditorium added successfully!";
    } else {
        $feedback_message = "Error: " . $stmt->errorInfo()[2]; // Display error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Auditorium</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Auditorium</h1>
        <form method="POST" action="">
            <label for="audi_id">Auditorium ID:</label>
            <input type="number" id="audi_id" name="audi_id" required><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location"><br>

            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity"><br>

            <label for="projector">Has Projector:</label>
            <select id="projector" name="projector">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select><br>

            <label for="sound_sys">Has Sound System:</label>
            <select id="sound_sys" name="sound_sys">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select><br>

            <button type="submit">Add Auditorium</button>
        </form>

        <?php if ($feedback_message): ?>
            <p style="color: green;"><?php echo $feedback_message; ?></p>
        <?php endif; ?>
        
        <br>
        <a href='dashboard_admin.php'>Back to Dashboard</a>
    </div>
</body>

</html>

