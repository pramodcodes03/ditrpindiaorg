<!doctype html>
<html lang="en">
<?php 
$page = isset($_GET['pg'])?$_GET['pg']:'home';

include('include/common/html_header.php'); 

?>

<?php
$data=array();
//print_r($_GET); exit();
$action= isset($_GET['verify_student'])?$_GET['verify_student']:'';
$success='';
if($action!='')
{
    $success=false;
    
    $student_code =$db->test(isset($_GET['code'])?$_GET['code']:'');
    if( $student_code!=''){
	$sql = "SELECT *,get_institute_email(A.INSTITUTE_ID) as institute_email,get_institute_mobile(A.INSTITUTE_ID) as institutemobile,  get_institute_address(A.INSTITUTE_ID) as instituteaddress, get_institute_city(A.INSTITUTE_ID) as institutecity,get_institute_state(A.INSTITUTE_ID) as institutestate,get_stud_photo(A.STUDENT_ID) AS STUD_PHOTO,get_course_title_modify(A.COURSE_ID) AS COURSE_NAME,get_course_multi_sub_title_modify(A.MULTI_SUB_COURSE_ID) AS COURSE_NAME_MULTI_SUB, A.COURSE_NAME AS COURSE_NAME_TYPING, DATE_FORMAT(A.ISSUE_DATE,'%d/%m/%Y') AS ISSUE_DATE_F FROM certificates_details A  WHERE A.CERTIFICATE_NO='$student_code' AND A.DELETE_FLAG=0 ORDER BY A.CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	$res = $db->execQuery($sql);
	
	if($res && $res->num_rows>0)
	{
	    $success=true;
		while($data = $res->fetch_assoc())
		{
		    extract($data);
		    //print_r($STUD_PHOTO); exit();
			$photo = HTTP_HOST."/resources/img/teacher_2_small.jpg";
			if($STUD_PHOTO!='')
				$photo = "uploads/student/".$STUDENT_ID.'/'.$STUD_PHOTO;
         
         $course_duration = $db->get_course_duration($COURSE_ID);
         $course_duration_multisub = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID); 
         $course_duration_typing = $db->get_course_duration_typing($TYPING_COURSE_ID);     
				
			/*if($STUDENT_PHOTO!='')
				$photo = HTTP_HOST."/uploads/certificates/photos/$STUDENT_PHOTO";*/
         
          	  $cour_dur = '';
              if($course_duration != ''){
              	$cour_dur = $course_duration;
              }
              if($course_duration_multisub != ''){
              	$cour_dur = $course_duration_multisub;
              }  
              if($course_duration_typing != ''){
              	$cour_dur = $course_duration_typing;
              }   
              $date = strtotime($ISSUE_DATE);
              $new_date = strtotime('- '.$cour_dur, $date);
              $start_date = strtotime('+ 1 day ',$new_date);
              $start_course_date = date('d M Y', $start_date); 
              $end_course_date = date('d M Y', $date); 
	
		}
	}
    }
}

?>

<div id="rs-events" class="rs-events sec-spacer pt-70">
			<div class="container">				
                <div class="row">
                    <div class="col-sm-12 fverify">
                        <?php
                            if($success==true)
                            {
                                ?>
                            <!-- <h4 class="title-default-left title-bar-high">Student Certificate Details</h4>
                                        
                                <div class="table-responsive">

                                            <table class="table table-bordered table-responsive">
                                                <tr>
                                                    <th>Photo</th>
                                                    <td><img src="<?= $photo ?>" alt="Student Photo" style="max-height:200px;margin:auto;" class="img thumbnail styled"></td>   
                                                </tr>
                                                <tr>
                                                    <th>Certificate No.</th>
                                                 
                                                    <td><?= $CERTIFICATE_NO ?></td>
                                                
                                                </tr>
                                                <tr>
                                                    <th>Certificate Issue Date</th>
                                                  
                                                    <td><?php echo $ISSUE_DATE = date('d M Y', strtotime($ISSUE_DATE));  ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Name of Student </th>
                                                  
                                                    <td><?= $STUDENT_NAME ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Course Name </th>
                                                   
                                                    <td><?= $COURSE_NAME ?><?= $COURSE_NAME_MULTI_SUB ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Marks Obtained</th>
                                                   
                                                    <td><?= $MARKS_PER ?> % </td>
                                                </tr><tr>
                                                    <th>Grade Secured</th>
                                                
                                                    <td><?= ($GRADE!='')?$GRADE:'-' ?></td>
                                                </tr>
                                            
                                                    <th>Name of Institution</th>
                                                   
                                                    <td><?= $INSTITUTE_NAME ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Address</th>
                                                   
                                                    <td style="text-transform:capitalize;"><?= $instituteaddress.' , '.$institutecity.' , '.$institutestate ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Email</th>
                                                   
                                                    <td><?= $institute_email ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Contact Number</th>
                                                   
                                                    <td><?= $institutemobile ?></td>
                                                </tr> 
                                    </table>
                                </div> -->
                                <div class="col-md-12" style="background-color: #f1f1f1;">
                                <div class="col-md-12" style="background-color: #ffffff; padding: 10px 10px;     margin: 10px;">
                                    <div class="col-md-4">                                    
                                        <img src="<?= $photo ?>" alt="Student Photo" style="max-height:200px;" class="img thumbnail styled">
                                    </div>
                                    <div class="col-md-8">
                                        <h4 style="margin: 10px 0px; line-height: 25px;color: #d81111;">Name Of Student : </h4>
                                        <h4 style="margin: 0px; line-height: 25px;font-size: 15px;"><?= $STUDENT_NAME ?> </h4>
                                    </div>
                                </div>
                         

                                <div class="col-lg-12 col-md-6 grid-item" style="background-color: #ffffff;
    padding: 10px 10px;    margin: 10px;
   ">
                                    <div class="course-item">                                   
                                        <div class="course-body">
                                            <div class="course-desc">  
                                                <h2 style="color: #fff;
    background-color: #000;
    padding: 5px;
    font-size: 18px;
    border-radius: 10px;
    width: 50%;"> Course Name </h2>                                         
                                                <div class="columns">
                                                    <ul class="price">                                                       
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;"><?= $COURSE_NAME ?><?= $COURSE_NAME_MULTI_SUB ?><?php if($TYPING_COURSE_ID!='' && $TYPING_COURSE_ID!=0){ echo $COURSE_NAME_TYPING; }?></h4></li>
                                                          
                                                    </ul>
                                                </div>  
                                                
                                                <h2 style="color: #fff;
    background-color: #000;
    padding: 5px;
    font-size: 18px;
    border-radius: 10px;
    width: 50%;"> Course Details </h2>                                         
                                                <div class="columns">
                                                    <ul class="price">                                                       
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Certificate Number : <?= $CERTIFICATE_NO ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Course Duration : <?= $course_duration ?><?= $course_duration_multisub ?><?= $course_duration_typing ?></h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Certificate Issue : <?= $ISSUE_DATE_F ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Certificate Period : <?= $start_course_date ?> To <?= $end_course_date ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Marks Obtained : <?= $MARKS_PER ?> % </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Grade Secured : <?= $GRADE ?> </h4></li>
                                                          
                                                    </ul>
                                                </div>  

                                            </div>
                                        </div>                                   
                                    </div>                      
                                </div>

                                
                                <div class="col-lg-12 col-md-6 grid-item" style="background-color: #ffffff;
    padding: 10px 10px;
    margin: 5px;">
                                    <div class="course-item">                                   
                                        <div class="course-body">
                                            <div class="course-desc">    
                                                <h2 style="color: #fff;
    background-color: #f00;
    padding: 5px;
    font-size: 18px;
    border-radius: 10px;
    width: 100%;"> Authorized Center & Controller of Examination </h2>                                           
                                                <div class="columns">
                                                    <h3 style="color: #fff;
    background-color: #000;
    padding: 5px;
    font-size: 18px;
    border-radius: 10px;
    width: 50%;">Institute Details </h3>
                                                    <ul class="price">                                                      
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Name Of Institute : <br/><br/><?= $INSTITUTE_NAME ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Institute Email : <br/><br/><?= $institute_email ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Institute Contact :<br/><br/> <?= $institutemobile ?> </h4></li>
                                                        <li class="header"><h4 class="course-value" style="font-size: 15px;">Institute Address : <br/><br/><?= $instituteaddress ?> </h4></li>
                                                          
                                                    </ul>
                                                </div>                                               
                                            </div>
                                        </div>                                   
                                    </div>                      
                                </div>
                                </div>
                                <?php }elseif($success==false){ ?>
                                    <div class="alert alert-danger">
                                    <p><strong>Sorry! </strong>
                                    The entered certificate number  not found!
                                    </p>
                                    </div>
                                <?php } ?>
                       
                    </div>
                </div>
			</div>
        </div>

<script type="text/javascript">
	var myForm = document.getElementById('certVerifyForm');
    myForm.onsubmit = function() {
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
    this.target = 'Popup_Window';
};
</script>