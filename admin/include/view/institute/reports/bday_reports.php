<?php 
	$user_id    = $_SESSION['user_id'];
	$month	 	= isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
	$day	 	= isset($_REQUEST['day'])?$_REQUEST['day']:date('d');
	
	$month1	 	= isset($_REQUEST['month'])?$_REQUEST['month']:date('m');
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
					include('include/classes/institute.class.php');
					$institute = new institute();
					$cond='';
					$res = $institute->list_institute_birthday(" AND MONTH(A.DOB) = $month AND DAY(A.DOB) = $day");
					if($res!='')
					{
						$srno=1;
						
						while($data = $res->fetch_assoc())
						{
						    extract($data);
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
								<td><?= $INSTITUTE_NAME  ?></td>									
								<td><?= $MOBILE ?></td>
								<td><?= $EMAIL  ?></td>
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
						$res1 = $institute->list_institute_birthday(" AND MONTH(A.DOB) = $month1 ");
						if($res1!='')
						{
							$srno=1;
							
							while($data1 = $res1->fetch_assoc())
							{
							     extract($data1);
							
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
    								<td><?= $INSTITUTE_NAME  ?></td>									
    								<td><?= $MOBILE ?></td>
    								<td><?= $EMAIL  ?></td>
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