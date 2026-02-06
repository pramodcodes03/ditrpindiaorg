<?php
//print_r($_SESSION);exit;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>
<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">List Admin Support
          <a href="page.php?page=addAdminSupport" class="btn btn-primary" style="float: right">Add Support</a>
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
                <th>Query</th>
                <th>Admin Reply</th>
                <th>Current Status</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once('include/classes/helpsupport.class.php');
              $helpsupport = new helpsupport();
              $res = $helpsupport->list_support('', " AND INSTITUTE_ID = $user_id AND ADMIN_ID = '1' ");
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  $TICKET_ID         = $data['TICKET_ID'];
                  $STUDENT_ID     = $data['STUDENT_ID'];
                  $SUPPORT_TYPE_ID  = $data['SUPPORT_TYPE_ID'];
                  $SUPPORT_CAT_ID   = $data['SUPPORT_CAT_ID'];
                  $DESCRIPTION      = $data['DESCRIPTION'];
                  $AUTHOR_NAME      = $data['AUTHOR_NAME'];
                  $MOBILE           = $data['MOBILE'];
                  $ALT_MOBILE       = $data['ALT_MOBILE'];
                  $EMAIL            = $data['EMAIL'];
                  $ALT_EMAIL        = $data['ALT_EMAIL'];
                  $RATING   = $data['RATING'];
                  $ADMIN_UPDATES           = $data['ADMIN_UPDATES'];
                  $CURRENT_STATUS   = $data['CURRENT_STATUS'];

                  $ACTIVE        = $data['ACTIVE'];
                  $CREATED_BY   = $data['CREATED_BY'];
                  $CREATED_ON   = $data['CREATED_ON'];

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
                  $action = "<a href='page.php?page=replyAdminSupport&id=$TICKET_ID' class='btn btn-primary btn1' title='View Help Support'><i class='fa fa-envelope'> View</i></a> ";

                  // $action .= "<a href='javascript:void(0)' data-id='".$TICKET_ID."' data-rating='".$RATING."' class='btn btn-primary send-rating-details' title='Rating' data-toggle='modal' data-target='.rating-details' ><i class='fa fa-star'> Rating</i></a>";

                  echo "<tr id='supportId-" . $TICKET_ID . "'>							
                    <td>$srno</td>    
                    <td>$DESCRIPTION</td>                                     
                    <td>$ADMIN_UPDATES</td>                       
                    <td>$CURRENT_STATUS</td>                                 		
                    <td id='status-$TICKET_ID'>$ACTIVE</td>
                    <td>$CREATED_ON</td> 
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


<!-- modal to send email -->
<div class="modal fade rating-details" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <img src="resources/dist/img/loader.gif" class="loader-mg-modal" />
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">

      <div class="box box-primary modal-body">
        <div class="">
          <div class="box-header with-border">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <!-- /.box-header -->
          <form id="save_ticket_rating_form" method="post" class="form-horizontal">
            <div id="message" class="alert alert-warning" style="display:none">ksgdjsnvd ms </div>
            <input type="hidden" id="ticketId" name="ticket_id" id="" value="" />
            <input type="hidden" id="ticketRating" name="ticket_rating" id="" value="0" />
            <input type="hidden" name="action" id="action" value="save_ticket_rating" />
            <header class='header text-center'>
              <h2>Rating</h2>
            </header>
            <section class='rating-widget'>
              <!-- Rating Stars Box -->
              <div class='rating-stars text-center'>
                <ul id='stars'>
                  <li class='star' title='Poor' data-value='1'>
                    <i class='fa fa-star fa-fw'></i>
                  </li>
                  <li class='star' title='Fair' data-value='2'>
                    <i class='fa fa-star fa-fw'></i>
                  </li>
                  <li class='star' title='Good' data-value='3'>
                    <i class='fa fa-star fa-fw'></i>
                  </li>
                  <li class='star' title='Excellent' data-value='4'>
                    <i class='fa fa-star fa-fw'></i>
                  </li>
                  <li class='star' title='WOW!!!' data-value='5'>
                    <i class='fa fa-star fa-fw'></i>
                  </li>
                </ul>
              </div>

              <div class='success-box'>
                <div class='clearfix'></div>
                <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K' />
                <div class='text-message'></div>
                <div class='clearfix'></div>
              </div>
            </section>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="submit" name="" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Save</button>
              </div>
            </div>
          </form>
          <!-- /.box-footer -->
        </div>
      </div>
    </div>
  </div>
</div>