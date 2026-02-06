    <div class="content-wrapper">
      <div class="col-lg-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Gallery Video Section
              <a href="/website_management/addGalleryVideos" class="btn btn-primary" style="float: right">Add Gallery Video</a>
              <a href="#" class="btn btn-warning" style="float: right; margin-right:20px;" target="_blank">How To Upload Videos</a>
            </h4>

            <div class="table-responsive pt-3">
              <table id="order-listing" class="table">
                <thead>
                  <tr class="tableRowColor">
                    <th>
                      #
                    </th>
                    <th>
                      Gallery Name
                    </th>
                    <th>
                      Gallery Video Link
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
                  $res = $websiteManage->list_galleryVideos('', '');
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      extract($data);

                      $action = '';
                      $action = "<a href='/website_management/editGalleryVideos&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                      $action .= "<a href='javascript:void(0)' onclick='deletegalleryVideos($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";

                      echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td>$name</td>
                                      <td>$video</td>
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