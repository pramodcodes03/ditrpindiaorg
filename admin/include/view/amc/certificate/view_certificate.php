<?php
ob_clean();
    $amc_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'';
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	$html='';	
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	//$id = base64_decode($id);
	include_once('include/classes/amc.class.php');
	$amc = new amc();
    $res = $amc->list_amc($amc_id,'');
	//$res 	= $exam->list_certificates_requests($id, '', '', '');
	$data 	= $res->fetch_assoc();
	extract($data);
    //print_r($data);exit();
	//$oneYearOn = date('Y-m-d',strtotime($REG_DATE("Y-m-d", mktime()) . " + 365 day"));
    $newEndingDate=date('d-m-Y', strtotime('+1 year', strtotime($REG_DATE)) );
	//==============================================================

	// $issue=date_format($CREATED_ON,"Y/m/d");
	
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	

	
	.idproof{position:absolute;top:620px;text-align:center;width:100%;font-weight:normal}
	
	.atcname{position:absolute;top:440px;width:100%; font-size:24px;text-transform: uppercase; text-align:center;}
	.grade{position:absolute;top:473px;width:60%;font-size:14px;text-transform: uppercase;left:160px; line-height:15px;text-align:center; }
	.grade1{position:absolute;top:490px;width:100%;font-size:14px;text-transform: uppercase;left:160px;}
	.pin{position:absolute;top:505px;text-align:center;width:100%;font-size:14px; text-transform: uppercase;}
	.studname{position:absolute;top:522px;text-align:center;width:100%;font-size:14px; text-transform: uppercase;}
	.amcarea{position:absolute;top:540px;text-align:center;width:100%;font-size:14px; width:50%;left:180px;}
	
	.marks{	position:absolute;top:710px;left:71%; width:100px;}
	.coursename{position:absolute;top:775px;text-align:center;width:100%; font-size:16px;}
	.courseduration{position:absolute;top:800px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:250px;left:40%;}
	.issue{position:absolute;	bottom:230px;left:38%;}
	.date{position:absolute;bottom:210px;left:38%;}
	.foot{position:absolute;bottom:205px;right:40%;}
	</style>';
	$html .= '
            <img src="resources/dist/img/AMC SAMPLE F.JPG" style="width:100%" />
		    <h3 class="atcname"><b>'.$AMC_COMPANY_NAME.'</b></h3>
		    <h2 class="grade">'.$ADDRESS_LINE1.'</h2>
		    <!--<h2 class="grade1">'.$ADDRESS_LINE2.' </h2>-->
			<h2 class="pin"> PIN: '.$POSTCODE.','.$STATE_NAME.'-'.$CITY_NAME.'</h2>
			<h2 class="studname"> Applicant Name : '.$AMC_NAME.'</h2>
			<h2 class="amcarea"> AMC AREA: '.$DETAIL_DESCRIPTION.'</h2>
			<div class="foot">
				<table>
					<tr>
						<th align="left">ATC CODE</th>
						<th>:</th>
						<th align="left">'.$AMC_CODE.'</th>
					</tr>
					<tr>
						<th align="left">Date Of Issue</th>
						<th>:</th>
						<th align="left">'.$REG_DATE.'</th>
					</tr>
					<tr>
						<th align="left">Date Of Renewal</th>
						<th>:</th>
						<th align="left">'.$newEndingDate.'</th>
					</tr>
				</table>
			</div>';
//==============================================================
$mpdf->WriteHTML($html);
$mpdf->Output($file,'I');
//send sms to student
ob_end_flush();
?>