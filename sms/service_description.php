<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection (Embedded)
$host = "localhost"; // Your database host (e.g., localhost)
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "aeady salon"; // Replace with your actual database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in URL and is a number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $service_id = $_GET['id'];

    // Prepare SQL query to fetch service details
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if service exists
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "<p>Service not found.</p>";
        exit;
    }
} else {
    echo "<p>Invalid request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service['service_name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<style>
    body {
        background: url('../images/grey.jpeg') no-repeat center center fixed;
        background-size: cover;
        color: #fff;
    }

    .nav-link {
        font-size: 1rem;
        color: #fff;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.25rem;
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand text-white" href="index.php">GlamourGrid</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="services.php">Services</a></li>
                    <?php if (empty($_SESSION['user_id'])) { ?>
                        <li class="nav-item"><a class="nav-link" href="../admin/index.php">Admin</a></li>
                        <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="book_appointments.php">Book Salon</a></li>
                        <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
                        <li class="nav-item"><a class="nav-link" href="invoice_history.php">Invoice History</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="change_password.php">Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link text-white" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center"><?php echo htmlspecialchars($service['service_name']); ?></h2>
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($service['image']); ?>" class="img-fluid rounded" alt="Service Image">
            </div>
            <div class="col-md-6">
                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                <p><strong>Price:</strong> ₦<?php echo number_format($service['price'], 2); ?></p>
                <a href="book_appointments.php?service_id=<?php echo $service['id']; ?>" class="btn btn-primary">Book Now</a>
            </div>
        </div>
    </div>
    <?php include_once('footer.php'); ?>

</body>

</html>