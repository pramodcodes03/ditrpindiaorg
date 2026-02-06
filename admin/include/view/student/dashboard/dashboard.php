<?php
$user_id = $_SESSION['user_id'];
$month       = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
$day           = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');

$month1       = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
$day1       = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';

include_once('include/classes/student.class.php');
$student = new student();

include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();

$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

$inst_id = $db->get_student_institute_id($student_id);
$inst_name = $db->get_institute_name($inst_id);

$res = $student->list_student($student_id, '', '');
if ($res != '') {
   while ($resdata = $res->fetch_assoc()) {
      extract($resdata);
      //print_r($resdata);
   }
}
$STUD_PHOTO = ($STUD_PHOTO != '') ? STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO : '../uploads/default_user.png';

include_once('include/classes/tools.class.php');
$tools = new tools();

$res = $tools->list_marquee('', " AND inst_id = $inst_id");
if ($res != '') {
   $srno = 1;
   while ($data = $res->fetch_assoc()) {
      extract($data);
   }
}

?>
<?php
if ($name != '') {
?>
   <marquee class="marqueeTag"><?= html_entity_decode($name) ?></marquee>
<?php
}
?>


<div class="content-wrapper">
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <div class="col-12">
               <div class="row">
                  <div class="col-md-4">
                     <h3 class="font-weight-bold mb-4">Student Dashboard</h3>
                     <h4 class="font-weight-bold">Welcome, <br /><br />
                        <p style="color: #8b8500; font-size: 18px; line-height: 28px;"><strong><?= $ABBREVIATION ?>. <?= $STUDENT_FULLNAME ?></strong></p>
                     </h4>
                  </div>

                  <div class="col-md-8">
                     <div class="row">
                        <div class="col-md-7 grid-margin transparent">
                           <h3>Refer & Earn </h3>
                           <p>Now refer any of your Friends and Get a bonus amount in your wallet on confirm admission of your friend.</p>
                           <h4> Your Referal Code Is : <b><?= $STUDENT_CODE ?></b>
                              <a class='btn btn-success btn1' title='Share' data-toggle='modal' data-target='#shareModal'><i class='mdi mdi-share-variant'></i>Share</a>
                           </h4>

                           <!-- <div class="sharethis-inline-share-buttons"></div> pay-online -->
                        </div>

                        <div class="col-md-5 grid-margin transparent">
                           <div class="card card-dark-blue bgblack1">
                              <div class="card-body">
                                 <p class="card-title text-white">Your Wallet Amount</p>
                                 <div class="row">
                                    <div class="col-md-12 text-white">
                                       <h3 class="dashboard-text"> INR <?= $db->get_institute_walletamount($student_id, '4') ?> </h3>
                                       <a href="#" class="btn btn-warning btn1" style="padding: 10px 10px !important;">Recharge Wallet</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>


   <div class="row">
      <div class="col-md-12 grid-margin transparent">
         <div class="row" style="height:100%">
            <?php
            $courses = $student->list_student_courses('', $student_id, '');
            if ($courses != '') {
               while ($courseData = $courses->fetch_assoc()) {
                  extract($courseData);
                  //print_r($courseData);
                  $COURSE_INFO       = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
                  //print_r($COURSE_INFO); 

                  $docData1 = $student->get_student_installment_details($student_id, $INSTITUTE_COURSE_ID, false);
                  $sr1 = 0;
                  $tbl1 = '';
                  if (!empty($docData1)) {

                     $del = '';

                     $tbl1 = '<table class="table table-bordered">';
                     $tbl1 .= '<tr>
				                      <th>Sr.No</th>
				                      <th>Installment Name</th>
                                      <th>Installment Amount</th>
                                      <th>Date</th>
				                   </tr>';
                     foreach ($docData1 as $key => $value) {
                        extract($value);
                        //print_r($value);
                        $tbl1 .= '<tr id="id' . $INSTALLMENT_ID . '">';
                        $tbl1 .= '<td>' . ++$sr1 . '</td>';
                        $tbl1 .= '<td>';
                        $tbl1 .= $INSTALLMENT_NAME;
                        $tbl1 .= '</td>';
                        $tbl1 .= '<td>';
                        $tbl1 .= $AMOUNT;
                        $tbl1 .= '</td>';
                        $tbl1 .= '<td>';
                        $tbl1 .= $DATE;
                        $tbl1 .= '</td>';
                        $tbl1 .= '</tr>';
                     }
                     $tbl1 .= '</table>';
                  }

                  if ($COURSE_INFO['COURSE_ID'] != '' && !empty($COURSE_INFO['COURSE_ID']) && $COURSE_INFO['COURSE_ID'] != '0') {
                     $COURSE_ID          = isset($COURSE_INFO['COURSE_ID']) ? $COURSE_INFO['COURSE_ID'] : '';
                     $COURSE_NAME       = isset($COURSE_INFO['COURSE_NAME']) ? $COURSE_INFO['COURSE_NAME'] : '';
                     $COURSE_DURATION    = isset($COURSE_INFO['COURSE_DURATION']) ? $COURSE_INFO['COURSE_DURATION'] : '';
                     $COURSE_FEES       = isset($COURSE_INFO['COURSE_FEES']) ? $COURSE_INFO['COURSE_FEES'] : '';
                     $COURSE_NAME_MODIFY       = isset($COURSE_INFO['COURSE_NAME_MODIFY']) ? $COURSE_INFO['COURSE_NAME_MODIFY'] : '';

                     $checkCertPrintAvilability = $access->getCertPrintAvailablity($COURSE_ID, $_SESSION['user_id'], $INSTITUTE_ID);
            ?>
                     <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card card-tale" style="background: #ececf0;">
                           <div class="card-body">
                              <p class="card-title" style="    color: #000;
                        font-size: 15px;
                        font-weight: 600;
                        text-align: left;
                        margin: 10px 0px;"><?= $COURSE_NAME_MODIFY ?></p>

                              <div class="">
                                 <p style="font-size: 14px; color: #000;text-align: left;">Joined On : <?= $ACCOUNT_REGISTERED_DATE ?></p>
                                 <p> <?php
                                       if ($EXAM_STATUS == 3) {
                                          echo '<p class="text-red" style="        font-size: 14px;
                                    color: #b91414;font-weight: 600;">Exam Status : Appeared</p>';


                                          if ($checkCertPrintAvilability == 1) {
                                             echo '<div style="
                                       width: -webkit-fill-available;
                                       bottom: 110px;">';
                                             echo '<a href="page.php?page=print-student-certificate&course=' . $COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                       border: 1px solid #e0b41e;    margin: 0px 10px;">My Certificate <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '<a href="page.php?page=print-student-marksheet&course=' . $COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                       border: 1px solid #e0b41e;    margin: 0px 10px;">My Marksheet <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '</div>';
                                          }
                                       }
                                       ?>
                                 </p>
                                 <div style="
                                 width: 90%;
                                 bottom: 0px;
                              ">
                                    <p> <a href="page.php?page=coursesDetails&id=<?= $INSTITUTE_COURSE_ID ?>" class="btn btn-info" style="padding: 15px; width: 100%; margin: 20px 0px;">Course info <i class="fa fa-arrow-circle-right"></i></a></p>
                                    <div style="margin-bottom:10px"> <?php echo $tbl1; ?> </div>
                                 </div>

                                 <?php
                                 //if the exam has been appeared already                        
                                 if ($EXAM_STATUS == 2) {
                                    //show button only if the student is applied for ONLINE exam
                                    if ($EXAM_TYPE == 1) {
                                 ?>
                                       <div class="row">
                                          <a href="#" class="col-md-5  btn btn-danger" data-toggle="modal" data-target="#appearexammodal<?= $STUD_COURSE_DETAIL_ID ?>" style="margin:2px">Appear Final Exam</a>
                                          <a href="#" class="col-md-5  btn btn-warning" id="exam_<?= $STUD_COURSE_DETAIL_ID ?>" data-toggle="modal" data-target="#demoexammodal<?= $STUD_COURSE_DETAIL_ID ?>" style="margin:2px">Demo Exam</a>
                                       </div>

                                       <!-- Appear for final exam Modal -->
                                       <div class="modal fade modal-danger" id="appearexammodal<?= $STUD_COURSE_DETAIL_ID ?>" tabindex="-1" role="dialog" aria-labelledby="appearexammodal<?= $STUD_COURSE_DETAIL_ID ?>Label">
                                          <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h4 class="modal-title" id="myModalLabel">Appearing For Final Exam</h4>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                   <h4>Notice:</h4>
                                                   <h5>You need EXAM OTP to appear for final exam. </h5>
                                                   <h5>Please contact to your institute for Exam OTP. </h5>
                                                </div>
                                                <div class="modal-footer">
                                                   <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                   <a href="<?= EXAM_PORTAL_URL ?>/index.php?student_course_id=<?= base64_encode($STUD_COURSE_DETAIL_ID) ?>&exam=final" class="btn btn-primary">Continue</a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <!-- Demo exam Modal -->
                                       <div class="modal fade modal modal-primary" id="demoexammodal<?= $STUD_COURSE_DETAIL_ID ?>" tabindex="-1" role="dialog" aria-labelledby="demoexammodal<?= $STUD_COURSE_DETAIL_ID ?>Label">
                                          <div class="modal-dialog" role="document">
                                             <div class="modal-content">
                                                <div class="modal-header">
                                                   <h4 class="modal-title" id="myModalLabel">Appearing For Demo Exam</h4>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                   <table class="table">
                                                      <tr>
                                                         <th>Total Demo Allowed</th>
                                                         <td>:</td>
                                                         <td><?= $INSTITUTE_DEMO_COUNT ?></td>
                                                      </tr>
                                                      <tr>
                                                         <th>Total Demo Attempted</th>
                                                         <td>:</td>
                                                         <td><?= $DEMO_COUNT ?></td>
                                                      </tr>
                                                      <tr>
                                                         <th>Total Demo Remaining</th>
                                                         <td>:</td>
                                                         <td><?php echo $remaining = $INSTITUTE_DEMO_COUNT - $DEMO_COUNT; ?></td>
                                                      </tr>
                                                   </table>
                                                   <?php if ($remaining <= 0) { ?>
                                                      <div class="callout callout-danger">
                                                         <h4>Sorry! No demo available!</h4>
                                                      </div>
                                                   <?php } ?>
                                                </div>
                                                <div class="modal-footer">
                                                   <input type="hidden" name="coursedetailid" value="<?= $STUD_COURSE_DETAIL_ID ?>" />
                                                   <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                   <?php if ($remaining > 0) { ?>
                                                      <a href="<?= EXAM_PORTAL_URL ?>/index.php?student_course_id=<?= base64_encode($STUD_COURSE_DETAIL_ID) ?>&exam=demo" class="btn btn-primary <?= ($remaining <= 0) ? 'disabled' : '' ?>">Continue</a>
                                                   <?php } ?>
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                       <?php }
                                    //show button if applied for offline exam
                                    if ($EXAM_TYPE == 2 && $EXAM_STATUS == 2) {

                                       // if secrete code is generated already
                                       if ($EXAM_SECRETE_CODE == '' || $EXAM_SECRETE_CODE == NULL) {
                                       ?>

                                          <a href="#" class="btn btn-flat bg-orange" data-toggle="modal" data-target="#appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>">Download Paper</a>

                                          <!-- Appear for final exam Modal -->
                                          <div class="modal fade modal-danger" id="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>" tabindex="-1" role="dialog" aria-labelledby="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>Label">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                      <h4 class="modal-title" id="myModalLabel">Appearing For Final Exam</h4>
                                                   </div>
                                                   <div class="modal-body">
                                                      <h4>Notice:</h4>
                                                      <ul>
                                                         <li>To appear for final exam you will need a Exam OTP.</li>
                                                         <li>To get the Exam OTP code you must contact your Institute.</li>
                                                         <li>After you recieve the Exam OTP, please enter the code and start your final exam.</li>
                                                         <li>Once you appeared for final exam, you can not appear for the demo exam.</li>
                                                         <li>For any queries, please contact your Institute.</li>
                                                      </ul>
                                                   </div>
                                                   <div class="modal-footer">
                                                      <button type="button" class="btn btn-outline" data-dismiss="modal">Cancel</button>
                                                      <a href="javascript:void(0)" id="gen<?= $STUD_COURSE_DETAIL_ID ?>" onclick="generateESC(this.id)" class="btn btn-outline">Generate Exam Code</a>
                                                      <?php
                                                      ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       <?php
                                       } else {
                                       ?>
                                          <a href="download-offline-papers" class="btn btn-flat bg-orange">Download Paper</a>
                                       <?php
                                       }
                                    }
                                    if ($EXAM_TYPE == 3) {
                                       ?>
                                       <p class="text-red" style="color: #000000;padding: 0px 25px;font-size: 16px;">Dear Student Please Contact Us For Your Practical Examination.</p>
                                 <?php
                                    }
                                 }
                                 ?>


                              </div>
                           </div>
                        </div>
                     </div>
                  <?php
                  }
                  if ($COURSE_INFO['MULTI_SUB_COURSE_ID'] != '' && !empty($COURSE_INFO['MULTI_SUB_COURSE_ID']) && $COURSE_INFO['MULTI_SUB_COURSE_ID'] != '0') {
                     $MULTI_SUB_COURSE_ID          = isset($COURSE_INFO['MULTI_SUB_COURSE_ID']) ? $COURSE_INFO['MULTI_SUB_COURSE_ID'] : '';
                     $MULTI_SUB_COURSE_NAME       = isset($COURSE_INFO['MULTI_SUB_COURSE_NAME']) ? $COURSE_INFO['MULTI_SUB_COURSE_NAME'] : '';
                     $MULTI_SUB_COURSE_DURATION    = isset($COURSE_INFO['MULTI_SUB_COURSE_DURATION']) ? $COURSE_INFO['MULTI_SUB_COURSE_DURATION'] : '';
                     $MULTI_SUB_COURSE_FEES       = isset($COURSE_INFO['MULTI_SUB_COURSE_FEES']) ? $COURSE_INFO['MULTI_SUB_COURSE_FEES'] : '';
                     $COURSE_NAME_MODIFY       = isset($COURSE_INFO['COURSE_NAME_MODIFY']) ? $COURSE_INFO['COURSE_NAME_MODIFY'] : '';

                     $checkCertPrintAvilability = $access->getCertPrintAvailablityMulti($MULTI_SUB_COURSE_ID, $_SESSION['user_id'], $INSTITUTE_ID);
                  ?>
                     <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card card-tale" style="background: #ececf0;">
                           <div class="card-body">
                              <p class="card-title" style="    color: #000;
                        font-size: 15px;
                        font-weight: 600;
                        text-align: left;
                        margin: 10px 0px;"><?= $COURSE_NAME_MODIFY ?></p>

                              <div class="">
                                 <p style="font-size: 14px; color: #000;text-align: left;">Joined On : <?= $ACCOUNT_REGISTERED_DATE ?></p>
                                 <p> <?php
                                       if ($EXAM_STATUS == 3) {
                                          echo '<p class="text-red" style="        font-size: 14px;
                                    color: #b91414;font-weight: 600;">Exam Status : Appeared</p>';


                                          if ($checkCertPrintAvilability == 1) {
                                             echo '<div style="
                                       width: -webkit-fill-available;
                                       bottom: 110px;">';
                                             echo '<a href="page.php?page=print-student-certificate&course_multi_sub=' . $MULTI_SUB_COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                       border: 1px solid #e0b41e;    margin: 0px 10px;">My Certificate <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '<a href="page.php?page=print-student-marksheet&course_multi_sub=' . $MULTI_SUB_COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                       border: 1px solid #e0b41e;    margin: 0px 10px;">My Marksheet <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '</div>';
                                          }
                                       }
                                       ?>
                                 </p>
                                 <div style="
                                 width: 90%;
                                 bottom: 0px;
                              ">
                                    <p> <a href="page.php?page=coursesDetails&id=<?= $INSTITUTE_COURSE_ID ?>" class="btn btn-info" style="padding: 15px; width: 100%; margin: 20px 0px;">Course info <i class="fa fa-arrow-circle-right"></i></a></p>
                                    <?php echo $tbl1; ?>
                                 </div>

                                 <?php
                                 //if the exam has been appeared already                        
                                 if ($EXAM_STATUS == 2) {
                                    //show button only if the student is applied for ONLINE exam
                                    if ($EXAM_TYPE == 1) {
                                       //multi subject online exam
                                 ?>

                                       <div class="row">

                                          <?php
                                          $docData1 = $coursemultisub->get_course_multi_sub1($MULTI_SUB_COURSE_ID, $inst_id, false);
                                          //print_r($docData1);
                                          $sr1 = 0;
                                          $tbl1 = '';
                                          if (!empty($docData1)) {

                                             $del = '';

                                             $tbl1 = '<table class="table table-bordered">';
                                             $tbl1 .= '<tr style="font-size: 12px;">
                                                    <th>Sr.No</th>
                                                    <th>Subject Name</th>
                                                    <th>Final Exam</th>
                                                    <th>Demo Exam</th>
                                           
                                                    </tr>';
                                             foreach ($docData1 as $key => $value) {
                                                extract($value);
                                                $remaining = $INSTITUTE_DEMO_COUNT - $DEMO_COUNT;
                                                //print_r($value); exit();

                                                $displayBtn = '';
                                                $checkExamDone = 0;
                                                $checkExamDone = $student->getExamDoneCheck($COURSE_SUBJECT_ID, $MULTI_SUB_COURSE_ID, $_SESSION['user_id'], $INSTITUTE_ID);
                                                if ($checkExamDone == 1) {
                                                   $displayBtn = " disabled-link";
                                                }
                                                $del = "<a href='javascript:void(0)' onclick='deleteSubjectMultiSub($COURSE_SUBJECT_ID,$MULTI_SUB_COURSE_ID)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";
                                                $tbl1 .= '<tr id="id' . $COURSE_SUBJECT_ID . '">';
                                                $tbl1 .= '<td>' . ++$sr1 . '</td>';
                                                $tbl1 .= '<td>';
                                                $tbl1 .= $COURSE_SUBJECT_NAME;
                                                $tbl1 .= '</td>';

                                                $tbl1 .= '<td>';
                                                $tbl1 .= ' <a href="#" class="btn btn-danger ' . $displayBtn . '" data-toggle="modal" data-target="#appearexammodal1' . $COURSE_SUBJECT_ID . '" style="margin:2px;padding: 10px;"  
                                                      >Final Exam</a>';
                                                $tbl1 .= '</td>';

                                                $tbl1 .= '<td>';
                                                $tbl1 .= '<a href="#" class="btn btn-warning ' . $displayBtn . '" id="exam_' . $COURSE_SUBJECT_ID . '" data-toggle="modal" data-target="#demoexammodal1' . $COURSE_SUBJECT_ID . '"  style="margin:2px; padding: 10px;" 
                                                      >Demo Exam</a>';
                                                $tbl1 .= '</td>';

                                                $tbl1 .= '</tr>';



                                                $tbl12 .= '<div class="modal fade modal-danger" id="appearexammodal1' . $COURSE_SUBJECT_ID . '" tabindex="-1" role="dialog" aria-labelledby="appearexammodal1' . $COURSE_SUBJECT_ID . ' Label">
                                                         <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                               <div class="modal-header">
                                                                  <h4 class="modal-title" id="myModalLabel">Appearing For Final Exam</h4>
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                               </div>
                                                               <div class="modal-body">
                                                                  <h4>Notice:</h4>
                                                                  <h5>You need EXAM OTP to appear for final exam. </h5>
                                                                  <h5>Please contact to your institute for Exam OTP. </h5>
                                                               </div>
                                                               <div class="modal-footer">				 
                                                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>					
                                                                  <a href="' . EXAM_PORTAL_URL . '/index.php?student_course_id=' . base64_encode($STUD_COURSE_DETAIL_ID) . '&exam=final&subject_id=' . $COURSE_SUBJECT_ID . '" class="btn btn-primary" >Continue</a>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                  
                                                    
                                                      <div class="modal fade modal modal-primary" id="demoexammodal1' . $COURSE_SUBJECT_ID . '" tabindex="-1" role="dialog" aria-labelledby="demoexammodal1' . $COURSE_SUBJECT_ID . ' Label">
                                                         <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                               <div class="modal-header">
                                                                  <h4 class="modal-title" id="myModalLabel">Appearing For Demo Exam</h4>
                                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                               </div>
                                                               <div class="modal-body">
                                                                 <p> Please click on continue to proceed for demo exam.</p>
                                                                 ';

                                                $tbl12 .= '</div>
                                                               <div class="modal-footer">
                                                                  <input type="hidden" name="coursedetailid" value="' . $STUD_COURSE_DETAIL_ID . '" />
                                                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';


                                                $tbl12 .= '<a href="' . EXAM_PORTAL_URL . '/index.php?student_course_id=' . base64_encode($STUD_COURSE_DETAIL_ID) . '&exam=demo&subject_id=' . $COURSE_SUBJECT_ID . '" class="btn btn-primary">Continue</a>';

                                                $tbl12 .= '</div>
                                                            </div>
                                                         </div>
                                                      </div> ';
                                             }
                                             $tbl1 .= '</table>';
                                          }

                                          echo $tbl1;
                                          echo $tbl12;
                                          ?>




                                       </div>





                                       <?php }
                                    //show button if applied for offline exam
                                    if ($EXAM_TYPE == 2 && $EXAM_STATUS == 2) {

                                       // if secrete code is generated already
                                       if ($EXAM_SECRETE_CODE == '' || $EXAM_SECRETE_CODE == NULL) {
                                       ?>

                                          <a href="#" class="btn btn-flat bg-orange" data-toggle="modal" data-target="#appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>">Download Paper</a>

                                          <!-- Appear for final exam Modal -->
                                          <div class="modal fade modal-danger" id="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>" tabindex="-1" role="dialog" aria-labelledby="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>Label">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                      <h4 class="modal-title" id="myModalLabel">Appearing For Final Exam</h4>
                                                   </div>
                                                   <div class="modal-body">
                                                      <h4>Notice:</h4>
                                                      <ul>
                                                         <li>To appear for final exam you will need a Exam OTP.</li>
                                                         <li>To get the Exam OTP code you must contact your Institute.</li>
                                                         <li>After you recieve the Exam OTP, please enter the code and start your final exam.</li>
                                                         <li>Once you appeared for final exam, you can not appear for the demo exam.</li>
                                                         <li>For any queries, please contact your Institute.</li>
                                                      </ul>
                                                   </div>
                                                   <div class="modal-footer">
                                                      <button type="button" class="btn btn-outline" data-dismiss="modal">Cancel</button>
                                                      <a href="javascript:void(0)" id="gen<?= $STUD_COURSE_DETAIL_ID ?>" onclick="generateESC(this.id)" class="btn btn-outline">Generate Exam Code</a>
                                                      <?php
                                                      ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       <?php
                                       } else {
                                       ?>
                                          <a href="download-offline-papers" class="btn btn-flat bg-orange">Download Paper</a>
                                       <?php
                                       }
                                    }
                                    if ($EXAM_TYPE == 3) {
                                       ?>
                                       <p class="text-red" style="color: #000000;padding: 0px 25px;font-size: 16px;">Dear Student Please Contact Us For Your Practical Examination.</p>
                                 <?php
                                    }
                                 }
                                 ?>


                              </div>
                           </div>
                        </div>
                     </div>
                  <?php
                  }
                  if ($COURSE_INFO['TYPING_COURSE_ID'] != '' && !empty($COURSE_INFO['TYPING_COURSE_ID']) && $COURSE_INFO['TYPING_COURSE_ID'] != '0') {
                     $TYPING_COURSE_ID          = isset($COURSE_INFO['TYPING_COURSE_ID']) ? $COURSE_INFO['TYPING_COURSE_ID'] : '';
                     $TYPING_COURSE_NAME       = isset($COURSE_INFO['TYPING_COURSE_NAME']) ? $COURSE_INFO['TYPING_COURSE_NAME'] : '';
                     $TYPING_COURSE_DURATION    = isset($COURSE_INFO['TYPING_COURSE_DURATION']) ? $COURSE_INFO['TYPING_COURSE_DURATION'] : '';
                     $TYPING_COURSE_FEES       = isset($COURSE_INFO['TYPING_COURSE_FEES']) ? $COURSE_INFO['TYPING_COURSE_FEES'] : '';
                     $COURSE_NAME_MODIFY       = isset($COURSE_INFO['COURSE_NAME_MODIFY']) ? $COURSE_INFO['COURSE_NAME_MODIFY'] : '';

                     $checkCertPrintAvilability = $access->getCertPrintAvailablityTyping($TYPING_COURSE_ID, $_SESSION['user_id'], $INSTITUTE_ID);
                  ?>
                     <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card card-tale" style="background: #ececf0;">
                           <div class="card-body">
                              <p class="card-title" style="    color: #000;
                           font-size: 15px;
                           font-weight: 600;
                           text-align: left;
                           margin: 10px 0px;"><?= $COURSE_NAME_MODIFY ?></p>

                              <div class="">
                                 <p style="font-size: 14px; color: #000;text-align: left;">Joined On : <?= $ACCOUNT_REGISTERED_DATE ?></p>
                                 <p> <?php
                                       if ($EXAM_STATUS == 3) {
                                          echo '<p class="text-red" style="        font-size: 14px;
                                       color: #b91414;font-weight: 600;">Exam Status : Appeared</p>';


                                          if ($checkCertPrintAvilability == 1) {
                                             echo '<div style="
                                          width: -webkit-fill-available;
                                          bottom: 110px;">';
                                             echo '<a href="page.php?page=print-student-certificate&course_typing=' . $TYPING_COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                          border: 1px solid #e0b41e;    margin: 0px 10px;">My Certificate <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '<a href="page.php?page=print-student-marksheet&course_typing=' . $TYPING_COURSE_ID . '" target="_blank" class="col-md-5 btn btn-primary" style="background-color: #e0b41e;
                                          border: 1px solid #e0b41e;    margin: 0px 10px;">My Marksheet <i class="fa fa-arrow-circle-right"></i></a>';
                                             echo '</div>';
                                          }
                                       }
                                       ?>
                                 </p>
                                 <div style="
                                    width: 90%;
                                    bottom: 0px;
                                 ">
                                    <p> <a href="page.php?page=coursesDetails&id=<?= $INSTITUTE_COURSE_ID ?>" class="btn btn-info" style="padding: 15px; width: 100%; margin: 20px 0px;">Course info <i class="fa fa-arrow-circle-right"></i></a></p>
                                    <?php echo $tbl1; ?>
                                 </div>

                                 <?php
                                 //if the exam has been appeared already                        
                                 if ($EXAM_STATUS == 2) {
                                    //show button only if the student is applied for ONLINE exam
                                    if ($EXAM_TYPE == 1) {
                                       //multi subject online exam
                                 ?>

                                       <div class="row">
                                          <p style="color: #ff0000;padding: 0px 25px;font-size: 16px;"><strong> Currently online exam for this course is not active. Please contact your institute for further details.</strong></p>


                                       </div>




                                       <?php }
                                    //show button if applied for offline exam
                                    if ($EXAM_TYPE == 2 && $EXAM_STATUS == 2) {

                                       // if secrete code is generated already
                                       if ($EXAM_SECRETE_CODE == '' || $EXAM_SECRETE_CODE == NULL) {
                                       ?>

                                          <a href="#" class="btn btn-flat bg-orange" data-toggle="modal" data-target="#appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>">Download Paper</a>

                                          <!-- Appear for final exam Modal -->
                                          <div class="modal fade modal-danger" id="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>" tabindex="-1" role="dialog" aria-labelledby="appearofflineexammodal<?= $STUD_COURSE_DETAIL_ID ?>Label">
                                             <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                   <div class="modal-header">
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                      <h4 class="modal-title" id="myModalLabel">Appearing For Final Exam</h4>
                                                   </div>
                                                   <div class="modal-body">
                                                      <h4>Notice:</h4>
                                                      <ul>
                                                         <li>To appear for final exam you will need a Exam OTP.</li>
                                                         <li>To get the Exam OTP code you must contact your Institute.</li>
                                                         <li>After you recieve the Exam OTP, please enter the code and start your final exam.</li>
                                                         <li>Once you appeared for final exam, you can not appear for the demo exam.</li>
                                                         <li>For any queries, please contact your Institute.</li>
                                                      </ul>
                                                   </div>
                                                   <div class="modal-footer">
                                                      <button type="button" class="btn btn-outline" data-dismiss="modal">Cancel</button>
                                                      <a href="javascript:void(0)" id="gen<?= $STUD_COURSE_DETAIL_ID ?>" onclick="generateESC(this.id)" class="btn btn-outline">Generate Exam Code</a>
                                                      <?php
                                                      ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       <?php
                                       } else {
                                       ?>
                                          <a href="download-offline-papers" class="btn btn-flat bg-orange">Download Paper</a>
                                       <?php
                                       }
                                    }
                                    if ($EXAM_TYPE == 3) {
                                       ?>
                                       <p class="text-red" style="color: #000000;padding: 0px 25px;font-size: 16px;">Dear Student Please Contact Us For Your Practical Examination.</p>
                                 <?php
                                    }
                                 }
                                 ?>


                              </div>
                           </div>
                        </div>
                     </div>
            <?php
                  }
               }
            }
            ?>

         </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
         <div class="card">
            <div class="card-people mt-auto">
               <img src="resources/images/dashboard/institute_homepage.jpg" alt="people">
            </div>
         </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
         <div class="col-md-6 grid-margin transparent">
            <div class="card card-dark-blue bgcrimson">
               <div class="card-body">
                  <p class="card-title text-white">Balance Fees</p>
                  <div class="row">
                     <div class="col-md-12 text-white">
                        <?php
                        $ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;
                        $ALL_COURSE_FEES = $student->total_coursefess_student($student_id);
                        $TOTAL_FEES_PAID = $student->total_paidfess_student($student_id);
                        $TOTAL_FEES_BALANCE = $ALL_COURSE_FEES - $TOTAL_FEES_PAID;

                        ?>
                        <h5 class="dashboard-text">Paid Fees : <?= $TOTAL_FEES_PAID ?></h5>
                        <h5 class="dashboard-text">Balance Fees : <?= $TOTAL_FEES_BALANCE ?></h5>
                        <h5 class="dashboard-text">Total Fees : <?= $ALL_COURSE_FEES ?></h5>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 grid-margin transparent">
            <div class="card card-dark-blue bgblack1">
               <div class="card-body">
                  <p class="card-title text-white">Refferal Amount</p>
                  <div class="row">
                     <div class="col-md-12 text-white">
                        <h3 class="dashboard-text"> INR <?= $db->get_institute_walletamount($student_id, '4') ?> </h3>
                        <a href="#" class="btn btn-warning btn1" style="padding: 10px 10px !important;">View History</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">

      <div class="col-md-6 grid-margin transparent">
         <div class="card">
            <div class="card-body">
               <h4 class="card-title">Today's Birthday List
               </h4>
               <ul class="icon-data-list">
                  <?php
                  //date('m')date('d')
                  $month       = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
                  $day       = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');
                  include('include/classes/admin.class.php');
                  $admin = new admin();
                  $cond = '';
                  $res = $admin->get_birth_day_report($month, $day, " AND A.INSTITUTE_ID = $user_id ORDER BY DAY(A.STUDENT_DOB) ASC");
                  if ($res != '') {
                     $srno = 1;

                     while ($data = $res->fetch_assoc()) {
                        //extract($data);
                        $STUDENT_ID        = $data['STUDENT_ID'];
                        $STUDENT_FNAME    = $data['STUDENT_FNAME'];
                        $STUDENT_MNAME    = $data['STUDENT_MNAME'];
                        $STUDENT_LNAME    = $data['STUDENT_LNAME'];
                        $STUDENT_PHOTO    = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $data['STUDENT_PHOTO'];
                        $STUDENT_MOBILE    = $data['STUDENT_MOBILE'];
                        $STUDENT_EMAIL    = $data['STUDENT_EMAIL'];
                        $DOB            = $data['DOB_FORMATTED'];
                        $DOB_DAY         = $data['DOB_DAY'];
                        $DOB_MONTH         = $data['DOB_MONTH'];
                        $today_month      = date('m');
                        $today_day        = date('d');
                  ?>
                        <li>
                           <div class="d-flex">
                              <img class="photo1" src="<?= $STUDENT_PHOTO ?>" alt="user">
                              <div>
                                 <p class="title2"><?= $STUDENT_FNAME ?> <?= $STUDENT_LNAME ?></p>

                              </div>
                           </div>
                        </li>
                  <?php
                        $srno++;
                     }
                  }
                  ?>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Share Details</h5>
            <button class="btn btn-warning btn" onclick="copyContent()">Copy!</button>
         </div>
         <div class="modal-body">
            <p id="myText"> Hey Friends, - Myself <?= $ABBREVIATION ?>. <?= $STUDENT_FULLNAME ?> - I have got something awesome to share! I have been studying with <?= $inst_name ?> and I think you will love it too. Use my referral code: <?= $STUDENT_CODE ?> when you sign up. Do not miss out on the benefits. Join me at <?= HTTP_HOST ?> . – Thankyou</p>
         </div>
      </div>
   </div>
</div>

<script>
   let text = document.getElementById('myText').innerHTML;
   const copyContent = async () => {
      try {
         await navigator.clipboard.writeText(text);
         document.getElementById("myText").style.backgroundColor = "#fffb98";
         document.getElementById("myText").style.padding = "5px";
      } catch (err) {
         console.error('Failed to copy: ', err);
      }
   }
</script>