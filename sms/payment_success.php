<?php
session_start();
include('db_connect.php');

// Ensure appointment number is provided
if (!isset($_GET['apt_number'])) {
    echo "<script>alert('Invalid access!'); window.location.href='dashboard.php';</script>";
    exit();
}

$apt_number = $_GET['apt_number'];

// Fetch payment and appointment details
$query = $pdo->prepare("
    SELECT pr.AptNumber, pr.AmountPaid, pr.PaymentStatus, pr.TransactionRef, b.ClientName, b.PhoneNumber, 
           b.AptDate, b.AptTime, GROUP_CONCAT(s.service_name SEPARATOR ', ') AS Services
    FROM payment_receipts pr
    JOIN bookings b ON pr.AptNumber = b.AptNumber
    JOIN appointment_services aps ON b.id = aps.appointment_id
    JOIN services s ON aps.service_id = s.id
    WHERE pr.AptNumber = ?
    GROUP BY pr.AptNumber
");
$query->execute([$apt_number]);
$payment = $query->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    echo "<script>alert('Payment details not found!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - GlamourGrid</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 50px;
            color: #28a745;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
        }
        .details-table th, .details-table td {
            padding: 10px;
            text-align: left;
        }
        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">✅</div>
            <h2 class="mt-3">Payment Successful!</h2>
            <p>Thank you, <strong><?php echo htmlspecialchars($payment['ClientName']); ?></strong>. Your payment has been received successfully.</p>

            <table class="details-table table table-bordered">
                <tr>
                    <th>Appointment Number:</th>
                    <td><?php echo htmlspecialchars($payment['AptNumber']); ?></td>
                </tr>
                <tr>
                    <th>Transaction Reference:</th>
                    <td><?php echo htmlspecialchars($payment['TransactionRef']); ?></td>
                </tr>
                <tr>
                    <th>Amount Paid:</th>
                    <td><strong>₦<?php echo number_format($payment['AmountPaid'], 2); ?></strong></td>
                </tr>
                <tr>
                    <th>Services Booked:</th>
                    <td><?php echo htmlspecialchars($payment['Services']); ?></td>
                </tr>
                <tr>
                    <th>Appointment Date:</th>
                    <td><?php echo htmlspecialchars($payment['AptDate']); ?></td>
                </tr>
                <tr>
                    <th>Appointment Time:</th>
                    <td><?php echo htmlspecialchars($payment['AptTime']); ?></td>
                </tr>
            </table>

            <a href="dashboard.php" class="btn btn-primary btn-home">Go to Dashboard</a>
        </div>
    </div>
</body>
</html>
