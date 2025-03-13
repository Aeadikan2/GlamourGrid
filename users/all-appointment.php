<?php
session_start();
include('db_connect.php');
include('header.php');

if (!isset($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}

$userId = $_SESSION['user_id']; // Logged-in user ID

// Handle delete functionality
if (isset($_GET['delid'])) {
    $sid = intval($_GET['delid']);
    $query = mysqli_query($conn, "DELETE FROM bookings WHERE ID = '$sid'");

    if ($query) {
        echo "<script>alert('Appointment deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting appointment.');</script>";
    }

    echo "<script>window.location.href='all-appointment.php'</script>";
    exit();
}
?>

<div class="container mt-4">
    <h2 class="text-center fw-bold">My Appointments</h2>

    <div class="card p-4 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Appointment No.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ret = mysqli_query($conn, "
                        SELECT 
                            bookings.ClientName, bookings.PhoneNumber,
                            bookings.id AS bid, bookings.AptNumber, bookings.AptDate, bookings.AptTime,
                            bookings.Status 
                        FROM bookings
                        WHERE bookings.UserID = '$userId'
                        ORDER BY bookings.BookingDate DESC
                    ");

                    $cnt = 1;
                    while ($row = mysqli_fetch_array($ret)) {
                        $statusBadge = match ($row['Status']) {
                            'Accepted' => '<span class="badge bg-success">Accepted</span>',
                            'Declined' => '<span class="badge bg-danger">Declined</span>',
                            default => '<span class="badge bg-warning text-dark">Pending</span>'
                        };
                    ?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo htmlspecialchars($row['AptNumber']); ?></td>
                            <td><?php echo htmlspecialchars($row['ClientName']); ?></td>
                            <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
                            <td><?php echo htmlspecialchars($row['AptDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['AptTime']); ?></td>
                            <td><?php echo $statusBadge; ?></td>
                            <td>
                                <a href="view-appointments.php?viewid=<?php echo $row['bid']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="all-appointment.php?delid=<?php echo $row['bid']; ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this appointment?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                        $cnt++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Responsive Styles -->
<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        table {
            font-size: 14px;
        }
        th, td {
            padding: 8px !important;
        }
        .btn {
            padding: 5px 8px;
        }
    }
</style>

<?php include 'footer.php'; ?>
