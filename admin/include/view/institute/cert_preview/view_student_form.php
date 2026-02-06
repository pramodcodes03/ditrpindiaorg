<?php
	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => sys_get_temp_dir() . '/mpdf']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);

	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$user_id,'');
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
	$STUD_PHOTO = 'resources/dummy/dummy-photo.png';
	$STUD_SIGN = 'resources/dummy/dummy-signature.png';
	$file = 'resources/dummy/dummy_qr.png'; 
	 
	$html2 = '
	<style>

	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:702px;text-align:left;width:100%; font-size:12px; left:7%;}
	.instcode{position:absolute;top:667px;text-align:left;width:100%; font-size:12px; left:78%;}
	.admissiondate{position:absolute;top:270px;text-align:left;width:100%; font-size:12px; left:28%;}

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

	.studsign{position:absolute;top:664px;width:195px; height:60px;background-image:url("'.$STUD_SIGN.'"); background-size:195px 60px; background-repeat:no-repeat; right:10%;}
	
	.qrheadtext{position:absolute;top:5.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;bottom:10%;width:85px; height:85px; text-align:center; float:right; left:62.5%;}

	.coursefees{position:absolute;top:770px;text-align:left;width:100%; font-size:12px; left:10%;}
	.paidfees{position:absolute;top:770px;text-align:left;width:100%; font-size:12px; left:40%;}
	.balancefees{position:absolute;top:770px;text-align:left;width:100%; font-size:12px; left:70%;}
	.batchname{position:absolute;top:830px;text-align:left;width:100%; font-size:12px; left:10%;}
    
	</style>';
	$html = '	
				<div class="qrcodeimage"><img src="'.$file.'"></div> 

	            <div class="studphoto"></div>
	            <p class="coursename"> ADVANCE DIPLOMA IN COMPUTER APPLICATION (M-10001) </p>
	            <p class="admissiondate"> 21-07-2023 </p>

				<p class="rollnumber"><strong> Roll Number : 101 </strong></p>
	            
	            <p class="studentfirstname">RAHUL</p>
				<p class="studentmiddlename">RAM</p>
				<p class="studentlastname">DAS</p>
				<p class="studentmothername">PUJA DAS</p>
				
				<p class="adharno">440044004400</p>
				<p class="mobileno">9898989898</p>
				<p class="mobilealtno">7474747474</p>
				
				<p class="gender">Male</p>
				<p class="dob">28-08-1999</p>
				<p class="email">test@gmail.com</p>
				<p class="caste">Hindu</p>
				<p class="qualifiaction">12th Pass</p>
				
				<p class="occupation">Student</p>
				
				<p class="state">Maharashtra</p>
				
				<p class="postcode">403103</p>
				
				<p class="permanantaddress" style="word-wrap: break-word; width:60%">New Rushikesh Apartment, Near Cricket Ground, Virat Nagar, Virar West, Palghar, Mumbai, 401303, Maharashtra, India</p>

				<div class="studsign"></div>               
                
				<p class="coursefees">5000</p>
				<p class="paidfees">1000</p>
				<p class="balancefees">4000</p>
				<p class="batchname">Batch 1</p>
				
				';
	
	
	$html2 .= '<img src="'.$admissionform_image.'" style="width:100%;" />';
	

	$html2 .= $html;
    
	//==============================================================
//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output('Admission_Form.pdf', 'I');

?>