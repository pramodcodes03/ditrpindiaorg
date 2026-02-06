<?php
//error_reporting(E_ALL);
ini_set("memory_limit", "128M");
$inst = isset($_REQUEST['inst']) ? $_REQUEST['inst'] : '';

//$inst = array(186);
if ($inst != '' && !empty($inst)) {
	date_default_timezone_set("Asia/Kolkata");
	ob_end_clean();
	include("include/plugins/pdf/mpdf.php");

	include_once('include/classes/institute.class.php');

	include_once('include/classes/tools.class.php');
	$tools = new tools();

	$resB = $tools->list_backgroundimages('', '1', '');
	if ($resB != '') {
		$srno = 1;
		while ($dataB = $resB->fetch_assoc()) {
			//extract($dataB);	
			$imageId = $dataB['inst_id'];
			$performance_image = $dataB['performance_image'];
			$performance_image  = BACKGROUND_IMAGE_PATH . '/' . $imageId . '/' . $performance_image;
		}
	}

	$html = '';

	foreach ($inst as $id) {

		$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 16, 13);
		$institute = new institute();
		$res = $institute->list_institute($id, '');
		$data 	= $res->fetch_assoc();
		$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
		$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
		$REG_DATE 			= $data['REG_DATE'];
		$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
		$INSTITUTE_NAME 	= htmlspecialchars_decode($data['INSTITUTE_NAME']);
		//$INSTITUTE_NAME 	= mb_strtolower(html_entity_decode($INSTITUTE_NAME,ENT_COMPAT|ENT_HTML401,'UTF-8'),'UTF-8' );
		$INSTITUTE_NAME 	= strtoupper($data['INSTITUTE_NAME']);

		$INSTITUTE_OWNER_NAME = htmlspecialchars_decode(strtoupper($data['INSTITUTE_OWNER_NAME']));
		$EMAIL 				= strtolower($data['EMAIL']);
		$MOBILE 			= $data['MOBILE'];

		$VERIFIED 			= $data['VERIFIED'];
		$ADDRESS_LINE1 		= htmlspecialchars_decode(strtoupper(rtrim($data['ADDRESS_LINE1'], ',')));
		//$ADDRESS_LINE2 		= htmlspecialchars_decode(strtoupper($data['ADDRESS_LINE2']));
		$TALUKA 		= htmlspecialchars_decode(strtoupper($data['TALUKA']));
		$CITY_NAME 			= htmlspecialchars_decode(strtoupper($data['CITY_NAME']));
		$STATE_NAME 		= htmlspecialchars_decode(strtoupper($data['STATE_NAME']));
		$POSTCODE 			= $data['POSTCODE'];

		$created_by  = $_SESSION['user_fullname'];
		$ip_address  = $_SESSION['ip_address'];
		$today_date	 = $REG_DATE; //date('d.m.Y');
		$certificate_date = date('Y-m-d');

		$renewline = "Valid upto 31<sup>st</sup> March " . (@date('Y') + 1) . ", subject to renewal.";


		$ownerphoto = $institute->get_institute_docs_all($INSTITUTE_ID, 'owner_photo', false);


		$disp_photo_id = '';
		if (!empty($ownerphoto)) {
			foreach ($ownerphoto as $fileId => $photoidinfo)
				extract($photoidinfo);

			$FILE_NAME = $photoidinfo['file_name'];
			$filePath = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $FILE_NAME;

			$disp_photo_id = '<div id="img-' . $FILE_ID . '">';
			$disp_photo_id .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
			$disp_photo_id .= '<a href="' . $filePath . '" target="_blank"><img src="' . $filePath . '" class="img img-responsive thumbnail" style="height:195px;border:1px solid #fff;" /></a>	';
			$disp_photo_id .= '</div>';
		}


		$html2 = '
<style>
	body {padding:0;font-family: sans-serif; position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instcode{position:absolute;top:605px;text-align:center;width:60%;font-size:12px; left:50.8%; color:#fff;}
	.instowner{position:absolute;top:640px;text-align:center;width:60%; font-size:22px; color:#fff; left:46.7%;}
	.mobile{position:absolute;top:674px;text-align:center;width:60%; font-size:22px; left:52.5%; color:#000;}
	.state{position:absolute;top:363px;text-align:center;width:60%; font-size:14px; color:#fff; left:46.7%;}
	
	.ownerphoto{position:absolute;top:405px;left:65%;width:400px; height:400px;background-size:400px 400px; background-repeat:no-repeat; }

	.instname{position:absolute;top:95px;text-align:center;width:100%;font-size:30px; left:2.7%; color:#da251c; font-weight:900; line-height:34px;}

	</style>';

		$html = '   <div class="ownerphoto">' . $disp_photo_id . '</div>
	                
	                <h2  class="instname"><strong>' . $INSTITUTE_NAME . '</strong></h2>
    	            <h2 class="instcode">' . $INSTITUTE_CODE . '</h2>
    	            <h2 class="instowner"><strong>' . $INSTITUTE_OWNER_NAME . '</strong></h2>
    				<h2 class="mobile">' . $MOBILE . ' </h2>
    				<h2 class="state">' . $STATE_NAME . ' </h2>
				';


		$html2 .= '<img src="' . $performance_image . '" style="width:100%" />';


		$html2 .= $html;

		//==============================================================
		//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'iso-8859-4';

		$mpdf->WriteHTML($html2);
		$mpdf->Output($file, 'I');
	}
} else {
	header('location:page.php?page=list-institutes');
	exit;
}
