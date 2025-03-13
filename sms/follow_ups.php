<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Feedback Message
$feedback = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['client'];
    $followup_type = htmlspecialchars(trim($_POST['followup_type']));
    $message = htmlspecialchars(trim($_POST['message']));
    $send_date = htmlspecialchars(trim($_POST['send_date']));
    $send_time = htmlspecialchars(trim($_POST['send_time']));

    if (strtotime($send_date) < strtotime(date('Y-m-d'))) {
        $feedback = "<div class='alert alert-danger'>Please select a future date for sending the follow-up.</div>";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO follow_ups (ClientID, FollowupType, Message, SendDate, SendTime, Status) 
                VALUES (:client_id, :followup_type, :message, :send_date, :send_time, 'Pending')
            ");
            $stmt->execute([
                ':client_id' => $client_id,
                ':followup_type' => $followup_type,
                ':message' => $message,
                ':send_date' => $send_date,
                ':send_time' => $send_time,
            ]);
            $feedback = "<div class='alert alert-success'>Follow-up scheduled successfully!</div>";
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $feedback = "<div class='alert alert-danger'>An error occurred while scheduling the follow-up. Please try again later.</div>";
        }
    }
}

// Fetch clients
function fetchClients()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, name, email, phone FROM clients");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$clients = fetchClients();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automated Follow-ups - GlamourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container mt-5 bg-white rounded shadow p-4">
        <h2 class="text-center py-3">Automated Follow-ups</h2>

        <!-- Feedback Message -->
        <?php echo $feedback; ?>

        <form action="follow_ups.php" method="POST" class="row g-3">
            <div class="mb-3 col-md-6">
                <label for="client" class="form-label">Select Client</label>
                <select name="client" id="client" class="form-select" required>
                    <option value="" selected disabled>Choose a client</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>">
                            <?= $client['name'] ?> (<?= $client['email'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label for="followup_type" class="form-label">Follow-up Type</label>
                <select name="followup_type" id="followup_type" class="form-select" required>
                    <option value="" selected disabled>Select follow-up type</option>
                    <option value="Rebooking Reminder">Rebooking Reminder</option>
                    <option value="Satisfaction Check">Satisfaction Check</option>
                    <option value="Seasonal Offer">Seasonal Offer</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" rows="4" class="form-control" placeholder="Enter your follow-up message" required></textarea>
            </div>
            <div class="mb-3 col-md-6">
                <label for="send_date" class="form-label">Send Date</label>
                <input type="date" name="send_date" id="send_date" class="form-control" required>
            </div>
            <div class="mb-3 col-md-6">
                <label for="send_time" class="form-label">Send Time</label>
                <input type="time" name="send_time" id="send_time" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-outline-info">Schedule Follow-up</button>
            </div>
        </form>
    </div>

    <?php include_once('footer.php'); ?>
</body>

</html>
