<?php
ob_clean();
ob_start();

$user_id= isset($_REQUEST['id'])?$_REQUEST['id']:'';	

if($user_id!='' && !empty($user_id))
{
	date_default_timezone_set("Asia/Kolkata");
  
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => sys_get_temp_dir() . '/mpdf']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
    
	include_once('include/classes/student.class.php');
	$student 	= new  student();	
	$html='';	
	$res = $student->list_student_direct_admission($user_id,'','','');	
	$data 	= $res->fetch_assoc();
    
	extract($data);
	//print_r($data);exit();
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;
	
	
	$html = 
	'<style>
	body {padding:0;font-family: sans-serif; font-size: 9pt;position:absolute;z-index:0;top:0px;}
    .name{position:absolute;top:9%;left:45%;width:45%; font-size:12pt;line-height:24px}
	.studname{position:absolute;top:18.5%;left:43%;width:45%; font-size: 9pt;}
	.dob{position:absolute;top:20.5%;left:43%; width:35%; font-size: 9pt;}	
    .gender{position:absolute;top:22.5%;left:43%; width:35%; font-size: 9pt;}	
    .address{position:absolute;top:24.5%;left:43%; width:45%; font-size: 9pt;}	
    
    .mobile{position:absolute;top:37.5%;left:13%; width:35%; font-size: 9pt;}	
    .email{position:absolute;top:41%;left:13%; width:35%; font-size: 9pt;}	
    .address1{position:absolute;top:44.5%;left:13%; width:30%; font-size: 9pt;}	
    
    highlight p{padding-top:5px; padding-bottom:10px; font-size:8px; font-weight:100;}
    
    .studphoto{position:absolute;top:77px;left:9.1%;width:200px; height:200px;background-image:url("'.$STUD_PHOTO.'"); background-size:200px 200px; background-repeat:no-repeat; border-radius:100%;}
     .bottom_name{position:absolute;bottom:9%;width:40%; font-size:14px; float:right; margin-right:0px;left:60%;}
	</style>';
  $html .= '
          <img src="'.HTTP_HOST.'uploads/resume_blank.jpg" style="width:100%" />         
              <div class="studphoto"></div>
                          <h2 class="name">'.$STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME.'</h2>   
                          <h2 class="studname"> Full Name : '.$STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME.'</h2>   
                          <h2 class="dob"> Date Of Birth : '.date("d-m-Y",strtotime($STUDENT_DOB)).' </h2>
                          <h2 class="gender"> Gender : '.$STUDENT_GENDER.' </h2>
						  <h2 class="address"> Address : '.$STUDENT_PER_ADD.' </h2>
                          
                          <h2 class="mobile"> Mobile : '.$STUDENT_MOBILE.' </h2>
                          <h2 class="email"> Email Id : '.$STUDENT_EMAIL.' </h2>
                          <h2 class="address1"> '.$STUDENT_PER_ADD.' </h2>';
  
  $html .='<div style="position:fixed; top:370px; left:45%;">';
  
  						  $edu 	= $student->list_student_educational_info('',$user_id);
                          if($edu!='')
                            {
                            $html .= "<div style='width:100%; font-size: 10pt; margin-left:10px'>";
                            while($data = $edu->fetch_assoc())
                            {
                                $COURSE_NAME 			= $data['COURSE_NAME'];
                                $INSTITUTE_NAME 		= $data['INSTITUTE_NAME'];
                                $UNIVERSITY_NAME 		= $data['UNIVERSITY_NAME'];
                                $START_DATE_FORMATTED 	= $data['START_DATE_FORMATTED'];
                                $END_DATE_FORMATTED 	= $data['END_DATE_FORMATTED'];
                                $START_DATE_YEAR 		= $data['START_DATE_YEAR'];
                                $END_DATE_YEAR 			= $data['END_DATE_YEAR'];
                                $MARKS 					= $data['MARKS'];
                                $DESCRIPTION 			= $data['DESCRIPTION'];
                          
                                $html .='<div class="highlight">
                                <p> Course : '.$COURSE_NAME.' </p>
                                <p> Board / University : '.$UNIVERSITY_NAME.' </p>
                                <p> School / Institute : '.$INSTITUTE_NAME.' </p>
                                <p> Year : '.$END_DATE_YEAR.' </p>
                                <p> Marks : '.$MARKS.' </p> </div>';                         
                                }
                            $html .= "</div>";
                          }
  $html .= '</div>';	
  
  $html .='<div style="position:fixed; top:620px; left:5%;">';
  						  $exp 	= $student->list_student_experience_info('',$user_id);
                          if($exp!=''){
                            $html .= "<div style='width:100%; font-size: 10pt; margin-left:10px'>";                       
                            while($data = $exp->fetch_assoc())
                            {
                                $JOB_TITLE				= $data['JOB_TITLE'];
                                $COMPANY_NAME			= $data['COMPANY_NAME'];
                                $START_DATE_FORMATTED	= $data['START_DATE_FORMATTED'];
                                $END_DATE_FORMATTED		= $data['END_DATE_FORMATTED'];
                                $DESCRIPTION			= $data['DESCRIPTION'];
                                if($JOB_TITLE!='')
                                {
                                     $html .=' <div class="highlight">
                                     <p class="jobtitle"> Job Title : '.$JOB_TITLE.' </p>
                                    <p class="companyname"> Company Name : '.$COMPANY_NAME.' </p>
                                    <p class="datefrom"> Date From : '.$START_DATE_FORMATTED.' </p>
                                    <p class="dateto"> Date To : '.$END_DATE_FORMATTED.' </p>
                                    <p class="jobdesc"> Job Description : '.$DESCRIPTION.' </p></div>';
                                }
                               
							}
                             $html .= "</div>";
                            
                            }
                      $html .= '</div>';
    $html .= ' <h2 class="bottom_name">'.$STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME.'</h2> ';
    //$html .= '</div>';	
   // echo $html; exit();
	$mpdf->WriteHTML($html);
	//$mpdf->Output($file,'I');
	$mpdf->Output($STUDENT_FNAME.' '.$STUDENT_LNAME.' Resume.pdf', 'I');
		
}
ob_end_flush();
?>