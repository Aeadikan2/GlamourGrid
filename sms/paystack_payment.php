<?php
session_start();
include('db_connect.php');

if (!isset($_POST['apt_number']) || !isset($_POST['amount'])) {
    echo "<script>alert('Invalid transaction request!'); window.location.href='dashboard.php';</script>";
    exit();
}

$apt_number = $_POST['apt_number'];
$amount = $_POST['amount']; // Amount in kobo (100 kobo = ₦1)

// Fetch client details
$query = $pdo->prepare("SELECT ClientName, PhoneNumber FROM bookings WHERE AptNumber = ?");
$query->execute([$apt_number]);
$client = $query->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "<script>alert('Appointment not found!'); window.location.href='dashboard.php';</script>";
    exit();
}

$client_name = $client['ClientName'];
$client_email = "user@example.com"; // Use real email if stored in DB
$phone_number = $client['PhoneNumber'];

// Generate a unique reference
$reference = "SALON_" . uniqid() . time();

// Paystack API Key
$paystack_secret_key = "sk_test_c8725b3376b5311ecc7d64c860bb783ff3a953cb"; // Replace with your actual key

// Prepare Paystack request
$paystack_url = "https://api.paystack.co/transaction/initialize";
$callback_url = "paystack_callback.php"; // Page to handle verification after payment

$data = [
    "email" => $client_email,
    "amount" => $amount,
    "reference" => $reference,
    "callback_url" => $callback_url,
    "metadata" => [
        "customer_name" => $client_name,
        "phone" => $phone_number,
        "appointment_number" => $apt_number
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $paystack_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $paystack_secret_key",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if ($result['status'] && isset($result['data']['authorization_url'])) {
    // Redirect to Paystack payment page
    header("Location: " . $result['data']['authorization_url']);
    exit();
} else {
    echo "<script>alert('Error initiating payment! Please try again.'); window.location.href='invoice_summary.php?apt_number=$apt_number';</script>";
    exit();
}
?>
