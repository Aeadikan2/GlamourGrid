<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../admin/includes/db_connect.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}

// Get invoice ID from URL and validate
$invoiceid = isset($_GET['invoiceid']) ? intval($_GET['invoiceid']) : 0;
if ($invoiceid === 0) {
    die("Error: No invoice ID found.");
}

// Fetch invoice and user details
$query = mysqli_query($con, "
    SELECT DISTINCT DATE(invoice.posting_date) AS invoicedate, 
        users.name, users.email, users.created_at, invoice.billing_id
    FROM invoice 
    JOIN users ON users.id = invoice.user_id 
    WHERE invoice.billing_id = '$invoiceid'
");

$invoiceData = mysqli_fetch_assoc($query);
if (!$invoiceData) {
    die("Error: Invoice not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice Details | Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            text-align: left;
        }
        .print-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .btn-print {
            width: 100%;
            max-width: 200px;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .invoice-container {
                padding: 15px;
            }
            h4 {
                font-size: 1.2rem;
            }
            .table th, .table td {
                font-size: 14px;
            }
        }

        /* Print Styles */
        @media print {
            .print-btn {
                display: none;
            }
            body {
                background: #fff !important;
            }
        }
    </style>
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container mt-4">
    <div class="invoice-container">
        <h4 class="text-center fw-bold">Invoice #<?php echo $invoiceid; ?></h4>
        
        <!-- Customer Details -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr><th colspan="4" class="text-left text-black">Customer Details</th></tr>
                <tr>
                    <th>Name</th><td><?php echo htmlspecialchars($invoiceData['name']); ?></td>
                    <th>Email</th><td><?php echo htmlspecialchars($invoiceData['email']); ?></td>
                </tr>
                <tr>
                    <th>Registration Date</th><td><?php echo htmlspecialchars($invoiceData['created_at']); ?></td>
                    <th>Invoice Date</th><td><?php echo htmlspecialchars($invoiceData['invoicedate']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Services Details -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr><th colspan="3" class="text-left text-black">Services Details</th></tr>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Price</th>
                </tr>

                <?php
                $serviceQuery = mysqli_query($con, "
                    SELECT services.service_name, services.price  
                    FROM invoice 
                    JOIN services ON services.id = invoice.service_id
                    WHERE invoice.billing_id = '$invoiceid'
                ");

                $cnt = 1;
                $grandTotal = 0;
                while ($service = mysqli_fetch_assoc($serviceQuery)) {
                    $subtotal = $service['price'];
                    $grandTotal += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                        <td><?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php } ?>

                <tr>
                    <th colspan="2" class="text-end">Grand Total</th>
                    <th><?php echo number_format($grandTotal, 2); ?></th>
                </tr>
            </table>
        </div>

        <!-- Print Button -->
        <div class="print-btn">
            <button class="btn btn-primary btn-print" onclick="printInvoice()">
                <i class="fa fa-print"></i> Print Invoice
            </button>
        </div>
    </div>
</div>

<?php include_once('footer.php'); ?>

<script>
    function printInvoice() {
        window.print();
    }
</script>

<!-- ✅ Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>  
