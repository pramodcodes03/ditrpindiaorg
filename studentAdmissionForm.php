<?php
error_reporting(E_ALL);
ini_set("memory_limit","128M");
include('admin/include/classes/database_results.class.php');
include('admin/include/classes/access.class.php');
include('admin/include/classes/websiteManage.class.php');

$db   = new  database_results();
$access = new  access();
$websiteManage = new  websiteManage();

$id =  isset($_GET['id'])?$_GET['id']:'';
$INSTITUTE_COURSE_ID =  isset($_GET['courseid'])?$_GET['courseid']:'';


//$inst = array(186);
if($id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	include("admin/include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('admin/include/classes/student.class.php');
		
	$html='';	
	
	include_once('admin/include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
	//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	$mpdf->showImageErrors = true;
	//$mpdf->curlAllowUnsafeSslRequests = true;
	$student = new student();
	$res = $student->list_student($id,'','');
	$data 	= $res->fetch_assoc();
	//print_r($data); exit();
	//extract($data);
	$STUDENT_ID 		= $data['STUDENT_ID'];
	
	$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
	$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
	$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
	$INSTITUTE_ADDRESS 	= $data['INSTITUTE_ADDRESS']; 
	$INSTITUTE_CITY 	= $data['INSTITUTE_CITY'];
	$INSTITUTE_STATE 	= $data['INSTITUTE_STATE'];
	
	
	$STUD_PHOTO 		= $data['STUD_PHOTO'];
	$STUD_SIGN 			= $data['STUD_SIGN'];
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
    $CASTE = $data['CASTE'];
	
	//$INSTITUTE_COURSE_ID = $student->get_institutecourse_id($STUDENT_ID);
	
	/*$COURSE_ID = $student->get_student_course_id($INSTITUTE_COURSE_ID);
	
	$COURSE_NAME = $student->get_student_coursesname($COURSE_ID);
	
	$COURSE_CODE = $student->get_student_coursescode($COURSE_ID);
		
	$COURSE_AWARD = $student->get_student_coursesawardid($COURSE_ID);
	$AWARD = $student->get_student_awardname($COURSE_AWARD);*/


	$res1 = $student->get_student_course_id($INSTITUTE_COURSE_ID);
	if($res1!='')
	{
		while($resdata = $res1->fetch_assoc())
		{
			extract($resdata);
			//print_r($resdata);exit();
		}
	}
	if($COURSE_ID!='' && !empty($COURSE_ID) && $COURSE_ID!='0'){
		$COURSE_NAME = $student->get_student_coursesname($COURSE_ID);
		
		$COURSE_CODE = $student->get_student_coursescode($COURSE_ID);
			
		$COURSE_AWARD = $student->get_student_coursesawardid($COURSE_ID);
		$AWARD = $student->get_student_awardname($COURSE_AWARD);
	}
	if($MULTI_SUB_COURSE_ID!='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!='0'){
		$COURSE_NAME = $student->get_student_coursesname_multi_sub($MULTI_SUB_COURSE_ID);
		
		$COURSE_CODE = $student->get_student_coursescode_multi_sub($MULTI_SUB_COURSE_ID);
			
		$COURSE_AWARD = $student->get_student_coursesawardid_multi_sub($MULTI_SUB_COURSE_ID);
		$AWARD = $student->get_student_awardname_multi_sub($COURSE_AWARD);
	}

	
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];
	
		
	//$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;
	
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;

	$STUD_SIGN = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_SIGN;


	
	$filename = $STUDENT_CODE.'_'.$STUDENT_FNAME;
	$certificatefile = $filename.'.pdf';
	
	include_once('admin/include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$INSTITUTE_ID,'');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
    	//extract($dataB);	
    	$imageId = $dataB['inst_id'];
    	$admissionform_image = $dataB['admissionform_image'];
    	$admissionform_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$admissionform_image; 
      }
    }

	$file = "";
	if($QRFILE !== ''){
	    $file = HTTP_HOST.'/admin/'.$QRFILE;
	}	
	
	$payments = $student->get_fees_details_form($STUDENT_ID,$INSTITUTE_COURSE_ID);
	while($data4 = $payments->fetch_assoc()){
		{
			//print_r($data4); exit();
			//extract($data4);
			$COURSE_FEES = $data4['COURSE_FEES'];
			$TOTAL_COURSE_FEES = $data4['TOTAL_COURSE_FEES'];
			$FEES_RECIEVED = $data4['FEES_RECIEVED'];
			$FEES_BALANCE = $data4['FEES_BALANCE'];
			$FEES_PAID_DATE = $data4['FEES_PAID_DATE'];
			$BATCH_ID = $data4['BATCH_ID'];
			$ADMISSION_DATE = date("d-m-Y",strtotime($data4['CREATED_ON']));
			$batch_name = $db->get_batchname($BATCH_ID);
            $ADMISSION_DATE1 = date("d-m-Y",strtotime($data4['ADMISSION_DATE']));
		}
	}
	 
	$html2 = '
<style>

	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:702px;text-align:left;width:100%; font-size:12px; left:7%;}
	.instcode{position:absolute;top:667px;text-align:left;width:100%; font-size:12px; left:78%;}
	.admissiondate{position:absolute;top:275px;text-align:left;width:100%; font-size:12px; left:31%;}

	.rollnumber{position:absolute;top:275px;text-align:left;width:100%; font-size:12px; left:55%;}
	
	.instaddress1{position:absolute;top:757px;text-align:left;width:100%; font-size:12px; left:7%;}
	.instaddress2{position:absolute;top:777px;text-align:left;width:100%; font-size:12px; left:7%;}
	
	.studentfirstname{position:absolute;top:400px;text-align:left;width:100%; font-size:12px; left:8%;}
	.studentmiddlename{position:absolute;top:400px;text-align:left;width:100%; font-size:12px; left:31%;}
	.studentlastname{position:absolute;top:400px;text-align:left;width:100%; font-size:12px; left:52.5%;}
	.studentmothername{position:absolute;top:400px;text-align:left;width:100%; font-size:12px; left:73.5%;}
	
	.coursename{position:absolute;top:338px;text-align:left;width:100%; font-size:12px; left:10%;}
	
	.adharno{position:absolute;top:467px;text-align:left;width:100%; font-size:12px; left:10%;}
	.mobileno{position:absolute;top:467px;text-align:left;width:100%; font-size:12px; left:40%;}
	.mobilealtno{position:absolute;top:467px;text-align:left;width:100%; font-size:12px; left:70%;}
	
	.gender{position:absolute;top:528px;text-align:left;width:100%; font-size:12px; left:10%;}
	.email{position:absolute;top:528px;text-align:left;width:100%; font-size:12px; left:38%;}
	.dob{position:absolute;top:528px;text-align:left;width:100%; font-size:12px; left:70%;}

	.caste{position:absolute;top:587px;text-align:left;width:100%; font-size:12px; left:9%;}
	.occupation{position:absolute;top:587px;text-align:left;width:100%; font-size:12px; left:45%;}
	.qualifiaction{position:absolute;top:587px;text-align:left;width:100%; font-size:12px; left:28%;}
	.state{position:absolute;top:587px;text-align:left;width:100%; font-size:12px; left:60%;}
	.postcode{position:absolute;top:587px;text-align:left;width:100%; font-size:12px; left:80%;}
	.permanantaddress{position:absolute;top:650px;text-align:left;width:100%; font-size:12px; left:9%;}
	
	.studphoto{position:absolute;top:190px;width:140px; height:140px;background-image:url("'.$STUD_PHOTO.'"); background-size:140px 140px; background-repeat:no-repeat; left:75%;}

	.studsign{position:absolute;top:664px;width:175px; height:60px;background-image:url("'.$STUD_SIGN.'"); background-size:175px 60px; background-repeat:no-repeat; right:10%;}
	
	.qrheadtext{position:absolute;top:5.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;bottom:11%;width:85px; height:85px; text-align:center; float:right; left:62.5%;}

	.coursefees{position:absolute;top:795px;text-align:left;width:100%; font-size:14px; left:10%;}
	.paidfees{position:absolute;top:795px;text-align:left;width:100%; font-size:14px; left:40%;}
	.balancefees{position:absolute;top:795px;text-align:left;width:100%; font-size:14px; left:70%;}
	.batchname{position:absolute;top:855px;text-align:left;width:100%; font-size:14px; left:10%;}
    
	</style>';
	$html = '	
				<div class="qrcodeimage"><img src="'.$file.'"></div> 

	            <div class="studphoto"></div>
	            <p class="coursename">'.$AWARD.' IN '.$COURSE_NAME.' ('.$COURSE_CODE.')</p>
	            <p class="admissiondate">'.$ADMISSION_DATE1.'</p>

				<p class="rollnumber"><strong> Roll Number : '.$ROLL_NUMBER.'</strong></p>
	            
	            <p class="studentfirstname">'.$STUDENT_FNAME.'</p>
				<p class="studentmiddlename">'.$STUDENT_MNAME.'</p>
				<p class="studentlastname">'.$STUDENT_LNAME.'</p>
				<p class="studentmothername">'.$STUDENT_MOTHERNAME.'</p>
				
				<p class="adharno">'.$STUDENT_ADHAR_NUMBER.'</p>
				<p class="mobileno">'.$STUDENT_MOBILE.'</p>
				<p class="mobilealtno">'.$STUDENT_MOBILE2.'</p>
				
				<p class="gender">'.$STUDENT_GENDER.'</p>
				<p class="dob">'.$STUD_DOB_FORMATED.'</p>
				<p class="email">'.$STUDENT_EMAIL.'</p>
				<p class="caste">'.$CASTE .'</p>
				<p class="qualifiaction">'.$EDUCATIONAL_QUALIFICATION .'</p>
				
				<p class="occupation">'.$OCCUPATION.'</p>
				
				<p class="state">'.$student->get_state($STUDENT_STATE).'</p>
				
				<p class="postcode">'.$STUDENT_PINCODE.'</p>
				
				<p class="permanantaddress" style="word-wrap: break-word; width:50%">'.$STUDENT_PER_ADD.' , '.$STUDENT_CITY.' , '.$student->get_state($STUDENT_STATE).'</p>

				<div class="studsign"></div>

				<p class="coursefees">'.$COURSE_FEES.'</p>
				<p class="paidfees">'.$FEES_RECIEVED.'</p>
				<p class="balancefees">'.$FEES_BALANCE.'</p>
				<p class="batchname">'.$batch_name.'</p>
				';
	
	
	$html2 .= '<img src="'.$admissionform_image.'" style="width:100%;" />';
	

	$html2 .= $html;
    
	//==============================================================
//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output($STUDENT_FNAME.' '.$STUDENT_LNAME.'_Admission_Form.pdf', 'I');
}
?>