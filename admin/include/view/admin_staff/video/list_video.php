 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			List Video
 			<small>All Video</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#"> Video</a></li>
 			<li class="active"> List Video</li>
 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">
 		<?php
			if (isset($_SESSION['msg'])) {
				$message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
				$msg_flag = $_SESSION['msg_flag'];
			?>
 			<div class="row">
 				<div class="col-sm-12">
 					<div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
 						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
 						<h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
 						<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
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
 				<div class="box">
 					<div class="box-header">
 						<h3 class="box-title">List Video Details</h3>
 						<?php if ($db->permission('add_exam')) { ?>
 							<a href="add-video" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Video Details</a>
 						<?php } ?>
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">
 						<div class="table-responsive">
 							<table class="table table-bordered table-hover list-exams data-tbl">
 								<thead>
 									<tr>
 										<th>Sr.</th>
 										<th>Video </th>
 										<th>Status</th>
 										<th>Action</th>
 									</tr>
 								</thead>
 								<tbody>
 									<?php
										include_once('include/classes/tools.class.php');
										$exam = new tools();
										$res = $exam->list_video('', '');
										if ($res != '') {
											$srno = 1;
											while ($data = $res->fetch_assoc()) {
												$CONTEST_ID 	= $data['RESULT_ID'];
												$CONTEST_TITLE  = $data['RESULT_STATE'];


												if (!empty($CONTEST_TITLE)) {
													$CONTEST_TITLE = '<iframe id="player" type="text/html" 
  src="https://www.youtube.com/embed/' . $CONTEST_TITLE . '"
  frameborder="0" width="300" height="200"></iframe>';
												} else {
													$CONTEST_TITLE = "Their Is No Video";
												}

												//	$CONTEST_IMG 	= $data['RESULT_IMG'];
												$ACTIVE			= $data['ACTIVE'];
												$CREATED_BY 	= $data['CREATED_BY'];
												$CREATED_ON 	= $data['CREATED_ON'];
												$rowclass		= ($ACTIVE == 0) ? 'class="danger"' : '';



												if ($ACTIVE == 1)
													$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
												elseif ($ACTIVE == 0)
													$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';



												$action = "";

												if ($db->permission('update_exam'))
													$action .= "<a href='page.php?page=update-video&id=$CONTEST_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
												if ($db->permission('delete_exam'))
													$action .= "<a href='javascript:void(0)' onclick='deleteVideo($CONTEST_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
					";

												echo " <tr id='exam-id" . $CONTEST_ID . "'>
							<td>$srno</td>
							<td>$CONTEST_TITLE</td>							
												
								
							<td id='status-" . $CONTEST_ID . "'>$ACTIVE</td>
							<td>$action</td>
                           </tr>";
												$srno++;
											}
										}

										?>
 								</tbody>

 							</table>
 						</div>
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


 <!-- modal to send email -->