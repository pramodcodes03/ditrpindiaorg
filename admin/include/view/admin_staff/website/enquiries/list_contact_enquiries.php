 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Recieved Contact Enquiries On Website
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"> Enquiries</a></li>
        <li class="active"> Recieved Contact Enquiriess On Website</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
			
      <div class="row">
		
	
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Recieved Contact Enquiries On Website</h3>
			
            </div>
            <!-- /.box-header -->
            <div class="box-body">
			 
			 <table id="example1" class="table table-bordered table-striped table-hover data-tbl">
                <thead>
                <tr>
                  <th>Sr.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>               
                  <th>Message</th>               
                  <th align="middle">Date</th>
                </tr>
                </thead>
                <tbody>
			<?php
			$sql ="SELECT *, CONCAT(FNAME,' ',LNAME) AS NAME, DATE_FORMAT(CREATED_ON,'%d-%m-%Y %h:%i %p') AS CREATED_DATE FROm contact_enquiry ORDER BY CREATED_ON DESC";
			$res = $db->execQuery($sql);
			if($res!='')
			{
				$srno=1;
				while($data = $res->fetch_assoc())
				{
					extract($data);					
					echo " <tr id='inst-id-$CONTACT_ID'>
							<td>$srno</td>
							<td>$NAME</td>
							<td>$EMAIL</td>
							<td>$MOBILE</td>					
							<td>$MESSAGE</td>					
							<td id='status-$CONTACT_ID'>$CREATED_DATE</td>
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