    <!-- Services Start -->
    <div class="rs-services rs-services-style1">
        <div class="container">
            <div class="row">
                <?php

                if ($res != '') {
                    while ($data = $res->fetch_assoc()) {
                        extract($data);
                ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="services-item rs-animation-hover" style="background-color:<?= $box_color1 ?>">
                                <div class="services-icon" style="background-color:<?= $box_color1 ?>">
                                    <i class="fa fa-american-sign-language-interpreting rs-animation-scale-up"></i>
                                </div>
                                <div class="services-desc">
                                    <h4 class="services-title"><?= $box1_title ?></h4>
                                    <p><?= $box1_desc ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="services-item rs-animation-hover" style="background-color:<?= $box_color2 ?>">
                                <div class="services-icon" style="background-color:<?= $box_color2 ?>">
                                    <i class="fa fa-book rs-animation-scale-up"></i>
                                </div>
                                <div class="services-desc">
                                    <h4 class="services-title"><?= $box2_title ?></h4>
                                    <p><?= $box2_desc ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="services-item rs-animation-hover" style="background-color:<?= $box_color3 ?>">
                                <div class="services-icon" style="background-color:<?= $box_color3 ?>">
                                    <i class="fa fa-user rs-animation-scale-up"></i>
                                </div>
                                <div class="services-desc">
                                    <h4 class="services-title"><?= $box3_title ?></h4>
                                    <p><?= $box3_desc ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="services-item rs-animation-hover" style="background-color:<?= $box_color4 ?>">
                                <div class="services-icon" style="background-color:<?= $box_color4 ?>">
                                    <i class="fa fa-graduation-cap rs-animation-scale-up"></i>
                                </div>
                                <div class="services-desc">
                                    <h4 class="services-title"><?= $box4_title ?></h4>
                                    <p><?= $box4_desc ?></p>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <!-- Services End -->

    <!-- About Us Start -->
    <div id="rs-about" class="rs-about sec-spacer">
        <div class="container">
            <div class="sec-title mb-50 text-center">

            </div>

            <?php
            $res = $websiteManage->list_about('', '');
            if ($res != '') {
                while ($data = $res->fetch_assoc()) {
                    extract($data);
                    $about_home = 'resources/default_images/about_default.jpg';
                    if ($homepage_image != '')
                        $about_home = ABOUTUS_PATH . '/' . $id . '/' . $homepage_image;
            ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="about-img rs-animation-hover">
                                <img src="<?= $about_home ?>" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="about-desc">
                                <p><?= html_entity_decode($about_short) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px">
                        <div id="accordion" class="rs-accordion-style1">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header active" id="headingTwo">
                                        <h3 class="acdn-title">
                                            Our Mission
                                        </h3>
                                    </div>
                                    <div id="collapseTwo" class="collapse show">
                                        <div class="card-body">
                                            <?= html_entity_decode($mission_short) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-header active mb-0">
                                        <h3 class="acdn-title">
                                            Our Vision
                                        </h3>
                                    </div>
                                    <div id="collapseThree" class="collapse show">
                                        <div class="card-body">
                                            <?= html_entity_decode($vision_short) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!--    <div id="accordion" class="rs-accordion-style1">                           
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h3 class="acdn-title" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Our Mission
                                    </h3>
                                </div>
                                <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <?= html_entity_decode($mission_short) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header mb-0" id="headingThree">
                                    <h3 class="acdn-title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Our Vision
                                    </h3>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordion">
                                    <div class="card-body">
                                        <?= html_entity_decode($vision_short) ?>
                                    </div>
                                </div>
                            </div>
                        </div> -->


            <?php
                }
            }
            ?>
        </div>
    </div>
    </div>
    <!-- About Us End -->

    <!-- Courses Start -->
    <div id="rs-courses" class="rs-courses sec-color sec-spacer">
        <div class="container">
            <div class="sec-title mb-50 text-center">
                <h2>OUR POPULAR COURSES</h2>
            </div>

            <div class="rs-courses-3">
                <div class="container">
                    <div class="row grid">
                        <?php
                        $sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID  FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 AND A.INSTITUTE_ID = 1  LIMIT 0,6 ";
                        //echo $sql;
                        $ex = $db->execQuery($sql);
                        if ($ex && $ex->num_rows > 0) {
                            while ($data = $ex->fetch_assoc()) {
                                $COURSE_ID                 = $data['COURSE_ID'];
                                $MULTI_SUB_COURSE_ID    = $data['MULTI_SUB_COURSE_ID'];
                                $TYPING_COURSE_ID       = $data['TYPING_COURSE_ID'];
                                $instituteCourseId      = $data['INSTITUTE_COURSE_ID'];

                                if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
                                    $course_data = $db->get_course_detail($COURSE_ID);

                                    $course_name     = $course_data['COURSE_NAME_MODIFY'];
                                    $c_id     = $course_data['COURSE_ID'];

                                    $course_code        = $course_data['COURSE_CODE'];
                                    $course_duration     = $course_data['COURSE_DURATION'];
                                    $course_details     = $course_data['COURSE_DETAILS'];
                                    $course_eligibility    = $course_data['COURSE_ELIGIBILITY'];
                                    $course_fees        = $course_data['COURSE_FEES'];
                                    $course_mrp            = $course_data['COURSE_MRP'];
                                    $course_minamount     = $course_data['MINIMUM_AMOUNT'];
                                    $course_image        = $course_data['COURSE_IMAGE'];

                                    $path = COURSE_MATERIAL_PATH . '/' . $COURSE_ID . '/' . $course_image;

                                    $display_fees        = $course_data['DISPLAY_FEES'];
                                }

                                if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
                                    $course_data = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);

                                    $course_name     = $course_data['COURSE_NAME_MODIFY'];
                                    $m_id             = $course_data['MULTI_SUB_COURSE_ID'];
                                    $course_code        = $course_data['MULTI_SUB_COURSE_CODE'];
                                    $course_duration     = $course_data['MULTI_SUB_COURSE_DURATION'];
                                    $course_details     = $course_data['MULTI_SUB_COURSE_DETAILS'];
                                    $course_eligibility    = $course_data['MULTI_SUB_COURSE_ELIGIBILITY'];
                                    $course_fees        = $course_data['MULTI_SUB_COURSE_FEES'];
                                    $course_mrp            = $course_data['MULTI_SUB_COURSE_MRP'];
                                    $course_minamount     = $course_data['MULTI_SUB_MINIMUM_AMOUNT'];
                                    $course_image        = $course_data['MULTI_SUB_COURSE_IMAGE'];

                                    $path = COURSE_WITH_SUB_MATERIAL_PATH . '/' . $MULTI_SUB_COURSE_ID . '/' . $course_image;

                                    $display_fees        = $course_data['DISPLAY_FEES'];
                                }

                                if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
                                    $course_data = $db->get_course_detail_typing($TYPING_COURSE_ID);

                                    $course_name     = $course_data['COURSE_NAME_MODIFY'];
                                    $m_id             = $course_data['TYPING_COURSE_ID'];
                                    $course_code        = $course_data['TYPING_COURSE_CODE'];
                                    $course_duration     = $course_data['TYPING_COURSE_DURATION'];
                                    $course_details     = $course_data['TYPING_COURSE_DETAILS'];
                                    $course_eligibility    = $course_data['TYPING_COURSE_ELIGIBILITY'];
                                    $course_fees        = $course_data['TYPING_COURSE_FEES'];
                                    $course_mrp            = $course_data['TYPING_COURSE_MRP'];
                                    $course_minamount     = $course_data['TYPING_MINIMUM_AMOUNT'];
                                    $course_image        = $course_data['TYPING_COURSE_IMAGE'];

                                    $path = COURSE_WITH_TYPING_MATERIAL_PATH . '/' . $TYPING_COURSE_ID . '/' . $course_image;

                                    $display_fees        = $course_data['DISPLAY_FEES'];
                                }

                        ?>

                                <div class="col-lg-4 col-md-6 grid-item">
                                    <div class="course-item">
                                        <div class="course-img">
                                            <img src="<?= $path ?>" alt="" />
                                            <!-- <span class="course-value">Rs. <?= $course_fees ?>/-</span> -->
                                            <div class="course-toolbar">
                                                <div class="course-duration">
                                                    <i class="fa fa-clock-o"></i> <?= $course_duration ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-body">
                                            <div class="course-desc">
                                                <h4 class="course-title"><a href="course-details/<?= $instituteCourseId ?>"> <?= $course_name ?></a></h4>
                                                <?php
                                                if ($display_fees != 0) {
                                                ?>
                                                    <div class="columns">
                                                        <ul class="price">
                                                            <li class="header">
                                                                
                                                            </li>

                                                            <li>
                                                                
                                                            </li>
                                                        </ul>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <p>
                                                    Course Code : <?= $course_code ?>
                                                     
                                                </p>
                                                <p>
                                        Course Duration : <?= $course_duration ?>
                                    </p>
                                            </div>
                                        </div>
                                        <div class="course-footer">
                                            <div class="course-button">
                                                <a href="/course-enquiry/<?= $instituteCourseId ?>">ENQUIRY NOW</a>
                                            </div>

                                            <div class="course-button">
                                                <a href="/course-details/<?= $instituteCourseId ?>">DETAILS</a>
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
            </div>
        </div>
    </div>
    <!-- Courses End -->

    <!-- Events Start -->
    <div id="rs-events" class="rs-events sec-spacer">
        <div class="container">
            <div class="sec-title mb-50 text-center">
                <h2>OUR ACHIEVERS</h2>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="rs-carousel owl-carousel" data-loop="false" data-items="4" data-margin="0" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="1200" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="1" data-ipad-device-nav="true" data-ipad-device-dots="true" data-md-device="4" data-md-device-nav="true" data-md-device-dots="false">
                        <?php
                        $res = $websiteManage->list_achievers('', '');
                        if ($res != '') {
                            while ($data = $res->fetch_assoc()) {
                                extract($data);
                                $achiever_img = 'resources/default_images/achiever_default.jpg';
                                if ($image != '')
                                    $achiever_img = ACHIEVERS_PATH . '/' . $id . '/' . $image;
                        ?>
                                <div class="event-item">
                                    <div class="event-img">
                                        <img class="achiver-home" src="<?= $achiever_img ?>" alt="" />
                                        <!-- <a class="image-link" href="events-details.html" title="University Tour 2018">
                                        <i class="fa fa-link"></i>
                                    </a> -->
                                    </div>
                                    <div class="events-details sec-color">
                                        <h4 class="event-title"><a href="#"><?= $name ?></a></h4>
                                        <div class="event-meta">
                                            <h5><?= $course ?></h5>
                                            <p><?= $description ?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Events End -->

    <!-- Team Start -->
    <div id="rs-team" class="rs-team sec-color sec-spacer">
        <div class="container">
            <div class="sec-title mb-50 text-center">
                <h2>OUR EXPERIENCED STAFFS</h2>
            </div>
            <div class="rs-carousel owl-carousel" data-loop="false" data-items="4" data-margin="0" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="1200" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="1" data-ipad-device-nav="true" data-ipad-device-dots="true" data-md-device="4" data-md-device-nav="true" data-md-device-dots="false">
                <?php
                $res = $websiteManage->list_team('', '');
                if ($res != '') {
                    while ($data = $res->fetch_assoc()) {
                        extract($data);
                        $team_img = 'resources/default_images/team_default.jpg';
                        if ($image != '')
                            $team_img = OURTEAM_PATH . '/' . $id . '/' . $image;
                ?>
                        <div class="team-item pd-25">
                            <div class="team-img">
                                <img class="team-home" src="<?= $team_img ?>" alt="team Image" />
                                <div class="normal-text">
                                    <h3 class="team-name"><?= $name ?></h3>
                                    <span class="subtitle"><?= $designation ?></span>
                                </div>
                            </div>
                            <div class="team-content">
                                <div class="overly-border"></div>
                                <div class="display-table">
                                    <div class="display-table-cell">
                                        <h3 class="team-name"><a href="teachers-single.html"><?= $name ?></a></h3>
                                        <span class="team-title"><?= $designation ?></span>
                                        <p class="team-desc"><?= $description ?></p>

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
    </div>
    <!-- Team End -->

    <!-- Products Start -->
    <div id="rs-events" class="rs-events sec-spacer sec-color">
        <div class="container">
            <div class="sec-title mb-50 text-center">
                <h2>JOB UPDATES</h2>
            </div>
            <div class="rs-carousel owl-carousel" data-loop="false" data-items="3" data-margin="30" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="1200" data-dots="true" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="2" data-ipad-device-nav="true" data-ipad-device-dots="true" data-md-device="3" data-md-device-nav="true" data-md-device-dots="true">
                <?php
                $res = $websiteManage->list_jobpost('', '');
                if ($res != '') {
                    while ($data = $res->fetch_assoc()) {
                        extract($data);
                        //print_r($data);
                        $job_img = 'resources/default_images/job_default.jpg';
                        if ($image != '')
                            $job_img = JOBPOST_PATH . '/' . $id . '/' . $image;
                ?>
                        <div class="event-item">
                            <div class="event-img">
                                <img src="<?= $job_img ?>" alt="" style="height:200px" />
                                <a class="image-link" href="job-details&id=<?= $id ?>" title="University Tour 2018">
                                    <i class="fa fa-link"></i>
                                </a>
                            </div>
                            <div class="events-details white-bg">
                                <div class="event-date">
                                    <i class="fa fa-calendar"></i>
                                    <span>Start Date : <?= $post_date ?></span>
                                    <br />
                                    <i class="fa fa-calendar"></i>
                                    <span>Last Date : <?= $last_date ?></span>
                                </div>
                                <h4 class="event-title"><a href="job-details/<?= $id ?>"><?= $title ?></a></h4>
                                <div class="event-meta">
                                    <div class="event-location">
                                        <span>Job Code : <?= $job_code ?></span>
                                    </div>
                                </div>
                                <div class="event-btn">
                                    <a class="btn btn-primary" href="job-details/<?= $id ?>">View Details <i class="fa fa-angle-double-right"></i></a>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Testimonial Start -->
    <div id="rs-testimonial" class="rs-testimonial bg5 sec-spacer">
        <div class="container">
            <div class="sec-title mb-50 text-center">
                <h2 class="white-color">WHAT PEOPLE SAYS</h2>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="rs-carousel owl-carousel" data-loop="true" data-items="2" data-margin="30" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="1200" data-dots="true" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="2" data-ipad-device-nav="true" data-ipad-device-dots="true" data-md-device="2" data-md-device-nav="true" data-md-device-dots="true">
                        <?php
                        $res = $websiteManage->list_testimonial('', '');
                        if ($res != '') {
                            while ($data = $res->fetch_assoc()) {
                                extract($data);
                                $testimonial_img = 'resources/default_images/testimonial_default.jpg';
                                if ($image != '')
                                    $testimonial_img = TESTIMONIAL_PATH . '/' . $id . '/' . $image;
                        ?>
                                <div class="testimonial-item">
                                    <div class="testi-img">
                                        <img src="<?= $testimonial_img ?>" alt="Jhon Smith">
                                    </div>
                                    <div class="testi-desc">
                                        <h4 class="testi-name"><?= $name ?></h4>
                                        <p>
                                            <?= $description ?>
                                        </p>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->