<?php
session_start();
include('db_connect.php'); // Ensure you have a file for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute([':email' => $email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Store user ID
        $_SESSION['username'] = $user['name']; // Store user name

        header('Location: ../users/dashboard.php'); // Redirect to dashboard
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GlamourGrid | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include_once('header.php'); ?>

    <div class="container mt-5 mb-5">
        <div class="form-container">
            <h2 class="text-center mb-4 fw-bold">Login</h2>
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
                <div class="mb-3 text-end">
                    <a href="forgot-password.php" class="link-primary">Forgot Password?</a>
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-contact w-100">Login</button>
                </div>
                <p class="text-center">Don't have an account? <a href="signup.php" class="link-primary">Sign up here</a>.</p>
            </form>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
</body>
</html>
