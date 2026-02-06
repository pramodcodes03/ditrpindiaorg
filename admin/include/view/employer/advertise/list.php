<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>

<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Advertise Section
          <a href="page.php?page=addAdvertisement" class="btn btn-primary" style="float: right">Add Advertise</a>
        </h4>

        <div class="table-responsive pt-3">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>
                  #
                </th>
                <th>
                  Advertise Image
                </th>
                <th>
                  Advertise Title
                </th>
                <th>
                  Advertise Link
                </th>
                <th>
                  Action
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
              include_once('include/classes/tools.class.php');
              $tools = new tools();
              $res = $tools->list_advertise('', " AND inst_id = $user_id");
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);
                  $photo = '';
                  $photo = IMS_ADVERTISE_PATH . '/' . $id . '/' . $image;

                  $action = '';
                  $action = "<a href='page.php?page=updateAdvertisement&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                  $action .= "<a href='javascript:void(0)' onclick='deleteupdateAdvertisement($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";

                  echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td><img src='$photo' style='width:150px; height:100%; border-radius:0;'/></td>
                                      <td>$name</td> 
                                      <td>$link</td>                                    
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