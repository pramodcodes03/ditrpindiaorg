<?php
include_once('include/classes/course.class.php');
$course = new course();
include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();
include_once('include/classes/student.class.php');
$student = new student();

$action = isset($_POST['purchase_course']) ? $_POST['purchase_course'] : '';

$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$inst_course_id = isset($_GET['id']) ? $_GET['id'] : '';

if ($action != '') {
    $result = $student->purchase_course();
    $result = json_decode($result, true);
    //print_r($result);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = $result['message'];
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        header('location:page.php?page=myCoursesList');
    }
}

$wallet_details = $access->get_wallet('', $student_id, '4', '');

while ($data_wallet = $wallet_details->fetch_assoc()) {
    extract($data_wallet);
    $walletAmount = $TOTAL_BALANCE;
}

$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID FROM institute_courses A WHERE A.INSTITUTE_COURSE_ID = '$inst_course_id' AND A.DELETE_FLAG=0";
$ex = $db->execQuery($sql);
if ($ex && $ex->num_rows > 0) {
    while ($data = $ex->fetch_assoc()) {
        //print_r($data);          
        $COURSE_ID              = $data['COURSE_ID'];
        $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];

        if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
            $course_data = $db->get_course_detail($COURSE_ID);
            $course_name     = $course_data['COURSE_NAME_MODIFY'];
            $course_id     = $course_data['COURSE_ID'];

            $course_code        = $course_data['COURSE_CODE'];
            $course_duration     = $course_data['COURSE_DURATION'];
            $course_details     = $course_data['COURSE_DETAILS'];
            $course_eligibility    = $course_data['COURSE_ELIGIBILITY'];
            $course_fees        = $course_data['COURSE_FEES'];
            $course_mrp            = $course_data['COURSE_MRP'];
            $course_minamount     = $course_data['MINIMUM_AMOUNT'];
            $course_image        = $course_data['COURSE_IMAGE'];

            $path = COURSE_MATERIAL_PATH . '/' . $COURSE_ID . '/' . $course_image;

            $video1        = $course_data['VIDEO1'];
            $video2        = $course_data['VIDEO2'];
        }

        if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
            $course_data = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
            $course_name     = $course_data['COURSE_NAME_MODIFY'];
            $course_id     = $course_data['MULTI_SUB_COURSE_ID'];
            $course_code        = $course_data['MULTI_SUB_COURSE_CODE'];
            $course_duration     = $course_data['MULTI_SUB_COURSE_DURATION'];
            $course_details     = $course_data['MULTI_SUB_COURSE_DETAILS'];
            $course_eligibility    = $course_data['MULTI_SUB_COURSE_ELIGIBILITY'];
            $course_fees        = $course_data['MULTI_SUB_COURSE_FEES'];
            $course_mrp            = $course_data['MULTI_SUB_COURSE_MRP'];
            $course_minamount     = $course_data['MULTI_SUB_MINIMUM_AMOUNT'];
            $course_image        = $course_data['MULTI_SUB_COURSE_IMAGE'];

            $path = COURSE_WITH_SUB_MATERIAL_PATH . '/' . $MULTI_SUB_COURSE_ID . '/' . $course_image;

            $video1        = $course_data['VIDEO1'];
            $video2        = $course_data['VIDEO2'];
        }
    }
}


?>
<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $course_name ?>
                </h4>

                <div class="col-md-12">
                    <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-people mt-auto">
                                    <img src="<?= $path ?>" alt="<?= $course_name ?>">
                                </div>
                            </div>

                            <div class="col-md-6 boxMain">
                                <div class="rightBox">
                                    <h5> Course Duration : <?= $course_duration ?></h5>
                                    <h5> Course Fees : Rs. <?= $course_fees ?></h5>
                                    <h5>Minimum Amount To Pay : Rs. <?= $course_minamount ?></h5>

                                    <h5>Your Wallet Amount : Rs. <?= $walletAmount ?></h5>

                                    <input type="hidden" name="student_id" value="<?= $student_id ?>" />
                                    <input type="hidden" name="inst_id" value="1" />
                                    <input type="hidden" name="inst_course_id" value="<?= $inst_course_id ?>" />
                                    <input type="hidden" name="course_fees" value="<?= $course_fees ?>" />
                                    <input type="hidden" name="minimum_courseamount" value="<?= $course_minamount ?>" />
                                    <input type="hidden" name="wallet_amount" value="<?= $walletAmount ?>" />
                                    <br />
                                    <h5>Enter Amount You Want To Pay Now :</h5>
                                    <input class="col-md-6 form-control mt-10" type="text" name="paying_amount" value="" />
                                    <span class="help-block"><?= (isset($errors['paying_amount'])) ? $errors['paying_amount'] : '' ?></span>
                                    <br />

                                    <input type="submit" name="purchase_course" class="btn btn-primary btn1" value="Buy Now" />
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12 detailBox">
                        <h4> Course Syllabus : </h4>
                        <p> <?= html_entity_decode($course_details);  ?></p>
                        <h4> Course Eligibiity : </h4>
                        <p> <?= html_entity_decode($course_eligibility);  ?></p>
                    </div>
                </div>

                <div class="col-md-12 detailBox">
                    <h4> Sample Videos : </h4>
                    <div class="row col-md-12">
                        <div class="col-md-5" style="margin:15px">
                            <iframe width="450" height="315" src="<?= $video1 ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                        <div class="col-md-5" style="margin:15px">
                            <iframe width="450" height="315" src="<?= $video2 ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
</div>