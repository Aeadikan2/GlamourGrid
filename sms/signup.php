<?php
session_start();
include('db_connect.php'); // Ensure you have a file for database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hashing the password

    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute([':email' => $email]);

    if ($query->rowCount() > 0) {
        $error = "Email already exists. Please log in.";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $insert->execute([':name' => $name, ':email' => $email, ':password' => $password]);
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Unique identifier for the user
        $_SESSION['username'] = $name; // User's name for personalization
        header('Location: index.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup - GlarmourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once('header.php'); ?>
<div class="container mt-5">
    <h2 class="text-center">Signup</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Signup</button>
        <p class="mt-3">Already have an account? <a href="login.php">Log in here</a>.</p>
    </form>
</div>
<?php include_once('footer.php'); ?>
</body>
</html>
