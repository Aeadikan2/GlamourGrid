<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/db_connect.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$invoices_result = mysqli_query($conn, "SELECT i.id, u.name as user_name, s.service_name, i.price, i.quantity, i.total_amount, i.status, i.posting_date 
                                        FROM invoice i 
                                        JOIN users u ON i.user_id = u.id 
                                        JOIN services s ON i.service_id = s.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Invoices</title>
</head>
<body class="cbp-spmenu-push">
<div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

    <h1>Invoices</h1>
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Service Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Posting Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($invoice = mysqli_fetch_assoc($invoices_result)) { ?>
                <tr>
                    <td><?php echo $invoice['id']; ?></td>
                    <td><?php echo htmlspecialchars($invoice['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['price']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['status']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['posting_date']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="js/bootstrap.js"></script>
</body>
</html>