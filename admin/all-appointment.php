<?php
session_start();
include('includes/db_connect.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();  // Ensure the script stops execution
}

// Handle delete functionality
if (isset($_GET['delid'])) {
    $sid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM bookings WHERE ID = '$sid'");

    if ($query) {
        echo "<script>alert('Data Deleted');</script>";
    } else {
        echo "<script>alert('Error deleting data');</script>";
    }

    echo "<script>window.location.href='all-appointment.php'</script>";
    exit();
}

// Handle status update (Accept or Decline)
if (isset($_POST['action']) && isset($_POST['id'])) {
    $appointmentId = intval($_POST['id']);
    $action = $_POST['action'];

    // Map actions to statuses
    $statusMap = [
        'accept' => 'Accepted',
        'decline' => 'Declined',
    ];

    if (array_key_exists($action, $statusMap)) {
        $status = $statusMap[$action];
        $updateQuery = "UPDATE bookings SET Status='$status' WHERE ID='$appointmentId'";

        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Appointment status updated successfully.');</script>";
        } else {
            echo "<script>alert('Error updating status: " . mysqli_error($con) . "');</script>";
        }
        echo "<script>window.location.href='all-appointment.php'</script>";
        exit();
    }
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>GlamourGrid || All Appointments</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">All Appointments</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>All Appointments:</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Appointment Number</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Services</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "
                                    SELECT 
                                        bookings.ClientName, bookings.PhoneNumber,
                                        bookings.id AS bid, bookings.AptNumber, bookings.AptDate, bookings.AptTime,
                                        bookings.Status, 
                                        GROUP_CONCAT(services.service_name SEPARATOR ', ') AS Services
                                    FROM bookings
                                    JOIN users ON users.id = bookings.UserID
                                    LEFT JOIN appointment_services ON appointment_services.appointment_id = bookings.id
                                    LEFT JOIN services ON services.id = appointment_services.service_id
                                    GROUP BY bookings.id
                                    ORDER BY bookings.BookingDate DESC
                                ");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt; ?></th>
                                        <td><?php echo htmlspecialchars($row['AptNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ClientName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['AptDate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['AptTime']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Services'] ?: 'No Services Selected'); ?></td>
                                        <td>
                                            <?php echo $row['Status'] ? htmlspecialchars($row['Status']) : "Not Updated Yet"; ?>
                                        </td>
                                        <td width="200">
                                            <?php if ($row['Status'] == 'Pending' || !$row['Status']): ?>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?php echo $row['bid']; ?>">
                                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                                </form>
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="id" value="<?php echo $row['bid']; ?>">
                                                    <button type="submit" name="action" value="decline" class="btn btn-danger btn-sm">Decline</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">No actions available</span>
                                            <?php endif; ?>
                                            <a href="all-appointment.php?delid=<?php echo $row['bid']; ?>" class="btn btn-danger btn-sm"
                                               onClick="return confirm('Are you sure you want to delete?')">Delete</a>
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
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>
<?php {} ?>
