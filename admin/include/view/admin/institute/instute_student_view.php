    <div class="card-body">
        <h4 class="card-title">Student list
        </h4>
        <?php
          include_once 'new_db_conection.php';
      		echo $userName = $_GET['userName'];
            echo $passWord = $_GET['passWord'];
            echo $dataBase = $_GET['dataBase'];
      		$dbConn = new mysqli('localhost',"$userName","$passWord","$dataBase");
      print_r($dbConn);
      
            echo $sqlCountStudent = "SELECT A.*, (SELECT STATE_NAME FROM states_master B WHERE B.STATE_ID = A.STUDENT_STATE) as STATE FROM student_details A WHERE 1";
            $resultCountStudent = $dbConn->query($sqlCountStudent);   
            if(mysqli_num_rows($resultCountStudent) > 0){
              while($data1 = mysqli_fetch_assoc($resultCountStudent)){
                 extract($data1);
                print_r($data1); exit();
              }
            }
      
      
        ?>
        <?php
        if (mysqli_num_rows($resultCountStudent) > 0) {
        ?>
            <div class="table-responsive pt-3">
                <div id="order-listing_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                        </div>
                        <div class="col-sm-12 col-md-6">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                                <thead>
                                    <tr role="row">
                                        <th>Sr.No</th>
                                        <th>Student Name</th>  
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>DOB</th>
                                        <th>City</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                               <tbody>
                                <?php
                                $i = 0;
                                while ($row = mysqli_fetch_array($resultCountStudent)) {
                                ?>
                                   
                                        <tr id="row-1" class="odd">
                                           <td><?= $STUDENT_ID  ?></td>
                                           <td><?= $STUDENT_FNAME  ?><?= $STUDENT_MNAME  ?><?= $STUDENT_LNAME  ?></td>
                                           <td><?= $STUDENT_EMAIL  ?></td>
                                           <td><?= $STUDENT_MOBILE  ?></td>
                                           <td><?= $STUDENT_DOB  ?></td>
                                           <td><?= $STUDENT_CITY  ?></td>
                                           <td><?= $STATE  ?></td>    
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                        ?>
                                    <?php
                                } else {
                                    echo "No result found";
                                }
                                    ?>
                                      
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>