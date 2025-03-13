<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/db_connect.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

// Ensure invoice ID is provided
if (!isset($_GET['invoiceid']) || empty($_GET['invoiceid'])) {
    die("Error: No invoice ID found.");
}

$invoiceid = intval($_GET['invoiceid']);


?>

<!DOCTYPE HTML>
<html>

<head>
    <title>GlarmourGrid || Invoice Details</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
</head>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Invoice Details</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Invoice #<?php echo $invoiceid; ?></h4>
                        <?php
                        $invid = intval($_GET['invoiceid']);
                        $ret = mysqli_query($con, "select DISTINCT  date(invoice.posting_date) as invoicedate,users.name,users.email,users.created_at,invoice.billing_id
    from  invoice 
    join users on users.id=invoice.user_id
    where invoice.billing_id='$invid'");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {

                        ?>
                            <table class="table table-bordered" width="100%" border="1">
                                <tr>
                                    <th colspan="6">Customer Details</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $row['name'] ?></td>
                                    <th>Email </th>
                                    <td><?php echo $row['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Registration Date</th>
                                    <td><?php echo $row['created_at'] ?></td>
                                    <th>Invoice Date</th>
                                    <td colspan="3"><?php echo $row['invoicedate'] ?></td>
                                </tr>
                            <?php } ?>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Posting Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT invoice.*, services.service_name 
                                          FROM invoice 
                                          JOIN services ON invoice.service_id = services.id 
                                          WHERE invoice.billing_id = '$invoiceid'";
                                    $result = mysqli_query($con, $query);
                                    $cnt = 1;
                                    $grandTotal = 0;

                                    while ($row = mysqli_fetch_array($result)) {
                                        $total = $row['price'] * $row['quantity'];
                                        $grandTotal += $total;
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $cnt; ?></th>
                                            <td><?php echo $row['service_name']; ?></td>
                                            <td><?php echo number_format($row['price'], 2); ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo number_format($total, 2); ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['posting_date']; ?></td>
                                        </tr>
                                    <?php
                                        $cnt++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" align="right"><strong>Grand Total:</strong></td>
                                        <td><strong><?php echo number_format($grandTotal, 2); ?></strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="margin-top:1%" align="center">
                    <i class="fa fa-print fa-2x" style="cursor: pointer;" OnClick="CallPrint(this.value)"></i>
                  </p>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="js/bootstrap.js"></script>
    <script>
function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
}
</script>
</body>

</html>