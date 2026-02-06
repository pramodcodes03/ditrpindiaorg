<?php
//print_r($_SESSION);exit;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

?>
<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">List Support </h4>
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
                <th>Date</th>
                <th>Institute Name</th>
                <th> Contact No</th>
                <th>Institute Reply</th>
                <th>Current Status</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once('include/classes/helpsupport.class.php');
              $helpsupport = new helpsupport();
              $res = $helpsupport->list_support('', " AND ADMIN_ID = $user_id ");
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  $TICKET_ID         = $data['TICKET_ID'];
                  $STUDENT_ID     = $data['STUDENT_ID'];
                  $INSTITUTE_ID     = $data['INSTITUTE_ID'];
                  $DESCRIPTION        = $data['DESCRIPTION'];
                  $MOBILE             = $data['MOBILE'];
                  $EMAIL              = $data['EMAIL'];
                  $CURRENT_STATUS     = $data['CURRENT_STATUS'];
                  $ADMIN_UPDATES      = $data['ADMIN_UPDATES'];
                  $STUDENT_NAME       = $data['STUDENT_NAME'];
                  $ACTIVE        = $data['ACTIVE'];
                  $CREATED_BY       = $data['CREATED_BY'];
                  $CREATED_ON       = $data['CREATED_ON'];

                  $INSTITUTE_NAME = $db->get_institute_name($INSTITUTE_ID);

                  if ($db->permission('update_course')) {
                    if ($ACTIVE == 1)
                      $ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
                    elseif ($ACTIVE == 0)
                      $ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
                  }

                  if ($db->permission('update_course')) {
                    if ($CURRENT_STATUS == 1)
                      $CURRENT_STATUS = '<span style="color: #ceb101; font-size: 14px; font-weight: 600;">Pending</span>';
                    elseif ($CURRENT_STATUS == 2)
                      $CURRENT_STATUS = '<span style="color:#f00; font-size: 14px; font-weight: 600;">Closed</span> ';
                  }

                  $action = '';

                  $action = "<a href='page.php?page=replySupport&id=$TICKET_ID' class='btn btn-primary btn1' title='View Help Support'><i class='fa fa-envelope'> View</i></a>";


                  echo "<tr id='supportId-" . $TICKET_ID . "'>							
                          <td>$srno</td>	
                          <td>$CREATED_ON</td>        							
                          <td>$INSTITUTE_NAME</td>
                          <td>$MOBILE</td>	                                            
                          <td>$ADMIN_UPDATES</td>    
                          <td>$CURRENT_STATUS</td>
                          <td id='status-$TICKET_ID'>$ACTIVE</td>
                          <td>$action</td>
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