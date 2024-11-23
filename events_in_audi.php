<?php
include('db.php');
session_start();

// Fetch all events along with their associated auditorium details, sorted by start time
$sql_events = "
    SELECT e.Event_ID, e.Name AS Event_Name, 
           a.Audi_ID, a.Name AS Auditorium_Name, 
           e.Start_Time, e.End_Time
    FROM Event e
    JOIN Booking b ON e.Event_ID = b.Event_ID
    JOIN Auditorium a ON b.Audi_ID = a.Audi_ID
    WHERE e.Start_Time >= NOW()  -- Only upcoming events
    ORDER BY e.Start_Time ASC     -- Sort by start time
";

$stmt_events = $conn->prepare($sql_events);
$stmt_events->execute();
$result_events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Events</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        p {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
            }
            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Scheduled Events</h1>
        
        <?php if (count($result_events) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Number</th>
                        <th>Event Name</th>
                        <th>Auditorium Number</th>
                        <th>Auditorium Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result_events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event['Event_ID']); ?></td>
                            <td><?php echo htmlspecialchars($event['Event_Name']); ?></td>
                            <td><?php echo htmlspecialchars($event['Audi_ID']); ?></td>
                            <td><?php echo htmlspecialchars($event['Auditorium_Name']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($event['Start_Time']))); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($event['End_Time']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming events found.</p>
        <?php endif; ?>
    </div>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
