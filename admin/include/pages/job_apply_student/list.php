<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Job enquiries</h4>                       
        <div class="table-responsive pt-3">
          <table id="order-listing" class="table">
            <thead>
              <tr class="tableRowColor">
                <th>
                  #
                </th>
                <th>
                  Student Name
                </th>
                <th>
                  Job Title
                </th>
                <th>
                  Email Id
                </th>   
                <th>
                  Mobile Number
                </th>                         
                <th>
                  Message
                </th> 
                <th>
                  Date
                </th> 
              </tr>
            </thead>
            <tbody>
               <?php
                    include_once('include/classes/websiteManage.class.php');
                    $websiteManage = new websiteManage();
                    $res = $websiteManage->list_job_apply_student('','');
                    if($res!='')
                    {
                      $srno=1;
                      while($data = $res->fetch_assoc())
                      {
                        extract($data);                      
                        $created_at = date("d-m-Y",strtotime($created_at));
                        echo " <tr id='id".$id ."'>                               
                            <td>$srno</td>
                            <td>$name</td>
                            <td>$job_name</td>
                            <td>$email_id</td>
                            <td>$mobile</td>                                      
                            <td>$message</td>
                            <td>$created_at</td>
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