<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Box Section</h4>

        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>
                  #
                </th>
                <th>
                  Box Title 1
                </th>
                <th>
                  Box Title 2
                </th>
                <th>
                  Box Title 3
                </th>
                <th>
                  Box Title 4
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
              $res = $websiteManage->list_sliderbox('', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);
                  $action = '';
                  $action = "<a href='/website_management/editSliderBox&id=$id' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                  echo " <tr id='id" . $id . "'>
                 
                        <td>$srno</td>  
                        <td>$box1_title</td>
                        <td>$box2_title</td>
                        <td>$box3_title</td>
                        <td>$box4_title</td>
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