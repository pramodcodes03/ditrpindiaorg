<?php
include_once('database_results.class.php');
include_once('access.class.php');
include_once('s3.php');
include_once('s3Class.php');

class helpsupport extends access
{
    
	/*---------------------- Support Type ------------------------------*/
	public function add_support_type()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $supporttype 		= parent::test(isset($_POST['supporttype'])?$_POST['supporttype']:'');
		  $status 		= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $created_by  		= $_SESSION['user_fullname'];
		 //new validations
		  if ($supporttype=='')
			$errors['supporttype'] = 'Support Type is required.';		
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();
					
					$tableName 	= "help_support_type";
					$tabFields 	= "(SUPPORT_TYPE_ID, SUPPORT_NAME, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "(NULL,'$supporttype','$status','$created_by',NOW())";
										
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					
					$exSql		= parent::execQuery($insertSql);
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Support Type has been added successfully!';
			}				
						
		return json_encode($data);			
	}
	
	/* update Activation Plan  
	@param: 
	@return: json
	*/
	public function update_support_type($supporttype_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		 $supporttype_id 			= parent::test(isset($_POST['supporttype_id'])?$_POST['supporttype_id']:'');
		 $supporttype 		= parent::test(isset($_POST['supporttype'])?$_POST['supporttype']:'');
		
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');		 
				 
		  $updated_by  		= $_SESSION['user_fullname'];
		
		 /* check validations */		 
		  if ($supporttype=='')
			$errors['supporttype'] = 'Support Type is required.';		
		 
		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "help_support_type";
					$setValues 	= "SUPPORT_NAME='$supporttype',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE SUPPORT_TYPE_ID='$supporttype_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
				
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Support Type has been updated successfully!';
						
					
				 
			}
		return json_encode($data);			
	}
	public function list_support_type($supporttype_id='', $cond='')
	{
		$data = '';
		$sql= "SELECT * FROM help_support_type WHERE DELETE_FLAG=0";
		if($supporttype_id!='')
		{
			$sql .= " AND SUPPORT_TYPE_ID='$supporttype_id' ";
		}
		if($cond!='')
		{
			$sql .= $cond;
		}
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	public function deleteSupportType($supporttype_id)
	{
		echo $sql = "UPDATE help_support_type SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE SUPPORT_TYPE_ID='$supporttype_id'";
		$res= parent::execQuery($sql);
		return false;
	}

	/*---------------------- Support Category ------------------------------*/

	public function add_support_cat()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $supporttype 		= parent::test(isset($_POST['supporttype'])?$_POST['supporttype']:'');
		  $supportcat 		= parent::test(isset($_POST['supportcat'])?$_POST['supportcat']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $created_by  		= $_SESSION['user_fullname'];
		 //new validations
		  if ($supporttype=='')
			$errors['supporttype'] = 'Select Support Type is required.';
		 if ($supportcat=='')
			$errors['supportcat'] = 'Support Category Name is required.';		
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();
					
					$tableName 	= "help_support_category";
					$tabFields 	= "(SUPPORT_CAT_ID,SUPPORT_TYPE_ID, CATEGORY_NAME, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "(NULL,'$supporttype','$supportcat','$status','$created_by',NOW())";
										
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					
					$exSql		= parent::execQuery($insertSql);
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Support Category has been added successfully!';
			}				
						
		return json_encode($data);			
	}
	
	/* update Activation Plan  
	@param: 
	@return: json
	*/
	public function update_support_cat($supporttcat_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		 $supporttcat_id 			= parent::test(isset($_POST['supporttcat_id'])?$_POST['supporttcat_id']:'');

		 $supporttype 		= parent::test(isset($_POST['supporttype'])?$_POST['supporttype']:'');
		 $supportcat 		= parent::test(isset($_POST['supportcat'])?$_POST['supportcat']:'');

		 $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');		 
				 
		  $updated_by  		= $_SESSION['user_fullname'];
		
		 /* check validations */		 
		   if ($supporttype=='')
			$errors['supporttype'] = 'Select Support Type is required.';
		   if ($supportcat=='')
			$errors['supportcat'] = 'Support Category Name is required.';		
		 
		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "help_support_category";
					$setValues 	= "SUPPORT_TYPE_ID='$supporttype',CATEGORY_NAME='$supportcat',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE SUPPORT_CAT_ID='$supporttcat_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Support Category has been updated successfully!';
						
					
				 
			}
		return json_encode($data);			
	}
	public function list_support_cat($supportcat_id='', $cond='')
	{
		$data = '';
		$sql= "SELECT * FROM help_support_category WHERE DELETE_FLAG=0";
		if($supportcat_id!='')
		{
			$sql .= " AND SUPPORT_CAT_ID='$supportcat_id' ";
		}
		if($cond!='')
		{
			$sql .= $cond;
		}
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	public function deleteSupportCat($supportcat_id)
	{
		echo $sql = "UPDATE  help_support_category SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE SUPPORT_CAT_ID='$supportcat_id'";
		$res= parent::execQuery($sql);
		return false;
	}

	/*---------------------- Support  ------------------------------*/

	public function add_support()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $student_id 		= parent::test(isset($_POST['student_id'])?$_POST['student_id']:'');
		  $inst_id 			= parent::test(isset($_POST['inst_id'])?$_POST['inst_id']:'');
		  $admin_id 			= parent::test(isset($_POST['admin_id'])?$_POST['admin_id']:'');
		  $mobile 			= parent::test(isset($_POST['mobile'])?$_POST['mobile']:'');		
		  $email 			= parent::test(isset($_POST['email'])?$_POST['email']:'');		
		  $description 		= parent::test(isset($_POST['description'])?$_POST['description']:'');
		  $supportfiles 		= isset($_FILES['supportfiles']['name'])?$_FILES['supportfiles']['name']:'';
		

		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $created_by  		= $_SESSION['user_fullname'];
	
			if($description=='')
				$errors['description'] = 'Required Feild.';		
		
		 //$errors=array();
            if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();
				
				$tableName 	= "help_support";
				$tabFields 	= "(TICKET_ID,STUDENT_ID,INSTITUTE_ID,DESCRIPTION,MOBILE,EMAIL,CURRENT_STATUS, ACTIVE,CREATED_BY, CREATED_ON,ADMIN_ID)";
				$insertVals	= "(NULL,'$student_id','$inst_id','$description','$mobile','$email',1,'$status','$created_by',NOW(),'$admin_id')";
				$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);				
				$exSql		= parent::execQuery($insertSql);

			if($exSql)
			{	
				$last_insert_id = parent::last_id();
				
				$courseImgPathDir 		= 	HELPSUPPORT_PHOTO_PATH.'/'.$last_insert_id.'/';

				if($supportfiles!='')
				{								
					while(list($key,$value) = each($_FILES["supportfiles"]["name"]))
					{								
						$cover_image		= $_FILES["supportfiles"]["name"][$key];									
						//if product record is not blank
						if($cover_image !='') 
						{
							$ext 			= pathinfo($_FILES["supportfiles"]["name"][$key], PATHINFO_EXTENSION);
							$file_name 		= helpsupport.'_'.mt_rand(0,123456789).'.'.$ext;					
							$tableName2 	= "help_support_images";			
							$tabFields2 	= "(HELP_SUPPORT_IMG_ID,TICKET_ID,IMAGE,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
							$insertVals2	= "(NULL, '$last_insert_id', '$file_name','1',0,'$created_by',NOW())";
							$insertSql2		= parent::insertData($tableName2,$tabFields2,$insertVals2);
							$exec2   		= parent::execQuery ($insertSql2);

							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["supportfiles"]["tmp_name"][$key], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["supportfiles"]["tmp_name"][$key], $courseImgThumbPathFile,  $ext, 300, 280);
						}
					}									
				}
			}							
			parent::commit();
			$data['success'] = true;
			$data['message'] = 'Success! New Help Support has been added successfully!';
			}				
						
		return json_encode($data);			
	}
	
	/* update Activation Plan  
	@param: 
	@return: json
	*/
	public function reply_support($support_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		 $ticket_id 	= parent::test(isset($_POST['ticket_id'])?$_POST['ticket_id']:'');
		 $reply 		= parent::test(isset($_POST['reply'])?$_POST['reply']:'');

		 $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');		 
				 
		 $updated_by  		= $_SESSION['user_fullname'];
		
		 /* check validations */		 
		   if ($reply=='')
			$errors['reply'] = 'Admin Reply Is Required.';		  	
		 
		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "help_support";
					$setValues 	= "ADMIN_UPDATES='$reply',CURRENT_STATUS=2,ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE TICKET_ID='$ticket_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Admin reply send successfully!';
				 
			}
		return json_encode($data);
	}
	public function list_support($support_id='', $cond='')
	{
		$data = '';
		$sql= "SELECT *,get_student_name(STUDENT_ID) as STUDENT_NAME FROM help_support WHERE DELETE_FLAG=0";
		if($support_id!='')
		{
			$sql .= " AND TICKET_ID='$support_id' ";
		}
		if($cond!='')
		{
			$sql .= $cond;
		}
		$sql .= ' ORDER BY CREATED_ON DESC';
		//	echo $sql; exit();
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	public function get_helpsupport_files($support_id='', $display=true)
	{
		$img='';
		$data= array();
		$target='';
		
		$sql ="SELECT HELP_SUPPORT_IMG_ID,TICKET_ID, IMAGE FROM help_support_images WHERE 1";
		if($support_id!='')
			$sql .= " AND TICKET_ID='$support_id'";
	
		$sql .= ' ORDER BY HELP_SUPPORT_IMG_ID ';
		//echo $sql; exit();
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			while($rec = $res->fetch_assoc())
			{
				$HELP_SUPPORT_IMG_ID = $rec['HELP_SUPPORT_IMG_ID'];
				$IMAGE 		= $rec['IMAGE'];
				$TICKET_ID 	= $rec['TICKET_ID'];
				if($IMAGE!='')
				{
					if(!$display)			
					{	
						$data1 = array("HELP_SUPPORT_IMG_ID" => $HELP_SUPPORT_IMG_ID, "IMAGE" => $IMAGE, "TICKET_ID" => $TICKET_ID);	array_push($data, $data1);
					}else{
					$filePath = HELPSUPPORT_PHOTO_PATH.'/'.$TICKET_ID.'/thumb/'.$IMAGE;
					$fileLink = HELPSUPPORT_PHOTO_PATH.'/'.$TICKET_ID.'/'.$IMAGE;
				
					$img .=  '<div class="col-sm-3"> <div id="file-area'.$TICKET_ID.'"><a href="'.$fileLink.'" target="_blank" title="View File"><i class="fa fa-eye"></i></a>
												<a href="'.$fileLink.'" target="_blank">
												<img src="'.$filePath.'" class="img img-responsive" style="height:200px;" />
												</a>
											</div> </div>';
					}
					
				}				
			}			
		}		
		if(!$display)
			return $data;
		else return $img;
	}
	public function deleteSupport($support_id)
	{
		echo $sql = "UPDATE  help_support SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE TICKET_ID='$supportcat_id'";
		$res= parent::execQuery($sql);
		return false;
	}
	public function save_ticket_rating()
	{
	   
	    $ticket_id = parent::test(isset($_POST['ticket_id'])?$_POST['ticket_id']:'');
	    $ticket_rating = parent::test(isset($_POST['ticket_rating'])?$_POST['ticket_rating']:'');
	    if ($ticket_id=='')
			$errors['$ticket_id'] = 'Ticket ID Is Required.';	
		if ($ticket_rating=='')
			$errors['$ticket_rating'] = 'Rating Is Required.';
		 
		if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
		} else {
	           $sql = "UPDATE  help_support SET RATING='$ticket_rating',  UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_name']."' WHERE TICKET_ID='$ticket_id'";
		       $res= parent::execQuery($sql);
		       $data['success'] = true;
			$data['message'] = 'Success! Rating saved successfully!';
		}
		return json_encode($data);
	}
	
}
?>