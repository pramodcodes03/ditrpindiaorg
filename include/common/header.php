<style>
    marquee p {
        margin-bottom: 0px;
    }

    .animation_ind_up {
        animation: pulse_up 1s infinite;
    }
</style>
<?php
$res_color = $websiteManage->list_color('', '');
if ($res_color != '') {
    while ($data_color = $res_color->fetch_assoc()) {
        extract($data_color);
    }
}
?>

<body class="home1">
    <!--Preloader area start here-->
    <!--  <div class="book_preload">
            <div class="book">
                <div class="book__page"></div>
                <div class="book__page"></div>
                <div class="book__page"></div>
            </div>
        </div> -->
    <!--Preloader area end here-->

    <!--Full width header Start-->
    <div class="full-width-header">

        <!-- Toolbar Start -->
        <div class="rs-toolbar" style="background-color: <?= $top_header_color ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="rs-toolbar-left">
                            <div class="welcome-message">
                                <i class="fa fa-bank"></i>
                                <spanWELCOME TO>DITRP INDIA</spanWELCOME>
                                <!-- <i class="fa fa-bank"></i><span><?= $name ?></span>  -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="rs-toolbar-right">
                            <div class="toolbar-share-icon">
                                <ul>
                                    <li><a href="https://drive.usercontent.google.com/download?id=1wbC8P805dj-YaJJ54rS0vRe0o1jwfrF3&export=download&authuser=0" target="_blank"><i class="fa fa-android" style="font-size:24px; color:white"></i>
                                            <?php

                                            if ($res != '') {
                                                while ($data = $res->fetch_assoc()) {
                                                    extract($data);
                                            ?>
                                    <li><a href="<?= $link ?>" target="_blank"> <i class="<?= $icon ?>"></i></a></li>
                            <?php
                                                }
                                            }
                            ?>

                            <a class="cta-button animation_ind_up" href="/FranchiseRegistration"
                                style="font-size: 14px;
    background-color: yellow;
    padding: 5px;
    margin: 0px 11px;
    color: #000;">Franchise Form</a>

                            <li> <a class="btn btn-warning cta-button" href="<?= HTTP_HOST ?>/admin/index.php" style="font-size:14px;">Student Login</a></li>
                            <li> <a class="btn btn-warning  cta-button" href="<?= HTTP_HOST ?>/admin/index.php" style="font-size:14px;">Institute Login</a> </li>


                            <!--<li> -->
                            <!--    <div id="rs-calltoaction" class="rs-calltoaction">-->
                            <!--        <a class="cta-button" href="<?= HTTP_HOST ?>/admin/index.php" style="font-size:14px;">Student Login</a>-->

                            <!--        <a class="cta-button" href="<?= HTTP_HOST ?>/admin" style="font-size:14px;">Institute Login</a>-->
                            <!--    </div> -->
                            <!--</li>                         -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Toolbar End -->

        <!--Header Start-->
        <header id="rs-header" class="rs-header">

            <!-- Header Top Start -->
            <div class="rs-header-top">
                <div class="container">
                    <?php
                    $res = $websiteManage->list_contact('', '');
                    if ($res != '') {
                        while ($data = $res->fetch_assoc()) {
                            extract($data);
                    ?>
                            <div class="row">
                                <div class="col-md-4 col-sm-12 logosection">
                                    <?php
                                    $res = $websiteManage->list_logo('', '');
                                    if ($res != '') {
                                        while ($data = $res->fetch_assoc()) {
                                            extract($data);
                                            $logo = '/' . LOGO_PATH . '/' . $id . '/' . $image;
                                    ?>
                                            <div class="logo-area">
                                                <!-- <a href="index.php"><img src="<?= $logo ?>" alt="logo" style="width: 150px;"></a> -->
                                                <a href="index.php"><img src="/uploads/ditrp_logo/logo_.png" alt="logo" style="width: 150px;"></a>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>

                                <div class="col-md-4 col-sm-12 emailsection">
                                    <div class="header-contact" style="float: initial;">
                                        <div id="info-details" class="widget-text">
                                            <i class="glyph-icon flaticon-email"></i>
                                            <div class="info-text">
                                                <a href="mailto:<?= $email_id ?>">
                                                    <span>Mail Us</span>
                                                    <?= $email_id ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 loginsection">
                                    <div class="pull-right display">
                                        <div id="phone-details" class="widget-text" style="display: inline-flex; width: 100%;">
                                            <i class="glyph-icon flaticon-phone-call mobilecall"></i>
                                            <div class="info-text mobilecall">
                                                <a href="tel:<?= $contact_number1 ?>">
                                                    <span>Call Us</span>
                                                    <?= $contact_number1 ?>


                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <!-- Header Top End -->

            <!-- Menu Start -->
            <div class="menu-area menu-sticky" style="background-color: <?= $header_color ?>">
                <div class="container">
                    <div class="main-menu">
                        <div class="row relative">
                            <div class="col-sm-12">
                                <a class="rs-menu-toggle"><i class="fa fa-bars"></i>Menu</a>
                                <nav class="rs-menu">
                                    <ul class="nav-menu">
                                        <li class="<?php if ($page == '') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="index.php" class="home">Home</a>
                                        </li>
                                        <li class="<?php if ($page == 'about_us') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="about_us">About Us</a></li>
                                        <li class="<?php if ($page == 'courses') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="/courses">Courses</a></li>
                                        <li class="<?php if ($page == 'services') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="/services">Our Products</a></li>
                                        <li class="<?php if ($page == 'achievers') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="/achievers">Our Achievers</a></li>
                                        <li class="menu-item-has-children <?php if ($page == 'galleryImages' || $page == 'galleryVideos' || $page == 'News') {
                                                                                echo 'current-menu-item current_page_item';
                                                                            } ?>"> <a href="#">Gallery</a>
                                            <ul class="sub-menu">
                                                <li><a href="/galleryImages">Images</a></li>
                                                <li><a href="/galleryVideos">Videos</a></li>
                                                <li><a href="/News">News</a></li>
                                            </ul>
                                        </li>
                                        <!--<li> <a href="/ourteam">Our Team</a></li>-->
                                        <li class="menu-item-has-children <?php if ($page == 'ATCCertifications' || $page == 'StudentCertifications' || $page == 'OurCertifications') {
                                                                                echo 'current-menu-item current_page_item';
                                                                            } ?>"> <a href="#">Certificates</a>
                                            <ul class="sub-menu">
                                                <li><a href="/ATCCertificates">ATC Certificates</a></li>

                                                <li><a href="/StudentCertificates">Student Certificates</a></li>

                                                <li><a href="/OurCertificates">Our Certificates</a></li>

                                            </ul>
                                        </li>

                                        <li class="menu-item-has-children <?php if ($page == 'studentVerification' || $page == 'atcVerification') {
                                                                                echo 'current-menu-item current_page_item';
                                                                            } ?>"> <a href="#">Verification</a>
                                            <ul class="sub-menu">
                                                <li><a href="/studentVerification">Student Verification</a></li>
                                                <li><a href="https://ditrpindia.com/page.php?pg=certificate-verify" target="_blank">Student Verification DITRPINDIA</a></li>
                                                <li><a href="/atcVerification">ATC Verification</a></li>
                                                <li><a href="/oldCertificateVerification">Old Certficate Verification</a></li>
                                            </ul>
                                        </li>
                                        <li class="<?php if ($page == 'contact') {
                                                        echo 'current-menu-item current_page_item';
                                                    } ?>"> <a href="/contact">Contact Us</a></li>


                                        <li class="menu-item-has-children <?php if ($page == 'FranchiseRegistration' || $page == 'FranchiseEnquiry') {
                                                                                echo 'current-menu-item current_page_item';
                                                                            } ?>"> <a href="#">Franchise Registration</a>
                                            <ul class="sub-menu">
                                                <li class="<?php if ($page == 'FranchiseRegistration') {
                                                                echo 'current-menu-item current_page_item';
                                                            } ?>"> <a href="/FranchiseRegistration">Franchise Registration</a></li>

                                                <li class="<?php if ($page == 'FranchiseEnquiry') {
                                                                echo 'current-menu-item current_page_item';
                                                            } ?>"> <a href="/FranchiseEnquiry">Franchise Enquiry</a></li>
                                            </ul>
                                        </li>



                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $res = $websiteManage->list_marquee('', ' AND  inst_id= 1');
            if ($res != '') {
                while ($data = $res->fetch_assoc()) {
                    extract($data);
            ?>
                    <!-- <marquee style="background-color: <?= $marquee_color ?>; color: #FFFFFF; font-size: 18px; padding: 10px;"> <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> </marquee> -->
                    <marquee style="background-color: <?= $marquee_color ?>; color: #FFFFFF; font-size: 18px; padding: 10px;">WELCOME TO DITRP INDIA!</marquee>
            <?php
                }
            }
            ?>
        </header>
        <!--Header End-->
    </div>
    <!--Full width header End-->