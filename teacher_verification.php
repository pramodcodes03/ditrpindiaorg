<!doctype html>
<html lang="en">
<?php 
$page = isset($_GET['pg'])?$_GET['pg']:'home';
include('include/common/html_header.php'); 
?>

<?php
$data=array();
//print_r($_GET); exit();
$action= isset($_GET['verify_teacher'])?$_GET['verify_teacher']:'';
$success='';
if($action!='')
{
    $success=false;
    
    $id =$db->test(isset($_GET['id'])?$_GET['id']:'');
    if( $id!=''){
	$sql = "SELECT A.*,get_institute_email(A.inst_id) as institute_email,get_institute_mobile(A.inst_id) as institutemobile,  get_institute_address(A.inst_id) as instituteaddress, get_institute_city(A.inst_id) as institutecity,get_institute_state(A.inst_id) as institutestate FROM teacher A  WHERE A.id='$id' AND A.delete_flag=0 ORDER BY A.id DESC LIMIT 0,1";
	$res = $db->execQuery($sql);
	
	if($res && $res->num_rows>0)
	{
	    $success=true;
		while($data = $res->fetch_assoc())
		{
			extract($data);
			//print_r($data);
			$photo1 = HTTP_HOST."/resources/img/teacher_2_small.jpg";
			if($photo!='')
				$photo1 = "uploads/teacher/".$id.'/'.$photo;
				
			$INSTITUTE_NAME = $db->get_institute_name($inst_id);
	
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
			<h2>Teacher Details</h2>
		</div> 
		<div class="table-responsive">
			<table class="table table-bordered table-responsive">
				<tr>
					<td><img src="<?= $photo1 ?>" alt="Student Photo" style="max-height:200px;margin:auto;" class="img thumbnail styled"></td>   
				</tr> 
				<tr>
					<th>ID Number</th>
					<!-- <th>:</th> -->
					<td><?= $code ?></td>
				</tr>
				<tr>
					<th>Teacher Name</th>
					<!-- <th>:</th> -->
					<td><?= $name ?></td>
				</tr>
				<tr>
					<th>Designation</th>
					<!-- <th>:</th> -->
					<td><?= $designation ?></td>
				</tr>
							
				<tr>
					<th>Email Id</th>
					<!-- <th>:</th> -->
					<td><?= $email ?></td>
				</tr>
				<tr>
					<th>Contact Number</th>
					<!-- <th>:</th> -->
					<td><?= $mobile ?></td>
				</tr>
				
				<tr>
					<th>ATC Name</th>
					<!-- <th>:</th> -->
					<td><?= $INSTITUTE_NAME ?></td>
				</tr>
				<tr>
					<th>ATC Email</th>
					<!-- <th>:</th> -->
					<td><?= $institute_email ?></td>
				</tr>
              	<tr>
					<th>ATC Contact</th>
					<!-- <th>:</th> -->
					<td><?= $institutemobile ?></td>
				</tr>
              	<tr>
					<th>ATC Address</th>
					<!-- <th>:</th> -->
					<td><?= $instituteaddress ?> <?= $institutecity ?> <?= $institutestate ?></td>
				</tr>
				
			</table>
			
			<p>The Teacher Name  mentioned on ID Card, he/she is an employee of  Above Mentioned ATC. DITRP is not Responsible in any way for the same.</p>
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