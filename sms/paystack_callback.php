<?php
session_start();
include('db_connect.php');

$paystack_secret_key = "sk_test_c8725b3376b5311ecc7d64c860bb783ff3a953cb"; // Replace with your actual Paystack Secret Key

// Get the payment reference from Paystack
if (!isset($_GET['reference'])) {
    echo "No reference supplied";
    exit();
}

$reference = $_GET['reference'];

// Verify transaction using Paystack API
$url = "https://api.paystack.co/transaction/verify/" . $reference;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret_key",
    "Cache-Control: no-cache"
]);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result && $result['status'] && $result['data']['status'] === 'success') {
    // Extract payment details
    $amountPaid = $result['data']['amount'] / 100; // Convert kobo to Naira
    $transactionRef = $result['data']['reference'];
    $apt_number = $result['data']['metadata']['apt_number'];

    // Update the database with payment info
    $stmt = $pdo->prepare("
        INSERT INTO payment_receipts (AptNumber, AmountPaid, PaymentStatus, TransactionRef, PaymentDate)
        VALUES (?, ?, 'Paid', ?, NOW())
        ON DUPLICATE KEY UPDATE AmountPaid = VALUES(AmountPaid), PaymentStatus = VALUES(PaymentStatus), TransactionRef = VALUES(TransactionRef)
    ");
    $stmt->execute([$apt_number, $amountPaid, $transactionRef]);

    // Redirect to success page
    header("Location: payment_success.php?apt_number=" . urlencode($apt_number));
    exit();
} else {
    // Payment failed or verification failed
    echo "<script>alert('Payment verification failed. Please contact support.'); window.location.href='dashboard.php';</script>";
    exit();
}
?>
