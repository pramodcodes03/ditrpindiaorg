<?php

$user_id = $_SESSION['user_id'];
$user_role =$_SESSION['user_role'];
	
$search 	= isset($_POST['search'])?$_POST['search']:'';
$wallet_id 	= isset($_REQUEST['wallet'])?$_REQUEST['wallet']:'';
$datefrom 	= isset($_REQUEST['datefrom'])?$_REQUEST['datefrom']:'';
$dateto 	= isset($_REQUEST['dateto'])?$_REQUEST['dateto']:'';
$paymentmode1= isset($_REQUEST['paymentmode1'])?$_REQUEST['paymentmode1']:'';

$cond = '';

$trantype= isset($_REQUEST['trantype'])?$_REQUEST['trantype']:'';

if($trantype!=''){
  $cond = " AND TRANSACTION_TYPE='$trantype'";   
}


if($datefrom!='' && $dateto!='') 
{
	$datefrom1 = date('Y-m-d', strtotime($datefrom));
	$dateto1 = date('Y-m-d', strtotime($dateto));
	$cond = " AND A.CREATED_ON BETWEEN '$datefrom1' AND '$dateto1'";
}

?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Wallet Recharge History
				 	<form action="export.php" method="post" class=""  style="margin-left:25px; float: right">
						<input type="hidden" value="wallet_export" name="action" />
						<button type="submit" name="export" value="Export" class="btn btn-primary"><i class="fa fa-file-excel-o"></i>  Export</button>
					</form>                 
                  </h4>  
				  	<?php
						if(isset($success))
						{
						?>
						<div class="row">
						<div class="col-sm-12">
						<div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							<h4><i class="icon fa fa-check"></i> <?= ($success==true)?'Success':'Error' ?>:</h4>
							<?= isset($message)?$message:'Please correct the errors.'; ?>
						</div>
						</div>
						</div>
						<?php
						}
					?>
                                 
                  <div class="table-responsive pt-3">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr class="tableRowColor">
							<th>#</th>
							<th>Transaction No</th>
							<th>Name</th>					
							<th>Mode</th>	
							<th>Status</th>							
							<th>Transaction Type</th>
							<th>Amount</th>		
							<th>Recharge Date</th>
											
                        </tr>
                      </thead>
                      <tbody>
					  	<?php
							include('include/classes/admin.class.php');
							$admin = new admin();            
							$BONUS_STAUS = '';
							$history = $admin->get_recharge_history('','',$user_id,$user_role,'');
							arsort($history);
							//print_r($history);
							$walletres = $access->get_wallet('','','');
			
							if(!empty($history))
							{			
								$sr=1;
								foreach($history as $trans=>$transArr)
								{
									if(is_array($transArr) && !empty($transArr))
									{
										extract($transArr); 
									
										echo "<tr>
												<td>$sr</td>
												<td>#$TRANSACTION_NO</td>
												<td>$USER_FULLNAME</td>	
												<td>$PAYMENT_MODE</td>
												<td>$STATUS</td>	
												<td>$TRANSACTION_TYPE</td>				<td>$AMOUNT</td>
												<td>$CREATED_DATE</td>
											
											</tr>";
									}
									$sr++;
									
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