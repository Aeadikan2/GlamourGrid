<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../admin/includes/db_connect.php');

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice History | Dashboard</title>
    
    <!-- Bootstrap & Dashboard CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css"> <!-- Ensure your custom dashboard styles -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            margin-top: 30px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-view {
            background-color: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        #movetop {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 99;
            border: none;
            outline: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }
        #movetop:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include_once('header.php'); ?>

<div class="container invoice-container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">Invoice History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-dark text-white">
                                <tr> 
                                    <th>#</th> 
                                    <th>Invoice ID</th> 
                                    <th>Customer Name</th>
                                    <th>Invoice Date</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $userid = $_SESSION['user_id'];

                                $stmt = $con->prepare("
                                    SELECT DISTINCT 
                                        users.id AS uid, 
                                        users.name, 
                                        invoice.billing_id, 
                                        DATE(invoice.posting_date) AS posting_date  
                                    FROM users   
                                    JOIN invoice ON users.id = invoice.user_id 
                                    WHERE users.id = ? 
                                    ORDER BY invoice.id DESC
                                ");
                                $stmt->bind_param("i", $userid);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $cnt = 1;
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                ?>
                                        <tr> 
                                            <td><?php echo $cnt; ?></td> 
                                            <td><?php echo htmlspecialchars($row['billing_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['posting_date']); ?></td> 
                                            <td>
                                                <a href="view-invoices.php?invoiceid=<?php echo $row['billing_id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                <?php 
                                        $cnt++;
                                    }   
                                } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-danger fw-bold">No Record Found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!-- .table-responsive -->
                </div> <!-- .card-body -->
            </div> <!-- .card -->
        </div> <!-- .col-lg-10 -->
    </div> <!-- .row -->
</div> <!-- .container -->

<?php include_once('footer.php'); ?>

<!-- Move to Top Button -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <i class="fa fa-arrow-up"></i>
</button>

<script>
// ✅ Scroll to Top Button Functionality
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("movetop").style.display = "block";
    } else {
        document.getElementById("movetop").style.display = "none";
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>

<!-- ✅ Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

</body>
</html>  
