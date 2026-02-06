<!doctype html>
<html lang="en">
<?php
$page = isset($_GET['pg']) ? $_GET['pg'] : 'home';
include('include/common/html_header.php');
?>

<?php
$data = array();
//print_r($_GET); exit();
$action = isset($_GET['verify_atc']) ? $_GET['verify_atc'] : '';
$success = '';
if ($action != '') {
	$success = false;

	$code = $db->test(isset($_GET['code']) ? $_GET['code'] : '');
	if ($code != '') {
		$sql = "	SELECT A.*,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.VERIFIED_ON, '%d-%m-%Y') AS VERIFIED_ON_FORMATTED,DATE_FORMAT(A.DOB, '%d-%m-%Y') AS DOB_FORMATTED, DATE_FORMAT(B.ACCOUNT_REGISTERED_ON, '%d-%m-%Y') AS REG_DATE,DATE_FORMAT(B.ACCOUNT_EXPIRED_ON, '%d-%m-%Y ') AS EXP_DATE, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON, '%d-%m-%Y %h:%i %p') AS UPDATED_DATE, B.USER_NAME, B.USER_LOGIN_ID,states_master.STATE_NAME FROM institute_details A INNER JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
            INNER JOIN states_master ON A.STATE=states_master.STATE_ID          
		    WHERE A.DELETE_FLAG=0 AND A.INSTITUTE_CODE='$code'";
		$res = $db->execQuery($sql);

		if ($res && $res->num_rows > 0) {
			$success = true;
			while ($data = $res->fetch_assoc()) {
				extract($data);
			}
		}
	}
}

?>


<div id="rs-events" class="rs-events sec-spacer">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<h2 class="title-default-left title-bar-high mt-50">Franchise Verification (ATC Verification)</h2>
				</div>
			</div>
		</div>
		<?php
		if ($success == true) {


			$logo = $db->get_institute_docs_single($INSTITUTE_ID, 'logo');
			$logopath = $logo;
			$certverData = $INSTITUTE_ID . ':' . $INSTITUTE_CODE . ':' . $EMAIL;

			$owner_photo = $db->get_institute_docs_single($INSTITUTE_ID, 'owner_photo');
			$owner_photo_path = $owner_photo;
		?>
			<div class="row">
				<div class="col-sm-12 fverify">

					<div class="table-responsive">
						<table class="table table-bordered table-responsive">

							<tr>
								<th>INSTITUTE LOGO</th>
								<td>
									<img src="<?= $logopath ?>" class="img img-responsive img-rounded" style="height: 100px;">
								</td>
							</tr>
							<tr>
								<th>OWNER PHOTO</th>
								<td>
									<img src="<?= $owner_photo_path ?>" class="img img-responsive img-rounded" style="height: 100px;">
								</td>
							</tr>
							<tr>
								<th>INSTITUTE CODE</th>
								<td><?= $INSTITUTE_CODE ?></td>
							</tr>
							<tr>
								<th>INSTITUTE NAME</th>
								<td><?= $INSTITUTE_NAME ?></td>
							</tr>
							<tr>
								<th>INSTITUTE OWNER NAME</th>
								<td><?= $INSTITUTE_OWNER_NAME ?></td>
							</tr>
							<tr>
								<th>INSTITUTE EMAIL</th>
								<td><?= $EMAIL ?></td>
							</tr>
							<tr>
								<th>INSTITUTE CONTACT NUMBER</th>
								<td><?= $MOBILE ?></td>
							</tr>
							<tr>
								<th>INSTITUTE ADDRESS</th>
								<td><?= $ADDRESS_LINE1 . ' ,' . $CITY . ' , ' . $STATE_NAME ?></td>
							</tr>
							<!-- <tr>
							<th>INSTITUTE VERIFIED CERTIFICATE</th>
							<td>
								<form method="post" id="certVerifyForm" action="franchise_certificate_verify.php">
									<input type="hidden" name='code' value="<?= base64_encode($INSTITUTE_CODE) ?>">
									<input type="submit" name="verify_certificate" value="View Cerificate" class="default-big-btn" />
								</form>
							</td>				
						</tr> -->
						</table>
					</div>
				</div>
			</div>
	</div>

<?php
		}
?>

<script type="text/javascript">
	var myForm = document.getElementById('certVerifyForm');
	myForm.onsubmit = function() {
		var w = window.open('about:blank', 'Popup_Window', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
		this.target = 'Popup_Window';
	};
</script>