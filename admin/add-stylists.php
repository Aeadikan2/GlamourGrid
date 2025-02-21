<?php
session_start();
error_reporting(0);
include('includes/db_connect.php');

// Redirect to login if not logged in
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $stylistName = $_POST['stylistName'];
        $image = $_FILES["image"]["name"];

        // Get the image extension
        $extension = substr($image, strlen($image) - 4, strlen($image));

        // Allowed extensions
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");

        // Validate image extension
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif formats allowed');</script>";
        } else {
            // Rename the image file
            $newimage = md5($image) . time() . $extension;

            // Move the uploaded image to the images directory
            move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $newimage);

            // Insert the stylist into the database
            $query = mysqli_query($con, "INSERT INTO stylists(name, available, image) VALUES('$stylistName', 1, '$newimage')");

            if ($query) {
                echo "<script>alert('Stylist has been added successfully.');</script>";
                echo "<script>window.location.href = 'add-stylists.php';</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Admin | Add Stylist</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
</head>
<body class="cbp-spmenu-push">
<div class="main-content">
    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php'); ?>
    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Main Content -->
    <div id="page-wrapper">
        <div class="main-page">
            <div class="forms">
                <h3 class="title1">Add Stylist</h3>
                <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                    <div class="form-title">
                        <h4>Add Stylist Details:</h4>
                    </div>
                    <div class="form-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="stylistName">Stylist Name</label>
                                <input type="text" class="form-control" id="stylistName" name="stylistName" placeholder="Enter stylist name" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-default">Add Stylist</button>
                        </form>
                    </div>
                </div>

                <!-- Display Available Stylists
                <div class="widget-shadow">
                    <h3 class="title1">Available Stylists</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ret = mysqli_query($con, "SELECT * FROM stylists WHERE available = 1");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                            ?>
                            <tr>
                                <td><?php echo $cnt; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><img src="images/<?php echo $row['image']; ?>" width="50" height="50"></td>
                                <td>Available</td>
                            </tr>
                            <?php
                            $cnt++;
                        }
                        ?>
                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>
</div>

<!-- Scripts -->
<script src="js/classie.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
<?php {} ?>  
