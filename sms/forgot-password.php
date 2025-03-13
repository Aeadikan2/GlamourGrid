<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../admin/includes/db_connect.php');

if(isset($_POST['submit']))
{
    $userid = $_POST['user_id'];
    $email = $_POST['email'];
    $password = md5($_POST['newpassword']);

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ? AND user_id = ?");
    $stmt->bind_param("ss", $email, $userid);
    $stmt->execute();
    $stmt->store_result();
    $ret = $stmt->num_rows;

    if($ret > 0){
        $_SESSION['user_id'] = $userid;
        $_SESSION['email'] = $email;

        $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ? AND user_id = ?");
        $stmt->bind_param("sss", $password, $email, $userid);
        $query1 = $stmt->execute();

        if($query1){
            echo "<script>alert('Password successfully changed');</script>";
        }
    } else {
        echo "<script>alert('Invalid Details. Please try again.');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <title>GlamourGrid | Forgot Password Page</title>
    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  </head>
  <body id="home">
<?php include_once('header.php');?>

<script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
<!--bootstrap working-->
<script src="assets/js/bootstrap.min.js"></script>
<!-- //bootstrap working-->
<!-- disable body scroll which navbar is in active -->
<script>
$(function () {
  $('.navbar-toggler').click(function () {
    $('body').toggleClass('noscroll');
  })
});
</script>
<script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
} 
</script>

<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
        <div class="container">
            <div class="d-grid contact-view">
                <div class="map-content-9 mt-lg-0 mt-4">
                    <h1 class="text-center mt-4 mb-5 text-bold">Forgot Password</h1>
                    <h3 style="padding-bottom: 10px;">Reset your password and Fill below details</h3>
                    <form method="post" onsubmit="return checkpass();" name="changepassword">
                        <div>
                            <input type="text" class="form-control" name="email" placeholder="Enter Your Name" required="true">
                        </div>
                        <div style="padding-top: 30px;">
                            <input type="text" class="form-control" name="user_id" placeholder="Enter Your Email" required="true">
                        </div>
                        <div style="padding-top: 30px;">
                          <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password">
                        </div>
                        <div style="padding-top: 30px;">
                           <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password">
                        </div>
                        <div class="twice-two" style="padding-top: 30px;">
                          <a class="link--gray" style="color: blue;" href="login.php">Login Instead?</a>
                        </div>
                        <div class="text-end mb-3">
                          <button type="submit" class="btn btn-contact" name="submit">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include_once('footer.php');?>
<!-- move top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-long-arrow-up"></span>
</button>
<script>
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("movetop").style.display = "block";
        } else {
            document.getElementById("movetop").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
<!-- /move top -->
</body>
</html>