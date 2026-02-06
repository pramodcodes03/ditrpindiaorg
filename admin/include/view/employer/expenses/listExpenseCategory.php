 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Expense Category Type
 					<a href="page.php?page=addexpensetype" class="btn btn-primary btn1" style="float: right">Add Category Type</a>
 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Category Type</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
								$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

								if ($user_role == 3) {
									$institute_id = $db->get_parent_id($user_role, $user_id);
									$staff_id = $user_id;
								} else {
									$institute_id = $user_id;
									$staff_id = 0;
								}
								include_once('include/classes/expense.class.php');
								$expense = new expense();
								$res = $expense->list_expensestype('', " AND INSTITUTE_ID = $institute_id");

								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$CATEGORY_ID 		= $data['CATEGORY_ID'];
										$CATEGORY  	        = $data['CATEGORY'];
										$ACTIVE			    = $data['ACTIVE'];
										$CREATED_BY 	    = $data['CREATED_BY'];
										$CREATED_ON 	    = $data['CREATED_ON'];

										if ($ACTIVE == 1)
											$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';

										$action = '';
										$action = "<a href='page.php?page=updateexpensetype&id=$CATEGORY_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_enquiry'))
											$action .= "<a href='javascript:void(0)' onclick='deleteExpensesCat($CATEGORY_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";

										echo " <tr id='row-" . $CATEGORY_ID . "'>									
									<td>$srno</td>								
									<td>$CATEGORY</td>
									<td id='status-$CATEGORY_ID'>$ACTIVE</td>
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