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

?>

<div class="container mt-5">
    <h2 class="text-center">User Feedback</h2>
    <div class="card p-4">
        <h4>Add Feedback</h4>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="title" class="form-control" placeholder="Feedback Title" required>
            </div>
            <div class="mb-3">
                <textarea name="content" class="form-control" placeholder="Your Feedback" required></textarea>
            </div>
            <button type="submit" name="submit_review" class="btn btn-contact">Submit</button>
        </form>

        <?php
        if (isset($_POST['submit_review'])) {
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);

            // Insert review securely
            $stmt = $conn->prepare("INSERT INTO reviews (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $title, $content);
            
            if ($stmt->execute()) {
                echo "<p class='text-success mt-3'>Review added successfully!</p>";
            } else {
                echo "<p class='text-danger mt-3'>Error adding review.</p>";
            }
            $stmt->close();

            // Refresh page to show new review
            echo "<meta http-equiv='refresh' content='1'>";
        }
        ?>

    </div>
</div>

<?php 
$conn->close();
include 'footer.php'; 
?>