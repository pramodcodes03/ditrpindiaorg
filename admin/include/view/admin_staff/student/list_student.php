    <div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Student Enquiry</h4>                       
                  <div class="table-responsive pt-3">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                          <th>
                            #
                          </th>
                          <th>
                            Name
                          </th>
                          <th>
                            Mobile Number
                          </th>
                          <th>
                            Email Id
                          </th>   
                          <th>
                            Message
                          </th> 
                        </tr>
                      </thead>
                      <tbody>
                         <?php
                              include_once('include/classes/websiteManage.class.php');
                              $websiteManage = new websiteManage();
                              $res = $websiteManage->list_student_website_enquiry('','');
                              if($res!='')
                              {
                                $srno=1;
                                while($data = $res->fetch_assoc())
                                {
                                  extract($data); 
                                 
                                  echo " <tr id='id".$id ."'>                               
                                      <td>$srno</td>
                                      <td>$name</td>
                                      <td>$mobile</td>
                                      <td>$emailid</td>
                                      <td>$message</td>
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