<?php
include('db.php');

// Initialize variables
$participant_id = 0; // Replace with actual participant ID from session or authentication
$event_id = 0; // Replace with actual event ID based on participant registration
$feedback_message = "";

// Fetch participant ID from session (ensure session is started at the top of your script)
session_start();
if (isset($_SESSION['participant_id'])) {
    $participant_id = $_SESSION['participant_id']; // Get participant ID from session
}

// Fetch events that the participant has registered for
$sql_events = "SELECT e.Event_ID, e.Name FROM Event e 
               JOIN Request r ON e.Event_ID = r.Event_ID 
               WHERE r.Participant_ID = :participant_id";

$stmt = $conn->prepare($sql_events);
$stmt->execute([':participant_id' => $participant_id]);
$result_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $event_id = $_POST['event_id'];
    $feedback = $_POST['feedback'];

    // Check if participant ID exists in the Participant table
    $sql_check_participant = "SELECT COUNT(*) FROM Participant WHERE Participant_ID = :participant_id";
    $stmt_check = $conn->prepare($sql_check_participant);
    $stmt_check->execute([':participant_id' => $participant_id]);
    $participant_exists = $stmt_check->fetchColumn();

    if ($participant_exists > 0) {
        // Insert feedback into the feedback table
        $sql_feedback = "INSERT INTO Feedback (Participant_ID, Event_ID, Feedback) VALUES (:participant_id, :event_id, :feedback)";
        $stmt_feedback = $conn->prepare($sql_feedback);
        
        if ($stmt_feedback->execute([
            ':participant_id' => $participant_id,
            ':event_id' => $event_id,
            ':feedback' => $feedback
        ])) {
            $feedback_message = "Thank you for your feedback!";
        } else {
            $feedback_message = "Error: " . $stmt_feedback->errorInfo()[2]; // Get error message
        }
    } else {
        $feedback_message = "Invalid participant ID.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Give Feedback</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Give Feedback</h1>
        
        <?php if (count($result_events) > 0): ?>
            <form method="POST" action="">
                <label for="event_id">Select Event:</label>
                <select name="event_id" required>
                    <option value="">Select an event</option>
                    <?php foreach ($result_events as $event): ?>
                        <option value="<?php echo $event['Event_ID']; ?>"><?php echo $event['Name']; ?></option>
                    <?php endforeach; ?>
                </select><br>

                <label for="feedback">Feedback:</label><br>
                <textarea name="feedback" rows="4" required></textarea><br>

                <button type="submit">Submit Feedback</button>
            </form>
        <?php else: ?>
            <p>No events found for which you can give feedback.</p>
        <?php endif; ?>

        <?php if ($feedback_message): ?>
            <p style="color: green;"><?php echo $feedback_message; ?></p>
        <?php endif; ?>
    </div>
    <p><a href="participant_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
