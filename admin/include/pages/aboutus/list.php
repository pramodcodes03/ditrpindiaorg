<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">About Us Section</h4>

        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>
                  #
                </th>
                <th>
                  Action
                </th>
                <th width="50%">
                  About Us Short Description
                </th>
              </tr>
            </thead>
            <tbody>

              <?php
              include_once('include/classes/websiteManage.class.php');
              $websiteManage = new websiteManage();
              $res = $websiteManage->list_about('', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);

                  $action = '';
                  $action = "<a href='/website_management/editAboutUs&id=$id' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                  echo " <tr id='id" . $id . "'>
                 
                        <td>$srno</td>  
                        <td>$action</td>
                        <td width='50%'>" . html_entity_decode($about_short) . "</td>
                       
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