<!-- GetButton.io widget -->
<script type="text/javascript">
    (function() {
        var options = {
            whatsapp: "+91 9975554765", // WhatsApp number
            call_to_action: "Message us", // Call to action
            button_color: "#FF6550", // Color of button
            position: "right", // Position may be 'right' or 'left'
        };
        var proto = 'https:',
            host = "getbutton.io",
            url = proto + '//static.' + host;
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url + '/widget-send-button/js/init.js';
        s.onload = function() {
            WhWidgetSendButton.init(host, proto, options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    })();
</script>
<!-- /GetButton.io widget -->

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row ">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center ">
                <a class="navbar-brand brand-logo" href="page.php?page=IMSDashboard"><img src="<?= $logo ?>"
                        class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="page.php?page=IMSDashboard"><img src="<?= $logo ?>"
                        alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end franchise_nav_menu" style="background: #abb606 !important;">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <span id='ct7'></span>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <img src="<?= isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : ''  ?>"
                                alt="User Image" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">

                            <a class="dropdown-item" href="login.php?logout" title="Logout">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                            <i class="icon-ellipsis"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="login.php?logout" title="Logout">
                                <i class="ti-power-off text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item    <?php if ($page == 'IMSDashboard') {
                                                echo 'active';
                                            } ?>">
                        <a class="nav-link franchiseSidebar" href="page.php?page=IMSDashboard">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    <?php if ($db->permission('list_courses') || $db->permission('list_courses_multisub') || $db->permission('list_courses_typing')) { ?>
                        <?php if ($db->permission('list_courses')) { ?>
                            <li class="nav-item <?php if ($page == 'listCourses') {
                                                    echo 'active';
                                                } ?>">
                                <a class="nav-link franchiseSidebar" href="page.php?page=listCourses">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Add New Courses </span>
                                </a>
                            </li>
                        <?php } elseif ($db->permission('list_courses_multisub')) { ?>

                            <li class="nav-item <?php if ($page == 'listCoursesMultiSub') {
                                                    echo 'active';
                                                } ?>">
                                <a class="nav-link franchiseSidebar" href="page.php?page=listCoursesMultiSub">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Add New Courses </span>
                                </a>
                            </li>
                        <?php } elseif ($db->permission('list_courses_typing')) { ?>

                            <li class="nav-item <?php if ($page == 'listCoursesTyping') {
                                                    echo 'active';
                                                } ?>">
                                <a class="nav-link franchiseSidebar" href="page.php?page=listCoursesTyping">
                                    <i class="icon-grid menu-icon"></i>
                                    <span class="menu-title">Add New Courses </span>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <li class="nav-item 
                        <?php if ($page == 'studentEnquiry' || $page == 'studentAdmission' || $page == 'studentReAdmission' || $page == 'listStudentFees' || $page == 'refferalAmount') {
                            echo 'active';
                        } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#student-page"
                            aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Manage Students</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'studentEnquiry' || $page == 'studentAdmission' || $page == 'studentReAdmission' || $page == 'listStudentFees' || $page == 'refferalAmount') {
                                                    echo 'show';
                                                } ?>"
                            id="student-page">
                            <ul class="nav flex-column sub-menu">
                                <?php if ($db->permission('list_enquiry')) { ?>
                                    <li class="nav-item franchiseSidebar"> <a
                                            class="nav-link <?php if ($page == 'studentEnquiry') {
                                                                echo 'active';
                                                            } ?>"
                                            href="page.php?page=studentEnquiry">Student Enquiry</a></li>

                                <?php } ?>
                                <?php if ($db->permission('list_admission')) { ?>
                                    <li class="nav-item franchiseSidebar"> <a
                                            class="nav-link <?php if ($page == 'studentAdmission') {
                                                                echo 'active';
                                                            } ?>"
                                            href="page.php?page=studentAdmission">Student Admission</a></li>
                                <?php } ?>
                                <?php if ($db->permission('add_admission')) { ?>
                                    <li class="nav-item franchiseSidebar"> <a
                                            class="nav-link <?php if ($page == 'studentReAdmission') {
                                                                echo 'active';
                                                            } ?>"
                                            href="page.php?page=studentReAdmission">Re-Admission</a></li>
                                <?php } ?>
                                <?php if ($db->permission('list_student_fees')) { ?>
                                    <li class="nav-item franchiseSidebar"> <a
                                            class="nav-link <?php if ($page == 'listStudentFees') {
                                                                echo 'active';
                                                            } ?>"
                                            href="page.php?page=listStudentFees">Student Fees</a></li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>


                    <li
                        class="nav-item   <?php if ($page == 'resetExam' || $page == 'examOTP' || $page == 'listPracticalExamResult' || $page == 'listExamResultsAll' || $page == 'listHallticket') {
                                                echo 'active';
                                            } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#exam" aria-expanded="false"
                            aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Student Exams</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'resetExam' || $page == 'examOTP' || $page == 'listPracticalExamResult' || $page == 'listExamResultsAll' || $page == 'listHallticket') {
                                                    echo 'show';
                                                } ?>"
                            id="exam">
                            <ul class="nav flex-column sub-menu ">
                                <?php if ($db->permission('list_reset_exam')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar <?php if ($page == 'resetExam') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link  " href="page.php?page=resetExam">Reset Exam</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_exam_code')) { ?>
                                    <li class="nav-item franchiseSidebar  <?php if ($page == 'examOTP') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link    " href="page.php?page=examOTP">Exam Code</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_offline_marks_update')) { ?>
                                    <li class="nav-item franchiseSidebar  <?php if ($page == 'listPracticalExamResult') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link   "
                                            href="page.php?page=listPracticalExamResult">Offine Exams Marks
                                            Update</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_all_exam_results')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'listExamResultsAll') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link  " href="page.php?page=listExamResultsAll">All
                                            Exam Results</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_hall_tickets')) { ?>
                                    <li class="nav-item franchiseSidebar<?php if ($page == 'listHallticket') {
                                                                            echo 'active';
                                                                        } ?>"> <a
                                            class="nav-link  " href="page.php?page=listHallticket">Hall
                                            Tickets</a></li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>


                    <li
                        class="nav-item <?php if ($page == 'viewStudentCertificate' || $page == 'listExamResults' || $page == 'orderStudentCertificate' || $page == 'viewOrderStudentCertificate') {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#certificate"
                            aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Certificates</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'viewStudentCertificate' || $page == 'listExamResults' || $page == 'orderStudentCertificate' || $page == 'viewOrderStudentCertificate') {
                                                    echo 'show';
                                                } ?>"
                            id="certificate">
                            <ul class="nav flex-column sub-menu">
                                <?php if ($db->permission('list_apply_certificate')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'listExamResults') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link  " href="page.php?page=listExamResults">Apply For
                                            Certificates</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_apply_certificate')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'viewStudentCertificate') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link franchiseSidebar"
                                            href="page.php?page=viewStudentCertificate">View
                                            Certificates</a></li>
                                <?php } ?>
                                <?php if ($db->permission('list_apply_certificate')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'orderStudentCertificate') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link franchiseSidebar"
                                            href="page.php?page=orderStudentCertificate">Order
                                            Certificates</a></li>
                                <?php } ?>
                                <?php if ($db->permission('list_apply_certificate')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'viewOrderStudentCertificate') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link franchiseSidebar"
                                            href="page.php?page=viewOrderStudentCertificate">View Order
                                            Certificates</a></li>
                                <?php } ?>

                                <!-- <li class="nav-item <?php if ($page == 'viewStudentCertificate') {
                                                                echo 'active';
                                                            } ?>"> <a class="nav-link" href="page.php?page=viewStudentCertificate">View Certificates</a></li> -->
                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item <?php if ($page == 'listOnlineClasses' || $page == 'listMarqueeNotification' || $page == 'listAdvertisement') {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#notification"
                            aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Notifications</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'listOnlineClasses' || $page == 'listMarqueeNotification' || $page == 'listAdvertisement') {
                                                    echo 'show';
                                                } ?>"
                            id="notification">
                            <ul class="nav flex-column sub-menu">
                                <?php if ($db->permission('list_online_classes')) { ?>
                                    <li class="nav-item franchiseSidebar <?php if ($page == 'listOnlineClasses') {
                                                                                echo 'active';
                                                                            } ?>"> <a
                                            class="nav-link " href="page.php?page=listOnlineClasses">OnLine
                                            Class
                                            Notifications </a></li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>


                    <?php if ($db->permission('list_student_wallet')) { ?>
                        <li class="nav-item <?php if ($page == 'Wallet') {
                                                echo 'active';
                                            } ?>">
                            <a class="nav-link franchiseSidebar" href="page.php?page=Wallet">
                                <i class="icon-grid menu-icon"></i>
                                <span class="menu-title">Student Wallet </span>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="nav-item <?php if ($page == 'listBirthday') {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link franchiseSidebar" href="page.php?page=listBirthday">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Birthday's List </span>
                        </a>
                    </li>


                    <li
                        class="nav-item  <?php if ($page == 'listBatches' || $page == 'Attendance' || $page == 'AttendanceReport' || $page == 'AttendanceStudentReport') {
                                                echo 'active';
                                            } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#attendance"
                            aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Manage Attendance </span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'listBatches' || $page == 'Attendance' || $page == 'AttendanceReport' || $page == 'AttendanceStudentReport') {
                                                    echo 'show';
                                                } ?>"
                            id="attendance">
                            <ul class="nav flex-column sub-menu">

                                <?php if ($db->permission('list_attendance')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar <?php if ($page == 'Attendance') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link" href="page.php?page=Attendance">Attendance</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_attendance_report')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar<?php if ($page == 'AttendanceReport') {
                                                                            echo 'active';
                                                                        } ?>">
                                        <a class="nav-link" href="page.php?page=AttendanceReport">Attendance Report</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_attendance_report_student')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar <?php if ($page == 'AttendanceStudentReport') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link" href="page.php?page=AttendanceStudentReport">Student Wise
                                            Report</a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item <?php if ($page == 'listExpenses' || $page == 'listExpenseCategory' || $page == 'listSubExpenseCategory') {
                                            echo 'active';
                                        } ?>">
                        <a class="nav-link franchiseSidebar" data-toggle="collapse" href="#expenses"
                            aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Expenses</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse <?php if ($page == 'listExpenses' || $page == 'listExpenseCategory' || $page == 'listSubExpenseCategory') {
                                                    echo 'show';
                                                } ?>"
                            id="expenses">
                            <ul class="nav flex-column sub-menu">
                                <?php if ($db->permission('list_expenses')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar <?php if ($page == 'listExpenses') {
                                                                                echo 'active';
                                                                            } ?>">
                                        <a class="nav-link" href="page.php?page=listExpenses">Expenses List</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_expenses_category')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar<?php if ($page == 'listExpenseCategory') {
                                                                            echo 'active';
                                                                        } ?>">
                                        <a class="nav-link" href="page.php?page=listExpenseCategory">Expenses
                                            Categories</a>
                                    </li>
                                <?php } ?>
                                <?php if ($db->permission('list_expenses_subcategory')) { ?>
                                    <li
                                        class="nav-item franchiseSidebar<?php if ($page == 'listSubExpenseCategory') {
                                                                            echo 'active';
                                                                        } ?>">
                                        <a class="nav-link" href="page.php?page=listSubExpenseCategory">Expenses Sub
                                            Categories</a>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </li>


                </ul>
            </nav>
            <div class="main-panel">