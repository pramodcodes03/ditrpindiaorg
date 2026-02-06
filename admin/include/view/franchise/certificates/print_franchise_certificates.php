<?php
ob_clean();
ob_start();
//error_reporting(E_ALL);
//ini_set("memory_limit","128M");
$inst = isset($_GET['inst'])?$_GET['inst']:'';

if($inst!='' && !empty($inst))
{
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	//ob_clean(); 
  	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
  
	include_once('include/classes/institute.class.php');
  
  	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('','1','');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
	    //extract($dataB);	
	    $imageId = $dataB['inst_id'];
	    $atccert_image = $dataB['atccert_image'];
	    $atccert_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$atccert_image;
      }
    }
		
	$html='';	
	

	$institute = new institute();
	$res = $institute->list_institute($inst," AND B.USER_ROLE='8'");
	$data 	= $res->fetch_assoc();	
	//print_r($data); exit();
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
	//$ADDRESS_LINE2 		= htmlspecialchars_decode(strtoupper($data['ADDRESS_LINE2']));
	$CITY 				= htmlspecialchars_decode(strtoupper($data['CITY']));
	$STATE_NAME 		= htmlspecialchars_decode(strtoupper($data['STATE_NAME']));
	$POSTCODE 			= $data['POSTCODE'];
    $QRFILE 			= $data['QRFILE'];
    
    //QR Code  
    $file1 = "";
	if($QRFILE !== ''){
	    $file1 =  HTTP_HOST.$QRFILE;
	}
	
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];
	$today_date	 = $REG_DATE; //date('d.m.Y');
	$certificate_date = date('Y-m-d');	
      
	$auth_signature='';
	if(file_exists('resources/dist/img/signaturesharad.png'))
	$auth_signature = '<img src="resources/dist/img/signaturesharad.png" style="width:200px; height:130px;"/>';
	$html1 = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:480px;text-align:center;width:100%;}
	.instaddress1{position:absolute;top:510px;text-align:center;width:100%; font-size:10px; }
	.instaddress2{position:absolute;top:525px;text-align:center;width:100%; font-size:10px; }
	.applicant{position:absolute;top:545px;text-align:center;width:100%;}
	.email{position:absolute;top:565px;text-align:center;width:100%;}
	.certdate{position:absolute;bottom:210px;left:28%;}
	.validdate{position:absolute;bottom:185px;left:7%; font-weight:400;}
	.atccode{position:absolute;bottom:57px;left:46%;}
	.signature{position:absolute;bottom:18px;right:7%;}
	.foot{position:absolute;bottom:155px;right:40%;}
	.qrheadtext{position:absolute;top:30.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:35.1%;width:85px; height:85px; text-align:center; float:right; left:75%;}
	
	</style>';
	$html2 = '
<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:480px;text-align:center;width:100%;}
	.instaddress1{position:absolute;top:510px;text-align:center;width:100%; font-size:10px; }
	.instaddress2{position:absolute;top:525px;text-align:center;width:100%; font-size:10px; }
	.applicant{position:absolute;top:545px;text-align:center;width:100%;}
	.email{position:absolute;top:560px;text-align:center;width:100%;}
	.certdate{position:absolute;bottom:230px;left:28%;}
	.validdate{position:absolute;bottom:202px;left:7%;}
	.atccode{position:absolute;bottom:80px;left:46%;}
	.signature{position:absolute;bottom:35px;right:8%;}
	.qrheadtext{position:absolute;top:30.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:35.1%;width:85px; height:85px; left:80%;}
    
	</style>';
	 $html = '	<div class="qrcodeimage"><img src="'.$file1.'"></div> 
    			<h2 class="instname">'.$INSTITUTE_NAME.'</h2>
				<h3 class="instaddress1">'.$ADDRESS_LINE1.'</h3>
				<h3 class="instaddress2">'.$CITY.' - '.$POSTCODE.', '.$STATE_NAME.' (INDIA)</h3>				
				<h4 class="applicant">Applicant Name : '.$INSTITUTE_OWNER_NAME.' </h4>
				<h4 class="email">ATC CODE: '.$INSTITUTE_CODE.'</h4>				
				<div class="foot">
					<table>
						<tr>
							<th align="left">ATC CODE</th>
							<th>:</th>
							<th align="left">'.$INSTITUTE_CODE.'</th>
						</tr>
						<tr>
							<th align="left">Date Of Issue</th>
							<th>:</th>
							<th align="left">'.date('d-m-Y',strtotime($VERIFIED_DATE)).'</th>
						</tr>
						<tr>
							<th align="left">Date Of Renewal</th>
							<th>:</th>
							<th align="left">'.date('d-m', strtotime($VERIFIED_DATE)).'-'.(@date('Y')+1).'</th>
						</tr>
					</table>
				</div>
				'; 
	$html2 .= '<img src="'.$atccert_image.'" style="width:100%" />'.$html;
    $html1 .= $html2; 
	//==============================================================
	 $mpdf->WriteHTML($html1);
	 $mpdf->Output('Franchise_Certificate.pdf', 'I');
	
	}
	

ob_flush();
ob_end_flush();
?>