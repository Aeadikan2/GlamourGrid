<?php
// Database Configuration
$host = 'localhost';
$dbname = 'Aeady salon';
$user = 'root';
$password = '';

// Establish Database Connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize variables
$feedback = '';
$errors = [];

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and Validate Form Data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone) || !preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = "Valid phone number is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    // If no errors, save to database and send email
    if (empty($errors)) {
        try {
            // Save to Database
            $stmt = $pdo->prepare("INSERT INTO inquiries (name, email, phone, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $message]);

            // Send Email Notification
            $to = "aeadikanemmanuel@gmail.com"; // Replace with your admin email
            $subject = "New Inquiry from $name";
            $emailMessage = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
            $headers = "From: aeadikanemmanuel@gmail.com"; // Replace with your domain

            if (mail($to, $subject, $emailMessage, $headers)) {
                $feedback = "Thank you, $name! Your message has been sent successfully.";
            } else {
                $feedback = "Your message was saved, but we couldn't send the email.";
            }
        } catch (PDOException $e) {
            $feedback = "Error saving your message: " . $e->getMessage();
        }
    } else {
        $feedback = "Please correct the following errors: " . implode(" ", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background: lightslategray;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        align-items: center;
    }

    .contact-form {
        backdrop-filter: blur(50);
        border-color: black;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        justify-content: center;
        align-items: center;
    }

    .nav-link {
        font-size: 1rem;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.25rem;
    }

    .nav-link {
        color: #fff;
    }
</style>

</head>
<body>
<?php include_once ('header.php'); ?>

    <div class="contact-form">
        <h2 class="text-center mb-4">Contact Us</h2>

        <?php if (!empty($feedback)): ?>
            <div class="alert alert-<?php echo empty($errors) ? 'success' : 'danger'; ?>">
                <?php echo $feedback; ?>
            </div>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?? ''; ?>" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo $email ?? ''; ?>" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo $phone ?? ''; ?>" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" rows="5" class="form-control" placeholder="Write your message here" required><?php echo $message ?? ''; ?></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-secondary btn-lg">Send Message</button>
            </div>
        </form>
    </div>
        <?php include_once('footer.php'); ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>