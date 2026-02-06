<div class="banner1">
	<div class="w3_agileits_service_banner_info">
		<h2>Franchise Certificate</h2>
	</div>
</div>  
<div  class="container">   
        
        <div class="row">       
        <div class="col-lg-12 col-md-12 col-sm-12">       
          
          <?php
		 // print_r($_SESSION);
			if(isset($_SESSION['msg']))
			{
				$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
				$msg_flag =$_SESSION['msg_flag'];
			?>			
			 <div id="survey_container" class="wizard" style="color: #3a9234;">	
				<div id="middle-wizard" class="wizard-branch wizard-wrapper">
					<div class="submit step wizard-step current" id="complete" style="display: block;">
						<i class="icon-check"></i>						
						<h3 style="line-height: 1.5em;">Your application submitted! Thank you for your time.<br>
						Please check your mail for your Login ID and Password.<br>
						</h3>
						<p style="line-height: 3.5em;"> Kindly login to your account and upload the required documents (within 7 days) to move authorization process ahead.<p>
						 <a class="btn btn-sm btn-success" href="<?= HTTP_HOST ?>/app/login">Click to Login</a>
					</div>            
				</div>
				<br>
			</div>
			<?php
				unset($_SESSION['msg']);
				unset($_SESSION['msg_flag']);
			}
			?>
        </div><!-- End col-lg-9-->   
		<?php //include('include/common/imp_links.php'); ?>
        </div><!-- End row -->
 </div><!-- End main_content -->