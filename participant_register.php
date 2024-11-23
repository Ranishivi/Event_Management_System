<?php
include('db.php');

$participant_id = null; // Initialize variable to hold participant ID
$success_message = ""; // Initialize success message

// Function to generate a unique participant ID
function generateUniqueParticipantID($conn) {
    while (true) {
        // Generate a random integer within a specified range (e.g., 1000 to 9999)
        $random_id = rand(1000, 9999); 
        
        // Check if the generated ID already exists
        $sql_check = "SELECT COUNT(*) FROM Participant WHERE Participant_ID = :participant_id";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':participant_id', $random_id);
        $stmt_check->execute();
        $exists = $stmt_check->fetchColumn();

        // If ID does not exist, it's unique and can be used
        if ($exists == 0) {
            return $random_id;
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $event_id = $_POST['event_id']; // Get selected event ID

    // Ensure an event is selected
    if (empty($event_id)) {
        echo "<p style='color: red;'>Please select an event to register.</p>";
    } else {
        // Generate a unique participant ID
        $participant_id = generateUniqueParticipantID($conn);

        

        // Insert participant details into the Participant table
        try {
            $sql = "INSERT INTO Participant (Participant_ID, Username, Name, Email, Phone) VALUES (:participant_id, :username, :name, :email, :phone)";
            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':participant_id', $participant_id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            // Execute the statement
            $stmt->execute();

            // Insert into Requests table for admin approval
            $sql_request = "INSERT INTO Request (Participant_ID, Event_ID) VALUES (:participant_id, :event_id)";
            $stmt_request = $conn->prepare($sql_request);
            $stmt_request->bindParam(':participant_id', $participant_id);
            $stmt_request->bindParam(':event_id', $event_id);
            $stmt_request->execute();

            // Insert into Ticket table
            $sql_ticket = "INSERT INTO Ticket (Participant_ID, Event_ID) VALUES (:participant_id, :event_id)";
            $stmt_ticket = $conn->prepare($sql_ticket);
            $stmt_ticket->bindParam(':participant_id', $participant_id);
            $stmt_ticket->bindParam(':event_id', $event_id);
            $stmt_ticket->execute();

            // Prepare success message with participant ID
            $success_message = "Registration successful! Your Participant ID is: " . $participant_id ;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Fetch upcoming events only
$sql_events = "SELECT * FROM Event WHERE Start_Time > NOW()";
$stmt_events = $conn->prepare($sql_events);
$stmt_events->execute();
$result_events = $stmt_events->fetchAll(PDO::FETCH_ASSOC); // Fetch all events
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Participant Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Participant Registration</h2>
    <form method="POST" action="">
        <label>Username:</label><input type="text" name="username" required><br>
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>Phone:</label><input type="text" name="phone" required><br>

        <label for="event_id">Choose an Event:</label>
        <select name="event_id" required>
            <option value="">Select an event</option>
            <?php if (count($result_events) > 0): ?>
                <?php foreach($result_events as $event): ?>
                    <option value="<?php echo htmlspecialchars($event['Event_ID']); ?>">
                        <?php echo htmlspecialchars($event['Name']) . ' - ' . date('Y-m-d H:i', strtotime($event['Start_Time'])); ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No upcoming events available</option>
            <?php endif; ?>
        </select><br>

        <button type="submit">Register</button>
    </form>

    <?php
    // Display success message with participant ID if available
    if (!empty($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
        echo "<p><a href='participant_login.php'>Click here to login</a></p>"; // Login link
    }
    ?>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
