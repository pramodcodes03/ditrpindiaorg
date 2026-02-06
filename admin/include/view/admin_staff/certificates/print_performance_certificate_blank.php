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

	$html = '';

	foreach ($inst as $id) {

		$mpdf = new mPDF('c', 'A3-L', '', '', 0, 0, 0, 0, 0, 0);
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
		$filepath	 = SHOW_IMG_AWS . INST_CERTIFICATE_PATH . '/' . $INSTITUTE_ID;
		if (!file_exists($filepath)) {
			@mkdir($filepath, 0777, true);
		}
		$rand = $access->getRandomCode(6);
		$filename = $INSTITUTE_CODE . '_' . $rand;
		$certificatefile = $filename . '.pdf';
		$addressfile = $INSTITUTE_CODE . '_address_' . $rand . '.pdf';
		$file		= $filepath . '/' . $certificatefile;
		$addressPrintFile = $filepath . '/' . $addressfile;
		$renewline = "Valid upto 31<sup>st</sup> March " . (@date('Y') + 1) . ", subject to renewal.";


		$ownerphoto = $institute->get_institute_docs_all($INSTITUTE_ID, 'owner_photo', false);


		$disp_photo_id = '';
		if (!empty($ownerphoto)) {
			foreach ($ownerphoto as $fileId => $photoidinfo)
				extract($photoidinfo);

			$FILE_NAME = $photoidinfo['file_name'];
			$filePath = SHOW_IMG_AWS . INSTITUTE_DOCUMENTS_PATH . $INSTITUTE_ID . '/' . $FILE_NAME;

			$disp_photo_id = '<div id="img-' . $FILE_ID . '">';
			$disp_photo_id .= '<a href="javascript:void(0)" onclick="deleteStudFile(' . $FILE_ID . ')"><i class="fa fa-trash"></i></a>';
			$disp_photo_id .= '<a href="' . $filePath . '" target="_blank"><img src="' . $filePath . '" class="img img-responsive thumbnail" style="height:180px;border:3px solid red;" /></a>	';
			$disp_photo_id .= '</div>';
		}


		$html2 = '
<style>
	body {padding:0;font-family: sans-serif; position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instcode{position:absolute;top:600px;text-align:center;width:60%;font-size:18px;}
	.instname{position:absolute;top:640px;text-align:center;width:60%; font-size:22px; color:red;}
	.instaddress1{position:absolute;top:680px;text-align:center;width:60%; font-size:16px; }
	.instaddress3{position:absolute;top:720px;text-align:center;width:60%; font-size:16px; }
	.instaddress2{position:absolute;top:760px;text-align:center;width:60%; font-size:16px; }
	
	.ownerphoto{position:absolute;top:420px;left:26%;width:450px; height:450px;background-size:450px 450px; background-repeat:no-repeat; }

	</style>';

		$html = '   <div class="ownerphoto">' . $disp_photo_id . '</div>
	                
    	            <h2 class="instcode"> ATC CODE: ' . $INSTITUTE_CODE . '</h2>
    	            <h2 class="instname">' . $INSTITUTE_NAME . '</h2>
    				<h3 class="instaddress1">' . $ADDRESS_LINE1 . ' </h3>
    				<h3 class="instaddress3">Taluka: ' . $TALUKA . ' </h3>
    				<h3 class="instaddress2">' . $CITY_NAME . ' - ' . $POSTCODE . ', (' . $STATE_NAME . ')</h3>
			
				';


		/*$html2 .= '<img src="resources/dist/performance_cert/p_cert.jpg" style="width:100%" />';
	*/

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
