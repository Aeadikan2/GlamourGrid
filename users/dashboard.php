<?php
session_start();
include 'header.php';

// Database Connection
$conn = new mysqli("localhost", "root", "", "Aeady salon");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch upcoming appointment
$today = date("Y-m-d");
$appt_stmt = $conn->prepare("SELECT AptTime FROM bookings WHERE UserID = ? AND AptDate = ? AND reminded = 0");
$appt_stmt->bind_param("is", $user_id, $today);
$appt_stmt->execute();
$appt_result = $appt_stmt->get_result();
$appointment = $appt_result->fetch_assoc();
$appt_stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourGrid || UserDashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    
<div class="container mt-5 mb-5">
    <h2 class="text-center fw-bold">User Dashboard</h2>
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <img src="../sms/uploads/<?php echo !empty($user['image']) ? htmlspecialchars($user['image']) : 'default-avatar.jpg'; ?>"
                    class="rounded-circle mb-3" width="120" height="120"
                    alt="Profile Picture"
                    onerror="this.onerror=null;this.src='../sms/uploads/default-avatar.jpg';">

                <h4 class="fw-bold"><?php echo htmlspecialchars($user['name'] ?? ''); ?></h4>

                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item"><i class="fas fa-user me-2"></i><a href="profile.php"> Personal Info</a></li>
                    <!-- <li class="list-group-item"><i class="fas fa-heart me-2"></i> <a href="wishlist.php">Wishlist</a></li> -->
                    <li class="list-group-item"><i class="fas fa-images me-2"></i><a href="gallery.php"> Gallery</a></li>
                    <li class="list-group-item"><i class="fas fa-star me-2"></i> <a href="reviews.php"> Feedback</a></li>
                    <li class="list-group-item"><i class="fas fa-calendar-alt me-2"></i> <a href="all-appointment.php"> My Appointments</a></li>
                    <li class="list-group-item"><i class="fas fa-file-invoice-dollar me-2"></i> <a href="invoice_history.php"> My Invoices</a></li>
                    <li class="list-group-item"><i class="fas fa-lock me-2"></i><a href="change-password.php"> Change Password</a></li>
                    <li class="list-group-item text-danger">
                        <a href="logout.php" class="text-danger text-decoration-none">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-md-8 mt-5">
            <div class="card p-4 shadow-sm">
                <h4 class="fw-bold">
                    <i class="bi bi-person-circle me-2"></i>Welcome, <?php echo htmlspecialchars($user['name'] ?? ''); ?>!
                </h4>

                <div class="mt-4">
                    <h5 class="text-secondary"><i class="bi bi-info-circle me-2"></i> Personal Information</h5>
                    <p><strong><i class="bi bi-envelope-fill me-2"></i>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    <p><strong><i class="bi bi-telephone-fill me-2"></i>Phone:</strong> 
                        <?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : '<span class="text-muted">Not provided</span>'; ?>
                    </p>
                    <p><strong><i class="bi bi-globe2 me-2"></i>Country:</strong> 
                        <?php echo !empty($user['country']) ? htmlspecialchars($user['country']) : '<span class="text-muted">Not provided</span>'; ?>
                    </p>
                    <p><strong><i class="bi bi-geo-alt-fill me-2"></i>Address:</strong> 
                        <?php echo !empty($user['address']) ? htmlspecialchars($user['address']) : '<span class="text-muted">Not provided</span>'; ?>
                    </p>
                    <p><strong><i class="bi bi-card-text me-2"></i>Bio:</strong> 
                        <?php echo !empty($user['bio']) ? htmlspecialchars($user['bio']) : '<span class="text-muted">No bio available</span>'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($appointment) { ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        showReminderPopup("<?php echo $appointment['AptTime']; ?>");
    });

    function showReminderPopup(time) {
        let popup = document.createElement("div");
        popup.id = "reminderPopup";
        popup.innerHTML = `
            <i class="fa fa-calendar-check-o"></i>
            <div>
                <strong>Upcoming Appointment</strong>
                <p>Your appointment is scheduled for <b>${time}</b>.</p>
            </div>
            <button class="dismiss-btn" onclick="dismissReminder()">Dismiss</button>
        `;

        document.body.appendChild(popup);

        // ✅ Auto-dismiss after 10 seconds
        setTimeout(() => {
            if (popup) popup.remove();
        }, 10000);
    }

    function dismissReminder() {
        let popup = document.getElementById("reminderPopup");
        if (popup) popup.remove();
    }
</script>

<style>
/* ✅ Modern Popup Styling */
#reminderPopup {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 320px;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    font-family: 'Arial', sans-serif;
    animation: fadeIn 0.5s ease-in-out;
    z-index: 1000;
    display: flex;
    align-items: center;
}

/* ✅ Notification Icon */
#reminderPopup i {
    font-size: 30px;
    margin-right: 15px;
    color: #fff;
}

/* ✅ Dismiss Button */
.dismiss-btn {
    background: #fff;
    color: #ff5722;
    border: none;
    padding: 6px 12px;
    font-weight: bold;
    border-radius: 6px;
    margin-left: auto;
    cursor: pointer;
    transition: background 0.3s ease;
}

.dismiss-btn:hover {
    background: #fdd835;
    color: #222;
}

/* ✅ Fade-In Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<?php } ?>

<?php include 'footer.php'; ?>
</body>
</html>