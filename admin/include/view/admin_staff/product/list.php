 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Product
 					<a href="page.php?page=addProduct" class="btn btn-primary" style="float: right">Add Product</a>
 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Product Name</th>
 								<th>Product Link</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/tools.class.php');
								$tools = new tools();
								$res = $tools->list_product('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										extract($data);

										if ($active == 1) $active = 'Active';
										elseif ($active == 0) $active = 'In-Active';

										$action = '';

										$action = "<a href='page.php?page=updateProduct&id=$id' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

										$action .= "<a href='javascript:void(0)' onclick='deleteProduct($id)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";

										echo " <tr id='id" . $id . "'>						
									<td>$srno</td>	
									<td>$name</td>
									<td>$link</td>
									<td id='status-$id'>$active</td>
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