<?php
$data = array();

$action = isset($_POST['verify_student']) ? $_POST['verify_student'] : '';
$success = '';
if ($action != '') {
	$success = false;

	$student_code = $db->test(isset($_POST['code']) ? $_POST['code'] : '');
	if ($student_code != '') {
		$sql = "SELECT *,get_institute_email(A.INSTITUTE_ID) as institute_email,get_institute_mobile(A.INSTITUTE_ID) as institutemobile,  get_institute_address(A.INSTITUTE_ID) as instituteaddress, (SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) as institutecity,get_institute_state(A.INSTITUTE_ID) as institutestate,get_stud_photo(A.STUDENT_ID) AS STUD_PHOTO,get_course_title_modify(A.AICPE_COURSE_ID) AS COURSE_NAME,get_course_multi_sub_title_modify(A.MULTI_SUB_COURSE_ID) AS COURSE_NAME_MULTI_SUB, DATE_FORMAT(A.ISSUE_DATE,'%d/%m/%Y') AS ISSUE_DATE_F FROM certificates_details A  WHERE A.CERTIFICATE_NO='$student_code' AND A.DELETE_FLAG=0 ORDER BY A.CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
		$res = $db->execQuery($sql);

		if ($res && $res->num_rows > 0) {
			$success = true;
			while ($data = $res->fetch_assoc()) {
				extract($data);
				$photo = HTTP_HOST . "/resources/img/teacher_2_small.jpg";
				if ($STUDENT_PHOTO != '')
					$photo = STUDENT_DOCUMENTS_PATH . $STUDENT_ID . '/' . $STUDENT_PHOTO;



				/*if($STUDENT_PHOTO!='')
				$photo = HTTP_HOST."/uploads/certificates/photos/$STUDENT_PHOTO";*/
			}
		}
	}
}

?>

<!-- Inner Page Banner Area Start Here -->
<!--<div class="inner-page-banner-area" style="background-image: url('resources/img/banner/5.jpg');">
    <div class="container">
        <div class="pagination-area">
            <h1>Student Certificate Verification</h1>
            <ul>
                <li><a href="index.php">Home</a> -</li>
                <li>Student Certificate Verification</li>
            </ul>
        </div>
    </div>
</div>-->
<!-- Inner Page Banner Area End Here -->
<div class="about-page2-area">
	<div class="container">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="row">
				<h2 class="title-default-left title-bar-high">Student Certificate Verification -<span>DITRP</span></h2>
				<br>
				<div class="col-md-12 col-sm-8">
					<div id="login">
						<h4 class="title-default-left mb-5">Enter Student Certificate Code Here </h4>
						<div class="form-group">

							<input name="code" type="text" value="<?= isset($_POST['code']) ? $_POST['code'] : '' ?>" style="padding: 10px;
    font-size: 20px;">
						</div>

						<div class="">
							<input type="submit" name="verify_student" class="default-big-btn" value="Verify" />
							<a href="<?= HTTP_HOST ?>/certificate-verify" class="default-big-btn">Cancel</a>

						</div>
						<br><br>
					</div>
				</div>


			</div>
		</form>

		<div class="row">
			<div class="col-sm-12 fverify">
				<?php
				if ($success == true) {
				?>
					<h4 class="title-default-left title-bar-high">Student Certificate Details -<span>DITRP</span></h4>

					<div class="table-responsive">

						<table class="table table-bordered table-responsive">

							<tr>
								<th>Certificate No.</th>
								<!-- <th>:</th> -->
								<td><?= $CERTIFICATE_NO ?></td>
								<td rowspan="3"><img src="<?= $photo ?>" alt="Student Photo" style="max-height:200px;margin:auto;" class="img thumbnail styled"></td>
							</tr>
							<tr>
								<th>Certificate Issue Date</th>
								<!-- <th>:</th> -->
								<td><?php echo $ISSUE_DATE = date('d M Y', strtotime($ISSUE_DATE));  ?></td>
							</tr>
							<tr>
								<th>Name of Student </th>
								<!-- <th>:</th> -->
								<td><?= $STUDENT_NAME ?></td>
							</tr>
							<tr>
								<th>Course Name </th>
								<!-- <th>:</th> -->
								<td><?= $COURSE_NAME ?><?= $COURSE_NAME_MULTI_SUB ?></td>
							</tr>
							<tr>
								<th>Marks Obtained</th>
								<!-- <th>:</th> -->
								<td><?= $MARKS_PER ?> % </td>
							</tr>
							<tr>
								<th>Grade Secured</th>
								<!-- <th>:</th> -->
								<td><?= ($GRADE != '') ? $GRADE : '-' ?></td>
							</tr>
							<!-- <tr>
						<th>Name of Institution</th>
					
						<td><?= $INSTITUTE_NAME ?></td>
					</tr>
					<tr>
						<th>Institute Address</th>
					
						<td style="text-transform:capitalize;"><?= $instituteaddress . ' , ' . $institutecity . ' , ' . $institutestate ?></td>
					</tr>
					<tr>
						<th>Institute Email</th>
					
						<td><?= $institute_email ?></td>
					</tr>
					<tr>
						<th>Institute Contact Number</th>
					
						<td><?= $institutemobile ?></td>
					</tr> -->
						</table>
					<?php } elseif ($success == false) { ?>
						<div class="alert alert-danger">
							<p><strong>Sorry! </strong>
								The entered certificate number not found!
							</p>
						</div>
					<?php } ?>
					</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var myForm = document.getElementById('certVerifyForm');
	myForm.onsubmit = function() {
		var w = window.open('about:blank', 'Popup_Window', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
		this.target = 'Popup_Window';
	};
</script>