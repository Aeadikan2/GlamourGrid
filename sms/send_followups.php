<?php
include('db_connect.php');

try {
    $stmt = $pdo->query("SELECT * FROM follow_ups WHERE Status = 'Pending' AND SendDate = CURDATE() AND SendTime <= CURTIME()");
    while ($followup = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Example: Send email or SMS (using PHPMailer or a third-party SMS API)
        // sendNotification($followup['Message'], $followup['ClientID']);

        // Mark as sent
        $update = $pdo->prepare("UPDATE follow_ups SET Status = 'Sent' WHERE id = ?");
        $update->execute([$followup['id']]);
    }
} catch (PDOException $e) {
    error_log("Error processing follow-ups: " . $e->getMessage());
}
?>
