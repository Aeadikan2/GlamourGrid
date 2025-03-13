<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Database connection
    $con = new mysqli('localhost', 'root', '', 'Aeady Salon');

    // Check connection
    if ($con->connect_error) {
        die('<div class="alert alert-danger">Database connection failed!</div>');
    }

    // Collect form data & sanitize
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    // $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $message = htmlspecialchars(trim($_POST['message']));
    $full_name = $first_name . " " ;

    if (empty($first_name) || empty($email) || empty($phone) || empty($message)) {
        $error = "All fields are required!";
    } else {
        // Insert data using prepared statement
        $stmt = $con->prepare("INSERT INTO inquiries (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $phone, $message);

        if ($stmt->execute()) {
            $success = "Your message has been sent successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
    $con->close();
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
        }
        .contact-section {
            padding: 60px 0;
        }
        .contact-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-info {
            width: 40%;
        }
        .contact-info h5 {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .contact-info p {
            margin-bottom: 20px;
            color: #555;
        }
        .contact-form {
            width: 55%;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .form-control {
            background: #f1f1f1;
            border: none;
            padding: 10px;
            margin-bottom: 10px;
        }
        .btn-contact {
            background: linear-gradient(135deg, #ff758c, #ff7eb3);
            color: #fff;
            padding: 10px 20px;
            border: none;
            /* width: 100%; */
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-contact:hover {
            background: linear-gradient(135deg, #ff7eb3, #ff758c);
        }
        @media (max-width: 768px) {
            .contact-box {
                flex-direction: column;
                text-align: center;
            }
            .contact-info, .contact-form {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<section class="contact-section">
    <div class="container">
        <div class="contact-box">
            <!-- Contact Info Section -->
            <div class="contact-info">
                <h5><i class="fas fa-phone"></i> Call Us</h5>
                <p>+2349038585075</p>
                <h5><i class="fas fa-envelope"></i> Email Us</h5>
                <p>apanel@gmail.com</p>
                <h5><i class="fas fa-map-marker-alt"></i> Address</h5>
                <p>Karu Site, Abuja.</p>
                <h5><i class="fas fa-clock"></i> Time</h5>
                <p>10:30 am to 7:30 pm</p>
            </div>

            <!-- Contact Form Section -->
            <div class="contact-form">
                <?php
                if (isset($error)) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                if (isset($success)) {
                    echo '<div class="alert alert-success">' . $success . '</div>';
                }
                ?>
                <form action="" method="POST">
                    <!-- <div class="row"> -->
                        <div class="">
                            <input type="text" name="first_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <!-- <div class="col-md-6">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div> -->
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-md-6">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <textarea name="message" class="form-control" rows="4" placeholder="Message" required></textarea>
                    <button type="submit" name="submit" class="btn btn-contact mt-2 w-100%">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
