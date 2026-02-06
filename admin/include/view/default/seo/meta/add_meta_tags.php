<?php

$action = isset($_POST['submit'])?$_POST['submit']:'';

$page_name 		= $db->test(isset($_POST['page_name'])?$_POST['page_name']:'');

$page_url 		= $db->test(isset($_POST['page_url'])?$_POST['page_url']:'');

$page_title 	= $db->test(isset($_POST['page_title'])?$_POST['page_title']:'');

$meta_keywords 	= $db->test(isset($_POST['meta_keywords'])?$_POST['meta_keywords']:'');

$meta_description = isset($_POST['meta_description'])?$_POST['meta_description']:'';

$page_content 	= isset($_POST['page_content'])?$_POST['page_content']:'';

$page_status 	= $db->test(isset($_POST['page_status'])?$_POST['page_status']:'');

$created_by = $_SESSION['user_fname'].' '.$_SESSION['user_lname'];

$msg='';

if($action!='')

{

	header('location:page.php?page=

	

	 $sql = "SELECT * FROM seo_meta_information_master WHERE PAGE_URL='$page_url'";

	$res = $db->execQuery($sql);

	if($res)

	{

		if($res->num_rows<=0)

		{

			$insertSql = "INSERT INTO seo_meta_information_master(PAGE_NAME,PAGE_URL,PAGE_TITLE,META_KEYWORDS,META_DESCRIPTION,PAGE_DATA,PAGE_STATUS,DELETE_FLAG,CREATED_BY,CREATED_ON) VALUES('$page_name', '$page_url', '$page_title','$meta_keywords', '$meta_description', '$page_content','$page_status',0,'$created_by',now())";

			$insRes = $db->execQuery($insertSql);

			if($insRes)

			{

				$_SESSION['msg'] = 'Success! Meta inforamtion saved successfully!';

				header('location:page.php?p=list-meta-tags');

			}

			else

				$msg = 'Sorry! Meta inforamtion was not saved.';

			

		}

	}

	else{

		$msg = "Sorry! Page URL already present. It must be unique.";

	}



}



?>



<!-- PAGE WRAPPER  -->

<div id="page-wrapper" >

	<div id="page-inner">

	<div class="row">

		<div class="col-md-12">

			<h2>Add Meta Tags</h2>	

			

			

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

					 Add Meta Tags

				</div>

				<div class="panel-body">

					<div class="row">

							<div class="col-md-12">

							   <!-- <h3>Basic Form Examples</h3>-->

								<form class="form-horizontal" action="" method="post">

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page Name</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_name" name="page_name" value="<?= isset($page_name)?$page_name:'' ?>" placeholder="Page name">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page URL</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_url" name="page_url" value="<?= isset($page_url)?$page_url:'' ?>" placeholder="URL">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page Title</label>

										<div class="col-sm-8">

										  <input type="text" class="form-control" id="page_title" name="page_title" value="<?= isset($page_title)?$page_title:'' ?>" placeholder="Page Title">

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Meta Keywords</label>

										<div class="col-sm-8">

										   <textarea class="form-control" id="meta_keywords" name="meta_keywords"><?= isset($meta_keywords)?$meta_keywords:'' ?></textarea>

										   <p style="font-size: 12px;color: rgb(108, 101, 101);"><strong>Note:</strong> Enter multiple keyword seperated by commas</p>

										</div>

										

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Meta Description</label>

										<div class="col-sm-8">

										 <textarea class="form-control" id="meta_description" name="meta_description"><?= isset($meta_description)?$meta_description:'' ?></textarea>

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Page Content</label>

										<div class="col-sm-8">

										 <textarea class="form-control" id="page_content" name="page_content"><?= isset($page_content)?$page_content:'' ?></textarea>

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label">Status</label>

										<div class="col-sm-8">

											<label class="radio-inline">

											  <input type="radio" name="page_status" id="page_status1" value="1"> Active

											</label>

											<label class="radio-inline">

											  <input type="radio" name="page_status" id="page_status2" value="0"> In-active

											</label>

										</div>

									</div>

									<div class="form-group">

										<label for="inputEmail3" class="col-sm-3 control-label"></label>

										<div class="col-sm-8">

											 <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Add Page">

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