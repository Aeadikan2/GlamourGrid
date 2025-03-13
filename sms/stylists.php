<?php
// Database connection
$host = 'localhost';
$dbname = 'Aeady salon';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update stylist availability based on bookings
function updateStylistAvailability($conn) {
    // Set all stylists to available
    $resetQuery = "UPDATE stylists SET available = 1";
    $conn->query($resetQuery);

    // Set stylists with 4 or more bookings today to unavailable
    $updateQuery = "UPDATE stylists 
                    SET available = 0 
                    WHERE id IN (
                        SELECT StylistId FROM bookings 
                        WHERE DATE(AptTime) = CURDATE()
                        GROUP BY StylistId 
                        HAVING COUNT(*) >= 4
                    )";
    $conn->query($updateQuery);
}

// Run the update function
updateStylistAvailability($conn);

// Fetch stylists
$stylists_query = "SELECT * FROM stylists";
$stylists_result = $conn->query($stylists_query);
$stylists = [];
while ($row = $stylists_result->fetch_assoc()) {
    $stylists[] = $row;
}

// Fetch client queue (Bookings)
$queue_query = "
    SELECT bookings.id AS booking_id, bookings.AptNumber, bookings.AptTime, bookings.status, 
           stylists.name AS StylistName, stylists.image AS StylistImage
    FROM bookings 
    LEFT JOIN stylists ON bookings.StylistId = stylists.id 
    ORDER BY bookings.id ASC";
$queue_result = $conn->query($queue_query);
$queue = [];
while ($row = $queue_result->fetch_assoc()) {
    $queue[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourGrid | Stylist Availability & Queue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once('header.php'); ?>

    <div class="container py-5">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Available Stylists</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($stylists as $stylist): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <?php echo htmlspecialchars($stylist['name']); ?>
                            </div>
                            <?php if ($stylist['available']): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Unavailable</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Client Queue -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Client Queue</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Queue Position</th>
                            <th>Appointment Number</th>
                            <th>Appointment Time</th>
                            <th>Stylist</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($queue as $index => $entry): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($entry['AptNumber']); ?></td>
                                <td><?php echo htmlspecialchars($entry['AptTime']); ?></td>
                                <td><?php echo htmlspecialchars($entry['StylistName'] ?? 'Pending'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $entry['status'] === 'Completed' ? 'success' : ($entry['status'] === 'Pending' ? 'warning text-dark' : 'primary'); ?>">
                                        <?php echo htmlspecialchars($entry['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
</body>
</html>