 <?php
 ini_set('max_execution_time', 3000);			
include_once('include/classes/student.class.php');

$student = new student();
$student_id 	= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';
$file_id 		= isset($_REQUEST['file_id'])?$_REQUEST['file_id']:'';

$action = isset($_POST['upload'])?$_POST['upload']:'';
if($action!='')
{
	

	//$data = new Spreadsheet_Excel_Reader("OLD CERTIFICATE DATABASE.xls");
	$file 		= isset($_FILES["file"]["name"])?$_FILES["file"]["name"]:'';
	$file_tmp 	= isset($_FILES["file"]["tmp_name"])?$_FILES["file"]["tmp_name"]:'';
	
	if($file!='')
	{
		//if($ext=='csv' || $ext=='xls' || $ext=='sql')
		//{
			$db->execQuery("TRUNCATE certificates_details_old");
				
				$handle = fopen($file_tmp, "r");
			//	$db->execQuery("START TRANSACTION");
				$sql_err=0;
				$loop=0;
				while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if($loop>0){
					$serial_no 			= $db->test(isset($line[0])?$line[0]:'');
					$exam_date 			= $db->test(isset($line[1])?$line[1]:'');
					$stud_name			= $db->test(isset($line[2])?$line[2]:'');
					$stud_mob 			= $db->test(isset($line[3])?$line[3]:'');
					$course_code		= $db->test(isset($line[4])?$line[4]:'');
					$marks 				= $db->test(isset($line[5])?$line[5]:'');
					$inst_code 			= $db->test(isset($line[6])?$line[6]:'');
					$stud_name_cert 	= $db->test(isset($line[7])?$line[7]:'');
					$grade				= $db->test(isset($line[8])?$line[8]:'');
					$day_cert 			= $db->test(isset($line[9])?$line[9]:'');
					$month_cert 		= $db->test(isset($line[10])?$line[10]:'');
					$year_cert 			= $db->test(isset($line[11])?$line[11]:'');
					$grade2 			= $db->test(isset($line[12])?$line[12]:'');
					$course_name 		= $db->test(isset($line[13])?$line[13]:'');
					$incharge_name 		= $db->test(isset($line[14])?$line[14]:'');
					$inst_name 			= $db->test(isset($line[15])?$line[15]:'');
					$inst_city 			= $db->test(isset($line[16])?$line[16]:'');
					$cert_no 			= $db->test(isset($line[17])?$line[17]:'');
					$remark 			= $db->test(isset($line[18])?$line[18]:'');
					$payment			= $db->test(isset($line[19])?$line[19]:'');
					$lamination 		= $db->test(isset($line[20])?$line[20]:'');
					$lamination 		= $db->test(isset($line[21])?$line[21]:'');
					$cert_date 		= $day_cert.'.'.$month_cert.'.'.$year_cert;
					$created_by 	= $_SESSION['user_fullname'];
					$created_on_ip 	= $_SESSION['ip_address'];
					
					$table_name 	= 'certificates_details_old';
			if($cert_no!=''){
					echo $sql = "INSERT INTO $table_name (CERTIFICATE_DETAILS_ID, SRNO,CERTIFICATE_NO,INSTITUTE_CODE,INSTITUTE_NAME,INCHARGE_NAME,INSTITUTE_CITY,STUDENT_NAME,STUDENT_MOBILE,COURSE_CODE,COURSE_NAME,MARKS_PER,GRADE,EXAM_DATE,ISSUE_DATE,PAYMENT_RECIEVED,LAMINATION,REMARK,CREATED_BY,CREATED_ON,CREATED_ON_IP)
					VALUES (NULL, '$serial_no', '$cert_no','$inst_code', '$inst_name','$incharge_name','$inst_city', '$stud_name', '$stud_mob', '$course_code' ,'$course_name' , '$marks' ,'$grade','$exam_date' , '$cert_date', '$payment' ,'$lamination', '$remark','$created_by', NOW(),'$created_on_ip')";	
					echo "<br><br>";
					$res = $db->execQuery($sql);
					
			}
				  
				}
				$loop++;		  
			}	
			
		/*}else{
			$msg ="Invalid file! File format not supported.";
		}*/
	}	

}

 ?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Old Certificates Data
      
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>       
        <li>Student</li>
        <li class="active">List Old Certificates Data</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
		<?php
			if(isset($_SESSION['msg']))
			{
				$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
				$msg_flag =$_SESSION['msg_flag'];
			?>
			<div class="row">
			<div class="col-sm-12">
			<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>
				<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>
            </div>
			 </div>
			 </div>
			<?php
			unset($_SESSION['msg']);
			unset($_SESSION['msg_flag']);
			}
			?>
		<?php if($db->permission('upload_old_certificate_data')){ ?>
		<div class="row">
			<div class="col-xs-12">
			  <div class="box">
				<div class="box-header">
				 <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">
				 <input type="hidden" name="stud_id" value="<?= $student_id ?>" />
				  <div class="form-group <?= (isset($errors['file']))?'has-error':'' ?>">
					<label for="file" class="col-sm-3 control-label">Upload New Data (.csv format): </label>
					<div class="col-sm-3">
					  <input type="file" name="file" class="" id="file" />
					</div>
					<div class="col-sm-4">
					  <input type="submit" class="btn btn-sm btn-primary" id="upload" name="upload" Value="Upload" />
					 <!-- <a href="../uploads/old_certificate_data_sample.xls" class="btn btn-sm btn-link">Download Sample (.csv)</a> -->
					</div>
					<span class="help-block"><?= isset($errors['file'])?$errors['file']:'' ?></span>
				  </div>			 
				  </form>
				</div>
			  </div>
			  </div>
		  </div>
		<?php } ?>
			<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">			
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			 <table class="table table-bordered table-hover data-tbl">
                <thead>
                <tr>
					<th>#</th>
					<th>Certificate No</th>
					<th>Student Name</th>
					<th>Mobile</th>
					<th>Institute Name</th>
					<th>Marks</th>
					<th>Grade</th>
					<th>Exam Date</th>
					<th>Certificate Date</th>
				</tr>
                </thead>
                <tbody>
			<?php
			$sql = "SELECT *, DATE_FORMAT(EXAM_DATE, '%d-%m-%Y') AS EXAM_DATE_F, DATE_FORMAT(ISSUE_DATE, '%d-%m-%Y') AS ISSUE_DATE_F FROM certificates_details_old ORDER BY CERTIFICATE_NO DESC";
			$res = $db->execQuery($sql);
			if($res && $res->num_rows>0)
			{
		
			
				$filesNo = 1;
					while($result = $res->fetch_assoc())
					{
						extract($result);						
						
						echo $courseDetail = "<tr><td width='5%'>$filesNo</td>
										  <td>$CERTIFICATE_NO</td>	
										  <td>$STUDENT_NAME</td>	
										  <td>$STUDENT_MOBILE</td>	
										  <td>$INSTITUTE_NAME</td>	
										  <td>$MARKS_PER</td>	
										  <td>$GRADE</td>	
										  <td>$EXAM_DATE</td>	
										  <td>$ISSUE_DATE</td>	
										 
										 </tr>";
						$filesNo++;
					}
			
			}
			?>
                </tbody>               
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->     
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  