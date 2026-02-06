    <div class="content-wrapper">
      <div class="col-lg-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Testimonial Section
              <a href="/website_management/addTestimonial" class="btn btn-primary" style="float: right">Add Testimonial</a>
            </h4>


            <div class="table-responsive pt-3">
              <table id="order-listing" class="table">
                <thead>
                  <tr class="tableRowColor">
                    <th>
                      #
                    </th>
                    <th>
                      Testimonial Image
                    </th>
                    <th>
                      Testimonial Name
                    </th>
                    <th>
                      Testimonial Description
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
                  $res = $websiteManage->list_testimonial('', '');
                  if ($res != '') {
                    $srno = 1;
                    while ($data = $res->fetch_assoc()) {
                      extract($data);
                      $photo = '';
                      $photo = TESTIMONIAL_PATH . '/' . $id . '/' . $image;

                      $action = '';
                      $action = "<a href='/website_management/editTestimonial&id=$id ' class='btn btn-primary btn-sm' title='Edit'><i class=' fa fa-pencil'></i>Edit</a>";

                      $action .= "<a href='javascript:void(0)' onclick='deleteTestimonial($id)' class='btn btn-danger btn-sm ' title='Delete'><i class='fa fa-trash'></i>Delete</a>";

                      echo " <tr id='id" . $id . "'>                               
                                      <td>$srno</td>
                                      <td><img src='$photo' style='width:150px; height:100%; border-radius:0;'/></td>
                                      <td>$name</td>
                                      <td>$description</td>
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