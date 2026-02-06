<?php
    $data=array();

    $action= isset($_POST['verify_student'])?$_POST['verify_student']:'';
    $success='';
    if($action!='')
    {
        $success=false;
        
        $cert_number =$db->test(isset($_POST['cert_number'])?$_POST['cert_number']:'');
        if($cert_number!=''){
        $sql = "SELECT * FROM old_certificates_data A  WHERE A.cert_number='$cert_number' AND A.delete_flag=0 ORDER BY A.id DESC LIMIT 0,1";
        $res = $db->execQuery($sql);
        
        if($res && $res->num_rows>0)
        {
            $success=true;
            while($data = $res->fetch_assoc())
            {
                extract($data);               
        
            }
        }
        }
    }

?> 
         
         <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($verification!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$verification;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">Certficate Verification</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>Verification</li>
		                    </ul>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
        <?php
            }
        }
        ?>
        <div id="rs-events" class="rs-events sec-spacer">
			<div class="container">
				<div class="row">
			        <div class="col-md-12">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                    <h2 class="title-default-left title-bar-high mt-50">Student Certificate Verification</h2>	

                                <div class="col-md-12 col-sm-8">
                                    <div id="login">
                                            <h4 class="title-default-left mb-5">Enter Student Certificate Code Here </h4>
                                            <div class="form-group">
                                        
                                                <input name="cert_number" type="text" value="<?= isset($_POST['cert_number'])?$_POST['cert_number']:'' ?>" style="padding: 10px;
                                font-size: 20px;">
                                            </div>
                                        
                                            <div class="">
                                            <input type="submit" name="verify_student" class="btn btn-primary" value="Verify" /> 
                                            <a href="<?= HTTP_HOST ?>" class="btn btn-danger">Cancel</a>
                                            
                                            </div>
                                            <br><br>
                                    </div>
                                </div>

                                
                            </div>
                        </form>
			        </div>
			    </div>
                <div class="row">
                    <div class="col-sm-12 fverify">
                        <?php
                            if($success==true)
                            {
                                ?>
                            <h4 class="title-default-left title-bar-high">Student Certificate Details</h4>
                                        
                                <div class="table-responsive">

                                            <table class="table table-bordered table-responsive">
                                                <tr>
                                                    <th>Certificate No.</th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $cert_number ?></td>
                                                
                                                </tr>
                                                <tr>
                                                    <th>Certificate Issue Date</th>
                                                    <!-- <th>:</th> -->
                                                    <td><?php echo $ISSUE_DATE = date('d M Y', strtotime($cert_date));  ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Name of Student </th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $name ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Course Name </th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $course_name ?> </td>
                                                </tr>
                                                <tr>
                                                    <th>Course Duration </th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $course_duration ?> </td>
                                                </tr>
                                                <tr>
                                                    <th>Marks Obtained</th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $marks ?> % </td>
                                                </tr><tr>
                                                    <th>Grade Secured</th>
                                                    <!-- <th>:</th> -->
                                                    <td><?= $grade ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Name of Institution</th>
                                                   
                                                    <td><?= $institute_name ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Address</th>
                                                   
                                                    <td style="text-transform:capitalize;"><?= $institute_address ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Email</th>
                                                   
                                                    <td><?= $email ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Institute Contact Number</th>
                                                   
                                                    <td><?= $contact_number ?></td>
                                                </tr>
                                    </table>
                                    <?php }elseif($success==false){ ?>
                            <div class="alert alert-danger">
                            <p><strong>Sorry! </strong>
                            The entered certificate number  not found!
                            </p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
			</div>
        </div>

        <script type="text/javascript">
            var myForm = document.getElementById('certVerifyForm');
            myForm.onsubmit = function() {
                var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=650,height=800,left = 312,top = 30');
                this.target = 'Popup_Window';
            };
        </script>