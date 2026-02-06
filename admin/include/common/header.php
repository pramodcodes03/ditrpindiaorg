<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">

        <a class="navbar-brand brand-logo" href="page.php?page=index.php"><img src="<?= $logo ?>" class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="page.php?page=index.php"><img src="<?= $logo ?>" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">

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
          <li class="nav-item <?php if ($page == 'websiteDashboard') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/websiteDashboard">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>

          <li class="nav-item <?php if ($page == 'manageLogo') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageLogo">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Manage Logo</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageSlider' || $page == 'manageMarquee' || $page == 'manageTestimonial' || $page == 'manageSocialLinks' || $page == 'manageAdvertise') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#home-page" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Home Page</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'manageSlider' || $page == 'manageMarquee' || $page == 'manageTestimonial' || $page == 'manageSocialLinks' || $page == 'manageAdvertise' ||  $page == 'SliderBox') {
                                    echo 'show';
                                  } ?>" id="home-page">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'manageSlider') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/manageSlider">Main Slider</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'SliderBox') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/SliderBox">Slider Below Boxes</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'manageMarquee') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/manageMarquee">Marquee Tag</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'manageTestimonial') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/manageTestimonial">Testimonials</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'manageSocialLinks') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/manageSocialLinks">Social Media Links</a></li>
                <li class="nav-item"> <a class="nav-link <?php if ($page == 'manageAdvertise') {
                                                            echo 'active';
                                                          } ?>" href="/website_management/manageAdvertise">Advertisements (Popup)</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item <?php if ($page == 'AboutUs') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/AboutUs">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">About Us</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'franchiseDetails') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/franchiseDetails">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Franchise Details</span>
            </a>
          </li>

          <li class="nav-item <?php if ($page == 'manageServices') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageServices">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Services</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageAffiliations') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageAffiliations">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Affiliations</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageAchievers') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageAchievers">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Achievers</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageTeam') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageTeam">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Team</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageGalleryImages' || $page == 'manageGalleryVideos') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" data-toggle="collapse" href="#gallery" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Gallery</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?php if ($page == 'manageGalleryImages' || $page == 'manageGalleryVideos' || $page == 'manageNews') {
                                    echo 'show';
                                  } ?>" id="gallery">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item <?php if ($page == 'manageGalleryImages') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="/website_management/manageGalleryImages">Images</a></li>
                <li class="nav-item <?php if ($page == 'manageGalleryVideos') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="/website_management/manageGalleryVideos">Videos</a></li>
                <li class="nav-item <?php if ($page == 'manageNews') {
                                      echo 'active';
                                    } ?>"> <a class="nav-link" href="/website_management/manageNews">News</a></li>

              </ul>
            </div>
          </li>
          <li class="nav-item <?php if ($page == 'manageJobs') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageJobs">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Job Updates</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'listJobApply') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/listJobApply">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Job Enquiries</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'ContactUs') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/ContactUs">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Contact Details</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'managePolicies') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/managePolicies">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Policies</span>
            </a>
          </li>
          <!-- <li class="nav-item <?php if ($page == 'manageVerification') {
                                      echo 'active';
                                    } ?>">
            <a class="nav-link" href="/website_management/manageVerification">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Student Verification</span>
            </a>
          </li>   -->
          <li class="nav-item <?php if ($page == 'manageBlogs') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageBlogs">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Our Blogs</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageColors') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageColors">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Colour Management</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageHeaderImages') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageHeaderImages">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Header Images</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'managePartners') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/managePartners">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Partners</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'managePayments') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/managePayments">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Payments Methods</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'sampleCert') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/sampleCert">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Sample Certificates</span>
            </a>
          </li>
          <li class="nav-item <?php if ($page == 'manageDownload') {
                                echo 'active';
                              } ?>">
            <a class="nav-link" href="/website_management/manageDownload">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Download Material</span>
            </a>
          </li>
        </ul>
      </nav>
      <div class="main-panel">