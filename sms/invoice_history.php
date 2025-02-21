<?php 
session_start();
error_reporting(0);
include('includes/db_connect.php');

// ✅ Fix: Use correct session variable for authentication
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>GlamourGrid | Invoice History</title>
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body id="home">

<?php include_once('header.php'); ?>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Disable body scroll when navbar is active -->
<script>
$(function () {
    $('.navbar-toggler').click(function () {
        $('body').toggleClass('noscroll');
    });
});
</script>

<!-- Breadcrumbs -->
<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Invoice History</h3>
                <p class="tiltle-para">Track all your past invoices here.</p>
            </div>
        </div>
    </div>
    <!-- <div class="breadcrumbs-sub">
        <div class="container">   
            <ul class="breadcrumbs-custom-path">
                <li><a href="index.php">Home <span class="fa fa-angle-right"></span></a></li>
                <li class="active">Invoice History</li>
            </ul>
        </div>
    </div> -->
</section>

<!-- Invoice Table -->
<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
        <div class="container">
            <div class="table-content table-responsive cart-table-content">
                <h4 style="padding-bottom: 20px; text-align: center; color: black;">Invoice History</h4>
                <table class="table table-bordered">
                    <thead>
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
                                users.ID AS uid, 
                                users.name, 
                                users.email, 
                                invoice.billing_Id, 
                                DATE(invoice.posting_date) AS posting_date  
                            FROM users   
                            JOIN invoice ON users.ID = invoice.user_id 
                            WHERE users.ID = ? 
                            ORDER BY invoice.ID DESC
                        ");
                        $stmt->bind_param("i", $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $cnt = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <tr> 
                                    <th scope="row"><?php echo $cnt; ?></th> 
                                    <td><?php echo htmlspecialchars($row['billing_Id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['posting_date']); ?></td> 
                                    <td>
                                        <a href="view-invoices.php?invoiceid=<?php echo $row['billing_Id']; ?>" class="btn btn-info">View</a>
                                    </td>
                                </tr>
                        <?php 
                                $cnt++;
                            } 
                        } else { ?>
                            <tr>
                                <th colspan="5" class="text-center" style="color:red">No Record Found</th>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</section>

<?php include_once('footer.php'); ?>


<!-- Move to Top Button -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-long-arrow-up"></span>
</button>

<script>
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

</body>
</html>
