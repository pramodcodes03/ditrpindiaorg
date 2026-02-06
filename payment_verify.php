<!doctype html>
<html lang="en">
<?php 
	$page = isset($_GET['pg'])?$_GET['pg']:'home';
	include('include/common/html_header.php'); 
	//include('include/common/header.php');
?>

<?php
$data=array();
//print_r($_GET); exit();
$action= isset($_GET['verify_student'])?$_GET['verify_student']:'';
$success='';
if($action!='')
{
    $success=false;
    
    $receipt_code =$db->test(isset($_GET['code'])?$_GET['code']:'');
    if( $receipt_code!=''){
	$sql = "SELECT A.*,get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME,get_stud_photo(A.STUDENT_ID) AS STUD_PHOTO FROM student_payments A  WHERE A.RECIEPT_NO='$receipt_code' AND A.DELETE_FLAG=0 ORDER BY A.PAYMENT_ID DESC LIMIT 0,1";
	$res = $db->execQuery($sql);
	
	if($res && $res->num_rows>0)
	{
	    $success=true;
		while($data = $res->fetch_assoc())
		{
            extract($data);
            //print_r($data);
			$photo = HTTP_HOST."/resources/img/teacher_2_small.jpg";
			if($STUD_PHOTO!='')
				$photo = "uploads/student/".$STUDENT_ID.'/'.$STUD_PHOTO;

			$sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.TYPING_COURSE_ID FROM institute_courses A WHERE A.DELETE_FLAG=0 AND A.INSTITUTE_COURSE_ID = $INSTITUTE_COURSE_ID";
			//echo $sql;
			$ex = $db->execQuery($sql);
			if($ex && $ex->num_rows>0)
			{
				while($data = $ex->fetch_assoc())
				{
					$COURSE_ID 			    = $data['COURSE_ID'];
					$MULTI_SUB_COURSE_ID    = $data['MULTI_SUB_COURSE_ID'];
					$instituteCourseId      = $data['INSTITUTE_COURSE_ID'];
					$TYPING_COURSE_ID 	 = $data['TYPING_COURSE_ID'];

					if($COURSE_ID!='' && !empty($COURSE_ID) && $COURSE_ID!='0'){													
						$course_data = $db->get_course_detail($COURSE_ID);
						
						$course_name 	= $course_data['COURSE_NAME_MODIFY'];
						$c_id 	= $course_data['COURSE_ID'];

						$course_code	    = $course_data['COURSE_CODE'];
						$course_duration 	= $course_data['COURSE_DURATION'];
						$course_details 	= $course_data['COURSE_DETAILS'];
						$course_eligibility	= $course_data['COURSE_ELIGIBILITY'];
						$course_fees	    = $course_data['COURSE_FEES'];
						$course_mrp	        = $course_data['COURSE_MRP'];
						$course_minamount 	= $course_data['MINIMUM_AMOUNT'];
						$course_image	    = $course_data['COURSE_IMAGE'];

						$path = COURSE_MATERIAL_PATH.'/'.$COURSE_ID.'/'.$course_image;
						
					}

					if($MULTI_SUB_COURSE_ID!='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!='0')
					{											
						$course_data = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						
						$course_name 	= $course_data['COURSE_NAME_MODIFY'];
						$m_id 	        = $course_data['MULTI_SUB_COURSE_ID'];
						$course_code	    = $course_data['MULTI_SUB_COURSE_CODE'];
						$course_duration 	= $course_data['MULTI_SUB_COURSE_DURATION'];
						$course_details 	= $course_data['MULTI_SUB_COURSE_DETAILS'];
						$course_eligibility	= $course_data['MULTI_SUB_COURSE_ELIGIBILITY'];
						$course_fees	    = $course_data['MULTI_SUB_COURSE_FEES'];
						$course_mrp	        = $course_data['MULTI_SUB_COURSE_MRP'];
						$course_minamount 	= $course_data['MULTI_SUB_MINIMUM_AMOUNT'];
						$course_image	    = $course_data['MULTI_SUB_COURSE_IMAGE'];

						$path = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$MULTI_SUB_COURSE_ID.'/'.$course_image;
						
					} 
					if($TYPING_COURSE_ID!='' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID!='0')
					{											
						$course_data = $db->get_course_detail_typing($TYPING_COURSE_ID);
						
						$course_name 	= $course_data['COURSE_NAME_MODIFY'];
						$m_id 	        = $course_data['TYPING_COURSE_ID'];
						$course_code	    = $course_data['TYPING_COURSE_CODE'];
						$course_duration 	= $course_data['TYPING_COURSE_DURATION'];
						$course_details 	= $course_data['TYPING_COURSE_DETAILS'];
						$course_eligibility	= $course_data['TYPING_COURSE_ELIGIBILITY'];
						$course_fees	    = $course_data['TYPING_COURSE_FEES'];
						$course_mrp	        = $course_data['TYPING_COURSE_MRP'];
						$course_minamount 	= $course_data['TYPING_MINIMUM_AMOUNT'];
						$course_image	    = $course_data['TYPING_COURSE_IMAGE'];

						$path = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$TYPING_COURSE_ID.'/'.$course_image;
						
					} 
				}
			}
				
			/*if($STUDENT_PHOTO!='')
				$photo = HTTP_HOST."/uploads/certificates/photos/$STUDENT_PHOTO";*/
	
		}
	}
    }
}

?>

<div class="rs-mission sec-color sec-spacer pt-70">
	<div class="container">
	<div class="row">
	<div class="col-sm-12 fverify">
		<?php
			if($success==true)
			{
		?>
		<div class="abt-title">
			<h2>Student Payment Details</h2>
		</div> 
		<div class="table-responsive">
			<table class="table table-bordered table-responsive">
				<tr>
					<th>Photo</th>
					<td><img src="<?= $photo ?>" alt="Student Photo" style="max-height:200px;margin:auto;" class="img thumbnail styled"></td>   
				</tr>            
				<tr>
					<th>Name of Student </th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_FULLNAME ?></td>
				</tr>
				<tr>
					<th>Course Name </th>
					<!-- <th>:</th> -->
					<td><?= $course_name ?></td>
				</tr>
				<tr>
					<th>Course Fees</th>
					<!-- <th>:</th> -->
					<td><?= $COURSE_FEES ?> </td>
				</tr>
				<tr>
					<th>Fees Paid</th>
					<!-- <th>:</th> -->
					<td><?= $FEES_PAID ?> </td>
				</tr>
				<tr>
					<th>Fees Balance</th>
					<!-- <th>:</th> -->
					<td><?= $FEES_BALANCE ?></td>
				</tr>				
			</table>
		</div>
		<?php }elseif($success==false){ ?>
			<div class="alert alert-danger">
			<p><strong>Sorry! </strong>
			No records for this QR.
			</p>
			</div>
		<?php } ?>
		</div>
	</div>
	</div>
</div>

<script type="text/javascript">
	var myForm = document.getElementById('certVerifyForm');
    myForm.onsubmit = function() {
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
    this.target = 'Popup_Window';
};
</script>