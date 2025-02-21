<?php
require_once('includes/db_connect.php');
session_start();

// Fetch stylists from the database
$stylists_query = "SELECT * FROM stylists";
$stylists_result = $con->query($stylists_query);
$stylists = [];
if ($stylists_result) {
    while ($row = $stylists_result->fetch_assoc()) {
        $stylists[] = $row;
    }
}

// Handle form submission for adding a new stylist with an image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $stylistName = $_POST['stylistName'];
    $image = $_FILES["image"]["name"];

    // Get the image extension
    $extension = strtolower(substr($image, strlen($image) - 4, strlen($image)));

    // Allowed extensions
    $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

    // Validate image extension
    if (!in_array($extension, $allowed_extensions)) {
        $_SESSION['error'] = "Invalid format. Only jpg / jpeg / png / gif formats allowed.";
    } else {
        // Rename the image file
        $newimage = md5($image) . time() . $extension;

        // Move the uploaded image to the images directory
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $newimage);

        // Insert the stylist into the database
        $query = $con->prepare("INSERT INTO stylists (name, available, image) VALUES (?, 1, ?)");
        $query->bind_param("ss", $stylistName, $newimage);

        if ($query->execute()) {
            $_SESSION['message'] = "Stylist has been added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add stylist.";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle availability status toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $stylistId = $_POST['stylist_id'];
    $currentAvailable = $_POST['current_available'];

    // Toggle the availability status
    $newAvailableStatus = $currentAvailable == 1 ? 0 : 1;

    // Update the stylist's availability in the database
    $query = $con->prepare("UPDATE stylists SET available = ? WHERE id = ?");
    $query->bind_param("ii", $newAvailableStatus, $stylistId);

    if ($query->execute()) {
        $_SESSION['message'] = "Stylist availability updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update stylist availability.";
    }

    // Redirect to the same page to avoid resubmission issues
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Stylists</title>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href="css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .tables {
            margin: auto;
            max-width: 1200px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2.text-center {
            margin-bottom: 20px;
        }

        .table {
            margin-top: 20px;
        }

        .alert {
            margin-bottom: 20px;
        }

        footer {
            margin-top: auto;
            background: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>

<body class="cbp-spmenu-push">
<div class="main-content">
<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <?php include_once('includes/sidebar.php'); ?>
            <?php include_once('includes/header.php'); ?>

            <h2 class="text-center">Manage Stylists</h2>

            <!-- Feedback Messages -->
            <?php if (!empty($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-striped mt-5">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Available</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($stylists) > 0): ?>
                        <?php foreach ($stylists as $index => $stylist): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($stylist['name']); ?></td>
                                <td>
                                    <?php if (!empty($stylist['image'])): ?>
                                        <img src="images/<?php echo $stylist['image']; ?>" width="50" height="50">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $stylist['available'] == 1 ? 'Available' : 'Not Available'; ?>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="stylist_id" value="<?php echo $stylist['id']; ?>">
                                        <input type="hidden" name="current_available" value="<?php echo $stylist['available']; ?>">
                                        <button type="submit" name="toggle_status" class="btn btn-<?php echo $stylist['available'] == 1 ? 'danger' : 'success'; ?>">
                                            <?php echo $stylist['available'] == 1 ? 'Mark Not Available' : 'Mark Available'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No stylists found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
</html>