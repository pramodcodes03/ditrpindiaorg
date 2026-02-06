<?php

/* display exam results details */

$atccode     = $db->test(isset($_REQUEST['atccode']) ? $_REQUEST['atccode'] : '');
$institute = $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
$mobile    = $db->test(isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '');
$state     = $db->test(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');

$cond = '';
if ($institute != '') $cond .= " AND A.INSTITUTE_ID='$institute'";
if ($atccode != '') $cond  .= " AND A.INSTITUTE_ID='$atccode'";
if ($mobile != '') $cond .= " AND A.INSTITUTE_ID='$mobile'";
if ($state != '') $cond .= " AND A.STATE='$state'";


?>


<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Franchise
                    <a href="page.php?page=addFranchise" class="btn btn-primary" style="float: right">Add Franchise</a>
                    <form action="export.php" method="post" class="">
                        <input type="hidden" value="institute_export" name="action" />
                        <button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
                    </form>
                </h4>
                <?php
                if (isset($_SESSION['msg'])) {
                    $message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
                    $msg_flag = $_SESSION['msg_flag'];
                ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                                <h4><i class="icon mdi mdi-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
                                <?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
                            </div>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['msg']);
                    unset($_SESSION['msg_flag']);
                }
                ?>
                <div class="table-responsive pt-3">
                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Action</th>
                                <th>Logo</th>
                                <th>Student <br /><br /> Admission</th>
                                <th>Student <br /><br /> Enquiry</th>
                                <th>Institute <br /><br /> Name</th>
                                <th>Main <br /><br /> Wallet</th>

                                <!--<th>AMC Code (Ref)</th>-->
                                <!--<th>State</th>-->
                                <!--<th>City</th>-->
                                <!--<th>Pincode</th>-->
                                <!--<th>ATC Code</th>-->
                                <!--<th>Username</th>-->
                                <!--<th>Password</th>-->

                                <!--<th>Mobile</th>-->
                                <th>Approved</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$cond = " ";
                            $verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
                            if ($verified != '') {
                                $cond .= " AND A.VERIFIED='$verified'";
                            }
                            //$cond .=  " ORDER BY A.CREATED_ON DESC";

                            include_once('include/classes/institute.class.php');
                            include_once('include/classes/student.class.php');
                            $institute = new institute();
                            $student = new student();

                            /* Pagination Code */
                            $rec_limit = 50;

                            $sql = "SELECT COUNT(institute_details.INSTITUTE_ID) as total FROM institute_details WHERE institute_details.DELETE_FLAG=0";
                            $exc = $db->execQuery($sql);
                            $rec = $exc->fetch_assoc();
                            $rec_count = $rec['total'];

                            if (isset($_GET['pg'])) {
                                $page = $_GET['pg'] + 1;
                                $offset = $rec_limit * $page;
                            } else {
                                $page = 0;
                                $offset = 0;
                            }
                            $left_rec = $rec_count - ($page * $rec_limit);
                            $pageUrl = 'list-institutes';

                            $cond .= " AND B.USER_ROLE=8";

                            $res = $institute->list_institute('', $cond);
                            if ($res != '') {
                                $srno = 1;

                                while ($data = $res->fetch_assoc()) {
                                    $INSTITUTE_ID         = $data['INSTITUTE_ID'];
                                    $USER_LOGIN_ID         = $data['USER_LOGIN_ID'];
                                    $REG_DATE             = $data['REG_DATE'];
                                    $INSTITUTE_CODE     = $data['INSTITUTE_CODE'];
                                    $INSTITUTE_NAME     = $data['INSTITUTE_NAME'];
                                    $INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
                                    $EMAIL                 = $data['EMAIL'];
                                    $MOBILE             = $data['MOBILE'];
                                    $CREDIT             = $data['CREDIT'];
                                    $CREDIT_BALANCE     = $data['CREDIT_BALANCE'];
                                    $USER_NAME             = $data['USER_NAME'];
                                    $PASS_WORD             = $data['PASS_WORD'];
                                    $ACTIVE             = $data['ACTIVE'];

                                    $AMC_CODE             = $data['AMC_CODE'];
                                    $POSTCODE             = $data['POSTCODE'];

                                    $STATE_NAME             = $data['STATE_NAME'];
                                    $CITY             = $data['CITY'];
                                    $VERIFIED             = $data['VERIFIED'];
                                    $verify_flag             = $data['VERIFIED'];

                                    $GSTNO                 = $data['GSTNO'];
                                    $PRIMEMEMBER         = $data['PRIMEMEMBER'];
                                    $SHOW_ON_WEBSITE         = $data['SHOW_ON_WEBSITE'];
                                    $LOCATION         = $data['LOCATION'];

                                    if ($LOCATION !== '') {
                                        $LOCATION = $LOCATION;
                                    } else {
                                        $LOCATION = "#";
                                    }
                                    $color = '';
                                    if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
                                        $color = "style='background-color: #fffe47;font-weight: bold;'";
                                    }

                                    $count = '';
                                    $cond21  = " AND INSTITUTE_ID = $INSTITUTE_ID";
                                    $count = $student->get_admission_count($cond21);

                                    $count_enquiry = $student->get_admission_count_enquiry($cond21);

                                    if ($db->permission('update_institute')) {
                                        if ($ACTIVE == 1)
                                            $ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatus(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> YES</a>';
                                        elseif ($ACTIVE == 0)
                                            $ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatus(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> NO</a>';
                                    } else {
                                        if ($ACTIVE == 1)
                                            $ACTIVE = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
                                        elseif ($ACTIVE == 0)
                                            $ACTIVE = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
                                    }

                                    $performanceCert = "";

                                    // $performanceCert .= "<a href='print-performance-cert&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Performance Certificate' target='_blank'><i class='fa fa-trophy'></i></a>";

                                    // $performanceCert .= "<a href='print-performance-cert-cover&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Performance Certificate' target='_blank'><i class='fa fa-file-image-o'></i></a>";

                                    $printCert = "";

                                    if ($VERIFIED == 1) {
                                        $VERIFIED = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
                                        $printCert = "<a href='page.php?page=printFranchiseCertificate&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Certificate' target='_blank'><i class=' mdi mdi-certificate'></i></a>";

                                        $printCert .= "<a href='page.php?page=printFranchiseAddress&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Address' target='_blank'><i class=' mdi mdi-message-text'></i></a>";
                                    } elseif ($VERIFIED == 0) {
                                        $VERIFIED = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
                                    }


                                    $changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
                                    $changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';
                                    /*$PHOTO = '../uploads/default_user.png';*/
                                    $logo = '../uploads/default_user.png';
                                    //if($STAFF_PHOTO!='')
                                    //	$PHOTO = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$STAFF_PHOTO;

                                    $editLink = "";
                                    if ($db->permission('update_institute'))
                                        $editLink .= "<a href='page.php?page=updateFranchise&id=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

                                    if ($db->permission('delete_institute'))
                                        $deleteLink = " <a href='javascript:void(0)' class='btn btn-danger table-btn' title='Delete' onclick='deleteInstitute($INSTITUTE_ID)'><i class='  mdi mdi-delete'></i></a>";

                                    $editLink .= $printCert;

                                    $params = "'$USER_NAME','" . $PASS_WORD . "'";
                                    $loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";

                                    $logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');


                                    $courier_wallet = 0;
                                    $res11 = $access->get_courier_wallet('', $INSTITUTE_ID, 8);
                                    if ($res11 != '') {
                                        while ($data11 = $res11->fetch_assoc()) {
                                            $courier_wallet = $data11['TOTAL_BALANCE'];
                                        }
                                    }
                                    $main_wallet = 0;
                                    $res111 = $access->get_wallet('', $INSTITUTE_ID, 8);
                                    if ($res111 != '') {
                                        while ($data111 = $res111->fetch_assoc()) {
                                            $main_wallet = $data111['TOTAL_BALANCE'];
                                        }
                                    }

                                    if ($SHOW_ON_WEBSITE == 1)
                                        $SHOW_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatusWebsite(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> Shown On Website</a>';
                                    elseif ($SHOW_ON_WEBSITE == 0)
                                        $SHOW_ON_WEBSITE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatusWebsite(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> Not Shown On Website</a>';

                                    $editLink .= "<br/><a class='btn btn-success table-btn' title='Share' data-toggle='modal' data-target='#shareModal$INSTITUTE_ID'><i class='mdi mdi-share-variant'></i></a>";

                                    echo " <tr id='row-$INSTITUTE_ID' $color>
						    <td id='website-$INSTITUTE_ID'>$srno</td>
						    
						
							<td>$editLink <a href='$LOCATION' target='_blank' class='btn btn-primary table-btn'> <i class='mdi mdi-map-marker'></i></a></td>
                            <td>$logo <p>$loginBtn</p> $SHOW_ON_WEBSITE </td>
                            <td>$count </td>
							<td> $count_enquiry	</td>
							<td>
							    ATC Code : $INSTITUTE_CODE <br/><br/>
							    Institute Name : <br/><br/> $INSTITUTE_NAME <br/><br/>
							    Courier Wallet : $courier_wallet
							    <br/><br/>
							    Mobile Number : $MOBILE  <br/><br/>
							    AMC Code (Ref) : $AMC_CODE  <br/><br/>
							    User Name : $USER_NAME  <br/><br/>
							    
							     City : $CITY  <br/><br/>
							     State : $STATE_NAME  <br/><br/>
							     Pincode : $POSTCODE <br/><br/>
							    
							    
							</td>
							<td>$main_wallet</td>
							<!-- <td>$AMC_CODE</td> -->
							
							<!-- <td>$STATE_NAME</td>
							<td>$CITY</td>
							<td>$POSTCODE</td> -->
						<!--	<td>$INSTITUTE_CODE</td> -->
							
							<!-- <td>$USER_NAME</td> -->
							<!-- <td>$EMAIL</td> -->
							<!-- <td>$MOBILE</td> -->
								<td id='verify-$INSTITUTE_ID'>$VERIFIED </td>
                            <td id='status-$INSTITUTE_ID'>$ACTIVE $deleteLink</td>
                           </tr>";
                                    $srno++;

                                    echo '
							
					<div class="modal fade" id="shareModal' . $INSTITUTE_ID . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Institute Details</h5>
							<button class="btn btn-warning btn" onclick="copyContent' . $INSTITUTE_ID . '()">Copy!</button>     
							
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body" id="p' . $INSTITUTE_ID . '">								 
								<p> Institute Name : ' . $INSTITUTE_NAME . '</p>
							
								<p> Username : ' . $USER_NAME . '</p>
								<p> Website Link : ' . HTTP_HOST . 'admin/login.php</p>
								<p> App Link : https://play.google.com/store/apps/details?id=com.app.ditrpindia&pcampaignid=web_share </p>
						  </div>     
						</div>
					  </div>
					</div>
					
					
            <script>
              let text' . $INSTITUTE_ID . ' = document.getElementById("p' . $INSTITUTE_ID . '").innerHTML;  
              
              text' . $INSTITUTE_ID . '=text' . $INSTITUTE_ID . '.replace(/<p>/gi, "");
              text' . $INSTITUTE_ID . '=text' . $INSTITUTE_ID . '.replace(/<\/?p>/gi, "");
            
              //console.log(text);
            
              const copyContent' . $INSTITUTE_ID . ' = async () => {
                try {
                  await navigator.clipboard.writeText(text' . $INSTITUTE_ID . ');
                   
                } catch (err) {
                  console.error("Failed to copy: ", err);
                   //console.log(err);
                }
              }
            </script>

					';
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