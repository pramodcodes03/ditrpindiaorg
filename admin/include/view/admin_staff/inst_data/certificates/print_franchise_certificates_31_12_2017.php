<?php
//error_reporting(E_ALL);
ini_set("memory_limit","128M");
$id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'';

//$inst = array(186);
if($id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('include/classes/institute.class.php');
		
	$html='';	
	
	
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	$institute = new institute();
	$res = $institute->list_institute($id,'');
	$data 	= $res->fetch_assoc();	
	//extract($data);
	$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
	$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
	$REG_DATE 			= $data['REG_DATE'];
	$VERIFIED_DATE			= $data['VERIFIED_ON_FORMATTED'];
	$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
	$INSTITUTE_NAME 	= strtoupper($data['INSTITUTE_NAME']);
	$INSTITUTE_OWNER_NAME= htmlspecialchars_decode(strtoupper($data['INSTITUTE_OWNER_NAME']));
	$EMAIL 				= strtolower($data['EMAIL']);
	$MOBILE 			= $data['MOBILE'];
	
	$VERIFIED 			= $data['VERIFIED'];
	$ADDRESS_LINE1 		= htmlspecialchars_decode(strtoupper(rtrim($data['ADDRESS_LINE1'],',')));
	$ADDRESS_LINE2 		= htmlspecialchars_decode(strtoupper($data['ADDRESS_LINE2']));
	$CITY_NAME 			= htmlspecialchars_decode(strtoupper($data['CITY_NAME']));
	$STATE_NAME 		= htmlspecialchars_decode(strtoupper($data['STATE_NAME']));
	$POSTCODE 			= $data['POSTCODE'];
	
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];
	$today_date	 = $REG_DATE; //date('d.m.Y');
	$certificate_date = date('Y-m-d');
	$filepath	 = INST_CERTIFICATE_PATH.'/'.$INSTITUTE_ID;
	if (!file_exists($filepath)) {
		@mkdir($filepath, 0777, true);
	}	
	$rand = $access->getRandomCode(6);
	$filename = $INSTITUTE_CODE.'_'.$rand;
	$certificatefile = $filename.'.pdf';
	$addressfile = $INSTITUTE_CODE.'_address_'.$rand.'.pdf';
	$file		= $filepath.'/'.$certificatefile;
	$addressPrintFile = $filepath.'/'.$addressfile;
	$renewline = "Valid upto 31<sup>st</sup> March ". (@date('Y')+1) .", subject to renewal.";
	
	
	
	
	$html2 = '
<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:480px;text-align:center;width:100%;}
	.instaddress1{position:absolute;top:510px;text-align:center;width:100%; font-size:10px; }
	.instaddress2{position:absolute;top:525px;text-align:center;width:100%; font-size:10px; }
	.applicant{position:absolute;top:545px;text-align:center;width:100%;}
	.email{position:absolute;top:560px;text-align:center;width:100%;font-weight:400;}
	.certdate{position:absolute;bottom:230px;left:28%;}
	.validdate{position:absolute;bottom:202px;left:7%;}
	.atccode{position:absolute;bottom:80px;left:46%;}
	.signature{position:absolute;bottom:35px;right:8%;}
	</style>';
	$html = '	<h2 class="instname">'.$INSTITUTE_NAME.'</h2>
				<h3 class="instaddress1">'.$ADDRESS_LINE1.' , '.$ADDRESS_LINE2.'</h3>
				<h3 class="instaddress2">'.$CITY_NAME.' - '.$POSTCODE.', '.$STATE_NAME.' (INDIA)</h3>				
				<h4 class="applicant">Applicant : '.$INSTITUTE_OWNER_NAME.' </h4>
				<h5 class="email">Email ID : '.$EMAIL.'</h5>
				
			<!--	<h3 class="certdate">'.$VERIFIED_DATE.'</h3> 
				 <h5 class="validdate">'.$renewline.'</h5> 
			
				<!--<h1 class="atccode">'.$INSTITUTE_CODE.'</h1>-->
				';
	
	
	$html2 .= '<img src="resources/dist/img/democertificateatc.jpg" style="width:100%" />';
	

	$html2 .= $html;
    
	//==============================================================
//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
    $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	$mpdf->Output($file,'I');
	
	
}
?>