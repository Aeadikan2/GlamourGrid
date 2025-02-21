<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - GlarmourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .about-section {
            padding: 50px 0;
        }
        .about-container {
            max-width: 1200px;
            margin: auto;
        }
        .about-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .about-content {
            display: flex;
            align-items: center;
        }
        .about-text {
            padding: 20px;
        }
        .about-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .about-description {
            font-size: 1rem;
            line-height: 1.8;
            color: #000;
        }
    </style>
</head>
<body>
   <?php include_once('header.php'); ?>

    <!-- About Section -->
    <section class="about-section">
        <div class="about-container container">
            <!-- Row 1 -->
            <div class="row about-content mb-5">
                <div class="col-md-6">
                    <img src="../images/image.png" alt="About Us" class="about-image">
                </div>
                <div class="col-md-6 about-text">
                    <h2 class="about-title">About GlarmourGrid</h2>
                    <p class="about-description">
                        Welcome to GlarmourGrid, your ultimate destination for top-notch beauty and grooming services. Our team of professional stylists and beauticians is dedicated to enhancing your look and boosting your confidence.
                        We specialize in a wide array of services, from haircuts and coloring to skincare and body therapies, ensuring you leave feeling rejuvenated.
                    </p>
                </div>
            </div>
            <!-- Row 2 -->
            <div class="row about-content mb-5">
                <div class="col-md-6 order-md-2">
                    <img src="../images/m2.avif" alt="Our Mission" class="about-image">
                </div>
                <div class="col-md-6 about-text">
                    <h2 class="about-title">Our Mission</h2>
                    <p class="about-description">
                        At Scissors Salon, our mission is to deliver unparalleled service in a relaxing and luxurious environment. We believe everyone deserves to look and feel their best, and we are here to make that happen.
                        Our commitment to using premium products and the latest techniques ensures an exceptional experience for every client.
                    </p>
                </div>
            </div>
            <!-- Row 3 -->
            <div class="row about-content">
                <div class="col-md-6">
                    <img src="../images/w1.avif" alt="Why Choose Us" class="about-image">
                </div>
                <div class="col-md-6 about-text">
                    <h2 class="about-title">Why Choose Us?</h2>
                    <p class="about-description">
                        We stand out for our attention to detail, customized services, and friendly atmosphere. Whether you're looking for a quick trim or a full makeover, we tailor our services to meet your individual needs. 
                        Experience the Scissors Salon difference and discover why our clients keep coming back.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include('footer.php'); ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
