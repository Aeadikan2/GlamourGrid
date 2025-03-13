<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// Ensure appointment number is passed
if (!isset($_GET['apt_number'])) {
    echo "<script>alert('Invalid access!'); window.location.href='dashboard.php';</script>";
    exit();
}

$apt_number = $_GET['apt_number'];

// Fetch appointment details
$query = $pdo->prepare("
    SELECT bookings.ClientName, bookings.PhoneNumber, bookings.AptNumber, bookings.AptDate, bookings.AptTime,
           GROUP_CONCAT(services.service_name SEPARATOR ', ') AS Services,
           SUM(services.price) AS TotalAmount
    FROM bookings
    JOIN appointment_services ON appointment_services.appointment_id = bookings.id
    JOIN services ON services.id = appointment_services.service_id
    WHERE bookings.AptNumber = ?
    GROUP BY bookings.AptNumber
");
$query->execute([$apt_number]);
$appointment = $query->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "<script>alert('Appointment not found!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6, #ffffff);
            font-family: 'Poppins', sans-serif;
        }
        .invoice-container {
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .table th {
            background-color: #0d6efd;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        .table td {
            font-size: 16px;
            color: #333;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            font-size: 18px;
            padding: 12px;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #084298;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-container">
        <h2 class="text-center fw-bold text-uppercase mb-4">Invoice Summary</h2>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <tr>
                    <th>Appointment Number</th>
                    <td><?php echo htmlspecialchars($appointment['AptNumber']); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($appointment['ClientName']); ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo htmlspecialchars($appointment['PhoneNumber']); ?></td>
                </tr>
                <tr>
                    <th>Appointment Date</th>
                    <td><?php echo htmlspecialchars($appointment['AptDate']); ?></td>
                </tr>
                <tr>
                    <th>Appointment Time</th>
                    <td><?php echo htmlspecialchars($appointment['AptTime']); ?></td>
                </tr>
                <tr>
                    <th>Booked Services</th>
                    <td><?php echo htmlspecialchars($appointment['Services']); ?></td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td class="fw-bold fs-5">₦<?php echo number_format($appointment['TotalAmount'], 2); ?></td>
                </tr>
            </table>
        </div>

        <form action="paystack_payment.php" method="POST">
            <input type="hidden" name="apt_number" value="<?php echo htmlspecialchars($appointment['AptNumber']); ?>">
            <input type="hidden" name="amount" value="<?php echo $appointment['TotalAmount'] * 100; ?>">
            <button type="submit" class="btn btn-primary w-100 mt-3">Proceed to Payment</button>
        </form>
    </div>
</div>

</body>
</html>
