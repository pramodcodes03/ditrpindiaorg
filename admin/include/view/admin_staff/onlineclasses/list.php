    <div class="content-wrapper">
      <div class="col-lg-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">List Online Classes
              <a href="page.php?page=addOnlineClasses" class="btn btn-primary" style="float: right">Add Class Details</a>
            </h4>

            <div class="table-responsive pt-3">
              <table id="order-listing" class="table">
                <thead>
                  <tr class="tableRowColor">
                    <th>
                      #
                    </th>
                    <th>
                      Course Name
                    </th>
                    <th>
                      Name
                    </th>
                    <th>
                      Link
                    </th>
                    <th>
                      Expiry Date
                    </th>
                    <th>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                  $user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
                  if ($user_role == 5) {
                    $institute_id = $db->get_parent_id($user_role, $user_id);
                    $staff_id = $user_id;
                  } else {
                    $institute_id = $user_id;
                    $staff_id = 0;
                  }
                  $cond = " AND inst_id='$institute_id'";
                  include_once('include/classes/tools.class.php');
                  $tools = new tools();
                  $res = $tools->list_onlineclasses_details('', $cond);
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      extract($data);
                      $expirydate = date("d-m-Y", strtotime($expirydate));
                      $action = '';
                      $action = "<a href='page.php?page=updateOnlineClasses&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                      $action .= "<a href='javascript:void(0)' onclick='deleteOnlineClasses($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";
                      $course_name = $db->get_inst_course_name($course_id);

                      echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td>$course_name</td>
                                      <td>$title</td>
                                      <td>$link</td>
                                      <td>$expirydate</td>
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