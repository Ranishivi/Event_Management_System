<?php
session_start(); // Start the session
include('db.php'); // Include your database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the logged-in admin's ID from the session
$admin_id = $_SESSION['admin_id'];

// Fetch all participant requests for events created by the logged-in admin
$sql_requests = "SELECT r.Participant_ID, r.Event_ID, p.Name, e.Name AS Event_Name, t.STATUS 
                 FROM Request r 
                 JOIN Participant p ON r.Participant_ID = p.Participant_ID 
                 JOIN Event e ON r.Event_ID = e.Event_ID
                 JOIN Ticket t ON t.Participant_ID = p.Participant_ID AND t.Event_ID = e.Event_ID
                 JOIN Registers re ON re.Event_ID = e.Event_ID
                 WHERE re.Admin_ID = :admin_id"; // Assuming there is a Created_By column in Event table

$stmt_requests = $conn->prepare($sql_requests);
$stmt_requests->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt_requests->execute();
$result_requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);

// Check if a request has been approved
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'approve') {
    $participant_id = $_POST['participant_id'];
    $event_id = $_POST['event_id'];
    
    // Approve the request (update the status in the database)
    $update_sql = "UPDATE Ticket SET STATUS = 'approved' WHERE Participant_ID = :participant_id AND Event_ID = :event_id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);
    $update_stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        $feedback_message = "Participant approved successfully!";
    } else {
        $feedback_message = "Failed to approve participant. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Participants</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add some basic styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .container {
            width: 80%;
            margin: auto;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Participant Requests</h1>

        <?php if (isset($feedback_message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($feedback_message); ?></p>
        <?php endif; ?>

        <?php if (count($result_requests) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Participant ID</th>
                        <th>Name</th>
                        <th>Event</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['Participant_ID']); ?></td>
                            <td><?php echo htmlspecialchars($request['Name']); ?></td>
                            <td><?php echo htmlspecialchars($request['Event_Name']); ?></td>
                            <td>
                                <?php if ($request['STATUS'] === 'approved'): ?>
                                    <button style="background-color: grey; color: white; border: none; padding: 5px 10px; cursor: not-allowed;" disabled>Approved</button>
                                <?php else: ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="participant_id" value="<?php echo htmlspecialchars($request['Participant_ID']); ?>">
                                        <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($request['Event_ID']); ?>">
                                        <button type="submit" name="action" value="approve">Approve</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending requests found.</p>
        <?php endif; ?>
    
        <br>
        <a href='dashboard_admin.php'>Back to Dashboard</a>
    </div>
</body>
</html>
