<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

if(!isset($_SESSION)) session_start();
//include connections
$payid = isset($_GET['payid'])?$_GET['payid']:'';
include_once($_SERVER['DOCUMENT_ROOT'].'instituteManagement/admin/include/classes/config.php');
$INST_LOGO = ROOT.'/resources/dist/img/logo_pdf.png';
$institute_name = 'Institute Name';
$institute_address = 'Institute Address 123455678';

include_once($_SERVER['DOCUMENT_ROOT'].'instituteManagement/admin/include/classes/database_results.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'instituteManagement/admin/include/classes/access.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'instituteManagement/admin/include/classes/institute.class.php');

$db 	= new  database_results();
$access = new  access();
$institute = new  institute();

include_once($_SERVER['DOCUMENT_ROOT'].'instituteManagement/admin/include/classes/tools.class.php');
$tools = new tools(); 

$resB = $tools->list_backgroundimages('1','');
if($resB!='')
{
  $srno=1;
  while($dataB = $resB->fetch_assoc())
  {
	extract($dataB);		
	$feesreceipt_image     = BACKGROUND_IMAGE_PATH.'/'.$id.'/'.$feesreceipt_image;
  }
}



$sql = "SELECT *, get_student_name(STUDENT_ID) as STUDENT_NAME, get_institute_name(INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_staff_name(STAFF_ID) AS STAFF_NAME FROM student_payments WHERE PAYMENT_ID='$payid'";
$res = $db->execQuery($sql);
if($res && $res->num_rows>0)
{
	while($data = $res->fetch_assoc())
	{
		extract($data);
		
		$INSTITUTE_COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
		$RECIEPT_DATE = date('d-m-Y');
		$instinfo	= $institute->list_institute($INSTITUTE_ID,'');
		if($instinfo!='')
		{
			$instdata = $instinfo->fetch_assoc();			
			$INSTITUTE_ADDRESS1 = $instdata['ADDRESS_LINE1'];
			$INSTITUTE_MOBILE = $instdata['MOBILE'];
			$INSTITUTE_EMAIL = $instdata['EMAIL'];
			$insfile = $institute->get_institute_docs_all($INSTITUTE_ID, 'logo', false);
			$INST_LOGO = isset($insfile['file_name'])?$insfile['file_name']:'';
			$INST_LOGO = ROOT.'/uploads/institute/docs/'.$INSTITUTE_ID.'/thumb/'.$INST_LOGO;			
					
		}
		
		
	}
}
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		/*$institute_logo = ROOT.'/resources/dist/img/logo_pdf.png';
		$institute_name = 'Institute Name';	
		$institute_address = 'Institute Address 123455678';
		$image_file = $institute_logo;
		$this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 15);
		// Title
		$this->Cell(0, 15, $institute_name, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		// Set font
		$this->SetFont('helvetica', '', 8);
		$this->Cell(0, 0, $institute_address, 0, 0, 'C', 0, '', 0, false, 'M', 'M');*/
		
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $institute_name, $institute_address);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage();



// -----------------------------------------------------------------------------
$reciept_tpl = '';

$tbl0 = <<<EOD
	<table style="border:1px solid #000">
		<tr>
			<td align="center" width="30%"><img src="$INST_LOGO" style="width:50px; height:50px" /></td>			
			<td align="left" width="40%"><h3>$INSTITUTE_NAME</h3> <h5>$INSTITUTE_ADDRESS1</h5>
			
			</td>	
			<td width="30%"><h6>Mobile: $INSTITUTE_MOBILE</h6><h6> Email:$INSTITUTE_EMAIL</h6></td>			
		</tr>
	</table>
EOD;
$tbl1 = $tbl0;

$tbl1 .= <<<EOD
	<table style="border-left:1px solid #000;border-right:1px solid #000">
		<tr>
			<td><strong>Reciept No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong> $RECIEPT_NO</td>			
			<td align="right"><strong>Date:</strong> $RECIEPT_DATE</td>			
		</tr>
	</table>
EOD;

$tbl2 = <<<EOD
	<table style="border-bottom:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">
		<tr>
			<td><strong>Student Name &nbsp;:</strong> $STUDENT_NAME</td>	
		</tr>
		<tr>
			<td><strong>Course Name &nbsp;&nbsp;:</strong> $INSTITUTE_COURSE_NAME</td>	
		</tr>
	</table>
EOD;

$tbl3 = <<<EOD
<br><br>
<table cellspacing="0" cellpadding="5" border="1">
    <thead>
	<tr  style="font-weight:bold">
		<th width="10%" align="center">Sr No.</th>
		<th width="60%" align="center">Perticulars</th>
		<th width="30%" align="center">Amount</th>
	</tr>
	</thead>
	<tbody>
	<tr>
        <td width="10%">1</td>
        <td width="60%">Fees Paid</td>
        <td width="30%" align="right">$FEES_PAID</td>
    </tr>
   </tbody>
   <tfoot>
	<tr style="font-weight:bold">
		<td colspan="2" align="right">Total</td>
		<td align="right">$FEES_PAID</td>
	</tr>
   
   </tfoot>
</table>
EOD;

$tbl4 = <<<EOD
<br><br>
	<table>
		<tr>
			<td align="right"><strong>Reciever's Signature</strong></td>	
		</tr>
		<tr>
			<td align="right">__________________</td>	
		</tr>
	</table>
EOD;
$pdf->writeHTML($tbl1.$tbl2.$tbl3.$tbl4, true, false, false, false, '');



// -----------------------------------------------------------------------------

//Close and output PDF document
ob_end_clean();
$pdf->Output('example_048.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
