<?php 
	$user_id= $_SESSION['user_id'];
	$month	 	= isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
	$day	 	= isset($_REQUEST['day'])?$_REQUEST['day']:date('d');
	
	$month1	 	= isset($_REQUEST['month'])?$_REQUEST['month']:'';
	$day1	 	= isset($_REQUEST['day'])?$_REQUEST['day']:'';
?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
		<div class="card">
		<div class="card-body">
			<h4 class="card-title">Today's Birthday List
			</h4>  
							
			<div class="table-responsive pt-3">
			<table id="order-listing1" class="table">
				<tbody>
				<?php
					include('include/classes/admin.class.php');
					$admin = new admin();
					$cond='';
					$res = $admin->get_birth_day_report($month,$day," AND A.INSTITUTE_ID = $user_id ORDER BY DAY(A.STUDENT_DOB) ASC");
					if($res!='')
					{
						$srno=1;
						
						while($data = $res->fetch_assoc())
						{
							$STUDENT_ID  		= $data['STUDENT_ID'];
							$STUDENT_FNAME 	= $data['STUDENT_FNAME'];
							$STUDENT_MNAME 	= $data['STUDENT_MNAME'];
							$STUDENT_LNAME 	= $data['STUDENT_LNAME'];
							$STUDENT_MOBILE 			= $data['STUDENT_MOBILE'];					
							$STUDENT_EMAIL 			= $data['STUDENT_EMAIL'];					
							$DOB 				= $data['DOB_FORMATTED'];
							$DOB_DAY 			= $data['DOB_DAY'];
							$DOB_MONTH 			= $data['DOB_MONTH'];
							$today_month = date('m');
							$today_day = date('d');
							$rowcolor = "";	
							$style = "";	
							if($DOB_DAY==$today_day && $today_month == $DOB_MONTH)
								$rowcolor = "style='background-color:#98ff8f'";
							
							?>
							<tr class="" <?= $rowcolor ?>>
								<td><?= $srno ?></td>
								<td><?= $DOB ?></td>
								<td><?= $STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME ?></td>									
								<td><?= $STUDENT_MOBILE ?></td>
								<td><?= $STUDENT_EMAIL ?></td>
							</tr>
							
							<?php
							
							$srno++;
						}
					}
					
				?>                              
				</tbody>
			</table>
			</div>
		</div>

		<div class="card-body">
			<h4 class="card-title">Birthday's List
			</h4>  
							
			<div class="table-responsive pt-3">
				<table id="order-listing" class="table">
					<thead>
					<tr class="tableRowColor">
						<tr>
						<th>S/N</th>
						<th>Date Of Birth</th>
						<th>Student Name</th>
						<th>Mobile Number</th>
						<th>Email</th>
					</tr>
					</thead>
					<tbody>
					<?php
						
						$cond='';
						$res = $admin->get_birth_day_report($month1,$day1," AND INSTITUTE_ID = $user_id ORDER BY DAY(A.STUDENT_DOB) ASC");
						if($res!='')
						{
							$srno=1;
							
							while($data = $res->fetch_assoc())
							{
								$STUDENT_ID  		= $data['STUDENT_ID'];
								$STUDENT_FNAME 	= $data['STUDENT_FNAME'];
								$STUDENT_MNAME 	= $data['STUDENT_MNAME'];
								$STUDENT_LNAME 	= $data['STUDENT_LNAME'];
								$STUDENT_MOBILE 			= $data['STUDENT_MOBILE'];					
								$STUDENT_EMAIL 			= $data['STUDENT_EMAIL'];					
								$DOB 				= $data['DOB_FORMATTED'];
								$DOB_DAY 			= $data['DOB_DAY'];
								$DOB_MONTH 			= $data['DOB_MONTH'];
								$today_month = date('m');
								$today_day = date('d');
								$rowcolor = "";	
								$style = "";	
								if($DOB_DAY==$today_day && $DOB_MONTH == $today_month)
									$rowcolor = "style='background-color:#98ff8f'";
								
								?>
								<tr class="" <?= $rowcolor ?>>
									<td><?= $srno ?></td>
									<td><?= $DOB ?></td>
									<td><?= $STUDENT_FNAME.' '.$STUDENT_MNAME.' '.$STUDENT_LNAME ?></td>								
									<td><?= $STUDENT_MOBILE ?></td>
									<td><?= $STUDENT_EMAIL ?></td>
								</tr>
								
								<?php
								
								$srno++;
							}
						}
						
					?>                              
					</tbody>
				</table>
			</div>
		</div>

		</div>
	</div>
</div>