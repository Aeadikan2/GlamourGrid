<?php
session_start();
include 'includes/db_connect.php';

// ✅ Redirect if admin is not logged in
if (!isset($_SESSION['bpmsaid'])) {
    header("Location: logout.php");
    exit();
}

// ✅ Handle review deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    if ($stmt = $con->prepare("DELETE FROM reviews WHERE id = ?")) {
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            echo "<script>alert('Review deleted successfully!'); window.location.href='view-reviews.php';</script>";
        } else {
            echo "<script>alert('Error deleting review. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing delete statement.');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Admin | Client Reviews</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
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
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Header -->
        <?php include 'includes/header.php'; ?>

        <!-- Main content -->
        <div id="page-wrapper">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Client Reviews</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>All Reviews:</h4>
                        <table class="table table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Title</th>
                                    <th>Review</th>
                                    <th>Date Posted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // ✅ Fetch all reviews along with user name
                                if ($stmt = $con->prepare("
                                    SELECT reviews.id, reviews.title, reviews.content, reviews.created_at, users.name 
                                    FROM reviews 
                                    JOIN users ON reviews.user_id = users.id 
                                    ORDER BY reviews.created_at DESC
                                ")) {
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$cnt}</td>
                                                    <td>".htmlspecialchars($row['name'])."</td>
                                                    <td>".htmlspecialchars($row['title'])."</td>
                                                    <td>".htmlspecialchars($row['content'])."</td>
                                                    <td>".date("F j, Y, g:i A", strtotime($row['created_at']))."</td>
                                                    <td>
                                                        <a href='view-reviews.php?delete_id={$row['id']}' 
                                                           class='btn btn-danger btn-sm' 
                                                           onclick='return confirm(\"Are you sure you want to delete this review?\");'>
                                                            <i class='fa fa-trash'></i> Delete
                                                        </a>
                                                    </td>
                                                  </tr>";
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center text-muted'>No reviews found.</td></tr>";
                                    }
                                    $stmt->close();
                                } else {
                                    echo "<tr><td colspan='6' class='text-center text-danger'>Error fetching reviews.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- JS Scripts -->
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
    <!-- Scroll JS -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.js"></script>

</body>
</html>

<?php $con->close(); ?>
