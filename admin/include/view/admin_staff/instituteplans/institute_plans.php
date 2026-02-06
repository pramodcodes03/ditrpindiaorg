<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">List Institute Plans
          <a href="page.php?page=addPlans" class="btn btn-primary" style="float: right">Add Institute Plans</a>
        </h4>
        <?php
        if (isset($_SESSION['msg'])) {
          $message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
          $msg_flag = $_SESSION['msg_flag'];
        ?>
          <div class="row">
            <div class="col-sm-12">
              <div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
                <?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
              </div>
            </div>
          </div>
        <?php
          unset($_SESSION['msg']);
          unset($_SESSION['msg_flag']);
        }
        ?>
        <div class="table-responsive pt-3">
          <table id="order-listing" class="table">
            <thead>
              <tr>
                <th>Sr.</th>
                <th>Institute Plan Name</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once('include/classes/instituteplans.class.php');
              $instituteplans = new instituteplans();
              $res = $instituteplans->list_institue_plan('', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  $PLAN_ID     = $data['PLAN_ID'];
                  $PLAN_NAME     = $data['PLAN_NAME'];

                  $ACTIVE     = ($data['ACTIVE'] == 1) ? 'ACTIVE' : 'IN-ACTIVE';

                  $editLink = "";
                  $editLink .= "<a href='page.php?page=updatePlans&id=$PLAN_ID' class='btn btn-primary table-btn' title='Edit'><i class='  mdi mdi-grease-pencil'></i></a>";

                  $editLink .= "<a href='javascript:void(0)' class='btn btn-danger table-btn' title='Delete' onclick='deleteInstitutePlan($PLAN_ID)'><i class=' mdi mdi-delete'></i></a>";

                  echo " <tr id='row-$PLAN_ID'>
                  <td>$srno</td>							
                  <td>$PLAN_NAME</td>							
                  <td id='status-$PLAN_ID'>$ACTIVE</td>
                  <td>$editLink</td>
                              </tr>";
                  $srno++;
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