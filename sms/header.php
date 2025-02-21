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
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background: lightslategray;
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
        height: 100vh;
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
        background-color: rgb(208, 8, 155);
        display: inline-block;
        padding: 5px;
        border-radius: 5px;
        text-decoration: none;
    }

    a.stylists-link:hover {
        background-color: #ffc0cb;
        /* Slightly darker pink for hover effect */
    }

    .video-section {
        background-color: #63768D;
        padding: 50px 20px;
        text-align: center;
    }

    .video-section h2 {
        font-size: 2rem;
        color: #000;
        margin-bottom: 20px;
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
            overflow-x: auto; /* Ensure horizontal scrolling on smaller screens */
        }

        .btn-outline-light {
            white-space: nowrap; /* Prevent button text from wrapping */
        }
</style>



<body>
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
                        <li class="nav-item"><a class="nav-link" href="../admin/index.php">Admin</a></li>
                        <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="booking_history.php">Booking History</a></li>
                        <!-- <li class="nav-item"><a class="nav-link" href="invoice_history.php">Invoice History</a></li> -->
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="change-password.php">Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link text-white" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-pink btn-lg" href="book_appointments.php">Book Salon</a></li>
                </ul>
                <a href="stylists.php" title="View Stylists" class="stylists-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-calendar2-check" viewBox="0 0 16 16">
                    <path d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                    <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </a>
            </div>
        </div>
    </nav>
</body>

</html>