    <div class="content-wrapper">
      <div class="col-lg-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Download Material Section
              <a href="/website_management/addDownload" class="btn btn-primary" style="float: right">Add Material</a>
            </h4>

            <div class="table-responsive pt-3">
              <table id="order-listing" class="table">
                <thead>
                  <tr class="tableRowColor">
                    <th>
                      #
                    </th>
                    <th>
                      Title
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
                  $res = $websiteManage->list_download_materials('', '');
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      extract($data);
                      $photo = '';
                      $photo = DOWNLOADMATERIAL_PATH . '/' . $id . '/' . $files;

                      $action = '';
                      $action = "<a href='/website_management/editDownload&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                      $action .= "<a href='javascript:void(0)' onclick='deleteDownload($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";

                      echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td>$title</td>
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