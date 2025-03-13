<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../admin/includes/db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('location:logout.php');
    exit();
}

$uid = $_SESSION['user_id'];

// Handle profile update
if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    // File upload handling
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../sms/uploads/";
        $file_name = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "png", "jpeg", "gif"];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $stmt = $con->prepare("UPDATE users SET name=?, email=?, image=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $email, $file_name, $uid);
            } else {
                echo '<script>alert("Error uploading file. Try again.")</script>';
            }
        } else {
            echo '<script>alert("Invalid file format. Only JPG, PNG, JPEG, and GIF allowed.")</script>';
        }
    } else {
        $stmt = $con->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $uid);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Profile updated successfully.")</script>';
        echo '<script>window.location.href="profile.php"</script>';
    } else {
        echo '<script>alert("Something went wrong. Please try again.")</script>';
    }
    $stmt->close();
}

// Fetch user details
$stmt = $con->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!doctype html>
<html lang="en">
<head>
    <title>GlamourGrid | Profile Page</title>
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .profile-container { position: relative; z-index: 1; }
        .user-icon { font-size: 100px; color: #6c757d; }
    </style>
</head>
<body id="home">
    <?php include_once('header.php'); ?>

    <section class="w3l-contact-info-main profile-container" id="contact">
        <div class="contact-sec">
            <div class="container mt-5 mb-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card p-4">
                            <div class="text-center">
                                <img src="../sms/uploads/<?php echo !empty($user['image']) ? htmlspecialchars($user['image']) : 'default-avatar.jpg'; ?>" 
                                     class="rounded-circle mb-3" width="120" height="120" alt="Profile Picture">
                            </div>
                            <h1 class="text-center mt-4 text-bold">User Profile</h1>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <input type="file" class="form-control" name="profile_image">
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Registration Date</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['created_at']); ?>" readonly>
                                </div>
                                <div class="text-end mt-3 mb-4">
                                    <button type="submit" class="btn btn-contact" name="submit">Save Change</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('footer.php'); ?>
</body>
</html>
