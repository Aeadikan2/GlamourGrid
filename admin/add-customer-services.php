<?php
session_start();
include('includes/db_conect.php');
if (!isset($_SESSION['bpmsaid']) || $_SESSION['bpmsaid'] == 0) {
    header('location:logout.php');
    exit();
}

if (!isset($_GET['addid'])) {
    echo "No user ID provided.";
    exit();
}

$userId = intval($_GET['addid']);

$user_result = mysqli_query($con, "SELECT id, name FROM users WHERE id = $userId");
if ($user_result === false) {
    die("Error executing query: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    echo "<script>alert('No user found with the given ID.');</script>";
    echo "<script>window.location.href='customer-list.php';</script>";
    exit();
}

$services_result = mysqli_query($con, "SELECT id, service_name, price FROM services");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['service_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $totalAmount = $price * $quantity;

    $invoiceId = uniqid($userId . '-');

    $stmt = $con->prepare("INSERT INTO invoice (user_id, service_id, billing_id, price, quantity, total_amount, status, posting_date) VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iisidd", $userId, $serviceId, $invoiceId, $price, $quantity, $totalAmount);

    if ($stmt->execute()) {
        echo "<script>alert('Service assigned successfully and invoice generated!');</script>";
        echo "<script>window.location.href='customer-list.php'</script>";
    } else {
        echo "<script>alert('Failed to assign service: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Customer Services</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css">
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/wow.min.js"></script>
    <script>new WOW().init();</script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>
    <link href="css/custom.css" rel="stylesheet">
</head>
<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Assign Services to <?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <form method="POST" action="" class="form-horizontal">
                        <div class="form-group">
                            <label for="service_id" class="col-sm-2 control-label">Select Service:</label>
                            <div class="col-sm-10">
                                <select name="service_id" id="service_id" class="form-control" required onchange="updatePrice(this)">
                                    <option value="">Select a service</option>
                                    <?php while ($service = mysqli_fetch_assoc($services_result)) { ?>
                                        <option value="<?php echo $service['id']; ?>" data-price="<?php echo $service['price']; ?>">
                                            <?php echo $service['service_name']; ?> - $<?php echo $service['price']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">Price:</label>
                            <div class="col-sm-10">
                                <input type="text" name="price" id="price" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-sm-2 control-label">Quantity:</label>
                            <div class="col-sm-10">
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Assign Service</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script>
        function updatePrice(selectElement) {
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var price = selectedOption.getAttribute('data-price');
            document.getElementById('price').value = price;
        }
    </script>
    <script src="js/classie.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>