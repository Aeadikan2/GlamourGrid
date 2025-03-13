<?php 
session_start();
include('db_connect.php'); 

// Initialize feedback message
$feedback = "";

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $feedback = "<div class='alert alert-warning'>You must be logged in to view your booking history.</div>";
} else {
    try {
        // Connect to the database with PDO
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the user's booking history
        $stmt = $pdo->prepare("
            SELECT 
                bookings.AptNumber AS appointment_number,
                bookings.AptDate AS appointment_date,
                bookings.AptTime AS appointment_time,
                bookings.Status AS status,
                bookings.BookingDate AS booking_date,
                services.service_name,
                stylists.name
            FROM bookings
            JOIN services ON services.id = bookings.ServiceID
            LEFT JOIN stylists ON stylists.id = bookings.StylistID
            WHERE bookings.UserID = :user_id
            ORDER BY bookings.BookingDate DESC
        ");
        $stmt->execute([':user_id' => $_SESSION['user_id']]);

        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($bookings) == 0) {
            $feedback = "<div class='alert alert-info'>No bookings found.</div>";
        }
    } catch (PDOException $e) {
        $feedback = "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - GlamourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once('header.php'); ?>
<script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            });
        });
    </script>

<div class="container mt-5">
    <h2 class="text-center">Your Booking History</h2>

    <!-- Feedback Message -->
    <?php echo $feedback; ?>

    <?php if (isset($bookings) && count($bookings) > 0): ?>
        <!-- Make the table scrollable on smaller screens -->
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Appointment Number</th>
                        <th>Service</th>
                        <th>Stylist</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($booking['appointment_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                            <td>
                                <?php 
                                echo $booking['name'] ? htmlspecialchars($booking['name']) : 'No stylist assigned'; 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($booking['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['appointment_time']); ?></td>
                            <td>
                                <?php 
                                if (empty($booking['status'])) {
                                    echo "Waiting for confirmation";
                                } else {
                                    echo htmlspecialchars($booking['status']);
                                }
                                ?>
                            </td>
                            <td>
                                <a href="appointment_details.php?appointment_number=<?php echo urlencode($booking['appointment_number']); ?>" class="btn btn-contact btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="mt-4">
            <p class="text-center text-muted"><?php echo $feedback; ?></p>
        </div>
    <?php endif; ?>
</div>

<?php include_once('footer.php'); ?>

<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
