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
            <a href="contact.php" class="btn btn-pink btn-lg">Contact Us</a>
            <!-- <a href="book_appointments.php" class="btn btn-pink btn-lg">Book Appointment</a> -->
            
        </div>
    </div>

    <!-- About section -->
    <section class="about">
        <div class="about-layout">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-6 d-flex justify-content-center">
                        <img src="../images/image.png" alt="product" class="img-fluid rounded shadow">
                    </div>
                    <!-- Text Section -->
                    <div class="col-lg-6 mt-4 about-right-faq">
                        <h3 class="title-big">Experience the GlamourGrid Touch</h3>
                        <p class="mt-3 para">
                            Discover a full range of premium services at GlamourGrid, tailored to elevate your style and well-being. Explore our curated offerings and indulge in a beauty experience like no other.
                        </p>
                        <div class="hair-cut row">
                            <!-- List Column 1 -->
                            <div class="col-md-6">
                                <ul class="w3l-right-book">
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=14">Hair wash and Blow dry</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=15">Color & highlights</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=16">Straightening</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=17">Blow Drying</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=18">Hair Treatment</a></li>
                                </ul>
                            </div>
                            <!-- List Column 2 -->
                            <div class="col-md-6">
                                <ul class="w3l-right-book">
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=19">Braiding</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=20">Weaving</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=21">Hair Styling</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=22">Hair Loosening</a></li>
                                    <li><span class="fa fa-check" aria-hidden="true"></span><a href="service_description.php?id=23">Hair Extensions</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-section">
        <h2>Take a Tour of Our Salon</h2>
        <iframe width="720" height="405" src="../images/salon_video.mp4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </section>

    <?php
    include_once('footer.php');
    ?>

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