<?php
include_once('include/classes/audit.class.php');
$audit = new audit();
 ?>
<!-- PAGE WRAPPER  -->
<div id="page-wrapper" >
	<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<h2>Logs</h2>	
			<h5 class="bread"><a href="page.php?p=list-audits">Logs</a> <i class="fa fa-angle-double-right"></i> List Logs </h5>
			<!--
			<div class="pull-right">
				<a href="page.php?p=add-post" class="btn btn-primary">Add</a>
			</div>
			-->
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
					 List Activities Logs
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="list_users_table">
											
							<?=	$audit->list_audits(); ?>								
							
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