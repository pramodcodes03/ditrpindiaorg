<?php
$action = isset($_POST['update_status']) ? $_POST['update_status'] : '';
if ($action != '') {
  $inst_id = isset($_POST['inst_id']) ? $_POST['inst_id'] : '';
  $active_status = isset($_POST['active_status']) ? $_POST['active_status'] : '';
  $dbUser = isset($_POST['dbUser']) ? $_POST['dbUser'] : '';
  $dbPassword = isset($_POST['dbPassword']) ? $_POST['dbPassword'] : '';
  $dbName = isset($_POST['dbName']) ? $_POST['dbName'] : '';


  $dbConn897 = new mysqli('localhost', "$dbUser", "$dbPassword", "$dbName");

  $sqlStatus1 = "UPDATE institute_details SET ACTIVE='$active_status' WHERE 1";
  $sqlStatus2 = "UPDATE user_login_master SET ACTIVE='$active_status' WHERE USER_ROLE != 1";

  $resultStatus1 = $dbConn897->query($sqlStatus1);
  $resultStatus2 = $dbConn897->query($sqlStatus2);

  if ($resultStatus1 && $resultStatus2) {
    header('location:page.php?page=listInstitute');
  } else {
    echo '<script>alert("Falied")</script>';
  }
}
?>

<style>
  .modal-dialog,
  .modal-content {
    /* 100% of window height */
    height: 95%;
  }

  .modal-body {
    /* 100% = dialog height, 120px = header + footer */
    overflow-y: scroll;
  }

  #allocate-table-body {
    max-height: calc(100vh - 100px);
    overflow-y: auto;
  }

  .modalHeadTbl {
    display: table;
    width: 100%;
  }

  .modalHeadTbl h4 {
    display: table-cell;
    font-size: 14px;
    border: 1px solid #333;
    padding: 10px;
  }

  .modalDataTbl {
    display: table;
    width: 100%;
  }

  .modalDataTbl p {
    display: table-cell;
    font-size: 14px;
    border: 1px solid #333;
    padding: 10px;
  }
</style>

<div class="card-body">
  <h4 class="card-title">Center List
  </h4>
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
                <th>ACTIVE STATUS</th>
                <th>DOMAIN NAME</th>
                <th>CENTER NAME </th>
                <th>NUMBER OF ADMISION</th>
                <th>ASSIGNED ADMISION</th>
                <th>NUMBER OF FRANCHISE</th>
                <th>PURCHASE DATE</th>
                <th>EXPIRE DATE</th>
                <th>EMAIL </th>
                <th>CONTACT </th>
                <th>USER NAME </th>
                <th>VIEW STUDENT </th>
                <th>PLAN</th>
                <th>ACTION</th>

              </tr>
            </thead>
            <tbody>
              <?php
              include_once('new_db_conection.php');

              $length = count($dataBaseArray);

              for ($i = 0; $i < $length; $i++) {
                $userName = $dataBaseArray[$i][0];
                $passWord = $dataBaseArray[$i][1];
                $dataBase = $dataBaseArray[$i][2];

                $ratnesh = "'$userName','$passWord','$dataBase'";
                $dbConn = new mysqli('localhost', "$userName", "$passWord", "$dataBase");

                $sql = "SELECT A.*, B.ACTIVE AS ACTIVATED_STATUS, B.USER_NAME FROM institute_details A LEFT JOIN user_login_master B ON A.INSTITUTE_ID = B.USER_ID WHERE B.USER_ROLE =2";
                $result = $dbConn->query($sql);
                //print_r($result); exit();

                $sqlCountStudent = "SELECT  count(STUDENT_ID) as total from student_course_details WHERE 1";
                $resultCountStudent = $dbConn->query($sqlCountStudent);
                if (mysqli_num_rows($resultCountStudent) > 0) {
                  while ($data1 = mysqli_fetch_assoc($resultCountStudent)) {
                    $countStu = $data1['total'];
                  }
                }

                $sqlCountFranchise = "SELECT  count(INSTITUTE_ID) as total from institute_details WHERE INSTITUTE_ID != 1";
                $resultCountFranchise = $dbConn->query($sqlCountFranchise);
                if (mysqli_num_rows($resultCountFranchise) > 0) {
                  while ($data2 = mysqli_fetch_assoc($resultCountFranchise)) {
                    $countFranchise = $data2['total'];
                  }
                }

                if ($result) {
                  if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                      extract($data);
                      $sqlDM = "SELECT C.id,C.domain_purchase_date, C.domain_expire_date, C.remark, C.admission_point FROM domain_management C WHERE C.domain_name = '$DOMAIN_NAME'";
                      $resultDM = $conn1->query($sqlDM);
                      if ($resultDM) {
                        if (mysqli_num_rows($resultDM) > 0) {
                          while ($dataDM = mysqli_fetch_assoc($resultDM)) {
                            $id = $dataDM['id'];
                            $domain_purchase_date = $dataDM['domain_purchase_date'];
                            $domain_expire_date = $dataDM['domain_expire_date'];
                            $remark = $dataDM['remark'];
                            $admission_point = $dataDM['admission_point'];
                          }
                        }
                      }

              ?>
                      <tr id="row-1" class="odd">
                        <td id="status-1" style="font-size: 18px;">
                          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="inst_id" value="1" />
                            <input type="hidden" name="dbUser" value="<?php echo $userName ?>" />
                            <input type="hidden" name="dbPassword" value="<?php echo $passWord ?>" />
                            <input type="hidden" name="dbName" value="<?php echo $dataBase ?>" />
                            <?php if ($ACTIVATED_STATUS == 1) { ?>
                              <input type="hidden" name="active_status" value="0" />
                              <input type="submit" name="update_status" class="btn btn-success mr-2" value="Active">
                            <?php } ?>
                            <?php if ($ACTIVATED_STATUS == 0) { ?>
                              <input type="hidden" name="active_status" value="1" />
                              <input type="submit" name="update_status" class="btn btn-danger mr-2" value="In-Active">
                            <?php } ?>

                          </form>
                        </td>
                        <td><?= $DOMAIN_NAME ?></td>
                        <td><?= $INSTITUTE_NAME ?></td>
                        <td><?= $countStu ?></td>
                        <td><?= $admission_point ?></td>
                        <td><?= $countFranchise ?></td>
                        <td><?php echo date("d-m-Y", strtotime($domain_purchase_date)) ?></td>
                        <td><?php echo date("d-m-Y", strtotime($domain_expire_date)) ?></td>
                        <td><?= $EMAIL ?></td>
                        <td><?= $MOBILE ?></td>
                        <td><?= $USER_NAME ?></td>
                        <td>
                          <a title='Share' data-toggle='modal' data-target='#shareModal<?= $id ?>' class="btn btn-primary table-btn"><i class="mdi mdi-eye ml-1"></i></a>
                          <a class=" btn btn-primary  text-white mt-2 p-2">Total student</a>
                          <button class="btn btn-primary table-btn" title="TOTAL STUDENT"><?= $countStu ?></button>
                        </td>
                        <td></td>

                        <td>
                          <a href="page.php?page=tbledit&domain=<?= $id ?>" class="btn btn-primary table-btn"><i class="mdi mdi-grease-pencil" style="font-size:36px"></i></a>
                        </td>

                      </tr>

                      <div class="modal fade" id="shareModal<?= $id ?>">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Student Details</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <?php
                              $srNo = 1;
                              $sqlCountStudent = "SELECT A.*,B.ABBREVIATION,B.STUDENT_CODE,B.STUDENT_FNAME,B.STUDENT_MNAME,B.STUDENT_LNAME,B.STUDENT_MOTHERNAME,B.STUDENT_DOB,B.STUDENT_GENDER,B.STUDENT_MOBILE,B.STUDENT_MOBILE2,B.STUDENT_EMAIL,B.STUDENT_TEMP_ADD,B.STUDENT_PER_ADD,B.STUDENT_STATE,B.STUDENT_CITY,B.STUDENT_PINCODE,B.STUDENT_ADHAR_NUMBER,B.EDUCATIONAL_QUALIFICATION,B.OCCUPATION,B.INTERESTS,B.CERT_MNAME,B.CERT_LNAME,B.SONOF,B.DATE_JOINING,B.ENQUIRY_ID,B.STUD_LANG,B.VERIFIED,B.CASTE, (SELECT STATE_NAME FROM states_master C WHERE C.STATE_ID = B.STUDENT_STATE) as STATE FROM student_course_details A LEFT JOIN student_details B ON A.STUDENT_ID=B.STUDENT_ID  WHERE 1";
                              $resultCountStudent = $dbConn->query($sqlCountStudent);
                              if (mysqli_num_rows($resultCountStudent) > 0) {
                                while ($data1 = mysqli_fetch_assoc($resultCountStudent)) {
                                  extract($data1);

                              ?>
                                  <div class="modalDataTbl">
                                    <p><?= $srNo  ?></p>
                                    <p><?= $STUDENT_FNAME  ?><?= $STUDENT_MNAME  ?><?= $STUDENT_LNAME  ?></p>
                                    <p><?= $STUDENT_EMAIL  ?></p>
                                    <p><?= $STUDENT_MOBILE  ?></p>
                                    <p><?= $STUDENT_DOB  ?></p>
                                    <p><?= $STUDENT_CITY  ?></p>
                                    <p><?= $STATE  ?></p>
                                  </div>

                              <?php
                                  $srNo++;
                                }
                              }

                              ?>

                            </div>
                          </div>
                        </div>
                      </div>

              <?php
                    }
                  }
                }
                // closing connection 
                mysqli_close($dbConn);
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>