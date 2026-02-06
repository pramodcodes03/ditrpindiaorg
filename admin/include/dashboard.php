  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.php"><img src="<?= $logo ?>" class="mr-2" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="<?= $logo ?>" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

          <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img src="<?= isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : ''  ?>" alt="User Image" />
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="page.php?page=login.php?logout" title="Logout">
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

        <div class="main-panel" style="width:100%">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="row">

                  <div class="col-12 col-xl-8 mb-4 mb-xl-0 text-center">
                    <h3 class="font-weight-bold">Welcome To <?= $_SESSION['user_fullname']; ?></h3>
                  </div>

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-people">
                    <img src="resources/images/dashboard/institute_homepage.jpg" alt="people">
                  </div>
                </div>
              </div>
              <div class="col-md-6 grid-margin transparent">
                <div class="row">
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                      <div class="card-body">
                        <a href="/website_management/websiteDashboard">
                          <p class="fs-30 mb-2" style="    font-size: 24px;
    padding: 25px;
    text-decoration: none;
    color: #000;color:#fff;
}">Website Management</p>
                        </a>

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-4 stretch-card transparent">
                    <div class="card card-light-blue">
                      <div class="card-body">
                        <a href="page.php?page=IMSDashboard">
                          <p class="fs-30 mb-2" style="    font-size: 24px;
    padding: 25px;
    text-decoration: none;
    color: #000; color:#fff;
}">Institute Portal</p>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>