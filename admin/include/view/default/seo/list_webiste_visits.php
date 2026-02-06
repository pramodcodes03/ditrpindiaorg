 <link href="resources/js/morris/morris-0.4.3.min.css" rel="stylesheet">
<!-- PAGE WRAPPER  -->
<div id="page-wrapper" >
	<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<h2>Website Visits</h2>	
			
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
		<div class="col-md-6 col-sm-12 col-xs-12">                     
			<div class="panel panel-default">
				<div class="panel-heading">
					Monthly Website Visits
				</div>
				<div class="panel-body">
					<div id="bar-chart-website-visits-monthly"></div>
				</div>
			</div>            
		</div>  
		<div class="col-md-6 col-sm-12 col-xs-12">                     
			<div class="panel panel-default">
				<div class="panel-heading">
					Daily Website Visits
				</div>
				<div class="panel-body">
					<div id="bar-chart-website-visits-daily"></div>
				</div>
			</div>            
		</div>    
		<div class="col-md-6 col-sm-12 col-xs-12">                     
			<div class="panel panel-default">
				<div class="panel-heading">
					Monthly Ads Searched
				</div>
				<div class="panel-body">
					<div id="bar-chart-post-search-montly"></div>
				</div>
			</div>            
		</div>     
    </div>
	
		<!-- /. ROW  -->

	</div>
		<!-- /. ROW  -->
        
        </div>
               
    </div>
 <!-- /. PAGE WRAPPER  -->