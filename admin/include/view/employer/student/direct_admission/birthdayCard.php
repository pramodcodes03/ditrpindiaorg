<?php
error_reporting(E_ALL);
ini_set("memory_limit","128M");
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
if($user_role==3){
   $institute_id = $db->get_parent_id($user_role,$user_id);
   $staff_id = $user_id;
}
else{
   $institute_id = $user_id;
   $staff_id = 0;
}
$id =  isset($_GET['id'])?$_GET['id']:'';


//$inst = array(186);
if($id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('include/classes/student.class.php');
		
	$html='';	
	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [150,150]]);
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
	extract($data);
	
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;

	$STUD_SIGN = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_SIGN;
	
	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$institute_id,'');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
    	extract($dataB);	
        //print_r($dataB); exit();
    	$imageId = $dataB['inst_id'];
    	
    	if($birthdayimage !=''){
              $birthdayimage  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$birthdayimage;
          }else{
               $birthdayimage  = "resources/default/birthday.jpg";
          }
    
      }
    }

	$html2 = '
<style>

	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studentfirstname{position:absolute;top:480px;text-align:left;width:100%; font-size:22px; left:50%;color:#fff; font-weight:bold; }

	.dob{position:absolute;top:515px;text-align:left;width:100%; font-size:18px; left:55%;color:#fff; font-weight:bold;}

	.studphoto{position:absolute;top:280px;width:200px; height:200px;background-image:url("'.$STUD_PHOTO.'"); background-size:200px 200px; background-repeat:no-repeat; left:55%; border-radius:50%;border:3px solid #fff;}

	.studsign{position:absolute;top:664px;width:175px; height:60px;background-image:url("'.$STUD_SIGN.'"); background-size:175px 60px; background-repeat:no-repeat; right:10%;}
	
    
	</style>';
	$html = '	<div class="studphoto"></div>
	            <p class="studentfirstname">'.$STUDENT_FNAME.' '.$STUDENT_LNAME.'</p>
				<p class="dob"> Date : '.$STUD_DOB_FORMATED.'</p>
			
				';
	
	
	$html2 .= '<img src="'.$birthdayimage.'" style="width:100%; background-size: cover; use background-image-resize: 6;" />';
	

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