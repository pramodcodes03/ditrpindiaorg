<?php
//error_reporting(E_ALL);
ini_set("memory_limit","128M");
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");	
	ob_clean(); 
  
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
	    $atccert_image = $dataB['atccert_image'];
	    $atccert_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$atccert_image;
      }
    }
		
	$html='';	

	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 

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
	</style>';
	$html = '	<h2 class="instname">NEXSTEP COMPUTER ACADEMY </h2>
				<h3 class="instaddress1">New Rushikesh Apartment, Near Cricket Ground, Virat Nagar, Virar West</h3>
				<h3 class="instaddress2">Palghar, Mumbai, 401303, Maharashtra (INDIA)</h3>				
				<h4 class="applicant">Applicant Name : Amzad Sathe </h4>
				<h4 class="email">ATC CODE: MH/K863 </h4>				
				<div class="foot">
					<table>
						<tr>
							<th align="left">ATC CODE</th>
							<th>:</th>
							<th align="left">MH/K863</th>
						</tr>
						<tr>
							<th align="left">Date Of Issue</th>
							<th>:</th>
							<th align="left">10-08-2023</th>
						</tr>
						<tr>
							<th align="left">Date Of Renewal</th>
							<th>:</th>
							<th align="left">10-08-2024</th>
						</tr>
					</table>
				</div>
				';
	$html2 .= '<img src="'.$atccert_image.'" style="width:100%" />'.$html;
    $html1 .= $html2; 
	//==============================================================
    $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
    $mpdf->charset_in = 'iso-8859-4';
	$mpdf->WriteHTML($html1);
	$mpdf->Output('Franchise_Certificate','I');	
?>