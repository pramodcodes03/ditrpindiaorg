<?php

 ?>
<!-- PAGE WRAPPER  -->
<div id="page-wrapper" >
	<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<h2>Meta Tags</h2>	
			
			<div class="pull-right">
				<!--<a href="page.php?p=add-meta-tags" class="btn btn-primary">Add</a>-->
				<a href="page.php?p=add-meta-tags" class="btn btn-primary">Add</a>
			</div>
			
		</div>
		
	</div>
	 <!-- /. ROW  -->
		 <hr />
	<!-- show messages -->
<?php if(isset($_SESSION['msg'])){?>	
	<div class="row">
		 <div class="col-sm-12">
		 <p class="alert alert-success"><?= $_SESSION['msg'] ?> </p>
		 </div>
	 </div>
<?php 
	unset($_SESSION['msg']);
} ?>
	  <!-- /. show messages -->
	<div class="row">
		<div class="col-md-12">
			<!-- Advanced Tables -->
			<div class="panel panel-default">
				<div class="panel-heading">
					 Meta Tags
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="list_users_table">
							<thead>
								<tr>
									<th>#</th>
									<th>Page Name</th>
									<th>Page URL</th>
									<th>Added On</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$sql = "SELECT *, DATE_FORMAT(CREATED_ON, '%d-%m-%Y') AS CREATION_DATE FROM seo_meta_information_master WHERE DELETE_FLAG=0";
								$res = $db->execQuery($sql); 
								$rows = $res->num_rows;
								if($rows>0)
								{
									$srNo = 1;
									while($data = $res->fetch_assoc())
									{
										$META_INFO_ID 		= $data['META_INFO_ID'];
										$PAGE_NAME 			= $data['PAGE_NAME'];
										$PAGE_URL 			= $data['PAGE_URL'];
										$PAGE_TITLE 		= $data['PAGE_TITLE'];
										$PAGE_STATUS 		= $data['PAGE_STATUS'];
										$CREATION_DATE 		= $data['CREATION_DATE'];
										
										if($PAGE_STATUS==1) $PAGE_STATUS = 'Active';
										elseif($PAGE_STATUS==0) $PAGE_STATUS = 'In-active';
										
										echo '<tr>';
										echo '<td>'.$srNo.'</td>';
										echo '<td>'.$PAGE_NAME.'</td>';
										echo '<td>'.$PAGE_URL.'</td>';
										echo '<td>'.$CREATION_DATE.'</td>';
										echo '<td>'.$PAGE_STATUS.'</td>';
										
										echo '<td><a href="page.php?p=update-meta-tag&id='.$META_INFO_ID.'">Edit</a></td>';
										
										echo '</tr>';
										$srNo++;
									}
								}
							?>							
							</tbody>						
							
						</table>
					</div>
					
				</div>
			</div>
			<!--End Advanced Tables -->
		</div>
	</div>
		<!-- /. ROW  -->

	</div>
		<!-- /. ROW  -->
        
        </div>
               
    </div>
 <!-- /. PAGE WRAPPER  -->