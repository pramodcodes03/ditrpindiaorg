<style>
	table tr th, table tr td{
		text-align:left;
	}	
</style>

<?php
ini_set("memory_limit","128M");
$student_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'';
if($student_id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	include_once('include/classes/student.class.php');
	$student 	= new  student();
	
	$today_date		= date('d-m-Y');
	$filepath		= STUDENT_DOCUMENTS_PATH.'/'.$student_id;
	if (!file_exists($filepath)) {
		@mkdir($filepath, 0777, true);
	}
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];
	$rand = $access->getRandomCode(6);
	$filename = 'resume';
	$resumefile = $filename.'.pdf';	
	$file		= $filepath.'/'.$resumefile;
	
	$sql = "SELECT * FROM student_files WHERE  STUDENT_ID='$student_id' AND FILE_LABEL='resume'";
	$res = $db->execQuery($sql);
	if($res && $res->num_rows>0)
	{
		while($data = $res->fetch_assoc())
		{
			$resume = $filepath.'/'.$FILE_NAME;
			unlink($resume);
		}
	}
	
	$sql = "DELETE FROM student_files WHERE STUDENT_ID='$student_id' AND FILE_LABEL='resume'";
	$res = $db->execQuery($sql);
	
	$sql = "INSERT INTO student_files (STUDENT_ID,FILE_NAME,FILE_MIME,FILE_LABEL,CREATED_BY,CREATED_ON,CREATED_ON_IP) VALUES('$student_id','$resumefile','pdf', 'resume','$created_by',NOW(),'$ip_address')";
	$res = $db->execQuery($sql);
	
	
	$detail = $student->list_student($student_id, '', '');
	$detail_html='';
	if($detail!='')
	{
		$detail_html .= '<h5 style="border:2px solid #ccc; background-color:#ccc; padding:5px; margin-bottom:5px;"><strong>Personal Details</strong></h5>
			<table class="table table-bordered" style="width:100%; margin: 25px 0px;">';
		while($data = $detail->fetch_assoc())
		{
			$STUDENT_FULLNAME		= $data['STUDENT_FULLNAME'];
			$STUDENT_FNAME 			= $data['STUDENT_FNAME'];
			$STUDENT_MNAME 			= $data['STUDENT_MNAME'];
			$STUDENT_LNAME 			= $data['STUDENT_LNAME'];
			$STUD_DOB_FORMATED 		= $data['STUD_DOB_FORMATED'];
			$STUDENT_GENDER 		= ucwords($data['STUDENT_GENDER']);
			$STUDENT_MOBILE 		= $data['STUDENT_MOBILE'];
			$STUDENT_EMAIL 			= $data['STUDENT_EMAIL'];
			$STUDENT_PER_ADD 		= $data['STUDENT_PER_ADD'];
			$STUDENT_PINCODE 		= $data['STUDENT_PINCODE'];
			$INTERESTS 				= $data['INTERESTS'];
			$STUDENT_CITY 			= $data['STUDENT_CITY'];
			
			$detail_html .= '<tr>
								<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Fullname</th>
								<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$STUDENT_FULLNAME.'</td>								
							</tr>
							<tr>
								<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Date Of Birth</th>
								<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$STUD_DOB_FORMATED.'</td>								
							</tr>
							<tr>
								<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Gender</th>
								<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$STUDENT_GENDER.'</td>								
							</tr>
							<tr>
								<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Address</th>
								<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$STUDENT_PER_ADD.'</td>								
							</tr>';
		}
		$detail_html .= '</table>';
	}
	$edu 	= $student->list_student_educational_info('',$student_id);
	$edu_html = '';
	if($edu!='')
	{
		$edu_html .= '
		<h5 style="border:1px solid #ccc; background-color:#ccc; padding:5px; margin-bottom:5px;"><strong>Educational Info</strong></h5>	
		<table class="table table-bordered" style="width:100%">
				<tr>
					<th width="15%" style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Course</th>
					<th width="30%" style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Board / University</th>
					<th width="30%" style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">School / Institute</th>
					<th width="15%" style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Year</th>
					<th width="10%" style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Marks (%)</th>
				</tr>';
		while($data = $edu->fetch_assoc())
		{
			$COURSE_NAME 			= $data['COURSE_NAME'];
			$INSTITUTE_NAME 		= $data['INSTITUTE_NAME'];
			$UNIVERSITY_NAME 		= $data['UNIVERSITY_NAME'];
			$START_DATE_FORMATTED 	= $data['START_DATE_FORMATTED'];
			$END_DATE_FORMATTED 	= $data['END_DATE_FORMATTED'];
			$START_DATE_YEAR 		= $data['START_DATE_YEAR'];
			$END_DATE_YEAR 			= $data['END_DATE_YEAR'];
			$MARKS 					= $data['MARKS'];
			$DESCRIPTION 			= $data['DESCRIPTION'];
			if($COURSE_NAME!='')
			{
				$edu_html .= '
				<tr>
					<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$COURSE_NAME.'</td>
					<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$UNIVERSITY_NAME.'</td>
					<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$INSTITUTE_NAME.'</td>
					<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$START_DATE_YEAR.' - '.$END_DATE_YEAR.'</td>
					<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$MARKS.'</td>
				</tr>';
			}
		}
		$edu_html .= '</table>';
	}
	$exp 	= $student->list_student_experience_info('',$student_id);
	$exp_html = '';
	if($exp!='')
	{
		$exp_html .='
		<h5 style="border:1px solid #ccc; background-color:#ccc; padding:5px; margin-bottom:5px;"><strong>Work Experience</strong></h5>
						
				';
		while($data = $exp->fetch_assoc())
		{
			$JOB_TITLE				= $data['JOB_TITLE'];
			$COMPANY_NAME			= $data['COMPANY_NAME'];
			$START_DATE_FORMATTED	= $data['START_DATE_FORMATTED'];
			$END_DATE_FORMATTED		= $data['END_DATE_FORMATTED'];
			$DESCRIPTION			= $data['DESCRIPTION'];
			if($JOB_TITLE!='')
			{
			$exp_html .='<table class="table table-bordered">					
					<tr>
						<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Job Title</th>
						<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$JOB_TITLE.'</td>								
					</tr>
					<tr>
						<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Company Name</th>
						<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$COMPANY_NAME.'</td>								
					</tr>
					<tr>
						<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Date From</th>
						<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$START_DATE_FORMATTED.'</td>								
					</tr>
					<tr>
						<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Date To</th>
						<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$END_DATE_FORMATTED.'</td>								
					</tr>
					<tr>
						<th style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">Job Description</th>
						<td style="padding: 15px;text-align: left;    border: 2px solid #7a7a7a;">'.$DESCRIPTION.'</td>								
					</tr>
					</table>
					';
			}
		}
		$exp_html .= '';
	}
	
	$html= '';
	
	$html .=  '	 
	<div class="container">
		
			<table>
				<tr>
					<td>
						<table class="table">
							<tr><td><h4><strong>'.$STUDENT_FNAME.' '.$STUDENT_LNAME.'</strong></h4></td></tr>
							<tr><td><strong>Mobile:</strong> '.$STUDENT_MOBILE.'</td></tr>
							<tr><td><strong>Email:</strong>  '.$STUDENT_EMAIL.'</td></tr>
						</table>
					</td>
					
				</tr>
			</table>			
			<div style="width:100%; border:1px solid #000; margin-bottom:40px"></div>			
			<h5 style="border:1px solid #ccc; background-color:#ccc; padding:5px; margin-bottom:5px;"><strong>Objective</strong></h5>
			<table class="table">				
				<tr><td>I am seeking employment with a company where I can use my talents and skills to grow and expand the company.</td></tr>
			</table>
			
			'.$detail_html.'
			'.$edu_html.'
			'.$exp_html.'
			
			<h5 style="border:1px solid #ccc; background-color:#ccc; padding:5px; margin-bottom:5px;"><strong>Declaration</strong></h5>
			<table class="table">
				<tr><td>I hereby declare that all the information mentioned above are true to the best of my knowledge. </td></tr>
			</table>	
			<table class="table">
				<tr>
					<td>
						<table class="table">
							<tr><td> <Strong>Date: </strong> '.$today_date.'</td></tr>
							<tr><td> <Strong>Place: </strong> '.$STUDENT_CITY.'</td></tr>
						</table>
					</td>
					<td align="right">
						<table class="table">
							<tr><td> <Strong>'.$STUDENT_FNAME.' '.$STUDENT_LNAME.'</strong></td></tr>
							<tr><td>____________________________</td></tr>
						</table>
					</td>
				</tr>
				
			</table>	
	</div>
	';
	
	
	//echo $html; exit();
	
//==============================================================
//==============================================================
//==============================================================
include("include/plugins/pdf/mpdf.php");

/* --- Print question sheet ------------------- */

$mpdf=new mPDF('c','A4','','',15,15,15,5,16,13); 
// LOAD a stylesheet
$stylesheet = file_get_contents('resources/bootstrap/css/bootstrap.css');
$mpdf->WriteHTML($stylesheet,1);// The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($html,2);
$mpdf->Output($file,'F');



header('location:page.php?page=resume.php?r='.base64_encode($file));

exit;
//==============================================================
//==============================================================
//==============================================================
}

?>