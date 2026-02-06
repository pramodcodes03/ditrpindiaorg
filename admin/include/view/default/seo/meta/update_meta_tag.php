<?php

$action = isset($_POST['update'])?$_POST['update']:'';





$msg='';

if($action!='')

{

	$meta_info_id 	= $db->test(isset($_POST['meta_info_id'])?$_POST['meta_info_id']:'');

	$page_name 		= $db->test(isset($_POST['page_name'])?$_POST['page_name']:'');

	$page_url 		= $db->test(isset($_POST['page_url'])?$_POST['page_url']:'');

	$page_title 	= $db->test(isset($_POST['page_title'])?$_POST['page_title']:'');

	$meta_keywords 	= $db->test(isset($_POST['meta_keywords'])?$_POST['meta_keywords']:'');
header('location:page.php?page=
	$meta_description = $db->test(isset($_POST['meta_description'])?$_POST['meta_description']:'');

	$page_content 	= $db->test(isset($_POST['page_content'])?$_POST['page_content']:'');

	$page_status 	= $db->test(isset($_POST['page_status'])?$_POST['page_status']:'');

	$updated_by = $_SESSION['user_fname'].' '.$_SESSION['user_lname'];

	

			

	 $updSql = "UPDATE seo_meta_information_master SET PAGE_NAME='$page_name', PAGE_URL='$page_url', PAGE_TITLE='$page_title', META_KEYWORDS='$meta_keywords', META_DESCRIPTION='$meta_description', PAGE_DATA='$page_content', PAGE_STATUS='$page_status', UPDATED_BY='$updated_by', UPDATED_ON=NOW() WHERE META_INFO_ID='$meta_info_id'";

	$insRes = $db->execQuery($updSql);

	if($insRes)

	{

		$_SESSION['msg'] = 'Success! Meta inforamtion updated successfully!';

		header('location:page.php?p=list-meta-tags');

	}

	else

		$msg = 'Sorry! Meta inforamtion was not updated.';

		

}

$meta_info_id = isset($_GET['id'])?$_GET['id']:'';

$sql = "SELECT * FROM seo_meta_information_master WHERE META_INFO_ID='$meta_info_id'";

$res = $db->execQuery($sql);

if($res)

{

	if($res->num_rows>0)

	{

		while($data = $res->fetch_assoc())

		{

			$META_INFO_ID		= $data['META_INFO_ID'];

			$PAGE_NAME			= $data['PAGE_NAME'];

			$PAGE_URL			= $data['PAGE_URL'];

			$PAGE_TITLE			= $data['PAGE_TITLE'];

			$META_KEYWORDS		= $data['META_KEYWORDS'];

			$META_DESCRIPTION	= $data['META_DESCRIPTION'];

			$PAGE_DATA			= $data['PAGE_DATA'];

			$PAGE_STATUS		= $data['PAGE_STATUS'];

			$DELETE_FLAG		= $data['DELETE_FLAG'];

			$CREATED_BY			= $data['CREATED_BY'];

			$CREATED_ON			= $data['CREATED_ON'];

			$UPDATED_BY			= $data['UPDATED_BY'];

			$UPDATED_ON			= $data['UPDATED_ON'];

		}

	}

}

?>



<!-- PAGE WRAPPER  -->

<div id="page-wrapper" >

	<div id="page-inner">

	<div class="row">

		<div class="col-md-12">

			<h2>Update Meta Tags</h2>			

			

		</div>

		

	</div>

	 <!-- /. ROW  -->

		 <hr />

	<!-- show messages -->

<?php if(isset($msg)){?>	

	<div class="row">

		 <div class="col-sm-12">

		 <p class="alert alert-error"><?= $msg ?> </p>

		 </div>

	 </div>

<?php 

	$msg='';

} ?>

	  <!-- /. show messages -->

	<div class="row">

		<div class="col-md-12">

			<!-- Advanced Tables -->

			<div class="panel panel-default">

				<div class="panel-heading">

					 Update Meta Tags

				</div>

				<div class="panel-body">

					<div class="row">

							<div class="col-md-12">

							   <!-- <h3>Basic Form Examples</h3>-->

								<form class="form-horizontal" action="" method="post">

									<input type="hidden" value="<?= $META_INFO_ID ?>" name="meta_info_id" />

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page Name</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_name" name="page_name" value="<?= isset($PAGE_NAME)?$PAGE_NAME:'' ?>" placeholder="Page name">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page URL</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_url" name="page_url" value="<?= isset($PAGE_URL)?$PAGE_URL:'' ?>" placeholder="URL">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page Title</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_title" name="page_title" value="<?= isset($PAGE_TITLE)?$PAGE_TITLE:'' ?>" placeholder="Page Title">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Meta Keywords</label>

										<div class="col-sm-8">

										   <textarea class="form-control" id="meta_keywords" name="meta_keywords"><?= isset($META_KEYWORDS)?$META_KEYWORDS:'' ?></textarea>

										   <p style="font-size: 12px;color: rgb(108, 101, 101);"><strong>Note:</strong> Enter multiple keyword seperated by commas</p>

										</div>

										

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Meta Description</label>

										<div class="col-sm-8">

										 <textarea class="form-control" id="meta_description" name="meta_description"><?= isset($META_DESCRIPTION)?$META_DESCRIPTION:'' ?></textarea>

										</div>

									</div>

									<div class="form-group">

									



										<label for="inputEmail3" class="col-sm-3 control-label">Page Content</label>

										<div class="col-sm-8">

										 <textarea class="form-control" id="page_content" name="page_content"><?= isset($PAGE_DATA)?$PAGE_DATA:'' ?></textarea>

										  <script>

                              CKEDITOR.replace( 'page_content' );

                             </script>

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Status</label>

										<div class="col-sm-8">

											<label class="radio-inline">

											  <input type="radio" name="page_status" id="page_status1" value="1" <?php if($PAGE_STATUS==1) echo 'checked="checked"'; ?>> Active

											</label>

											<label class="radio-inline">

											  <input type="radio" name="page_status" id="page_status2" value="0" <?php if($PAGE_STATUS==0) echo 'checked="checked"'; ?>> In-active

											</label>

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label"></label>

										<div class="col-sm-8">

											 <input type="submit" class="btn btn-primary" id="update" name="update" value="Update">

											 <input type="button" class="btn btn-warning" id="cancel" name="cancel" value="Cancel" onclick="history.back();">

										</div>

									</div>



								</form>

							</div>

					</div>

					

				</div>

			</div>

			<!--End Advanced Tables -->

		</div>

	</div>

		<!-- /. ROW  -->



	</div>

		<!-- /. ROW  -->

        

        </div>

               

    </div>

 <!-- /. PAGE WRAPPER  -->