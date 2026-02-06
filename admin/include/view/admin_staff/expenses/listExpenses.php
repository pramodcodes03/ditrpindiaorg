 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Expenses

 					<a href="page.php?page=addExpense" class="btn btn-primary" style="float: right">Add Expenses</a>

 					<form action="export.php" method="post" class="">
 						<input type="hidden" value="expense_export" name="action" />
 						<button type="submit" name="export" value="Export" class="btn btn-danger btn3">Export</button>
 					</form>

 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Expense Type</th>
 								<th>Expense Sub-Type</th>
 								<th>Issue Name</th>
 								<th>Name of Pesrson</th>
 								<th>Amount</th>
 								<th>Date</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								$user_id = $_SESSION['user_id'];
								include_once('include/classes/expense.class.php');
								$expense = new expense();
								$res = $expense->list_expenses('', " AND INSTITUTE_ID = $user_id");

								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$EXPENSE_ID 		= $data['EXPENSE_ID'];
										$EXPENSE_TYPE  	= $data['CATEGORYNAME'];
										$EXPENSE_SUBTYPE  	= $data['SUBCATEGORYNAME'];
										$ISSUE_NAME  	= $data['ISSUE_NAME'];
										$NAME_OF_PERSON  	= $data['NAME_OF_PERSON'];
										$AMOUNT  	= $data['AMOUNT'];
										$EDATE  	= $data['EDATE'];
										$ACTIVE			= $data['ACTIVE'];
										$CREATED_BY 	= $data['CREATED_BY'];
										$CREATED_ON 	= $data['CREATED_ON'];
										$EDATE = date("d-m-Y", strtotime($EDATE));
										if ($ACTIVE == 1)
											$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';

										$action = '';
										$action .= "<a href='page.php?page=updateExpense&id=$EXPENSE_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

										if ($db->permission('delete_enquiry'))
											//$action .= "<a href='javascript:void(0)' onclick='deleteExpenses($EXPENSE_ID)' class='btn btn-danger table-btn' title='Delete'><i class='mdi mdi-delete'></i></a>";		

											echo " <tr id='row-" . $EXPENSE_ID . "'>									
									<td>$srno</td>	
									<td>$EXPENSE_TYPE</td>									
									<td>$EXPENSE_SUBTYPE</td>
									<td>$ISSUE_NAME</td>	
									<td>$NAME_OF_PERSON</td>	
									<td>$AMOUNT</td>	
									<td>$EDATE</td>										
									<td id='status-$EXPENSE_ID'>$ACTIVE</td>
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