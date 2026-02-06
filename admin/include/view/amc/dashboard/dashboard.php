<?php
include_once('include/classes/amc.class.php');
$amc = new amc();
$user_id   = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
include_once('include/classes/institute.class.php');
$institute = new institute();
$totalc = 0.00;
$total_paid_comission = $institute->comission_paid1_count($user_id);
$total_unpaid_comission = $institute->comission_unpaid_count($user_id);
$totalc = $total_paid_comission + $total_unpaid_comission;


$totalcommission = $institute->total_commission($user_id);

$res = $amc->list_amc($user_id, '');
if ($res != '') {
  //	$srno=1;
  while ($data = $res->fetch_assoc()) {
    $VERIFIED       = $data['VERIFIED'];
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->

    <?php if ($VERIFIED == 1) { ?>
      <div class="row">
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <h3><?php echo $total_count = $institute->list_Assign_count($user_id); ?></h3>
              <p>Assign Institute </p>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <!--<div class="col-lg-3 col-xs-6 text-center">
          
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">			
              <h3><i class="fa fa-inr"></i> &nbsp; <?php echo  $totalcommission * 0.15;  ?> </h3>
              <p>Total Commission</p>
            </div>
          </div>
        </div>
         <div class="col-lg-3 col-xs-6 text-center">
         
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">		
              <h3><i class="fa fa-inr"></i> &nbsp; <?php echo $total_paid_comission * 0.15;   ?></h3>
              <p>Total Paid Commission</p>
            </div>
          </div>
        </div>
         <div class="col-lg-3 col-xs-6 text-center">
        
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">		
              <h3><i class="fa fa-inr"></i> &nbsp; <?php echo $total_unpaid_comission * 0.15; ?></h3>
              <p>Total Unpaid Commission</p>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>-->

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <h3><?php echo $total_HELP = $institute->helpSupport(); ?></h3>
              <p>Help Support</p>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <h4>CERTIFICATE</h4>
              <p><a href="page.php?page=view-cert" target="_blank" class="btn btn-sm btn-success">Download <i class="fa fa-arrow-circle-right"></i></a> <br></p>
            </div>
          </div>
        </div>
      <?php } else { ?>

        <p style="text-align: center;font-size: 47px;margin-top: 144px;font-style: italic;font-family: monospace;font-weight: bolder;color:tomato;">Your AMC Registration is Under Approval. Please Wait For Confirmation! DITRP Will Send Email Regarding Your AMC Approval.</p><?php } ?>
      <!-- ./col -->
      </div>
      <!-- /.row -->
      <hr>
  </section>
</div>