<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../admin/includes/db_connect.php');

// if (strlen($_SESSION['bpmsuid'] == 0)) {
//   header('location:logout.php');
// } else {

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
  header('location:logout.php');
  exit();
}

if (!isset($_GET['invoiceid']) || empty($_GET['invoiceid'])) {
  die("Error: No invoice ID found.");
}

$invoiceid = intval($_GET['invoiceid']);


?>
<!doctype html>
<html lang="en">

<head>


  <title>GlamourGrid | Booking History</title>

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>

<body id="home">
  <?php include_once('header.php'); ?>

  <script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
  <!--bootstrap working-->
  <script src="assets/js/bootstrap.min.js"></script>
  <!-- //bootstrap working-->
  <!-- disable body scroll which navbar is in active -->
  <script>
    $(function() {
      $('.navbar-toggler').click(function() {
        $('body').toggleClass('noscroll');
      })
    });
  </script>
  <section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec	">
      <div class="container">

        <div>
          <div class="cont-details">
            <div class="table-content table-responsive cart-table-content m-t-30">
              <h3 class="title1">Invoice Details</h3>

              <?php
              $invid = intval($_GET['invoiceid']);
              $ret = mysqli_query($con, "select DISTINCT  date(invoice.posting_date) as invoicedate,users.name,users.email,users.created_at,invoice.billing_id
    from  invoice 
    join users on users.id=invoice.user_id 
    where invoice.billing_id='$invid'");
              $cnt = 1;
              while ($row = mysqli_fetch_array($ret)) {

              ?>

                <div class="table-responsive bs-example widget-shadow">
                  <h4>Invoice #<?php echo $invid; ?></h4>
                  <table class="table table-bordered" width="100%" border="1">
                    <tr>
                      <th colspan="6">Customer Details</th>
                    </tr>
                    <tr>
                      <th>Name</th>
                      <td><?php echo $row['name'] ?></td>
                      <th>Email </th>
                      <td><?php echo $row['email'] ?></td>
                    </tr>
                    <tr>
                      <th>Registration Date</th>
                      <td><?php echo $row['created_at'] ?></td>
                      <th>Invoice Date</th>
                      <td colspan="3"><?php echo $row['invoicedate'] ?></td>
                    </tr>
                  <?php } ?>
                  </table>
                  <table class="table table-bordered" width="100%" border="1">
                    <tr>
                      <th colspan="3">Services Details</th>
                    </tr>
                    <tr>
                      <th>#</th>
                      <th>Service</th>
                      <th>Price</th>
                    </tr>

                    <?php
                    $ret = mysqli_query($con, "select services.service_name,services.price  
    from  invoice 
    join services on services.id=invoice.service_id
    where invoice.billing_id='$invid'");

                    $cnt = 1;
                    $gtotal = 0;

                    while ($row = mysqli_fetch_array($ret)) {
                    ?>

                      <tr>
                        <th><?php echo $cnt; ?></th>
                        <td><?php echo $row['service_name'] ?></td>
                        <td><?php echo $subtotal = $row['price'] ?></td>
                      </tr>
                    <?php
                      $cnt = $cnt + 1;
                      $gtotal += $subtotal;
                    } ?>

                    <tr>
                      <th colspan="2" style="text-align:center">Grand Total</th>
                      <th><?php echo $gtotal ?></th>

                    </tr>
                  </table>
                  <p style="margin-top:1%" align="center">
                    <i class="fa fa-print fa-2x" style="cursor: pointer;" OnClick="CallPrint(this.value)"></i>
                  </p>
                </div>
            </div>

          </div>

        </div>
      </div>
  </section>
  <?php include_once('footer.php'); ?>
  <!-- move top -->
  <button onclick="topFunction()" id="movetop" title="Go to top">
    <span class="fa fa-long-arrow-up"></span>
  </button>
  <script>
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {
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
  <script>
function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
}
</script>
</body>

</html>