<?php
session_start();
include('db_connect.php'); // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // User's name

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // If password is not empty, hash it and update
    if (!empty($password)) {
        $password = md5($password); // Hashing the password
        $update = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $update->execute([':name' => $name, ':email' => $email, ':password' => $password, ':id' => $user_id]);
    } else {
        // Update name and email only if password is not provided
        $update = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $update->execute([':name' => $name, ':email' => $email, ':id' => $user_id]);
    }

    $_SESSION['username'] = $name; // Update the session variable for username
    header('Location: profile.php'); // Redirect after update
}

// Fetch user data from the database
$query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$query->execute([':id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile - GlamourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Profile</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (Leave empty if no change)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <p class="mt-3">Want to log out? <a href="logout.php">Log out here</a>.</p>
</div>

<?php include_once('footer.php'); ?>
</body>
</html>
