<?php
// Database connection
$host = 'localhost';
$dbname = 'aeady salon';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new stylist creation with image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'], $_POST['stylist_name'])) {
    $stylist_name = $_POST['stylist_name'];

    // Handle file upload
    $image = $_FILES["image"]["name"];
    $image_temp = $_FILES["image"]["tmp_name"];
    $image_error = $_FILES["image"]["error"];
    $image_size = $_FILES["image"]["size"];
    
    // Get file extension
    $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    
    // Allowed image extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Validate image extension
    if (!in_array($image_extension, $allowed_extensions)) {
        $_SESSION['error'] = "Invalid format. Only jpg / jpeg / png / gif formats allowed.";
    } else {
        // Rename the image file
        $new_image_name = md5($image . time()) . '.' . $image_extension;
        $image_folder = 'uploads/stylists_images/';
        
        // Move the uploaded image to the images directory
        if (move_uploaded_file($image_temp, $image_folder . $new_image_name)) {
            // Insert stylist into the database
            $stmt = $conn->prepare("INSERT INTO stylists (name, available, image) VALUES (?, 1, ?)");
            $stmt->bind_param("ss", $stylist_name, $new_image_name);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Stylist added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add stylist.";
            }
        } else {
            $_SESSION['error'] = "Failed to upload the image.";
        }
    }
}

// Fetch stylists
$stylists_query = "SELECT * FROM stylists";
$stylists_result = $conn->query($stylists_query);
$stylists = [];
while ($row = $stylists_result->fetch_assoc()) {
    $stylists[] = $row;
}

// Fetch client queue (Bookings)
$queue_query = "
    SELECT bookings.id AS booking_id, bookings.AptNumber, bookings.AptTime, bookings.status, 
           stylists.name AS StylistName, stylists.image AS StylistImage
    FROM bookings 
    LEFT JOIN stylists ON bookings.StylistId = stylists.id 
    ORDER BY bookings.id ASC";
$queue_result = $conn->query($queue_query);
$queue = [];
while ($row = $queue_result->fetch_assoc()) {
    $queue[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourGrid Stylist Availability & Queue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once('header.php'); ?>
    <div class="container py-5">
        <!-- Stylist Availability -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Available Stylists</h3>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($stylists as $stylist): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <?php if (!empty($stylist['image'])): ?>
                                    <img src="uploads/stylists_images/<?php echo $stylist['image']; ?>" alt="Stylist Image" width="50" class="rounded-circle me-2">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($stylist['name']); ?>
                            </div>
                            <?php if ($stylist['available']): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Unavailable</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Client Queue -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Client Queue</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Queue Position</th>
                            <th>Appointment Number</th>
                            <th>Appointment Time</th>
                            <th>Stylist</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($queue as $index => $entry): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($entry['AptNumber']); ?></td>
                                <td><?php echo htmlspecialchars($entry['AptTime']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($entry['StylistName'] ?? 'Pending'); ?>
                                    <?php if (!empty($entry['StylistImage'])): ?>
                                        <img src="uploads/stylists_images/<?php echo $entry['StylistImage']; ?>" alt="Stylist Image" width="30" class="rounded-circle ms-2">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $status_badge = match ($entry['status']) {
                                        'Pending' => 'badge bg-warning text-dark',
                                        'In Progress' => 'badge bg-primary',
                                        'Completed' => 'badge bg-success',
                                        default => 'badge bg-secondary',
                                    };
                                    ?>
                                    <span class="<?php echo $status_badge; ?>">
                                        <?php echo htmlspecialchars($entry['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include_once('footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
