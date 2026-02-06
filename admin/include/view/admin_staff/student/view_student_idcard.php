<?php
//error_reporting(E_ALL);
ini_set("memory_limit","128M");
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
if($user_role==5){
   $institute_id = $db->get_parent_id($user_role,$user_id);
   $staff_id = $user_id;
}
else{
   $institute_id = $user_id;
   $staff_id = 0;
}
$id =  isset($_GET['id'])?$_GET['id']:'';
$INSTITUTE_COURSE_ID =  isset($_GET['courseid'])?$_GET['courseid']:'';
//$inst = array(186);
if($id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('include/classes/student.class.php');
		
	$html='';	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => sys_get_temp_dir() . '/mpdf']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
    
	//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	$student = new student();
	$res = $student->list_student($id,'','');
	$data 	= $res->fetch_assoc();	
	//extract($data);	
	$STUDENT_ID 		= $data['STUDENT_ID'];	
	$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
	$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
	$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
	$INSTITUTE_ADDRESS 	= $data['INSTITUTE_ADDRESS']; 
	$INSTITUTE_CITY 	= $data['INSTITUTE_CITY'];
	$INSTITUTE_STATE 	= $data['INSTITUTE_STATE'];
	$INSTITUTE_MOBILE 	= $data['INSTITUTE_MOBILE'];
	
	$STUD_PHOTO 		= $data['STUD_PHOTO'];
	$ABBREVIATION 		= $data['ABBREVIATION'];
	$STUDENT_CODE		= $data['STUDENT_CODE'];
	$STUDENT_FNAME 	    = $data['STUDENT_FNAME'];
	$STUDENT_MNAME 	    = $data['STUDENT_MNAME'];
	$STUDENT_LNAME      = $data['STUDENT_LNAME'];
	$STUDENT_MOTHERNAME = $data['STUDENT_MOTHERNAME'];
	$STUD_DOB_FORMATED  = $data['STUD_DOB_FORMATED'];
	$STUDENT_GENDER     = strtoupper($data['STUDENT_GENDER']);
	$STUDENT_MOBILE     = $data['STUDENT_MOBILE'];
	$STUDENT_MOBILE2    = $data['STUDENT_MOBILE2'];
	$STUDENT_EMAIL 		= strtolower($data['STUDENT_EMAIL']);	
	$ACCOUNT_REGISTERED_DATE = $data['ACCOUNT_REGISTERED_DATE'];	
	$JOINING_FORMATED = $data['JOINING_FORMATED'];

	$STUDENT_TEMP_ADD 		= htmlspecialchars_decode(strtoupper(rtrim($data['STUDENT_TEMP_ADD'],',')));
	$STUDENT_PER_ADD 		= htmlspecialchars_decode(strtoupper($data['STUDENT_PER_ADD']));
	$STUDENT_CITY 			= htmlspecialchars_decode(strtoupper($data['STUDENT_CITY']));
	$STUDENT_STATE 	    	= htmlspecialchars_decode(strtoupper($data['STUDENT_STATE']));
	$STUDENT_PINCODE 		= $data['STUDENT_PINCODE'];   	
	
	$STUDENT_ADHAR_NUMBER 		    = $data['STUDENT_ADHAR_NUMBER'];
	$EDUCATIONAL_QUALIFICATION 		= $data['EDUCATIONAL_QUALIFICATION'];
	$OCCUPATION 		            = $data['OCCUPATION'];
	$INTERESTS 		                = $data['INTERESTS'];
	$QRFILE 		                = $data['QRFILE'];
  	$ROLL_NUMBER 		            = $data['ROLL_NUMBER'];
	
	//$INSTITUTE_COURSE_ID = $student->get_institutecourse_id($STUDENT_ID);

	$res1 = $student->get_student_course_id($INSTITUTE_COURSE_ID);
	if($res1!='')
	{
		while($resdata = $res1->fetch_assoc())
		{
			extract($resdata);			
		}
	}
	if($COURSE_ID!=''  && !empty($COURSE_ID) && $COURSE_ID!='0'){
		$COURSE_NAME = $student->get_student_coursesname($COURSE_ID);
		
		$COURSE_CODE = $student->get_student_coursescode($COURSE_ID);
			
		$COURSE_AWARD = $student->get_student_coursesawardid($COURSE_ID);
		$AWARD = $student->get_student_awardname($COURSE_AWARD);
	}
	if($MULTI_SUB_COURSE_ID!=''  && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!='0'){
		$COURSE_NAME = $student->get_student_coursesname_multi_sub($MULTI_SUB_COURSE_ID);
		
		$COURSE_CODE = $student->get_student_coursescode_multi_sub($MULTI_SUB_COURSE_ID);
			
		$COURSE_AWARD = $student->get_student_coursesawardid_multi_sub($MULTI_SUB_COURSE_ID);
		$AWARD = $student->get_student_awardname_multi_sub($MULTI_SUB_COURSE_ID);
	}

	
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];
	
		
	//$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;
	
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;
	
	$filename = $STUDENT_CODE.'_'.$STUDENT_FNAME;
	$certificatefile = $filename.'.pdf';
	
	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$INSTITUTE_ID,'');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
	    //extract($dataB);	
	    $imageId = $dataB['inst_id'];
	    $idcard_image = $dataB['idcard_image'];
	    $idcard_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$idcard_image;
      }
    }

	$file1 = "";
	if($QRFILE !== ''){
	    $file1 = HTTP_HOST_ADMIN.'/'.$QRFILE;
	}
	
	$html2 = '
<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:239px;text-align:left;width:100%; font-size:07px; left:1%;}
	.studcode{position:absolute;top:278px;text-align:left;width:100%; font-size:10px; left:12.8%;}
	.admissiondate{position:absolute;top:184px;text-align:left;width:100%; font-size:08px; left:08%;}

	.rollnumber{position:absolute;top:293px;text-align:left;width:100%; font-size:12px; left:1.8%;}
	
	.instaddress1{position:absolute;top:262px;text-align:left;width:19.5%; font-size:07px; left:1.4%;}
	.instaddress2{position:absolute;top:282px;text-align:left;width:19.5%; font-size:07px; left:1.4%;}
	
	.instmobile{position:absolute;top:304px;text-align:left;width:100%; font-size:08px; left:8%;}
	
	.studentfirstname{position:absolute;top:217px;text-align:left;width:100%; font-size:10px; left:12.5%;}
	.studentmiddlename{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:30%;}
	.studentlastname{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:53%;}
	.studentmothername{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:76%;}

	.studentheading{position:absolute;top:185px;text-align:left;width:100%; font-size:08px; left:11.5%;}
	.courseheading{position:absolute;top:248px;text-align:left;width:100%; font-size:08px; left:11.5%;}
	.mobileheading{position:absolute;top:290px;text-align:left;width:100%; font-size:08px; left:11.5%;}

	
	.coursename{position:absolute;top:235px;text-align:left;width:15%; font-size:06px; left:12.5%; line-height:9px; }
	
	.adharno{position:absolute;top:375px;text-align:left;width:100%; font-size:12px; left:7%;}
	.mobileno{position:absolute;top:258px;text-align:left;width:100%; font-size:10px; left:14%;}
	.mobilealtno{position:absolute;top:375px;text-align:left;width:100%; font-size:12px; left:71%;}
	
	.email{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:47%;}
	
	.dob{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:30.6%;}
	
	.gender{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:7%;}
	
	.occupation{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:40%;}
	.qualifiaction{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:21%;}
	.state{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:65%;}
	.postcode{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:86%;}
	.permanantaddress{position:absolute;top:575px;text-align:left;width:100%; font-size:12px; left:7%;}
	
	.studphoto{position:absolute;top:127px;width:80px; height:90px;background-image:url("'.$STUD_PHOTO.'"); background-size:80px 90px; background-repeat:no-repeat; left:10%;}

	.qrheadtext{position:absolute;top:5.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:27.1%;width:40px; height:40px; text-align:center; float:right; left:20%;}
    

	</style>';
	$html = '	 <div class="qrcodeimage"><img src="'.$file1.'"></div> 
	            <div class="studphoto"></div>
			
	           
	            <p class="studentfirstname">'.$STUDENT_FNAME.'</p>
			
	            <p class="coursename">'.$AWARD.' IN '.$COURSE_NAME.' ('.$COURSE_CODE.')</p>
	           <!-- <p class="admissiondate">'.$JOINING_FORMATED.'</p> -->
			

				<p class="mobileno">'.$STUDENT_MOBILE.'</p>
				
				<p class="studcode">'.$STUDENT_CODE.'</p>

				<p class="rollnumber"><strong>  Roll Number : '.$ROLL_NUMBER.'</strong></p>

				';
	
	
	$html2 .= '<img src="'.$idcard_image.'" style="width:30%;" />';
	

	$html2 .= $html;
    
	//==============================================================
//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
   $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
   $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output($STUDENT_FNAME.' '.$STUDENT_LNAME.'_IDCard.pdf', 'I');
}
?>