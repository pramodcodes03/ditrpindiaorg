<?php 
    include_once('include/classes/course.class.php');
    $course = new course();
    include_once('include/classes/coursemultisub.class.php');
    $coursemultisub = new coursemultisub();
    include_once('include/classes/student.class.php');
    $student = new student();


	$student_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:''; 
    $inst_course_id = isset($_GET['id'])?$_GET['id']:'';

    $sql = "SELECT A.INSTITUTE_COURSE_ID,A.INSTITUTE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID,A.COURSE_FEES,A.MINIMUM_FEES FROM institute_courses A WHERE A.INSTITUTE_COURSE_ID = '$inst_course_id' AND A.DELETE_FLAG=0";           
    $ex = $db->execQuery($sql);
    if($ex && $ex->num_rows>0)
    {
        while($data = $ex->fetch_assoc())
        {        
            //print_r($data);  exit();        
            $COURSE_ID 			 = $data['COURSE_ID'];
            $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
            $TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];
            
            $INSTITUTE_ID 			 = $data['INSTITUTE_ID'];
            
            $videoPlanActive = 0;
            $todayDate = date("Y-m-d");
           $sql123 = "SELECT A.video_plan, A.video_date FROM institute_details A WHERE A.INSTITUTE_ID = '$INSTITUTE_ID' AND A.DELETE_FLAG=0";           
            $ex123 = $db->execQuery($sql123);
            if($ex123 && $ex123->num_rows>0)
            {
                while($data123 = $ex123->fetch_assoc())
                { 
                    $video_plan = $data123['video_plan'];
                    $video_date = $data123['video_date'];
                    
                    if($video_plan == '1' && $video_date >= $todayDate){
                        $videoPlanActive = 1;
                    }
                }
            }
            
            $COURSE_FEES 	 = $data['COURSE_FEES'];
            $MINIMUM_FEES 	 = $data['MINIMUM_FEES'];
            
            if($COURSE_ID!='' && !empty($COURSE_ID) && $COURSE_ID!='0'){													
                $course_data = $db->get_course_detail($COURSE_ID);
                $course_name 	= $course_data['COURSE_NAME_MODIFY'];
                $course_id 	= $course_data['COURSE_ID'];

                $course_code	    = $course_data['COURSE_CODE'];
                $course_duration 	= $course_data['COURSE_DURATION'];
                $course_details 	= $course_data['COURSE_DETAILS'];
                $course_eligibility	= $course_data['COURSE_ELIGIBILITY'];
                $course_fees	    = $COURSE_FEES;
                $course_mrp	        = $course_data['COURSE_MRP'];
                $course_minamount 	= $MINIMUM_FEES;
                $course_image	    = $course_data['COURSE_IMAGE'];

                $path = COURSE_MATERIAL_PATH.'/'.$COURSE_ID.'/'.$course_image;

                $video1	    = $course_data['VIDEO1'];
                $video2	    = $course_data['VIDEO2'];
                
            }

            if($MULTI_SUB_COURSE_ID!='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!='0')
            {											
                $course_data = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
                $course_name 	= $course_data['COURSE_NAME_MODIFY'];
                $course_id 	= $course_data['MULTI_SUB_COURSE_ID'];
                $course_code	    = $course_data['MULTI_SUB_COURSE_CODE'];
                $course_duration 	= $course_data['MULTI_SUB_COURSE_DURATION'];
                $course_details 	= $course_data['MULTI_SUB_COURSE_DETAILS'];
                $course_eligibility	= $course_data['MULTI_SUB_COURSE_ELIGIBILITY'];
                 $course_fees	    = $COURSE_FEES;
                $course_mrp	        = $course_data['MULTI_SUB_COURSE_MRP'];
                $course_minamount 	 = $data['MINIMUM_FEES'];
                $course_image	    = $course_data['MULTI_SUB_COURSE_IMAGE'];

                $path = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$MULTI_SUB_COURSE_ID.'/'.$course_image;

                $video1	    = $course_data['VIDEO1'];
                $video2	    = $course_data['VIDEO2'];
               
            } 
            
            if($TYPING_COURSE_ID!='' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID!='0')
            {											
                $course_data = $db->get_course_detail_typing($TYPING_COURSE_ID);
                $course_name 	= $course_data['COURSE_NAME_MODIFY'];
                $course_id 	= $course_data['TYPING_COURSE_ID'];
                $course_code	    = $course_data['TYPING_COURSE_CODE'];
                $course_duration 	= $course_data['TYPING_COURSE_DURATION'];
                $course_details 	= $course_data['TYPING_COURSE_DETAILS'];
                $course_eligibility	= $course_data['TYPING_COURSE_ELIGIBILITY'];
                 $course_fees	    = $COURSE_FEES;
                $course_mrp	        = $course_data['TYPING_COURSE_MRP'];
                $course_minamount 	 = $data['MINIMUM_FEES'];
                $course_image	    = $course_data['TYPING_COURSE_IMAGE'];

                $path = COURSE_WITH_TYPING_MATERIAL_PATH.'/'.$TYPING_COURSE_ID.'/'.$course_image;
               
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-people mt-auto">
                            <img src="<?= $path ?>" alt="people">                 
                        </div>
                    </div> 

                    <div class="col-md-6 boxMain">    
                        <div class="rightBox">
                            <h5> Course Duration : <?= $course_duration ?></h5>
                            <h5> Course Fees : Rs. <?= $course_fees ?></h5>     
                            <h5>Minimum Amount To Pay : Rs. <?= $course_minamount ?></h5>    
                            
                            <h5>Payment Installment Details</h5>
                            <?php						
							    echo $docData = $student->get_student_installments($student_id,$inst_course_id, true);
						    ?> 

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

                <div class="col-md-12">
                    <?php  if(!empty($COURSE_ID) && $videoPlanActive == '1'){ ?>
                  
                        <div class="detailBox" style="margin:10px 0px;">
                            <h4> Course Material PDF's : </h4>
                            <div class="row">
                                <?php
                                    $doc_pdf = $course->get_course_docs_all($COURSE_ID, false);                                    
                                    foreach($doc_pdf as $pdf){
                                    $fileLink = COURSE_MATERIAL_PATH.'/'.$pdf['course_id'].'/'.$pdf['file_name'];                               
                                ?> 
                            
                                <div class="col-md-2">
                                    <h5 class="title1"> <?= $pdf['file_label']; ?> </h5>
                                    <a href="<?= $fileLink ?>" target="_blank"><img src="resources/images/pdf_icon.png" alt="pdf-icon" style="width:100px"/></a>
                                </div>

                                <?php  } ?>  
                                    </div>
                        </div>
                        <div class="detailBox" style="margin:10px 0px;">
                            <h4> Course Material Videos : </h4>
                            <p> For Video Please Download App : <a href="https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share" target="_blank"> https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share </a> </p>                
                                                
                           
                        
                    </div>
                    <?php  } ?>

                    <?php  if($MULTI_SUB_COURSE_ID!='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!='0' && $videoPlanActive == '1'){ ?>
                    <div class="row">
                        <div class="col-md-12" style="margin:10px 0px;">
                            <h4> Course Material PDF's : </h4>
                            <div class="row">
                            <?php
                                $doc_pdf = $coursemultisub->get_course_multi_sub_docs_all($MULTI_SUB_COURSE_ID, false); 
                                foreach($doc_pdf as $pdf){
                                    $fileLink = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$MULTI_SUB_COURSE_ID.'/'.$pdf['file_name'];['file_name'];                               
                                ?> 
                            
                                <div class="col-md-2">
                                    <h5 class="title1"> <?= $pdf['file_label']; ?> </h5>
                                    <a href="<?= $fileLink ?>" target="_blank"><img src="resources/images/pdf_icon.png" alt="pdf-icon" style="width:100px"/></a>
                                </div>

                            <?php  } ?>  
                            </div>
                        </div>
                        <div class="col-md-12" style="margin:10px 0px;">
                            <h4> Course Material Videos : </h4>
                              <p> For Video Please Download App : <a href="https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share" target="_blank"> https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share </a> </p>                
                        </div>
                    </div>
                    <?php } ?>
                </div>
              

            </div>			
		</div>
		</div>
	</div>
</div>