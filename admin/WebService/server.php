<?php 
error_reporting(E_ALL);
error_reporting(1);
// headers to tell that result is JSON
header('Content-type: application/json');
//header();
session_start();

include('../include/classes/webservice.class.php');
//
$db 	= new  database_results();
$access = new  access();
$webservice = new  webservice();
$service = isset($_REQUEST['service'])?$_REQUEST['service']:'';

$output = "";

switch($service)
{
	case('studentLogin'):
	    //error_log(print_r($_POST, TRUE));
		$uname	= $db->test(isset($_POST['uname'])?$_POST['uname']:'');
		$pword	= $db->test(isset($_POST['pword'])?$_POST['pword']:'');
		$deviceid= $db->test(isset($_POST['deviceid'])?$_POST['deviceid']:'');
		$output = $webservice->wuser_login($uname,$pword,$deviceid);
		break;
		
	case('studentLogout'):
		$userid = $db->test($_POST['userid'])?$_POST['userid']:'';
		$output = $webservice->wuser_logout($userid);
		break;
		
	case('forgetPassword'):
		$email = $db->test($_POST['email'])?$_POST['email']:'';
		$role = $db->test($_POST['role'])?$_POST['role']:'';
		$output = $access->forgot_pass($email,$role);		
		break;

	case('studentRegister'):
		//$photo	    = isset($_FILES['photo']['name'])?$_FILES['photo']['name']:'';
 	    //	error_log(print_r($_POST, TRUE));exit();
        // 	echo $_POST['photo'];  
        // print_r($_POST);
		
		$photo       = $db->test($_POST['photo'])?$_POST['photo']:'';
			
		$name       = $db->test($_POST['name'])?$_POST['name']:'';
		
		$fathername       = $db->test($_POST['fathername'])?$_POST['fathername']:'';
		$mothername       = $db->test($_POST['mothername'])?$_POST['mothername']:'';
		$surname             = $db->test($_POST['surname'])?$_POST['surname']:'';
		
		$mobile     = $db->test($_POST['mobile'])?$_POST['mobile']:'';
		$email      = $db->test($_POST['email'])?$_POST['email']:'';
		$dob        = $db->test($_POST['dob'])?$_POST['dob']:'';
		$gender     = $db->test($_POST['gender'])?$_POST['gender']:'';
		$conf_pass  = $db->test($_POST['conf_pass'])?$_POST['conf_pass']:'';
		$aadhar     = $db->test($_POST['aadhar'])?$_POST['aadhar']:'';
		$address    = $db->test($_POST['address'])?$_POST['address']:'';
		$state      = $db->test($_POST['state'])?$_POST['state']:'';
		$city       = $db->test($_POST['city'])?$_POST['city']:'';
		$pincode    = $db->test($_POST['pincode'])?$_POST['pincode']:'';
		
		$output     = $webservice->add_student($name,$fathername,$mothername,$surname,$mobile,$email,$dob,$gender,$conf_pass,$aadhar,$address,$state,$city,$pincode,$photo);
		break;
    
    case('editProfile'):
			
		$userid       = $db->test($_POST['userid'])?$_POST['userid']:'';
		$photo        = $db->test($_POST['photo'])?$_POST['photo']:'';
		$name             = $db->test($_POST['name'])?$_POST['name']:'';
		$fathername       = $db->test($_POST['fathername'])?$_POST['fathername']:'';
		$mothername       = $db->test($_POST['mothername'])?$_POST['mothername']:'';
		$surname          = $db->test($_POST['surname'])?$_POST['surname']:'';
		
		$mobile     = $db->test($_POST['mobile'])?$_POST['mobile']:'';
		$email      = $db->test($_POST['email'])?$_POST['email']:'';
		$dob        = $db->test($_POST['dob'])?$_POST['dob']:'';
		$gender     = $db->test($_POST['gender'])?$_POST['gender']:'';
		//$conf_pass  = $db->test($_POST['conf_pass'])?$_POST['conf_pass']:'';
		$aadhar     = $db->test($_POST['aadhar'])?$_POST['aadhar']:'';
		
     	$address    = $db->test($_POST['address'])?$_POST['address']:'';
// 		$state      = $db->test($_POST['state'])?$_POST['state']:'';
// 		$city       = $db->test($_POST['city'])?$_POST['city']:'';
// 		$pincode    = $db->test($_POST['pincode'])?$_POST['pincode']:'';
		//error_log(print_r($_POST, TRUE)); exit();

		$output = $webservice->editProfile($userid,$name,$fathername,$mothername,$surname,$mobile,$email,$dob,$gender,$aadhar,$photo,$address);
		break;
		
	 case('changePassword'):
	//	error_log(print_r($_POST, TRUE)); exit();
		$user_id             = $db->test($_POST['user_id'])?$_POST['user_id']:'';
		$curr_password       = $db->test($_POST['curr_password'])?$_POST['curr_password']:'';
		$new_password        = $db->test($_POST['new_password'])?$_POST['new_password']:'';
		$confirm_password    = $db->test($_POST['confirm_password'])?$_POST['confirm_password']:'';
		
		$output = $webservice->changePassword($user_id,$curr_password,$new_password,$confirm_password);
		break;
		
    case('getAllCourses'):	
        $student_id = $db->test($_GET['student_id'])?$_GET['student_id']:'';
		$output = $webservice->getAllCourses($student_id);
		break;
		
	case('getAllCoursesAfterLogin'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $section_id = $db->test($_GET['section_id'])?$_GET['section_id']:'';
		$output = $webservice->getAllCoursesAfterLogin($userid,$section_id);
		break;

	case('getCoursesById'):	
		$inst_courseid = $db->test($_GET['inst_courseid'])?$_GET['inst_courseid']:'';
    	$userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
    	$inst_id = $db->test($_GET['inst_id'])?$_GET['inst_id']:'';	
		$output = $webservice->getCoursesById($inst_courseid,$userid,$inst_id);
		break;

	case('getCoursesPDF'):	
		$courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';	
		$output = $webservice->getCoursesPDF($courseid);
		break;

	case('getCoursesVideos'):	
		$courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';	
		$output = $webservice->getCoursesVideos($courseid);
		break;

	case('getWallet'):	
		$userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
		$output = $webservice->getWallet($userid);
		break;

	case('getInstituteDetails'):	
		$userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
		$output = $webservice->getInstituteDetails($userid);
		break;

	case('getStateList'):	
		$output = $webservice->getStateList();
		break;

	case('getCityList'):
		$stateid = $db->test($_GET['stateid'])?$_GET['stateid']:'';	
		$output = $webservice->getCityList($stateid);
		break;

	case('getProfile'):
		$userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
		$output = $webservice->getProfile($userid);
		break;
		
	case('getSlider'):		
		$output = $webservice->getSlider();
		break;
		
	case('buyCourseOnline'):
		$output = $webservice->buyCourseOnline();
		break;
			
	case('buyCourseWallet'):
		$output = $webservice->buyCourseWallet();
		break;
		
	case('CoursePurchaseList'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->CoursePurchaseList($userid);
		break;
		
	case('DemoExamList'):
	    $inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    
		$output = $webservice->DemoExamList($inst_course_id,$userid);
		break;
		
	case('StartDemoExam'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $langid = $db->test($_GET['langid'])?$_GET['langid']:'';
	    
		$output = $webservice->StartDemoExam($courseid,$userid,$langid);
		break;
		
    case('GetDemoExam'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $sessionid = $db->test($_GET['session_id'])?$_GET['session_id']:'';
	    
		$output = $webservice->GetDemoExam($courseid,$userid,$sessionid);
		break;
		
	case('SaveDemoExam'):
		$output = $webservice->SaveDemoExam();
		break;
		
	//final exam section
	
	case('FinalExamList'):
	    $courseid   = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid     = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->FinalExamList($courseid,$userid);
		break;
		
	case('StartFinalExam'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $langid = $db->test($_GET['langid'])?$_GET['langid']:'';
        $inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
		$output = $webservice->StartFinalExam($courseid,$userid,$langid,$inst_course_id);
		break;
		
    case('GetFinalExam'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $sessionid = $db->test($_GET['session_id'])?$_GET['session_id']:'';
		$output = $webservice->GetFinalExam($userid,$sessionid);
		break;
		
	case('SaveFinalExam'):
		$output = $webservice->SaveFinalExam();
		break;
		
    case('CourseEnquiryByStudent'):
		$output = $webservice->CourseEnquiryByStudent();
		break;
		
	case('WalletTrasactionList'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
		$output = $webservice->WalletTrasactionList($userid);
		break;
		
	case('GetFinalExamResult'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    //$examid = $db->test($_GET['examid'])?$_GET['examid']:'';
    	//$inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
		$output = $webservice->GetFinalExamResult($userid);
		break;
		
	case('ResetFinalExam'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
		$output = $webservice->ResetFinalExam($userid,$courseid);
		break;
		
	case('ApproveFinalCertificate'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $examresultid = $db->test($_GET['examresultid'])?$_GET['examresultid']:'';
		$output = $webservice->ApproveFinalCertificate($userid,$courseid,$examresultid);
		break;
		
	case('OrderFinalCertificate'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $examresultid = $db->test($_GET['examresultid'])?$_GET['examresultid']:'';
	    $cert_apply_id   = $db->test($_GET['cert_apply_id'])?$_GET['cert_apply_id']:'';
		$output = $webservice->OrderFinalCertificate($userid,$courseid,$examresultid,$cert_apply_id);
	    break;
	 
	case('Notification'):
		$output = $webservice->Notification();
	    break;
	
	case('OurAchivers'):
	    $state = $db->test($_GET['state'])?$_GET['state']:'';
		$output = $webservice->OurAchivers($state);
	    break;
	    
	case('CourseTestimonials'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
		$output = $webservice->CourseTestimonials($courseid);
	    break;
		
	case('GetExamInstruction'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->GetExamInstruction($courseid,$userid);
	    break;
	    
	 case('ShowCertificate'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $cert_apply_id   = $db->test($_GET['cert_apply_id'])?$_GET['cert_apply_id']:'';
		$output = $webservice->ShowCertificate($courseid,$userid,$cert_apply_id);
	    break;
	    
	 case('ShowMarksheet'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $cert_apply_id   = $db->test($_GET['cert_apply_id'])?$_GET['cert_apply_id']:'';
		$output = $webservice->ShowMarksheet($courseid,$userid,$cert_apply_id);
	    break;
	
	case('OnlineTrasactionList'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';	
		$output = $webservice->OnlineTrasactionList($userid);
		break;
		
	///search cources
		
	 case('getAllCoursesSearch'):	
	    $keyword = $db->test($_GET['keyword'])?$_GET['keyword']:'';
		$output = $webservice->getAllCoursesSearch($keyword);
		break;
		
	case('getAllCoursesAfterLoginSearch'):
	    $keyword = $db->test($_GET['keyword'])?$_GET['keyword']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->getAllCoursesAfterLoginSearch($userid,$keyword);
		break;
		
	case('becomeaVolunteer'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->becomeaVolunteer($userid);
		break;
		
	case('checkVolunteerStatus'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->checkVolunteerStatus($userid);
		break;
		
	case('applyCoupon'):
	    $couponcode = $db->test($_GET['couponcode'])?$_GET['couponcode']:'';
		$output = $webservice->applyCoupon($couponcode);
		break;
		
	case('getParcelDetails'):
	    $courseid = $db->test($_GET['courseid'])?$_GET['courseid']:'';
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
	    $cert_apply_id   = $db->test($_GET['cert_apply_id'])?$_GET['cert_apply_id']:'';
		$output = $webservice->getParcelDetails($courseid,$userid,$cert_apply_id);
		break;
    
    //new api's
    case('getOnlineClasses'):
	    $institute_id = $db->test($_GET['institute_id'])?$_GET['institute_id']:'';
    	$inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
		$output = $webservice->getOnlineClasses($institute_id,$inst_course_id);
		break;
    
    case('getStudentPhotos'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->getStudentPhotos($userid);
		break;
    
    case('getInstituteLogo'):    
      $inst_id = $db->test($_GET['inst_id'])?$_GET['inst_id']:'';
      $output = $webservice->getInstituteLogo($inst_id);
      break;
    
    case('getAdvertiseList'):    
      $institute_id = $db->test($_GET['institute_id'])?$_GET['institute_id']:'';
      $output = $webservice->getAdvertiseList($institute_id);
      break;
    
    case('getTodaysBirthday'):    
      $institute_id = $db->test($_GET['institute_id'])?$_GET['institute_id']:'';
      $output = $webservice->getTodaysBirthday($institute_id);
      break;
    
    case('getBirthdayThisMonth'):  
      $institute_id = $db->test($_GET['institute_id'])?$_GET['institute_id']:'';
      $output = $webservice->getBirthdayThisMonth($institute_id);
      break;
    
     case('getStudentBalanceFees'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
    	$course_id = $db->test($_GET['course_id'])?$_GET['course_id']:'';
		$output = $webservice->getStudentBalanceFees($userid,$course_id);
		break;
    
    case('home'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
    	$course_id = $db->test($_GET['course_id'])?$_GET['course_id']:'';
		$output = $webservice->homePageApi($userid);
		break;
    
    case('studentFeesHistory'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
    	$stud_course_details_id = $db->test($_GET['stud_course_details_id'])?$_GET['stud_course_details_id']:'';
		$output = $webservice->studentFeesHistory($userid,$stud_course_details_id);
		break;
    
    case('getDemoExamResult'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->getDemoExamResult($userid);
		break;
    
    case('studentWalletHistory'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->studentWalletHistory($userid);
		break;
    
    case('listHelpSupport'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
		$output = $webservice->listHelpSupport($userid);
		break;
    
    case('addHelpSupport'):	
		$userid = $db->test($_POST['userid'])?$_POST['userid']:'';
        $inst_id = $db->test($_POST['inst_id'])?$_POST['inst_id']:'';
        $mobile = $db->test($_POST['mobile'])?$_POST['mobile']:'';
        $email = $db->test($_POST['email'])?$_POST['email']:'';
        $description = $db->test($_POST['description'])?$_POST['description']:'';
	
		$output = $webservice->addHelpSupport($userid,$inst_id,$mobile,$email,$description);
		break;
    
    case('listAttendance'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
        $inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
		$output = $webservice->listAttendance($userid,$inst_course_id);
		break;
    
    case('newCoursePurchase'):
	    $student_id = $db->test($_POST['student_id'])?$_POST['student_id']:'';
        $inst_id = $db->test($_POST['inst_id'])?$_POST['inst_id']:'';
        $inst_course_id = $db->test($_POST['inst_course_id'])?$_POST['inst_course_id']:'';
        $paying_amount = $db->test($_POST['paying_amount'])?$_POST['paying_amount']:'';
        $minimum_courseamount = $db->test($_POST['minimum_courseamount'])?$_POST['minimum_courseamount']:'';
        $wallet_amount = $db->test($_POST['wallet_amount'])?$_POST['wallet_amount']:'';
        $coursefees = $db->test($_POST['coursefees'])?$_POST['coursefees']:'';    
    
		$output = $webservice->newCoursePurchase($student_id,$inst_id,$inst_course_id,$paying_amount,$minimum_courseamount,$wallet_amount,$coursefees);
		break;
    
    case('getFinalExamOTP'):
	    $userid = $db->test($_GET['userid'])?$_GET['userid']:'';
        $inst_course_id = $db->test($_GET['inst_course_id'])?$_GET['inst_course_id']:'';
		$output = $webservice->getFinalExamOTP($userid,$inst_course_id);
		break;
    
     case('verifyFinalExamOTP'):	
		$userid = $db->test($_POST['userid'])?$_POST['userid']:'';
        $inst_course_id = $db->test($_POST['inst_course_id'])?$_POST['inst_course_id']:'';
        $otp = $db->test($_POST['otp'])?$_POST['otp']:'';     
	
		$output = $webservice->verifyFinalExamOTP($userid,$inst_course_id,$otp);
		break;
    
    case('about'):
		$output = $webservice->aboutUs();
		break;
    
    case('privacyPolicy'):
		$output = $webservice->privacyPolicy();
		break;
    
    case('termsCondition'):
		$output = $webservice->termsCondition();
		break;

	default:
		$output = $webservice->defaultReqErr();
		break;
}

 echo  json_encode($output);


?>

