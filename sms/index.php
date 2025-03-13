<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlarmourGrid</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
       
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php
    include_once('header.php');
    ?>

    <script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
    <!--bootstrap working-->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- //bootstrap working-->
    <!-- disable body scroll which navbar is in active -->
    <script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            });
        });
    </script>
    <!-- disable body scroll which navbar is in active -->

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1 class="display-4 fw-bold">Welcome To GlarmourGrid</h1>
            <p class="lead">Your go-to destination for luxurious hair and beauty services. Our expert team ensures you leave feeling your best..</p>
            <a href="book_appointments.php" class="btn btn-contact btn-lg">Book Us</a>
            <a href="stylists.php" title="View Stylists" class="btn btn-contact btn-lg" style="margin-left: 10px;">Meet Our Stylists</a>
            <!-- <a href="book_appointments.php" class="btn btn-pink btn-lg">Book Appointment</a> -->
        </div>
    </div>

<!-- About section -->
<section class="about" style="margin-bottom: 50px;">    
    <div class="about-layout">
        <div class="container">
        <h1 class="text-center mt-3 mb-4 title-big">About Us</h1>
            <div class="row align-items-center">
                <!-- Image Section -->
                <div class="col-lg-6 d-flex justify-content-center">
                    <img src="../images/image.png" alt="product" class="img-fluid rounded shadow">
                </div>
                <!-- Text Section -->
                <div class="col-lg-6 mt-4 about-right-faq">
                    <h3 class="title-big">Experience the GlamourGrid Touch</h3>
                    <p class="mt-3 para text-black">
                        Discover a full range of premium services at GlamourGrid, tailored to elevate your style and well-being. Explore our curated offerings and indulge in a beauty experience like no other.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section" id="services">
    <div class="container">
        <h1 class="text-center mb-4 title-big">Our Services</h1>
        <div class="row">
            <?php
            // Connect to the database
            $con = new mysqli('localhost', 'root', '', 'Aeady Salon');

            // Check connection
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            // Fetch services from the database
            $sql = "SELECT * FROM services LIMIT 4";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-6 col-lg-3 mb-4">';
                    echo '<div class="card service-card">';
                    echo '<img src="' . $row["image"] . '" class="card-img-top" alt="' . $row["service_name"] . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["service_name"] . '</h5>';
                    echo '<p class="service-description">' . $row["description"] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }

            $con->close();
            ?>
        </div>
        <div id="moreServices" class="row" style="display: none;">
            <?php
            // Connect to the database
            $con = new mysqli('localhost', 'root', '', 'Aeady Salon');

            // Check connection
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            // Fetch more services from the database
            $sql = "SELECT * FROM services LIMIT 6 OFFSET 4";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-6 col-lg-3 mb-4">';
                    echo '<div class="card service-card">';
                    echo '<img src="' . $row["image"] . '" class="card-img-top" alt="' . $row["service_name"] . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["service_name"] . '</h5>';
                    echo '<p class="service-description">' . $row["description"] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }

            $con->close();
            ?>
        </div>
        <div class="text-center">
            <button id="seeMoreBtn" class="btn btn-primary">See More <span class="fa fa-arrow-down"></span></button>
        </div>
    </div>
</section>

<!-- Testimonies Section -->
<section class="testimonies-section">
    <div class="container">
        <h1 class="text-center mt-5 mb-4 title-big">What Our Clients Say</h1>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="testimony-card">
                    <img src="../images/c1.jpg" alt="Client 1">
                    <h5>Client 1</h5>
                    <p>"Amazing service! I felt so pampered and my hair looks fantastic."</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="testimony-card">
                    <img src="../images/c2.jpg" alt="Client 2">
                    <h5>Client 2</h5>
                    <p>"The best salon experience I've ever had. Highly recommend!"</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="testimony-card">
                    <img src="../images/c3.jpg" alt="Client 3">
                    <h5>Client 3</h5>
                    <p>"Professional and friendly staff. My new go-to salon."</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="testimony-card">
                    <img src="../images/t1.jpg" alt="Client 4">
                    <h5>Client 4</h5>
                    <p>"Exceptional service and beautiful results. Love it!"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Us Section -->
<?php
include_once('contact.php');?>


<!-- Video Section -->
<section class="video-section">
    <h1>Take a Tour of Our Salon</h1>
    <iframe src="../images/salon_video.mp4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</section>

<?php
include_once('footer.php');
?>

<!-- see more -->
<script>
    document.getElementById('seeMoreBtn').addEventListener('click', function() {
        var moreServices = document.getElementById('moreServices');
        if (moreServices.style.display === 'none') {
            moreServices.style.display = 'flex';
            this.innerHTML = 'See Less <span class="fa fa-arrow-up"></span>';
        } else {
            moreServices.style.display = 'none';
            this.innerHTML = 'See More <span class="fa fa-arrow-down"></span>';
        }
    });

    document.querySelectorAll('.service-card img').forEach(function(img) {
        img.addEventListener('mouseover', function() {
            this.nextElementSibling.querySelector('.service-description').style.display = 'block';
        });
        img.addEventListener('mouseout', function() {
            this.nextElementSibling.querySelector('.service-description').style.display = 'none';
        });
    });
</script>
<!-- move top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-long-arrow-up"></span>
</button>
<script>
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("movetop").style.display = "block";
        } else {
            document.getElementById("movetop").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>