 <?php
 include('include/classes/exam.class.php');
 $exam = new exam();
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
if($user_role==5){
   $institute_id = $db->get_parent_id($user_role,$user_id);
   $staff_id = $user_id;
}
else{
   $institute_id = $user_id;
   $staff_id = 0;
}
 

 
 $res 	= $exam->list_offline_downloaded_papers('','',$institute_id,'');
 ?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Offline Exams Papers
        <small>All Offline Papers</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a <a href="#"> Exams</a></li>
        <li class="active"> Offline Exam Papers</li>       
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

      <div class="row">
		
        <div class="col-xs-12">
          <div class="box box-warning">
            <div class="box-header">			
						
        
            </div>
            <!-- /.box-header -->
            <div class="box-body">			
			<table class="table table-bordered data-tbl">
			  <thead>
				<th>#</th>
				<th>Photo</th>
				<th>Student</th>
				<th>Course</th>
				<th>Question Paper</th>
				<th>Answer Paper</th>
				<th>Exam Status</th>
				<th>Action</th>
				<th>Created On</th>
			  </thead> <tbody>
			<?php		
			if($res!='')
			{
				$srno=1;
				while($data=$res->fetch_assoc())
				{
					extract($data);				
				/*	$PHOTO = '../uploads/default_user.png';*/	
						$PHOTO = SHOW_IMG_AWS.'/default_user.png';
					if($STUDENT_PHOTO!='')
						$PHOTO = SHOW_IMG_AWS.STUDENT_DOCUMENTS_PATH.$STUDENT_ID.'/'.$STUDENT_PHOTO;
					
					$quefile = SHOW_IMG_AWS.'/exam/offline/'.$STUDENT_ID.'/'.$QUESTION_PAPER;
					$ansfile = SHOW_IMG_AWS.'/exam/offline/'.$STUDENT_ID.'/'.$ANSWER_PAPER;
					/*$EXAM_STATUS_NAME='';
					if($EXAM_STATUS==2) $EXAM_STATUS_NAME= 'Pending'; 
					if($EXAM_STATUS==3) $EXAM_STATUS_NAME= 'Completed'; 
					*/
					$downloadque = "<a href='$quefile' target='_blank' class='btn btn-xs btn-flat bg-purple' title='Download Question Paper'><i class='fa fa-download'></i></a>";
					$downloadans = "<a href='$ansfile' target='_blank' class='btn btn-xs btn-flat bg-purple' title='Download Answer Paper'><i class='fa fa-download'></i></a>";
					
					if($EXAM_STATUS==2 && $db->permission('add_offline_exam_result'))
					$action = '<a href="page.php?page=add-offline-exam-result&id='.$OFFLINE_PAPER_ID.'" class="btn btn-xs btn-flat  btn-primary">Add Result</a>';					
					elseif($EXAM_STATUS==3 && $db->permission('update_offline_exam_result')){
						$action = '<a href="page.php?page=update-offline-exam-result&result='.$EXAM_RESULT_ID.'" class="btn btn-xs btn-flat  btn-primary">View Result</a>';	
					}
					$course_info	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
					$course_name 	= $course_info['COURSE_NAME'];
					echo "<tr><td>$srno</td>
							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
							<td>$STUDENT_NAME</td>
							<td>$course_name</td>
							<td>$downloadque</td>
							<td>$downloadans</td>
							<td>$EXAM_STATUS_NAME</td>
							<td>$action</td>
							<td>$CREATED_DATE</td>
							
							</tr>
							";
					$srno++;
					
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
  