<?php
session_start();
include('db_connect.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $cid = intval($_GET['viewid']);
    $status = $_POST['status'];

    $query = mysqli_query($con, "UPDATE bookings SET Status='$status' WHERE ID='$cid'");

    if ($query) {
        echo '<script>alert("Appointment status updated successfully.");</script>';
        echo "<script>window.location.href='all-appointment.php';</script>";
    } else {
        echo '<script>alert("Error updating status. Please try again.");</script>';
    }
}

// Fetch appointment details
if (isset($_GET['viewid'])) {
    $cid = intval($_GET['viewid']);
    $ret = mysqli_query($conn, "
        SELECT users.name, users.email, bookings.PhoneNumber, bookings.ID AS bid, 
               bookings.AptNumber, bookings.AptDate, bookings.AptTime, bookings.Message, 
               bookings.BookingDate, bookings.Status,
               GROUP_CONCAT(services.service_name SEPARATOR ', ') AS Services
        FROM bookings 
        JOIN users ON users.ID = bookings.UserID 
        LEFT JOIN appointment_services ON appointment_services.appointment_id = bookings.ID
        LEFT JOIN services ON services.id = appointment_services.service_id
        WHERE bookings.ID = '$cid'
        GROUP BY bookings.ID
    ");

    if (mysqli_num_rows($ret) > 0) {
        $row = mysqli_fetch_array($ret);
    } else {
        echo "<script>alert('Appointment not found!');</script>";
        echo "<script>window.location.href='all-appointment.php';</script>";
        exit();
    }
} else {
    echo "<script>window.location.href='all-appointment.php';</script>";
    exit();
}
?>

<div class="container mt-5">
    <h2 class="text-center fw-bold">View Appointment</h2>

    <div class="card p-4 shadow-sm">
        <table class="table table-bordered">
            <tr>
                <th>Appointment Number</th>
                <td><?php echo htmlspecialchars($row['AptNumber']); ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
            </tr>
            <tr>
                <th>Appointment Date</th>
                <td><?php echo htmlspecialchars($row['AptDate']); ?></td>
            </tr>
            <tr>
                <th>Appointment Time</th>
                <td><?php echo htmlspecialchars($row['AptTime']); ?></td>
            </tr>
            <tr>
                <th>Applied On</th>
                <td><?php echo htmlspecialchars($row['BookingDate']); ?></td>
            </tr>
            <tr>
                <th>Services Booked</th>
                <td><?php echo htmlspecialchars($row['Services'] ?: 'No Services Selected'); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php
                    $statusBadge = match ($row['Status']) {
                        'Accepted' => '<span class="badge bg-success">Accepted</span>',
                        'Declined' => '<span class="badge bg-danger">Declined</span>',
                        default => '<span class="badge bg-warning text-dark">Pending</span>'
                    };
                    echo $statusBadge;
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>