<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "aeady salon";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services from the database
$sql = "SELECT service_name, description, image, price FROM services";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        } */
        
    </style>
</head>
<body>
<?php include_once('header.php'); ?>
<script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            });
        });
    </script>
    <div class="container">
        <h1 class="text-center mt-5">Our Services</h1>
        <table class="service-table table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Service</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['service_name']); ?>"></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No services available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php include_once('footer.php'); ?>

</body>
</html>

<?php $conn->close(); ?>
