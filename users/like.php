<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$service_id = $_POST['service_id'];

// ✅ Use prepared statements
$checkQuery = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND service_id = ?");
$checkQuery->bind_param("ii", $user_id, $service_id);
$checkQuery->execute();
$checkResult = $checkQuery->get_result();

if ($checkResult->num_rows > 0) {
    // Unlike
    $deleteQuery = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND service_id = ?");
    $deleteQuery->bind_param("ii", $user_id, $service_id);
    $deleteQuery->execute();

    $updateQuery = $conn->prepare("UPDATE services SET likes = likes - 1 WHERE id = ?");
    $updateQuery->bind_param("i", $service_id);
    $updateQuery->execute();
} else {
    // Like
    $insertQuery = $conn->prepare("INSERT INTO likes (user_id, service_id, created_at) VALUES (?, ?, NOW())");
    $insertQuery->bind_param("ii", $user_id, $service_id);
    $insertQuery->execute();

    $updateQuery = $conn->prepare("UPDATE services SET likes = likes + 1 WHERE id = ?");
    $updateQuery->bind_param("i", $service_id);
    $updateQuery->execute();
}

// Fetch updated like count
$fetchQuery = $conn->prepare("SELECT likes FROM services WHERE id = ?");
$fetchQuery->bind_param("i", $service_id);
$fetchQuery->execute();
$result = $fetchQuery->get_result();
$row = $result->fetch_assoc();

echo json_encode(["success" => true, "likes" => $row['likes']]);
?>
