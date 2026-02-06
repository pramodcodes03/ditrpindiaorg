<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?=base_url()?>assets/front_assets/bootstrap/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>assets/front_assets/js/modernizr.custom.63321.js"></script>
<link href="<?=base_url()?>assets/front_assets/bootstrap/dist/css/sb-admin-2.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/css/style3.css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<title>ONLINE EXAM</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="icon" type="image/png" href="<?=base_url()?>assets/logo/icon.png" />
</head>
<body>
<div id="wrapper">
  <div id="page-wrapper">
    <div class="container">
      <div class="row"> 
      <!--<a id='modal-launcher' style="float:right; margin-top:20px" data-toggle="modal" data-target="#login-modal">Login </a> -->
      <a class="btn btn-launch" href="javascript:;" data-toggle="modal" data-target="#loginModal" style="float:right;"> Login</a>
      </div>
      <header>
        <div class="support-note"> <span class="note-ie">Sorry, only modern browsers.</span> </div>
        <h1  style="margin-top:50px;">Appear for Exam</h1>
        <h2 style=" margin-bottom:-50px">Enter your STUDENT ID below</h2>
        <div class="support-note"> <span class="note-ie">Sorry, only modern browsers.</span> </div>
      </header>
      <div class="row">
        <div class="col-lg-12">
          <div class="row" style="margin-top:10px;">
            <div class="col-lg-12"> 
              <!-- .panel-heading -->
              <div class="panel-body">
                <div class="panel-group" id="accordion">
                  <section class="main"><a href="<?= HOST ?>" style="background: #54cae6;padding: 10px;color: #fff;">Back to Home</a>  
<!--				  
                    <?php $msg = $this->session->userdata('msg');?>
                    <?php $adminFrm = array('class' => 'form-1', 'name' => 'submitStu', 'id' => 'submitStu');?>
                    <?php echo form_open_multipart('',$adminFrm); ?>
                    <?php
                        if(!empty($msg))
                        {
                            echo '<p align="center" style="margin-bottom:-10px;"><font color="#00cc00"><b>'.$msg.'</b></font></p><br>';
                            $this->session->unset_userdata('msg');
                        }
						
                     ?>
			
                    <p class="field">
                      <input type="text" name="student_course_id" placeholder="Enter Student ID" value="" required>
                      <i class="icon-user icon-large"></i> </p>
                    <p class="submit">
                      <button type="submit" name="submit"><i class="icon-arrow-right icon-large"></i></button>
                    </p>
					-->
                    </form>
                  </section>
                </div>
              </div>
              
              <!-- .panel-body --> 
              <!-- /.panel --> 
            </div>
            <!-- /.col-lg-12 --> 
          </div>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap Core JavaScript --> 
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/js/jquery.js"></script> 
<script src="<?=base_url()?>assets/front_assets/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
<!-- Custom Theme JavaScript --> 
<script src="<?=base_url()?>assets/front_assets/bootstrap/bower_components/metisMenu/dist/metisMenu.min.js"></script> 
<script src="<?=base_url()?>assets/front_assets/bootstrap/dist/js/sb-admin-2.js"></script>
</body>
</html>
<!--<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header login_modal_header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title" id="myModalLabel">Login</h2>
      </div>
      <div class="modal-body login-modal">
        <div class="clearfix"></div>
        <div id='social-icons-conatainer'>
        
        <?php $logFrm = array('name' => 'logFrm', 'id' => 'logFrm');?>
        <?php echo form_open('',$logFrm); ?>
             <div class='modal-body-left'>
            <div class="form-group">
              <input type="text" id="username" placeholder="Enter username" name="username" value="" class="form-control login-field">
              <i class="fa fa-user login-field-icon"></i></div>
            <div class="form-group">
              <input type="password" id="login-pass" placeholder="Password" name="pwd" value="" class="form-control login-field">
              <i class="fa fa-lock login-field-icon"></i></div>
            <input type="submit" name="login" value="Log in" class="btn btn-success modal-login-btn">
            &nbsp;&nbsp;<a href="#" class="login-link text-center">Lost your password?</a>
            </div>
       </form>
            
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer login_modal_footer"></div>
    </div>
  </div>
</div>-->
<!-- <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div class="modal-dialog">
	    	<div class="modal-content login-modal">
	      		<div class="modal-header login-modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title text-center" id="loginModalLabel">USER AUTHENTICATION</h4>
	      		</div>
	      		<div class="modal-body">
	      			<div class="text-center">
		      			<div role="tabpanel" class="login-tab">
						  	
						  	<ul class="nav nav-tabs" role="tablist">
						    	<li role="presentation" class="active"><a id="signin-taba" href="#home" aria-controls="home" role="tab" data-toggle="tab">Sign In</a></li>
						    	
						    	<li role="presentation"><a id="forgetpass-taba" href="#forget_password" aria-controls="forget_password" role="tab" data-toggle="tab">Forget Password</a></li>
						  	</ul>
						
						 
						 	<div class="tab-content">
						    	<div role="tabpanel" class="tab-pane active text-center" id="home">
						    		&nbsp;&nbsp;
						    		<span id="login_fail" class="response_error" style="display: none;">Loggin failed, please try again.</span>
						    		<div class="clearfix"></div>
						    		<?php $logFrm = array('name' => 'logFrm', 'id' => 'logFrm');?>
       								<?php echo form_open('',$logFrm); ?>
										<div class="form-group">
									    	<div class="input-group">
									      		<div class="input-group-addon"><i class="fa fa-user"></i></div>
									      		<input type="text" id="username" placeholder="Enter username" name="username" value="" class="form-control login-field" required="required">
									    	</div>
									    	<span class="help-block has-error" id="email-error"></span>
									  	</div>
									  	<div class="form-group">
									    	<div class="input-group">
									      		<div class="input-group-addon"><i class="fa fa-lock"></i></div>
									      		 <input type="password" id="login-pass" placeholder="Password" name="pwd" value="" class="form-control login-field" required="required">
									    	</div>
									    	<span class="help-block has-error" id="password-error"></span>
									  	</div>
							  			<input type="submit" name="login" value="Login" class="btn btn-success modal-login-btn">
							  			<div class="clearfix"></div>
									</form>
						    	</div>
						    	<div role="tabpanel" class="tab-pane" id="profile">
						    	    &nbsp;&nbsp;
						    	    <span id="registration_fail" class="response_error" style="display: none;">Registration failed, please try again.</span>
						    		<div class="clearfix"></div>
						    	</div>
						    	<div role="tabpanel" class="tab-pane text-center" id="forget_password">
						    		&nbsp;&nbsp;
						    	    <span id="reset_fail" class="response_error" style="display: none;"></span>
						    		<div class="clearfix"></div>
						    		<?php $logFrm1 = array('name' => 'logFrm1', 'id' => 'logFrm1');?>
          							<?php echo form_open('',$logFrm1); ?>
                                    <div class="form-group">
									    	<div class="input-group">
									      		<div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                  <select name="user_type" id="user_type" class="form-control login-field" required="required" onchange="checkInstitute(this.value)">
                                                  <option value="">--Select User Type--</option>
                                                  <option value="admin">Admin</option>
                                                  <option value="institute">Institute</option>
                                                  </select>
									    	</div>
									    	<span class="help-block has-error" data-error='0' id="femail-error"></span>
									  	</div>
                                        <div id="typediv">
                                        </div>
										<div class="form-group">
									    	<div class="input-group">
									      		<div class="input-group-addon"><i class="fa fa-user"></i></div>
                                                  <input type="email" id="femail" placeholder="Email" name="foremail" value="" class="form-control login-field" required="required">
									    	</div>
									    	<span class="help-block has-error" data-error='0' id="femail-error"></span>
									  	</div>
									  	
							  			 <input type="submit" name="send" value="Submit" class="btn btn-success modal-login-btn">
										<div class="clearfix"></div>
									</form>
						    	</div>
						  	</div>
						</div>
	      				
	      			</div>
	      		</div>
	      		
	    	</div>
	   </div>
 	</div> -->
<script>
	    $(document).ready(function(){
	    	$(document).on('click','.signup-tab',function(e){
	    		 e.preventDefault();
	    		 $('#signup-taba').tab('show');
	    	});	
	
	    	$(document).on('click','.signin-tab',function(e){
	    		 e.preventDefault();
	    		 $('#signin-taba').tab('show');
	    	});
	    	
	    	$(document).on('click','.forgetpass-tab',function(e){
	    		 e.preventDefault();
	    		 $('#forgetpass-taba').tab('show');
	    	});
	    });	
    </script>
    <script>
function getXMLHTTP() { 
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		return xmlhttp;
    }
	function checkInstitute(type) {	
		
			//alert(id);
			var strURL="<?=base_url()?>assets/includes/check_institute.php?type="+type;
			var req = getXMLHTTP();
			if (req) {
				req.onreadystatechange = function() {
					if (req.readyState == 4) {
						// only if "OK"
						if (req.status == 200) {	
							document.getElementById('typediv').innerHTML=req.responseText;
						} else {
							alert("There was a problem while using XMLHTTP:\n" + req.statusText);
						}
					}				
				}			
				req.open("GET", strURL, true);
				req.send(null);
			}
	}
	</script>