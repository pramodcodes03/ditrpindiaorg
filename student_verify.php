<!doctype html>
<html lang="en">
<?php 
$page = isset($_GET['pg'])?$_GET['pg']:'home';
include('include/common/html_header.php'); 
?>

<?php
$data=array();
//print_r($_GET); exit();
$action= isset($_GET['verify_student'])?$_GET['verify_student']:'';
$success='';
if($action!='')
{
    $success=false;
    
    $student_code =$db->test(isset($_GET['code'])?$_GET['code']:'');
    if( $student_code!=''){
	$sql = "SELECT A.*,get_student_name(A.STUDENT_ID) AS STUDENT_FULLNAME,get_stud_photo(A.STUDENT_ID) AS STUD_PHOTO,get_institute_email(A.INSTITUTE_ID) as institute_email,get_institute_mobile(A.INSTITUTE_ID) as institutemobile,  get_institute_address(A.INSTITUTE_ID) as instituteaddress, get_institute_city(A.INSTITUTE_ID) as institutecity,get_institute_state(A.INSTITUTE_ID) as institutestate FROM student_details A  WHERE A.STUDENT_CODE='$student_code' AND A.DELETE_FLAG=0 ORDER BY A.STUDENT_ID DESC LIMIT 0,1";
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
				
			$state_name= $db->get_state_name($STUDENT_STATE);
			/*if($STUDENT_PHOTO!='')
				$photo = HTTP_HOST."/uploads/certificates/photos/$STUDENT_PHOTO";*/

			include_once('include/classes/student.class.php');
			$student = new student();
			$course_id 	 =  $db->get_institutecourse_id($STUDENT_ID);
			$course_name = $db->get_inst_course_name($course_id);
          	$INSTITUTE_NAME = $db->get_institute_name($INSTITUTE_ID);
	
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
			<h2>Student Details</h2>
		</div> 
		<div class="table-responsive">
			<table class="table table-bordered table-responsive">
				<tr>
					<th>Photo</th>
					<td><img src="<?= $photo ?>" alt="Student Photo" style="max-height:200px;margin:auto;" class="img thumbnail styled"></td>   
				</tr> 
				<tr>
					<th>Course Name</th>
					<!-- <th>:</th> -->
					<td><?= $course_name ?></td>
				</tr>            
				<tr>
					<th>Student Name</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_FULLNAME ?></td>
				</tr>
				<tr>
					<th>Father Name</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_MNAME ?> <?= $STUDENT_LNAME ?></td>
				</tr>
				<tr>
					<th>Mother Name</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_MOTHERNAME ?></td>
				</tr>
							
				<tr>
					<th>Email Id</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_EMAIL ?></td>
				</tr>
				<tr>
					<th>Contact Number</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_MOBILE ?></td>
				</tr>
				<tr>
					<th>Address</th>
					<!-- <th>:</th> -->
					<td><?= $STUDENT_PER_ADD ?> <?= $STUDENT_CITY ?> <?= $state_name ?> <?= $STUDENT_PINCODE ?></td>
				</tr>
              
              	<tr>
					<th>Name Of Institute</th>
					<!-- <th>:</th> -->
					<td><?= $INSTITUTE_NAME ?></td>
				</tr>
              	<tr>
					<th>Institute Email</th>
					<!-- <th>:</th> -->
					<td><?= $institute_email ?></td>
				</tr>
              	<tr>
					<th>Institute Contact</th>
					<!-- <th>:</th> -->
					<td><?= $institutemobile ?></td>
				</tr>
              	<tr>
					<th>Institute Address</th>
					<!-- <th>:</th> -->
					<td><?= $instituteaddress ?></td>
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