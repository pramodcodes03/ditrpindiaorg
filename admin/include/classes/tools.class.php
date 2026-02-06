<?php
include_once('database_results.class.php');
include_once('access.class.php');
class tools extends access
{
	
	/* add new Contest Detals 
	@param: 
	@return: json
	*/
	public function add_contestdetail()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $contestdetail 	= parent::test(isset($_POST['contestdetail'])?$_POST['contestdetail']:'');
		  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Contest Name is required!';
		  if ($contestdetail=='') $errors['contestdetail'] = 'Contest details is required!';
		    
		  if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "contestdetails";
					$tabFields 	= "(CONTEST_TITLE, CONTEST_DESC, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "('$contestname', '$contestdetail','$status','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	


                        /* upload course files */
						$last_insert_id 	= parent::last_id();
						$courseImgPathDir 	= CONTESTDETAIL_MATERIAL_PATH.'/'.$last_insert_id.'/';
						
						
                        //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "CONTEST_IMG='$file_name'";
							$whereClause	= " WHERE CONTEST_ID='$last_insert_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}  	 
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Contest Details has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Contest Details.';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}	
	/* update institute 
	@param: 
	@return: json
	*/
	public function update_contestdetails($contestdetail_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $contestdetail 	= parent::test(isset($_POST['contestdetail'])?$_POST['contestdetail']:'');
		  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Contest Name is required!';
		  if ($contestdetail=='') $errors['contestdetail'] = 'Contest details is required!';
		    
		  if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		 
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				$exam_mode = json_encode($exam_mode);
					parent::start_transaction();										
					$tableName 	= "contestdetails";
					$setValues 	= " CONTEST_TITLE='$contestname',CONTEST_DESC='$contestdetail',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE CONTEST_ID='$contestdetail_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);

                    $courseImgPathDir = CONTESTDETAIL_MATERIAL_PATH.'/'.$contestdetail_id.'/';
                    //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "CONTEST_IMG='$file_name'";
							$whereClause	= " WHERE CONTEST_ID='$contestdetail_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}  	

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Contest Details has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function list_contestdetails($contest_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM contestdetails A WHERE A.DELETE_FLAG=0 ";
		
		if($contest_id!='')
		{
			$sql .= " AND A.CONTEST_ID='$contest_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	/* delete Contest file */
	public function delete_contestdetails($exam_id)
	{
		$sql = "UPDATE contestdetails SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE CONTEST_ID='$exam_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	/* add new result 
	@param: 
	@return: json
	*/
	public function add_result()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Result State is required!';
		  
		    
		  if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "result";
					$tabFields 	= "(RESULT_STATE, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "(UPPER('$contestname'),'$status','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 


                        /* upload course files */
						$last_insert_id 	= parent::last_id();
						$courseImgPathDir 	= RESULT_MATERIAL_PATH.'/'.$last_insert_id.'/';
						
						
                        //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "RESULT_IMG='$file_name'";
							$whereClause	= " WHERE RESULT_ID='$last_insert_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}  	 
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Result has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Result .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}
	
	/* update result 
	@param: 
	@return: json
	*/
	public function update_result($contestdetail_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Contest Name is required!';
		  
		   if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		 
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				$exam_mode = json_encode($exam_mode);
					parent::start_transaction();										
					$tableName 	= "result";
					$setValues 	= " RESULT_STATE='$contestname',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE RESULT_ID='$contestdetail_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);

                    $courseImgPathDir = RESULT_MATERIAL_PATH.'/'.$contestdetail_id.'/';
                    //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "RESULT_IMG='$file_name'";
							$whereClause	= " WHERE RESULT_ID='$contestdetail_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}  	

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Result has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function list_result($contest_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM result A WHERE A.DELETE_FLAG=0 ";
		
		if($contest_id!='')
		{
			$sql .= " AND A.RESULT_ID='$contest_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	/* delete result file */
	public function delete_result($exam_id)
	{
		$sql = "UPDATE result SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE RESULT_ID='$exam_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	/* add new contestform 
	@param: 
	@return: json
	*/
	public function add_contestform()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $course_pdf   = $_FILES['course_pdf']['tmp_name'];
		  $course_pdfname	= isset($_FILES['course_pdf']['name'])?$_FILES['course_pdf']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		 // if ($contestname=='') $errors['contestname'] = 'Result State is required!';
		  
		    
		  if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "contestform";
					$tabFields 	= "(CONTEST_TITLE, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "(UPPER('$contestname'),'$status','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 


                        /* upload course files */
						$last_insert_id 	= parent::last_id();
						$courseImgPathDir 	= CONTESTFORM_MATERIAL_PATH.'/'.$last_insert_id.'/';
						
						
                        //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "CONTEST_IMG='$file_name'";
							$whereClause	= " WHERE CONTEST_ID='$last_insert_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}

						//upload pdf
						if($course_pdfname!='')
						{
                            
							$setValues 		= "CONTEST_PDF='$course_pdfname'";
							$whereClause	= " WHERE CONTEST_ID='$last_insert_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$dest_file = $courseImgPathDir.'pdf';
							@mkdir($dest_file,0777,true);
                            $dest_file = $courseImgPathDir.'pdf/'.$course_pdfname;						
							move_uploaded_file( $course_pdf, $dest_file);							
						}    	



						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Contest Form has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Contest Form .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}	
	/* update result 
	@param: 
	@return: json
	*/
	public function update_contestform($contestdetail_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
          $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
          $course_pdf       = $_FILES['course_pdf']['tmp_name'];
		  $course_pdfname	= isset($_FILES['course_pdf']['name'])?$_FILES['course_pdf']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		 // if ($contestname=='') $errors['contestname'] = 'Contest Name is required!';
		  
		   if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		 
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				$exam_mode = json_encode($exam_mode);
					parent::start_transaction();										
					$tableName 	= "contestform";
					$setValues 	= " CONTEST_TITLE='$contestname',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE CONTEST_ID='$contestdetail_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);

                    $courseImgPathDir = CONTESTFORM_MATERIAL_PATH.'/'.$contestdetail_id.'/';
                      //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "CONTEST_IMG='$file_name'";
							$whereClause	= " WHERE CONTEST_ID='$contestdetail_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}

						//upload pdf
						if($course_pdfname!='')
						{
                            
							$setValues 		= "CONTEST_PDF ='$course_pdfname'";
							$whereClause	= " WHERE CONTEST_ID ='$contestdetail_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$dest_file = $courseImgPathDir.'pdf';
							@mkdir($dest_file,0777,true);
                            $dest_file = $courseImgPathDir.'pdf/'.$course_pdfname;						
							move_uploaded_file( $course_pdf, $dest_file);							
						} 
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Contest Form has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function list_contestform($contest_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM contestform A WHERE A.DELETE_FLAG=0 ";
		
		if($contest_id!='') 
		{
			$sql .= " AND A.CONTEST_ID='$contest_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	/* delete result file */
	public function delete_contestform($exam_id)
	{
		$sql = "UPDATE contestform SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE CONTEST_ID='$exam_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

  	/* add new Slider 
	@param: 
	@return: json
	*/
	public function add_slidernew()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
	
		    
		  if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "slider";
					$tabFields 	= "(CREATED_BY, CREATED_ON)";
					$insertVals	= "('$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 


                        /* upload course files */
						$last_insert_id 	= parent::last_id();
						$courseImgPathDir 	= SLIDERNEW_MATERIAL_PATH.'/'.$last_insert_id.'/';
						
						
                        //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "SLIDER_IMG='$file_name'";
							$whereClause	= " WHERE SLIDER_ID='$last_insert_id'";
							$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
							$exSql		= parent::execQuery($updateSql);
							$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
							$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
							$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
							@mkdir($courseImgPathDir,0777,true);
							@mkdir($courseImgThumbPathDir,0777,true);								
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgPathFile,  $ext, 1500, 500) ;
							parent::create_thumb_img($_FILES["course_img"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
						}  	 
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Slider has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Slider .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}
	public function list_slider($contest_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM slider A WHERE A.DELETE_FLAG=0 ";
		
		if($contest_id!='')
		{
			$sql .= " AND A.GALLERY_ID='$contest_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

	/* delete slider file */
	public function delete_slidernew($exam_id)
	{
		$sql = "UPDATE slider SET DELETE_FLAG=1 WHERE SLIDER_ID='$exam_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	/* add new result 
	@param: 
	@return: json
	*/
	public function add_video()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
        //  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Video Url  is required!';
		  
		    
		  
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "video";
					$tabFields 	= "(RESULT_STATE, ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "('$contestname','$status','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	
                       	 
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Video has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Video .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}	
	/* update result 
	@param: 
	@return: json
	*/
	public function update_video($contestdetail_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $contestname 		= parent::test(isset($_POST['contestname'])?$_POST['contestname']:'');		 
         // $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  if ($contestname=='') $errors['contestname'] = 'Video url is required!';
		  
		 
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				$exam_mode = json_encode($exam_mode);
					parent::start_transaction();										
					$tableName 	= "video";
					$setValues 	= " RESULT_STATE='$contestname',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE RESULT_ID='$contestdetail_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);

                 if($exSql){  	

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Video has been updated successfully!';
                     
                 }
                 else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not Update the Video .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
			}
		return json_encode($data);			
	}
	public function list_video($contest_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM video A WHERE A.DELETE_FLAG=0 ";
		
		if($contest_id!='')
		{
			$sql .= " AND A.RESULT_ID='$contest_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}	
	/* delete video file */
	public function delete_video($exam_id)
	{
		$sql = "UPDATE video SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE RESULT_ID='$exam_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	//online classes Links
	public function add_onlineclasses_details()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $title 		= parent::test(isset($_POST['title'])?$_POST['title']:'');
		  $link 		= parent::test(isset($_POST['link'])?$_POST['link']:'');
		  $description 	= parent::test(isset($_POST['description'])?$_POST['description']:'');
		  $expirydate 	= parent::test(isset($_POST['expirydate'])?$_POST['expirydate']:'');
		  $course_id 	= parent::test(isset($_POST['course_id'])?$_POST['course_id']:'');
		  $institute_id 	= parent::test(isset($_POST['institute_id'])?$_POST['institute_id']:'');
		  
         
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		 if ($title=='') $errors['title'] = 'Title is required!';
		 if ($link=='') $errors['link'] = 'Link is required!';		  
		    
	
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "online_classes";
					$tabFields 	= "(inst_id,course_id,title, link,description, active,delete_flag,created_by,created_at,expirydate)";
					$insertVals	= "('$institute_id','$course_id','$title','$link','$description','1','0','$created_by',NOW(),'$expirydate')";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 

						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Link has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Link .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}	
	public function update_onlineclasses_details($id='')
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $id 		= parent::test(isset($_POST['id'])?$_POST['id']:'');

		  $title 		= parent::test(isset($_POST['title'])?$_POST['title']:'');
		  $link 		= parent::test(isset($_POST['link'])?$_POST['link']:'');
		  $description 	= parent::test(isset($_POST['description'])?$_POST['description']:'');
		  $expirydate 	= parent::test(isset($_POST['expirydate'])?$_POST['expirydate']:'');
		  $course_id 	= parent::test(isset($_POST['course_id'])?$_POST['course_id']:'');
		  $institute_id 	= parent::test(isset($_POST['institute_id'])?$_POST['institute_id']:'');

		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  /* check validations */
		  if ($title=='') $errors['title'] = 'Title is required!';
		  if ($link=='') $errors['link'] = 'Link is required!';		  
		 
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {				
					parent::start_transaction();										
					$tableName 	= "online_classes";
					$setValues 	= " title='$title',link='$link',description='$description', updated_by='$updated_by',updated_at=NOW(),expirydate='$expirydate',course_id='$course_id',inst_id='$institute_id'";
					$whereClause= " WHERE id ='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);
                
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Link has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function list_onlineclasses_details($id='',$condition='')
	{
		$data = '';
		
		$sql= "SELECT A.* FROM online_classes A WHERE A.delete_flag=0 ";
		
		if($id!='') 
		{
			$sql .= " AND A.id='$id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		//echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	public function delete_onlineclasses_details($id)
	{
		$sql = "UPDATE online_classes SET delete_flag=1, updated_by='".$_SESSION['user_fullname']."', updated_at=NOW() WHERE id='$id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	//IMS maqrquee section

	public function edit_marquee($id)
	{
		
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id'])?$_POST['id']:'');
		$marqueetext 		= parent::test(isset($_POST['marqueetext'])?$_POST['marqueetext']:'');
	
		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];		
		
		/* check validations */

			// if($marqueetext=='')
			// {						
			// $errors['marqueetext'] = 'Message Is Required!';				
			// }
	
			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();										
				$tableName 	= "marquee_tags";
				$setValues 	= "name='$marqueetext', updated_by='$updated_by', updated_at=NOW()";
				$whereClause= " WHERE id='$id'";
				$updateSql	= parent::updateData($tableName,$setValues,$whereClause);			
				$exSql		= parent::execQuery($updateSql);

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New logo has been added successfully!';
			}
									
			return json_encode($data);		
	}	
	public function list_marquee($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM  marquee_tags A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id ='$id ' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

	//IMS Advertise offer

	public function add_advertise()
	{			
			$errors = array();  // array to hold validation errors
			$data = array();        // array to pass back data

			$name 			= parent::test(isset($_POST['name'])?$_POST['name']:'');
			$link 			= parent::test(isset($_POST['link'])?$_POST['link']:'');
			$advertiseimage 		= isset($_FILES['advertiseimage']['name'])?$_FILES['advertiseimage']['name']:'';		
			$institute_id 	= parent::test(isset($_POST['institute_id'])?$_POST['institute_id']:'');
		
			$role 			= 2; //institute;
			$created_by  		= $_SESSION['user_fullname'];		
			
			/* check validations */

			if($name=='')
			{						
			$errors['name'] = 'Name Is Required!';				
			}

			if($advertiseimage=='')
			{						
			$errors['advertiseimage'] = 'Image Is Required!';				
			}

			if($advertiseimage!='')
			{
				$allowed_ext = array('jpg','jpeg','png','gif');				
				$extension = pathinfo($advertiseimage, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['advertiseimage'] = 'Invalid file format! Please select valid image file.';
				}
			}
		
			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();					
				$tableName 	= "ims_advertise_popup";
				$tabFields 	= "(id,inst_id,name,link, active,delete_flag,created_by,created_at)";
				$insertVals	= "(NULL, '$institute_id','$name','$link','1','0','$created_by',NOW())";
									
				$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
				$exSql		= parent::execQuery($insertSql);

				if($exSql){
					/* upload course files */
					$last_insert_id 		= parent::last_id();	
					$courseImgPathDir 	= IMS_ADVERTISE_PATH.'/'.$last_insert_id.'/';

					if($advertiseimage!='')
					{								
						$ext 			= pathinfo($_FILES["advertiseimage"]["name"], PATHINFO_EXTENSION);
						$file_name 		= $name.'_'.mt_rand(0,123456789).'_'.$last_insert_id.'_T.'.$ext;
						$setValues 		= "image='$file_name'";
						$whereClause	= " WHERE id='$last_insert_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);				
					}
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New Advertise has been added successfully!';
					}
					//upload course image
					else{
							parent::rollback();
							$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
							$data['success'] = false;
							$data['errors']  = $errors;
						}
					}
			return json_encode($data);		
	}

	public function list_advertise($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM ims_advertise_popup A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id ='$id ' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

	public function edit_advertise($id)
	{
			
			$errors = array();  // array to hold validation errors
			$data = array();        // array to pass back data

			$id 			= parent::test(isset($_POST['id'])?$_POST['id']:'');

			$name 			= parent::test(isset($_POST['name'])?$_POST['name']:'');
			$link 			= parent::test(isset($_POST['link'])?$_POST['link']:'');
			$advertiseimage 		= isset($_FILES['advertiseimage']['name'])?$_FILES['advertiseimage']['name']:'';
		
			$role 					= 2; //institute;
			$updated_by  		= $_SESSION['user_fullname'];		
			
			/* check validations */

			if($name=='')
			{						
			$errors['name'] = 'Name Is Required!';				
			}	  
		
			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {

				parent::start_transaction();										
				$tableName 	= "ims_advertise_popup";
				$setValues 	= "name='$name',link='$link',updated_by='$updated_by', updated_at=NOW()";
				$whereClause= " WHERE id='$id'";
				$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
				$exSql		= parent::execQuery($updateSql);

				if($exSql){
					/* upload course files */				
					$courseImgPathDir 	= IMS_ADVERTISE_PATH.'/'.$id.'/'; 

					if($advertiseimage!='')
					{								
						$ext 			= pathinfo($_FILES["advertiseimage"]["name"], PATHINFO_EXTENSION);
						$file_name 		= $title.'_'.mt_rand(0,123456789).'_logo.'.$ext;
						$setValues 		= "image='$file_name'";
						$whereClause	= " WHERE id='$id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						parent::create_thumb_img($_FILES["advertiseimage"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);				
					}
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New advertise has been added successfully!';
					}
					//upload course image
					else{
							parent::rollback();
							$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
							$data['success'] = false;
							$data['errors']  = $errors;
						}
					}
		return json_encode($data);		
	}
	public function delete_advertise($id)
	{
		$sql = "UPDATE ims_advertise_popup SET delete_flag = 1 WHERE id='$id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	//refferal Amount
	public function edit_refferal_amount($id)
	{
		
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 			= parent::test(isset($_POST['id'])?$_POST['id']:'');
		$inst_id 		= parent::test(isset($_POST['inst_id'])?$_POST['inst_id']:'');
		$amount 		= parent::test(isset($_POST['amount'])?$_POST['amount']:'');
		
		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];		
		
		/* check validations */

			// if($marqueetext=='')
			// {						
			// $errors['marqueetext'] = 'Message Is Required!';				
			// }
	
			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();										
				$tableName 	= "referral_amount";
				$setValues 	= "amount='$amount', updated_by='$updated_by', updated_at=NOW()";
				$whereClause= " WHERE id='$id' AND inst_id='$inst_id'";
				$updateSql	= parent::updateData($tableName,$setValues,$whereClause);			
				$exSql		= parent::execQuery($updateSql);

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! New Refferal Amount has been added successfully!';
			}
									
			return json_encode($data);		
	}	
	public function list_refferal_amount($id='',$inst_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM  referral_amount A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id ='$id' ";
		}
		if($inst_id!='')
		{
			$sql .= " AND A.inst_id ='$inst_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

	//Background Images
	public function list_backgroundimages($id='',$inst_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM background_images A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id ='$id ' ";
		}
		if($inst_id!='')
		{
			$sql .= " AND A.inst_id ='$inst_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

	public function edit_backgroundimages($id)
	{
			
			$errors = array();  // array to hold validation errors
			$data = array();        // array to pass back data

			$id 					= parent::test(isset($_POST['id'])?$_POST['id']:'');		
			$inst_id 					= parent::test(isset($_POST['inst_id'])?$_POST['inst_id']:'');

			$certificate_image 		= isset($_FILES['certificate_image']['name'])?$_FILES['certificate_image']['name']:'';
			$marksheet_image 		= isset($_FILES['marksheet_image']['name'])?$_FILES['marksheet_image']['name']:'';
			$admissionform_image 	= isset($_FILES['admissionform_image']['name'])?$_FILES['admissionform_image']['name']:'';
			$idcard_image 			= isset($_FILES['idcard_image']['name'])?$_FILES['idcard_image']['name']:'';
			$hallticket_image 		= isset($_FILES['hallticket_image']['name'])?$_FILES['hallticket_image']['name']:'';
			$feesreceipt_image 		= isset($_FILES['feesreceipt_image']['name'])?$_FILES['feesreceipt_image']['name']:'';
			$atccert_image 			= isset($_FILES['atccert_image']['name'])?$_FILES['atccert_image']['name']:'';
			$typingmarksheet_image 	= isset($_FILES['typingmarksheet_image']['name'])?$_FILES['typingmarksheet_image']['name']:'';
	    	$seminar_image 	= isset($_FILES['seminar_image']['name'])?$_FILES['seminar_image']['name']:'';
	    	$performance_image 	= isset($_FILES['performance_image']['name'])?$_FILES['performance_image']['name']:'';
	    	$teacherid_image 	= isset($_FILES['teacherid_image']['name'])?$_FILES['teacherid_image']['name']:'';
	    	$birthday_image 	= isset($_FILES['birthday_image']['name'])?$_FILES['birthday_image']['name']:'';
			
			$updated_by  			= $_SESSION['user_fullname'];		

			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
			   

				parent::start_transaction();										
				$tableName 	= "background_images";
				$setValues 	= "updated_by='$updated_by', updated_at=NOW()";
				$whereClause= " WHERE id='$id' AND inst_id = '$inst_id'";
				$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
				$exSql		= parent::execQuery($updateSql);

				if($exSql){
					/* upload course files */				
					$courseImgPathDir 	= BACKGROUND_IMAGE_PATH.'/'.$inst_id.'/'; 

					if($certificate_image!='')
					{								
						$ext 			= pathinfo($_FILES["certificate_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'Certificate_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "certificate_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["certificate_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["certificate_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($marksheet_image!='')
					{								
						$ext 			= pathinfo($_FILES["marksheet_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'Marksheet_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "marksheet_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["marksheet_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["marksheet_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($admissionform_image!='')
					{								
						$ext 			= pathinfo($_FILES["admissionform_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'Admissionform_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "admissionform_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["admissionform_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["admissionform_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($idcard_image!='')
					{								
						$ext 			= pathinfo($_FILES["idcard_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'Idcard_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "idcard_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["idcard_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["idcard_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($hallticket_image!='')
					{								
						$ext 			= pathinfo($_FILES["hallticket_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'HallTicket_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "hallticket_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["hallticket_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["hallticket_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($feesreceipt_image!='')
					{								
						$ext 			= pathinfo($_FILES["feesreceipt_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'FeesReceipt_'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "feesreceipt_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["feesreceipt_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["feesreceipt_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($atccert_image!='')
					{								
						$ext 			= pathinfo($_FILES["atccert_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'ATCCert'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "atccert_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["atccert_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["atccert_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($typingmarksheet_image!='')
					{								
						$ext 			= pathinfo($_FILES["typingmarksheet_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'TypingMarksheet'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "typingmarksheet_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["typingmarksheet_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["typingmarksheet_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($seminar_image!='')
					{								
						$ext 			= pathinfo($_FILES["seminar_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'SeminarCert'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "seminar_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["seminar_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["seminar_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($performance_image!='')
					{								
						$ext 			= pathinfo($_FILES["performance_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'PerformanceCert'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "performance_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["performance_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["performance_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					if($teacherid_image!='')
					{								
						$ext 			= pathinfo($_FILES["teacherid_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'TeacherId'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "teacherid_image='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["teacherid_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["teacherid_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					
					if($birthday_image!='')
					{								
						$ext 			= pathinfo($_FILES["birthday_image"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'Birthday'.mt_rand(0,123456789).'.'.$ext;
						$setValues 		= "birthdayimage='$file_name'";
						$whereClause	= " WHERE id='$id' AND inst_id = '$inst_id'";
						$updateSql		= parent::updateData($tableName,$setValues,$whereClause);	
						$exSql			= parent::execQuery($updateSql);
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["birthday_image"]["tmp_name"], $courseImgPathFile,  $ext, 1800, 1750) ;
						parent::create_thumb_img($_FILES["birthday_image"]["tmp_name"], $courseImgThumbPathFile,  $ext, 1300, 1280);				
					}
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! New Image has been added successfully!';
					}
					//upload course image
					else{
							parent::rollback();
							$errors['message'] = 'Sorry! Something went wrong! Could not add the user.';
							$data['success'] = false;
							$data['errors']  = $errors;
						}
					}
		return json_encode($data);		
	}
	//old certificates
	public function list_oldcertificates($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM old_certificates_data A WHERE A.delete_flag=0 AND A.active=1 ";
		
		if($id!='')
		{
			$sql .= " AND A.id ='$id ' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}	
	public function add_old_certificate()
	{
		  $errors 	= array();  // array to hold validation errors
		  $data 	= array();        // array to pass back data
		//   print_r($_POST);
		//   print_r($_FILES); 

		  $certfile 	= parent::test(isset($_FILES['certfile']['name'])?$_FILES['certfile']['name']:'');		
		  $status 		= parent::test(isset($_POST['status'])?$_POST['status']:'');

		  $password 		= parent::test(isset($_POST['password'])?$_POST['password']:'');
	
		  $admin_id 	= $_SESSION['user_id'];
		  $created_by  	= $_SESSION['user_fullname'];
		  
		 /* check validations */		  
		 if ($certfile=='') $errors['certfile'] = 'CSV file is required!';
		 if($password=='')
			{
				$errors['password'] = "Required! Please enter the password.";
			}
		 if($certfile!='')
		 {			
			$allowed_ext = array('csv');				
			$extension = pathinfo($certfile, PATHINFO_EXTENSION);
			if(!in_array($extension, $allowed_ext))
			{					
				$errors['certfile'] = 'Invalid file format! Please select valid csv file.';
			}
		 } 			
		 if(!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
					if($certfile!='' && $password == 'Amzad@#$HDIPassword')
					{
					
						parent::start_transaction();
						$ext 		= pathinfo($_FILES["certfile"]["name"], PATHINFO_EXTENSION);
						$randno 	= @date('d_m_Y').'_'.mt_rand(0,123456789);
						$file_name  = 'certfile_'.$randno.'.'.$ext;					
						$img_zip_file_name='';

							
						$courseImgPathFile 	= 	OLDCERTIFICATE_SAVE_PATH.'/'.$file_name;
						@mkdir(OLDCERTIFICATE_SAVE_PATH.'/',0777,true);
						@move_uploaded_file($_FILES["certfile"]["tmp_name"], $courseImgPathFile);						
				
						//Import uploaded file to Database
						if(file_exists($courseImgPathFile))
						{
							$handle = fopen($courseImgPathFile, "r");
							$tableName1 	= "old_certificates_data";
							$i=0;
							while (($data = fgetcsv($handle,1000,',')) !== FALSE) 
							{	
								//skip the first row					
								if($i>0)
								{
									//print_r($data);
									$cert_number 		= isset($data[0])?$data[0]:'';									
									$cert_date 			= isset($data[1])?$data[1]:'';
									$name 				= isset($data[2])?$data[2]:'';
									$course_name 		= isset($data[3])?$data[3]:'';
									$course_duration 	= isset($data[4])?$data[4]:'';
									$marks 				= isset($data[5])?$data[5]:'';
									$grade 				= isset($data[6])?$data[6]:'';
									$institute_name 	= isset($data[7])?$data[7]:'';
									$institute_address 	= isset($data[8])?$data[8]:'';
									$email 				= isset($data[9])?$data[9]:'';
									$contact_number 	= isset($data[10])?$data[10]:'';
									
									$tabFields1 	= "(id,cert_number,cert_date,name,course_name,course_duration,marks,grade,institute_name,institute_address,email,contact_number,active,delete_flag,created_by,created_at)";				
									 $insertVals1	= "(NULL,'$cert_number','$cert_date', '$name','$course_name','$course_duration','$marks', '$grade', '$institute_name','$institute_address','$email','$contact_number','$status','0','$created_by',NOW())";						
									$insertSql1		= parent::insertData($tableName1,$tabFields1,$insertVals1);	
									$exSql1 		= parent::execQuery($insertSql1);
								}
								$i++;
							}
							fclose($handle);							
						}						
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Certificates has been added successfully!';

						
					}
					else{
							parent::rollback();
							$errors['message'] = 'Sorry! Something went wrong! Could not add the certificates.';
							$data['success'] = false;
							$data['errors']  = $errors;
						}
				}					
			
		return json_encode($data);			
	}
	public function update_old_certificate()
	{
		
		$errors = array();  // array to hold validation errors
		$data = array();        // array to pass back data

		$id 				= parent::test(isset($_POST['id'])?$_POST['id']:'');
		$cert_number 		= parent::test(isset($_POST['cert_number'])?$_POST['cert_number']:'');
		$cert_date 			= parent::test(isset($_POST['cert_date'])?$_POST['cert_date']:'');
		$name 				= parent::test(isset($_POST['name'])?$_POST['name']:'');
		$course_name 		= parent::test(isset($_POST['course_name'])?$_POST['course_name']:'');
		$course_duration 	= parent::test(isset($_POST['course_duration'])?$_POST['course_duration']:'');
		$marks 				= parent::test(isset($_POST['marks'])?$_POST['marks']:'');
		$grade 				= parent::test(isset($_POST['grade'])?$_POST['grade']:'');
		$institute_name 		= parent::test(isset($_POST['institute_name'])?$_POST['institute_name']:'');
		$institute_address 		= parent::test(isset($_POST['institute_address'])?$_POST['institute_address']:'');
		$email 					= parent::test(isset($_POST['email'])?$_POST['email']:'');
		$contact_number 		= parent::test(isset($_POST['contact_number'])?$_POST['contact_number']:'');
		
		$role 					= 2; //institute;
		$updated_by  		= $_SESSION['user_fullname'];	
	
			if (! empty($errors)) {
				// if there are items in our errors array, return those errors
				$data['success'] = false;
				$data['errors']  = $errors;
				$data['message']  = 'Please correct all the errors.';
			} else {
				parent::start_transaction();										
				$tableName 	= "old_certificates_data";
				$setValues 	= "cert_number='$cert_number',cert_date='$cert_date',name='$name',course_name='$course_name',course_duration='$course_duration',marks='$marks',grade='$grade',institute_name='$institute_name',institute_address='$institute_address',email='$email',contact_number='$contact_number',updated_by='$updated_by', updated_at=NOW()";
				$whereClause= " WHERE id='$id'";
				$updateSql	= parent::updateData($tableName,$setValues,$whereClause);			
				$exSql		= parent::execQuery($updateSql);

				parent::commit();
				$data['success'] = true;
				$data['message'] = 'Success! Certificate has been updated successfully!';
			}
									
			return json_encode($data);		
	}	
	public function delete_old_certificate($id)
	{
		$sql = "UPDATE old_certificates_data SET delete_flag=1,active=0, updated_by='".$_SESSION['user_fullname']."', updated_at=NOW() WHERE id='$id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	//Products
	public function add_product()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data
		  
		  //print_r($_POST); exit();
		  $name 		= parent::test(isset($_POST['name'])?$_POST['name']:'');		 
		  $link 		= parent::test(isset($_POST['link'])?$_POST['link']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
	
		  $created_by  		= $_SESSION['user_fullname'];
		 /* check validations */
		  if ($name=='')
			$errors['name'] = 'Name is required.';
		  if ($link=='')
			$errors['link'] = 'Link is required.';
		 
		 
		 //$errors=array();
           if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {				
					$tableName 	= "product";
					$tabFields 	= "(id, name, link,active,delete_flag,created_by,created_at)";
					$insertVals	= "(NULL, '$name', '$link','$status','0','$created_by',NOW())";

					 $insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{						    
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Product has been added successfully!';
					
					}else{
						parent::rollback();
						$data['message'] = 'Sorry! Something went wrong! Could not add the Product.';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}
	
	/* update institute 
	@param: 
	@return: json
	*/
	public function update_product($id)
	{
	     //print_r($_POST); exit();
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $id               = parent::test(isset($_POST['id'])?$_POST['id']:'');
		  $name 		= parent::test(isset($_POST['name'])?$_POST['name']:'');		 
		  $link 		= parent::test(isset($_POST['link'])?$_POST['link']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		
		 $updated_by  	= $_SESSION['user_fullname'];

		 	if ($name=='')
				$errors['name'] = 'Name is required.';
			if ($link=='')
				$errors['link'] = 'Link is required.';

		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {												
					$tableName 	= "product";
					$setValues 	= "name='$name', link='$link',updated_by='$updated_by', updated_at=NOW()";
					$whereClause= " WHERE id='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);

					$exSql	= parent::execQuery($updateSql);	
				
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Product has been updated successfully!';
				 
			}
		return json_encode($data);			
	}
	
	public function list_product($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM product A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id='$id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.created_at DESC';
	    //echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

    public function delete_product($id)
	{
		$sql = "UPDATE product SET active='0',delete_flag='1', updated_by='".$_SESSION['user_fullname']."' WHERE id='$id'";	 
		$res= parent::execQuery($sql);		
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}


}

?>