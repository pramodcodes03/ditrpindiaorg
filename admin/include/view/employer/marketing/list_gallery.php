 <?php
	$type = isset($_GET['type']) ? $_GET['type'] : 'marketing';
	$title = ($type == 'marketing') ? 'Marketing Material' : 'Gallery';
	?>

 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List <?= $title ?>

 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>#</th>
 								<th>Title</th>
 								<th>Description</th>
 								<th>Date</th>

 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								$res = $db->list_gallery('', $type, '1');
								if ($res == '') {
									echo '<tr align="center"><td colspan="5">No records found.</td></tr>';
								} else {
									$i = 1;
									while ($data = $res->fetch_assoc()) {
										$GALLERY_ID			= isset($data['GALLERY_ID']) ? $data['GALLERY_ID'] : '';
										$GALLERY_TITLE 		= isset($data['GALLERY_TITLE']) ? $data['GALLERY_TITLE'] : '';
										$TOTAL_FILES 		= isset($data['TOTAL_FILES']) ? $data['TOTAL_FILES'] : '';
										$GALLERY_DESC 		= isset($data['GALLERY_DESC']) ? $data['GALLERY_DESC'] : '';
										$ACTIVE				= isset($data['ACTIVE']) ? $data['ACTIVE'] : '';
										$CREATED_BY			= isset($data['CREATED_BY']) ? $data['CREATED_BY'] : '';
										$CREATED_ON			= isset($data['CREATED_DATE']) ? $data['CREATED_DATE'] : '';
										$GALLERY_TYPE			= isset($data['GALLERY_TYPE']) ? $data['GALLERY_TYPE'] : '';

										$updateLink 		= "updateMarkeing&id=$GALLERY_ID";


										$GALLERY_IMAGE = $db->list_gallery_files_single($GALLERY_ID);

										if ($GALLERY_IMAGE != '') {

											if ($GALLERY_TYPE == 'marketing')
												$path = '../uploads/marketing';
											$GALLERY_IMAGE = $path . '/' . $GALLERY_ID . "/" . $GALLERY_IMAGE;

											if (file_exists($GALLERY_IMAGE)) {
												$GALLERY_IMAGE	= '<img src="' . $GALLERY_IMAGE . '" style="height:50px; width:50px"/>';
											} else {
												$GALLERY_IMAGE = '<img src="resources/dist/img/ditrp_default_material.png" style="height:50px; width:50px"/>';
											}
										} else {
											$GALLERY_IMAGE = '<img src="resources/dist/img/ditrp_default_material.png" style="height:50px; width:50px"/>';
										}

										$action = '';
										if ($db->permission('update_gallery'))
											$action .= '<a href="' . $updateLink . '"  class="btn btn-primary" title="VIEW"><i class="fa fa-eye"></i> VIEW</a>';


										echo '<tr id="row-' . $GALLERY_ID . '">
    									<td><a href="' . $updateLink . '">' . $GALLERY_IMAGE . '</a></td>
    									<td>' . $GALLERY_TITLE . '</td>
    									<td>' . $GALLERY_DESC . '</td>
    									<td>' . $CREATED_ON . '</td>	
    
    									<td>' . $action . '</td>
    							</tr>';
										$i++;
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