<?php
include 'db_connect.php'; // Ensure this connects to your database

// Fetch images from database
$query = "SELECT id, service_name, image, likes FROM services ORDER BY id DESC";
$result = mysqli_query($conn, $query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .card {
            width: 300px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .like-btn {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
            color: gray;
        }
        .liked {
            color: red; /* When liked, change color to red */
        }
    </style>
</head>
<body>
<?php include_once('header.php'); ?>
<div class="container mt-4">
    <h2 class="text-center">Gallery</h2>
    <div class="gallery-container">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <img src="../uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['service_name']; ?>" style="height:200px; object-fit:cover;">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $row['service_name']; ?></h5>
                    <button class="like-btn" data-id="<?php echo $row['id']; ?>">
                        ❤️ <span class="like-count"><?php echo $row['likes']; ?></span>
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".like-btn").click(function () {
        var button = $(this);
        var serviceId = button.data("id");

        $.ajax({
            url: "like.php",
            type: "POST",
            data: { service_id: serviceId },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    button.find(".like-count").text(data.likes);
                    button.toggleClass("liked");
                }
            }
        });
    });
});
</script>
<?php include_once('footer.php'); ?>

</body>
</html>
