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
		$mpdf = new mPDF('c', 'A4', '', '', 10, 0, 10, 0, 16, 13);
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

		$rand = $access->getRandomCode(6);
		$filename = $INSTITUTE_CODE . '_' . $rand;
		$addressPrintFile = $filename . '.pdf';

		//==============================================================


		$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:470px;text-align:center;width:100%;}
	h1,h2,h3,h4,h5,h6{line-height: 10px; width:100%}
	.city_name{font-size:1.1em;}
	
	</style>';

		$TALUKA = ($TALUKA != '') ? "<h4>Taluka: $TALUKA</h4>" : '';

		$html .= '<h4>To,</h4>
			 <h3>ATC Code: ' . $INSTITUTE_CODE . '</h3>
			 <h2>' . $INSTITUTE_NAME . '</h2>
			 <h4>[Kind Attn.: ' . $INSTITUTE_OWNER_NAME . ', Mobile: ' . $MOBILE . ']</h4>
			 <h4>' . $ADDRESS_LINE1 . '</h4>
			 <!--<h4>' . $ADDRESS_LINE2 . '</h4>
			 <h4>' . $ADDRESS_LINE2 . '</h4>-->
			 ' . $TALUKA . '
			 <h4><span class="city_name">' . $CITY_NAME . '</span> - ' . $POSTCODE . ' (' . $STATE_NAME . ')</h4>
			';
		$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in = 'iso-8859-4';

		$mpdf->WriteHTML($html);
		$mpdf->Output($addressPrintFile, 'I');


		//	ob_end_flush();

	}
} else {
	header('location:page.php?page=list-institutes');
	exit;
}
