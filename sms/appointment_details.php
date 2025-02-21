<?php
session_start();
include('db_connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "<div class='alert alert-warning'>You must be logged in to view appointment details.</div>";
    exit;
}

// Ensure appointment number is provided
if (!isset($_GET['appointment_number']) || empty($_GET['appointment_number'])) {
    echo "<div class='alert alert-danger'>Invalid appointment number.</div>";
    exit;
}

$appointment_number = $_GET['appointment_number'];

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch appointment details
    $stmt = $pdo->prepare("
        SELECT 
            bookings.AptNumber AS appointment_number,
            bookings.AptDate AS appointment_date,
            bookings.AptTime AS appointment_time,
            bookings.Status AS status,
            bookings.BookingDate AS booking_date,
            bookings.Message AS message,
            services.service_name,
            users.name,
            users.email
        FROM bookings
        JOIN services ON services.id = bookings.ServiceID
        JOIN users ON users.id = bookings.UserID
        WHERE bookings.AptNumber = :appointment_number 
          AND bookings.UserID = :user_id
    ");
    $stmt->execute([
        ':appointment_number' => $appointment_number,
        ':user_id' => $_SESSION['user_id']
    ]);

    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        echo "<div class='alert alert-warning'>No details found for this appointment.</div>";
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Appointment Details</h2>

    <table class="table table-light table-strip">    
        <tr>
            <th>Appointment Number</th>
            <td><?php echo htmlspecialchars($appointment['appointment_number']); ?></td>
        </tr>
        <tr>
            <th>Service</th>
            <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
        </tr>
        <tr>
            <th>Appointment Date</th>
            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
        </tr>
        <tr>
            <th>Appointment Time</th>
            <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <?php
                if (empty($appointment['status'])) {
                    echo "Waiting for confirmation";
                } else {
                    echo htmlspecialchars($appointment['status']);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th>Booking Date</th>
            <td><?php echo htmlspecialchars($appointment['booking_date']); ?></td>
        </tr>
        <tr>
            <th>Message</th>
            <td><?php echo htmlspecialchars($appointment['message']); ?></td>
        </tr>
        <tr>
            <th>User Name</th>
            <td><?php echo htmlspecialchars($appointment['name']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($appointment['email']); ?></td>
        </tr>
    </table>

    <div class="mt-3">
        <a href="booking_history.php" class="btn btn-secondary">Back to Booking History</a>
    </div>
</div>

<?php include_once('footer.php'); ?>

</body>
</html>
