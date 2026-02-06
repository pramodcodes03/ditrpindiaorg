
    <?php
    $student_id = $db->test(isset($_REQUEST['student_id'])?$_REQUEST['student_id']:'');
    $course_id = $db->test(isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'');
    $res = $student->list_student_direct_admission("$student_id", '', '', '');
    if ($res != '') {
        $srno = 1;
        //echo "<pre>";
        while ($data = $res->fetch_assoc()) {
            //print_r($data);
            extract($data);
            if($STUD_PHOTO!=''){
              $STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;}else{$STUD_PHOTO = HTTP_HOST.'uploads/default_user.png';}

        }
    }
    ?>
    <div class="container">
    <div class="main-body">
    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="<?=  $STUD_PHOTO ?>" alt="<?= $STUDENT_FNAME ?>" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4><?php echo $STUDENT_FULLNAME; ?></h4>
                      <p class="text-secondary mb-1"><?php echo $STUDENT_EMAIL; ?></p>
                      <!-- <p class="text-muted font-size-sm"><?php echo $STUDENT_MOBILE.(!empty($STUDENT_MOBILE2)?' / '.$STUDENT_MOBILE2:''); ?></p> -->
                     
                    </div>
                  </div>
                </div>
              </div>
            <!-- <div class="card mt-3">
                <div class="card">
                    <div class="card-body">
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
                        
                    </div>
                </div>
            </div> -->
            </div>
            
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Roll Number</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php 
                     echo $ROLL_NUMBER;                     
                     ?>
                    </div>
                    </div>
                    <hr>
                  
                    <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php 
                     echo $ABBREVIATION.' '.$STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME;                     
                     ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0"><?php echo $SONOF;?></h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $STUDENT_MNAME; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Mother Name</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $STUDENT_MOTHERNAME; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Mobile</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $STUDENT_MOBILE.(!empty($STUDENT_MOBILE2)?' / '.$STUDENT_MOBILE2:''); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo $STUDENT_EMAIL; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Date of Birth</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo $STUD_DOB_FORMATED; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Gender</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo strtoupper($STUDENT_GENDER); ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo $STUDENT_PER_ADD; ?><br />
                    City:  <?php echo $STUDENT_CITY; ?><br />
                    Pincode: <?php echo $STUDENT_PINCODE; ?>
                    </div>
                  </div>
                  <hr>
                  <!-- <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Display Admission Form / Id Card / Fees Receipt</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                     <?php echo $DISPLAY_FORM_STATUS=='1'?'Yes':'No'; ?>
                    </div>
                  </div>
                  <hr> -->
                </div>
              </div>
            </div>
          </div>

        </div>
    </div>
    
