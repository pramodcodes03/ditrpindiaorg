<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="<?= $logo ?>" class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="<?= $logo ?>" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end studentSection">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
          <span id='ct7'></span>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="<?= isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : ''  ?>" alt="User Image" />
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
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
              <!-- <a class="dropdown-item" href="page.php?page=studentDetails">
                <i class="ti-settings text-primary"></i>
                Profile
              </a> -->
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
      <nav class="sidebar sidebar-offcanvas studentSectionSidebar" id="sidebar">
        <ul class="nav">
          <li class="nav-item <?php if ($page == 'IMSDashboard') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=IMSDashboard">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Mainboard</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'studentDetails') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=studentDetails">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Student Details</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listOnlineClasses') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listOnlineClasses">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Online Classes Links</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'myCoursesList') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=myCoursesList">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">My Courses</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'allCoursesList') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=allCoursesList">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">All Courses</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listExamResults') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listExamResults">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Exam Results</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listDemoExamResults') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listDemoExamResults">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Demo Exam Results</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'Wallet') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=Wallet">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Wallet</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'generate-resume') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=viewResume&id=<?= $_SESSION['user_id'] ?>" target="_blank">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">My Resume</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'listAdvertise') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listAdvertise">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Advertisment</span>
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

          <li class="nav-item <?php if ($page == 'listAttendance') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="page.php?page=listAttendance">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Attendance </span>
            </a>
          </li>

        </ul>
      </nav>
      <div class="main-panel">