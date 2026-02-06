<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="<?= $logo ?>" class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="<?= $logo ?>" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">


        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <a href="https://ditrp.digitalnexstep.com//admin/ourCentersLocation" target="_blank" style="    color: #fff;
    padding: 10px 30px;
    font-weight: 700;">OUR CENTER'S</a>

        <ul class="navbar-nav navbar-nav-right">
          <span id='ct7'></span>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="<?= isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : ''  ?>" alt="User Image" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="page.php?page=updateInstitute">
                <i class="ti-settings text-primary"></i>
                Profile
              </a>
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
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="page.php?page=updateInstitute">
                <i class="ti-settings text-primary"></i>
                Profile
              </a>
              <a class="dropdown-item" href="login.php?logout" title="Logout">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>

        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item <?php if ($page == 'IMSDashboard') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=IMSDashboard">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'listFranchiseEnquiry') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listFranchiseEnquiry">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Franchise Enquiry</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'listServicesEnquiry') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listServicesEnquiry">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Services Enquiry</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'listFranchise') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listFranchise">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Franchise List</span>
            </a>
          </li>
          <!-- <li class="nav-item <?php if ($page == 'studentEnquiry' || $page == 'studentAdmission' || $page == 'studentReAdmission' || $page == 'studentFees' || $page == 'refferalAmount' || $page == 'listTypingStudent') {
                                      echo 'active';
                                    } ?>">
            <a class="nav-link" data-toggle="collapse" href="#student-page" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage Students</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'studentEnquiry' || $page == 'studentAdmission'  || $page == 'studentReAdmission' || $page == 'listStudentFees' || $page == 'refferalAmount' || $page == 'listTypingStudent') {
                                    echo 'show';
                                  } ?>" id="student-page">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'studentEnquiry') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=studentEnquiry">Student Enquiry</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'studentAdmission') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=studentAdmission">Student Admission</a></li>
          
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'studentReAdmission') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=studentReAdmission">Re-Admission</a></li>
            
			
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listStudentFees') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listStudentFees">Student Fees</a></li> 
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'refferalAmount') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=refferalAmount">Set Referral Amount</a></li>  
              </ul>
            </div>
          </li>-->
          <li class="nav-item <?php if ($page == 'listAwardCategories' || $page == 'listCourse' || $page == 'listExams' || $page == 'listQueBank' || $page == 'listCourseMultiSub' || $page == 'listExamsMultiSub' || $page == 'listQueBankMultiSub' || $page == 'listTypingCourses' || $page == 'listExamsTypingCourses') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#home-page" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Examinations</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listAwardCategories' || $page == 'listPlans' || $page == 'listCourse' || $page == 'listExams' || $page == 'listQueBank' || $page == 'listCourseMultiSub' || $page == 'listExamsMultiSub' || $page == 'listQueBankMultiSub' || $page == 'listTypingCourses' || $page == 'listExamsTypingCourses') {
                                    echo 'show';
                                  } ?>" id="home-page">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listAwardCategories') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listAwardCategories">Award Categories</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listPlans') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listPlans">Institute Plans</a></li>

                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listCourse') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listCourse">Courses</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listExams') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listExams">Exams</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listQueBank') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listQueBank">Question Bank</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listCourseMultiSub') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listCourseMultiSub">Courses (Multiple Subjects)</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listExamsMultiSub') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listExamsMultiSub">Exams (Multiple Subjects)</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listQueBankMultiSub') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listQueBankMultiSub">Question Bank (Multiple Subjects)</a></li>


                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listTypingCourses') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listTypingCourses">Typing Courses</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'listExamsTypingCourses') {
                                                            echo 'active';
                                                          } ?>" href="page.php?page=listExamsTypingCourses">Typing Courses Exam</a></li>

              </ul>
            </div>
          </li>

          <!--   <li class="nav-item <?php if ($page == 'resetExam' || $page == 'examOTP' || $page == 'listPracticalExamResult' || $page == 'listExamResultsAll' || $page == 'listHallticket') {
                                        echo 'active';
                                      } ?>">
            <a class="nav-link" data-toggle="collapse" href="#exam" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Student Exams</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'resetExam' || $page == 'examOTP' || $page == 'listPracticalExamResult' || $page == 'listExamResultsAll' || $page == 'listHallticket') {
                                    echo 'show';
                                  } ?>" id="exam">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php if ($page == 'resetExam') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=resetExam">Reset Exam</a></li>
                <li class="nav-item <?php if ($page == 'examOTP') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=examOTP">Exam Code</a></li>
                <li class="nav-item <?php if ($page == 'listPracticalExamResult') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listPracticalExamResult">Offine Exams Marks Update</a></li>
                <li class="nav-item <?php if ($page == 'listExamResultsAll') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listExamResultsAll">All Exam Results</a></li>
                <li class="nav-item <?php if ($page == 'listHallticket') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listHallticket">Hall Tickets</a></li>
              </ul>
            </div>
          </li> -->


          <li class="nav-item <?php if ($page == 'listRequestedCertificates' || $page == 'listExamResults') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#certificate" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Certificates</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listRequestedCertificates' || $page == 'listExamResults') {
                                    echo 'show';
                                  } ?>" id="certificate">
              <ul class="nav flex-column sub-menu">
                <!-- <li class="nav-item <?php if ($page == 'listExamResults') {
                                            echo 'active';
                                          } ?>"> <a class="nav-link" href="page.php?page=listExamResults">Apply For Certificates</a></li> -->

                <li class="nav-item <?php if ($page == 'listRequestedCertificates') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listRequestedCertificates">Approve Certificates</a></li>

                <li class="nav-item <?php if ($page == 'listOrderRequestedCertificates') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listOrderRequestedCertificates">Print Order Certificates</a></li>

                <!-- <li class="nav-item <?php if ($page == 'viewStudentCertificate') {
                                            echo 'active';
                                          } ?>"> <a class="nav-link" href="page.php?page=viewStudentCertificate">View Certificates</a></li> -->
              </ul>
            </div>
          </li>

          <li class="nav-item <?php if ($page == 'oldCertificate') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=oldCertificate">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Old Certificates </span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listOnlineClasses' || $page == 'listMarqueeNotification' || $page == 'listAdvertisement') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#notification" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Notifications</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listOnlineClasses' || $page == 'listMarqueeNotification' || $page == 'listAdvertisement') {
                                    echo 'show';
                                  } ?>" id="notification">
              <ul class="nav flex-column sub-menu">

                <!-- <li class="nav-item <?php if ($page == 'listOnlineClasses') {
                                            echo 'active';
                                          } ?>"> <a class="nav-link" href="page.php?page=listOnlineClasses">OnLine Class Notifications </a></li> -->

                <li class="nav-item <?php if ($page == 'listMarqueeNotification') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listMarqueeNotification">Marquee Notification</a></li>
                <li class="nav-item <?php if ($page == 'listAdvertisement') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listAdvertisement">Advertisements</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item <?php if ($page == 'franchiseWallet') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=franchiseWallet">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Franchise Wallet </span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'courierWallet') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=courierWallet">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Courier Wallet </span>
            </a>
          </li>


          <!-- <li class="nav-item <?php if ($page == 'Wallet') {
                                      echo 'active';
                                    } ?>">
            <a class="nav-link" href="page.php?page=Wallet">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Student Wallet </span>
            </a>
          </li>-->

          <li class="nav-item <?php if ($page == 'manageBackground') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=manageBackground">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Upload Background </span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listSupport') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listSupport">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Help Support</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listBirthday') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listBirthday">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Birthday's List </span>
            </a>
          </li>
          <!-- /admin/listStaff -->
          <li class="nav-item <?php if ($page == 'listStaff') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listStaff">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">User Management </span>
            </a>
          </li>

          <!-- <li class="nav-item <?php if ($page == 'listBatches' || $page == 'Attendance' || $page == 'AttendanceReport' || $page == 'AttendanceStudentReport') {
                                      echo 'active';
                                    } ?>">
            <a class="nav-link" data-toggle="collapse" href="#attendance" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage Attendance</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listBatches' || $page == 'Attendance' || $page == 'AttendanceReport' || $page == 'AttendanceStudentReport') {
                                    echo 'show';
                                  } ?>" id="attendance">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php if ($page == 'listBatches') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listBatches">Batches</a></li>           
                <li class="nav-item <?php if ($page == 'Attendance') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=Attendance">Attendance</a></li>
                <li class="nav-item <?php if ($page == 'AttendanceReport') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=AttendanceReport">Attendance Report</a></li>
                <li class="nav-item <?php if ($page == 'AttendanceStudentReport') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=AttendanceStudentReport">Student Wise Report</a></li>

              </ul>
            </div>
          </li> -->

          <!-- <li class="nav-item <?php if ($page == 'listExpenses' || $page == 'listExpenseCategory' || $page == 'listSubExpenseCategory') {
                                      echo 'active';
                                    } ?>">
            <a class="nav-link" data-toggle="collapse" href="#expenses" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Expenses</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listExpenses' || $page == 'listExpenseCategory' || $page == 'listSubExpenseCategory') {
                                    echo 'show';
                                  } ?>" id="expenses">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php if ($page == 'listExpenses') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listExpenses">Expenses List</a></li>           
                <li class="nav-item <?php if ($page == 'listExpenseCategory') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listExpenseCategory">Expenses Categories</a></li>
                <li class="nav-item <?php if ($page == 'listSubExpenseCategory') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listSubExpenseCategory">Expenses Sub Categories</a></li>
              </ul>
            </div>
          </li> -->

          <li class="nav-item <?php if ($page == 'listSeminar' || $page == 'listSeminarStudent') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#seminar" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Seminar Section</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'listSeminar' || $page == 'listSeminarStudent') {
                                    echo 'show';
                                  } ?>" id="seminar">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php if ($page == 'listSeminar') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listSeminar">Seminar List</a></li>
                <li class="nav-item <?php if ($page == 'listSeminarStudent') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="page.php?page=listSeminarStudent">Seminar Student</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item <?php if ($page == 'viewProduct') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=viewProduct">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Product </span>
            </a>
          </li>
          <!--/admin/masterPassword-->
          <li class="nav-item <?php if ($page == 'masterPassword') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="#">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Master Password </span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'list-festival') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=list-festival">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Festival</span>
            </a>
          </li>


          <li class="nav-item <?php if ($page == 'listMarkeing&type=marketing') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listMarkeing&type=marketing">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Markeing Material</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listRechargeOffers') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listRechargeOffers">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Recharge Offers</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listRechargeRequest') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listRechargeRequest">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Recharge Request</span>
            </a>
          </li>



        </ul>
      </nav>
      <div class="main-panel">