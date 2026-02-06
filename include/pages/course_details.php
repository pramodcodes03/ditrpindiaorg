<?php
//print_r($_GET);
$course_id = isset($id) ?? '';
if ($course_id != '') {
    $sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID  FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.INSTITUTE_COURSE_ID = $course_id";
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

                $video1        = $course_data['VIDEO1'];
                $video2        = $course_data['VIDEO2'];
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

                $video1        = $course_data['VIDEO1'];
                $video2        = $course_data['VIDEO2'];
            }

            if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
                $course_data = $db->get_course_detail_typing($TYPING_COURSE_ID);

                $course_name         = $course_data['COURSE_NAME_MODIFY'];
                $m_id                 = $course_data['TYPING_COURSE_ID'];
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
        }
    }
}
?>


<?php
$res = $websiteManage->list_headimages('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $image = 'resources/default_images/about_default.jpg';
        if ($courses != '')
            $image     = BANNERS_PATH . '/' . $id . '/' . $courses;
?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title"><?= $course_name ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<!-- Courses Details Start -->
<div class="rs-courses-details pt-100 pb-70">
    <div class="container">
        <div class="row mb-30">
            <div class="col-lg-12 col-md-12">
                <div class="col-md-6">
                    <div class="detail-img">
                        <img src="<?= $path ?>" alt="<?= $course_name ?>" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="course-content">
                        <!--<h3 class="course-title">Computer Science And Engineering</h3>-->
                        <div class="course-instructor">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="instructor-title">BASIC <span class="primary-color">INFORMATION</span></h3>
                                    <?php
                                    if ($display_fees != 0) {
                                    ?>
                                        <div class="row info-list">
                                            <div class="col-md-8">
                                                <ul>
                                                    <li>
                                                        <span>Course Duration :</span> <?= $course_duration ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>

                                    <div class="apply-btn">
                                        <a href="admissionForm&id=<?= $instituteCourseId ?>">Admission Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 rs-courses-details">
                    <div class="container row">
                        <div class="course-desc">
                            <div class="desc-text">
                                <?= html_entity_decode($course_details); ?>
                            </div>
                        </div>
                        <div class="course-desc">
                            <div class="desc-text">
                                <?= html_entity_decode($course_eligibility); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rs-courses-details">
                <div class="row">
                    <div class="col-md-6 course-desc">
                        <div class="desc-text">
                            <iframe width="560" height="315" src="<?= $video1 ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="col-md-6  course-desc">
                        <div class="desc-text">
                            <iframe width="560" height="315" src="<?= $video2 ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container pt-100">
            <div class="related-courses rs-courses-3">
                <div class="sec-title-2 mb-50">
                    <h2>OTHER COURSES</h2>
                </div>
                <div class="rs-carousel owl-carousel">
                    <?php
                    $sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID FROM institute_courses A WHERE A.DELETE_FLAG=0";
                    //echo $sql;
                    $ex = $db->execQuery($sql);
                    if ($ex && $ex->num_rows > 0) {
                        while ($data = $ex->fetch_assoc()) {
                            $COURSE_ID                 = $data['COURSE_ID'];
                            $MULTI_SUB_COURSE_ID    = $data['MULTI_SUB_COURSE_ID'];
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

                    ?>

                            <div class="course-item" style="margin:10px">
                                <div class="course-img">
                                    <img src="<?= $path ?>" alt="" />
                                    <!-- <span class="course-value"><?= $course_fees ?></span> -->
                                    <div class="course-toolbar">
                                        <div class="course-duration">
                                            <i class="fa fa-clock-o"></i> <?= $course_duration ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="course-body">
                                    <div class="course-desc">
                                        <h4 class="course-title"><a href="course-details&id=<?= $instituteCourseId ?>"> <?= $course_name ?></a></h4>
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

                    <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <!-- Courses Details End -->