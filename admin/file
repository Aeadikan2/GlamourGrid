<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/db_connect.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_POST['submit'])) {
    $uid = isset($_GET['addid']) ? intval($_GET['addid']) : 0;
    $invoiceid = mt_rand(100000000, 999999999);

    // Check if user exists
    $checkUser = mysqli_query($con, "SELECT id FROM users WHERE id = $uid");
    if (mysqli_num_rows($checkUser) == 0) {
        echo '<script>alert("Error: Invalid user ID.")</script>';
        echo "<script>window.location.href ='invoices.php'</script>";
        exit();
    }

    // Ensure 'sids' is set and is an array
    $sid = isset($_POST['sids']) ? $_POST['sids'] : [];

    if (count($sid) > 0) {
        foreach ($sid as $svid) {
            // Fetch the price of the service
            $serviceQuery = mysqli_query($con, "SELECT price FROM services WHERE ID = $svid");
            if ($serviceData = mysqli_fetch_assoc($serviceQuery)) {
                $price = $serviceData['price'];

                // Insert into invoice table
                $query = "INSERT INTO invoice (user_id, service_id, billing_id, price, quantity, status) 
                          VALUES ('$uid', '$svid', '$invoiceid', '$price', 1, 'Pending')";

                if (!mysqli_query($con, $query)) {
                    echo '<script>alert("Error: ' . mysqli_error($con) . '")</script>';
                }
            } else {
                echo '<script>alert("Error: Service ID ' . $svid . ' not found.")</script>';
            }
        }

        echo '<script>alert("Invoice created successfully. Invoice number is ' . $invoiceid . '")</script>';
        echo "<script>window.location.href ='invoices.php'</script>";
    } else {
        echo '<script>alert("Please select at least one service.")</script>';
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>GlamourGrid || Assign Services</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <!-- Animate CSS -->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/wow.min.js"></script>
    <script>new WOW().init();</script>

    <!-- Metis Menu -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
</head>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>
        <!-- Header -->
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Assign Services</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Assign Services:</h4>
                        <form method="post">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service Name</th>
                                        <th>Service Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM services");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $cnt; ?></th>
                                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                                            <td><input type="checkbox" name="sids[]" value="<?php echo $row['ID']; ?>"></td>
                                        </tr>
                                    <?php
                                        $cnt++;
                                    } ?>
                                    <tr>
                                        <td colspan="4" align="center">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include_once('includes/footer.php'); ?>
    </div>

    <!-- Sidebar Menu Script -->
    <script src="js/classie.js"></script>
    <script>
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
            showLeftPush = document.getElementById('showLeftPush'),
            body = document.body;

        showLeftPush.onclick = function() {
            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
            disableOther('showLeftPush');
        };

        function disableOther(button) {
            if (button !== 'showLeftPush') {
                classie.toggle(showLeftPush, 'disabled');
            }
        }
    </script>

    <!-- Scrolling JS -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="js/bootstrap.js"></script>

</body>

</html>
