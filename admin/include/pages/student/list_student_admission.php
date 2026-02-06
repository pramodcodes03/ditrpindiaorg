    <div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Student Admission</h4>                       
                  <div class="table-responsive pt-3">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr class="tableRowColor">
                          <th>
                            #
                          </th>
                          <th>
                            Name
                          </th>
                          <th>
                            Course Name
                          </th>
                          <th>
                            Mobile Number
                          </th>
                          <th>
                            Email Id
                          </th>   
                          <th>
                            Address
                          </th>
                          <th>
                            City
                          </th> 
                          <th>
                            State
                          </th>
                          <th>
                            Pincode
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php
                              include_once('include/classes/websiteManage.class.php');
                              $websiteManage = new websiteManage();
                              $res = $websiteManage->list_student_admission('','');
                              if($res!='')
                              {
                                $srno=1;
                                while($data = $res->fetch_assoc())
                                {
                                  extract($data); 
                                  $stateName = $db->get_state_name($state);
                                  echo " <tr id='id".$id ."'>                               
                                      <td>$srno</td>
                                      <td>$fname $lname</td>
                                      <td>$course_name</td>
                                      <td>$phone</td>
                                      <td>$email</td>
                                      <td>$address</td>
                                      <td>$city</td>
                                      <td>$stateName</td>
                                      <td>$pincode</td>
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