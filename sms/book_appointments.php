<?php
session_start();
include('db_connect.php');

// Initialize feedback message
$feedback = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $services = $_POST['service'] ?? [];
    $stylist_id = (int)$_POST['stylist'];
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "<div class='alert alert-danger'>Invalid email address.</div>";
    } elseif (strtotime($date) < strtotime(date('Y-m-d'))) {
        $feedback = "<div class='alert alert-danger'>Please select a valid future date.</div>";
    } elseif (empty($services) || !areValidServices($services) || !isValidStylist($stylist_id)) {
        $feedback = "<div class='alert alert-danger'>Invalid service(s) or stylist selection.</div>";
    } else {
        if (!isAvailable($stylist_id, $date, $time)) {
            $feedback = "<div class='alert alert-danger'>The selected time slot is not available. Please choose a different time or date.</div>";
        } else {
            try {
                $pdo->beginTransaction();

                // Generate appointment number
                $appointment_number = 'APT' . strtoupper(bin2hex(random_bytes(4)));

                // Insert booking into database
                $stmt = $pdo->prepare("
                    INSERT INTO bookings (UserID, ClientName, AptNumber, StylistID, AptDate, AptTime, Message, Status, PhoneNumber)
                    VALUES (:user_id, :name, :appointment_number, :stylist_id, :apt_date, :apt_time, :message, 'Pending', :phone_number)
                ");
                $stmt->execute([
                    ':user_id' => $_SESSION['user_id'],
                    ':name' => $name,
                    ':appointment_number' => $appointment_number,
                    ':stylist_id' => $stylist_id,
                    ':apt_date' => $date,
                    ':apt_time' => $time,
                    ':message' => $message,
                    ':phone_number' => $phone,
                ]);

                $appointment_id = $pdo->lastInsertId();

                // Insert selected services
                $stmt = $pdo->prepare("INSERT INTO appointment_services (appointment_id, service_id) VALUES (?, ?)");
                foreach ($services as $service_id) {
                    $stmt->execute([$appointment_id, $service_id]);
                }

                $pdo->commit();

                // Redirect to invoice summary page
                header("Location: invoice_summary.php?apt_number=$appointment_number");
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log("Database Error: " . $e->getMessage());
                $feedback = "<div class='alert alert-danger'>An error occurred while processing your request. Please try again later.</div>";
            }
        }
    }
}

// Validation functions
function areValidServices($services)
{
    global $pdo;
    if (empty($services)) return false;

    $placeholders = implode(',', array_fill(0, count($services), '?'));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM services WHERE id IN ($placeholders)");
    $stmt->execute($services);
    return $stmt->fetchColumn() == count($services);
}

function isValidStylist($stylist_id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stylists WHERE id = ?");
    $stmt->execute([$stylist_id]);
    return $stmt->fetchColumn() > 0;
}

function isAvailable($stylist_id, $date, $time)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE StylistID = ? AND AptDate = ? AND AptTime = ? AND Status != 'Cancelled'");
    $stmt->execute([$stylist_id, $date, $time]);
    return $stmt->fetchColumn() == 0;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - GlamourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div class="container mt-5 bg-white rounded shadow p-4 mb-5">
        <h2 class="text-center py-3">Book an Appointment</h2>

        <!-- Feedback Message -->
        <?php echo $feedback ?? ''; ?>

        <form action="book_appointments.php" method="POST" class="row g-3 needs-validation" novalidate>
            <div class="mb-3 col-md-4">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
                <div class="invalid-feedback">
                    Please enter your full name.
                </div>
            </div>
            <div class="mb-3 col-md-4">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            <div class="mb-3 col-md-4">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
                <div class="invalid-feedback">
                    Please enter your phone number.
                </div>
            </div>

            <div class="mb-3 col-md-6">
    <label for="service" class="form-label">Select Service(s)</label>
    <select name="service[]" id="service" class="form-select" multiple required>
        <?php
        try {
            // Fetch services from the database
            $stmt = $pdo->query("SELECT id, service_name, price FROM services");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $service_name = htmlspecialchars($row['service_name']);
                $price = number_format($row['price'], 2);
                echo "<option value='{$row['id']}'>{$service_name} - ₦{$price}</option>";
            }
        } catch (PDOException $e) { ?>
            <option disabled>Error fetching services</option>
        <?php }
        ?>
    </select>
    <div class="invalid-feedback">
        Please select at least one service.
    </div>
</div>

<!-- Include jQuery and Select2 for better UX -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#service').select2({
            placeholder: "Choose service(s)",
            allowClear: true
        });
    });
</script>


            <div class="mb-3 col-md-6">
                <label for="stylist" class="form-label">Select Stylist</label>
                <select name="stylist" id="stylist" class="form-select" required>
                    <option value="" selected disabled>Choose a stylist</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT id, name, available FROM stylists");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $stylist_name = htmlspecialchars($row['name']);
                            $availability = $row['available'] == 1 ? 'Available' : 'Not Available';
                            $disabled = $row['available'] == 1 ? '' : 'disabled';
                            echo "<option value='{$row['id']}' $disabled>{$stylist_name} - {$availability}</option>";
                        }
                    } catch (PDOException $e) { ?>
                        <option disabled>Error fetching stylists</option>
                    <?php }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Please select a stylist.
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label for="date" class="form-label">Preferred Date</label>
                <input type="date" name="date" id="date" class="form-control" required>
                <div class="invalid-feedback">
                    Please select a date.
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <label for="time" class="form-label">Preferred Time</label>
                <input type="time" name="time" id="time" class="form-control" required>
                <div class="invalid-feedback">
                    Please select a time.
                </div>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Additional Message</label>
                <textarea name="message" id="message" rows="4" class="form-control" placeholder="Add any special requests or notes"></textarea>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-outline-info">Submit</button>
            </div>
        </form>
    </div>
    

    <script>
      // JavaScript validation
      (function() {
          'use strict';
          window.addEventListener('load', function() {
              var forms = document.getElementsByClassName('needs-validation');
              Array.prototype.filter.call(forms, function(form) {
                  form.addEventListener('submit', function(event) {
                      if (form.checkValidity() === false) {
                          event.preventDefault();
                          event.stopPropagation();
                      }
                      form.classList.add('was-validated');
                  }, false);
              });
          }, false);
      })();
    </script>

    <?php include_once('footer.php'); ?>
</body>

</html>