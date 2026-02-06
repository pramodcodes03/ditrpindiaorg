<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<?php //print_r($_SESSION);
	$assign_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>
	<section class="content-header">
		<h1>
			List of Assigned Institutes
			<small>All Institutes</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"> Institutes</a></li>
			<li class="active"> Assigned Institutes</li>
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
						<h3 class="box-title">List Institutes</h3>

					</div>
					<!-- /.box-header -->
					<div class="box-body">


						<table class="table table-bordered table-striped table-hover data-tbl">
							<thead>
								<tr>

									<th>Sr.</th>
									<th>Logo</th>
									<th>Institute Name</th>
									<th>City</th>
									<th>ATC Code</th>
									<th>Address</th>
									<th>Email</th>
									<th>Mobile</th>
									<td>Status</td>
									<td>Action</td>

								</tr>
							</thead>
							<tbody>
								<?php
								$cond = " ";
								$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
								if ($verified != '') {
									$cond .= " AND A.VERIFIED='$verified'";
								}

								$cond .=  " AND D.AMC_ID=$assign_id";

								include_once('include/classes/amc.class.php');
								$amc = new amc();
								$res = $amc->list_unassigned_institutes($assign_id, '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {	//$PAY_STATUS = $data['PAY_STATUS'];
										$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
										$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
										$REG_DATE 			= $data['REG_DATE'];
										$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
										$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
										$INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
										$EMAIL 				= $data['EMAIL'];
										$MOBILE 			= $data['MOBILE'];
										$CREDIT 			= $data['CREDIT'];
										$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
										$ADDRESS_LINE1 			= $data['ADDRESS_LINE1'];
										$ADDRESS_LINE1 			= $data['ADDRESS_LINE1'];
										$ACTIVE 			= $data['ACTIVE'];
										$CITY_NAME 			= $data['CITY_NAME'];
										$VERIFIED 			= $data['VERIFIED'];
										$verify_flag 			= $data['VERIFIED'];


										if ($db->permission('update_institute')) {
											if ($ACTIVE == 1)
												$ACTIVE = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';
										} else {
											if ($ACTIVE == 1)
												$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';
										}
										$printCert = "";
										/*if($db->permission('verify_institute'))
					{
						if($VERIFIED==1){
						$VERIFIED = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstVerify('.$INSTITUTE_ID.',0)"><i class="fa fa-check"></i> YES</a> ';
						
						   // $printCert = "<a href='print-franchise-certificate&inst[]=$INSTITUTE_ID' class='btn btn-link' title='Print Certificate' target='_blank'><i class=' fa fa-certificate'></i></a>";
						
						  //  $printCert .= "<a href='print-franchise-address&inst[]=$INSTITUTE_ID' class='btn btn-link' title='Print Address' target='_blank'><i class=' fa fa-envelope'></i></a>";
						}
						elseif($VERIFIED==0)	
						$VERIFIED = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstVerify('.$INSTITUTE_ID.',1)"><i class="fa fa-times"></i> NO</a> ';	
					}else{
						if($VERIFIED==1)
						$VERIFIED = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
						elseif($VERIFIED==0)	
						$VERIFIED = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';	
					}
				*/
										$changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
										$changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';
										/*$PHOTO = '../uploads/default_user.png';*/
										$PHOTO = SHOW_IMG_AWS . '/default_user.png';
										//if($STAFF_PHOTO!='')
										//	$PHOTO = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$STAFF_PHOTO;
										$editLink = "";
										if ($db->permission('update_institute'))
											//	$editLink .= "<a href='update-institute&id=$INSTITUTE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

											if ($db->permission('delete_institute'))
												//$editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deleteInstitute($INSTITUTE_ID)'><i class=' fa fa-trash'></i></a>";

												/*
					$editLink .= "<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$EMAIL' data-id='$INSTITUTE_ID' data-name='$INSTITUTE_NAME'><i class=' fa fa-envelope'></i></a>";					
					$editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Change Password' $changepassFun><i class=' fa fa-key'></i></a>
					";
					*/

												/* 	$pay_status="";
                    if($PAY_STATUS==1)
                    {
                    	$pay_status="Paid";
                    }else{
                    	$pay_status="Unpaid";
                    }*/
												// $editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Password Recovery SMS' onclick='forgotPassSMS($INSTITUTE_ID,2)' ><i class=' fa fa-key'></i></a>";					

												//$editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Documents Reminder SMS' onclick='uploadDocsSMS($INSTITUTE_ID,2)' ><i class=' fa fa-bell'></i></a>";

												$editLink .= "<a href='page.php?page=show-details&id=$INSTITUTE_ID' class='btn btn-primary btn-xs' title='show Details'><i class=' fa fa-bell'>show Details</i></a>
					";

										//$editLink .= $printCert;
										include_once('include/classes/institute.class.php');
										$institute = new institute();

										$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');
										echo " <tr id='row-$INSTITUTE_ID'>
					 		
							<td>$srno</td>						
							<td>$logo</td>

							<td>$INSTITUTE_NAME</td>
							<td>$CITY_NAME</td>
							<td>$INSTITUTE_CODE</td>
							<td> $ADDRESS_LINE1</td>
							
							<td>$EMAIL</td> 
							<td>$MOBILE</td>
							<td>$ACTIVE</td>
							<td>$editLink</td>

                           </tr>";
										$srno++;
									}
								}

								?>
							</tbody>
						</table>


						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
</div>


<!-- modal to send email -->