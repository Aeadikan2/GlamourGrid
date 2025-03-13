<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$page_title = isset($page_title) ? $page_title : 'GlamourGrid'; // Default page title if not set
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"> <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, white, lightpink);
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .hero-section {
        background-image: url('../images/braid.jpg');
        background-size: cover;
        background-position: contain;
        color: white;
        text-align: center;
        height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .nav-link {
        font-size: 1rem;
        color: #fff;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.25rem;
    }

    .btn-pink {
        background-color: #e83e8c;
        color: white;
        border: none;
    }

    .btn-pink:hover {
        background-color: #d63384;
    }

    .about {
        padding: 50px 0;
        margin-bottom: 10px;
    }

    .about-layout {
        display: flex;
        flex-direction: column;
    }

    .about .container {
        max-width: 1200px;
    }

    .about img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .title-big {
        font-size: 1.8rem;
        font-weight: bold;
        color: inherit;
        text-decoration: none;
    }

    .video-section iframe {
        width: 100%;
        max-width: 720px;
        height: auto;
    }

    .para {
        color: #fff;
        line-height: 1.6;
    }

    .w3l-right-book {
        list-style: none;
        padding: 0;
    }

    .w3l-right-book li {
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .w3l-right-book li span {
        color: #28a745;
        margin-right: 8px;
    }

    .w3l-right-book li a {
        text-decoration: none;
        color: #555;
        transition: color 0.3s ease;
    }

    .w3l-right-book li a:hover {
        color: #000;
    }

    #movetop {
        display: none;
        position: fixed;
        bottom: 85px;
        right: 15px;
        z-index: 99;
        cursor: pointer;
        width: 40px;
        height: 40px;
        border-width: initial;
        background: #f567a6;
        border-style: none;
        border-color: initial;
        -o-border-image: initial;
        border-image: initial;
        outline: none;
        padding: 0px;
        border-radius: 4px;
        opacity: .8;
        transition: all 20.5s ease-out 20s;
    }

    #movetop span {
        color: #fff;
        font-size: 25px;
        line-height: 35px;
    }

    .w3l-inner-banner-main .about-inner {
        padding: 30px 0px 30px;
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        -ms-background-size: cover;
        position: relative;
        display: grid;
        align-items: center;
        z-index: 0;
        text-align: center;
        min-height: 250px;
    }

    .w3l-inner-banner-main .about-inner:before {
        content: "";
        background: rgba(27, 27, 27, 0.4);
        position: absolute;
        top: 0;
        min-height: 100%;
        z-index: -1;
        left: 0;
        right: 0;
    }

    a.stylists-link {
        display: inline-block;
        padding: 5px;
        border-radius: 5px;
        text-decoration: none;
    }

    a.stylists-link:hover {
        background-color: #ffc0cb;
    }

    .video-section {
        /* background-color: #63768D; */
        padding: 50px 20px;
        text-align: center;
    }

    .video-section h1 {
        font-size: 2rem;
        color: #000;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .video-section iframe {
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .service-table {
        margin-top: 50px;
        border-collapse: collapse;
        width: 100%;
    }

    .service-table th,
    .service-table td {
        padding: 15px;
        text-align: left;
    }

    .service-table th {
        background-color: #007bff;
        color: #fff;
    }

    .service-table img {
        width: 100px;
        height: auto;
        border-radius: 8px;
    }

    .table-responsive {
        overflow-x: auto;
        /* Ensure horizontal scrolling on smaller screens */
    }

    .btn-outline-light {
        white-space: nowrap;
        /* Prevent text wrapping */
    }

    .btn-contact {
        background-color: #e83e8c;
        color: white;
        border: none;
    }

    .service-description {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px); /* Adjust the blur value as needed */
        padding: 10px;
        border-radius: 5px;
        display: none;
    }

    .testimony-card {
        background: #fff;
        text-align: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin: 10px;
    }

    .testimony-card img {
        border-radius: 50%;
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>

<body>
<script>
        $(function() {
            $('.navbar-toggler').click(function() {
                $('body').toggleClass('noscroll');
            });
        });
    </script>
    <!-- Navigation Bar -->
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
                        <!-- <li class="nav-item"><a class="nav-link" href="../admin/index.php">Admin</a></li> -->
                        <li class="nav-item">
    <a class="nav-link btn btn-contact" href="signup.php">Signup / Login</a>
</li>
                
                    <?php } else { ?>
                        <!-- <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
                        <li class="nav-item"><a class="nav-link" href="invoice_history.php">Invoice History</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="change-password.php">Settings</a></li> -->
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li> 
                        
                    <li class="nav-item"><a class="nav-link btn btn-contact" href="../users/dashboard.php">Dashboard</a></li>

                    <?php } ?> 
                    <!-- <li class="nav-item"><a class="nav-link text-white" href="contact.php">Contact</a></li> -->
                    <!-- <li class="nav-item"><a class="nav-link btn btn-pink btn-lg" href="book_appointments.php">Book Salon</a></li> -->
                </ul>

            </div>
        </div>
    </nav>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>