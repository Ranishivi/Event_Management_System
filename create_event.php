<?php
session_start(); // Start the session
include('db.php'); // Include your database connection file

$message = "";
$audi_id = null; // Variable to hold the auditorium ID

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $capacity = $_POST['capacity'];
    $projector = isset($_POST['projector']) ? 1 : 0; // Check if projector is needed
    $sound_system = isset($_POST['sound_system']) ? 1 : 0; // Check if sound system is needed
    $requirements = $_POST['requirements'];

    try {
        // Start a transaction
        $conn->beginTransaction();

        // Insert data into the Event table
        $sql = "INSERT INTO Event (Name, Start_Time, End_Time, Capacity, Projector, Sound_System, REQUIREMENTS) 
                VALUES (:name, :start_time, :end_time, :capacity, :projector, :sound_system, :requirements)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters using named placeholders
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->bindParam(':projector', $projector, PDO::PARAM_BOOL);
        $stmt->bindParam(':sound_system', $sound_system, PDO::PARAM_BOOL);
        $stmt->bindParam(':requirements', $requirements);

        if ($stmt->execute()) {
            // Get the ID of the newly inserted event
            $event_id = $conn->lastInsertId();

            // Find a suitable auditorium
            $sql_auditorium = "SELECT Audi_ID FROM Auditorium 
                               WHERE Capacity >= :capacity 
                               AND Audi_ID NOT IN (
                                   SELECT Audi_ID FROM Booking 
                                   WHERE 
                                       (Start_Time < :end_time AND End_time > :start_time)
                               ) LIMIT 1";

            $stmt_auditorium = $conn->prepare($sql_auditorium);
            $stmt_auditorium->bindParam(':capacity', $capacity, PDO::PARAM_INT);
            $stmt_auditorium->bindParam(':start_time', $start_time);
            $stmt_auditorium->bindParam(':end_time', $end_time);

            if ($stmt_auditorium->execute()) {
                $audi_id = $stmt_auditorium->fetchColumn();

                if ($audi_id) {
                    // If an auditorium is found, insert into Booking table
                    $sql_booking = "INSERT INTO Booking (Event_ID, Audi_ID, Start_Time, End_time) 
                                    VALUES (:event_id, :audi_id, :start_time, :end_time)";
                    $stmt_booking = $conn->prepare($sql_booking);
                    $stmt_booking->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                    $stmt_booking->bindParam(':audi_id', $audi_id, PDO::PARAM_INT);
                    $stmt_booking->bindParam(':start_time', $start_time);
                    $stmt_booking->bindParam(':end_time', $end_time);
                    
                    if ($stmt_booking->execute()) {
                        $message = "Event scheduled successfully! Assigned Auditorium ID: " . $audi_id;
                    } else {
                        $message = "Error: Could not book the auditorium.";
                    }
                } else {
                    $message = "Error: No available auditorium found for the specified capacity and time.";
                }
            } else {
                $message = "Error: Could not retrieve auditoriums.";
            }

            // Check if admin_id is stored in session
            if (isset($_SESSION['admin_id'])) {
                $admin_id = $_SESSION['admin_id']; // Retrieve the admin ID from session

                // Insert data into the Registers table
                $sql_register = "INSERT INTO Registers (Admin_ID, Event_ID) VALUES (:admin_id, :event_id)";
                $stmt_register = $conn->prepare($sql_register);
                $stmt_register->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
                $stmt_register->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                
                if (!$stmt_register->execute()) {
                    $message .= " Error: Could not register the event with admin.";
                }
            } else {
                $message .= " Error: Admin ID not found in session.";
            }
        } else {
            $message = "Error: " . $stmt->errorInfo()[2]; // Get error message from PDO
        }

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $conn->rollBack();
        $message = "Error: ". $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Schedule a New Event</h1>
        
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Event Name:</label>
            <input type="text" name="name" required><br>

            <label for="start_time">Start Time:</label>
            <input type="datetime-local" name="start_time" required><br>

            <label for="end_time">End Time:</label>
            <input type="datetime-local" name="end_time" required><br>

            <label for="capacity">Capacity:</label>
            <input type="number" name="capacity" required><br>

            <label for="projector">Need Projector:</label>
            <input type="checkbox" name="projector"><br>

            <label for="sound_system">Need Sound System:</label>
            <input type="checkbox" name="sound_system"><br>

            <label for="requirements">Additional Requirements:</label>
            <textarea name="requirements" rows="4"></textarea><br>

            <button type="submit">Schedule Event</button>
        </form>
        <br>
        <a href='dashboard_admin.php'>Back to Dashboard</a>
    </div>
</body>
</html>
