<div class="sidebar" role="navigation">
    <div class="navbar-collapse">
        <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="dashboard.php"><i class="fa fa-home nav_icon"></i>Dashboard</a>
                </li>
                <li>
                    <a href="stylists.php"><i class="fa fa-check-square nav_icon"></i>Stylist Status</a>
                </li>
                <li>
                    <a href="add-stylists.php"><i class="fa fa-user nav_icon"></i>Stylists</a>
                </li>

                <li>
                    <a href="#" data-toggle="collapse" data-target="#servicesMenu">
                        <i class="fa fa-cogs nav_icon"></i>Services <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" id="servicesMenu">
                        <li><a href="add-services.php">Add Services</a></li>
                        <li><a href="manage-services.php">Manage Services</a></li>
                    </ul>
                </li>

                <!-- <li>
                    <a href="#" data-toggle="collapse" data-target="#pagesMenu">
                        <i class="fa fa-book nav_icon"></i>Pages <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" id="pagesMenu">
                        <li><a href="about-us.php">About Us</a></li>
                        <li><a href="contact-us.php">Contact Us</a></li>
                    </ul>
                </li> -->

                <li>
                    <a href="#" data-toggle="collapse" data-target="#appointmentsMenu">
                        <i class="fa fa-check-square-o nav_icon"></i>Appointments <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" id="appointmentsMenu">
                        <li><a href="all-appointment.php">All Appointments</a></li>
                        <li><a href="new-appointment.php">New Appointment</a></li>
                        <li><a href="accepted-appointment.php">Accepted Appointment</a></li>
                        <li><a href="rejected-appointment.php">Rejected Appointment</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" data-toggle="collapse" data-target="#enquiriesMenu">
                        <i class="fa fa-check-square-o nav_icon"></i>Enquiries <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" id="enquiriesMenu">
                        <li><a href="readenq.php">Read Enquiry</a></li>
                        <li><a href="unreadenq.php">Unread Enquiry</a></li>
                    </ul>
                </li>

                <li>
                    <a href="customer-list.php"><i class="fa fa-users nav_icon"></i>Customer List</a>
                </li>

                <li>
                    <a href="#" data-toggle="collapse" data-target="#reportsMenu">
                        <i class="fa fa-check-square-o nav_icon"></i>Reports <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse" id="reportsMenu">
                        <li><a href="bwdates-reports-ds.php">B/w Dates</a></li>
                        <li><a href="sales-reports.php">Sales Reports</a></li>
                    </ul>
                </li>

                <li>
                    <a href="invoices.php"><i class="fa fa-file-text-o nav_icon"></i>Invoices</a>
                </li>

                <li>
                    <a href="search-appointment.php"><i class="fa fa-search nav_icon"></i>Search Appointment</a>
                </li>

                <li>
                    <a href="change-password.php"><i class="fa fa-key nav_icon"></i>Change Password</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </nav>
    </div>
</div>
<!-- Ensure jQuery and Bootstrap JavaScript are loaded -->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>

<!-- Fix dropdown collapse issue -->
<script>
    $(document).ready(function() {
        $('.nav-second-level').collapse({ toggle: false });

        // Ensure clicking on menu toggles dropdown
        $('[data-toggle="collapse"]').on('click', function(e) {
            var target = $(this).data('target');
            $(target).collapse('toggle');
        });
    });
</script>
