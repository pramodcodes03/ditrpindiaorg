 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Institutes On Website
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a <a href="#"> Institutes</a></li>
        <li class="active"> List Institutes On Website</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
			<?php
			if(isset($_SESSION['msg']))
			{
				$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
				$msg_flag =$_SESSION['msg_flag'];
			?>
			<div class="row">
			<div class="col-sm-12">
			<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>
				<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>
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
              <h3 class="box-title">List Institutes On Website</h3>
			
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			 
			 <table id="example1" class="table table-bordered table-striped table-hover data-tbl">
                <thead>
                <tr>
                  <th>Sr.</th>
                  <th>Logo</th>
                  <th>Institute Name</th>
                  <th>ATC Code</th>               
                  <th>City</th>               
                  <th align="middle">Show on website</th>
                </tr>
                </thead>
                <tbody>
			<?php
			include_once('include/classes/institute.class.php');
			$institute = new institute();
			$res = $institute->list_institute('','');
			if($res!='')
			{
				$srno=1;
				while($data = $res->fetch_assoc())
				{
					$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
					$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
					$REG_DATE 			= $data['REG_DATE'];
					$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
					$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
					$INSTITUTE_OWNER_NAME= $data['INSTITUTE_OWNER_NAME'];
					$EMAIL 				= $data['EMAIL'];
					$MOBILE 			= $data['MOBILE'];
					$CITY_NAME 			= $data['CITY_NAME'];
					$CREDIT 			= $data['CREDIT'];
					$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
					$ACTIVE 			= $data['ACTIVE'];
					$VERIFIED 			= $data['VERIFIED'];
					$DISPLAY_ON_WEBSITE = $data['DISPLAY_ON_WEBSITE'];
					if($ACTIVE==1) $ACTIVE= 'Active';
					elseif($ACTIVE==0) $ACTIVE= 'In-Active';
					if($VERIFIED==1) $VERIFIED= 'Yes';
					elseif($VERIFIED==0) $VERIFIED= 'No';
					/*$PHOTO = '../uploads/default_user.png';*/
					$PHOTO = SHOW_IMG_AWS.'/default_user.png';
					//if($STAFF_PHOTO!='')
					//	$PHOTO = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$STAFF_PHOTO;
					$class = '';
					if($DISPLAY_ON_WEBSITE==1)
					{
					$editLink = " <label>
                  <input type='checkbox' value='1' id='inst-$INSTITUTE_ID' class='flat-red visible-inst' checked='checked'>
                </label>
					";
					
					}
					if($DISPLAY_ON_WEBSITE==0)
					{
					$editLink = " <label>
							  <input type='checkbox' id='inst-$INSTITUTE_ID' value='0' class='flat-red visible-inst'>
							</label>
					";
					}
					if($DISPLAY_ON_WEBSITE==1)
					$DISPLAY_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstVisibility('.$INSTITUTE_ID.',0)"><i class="fa fa-check"></i></a>';
					elseif($DISPLAY_ON_WEBSITE==0)	
					$DISPLAY_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstVisibility('.$INSTITUTE_ID.',1)"><i class="fa fa-times"></i></a>';	
					
					$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');
					echo " <tr id='inst-id-$INSTITUTE_ID' $class>
							<td>$srno</td>
							<td>$logo</td>
							<td>$INSTITUTE_NAME</td>
							<td>$INSTITUTE_CODE</td>					
							<td>$CITY_NAME</td>					
							<td id='status-$INSTITUTE_ID'>$DISPLAY_ON_WEBSITE</td>
                           </tr>";
					$srno++;
				}
			}
			
			?>
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

     
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
  
  <!-- modal to send email -->
  	<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
		  <div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
			 
			  <div class="box box-primary modal-body">
				 <div class="">
					<div class="box-header with-border">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h3 class="box-title">Compose New Message</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
					  <div class="form-group">
						<input class="form-control" placeholder="To:">
					  </div>
					  <div class="form-group">
						<input class="form-control" placeholder="Subject:">
					  </div>
					  <div class="form-group">
							<textarea id="compose-textarea" class="form-control" style="height: 150px">
							 
							</textarea>
					  </div>
					  <div class="form-group">
						
						<p class="help-block">Messages</p>
					  </div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
					  <div class="pull-right">
						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
						<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
					  </div>					 
					</div>
					<!-- /.box-footer -->
				  </div>
				 </div>
			</div>
		  </div>
		</div>