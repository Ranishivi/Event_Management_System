<?php
include('db.php');
session_start();

// Check if participant ID is set
$participant_id = isset($_SESSION['participant_id']) ? $_SESSION['participant_id'] : null;

if (!$participant_id) {
    // Handle case where the user is not logged in, e.g., redirect to login page
    header("Location: participant_login.php");
    exit();
}

// Fetch tickets for the participant including auditorium information
$sql_tickets = "SELECT t.Ticket_ID, t.Issue_Date, 
                        CASE 
                            WHEN t.STATUS IS NULL THEN 'PENDING' 
                            ELSE t.STATUS 
                        END AS STATUS 
                 FROM Ticket t 
                 WHERE t.Participant_ID = :participant_id"; // Add WHERE clause

$stmt_tickets = $conn->prepare($sql_tickets);
$stmt_tickets->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);
$stmt_tickets->execute();
$result_tickets = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tickets</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Your Tickets</h1>
        
        <?php if (count($result_tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_tickets as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket['Ticket_ID']; ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($ticket['Issue_Date'])); ?></td>
                            <td><?php echo $ticket['STATUS']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tickets found.</p>
        <?php endif; ?>
    </div>
    <p><a href="participant_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
