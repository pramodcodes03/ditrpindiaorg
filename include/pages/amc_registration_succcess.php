<section id="main_content" >
	<div class="container">
    <ol class="breadcrumb">
      <li><a href="<?= HTTP_HOST ?>/">Home</a></li>
      <li class="active">AMC Registration Success</li>
    </ol>
    <div class="row">       
    <div class="col-lg-12 col-md-12 col-sm-12">       
      
      <?php
	 // print_r($_SESSION);
		if(isset($_SESSION['msg']))
		{
			$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
			$msg_flag =$_SESSION['msg_flag'];
		?>			
		 <div id="survey_container" class="wizard">	
			<div id="middle-wizard" class="wizard-branch wizard-wrapper">
				<div class="submit step wizard-step current" id="complete" style="display: block;">
					<i class="icon-check"></i>
					
					<h3>Your application for AMC registration submitted sucessfully! <br>
					
					Thank you!
					
					</h3>
				</div>            
			</div>
		</div>
		<?php
		//unset($_SESSION['msg']);
		//unset($_SESSION['msg_flag']);
		}
		?>
    </div><!-- End col-lg-9-->   
	<?php //include('include/common/imp_links.php'); ?>
    </div><!-- End row -->
    </div><!-- End container -->
</section><!-- End main_content -->