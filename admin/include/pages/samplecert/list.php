    <div class="content-wrapper">
      <div class="col-lg-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Sample Certificates
              <a href="/website_management/addsampleCert" class="btn btn-primary" style="float: right">Add Certificates</a>
            </h4>

            <div class="table-responsive pt-3">
              <table id="order-listing" class="table">
                <thead>
                  <tr class="tableRowColor">
                    <th>
                      #
                    </th>
                    <th>
                      Image
                    </th>
                    <th>
                      Type
                    </th>
                    <th>
                      Title
                    </th>
                    <th>
                      Position
                    </th>
                    <th>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include_once('include/classes/websiteManage.class.php');
                  $websiteManage = new websiteManage();
                  $res = $websiteManage->list_sample_certificates('', '');
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      extract($data);
                      $photo = '';
                      $photo = WEBSITES_SAMPLE_CERT_PATH . '/' . $id . '/' . $image;

                      $action = '';
                      $action = "<a href='/website_management/editsampleCert&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                      $action .= "<a href='javascript:void(0)' onclick='deleteSampleCert($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";

                      echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td><img src='$photo' style='width:150px; height:100%; border-radius:0;'/></td>
                                      <td>$type</td>
                                      <td>$name</td>
                                      <td>$position</td>
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