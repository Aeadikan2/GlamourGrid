<?php
session_start();
include('db_connect.php'); // Ensure database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Handling file upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = "uploads/" . basename($image);
        
        // Move the uploaded file to the designated folder
        if (!move_uploaded_file($image_tmp, $image_folder)) {
            $error = "Failed to upload image.";
        }
    } else {
        $image = "default.jpg"; // Default image if no file is uploaded
    }

    // Check if email already exists
    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute([':email' => $email]);

    if ($query->rowCount() > 0) {
        $error = "Email already exists. Please log in.";
    } else {
        $insert = $pdo->prepare("INSERT INTO users (name, email, phone, address, image, password) 
                                 VALUES (:name, :email, :phone, :address, :image, :password)");
        $insert->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':image' => $image,
            ':password' => $password
        ]);
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Store user session
        $_SESSION['username'] = $name;
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup - GlarmourGrid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
<body>
<?php include_once('header.php'); ?>

<script>
    $(function() {
        $('.navbar-toggler').click(function() {
            $('body').toggleClass('noscroll');
        });
    });
</script>

<div class="container mt-5">
    <div class="form-container">
    <h2 class="text-center fw-bold">Signup</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Profile Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-contact w-100">Signup</button>
        <p class="mt-3">Already have an account? <a href="login.php">Log in here</a>.</p>
    </form>
</div>
</div>

<?php include_once('footer.php'); ?>
</body>
</html>
