<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Logo Management (Logo Size 200 pixel width * 150 pixel height)</h4>

        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>
                  #
                </th>
                <th>
                  Logo Title
                </th>
                <th>
                  Logo Image
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
              $res = $websiteManage->list_logo('', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);
                  $photo = '';
                  $photo = LOGO_PATH . '/' . $id . '/' . $image;

                  $action = '';
                  $action = "<a href='/website_management/editLogo&id=$id' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                  echo " <tr id='id" . $id . "'>
                 
                        <td>$srno</td>  
                        <td>$name</td>
                        <td><img src='$photo' style='width:50px; height:50px'/></td>
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