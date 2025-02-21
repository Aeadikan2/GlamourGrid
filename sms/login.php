<?php
session_start();
include('db_connect.php'); // Ensure you have a file for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hashing the password

    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $query->execute([':email' => $email, ':password' => $password]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id']; // Unique identifier for the user
        $_SESSION['username'] = $user['name']; // User's name for personalization
        header('Location: index.php');
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Scissors Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include_once('header.php'); ?>
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <p class="mt-3">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
    </form>
</div>
<?php include_once('footer.php'); ?>
</body>
</html>
