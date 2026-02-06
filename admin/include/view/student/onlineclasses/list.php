    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
          <div class="card-body">
          <h4 class="card-title">List Online Classes
          </h4>  
            <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="row">
                    
                    <div class="col-md-12 col-xl-12">
                      <div class="row col-md-12">
                          <?php   
                              //print_r($_SESSION['user_id']);                   
                              $inst_id = $db->get_student_institute_id($_SESSION['user_id']);
                              
                              include_once('include/classes/student.class.php');
                              $student = new student();
                              $output =array();
                              $course = array();
                              $course =  $student->get_institutecourse_id_onlineclasses($_SESSION['user_id']);                               
                              if($course!='')
                              {
                                while($resdata = $course->fetch_assoc())
                                {                                  
                                  $course_data = $resdata['INSTITUTE_COURSE_ID'];
                                  array_push($output,$course_data);                                 
                                }
                              }
                              $output = implode(",",$output);

                              include_once('include/classes/tools.class.php');
                              $tools = new tools();
                              $date = date("Y-m-d");
                              $cond = " AND NOW() <= expirydate AND inst_id='$inst_id' AND  course_id IN ($output)";
                              $res = $tools->list_onlineclasses_details('',$cond);
                              if($res!='')
                              {
                                $srno=1;
                                while($data = $res->fetch_assoc())
                                {
                                  extract($data);
                                  $course_name = $db->get_inst_course_name($course_id);
                          ?>
                          <div class="col-md-4 m-1 box-onlineclass">
                            <div class="ml-xl-4 mt-3">
                              <p><?= $course_name ?></p>  
                              <p class="card-title title1"><?= $title ?></p>                
                              <p class="mb-2 text1"><?= $description ?></p>
                              
                              <p class="mb-xl-4 text-primary link1">
                                Online Link :
                                <a href="<?= $link ?>" target="_blank"> <br/><?= $link ?> </a>
                              </p>

                              <p class="mb-2 text1">Expiry Date : <?= $expirydate = date("d-m-Y", strtotime($expirydate)); ?></p>

                            </div>                               
                          </div>
                          <?php              
                                $srno++;
                              }
                            }      
                          ?>  
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


 

         