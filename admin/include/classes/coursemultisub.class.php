<?php
include_once('database_results.class.php');
include_once('access.class.php');

class coursemultisub extends access
{

	
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_course_multi_sub()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $coursecode 		= parent::test(isset($_POST['coursecode'])?$_POST['coursecode']:'');
		  $award 			= parent::test(isset($_POST['award'])?$_POST['award']:'');
		  $coursename 		= parent::test(isset($_POST['coursename'])?$_POST['coursename']:'');
		  $duration 		= parent::test(isset($_POST['duration'])?$_POST['duration']:'');
		  $detail 			= parent::test(isset($_POST['detail'])?$_POST['detail']:'');
		  $eligibility 		= parent::test(isset($_POST['eligibility'])?$_POST['eligibility']:'');
		  //$fees 			= parent::test(isset($_POST['fees'])?$_POST['fees']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  //$display_coursefess 			= parent::test(isset($_POST['display_coursefess'])?$_POST['display_coursefess']:'');

		  $video1 		= parent::test(isset($_POST['video1'])?$_POST['video1']:'');
		  $video2 		= parent::test(isset($_POST['video2'])?$_POST['video2']:'');

		  //$coursefees 		= parent::test(isset($_POST['coursefees'])?$_POST['coursefees']:'');
		  //$coursemrp 		= parent::test(isset($_POST['coursemrp'])?$_POST['coursemrp']:'');
		  //$minimumfees 		= parent::test(isset($_POST['minimumfees'])?$_POST['minimumfees']:'');
		  
		   $filecount1 		= parent::test(isset($_POST['filecount1'])?$_POST['filecount1']:'');
		   $filecount2 		= parent::test(isset($_POST['filecount2'])?$_POST['filecount2']:'');
		  /* Files */
		  $filecount 		= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');

		  $filecount3 		= parent::test(isset($_POST['filecount3'])?$_POST['filecount3']:'');

		  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';	
		  
		  $inst_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($coursecode=='')
			$errors['coursecode'] = 'Course code is required!'; 
		  if ($award=='')
			$errors['award'] = 'Course award is required!';
		  if ($coursename=='')
			$errors['coursename'] = 'Course name is required!';

// 		if ($coursefees=='')
// 		$errors['coursefees'] = 'Course Fees is required!';
// 		if ($coursemrp=='')
// 		$errors['coursemrp'] = 'Course MRP is required!';
// 		if ($minimumfees=='')
// 		$errors['minimumfees'] = 'Minimum fees is required!';

		  if ($duration=='')
			$errors['duration'] = 'Course duration is required!';
	
	
		//   if ($detail=='')
		// 	$errors['detail'] = 'Course details is required!';
		 // if ($eligibility=='')
		//	$errors['eligibility'] = 'Course eligibility is required!';
	/*	  if ($fees=='')
			$errors['fees'] = 'Course fees is required!';
		  if ($fees!='' && !is_numeric($fees))
			$errors['fees'] = 'Invalid fees entered!';*/	
		
		  if(!$this->validate_course_code($coursecode,''))
			$errors['coursecode'] = 'Sorry! Course code is already present.';
		
		  /* files validations */
		 
		 if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		//   if($filecount>=1)
		//   {
		// 	  for($i=0; $i<$filecount; $i++)
		// 	  {
		// 		$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
		// 		if($coursematerial!='')
		// 		{
		// 			$filetitle 		= parent::test(isset($_POST['filetitle'.$i])?$_POST['filetitle'.$i]:'');				
		// 			$allowed_ext 	= array('jpg','jpeg','png','doc', 'docx', 'txt', 'pdf');				
		// 			$extension 		= pathinfo($coursematerial, PATHINFO_EXTENSION);
		// 			if(!in_array($extension, $allowed_ext))
		// 			{					
		// 				$errors['coursematerial'.$i] = 'Invalid file format! Please select valid file.';
		// 			}
		// 		}
		// 	  }
		//   }
		  if($filecount1>=1)
		  {
			  for($i=0; $i<$filecount1; $i++)
			  {
			  	$plan 			= parent::test(isset($_POST['plan'.$i])?$_POST['plan'.$i]:'');
				$fees 			= parent::test(isset($_POST['fees'.$i])?$_POST['fees'.$i]:'');	 				  					  				  	
			  	if ($plan=='')
					$errors['plan'.$i] = 'Please Select Plan!';                                                                                                                               
				if ($fees=='')
					$errors['fees'.$i] = 'Fees is required!';
				if ($fees!='' && !is_numeric($fees))
					$errors['fees'.$i] = 'Invalid fees entered!'; 
			  }			 
					
		  }
		  if($filecount2>=1)
		  {
			  for($i=0; $i<$filecount2; $i++)
			  {
			  	$subject 			= parent::test(isset($_POST['subject'.$i])?$_POST['subject'.$i]:'');
				
			  	if ($subject=='')
					$errors['subject'.$i] = 'Please Enter Subject!';                                       
				
			  }			 
					
		  }
		//   if($filecount3>=1)
		//   {
		// 	  for($i=0; $i<$filecount3; $i++)
		// 	  {
		// 		$videotitle 		= parent::test(isset($_POST['videotitle'.$i])?$_POST['videotitle'.$i]:'');
		// 		$videomaterial 		= parent::test(isset($_POST['videomaterial'.$i])?$_POST['videomaterial'.$i]:'');

		// 		if($videotitle == ''){
		// 			$errors['videotitle'.$i] = 'Video Title Is Required.';
		// 		}

		// 		if($videomaterial == ''){
		// 			$errors['videomaterial'.$i] = 'Video Link Is Required.';
		// 		}

		// 	  }
		//   }
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
					parent::start_transaction();
					$tableName 	= "multi_sub_courses";
					$tabFields 	= "(MULTI_SUB_COURSE_ID, MULTI_SUB_COURSE_CODE,MULTI_SUB_COURSE_AWARD, MULTI_SUB_COURSE_DURATION, MULTI_SUB_COURSE_NAME,MULTI_SUB_COURSE_DETAILS,MULTI_SUB_COURSE_ELIGIBILITY,ACTIVE,CREATED_BY, CREATED_ON,VIDEO1,VIDEO2)";
					$insertVals	= "(NULL, UPPER('M-$coursecode'),'$award', UPPER('$duration'), UPPER('$coursename'),'$detail','$eligibility','$status','$created_by',NOW(),'$video1','$video2')";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{
						/* upload course files */
						$last_insert_id 	= parent::last_id();	
						
						$tableName2 	= "institute_courses";
						$tabFields2 	= "(INSTITUTE_COURSE_ID, INSTITUTE_ID, COURSE_ID, MULTI_SUB_COURSE_ID,COURSE_FEES,ACTIVE, CREATED_BY,CREATED_ON)";
						$insertVals2	= "(NULL, '$inst_id', '0','$last_insert_id','$coursefees','$status','$created_by',NOW())";
						$insertSql2		= parent::insertData($tableName2,$tabFields2,$insertVals2);
						$exSql2			= parent::execQuery($insertSql2);

						$courseImgPathDir 	= COURSE_WITH_SUB_MATERIAL_PATH.'/'.$last_insert_id.'/';
						$tableName5 		= "multi_sub_courses_subjects";
						if($filecount2>=1)
						{
							for($k=0; $k<$filecount2; $k++)
							{
							$subject 			= parent::test(isset($_POST['subject'.$k])?$_POST['subject'.$k]:'');
						
							if($subject!='')
							{
															
								$tabFields5 	= "(COURSE_SUBJECT_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_NAME,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
								$insertVals5	= "(NULL, '$last_insert_id', '$subject','1','0','$created_by',NOW())";
								$insertSql5		= parent::insertData($tableName5,$tabFields5,$insertVals5);
								$exec5   		= parent::execQuery ($insertSql5);																
							}								
							}
						}
						$tableName4 		= "multi_sub_course_plan_fees";
						if($filecount1>=1)
						{
							for($j=0; $j<$filecount1; $j++)
							{
							  $plan 			= parent::test(isset($_POST['plan'.$j])?$_POST['plan'.$j]:'');
								$fees 			= parent::test(isset($_POST['fees'.$j])?$_POST['fees'.$j]:'');
							  if($plan!='' && $fees!='')
							  {
															  
								  $tabFields4 	= "(COURSE_PLAN_FEES_ID,MULTI_SUB_COURSE_ID,PLAN_ID,COURSE_FEES,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
								  $insertVals4	= "(NULL, '$last_insert_id', '$plan','$fees','1',0,'$created_by',NOW())";
								  $insertSql4	= parent::insertData($tableName4,$tabFields4,$insertVals4); 
								  $exec4   		= parent::execQuery ($insertSql4);																
							  }								
							}
						}		
                        //upload course image
						if($course_img!='')
						{								
							$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
							$file_name 		= $coursecode.'_logo.'.$ext;
							$setValues 		= "MULTI_SUB_COURSE_IMAGE='$file_name'";
							$whereClause	= " WHERE MULTI_SUB_COURSE_ID='$last_insert_id'";
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
              	          $tableName3 		= "multi_sub_courses_files";
						  if($filecount>=1)
						  {
							  for($i=0; $i<$filecount; $i++)
							  {
								$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
								if($coursematerial!='')
								{
									$filetitle 		= parent::test(isset($_POST['filetitle'.$i])?$_POST['filetitle'.$i]:$coursecode);
									
									$ext 			= pathinfo($_FILES["coursematerial".$i]["name"], PATHINFO_EXTENSION);
									$file_name 		= $coursecode.'_'.mt_rand(0,123456789).'.'.$ext;								
									$tabFields3 	= "(FILE_ID,MULTI_SUB_COURSE_ID,FILE_NAME,FILE_MIME,FILE_LABEL,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
									$insertVals3	= "(NULL, '$last_insert_id', '$file_name','$ext','$filetitle','1','0','$created_by',NOW())";
									$insertSql3		= parent::insertData($tableName3,$tabFields3,$insertVals3);
									$exec3   		= parent::execQuery ($insertSql3);
									
									$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
									@mkdir($courseImgPathDir,0777,true);
									@move_uploaded_file($_FILES["coursematerial".$i]["tmp_name"], $courseImgPathFile);
								}								
							  }
						  }	
						 	$tableName4 		= "multi_sub_course_videos";
							if($filecount3>=1)
							{
								for($i=0; $i<$filecount3; $i++)
								{
								
									$videotitle 		= parent::test(isset($_POST['videotitle'.$i])?$_POST['videotitle'.$i]:$videotitle);
									$videomaterial 		= parent::test(isset($_POST['videomaterial'.$i])?$_POST['videomaterial'.$i]:$videomaterial);
										
									if($videomaterial!='' || $videotitle!='')
							  		{
									$tabFields4 	= "(id,course_id,video_link,title,active,delete_flag,created_by,created_at)";
									$insertVals4	= "(NULL, '$last_insert_id', '$videomaterial','$videotitle','1',0,'$created_by',NOW())";
									$insertSql4		= parent::insertData($tableName4,$tabFields4,$insertVals4);
									$exec4   		= parent::execQuery ($insertSql4);	
									}											
								}
							}
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New course with subjects has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the course.';
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
	public function update_course_multi_sub($course_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $course_id 		= parent::test(isset($_POST['course_id'])?$_POST['course_id']:'');
			$award 		= parent::test(isset($_POST['award'])?$_POST['award']:'');		 
		 $coursecode 		= parent::test(isset($_POST['coursecode'])?$_POST['coursecode']:'');
		  $coursename 		= parent::test(isset($_POST['coursename'])?$_POST['coursename']:'');
		  $duration 		= parent::test(isset($_POST['duration'])?$_POST['duration']:'');
		  $detail 			= parent::test(isset($_POST['detail'])?$_POST['detail']:'');
		  $eligibility 		= parent::test(isset($_POST['eligibility'])?$_POST['eligibility']:'');
		  //$fees 			= parent::test(isset($_POST['fees'])?$_POST['fees']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  //$display_coursefess 			= parent::test(isset($_POST['display_coursefess'])?$_POST['display_coursefess']:'');

		  $video1 		= parent::test(isset($_POST['video1'])?$_POST['video1']:'');
		  $video2 		= parent::test(isset($_POST['video2'])?$_POST['video2']:'');
		  
		  //$coursefees 		= parent::test(isset($_POST['coursefees'])?$_POST['coursefees']:'');
		  //$coursemrp 		= parent::test(isset($_POST['coursemrp'])?$_POST['coursemrp']:'');
		  //$minimumfees 		= parent::test(isset($_POST['minimumfees'])?$_POST['minimumfees']:'');
		  $filecount1 		= parent::test(isset($_POST['filecount1'])?$_POST['filecount1']:'');
		  $filecount2 		= parent::test(isset($_POST['filecount2'])?$_POST['filecount2']:'');
		  /* Files */
		  $filecount 		= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');	

		  $filecount3 		= parent::test(isset($_POST['filecount3'])?$_POST['filecount3']:'');
 			
		  $course_img 		= isset($_FILES['course_img']['name'])?$_FILES['course_img']['name']:'';
		
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($coursecode=='')
			$errors['coursecode'] = 'Course code is required!'; 
		if ($award=='')
			$errors['award'] = 'Course award is required!';
		  if ($coursename=='')
			$errors['coursename'] = 'Course name is required!';
		  if ($duration=='')
			$errors['duration'] = 'Course duration is required!';
		//   if ($detail=='')
		// 	$errors['detail'] = 'Course details is required!';
		//   if ($eligibility=='')
		// 	$errors['eligibility'] = 'Course eligibility is required!';

// 			if ($coursefees=='')
// 			$errors['coursefees'] = 'Course Fees is required!';
// 			if ($coursemrp=='')
// 			$errors['coursemrp'] = 'Course MRP is required!';
// 			if ($minimumfees=='')
// 			$errors['minimumfees'] = 'Minimum fees is required!';
		
		 /* if ($fees=='')
			$errors['fees'] = 'Course fees is required!';
		  if ($fees!='' && !is_numeric($fees))
			$errors['fees'] = 'Invalid fees entered!';	*/
		
		  if(!$this->validate_course_code($coursecode, $course_id))
			$errors['coursecode'] = 'Sorry! Course code is already present.';
		if($course_img!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif');				
				$extension = pathinfo($course_img, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['course_img'] = 'Invalid file format! Please select valid image file.';
				}
		  }
		//   if($filecount>=1)
		//   {
		// 	  for($i=0; $i<$filecount; $i++)
		// 	  {
		// 		$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
		// 		if($coursematerial!='')
		// 		{
		// 			$filetitle 		= parent::test(isset($_POST['filetitle'.$i])?$_POST['filetitle'.$i]:'');				
		// 			$allowed_ext 	= array('jpg','jpeg','png','doc', 'docx', 'txt', 'pdf');				
		// 			$extension 		= pathinfo($coursematerial, PATHINFO_EXTENSION);
		// 			if(!in_array($extension, $allowed_ext))
		// 			{					
		// 				$errors['coursematerial'.$i] = 'Invalid file format! Please select valid file.';
		// 			}
		// 		}
		// 	  }
		//   }

			if($filecount1>=1)
			{
				for($i=0; $i<$filecount1; $i++)
				{
					$plan 			= parent::test(isset($_POST['plan'.$i])?$_POST['plan'.$i]:'');
				$fees 			= parent::test(isset($_POST['fees'.$i])?$_POST['fees'.$i]:'');	 				  					  				  	
					if ($plan=='')
					$errors['plan'.$i] = 'Please Select Plan!';                                                                                                                               
				if ($fees=='')
					$errors['fees'.$i] = 'Fees is required!';
				if ($fees!='' && !is_numeric($fees))
					$errors['fees'.$i] = 'Invalid fees entered!'; 
				}			 
					
			}

		  if($filecount2>=1)
		  {
			  for($i=0; $i<$filecount2; $i++)
			  {
			  		$subject = parent::test(isset($_POST['subject'.$i])?$_POST['subject'.$i]:'');
				
			  		if ($subject=='')
						$errors['subject'.$i] = 'Please Enter Subject!';
				
			  }			 
					
		  }

		
		  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "multi_sub_courses";
					$setValues 	= "MULTI_SUB_COURSE_CODE=UPPER('$coursecode'), MULTI_SUB_COURSE_AWARD=UPPER('$award'), MULTI_SUB_COURSE_DURATION=UPPER('$duration'), MULTI_SUB_COURSE_NAME=UPPER('$coursename'),MULTI_SUB_COURSE_DETAILS='$detail',MULTI_SUB_COURSE_ELIGIBILITY='$eligibility',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW(),VIDEO1='$video1',VIDEO2='$video2'";
					$whereClause= " WHERE MULTI_SUB_COURSE_ID='$course_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);

					$tableName1 	= "institute_courses";
					$setValues1 	= "COURSE_FEES='$coursefees',ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause1	= " WHERE MULTI_SUB_COURSE_ID='$course_id'";
					$updateSql1		= parent::updateData($tableName1,$setValues1,$whereClause1);			
					$exSql1			= parent::execQuery($updateSql1);

					$courseImgPathDir = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$course_id.'/';
					$tableName5 		= "multi_sub_courses_subjects";
					if($filecount2>=1)
					  {
						  for($k=0; $k<$filecount2; $k++)
						  {
							$course_multi_sub_id 			= parent::test(isset($_POST['course_multi_sub_id'.$k])?$_POST['course_multi_sub_id'.$k]:'');
							$subject 			= parent::test(isset($_POST['subject'.$k])?$_POST['subject'.$k]:'');
				  			
							if($course_multi_sub_id=='')
							{
															
								$tabFields5 	= "(COURSE_SUBJECT_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_NAME,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
								$insertVals5	= "(NULL, '$course_id', '$subject','1','0','$created_by',NOW())";
								$insertSql5		= parent::insertData($tableName5,$tabFields5,$insertVals5); 
								$exec5   		= parent::execQuery ($insertSql5);																
							}else{
								$setValues5 	= "MULTI_SUB_COURSE_ID='$course_id', COURSE_SUBJECT_NAME='$subject',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
								$whereClause5= " WHERE COURSE_SUBJECT_ID='$course_multi_sub_id'";
								$updateSql5	= parent::updateData($tableName5,$setValues5,$whereClause5);				
								$exSql5	= parent::execQuery($updateSql5);
							}								
						  }
					  }	

					  	// plan course

					$tableName4 		= "multi_sub_course_plan_fees";
					if($filecount1>=1)
					  {
						  for($j=0; $j<$filecount1; $j++)
						  {
							$course_plan_fees_id 			= parent::test(isset($_POST['course_plan_fees_id'.$j])?$_POST['course_plan_fees_id'.$j]:'');
							$plan 			= parent::test(isset($_POST['plan'.$j])?$_POST['plan'.$j]:'');
				  			$fees 			= parent::test(isset($_POST['fees'.$j])?$_POST['fees'.$j]:'');
							if($course_plan_fees_id=='')
							{
															
								$tabFields4 	= "(COURSE_PLAN_FEES_ID,MULTI_SUB_COURSE_ID,PLAN_ID,COURSE_FEES,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
								$insertVals4	= "(NULL, '$course_id', '$plan','$fees','1',0,'$created_by',NOW())";
								$insertSql4		= parent::insertData($tableName4,$tabFields4,$insertVals4); 
								$exec4   		= parent::execQuery ($insertSql4);																
							}else{
								$setValues4 	= "MULTI_SUB_COURSE_ID='$course_id', PLAN_ID='$plan', COURSE_FEES='$fees',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
								$whereClause4= " WHERE COURSE_PLAN_FEES_ID='$course_plan_fees_id'";
								$updateSql4	= parent::updateData($tableName4,$setValues4,$whereClause4);				
								$exSql4	= parent::execQuery($updateSql4);
							}								
						  }
					  }	 
					       
					//upload course image
					if($course_img!='')
					{								
						$ext 			= pathinfo($_FILES["course_img"]["name"], PATHINFO_EXTENSION);
						$file_name 		= $coursecode.'_logo.'.$ext;
						$setValues 		= "MULTI_SUB_COURSE_IMAGE='$file_name'";
						$whereClause	= " WHERE MULTI_SUB_COURSE_ID='$course_id'";
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
					
					$tableName3 	  = "multi_sub_courses_files";
					/* upload files */
					 if($filecount>=1)
					  {
						  for($i=0; $i<$filecount; $i++)
						  {
							$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
							if($coursematerial!='')
							{
								$filetitle 		= parent::test(isset($_POST['filetitle'.$i])?$_POST['filetitle'.$i]:$coursecode);
								
								$ext 			= pathinfo($_FILES["coursematerial".$i]["name"], PATHINFO_EXTENSION);
								$file_name 		= $coursecode.'_'.mt_rand(0,123456789).'.'.$ext;								
								$tabFields3 	= "(FILE_ID,MULTI_SUB_COURSE_ID,FILE_NAME,FILE_MIME,FILE_LABEL,ACTIVE,CREATED_BY,CREATED_ON)";
								$insertVals3	= "(NULL, '$course_id', '$file_name','$ext','$filetitle','1','$updated_by',NOW())";
								$insertSql3		= parent::insertData($tableName3,$tabFields3,$insertVals3);
								$exec3   		= parent::execQuery ($insertSql3);
                            
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);
								@move_uploaded_file($_FILES["coursematerial".$i]["tmp_name"], $courseImgPathFile);
							}
							
						  }
					  }		
					  
					  $tableName4 		= "multi_sub_course_videos";
					  if($filecount3>=1)
					  {
						  for($i=0; $i<$filecount3; $i++)
						  {
						  
							  $videotitle 		= parent::test(isset($_POST['videotitle'.$i])?$_POST['videotitle'.$i]:$videotitle);
							  $videomaterial 		= parent::test(isset($_POST['videomaterial'.$i])?$_POST['videomaterial'.$i]:$videomaterial);
							  if($videomaterial!='' || $videotitle!='')
							  {													  
							  $tabFields4 	= "(id,course_id,video_link,title,active,delete_flag,created_by,created_at)";
							  $insertVals4	= "(NULL, '$course_id', '$videomaterial','$videotitle','1',0,'$created_by',NOW())";
							  $insertSql4		= parent::insertData($tableName4,$tabFields4,$insertVals4);
							  $exec4   		= parent::execQuery ($insertSql4);
							  }												
						  }
					  }
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Course With Subjects has been updated successfully!';
			}
		return json_encode($data);			
	}
	public function update_inst_course_fees()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $inst_course_id 	= parent::test(isset($_POST['inst_course_id'])?$_POST['inst_course_id']:'');
		  $coursefees 		= parent::test(isset($_POST['coursefees'])?$_POST['coursefees']:'');
		  $courseminimumfees 		= parent::test(isset($_POST['courseminimumfees'])?$_POST['courseminimumfees']:'');
		  
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $updated_by  		= $_SESSION['user_fullname'];
		 /* check validations */	
		  if ($coursefees=='')
			$errors['coursefees'] = 'Course fees is required!';
		  if ($coursefees!='' && !is_numeric($coursefees))
			$errors['coursefees'] = 'Invalid coursefees entered!';	
		  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "institute_courses";
					$setValues 	= "COURSE_FEES='$coursefees',MINIMUM_FEES='$courseminimumfees',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);							
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Course fees has been updated successfully!';
			}
		return json_encode($data);			
	}
	public function list_courses_multi_sub($course_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.*, (SELECT B.AWARD FROM course_awards B WHERE B.AWARD_ID=A.MULTI_SUB_COURSE_AWARD) AS COURSE_AWARD_NAME FROM multi_sub_courses A WHERE A.DELETE_FLAG=0 ";
		
		if($course_id!='')
		{
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		//echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	public function get_course_docs_single($course_id='', $file_label='')
	{
		$fileLink='';
		$data= array();
		$target='';
		
		$sql ="SELECT * FROM institute_files WHERE 1";
		if($course_id!='')
			$sql .= " AND COURSE_ID='$course_id'";
		if($file_label!='')
			$sql .= " AND FILE_LABEL='$file_label'";
		$sql .= ' ORDER BY FILE_ID ';
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$rec = $res->fetch_assoc();
			$FILE_ID = $rec['FILE_ID'];
			$FILE_NAME = $rec['FILE_NAME'];
			$FILE_LABEL = $rec['FILE_LABEL'];
			$COURSE_ID = $rec['COURSE_ID'];
			if($FILE_NAME!='')
			{			
				$fileLink = INSTITUTE_DOCUMENTS_PATH.'/'.$COURSE_ID.'/'.$FILE_NAME;
				$img =  '<a href="'.$fileLink.'" target="_blank">'.$FILE_LABEL.'</a>';
			}
			else{				
				$img =  'No files';
			}
			
			
		}
		
		return $img;
	}
	
	public function get_course_multi_sub_docs_all($course_id='', $display=true)
	{
		$img = '';
		$data= array();
		$target='';
		
		$sql ="SELECT FILE_ID,FILE_NAME,FILE_LABEL,MULTI_SUB_COURSE_ID FROM multi_sub_courses_files WHERE 1";
		if($course_id!='')
			$sql .= " AND MULTI_SUB_COURSE_ID='$course_id'";		
		$sql .= ' ORDER BY FILE_ID DESC';
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table"><thead><tr> 
			<th>File Name</th>
			<th>Action</th>
		</tr></thead>';
			while($rec = $res->fetch_assoc())
			{
				$FILE_ID 		= $rec['FILE_ID'];
				$FILE_NAME 		= $rec['FILE_NAME'];
				$FILE_LABEL 		= $rec['FILE_LABEL'];
				$MULTI_SUB_COURSE_ID 		= $rec['MULTI_SUB_COURSE_ID'];
				if($FILE_NAME!='')
				{
					if(!$display)			
					{	
						$data1 = array("file_id" => $FILE_ID, "file_name" => $FILE_NAME, "file_label" => $FILE_LABEL, "course_id" => $MULTI_SUB_COURSE_ID);	array_push($data, $data1);
					}else{
					
						$fileLink = COURSE_WITH_SUB_MATERIAL_PATH.'/'.$MULTI_SUB_COURSE_ID.'/'.$FILE_NAME;
						
						$img .= '<tr id="file-area'.$FILE_ID.'">
									<td><a href="'.$fileLink.'" target="_blank">'.$FILE_LABEL.'</a></td>
									<td>
										<a href="javascript:void(0)" title= "Delete File" onclick="deleteCourseMultiSubFile('.$FILE_ID.','.$MULTI_SUB_COURSE_ID.')" class="btn btn-danger table-btn"><i class="mdi mdi-delete"></i></a>
										&nbsp;&nbsp;
										<a href="'.$fileLink.'" target="_blank" class="btn btn-primary table-btn" title="View File"><i class="mdi mdi-file-pdf"></i></a>
									</td>
								  </tr>';
						
					}
					
				}				
			}			
		}
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;
	}

	
	//video files
	public function get_course_multi_sub_videos_all($course_id='', $display=true)
	{
		$img = '';
		$data= array();
		$target='';
		
		$sql ="SELECT id,course_id,video_link,title FROM multi_sub_course_videos WHERE 1";
		if($course_id!='')
			$sql .= " AND course_id='$course_id'";		
		$sql .= ' ORDER BY id DESC';
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table"><thead><tr> 
			<th>Name</th>
			<th>Video Link</th>
			<th>Action</th>
		</tr></thead>';
			while($rec = $res->fetch_assoc())
			{
				$id 		= $rec['id'];
				$title 		= $rec['title'];
				$video_link = $rec['video_link'];
				$course_id 	= $rec['course_id'];
				if($id!='')
				{
					if(!$display)			
					{	
						$data1 = array("id" => $id, "title" => $title, "video_link" => $video_link, "course_id" => $course_id);	array_push($data, $data1);
					}else{					
							$img .= '
							<tr id="videos'.$id.'">
									<td>'.$title.'</td>
									<td>'.$video_link.'</td>
									<td>
										<a href="javascript:void(0)" title= "Delete Video Link" onclick="deleteCourseMultiSubVideo('.$id.','.$COURSE_ID.')" class="btn btn-danger table-btn"><i class="mdi mdi-delete"></i></a>
									</td>
								  </tr>';
						
					}
					
				}				
			}			
		}
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;
	}
	
	//plan update function in courses
	public function get_course_plans_multi_sub($course_id='', $display=true)
	{
		$img = '';
		$data= array();
		$target='';
		
		$sql ="SELECT A.*, B.PLAN_NAME	 FROM multi_sub_course_plan_fees A inner join institute_plans B ON A.PLAN_ID=B.PLAN_ID   WHERE 1";
		if($course_id!='')
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id'";		
		$sql .= ' ORDER BY A.COURSE_PLAN_FEES_ID ASC';
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table table-responsive table-bordered">';
			while($rec = $res->fetch_assoc())
			{
				$COURSE_PLAN_FEES_ID 		= $rec['COURSE_PLAN_FEES_ID'];
				$PLAN_ID 					= $rec['PLAN_ID'];
				$COURSE_FEES 				= $rec['COURSE_FEES'];
				$MULTI_SUB_COURSE_ID 		= $rec['MULTI_SUB_COURSE_ID'];
				$PLAN_NAME 					= $rec['PLAN_NAME'];
				if($PLAN_ID!='')
				{
										
					$img .= '<tr id="file-area'.$COURSE_PLAN_FEES_ID.'">
								<td>'.$this->get_course_planname($PLAN_ID).'</td>
								<td>'.$COURSE_FEES.'</td>
								<td>
									<a href="javascript:void(0)" title= "Delete File" onclick="deleteCoursePlan('.$COURSE_PLAN_FEES_ID.','.$MULTI_SUB_COURSE_ID.')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
								</td>
							  </tr>';						
				}
				array_push($data, $rec);					
			}	
		}
		
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;
	}
	
	///get course multi subjects details
	public function get_course_multi_sub($course_id='', $display=true)
	{		
		$img = '';
		$data= array();
		$target='';	
		
		$sql ="SELECT A.* FROM multi_sub_courses_subjects A WHERE ACTIVE = '1' AND DELETE_FLAG = '0'";
		if($course_id!='')
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id'";		
		$sql .= ' ORDER BY A.COURSE_SUBJECT_ID ASC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table table-responsive table-bordered">';
			while($rec = $res->fetch_assoc())
			{
				$COURSE_SUBJECT_ID 		= $rec['COURSE_SUBJECT_ID'];
				$MULTI_SUB_COURSE_ID 				= $rec['MULTI_SUB_COURSE_ID'];
				$COURSE_SUBJECT_NAME 	= $rec['COURSE_SUBJECT_NAME'];				
				if($COURSE_SUBJECT_ID!='')
				{
										
					$img .= '<tr id="file-area'.$COURSE_SUBJECT_ID.'">
								<td>'.$COURSE_SUBJECT_NAME.'</td>								
								<td>
									<a href="javascript:void(0)" title= "Delete File" onclick="deleteCoursePlan('.$COURSE_SUBJECT_ID.','.$MULTI_SUB_COURSE_ID.')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
								</td>
							  </tr>';						
				}
				array_push($data, $rec);					
			}	
		}
		
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;	
	}
	public function get_course_multi_sub1($course_id='',$inst_id='', $display=true)
	{		
		$img = '';
		$data= array();
		$target='';	
		
		$sql ="SELECT A.*,get_multisub_subjectname(A.COURSE_SUBJECT_ID) AS COURSE_SUBJECT_NAME  FROM institute_course_subjects A WHERE ACTIVE = '1' AND DELETE_FLAG = '0'";
		if($course_id!='')
			$sql .= " AND A.MULTI_SUB_COURSE_ID='$course_id' AND A.INSTITUTE_ID = '$inst_id'";		
		$sql .= ' ORDER BY A.COURSE_SUBJECT_ID ASC';
		//echo $sql;
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table table-responsive table-bordered">';
			while($rec = $res->fetch_assoc())
			{
				$COURSE_SUBJECT_ID 		= $rec['COURSE_SUBJECT_ID'];
				$MULTI_SUB_COURSE_ID 	= $rec['MULTI_SUB_COURSE_ID'];
				$COURSE_SUBJECT_NAME 	= $rec['COURSE_SUBJECT_NAME'];				
				if($COURSE_SUBJECT_ID!='')
				{
										
					$img .= '<tr id="file-area'.$COURSE_SUBJECT_ID.'">
								<td>'.$COURSE_SUBJECT_NAME.'</td>								
								<td>
									<a href="javascript:void(0)" title= "Delete File" onclick="deleteCoursePlan('.$COURSE_SUBJECT_ID.','.$MULTI_SUB_COURSE_ID.')" class="delete-icon"><i class="fa fa-trash-o"></i></a>
								</td>
							  </tr>';						
				}
				array_push($data, $rec);					
			}	
		}
		
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;	
	}
	/* validate institute code */
	public function validate_course_code($code,$course_id='')
	{
		$sql = "SELECT MULTI_SUB_COURSE_CODE  FROM multi_sub_courses WHERE MULTI_SUB_COURSE_CODE ='$code' AND DELETE_FLAG=0";
		if($course_id!='')
		$sql .= " AND MULTI_SUB_COURSE_ID!='$course_id'";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			return false;
		}
		return true;
	}
	/* delete course file */
	public function delete_course_multi_sub_file($file_id, $course_id='')
	{
		$sql = "DELETE FROM multi_sub_courses_files	 WHERE FILE_ID='$file_id'";
		if($course_id!='')
		 $sql .= " AND MULTI_SUB_COURSE_ID='$course_id'";
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	
	public function delete_course_multi_sub_video($file_id, $course_id='')
	{
		$sql = "DELETE FROM multi_sub_course_videos	 WHERE id='$file_id'";
		if($course_id!='')
		 $sql .= " AND course_id='$course_id'";
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	
	/* delete course file */
	public function delete_course($course_id)
	{
		
		$sql = "DELETE FROM multi_sub_courses WHERE MULTI_SUB_COURSE_ID ='$course_id'";
		$sql2 = "DELETE FROM multi_sub_courses_files WHERE MULTI_SUB_COURSE_ID='$course_id'";
		
		$res2 = parent::execQuery($sql2);
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	
	/* change course status */
	public function changeStatusFlagMultiSub($course_id, $flag)
	{
		echo $sql = "UPDATE multi_sub_courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW()WHERE MULTI_SUB_COURSE_ID='$course_id'";		
		$res= parent::execQuery($sql);
		
		if($res)
		{
			echo $sql1 = "UPDATE institute_courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE MULTI_SUB_COURSE_ID='$course_id'";
			$res= parent::execQuery($sql1);

			return true;
		}
		return false;
	}
	
	/* bulk edit */
	public function getBulkInfo($arr)
	{
		$html = '';
		if(is_array($arr))
		{
			$string = '';
			
			foreach($arr as $value)
				$string .= "'$value'".',';
			$string = rtrim($string,',');
			$sql = "SELECT COURSE_CODE,COURSE_ID FROM courses WHERE COURSE_ID IN ($string) ";
			$res = parent::execQuery($sql);
			if($res && $res->num_rows>0)
			{
				while($data  = $res->fetch_assoc())
				{
					$COURSE_ID = $data['COURSE_ID'];
					$COURSE_CODE = $data['COURSE_CODE'];
					$html .= "<label><input type='checkbox' value='$COURSE_ID' name='course_id[]' class='minimal' checked='checked'></label><span class='label label-primary'>$COURSE_CODE </span>  &nbsp;&nbsp;&nbsp;";
				}
			}
		}
		return $html;
	}
	public function bulkDeleteCourses($courseArr)
	{
		if (is_array($courseArr)) {
			$str = '';
			foreach($courseArr as $value)
				$str .= "'$value',";
			$str = rtrim($str,",");

			$sql = "UPDATE courses SET DELETE_FLAG=1, ACTIVE=0 WHERE COURSE_ID IN($str)";
			$res = parent::execQuery($sql);
			if($res && parent::rows_affected()>0)
			{
				return true;
			}
		}
		return false;
	}
	/* bulk edit */
	public function updateBulkInfo($courseIdArr, $fees)
	{
		if(is_array($courseIdArr))
		{
			$string = '';
			
			foreach($courseIdArr as $value)
				$string .= "'$value'".',';
			$string = rtrim($string,',');
			
			$sql = "UPDATE courses SET COURSE_FEES='$fees',UPDATED_BY='".$_SESSION['user_fullname']."',UPDATED_ON_IP='".$_SESSION['ip_address']."'  WHERE COURSE_ID IN ($string) ";
			$res = parent::execQuery($sql);
			if($res && parent::rows_affected()>0)
			{
				return true;
			}
		}
		return false;
	}
	
	/* institute functions */
	public function list_all_courses($inst_id)
	{
		$data = '';	
		$sql = "SELECT A.* FROM institute_courses A WHERE A.INSTITUTE_ID='$inst_id' AND A.DELETE_FLAG=0";
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}
	//list acicpe courses already in institute
	//list DITRP course to add which are not added in institute
	public function list_added_courses_multi_sub($inst_id)
	{
		$data = '';
		$sql= "SELECT A.*,A.MULTI_SUB_COURSE_FEES as EXAM_FEES,get_course_award_name(A.MULTI_SUB_COURSE_AWARD) AS COURSE_AWARD_NAME,get_course_multi_sub_title_modify (A.MULTI_SUB_COURSE_ID) COURSE_NAME_MODIFY, B.INSTITUTE_COURSE_ID, B.ACTIVE AS STATUS, B.COURSE_FEES AS INSTITUTE_COURSE_FEES,B.PLAN_ID, B.PLAN_FEES, B.MINIMUM_FEES FROM  multi_sub_courses A LEFT JOIN institute_courses B ON A.MULTI_SUB_COURSE_ID=B.MULTI_SUB_COURSE_ID  WHERE B.DELETE_FLAG=0  AND B.COURSE_TYPE=1 AND B.INSTITUTE_ID='$inst_id' ";
		//echo $sql;		
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}
	public function list_added_courses_single_multi_sub($inst_course_id)
	{
		$data = '';
		$sql= "SELECT A.*,A.MULTI_SUB_COURSE_FEES as EXAM_FEES,get_course_award_name(A.MULTI_SUB_COURSE_AWARD) AS COURSE_AWARD_NAME, B.INSTITUTE_COURSE_ID, B.ACTIVE AS STATUS, B.COURSE_FEES AS INSTITUTE_COURSE_FEES, B.PLAN_FEES ,  B.MINIMUM_FEES FROM  multi_sub_courses A LEFT JOIN institute_courses B ON A.MULTI_SUB_COURSE_ID=B.MULTI_SUB_COURSE_ID  WHERE B.DELETE_FLAG=0  AND B.COURSE_TYPE=1 AND B.INSTITUTE_COURSE_ID='$inst_course_id'";	
		//echo $sql; 	
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}
	public function list_notadded_courses_multi_sub($inst_id)
	{
		$data = '';
		$search ='';
		$where = '';
		$sql ="Select PLAN_ID FROM  institute_details WHERE INSTITUTE_ID=$inst_id";
			$res = parent:: execQuery($sql);
			$resdata = $res->fetch_assoc();
			$plan_id = $resdata['PLAN_ID'];
		
	//	echo $sql= "SELECT A.COURSE_ID FROM  courses A LEFT JOIN institute_courses B ON A.COURSE_ID=B.COURSE_ID  WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1 AND B.COURSE_TYPE=1 AND A.DELETE_FLAG=0";	
		$sql = "SELECT A.MULTI_SUB_COURSE_ID FROM institute_courses A WHERE A.COURSE_TYPE=1 AND A.INSTITUTE_ID='$inst_id' AND A.DELETE_FLAG=0";		
		$res = parent:: execQuery($sql);
		//$res = $this->list_inst_aicpe_courses($inst_id);
		if($res && $res->num_rows>0)
		{
			
			while($resdata = $res->fetch_assoc())
			{
				$MULTI_SUB_COURSE_ID = $resdata['MULTI_SUB_COURSE_ID'];
				$search .= "'$MULTI_SUB_COURSE_ID',";
			}
			$search = rtrim($search,",");
		}
		$where= '';
		if($search!='')
		{
			$where = " AND A.MULTI_SUB_COURSE_ID NOT IN($search) ";
		}
		 $sql2 = "SELECT A.*,get_course_multi_sub_title_modify(A.MULTI_SUB_COURSE_ID) AS COURSE_NAME_MODIFY,B.COURSE_FEES as PLAN_FEES,B.PLAN_ID FROM  multi_sub_courses A LEFT JOIN multi_sub_course_plan_fees B ON A.MULTI_SUB_COURSE_ID = B.MULTI_SUB_COURSE_ID WHERE  B.PLAN_ID='$plan_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 $where";
		//echo $sql2;
		$ex2 =parent::execQuery($sql2);
		if($ex2 && $ex2->num_rows>0)
		{
			$data = $ex2;
		}
		return $data;
	}
	
	//check course is added to institute or not
	public function check_course_in_institute($course_id,$inst_id,$course_type)
	{
		$sql = "SELECT * FROM institute_courses WHERE MULTI_SUB_COURSE_ID='$course_id' AND INSTITUTE_ID='$inst_id' AND COURSE_TYPE='$course_type' AND DELETE_FLAG=0";
		$result = parent::execQuery($sql);
		if($result && $result->num_rows>0)
			return true;
		return false;
	}
	// add DITRP course into institute
	public function institute_add_aicpe_course_multi_sub()
	{
		$res = '';
		$errors = array();  // array to hold validation errors
		$data 	= array();        // array to pass back data 

		$course_id 	= isset($_POST['course_id'])?$_POST['course_id']:'';		
		$course_subject_id 	= isset($_POST['course_subject_id'])?$_POST['course_subject_id']:'';		
		//print_r($course_subject_id); exit();
		$user_id = $_SESSION['user_id'];
		 $user_role =$_SESSION['user_role'];
		if($user_role==3){
		   $institute_id = parent::get_parent_id($user_role,$user_id);
		   $staff_id = $user_id;
		}
		else{
		   $institute_id = $user_id;
		   $staff_id = 0;
		}		
		 /* check validations */
		if ($course_subject_id=='' || empty($course_subject_id))
			$errors['course'] = 'Please check atleast one course subject!'; 
	
		if (!empty($errors)) {
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  if(isset($errors['course']))
				$data['message']  = 'Please check atleast one  course subject.';
			else
				$data['message']  = 'Please correct all the errors.';
			} else {
				
				foreach($course_subject_id as $value)
				{
					$subjectdetails 	= isset($_POST['subjectdetails'.$value])?$_POST['subjectdetails'.$value]:'';		
					$position 	= isset($_POST['position'.$value])?$_POST['position'.$value]:'';		


					$tableName = "institute_course_subjects";
					$tabFields = "(INSTITUTE_SUBJECT_ID,INSTITUTE_ID,MULTI_SUB_COURSE_ID,COURSE_SUBJECT_ID,SUBJECT_DETAILS,POSITION,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
					$insertValues = "(NULL, '$institute_id','$course_id','$value','$subjectdetails','$position',1,0,'".$_SESSION['user_name']."', NOW())";
					$sql = parent::insertData($tableName,$tabFields,$insertValues);
					$exec = parent::execQuery($sql);					

				}
				if($exec)
				{
					$data['success'] = true;
					$data['message'] = 'Success! Course Subject list has been added successfully!';
				}else{
					$data['success'] = false;
					$data['message'] = 'Sorry! Something went wrong!';
				}
			}
		return json_encode($data);
	}
	
	// delete institute course
	public function delete_institute_courseMultisub($inst_course_id)
	{
		$res = "";
		//check the course type
		$course_type='';
		$sql ="SELECT COURSE_TYPE,MULTI_SUB_COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res->fetch_assoc();
			$course_type = $data['COURSE_TYPE'];
			$MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
			if($course_type==2){
				$updsql = "UPDATE non_courses SET DELETE_FLAG=1, ACTIVE=0, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE MULTI_SUB_COURSE_ID='$MULTI_SUB_COURSE_ID'";
				$delres = parent::execQuery($updsql);
			}
		}
		$sql = "UPDATE institute_courses SET DELETE_FLAG=1, ACTIVE=0, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = parent::execQuery($sql);
		
		$sql11 = "UPDATE institute_course_subjects SET DELETE_FLAG=1, ACTIVE=0, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE MULTI_SUB_COURSE_ID='$MULTI_SUB_COURSE_ID' AND INSTITUTE_ID = '".$_SESSION['user_id']."'";
		$res11 = parent::execQuery($sql);
		
		
		if($res && parent::rows_affected()>0)
			return true;
		return false;
	}
	public function bulk_delete_inst_course_multi_sub($instCourseArr, $inst_id)
	{
		if (is_array($instCourseArr)) {
			$str = '';
			foreach($instCourseArr as $value)
				$str .= "'$value',";
			$str = rtrim($str,",");
			 $sql = "UPDATE institute_courses SET DELETE_FLAG=1, ACTIVE=0 , UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE INSTITUTE_ID= '$inst_id' AND INSTITUTE_COURSE_ID IN($str)";
			//echo $sql; exit();			
			$res = parent::execQuery($sql);
			if($res && parent::rows_affected()>0)
			{
				return true;
			}
		}
		return false;
	}
	/* change institute course status */
	public function change_inst_course_status($inst_course_id, $flag)
	{
		$sql ="SELECT COURSE_TYPE,COURSE_ID FROM institute_courses WHERE INSTITUTE_COURSE_ID='$inst_course_id'";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res->fetch_assoc();
			$course_type = $data['COURSE_TYPE'];
			$COURSE_ID = $data['COURSE_ID'];
			if($course_type==2){
				$updsql = "UPDATE non_courses SET ACTIVE='$flag',  UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE COURSE_ID='$COURSE_ID'";
				$delres = parent::execQuery($updsql);
			}
		}
		$sql = "UPDATE institute_courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(),UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE INSTITUTE_COURSE_ID='$inst_course_id'";		
		$res= parent::execQuery($sql);
		
		if($res)
		{
			return true;
		}
		return false;
	}
	/* change institute course status */
	public function change_inst_course_fees($inst_course_id, $fees)
	{
		$sql = "UPDATE institute_courses SET COURSE_FEES='$fees',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(),UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE INSTITUTE_COURSE_ID='$inst_course_id'";		
		$res= parent::execQuery($sql);
		
		if($res)
		{
			return true;
		}
		return false;
	}
	//institute add non DITRP course
	public function institute_add_nonaicpe_course()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $coursecode 		= parent::test(isset($_POST['coursecode'])?$_POST['coursecode']:'');
		  $award 			= parent::test(isset($_POST['award'])?$_POST['award']:'');
		  $courseauth 		= parent::test(isset($_POST['courseauth'])?$_POST['courseauth']:'');
		  $coursename 		= parent::test(isset($_POST['coursename'])?$_POST['coursename']:'');
		  $duration 		= parent::test(isset($_POST['duration'])?$_POST['duration']:'');
		  $detail 			= parent::test(isset($_POST['detail'])?$_POST['detail']:'');
		  $eligibility 		= parent::test(isset($_POST['eligibility'])?$_POST['eligibility']:'');
		  $examfees 			= parent::test(isset($_POST['examfees'])?$_POST['examfees']:'');
		  $coursefees 			= parent::test(isset($_POST['coursefees'])?$_POST['coursefees']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  /* Files */
		  $filecount 		= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $created_by  		= $_SESSION['user_fullname'];
		  $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
			if($user_role==3){
			   $institute_id = $db->get_parent_id($user_role,$admin_id);
			   $staff_id = $admin_id;
			}
			else{
			   $institute_id = $admin_id;
			   $staff_id = 0;
			}
		 /* check validations */
		 // if ($coursecode=='')
		//	$errors['coursecode'] = 'Course code is required!'; 
		  if ($award=='')
			$errors['award'] = 'Course award is required!';
		  if ($coursename=='')
			$errors['coursename'] = 'Course name is required!';
		  if ($duration=='')
			$errors['duration'] = 'Course duration is required!';
		  if ($detail=='')
			$errors['detail'] = 'Course details is required!';
		  if ($eligibility=='')
			$errors['eligibility'] = 'Course eligibility is required!';
		  if ($examfees=='')
			$errors['examfees'] = 'Exam fees is required!';
		  if ($examfees!='' && !is_numeric($examfees))
			$errors['examfees'] = 'Invalid exam fees entered!';	
		 if ($coursefees!='' && !is_numeric($coursefees))
			$errors['coursefees'] = 'Invalid course fees entered!';
		//  if(!$this->validate_course_code($coursecode))
		//	$errors['coursecode'] = 'Sorry! Course code is already present.';
		
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
					parent::start_transaction();
					$tableName 	= "non_courses";
					$tabFields 	= "(COURSE_ID, INSTITUTE_ID,COURSE_AWARD,COURSE_AUTHORITY, COURSE_DURATION, COURSE_NAME,COURSE_DETAILS,COURSE_ELIGIBILITY,EXAM_FEES,COURSE_FEES, ACTIVE,CREATED_BY, CREATED_ON,CREATED_ON_IP)";
					$insertVals	= "(NULL, '".$institute_id."',UPPER('$award'),UPPER('$courseauth'), UPPER('$duration'), UPPER('$coursename'),'$detail','$eligibility','$examfees','$coursefees','$status','$created_by',NOW(), '".$_SESSION['ip_address']."')";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	
						$last_insert_id = parent::last_id();
						//instiute course details 
						$tableName4 	= "institute_courses";
						$tabFields4 	= "(INSTITUTE_COURSE_ID, INSTITUTE_ID, COURSE_ID,COURSE_TYPE, EXAM_FEES,COURSE_FEES,CREATED_BY,CREATED_ON,CREATED_ON_IP)";
						$insertVals4	= "(NULL,'$institute_id','$last_insert_id','2','$examfees','$coursefees','$created_by',NOW(), '$created_by_ip')";
						$insertSql4		= parent::insertData($tableName4,$tabFields4,$insertVals4);
						$exSql4			= parent::execQuery($insertSql4);
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New course has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the course.';
						$data['success'] = false;
						$data['errors']  = $errors;
					}			 
			}
		return json_encode($data);			
	}
	public function list_nonaicpe_courses($course_id='', $inst_id='')
	{
		$data = '';
		$sql= "SELECT A.*,B.INSTITUTE_COURSE_ID FROM  non_courses A LEFT JOIN institute_courses B ON A.COURSE_ID=B.COURSE_ID WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0 AND B.COURSE_TYPE=2 ";	
		if($inst_id!='')
			$sql .= " AND A.INSTITUTE_ID='$inst_id'";
		if($course_id!='')	
		$sql .= " AND A.COURSE_ID='$course_id'";
		
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}
	
	public function institute_update_nonaicpe_course($course_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		 $course_id 		= parent::test(isset($_POST['course_id'])?$_POST['course_id']:'');
		$award 		= parent::test(isset($_POST['award'])?$_POST['award']:'');		 
		$courseauth 		= parent::test(isset($_POST['courseauth'])?$_POST['courseauth']:'');
		 
		 $inst_id 		= parent::test(isset($_POST['inst_id'])?$_POST['inst_id']:'');
		  $coursename 		= parent::test(isset($_POST['coursename'])?$_POST['coursename']:'');
		  $duration 		= parent::test(isset($_POST['duration'])?$_POST['duration']:'');
		  $detail 			= parent::test(isset($_POST['detail'])?$_POST['detail']:'');
		  $eligibility 		= parent::test(isset($_POST['eligibility'])?$_POST['eligibility']:'');
		  $fees 			= parent::test(isset($_POST['fees'])?$_POST['fees']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		   $examfees 			= parent::test(isset($_POST['examfees'])?$_POST['examfees']:'');
		  $coursefees 			= parent::test(isset($_POST['coursefees'])?$_POST['coursefees']:'');
		  /* Files */
		  $filecount 		= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');	 
		
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		//  if ($coursecode=='')
		//	$errors['coursecode'] = 'Course code is required!'; 
		if ($award=='')
			$errors['award'] = 'Course award is required!';
		if ($courseauth=='')
			$errors['courseauth'] = 'Course authorisation is required!';
		  if ($coursename=='')
			$errors['coursename'] = 'Course name is required!';
		  if ($duration=='')
			$errors['duration'] = 'Course duration is required!';
		  if ($detail=='')
			$errors['detail'] = 'Course details is required!';
		  if ($eligibility=='')
			$errors['eligibility'] = 'Course eligibility is required!';
		  if ($examfees=='')
			$errors['examfees'] = 'Exam fees is required!';
		  if ($examfees!='' && !is_numeric($examfees))
			$errors['examfees'] = 'Invalid exam fees entered!';	
		 if ($coursefees!='' && !is_numeric($coursefees))
			$errors['coursefees'] = 'Invalid course fees entered!';	
		 // if(!$this->validate_course_code($coursecode, $course_id))
		//	$errors['coursecode'] = 'Sorry! Course code is already present.';
		
	
		  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "non_courses";
					$setValues 	= "INSTITUTE_ID='$inst_id', COURSE_AWARD=UPPER('$award'),COURSE_AUTHORITY=UPPER('$courseauth'), COURSE_DURATION=UPPER('$duration'), COURSE_NAME=UPPER('$coursename'),COURSE_DETAILS='$detail',COURSE_ELIGIBILITY='$eligibility', COURSE_FEES='$coursefees',EXAM_FEES='$examfees',COURSE_FEES='$coursefees', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE COURSE_ID='$course_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);					
						
					$tableName 	= "institute_courses";
					$setValues 	= "EXAM_FEES='$examfees',COURSE_FEES='$coursefees', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE INSTITUTE_ID='$inst_id' AND COURSE_ID='$course_id' AND COURSE_TYPE='2'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);			
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Course has been updated successfully!';
			}
		return json_encode($data);			
	}
	
	/* change non DITRP course status */
	public function change_nonaicpe_course_status($inst_course_id, $flag)
	{
		$sql = "SELECT COURSE_ID,COURSE_TYPE FROM institute_courses INSTITUTE_COURSE_ID='$inst_course_id' AND DELETE_FLAG=0";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
				$data = $res->fetch_assoc();
				$COURSE_ID 	= $data['COURSE_ID'];
				$COURSE_TYPE = $data['COURSE_TYPE'];
				if($COURSE_TYPE==2)
				{
					$sql = "UPDATE non_courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(),UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE COURSE_ID='$COURSE_ID'";		
					$res= parent::execQuery($sql);	
				}
		}
		$sql = "UPDATE institute_courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(),UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE INSTITUTE_COURSE_ID='$inst_course_id'";		
		$res= parent::execQuery($sql);
		
		if($res)
		{
			return true;
		}
		return false;
	}
	/* delete non DITRP course */
	public function delete_nonaicpe_course($course_id)
	{
		$sql = "UPDATE non_courses SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE COURSE_ID='$course_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	// bulk delete nonaicpe courses
	public function bulk_delete_non_courses($instCourseArr)
	{
		if (is_array($instCourseArr)) {
			$str = '';
			foreach($instCourseArr as $value)
				$str .= "'$value',";
			$str = rtrim($str,",");
		 $sql = "UPDATE non_courses SET DELETE_FLAG=1, ACTIVE=0 , UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(), UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE COURSE_ID IN($str)";			
			$res = parent::execQuery($sql);
			if($res && parent::rows_affected()>0)
			{
				return true;
			}
		}
		return false;
	}
	//display course info
	public function display_course_info($course_id,$stud_id)
	{
		$result = '';
		$resultArr = array();
		$res = $this->list_courses($course_id,'');
		if($res!=='')
		{
			while($data = $res->fetch_assoc())
			{
				$COURSE_ID 		= $data['COURSE_ID'];
				$COURSE_CODE 	= $data['COURSE_CODE'];
				$COURSE_AWARD 	= $data['COURSE_AWARD'];
				$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
				$COURSE_DURATION= $data['COURSE_DURATION'];
				$COURSE_NAME 	= $data['COURSE_NAME'];
				$COURSE_FEES 	= $data['COURSE_FEES'];
				
				$resultArr['courseId'] = $COURSE_ID;
				$resultArr['examFees'] = $COURSE_FEES;
				$resultArr['courseName'] = $COURSE_NAME;
				$resultArr['courseDuration'] = $COURSE_DURATION;
				
				$result .= ' <table class="table">					
					<tr>
						<th>Course Name</th>
						<td>:</td>
						<td>'.$COURSE_NAME.'</td>
					</tr>
					<!-- <tr>
						<th>Course Award</th>
						<td>:</td>
						<td>'.$COURSE_AWARD_NAME.'</td>
					</tr> -->
					<tr>
						<th>Duration</th>
						<td>:</td>
						<td>'.$COURSE_DURATION.'</td>
					</tr>
					<tr>
						<th>Exam Fees</th>
						<td>:</td>
						<td>'.$COURSE_FEES.'</td>
					</tr>
					
				 </table>';
			}
		}
		return $resultArr;
	}
	public function get_inst_course_detail($inst_id='',$inst_course_id='',$sel_course='',$disp_option=false)
	{
		$course_info = array();
		$option = '';
	
		 $sql ="SELECT DISTINCT A.INSTITUTE_COURSE_ID,A.COURSE_FEES AS INST_COURSE_FEE,A.COURSE_TYPE,A.INSTITUTE_ID, A.COURSE_ID FROM institute_courses A WHERE A.INSTITUTE_ID='$inst_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
		 if($inst_course_id!='')
			 $sql .= " AND A.INSTITUTE_COURSE_ID=$inst_course_id";
		  $res = parent::execQuery($sql);
		  if($res && $res->num_rows>0)
		  {
			  $option = '<option value="">--select course--</option>';
			  while($data = $res->fetch_assoc())
			  {
				  extract($data);				 
				  $course = parent::get_course_detail($COURSE_ID,$COURSE_TYPE);
				  
				  if($sel_course==$INSTITUTE_COURSE_ID)
				  $option .= '<option value="'.$INSTITUTE_COURSE_ID.'" selected="selected">'.$course['COURSE_NAME'].'</option>';
				  else
				  $option .= '<option value="'.$INSTITUTE_COURSE_ID.'">'.$course['COURSE_NAME'].'</option>';
			  
				  $course_info['inst_course_id'] = $INSTITUTE_COURSE_ID;
				  $course_info['course_name'] = $course['COURSE_NAME'];
				  $course_info['course_type'] = $COURSE_TYPE;
				  $course_info['inst_course_fee'] = $INST_COURSE_FEE;
				  $course_info['exam_fees'] = $course['COURSE_FEES'];
			}
		  }		
		  return ($disp_option)?$option:$course_info;
	}

	//add course subject code for multiple subjects
	public function list_added_courses_subject_multi_sub($inst_course_id)
	{
		$data = '';
		$sql= "SELECT A.* FROM  multi_sub_courses_subjects A WHERE A.DELETE_FLAG=0  AND A.MULTI_SUB_COURSE_ID='$inst_course_id'";		
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}

	//course subject list added by institute in there course
	public function get_course_subject_added_by_institute($inst_course_id,$inst_id)
	{
		$data = '';
		$sql= "SELECT A.INSTITUTE_SUBJECT_ID,A.MULTI_SUB_COURSE_ID,A.COURSE_SUBJECT_ID as SUBJECT_ID ,get_subject_title_multi_sub(A.COURSE_SUBJECT_ID) as SUBJECT_NAME ,A.SUBJECT_DETAILS,A.POSITION, B.* FROM  institute_course_subjects A RIGHT JOIN multi_sub_course_exam_structure B ON A.COURSE_SUBJECT_ID = B.COURSE_SUBJECT_ID WHERE A.DELETE_FLAG=0 AND B.DELETE_FLAG=0 AND A.INSTITUTE_ID='$inst_id'  AND A.MULTI_SUB_COURSE_ID='$inst_course_id'";	
		//echo $sql; 
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$data = $res;
		}
		return $data;
	}
	//institute-> remove subject from course	
	public function delete_institute_course_sub($inst_course_sub_id)
	{
		$sql = "UPDATE institute_course_subjects SET DELETE_FLAG=1, UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE INSTITUTE_SUBJECT_ID='$inst_course_sub_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	//institute -> add course multi sub into institute	
	public function institute_add_aicpe_coursemulti()
	{
		$res = '';
		$errors = array();  // array to hold validation errors
		$data 	= array();        // array to pass back data 

		$course 	= isset($_POST['course'])?$_POST['course']:'';		
		$institute 	= isset($_POST['institute'])?$_POST['institute']:'';		
		$course_type= isset($_POST['course_type'])?$_POST['course_type']:'';
		
		$user_id = $_SESSION['user_id'];
		 $user_role =$_SESSION['user_role'];
		if($user_role==3){
		   $institute_id = parent::get_parent_id($user_role,$user_id);
		   $staff_id = $user_id;
		}
		else{
		   $institute_id = $user_id;
		   $staff_id = 0;
		}		
		 /* check validations */
		if ($course=='' || empty($course))
			$errors['course'] = 'Please check atleast one course !'; 
		else if(is_array($course)){
			foreach($course as $value)
			{
				$coursefees 	= isset($_POST['coursefees_'.$value])?$_POST['coursefees_'.$value]:'';		
				if($coursefees=='')
				{
					$errors['coursefees_'.$value] = 'Required!';
				}
				if($coursefees!='' && !parent::valid_decimal($coursefees))
				{
					$errors['coursefees_'.$value] = 'Invalid amount!';
				}
			}
		}
		
		if (!empty($errors)) {
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  if(isset($errors['course']))
				$data['message']  = 'Please check atleast one course.';
			else
				$data['message']  = 'Please correct all the errors.';
			} else {
				
				foreach($course as $value)
				{
					$coursefees 	= isset($_POST['coursefees_'.$value])?$_POST['coursefees_'.$value]:0;	
					$coursefeesminimum 	= isset($_POST['coursefeesminimum_'.$value])?$_POST['coursefeesminimum_'.$value]:0;

					$sql ="SELECT institute_details.PLAN_ID, multi_sub_course_plan_fees.COURSE_FEES AS PLAN_FEES FROM institute_details inner join multi_sub_course_plan_fees on institute_details.PLAN_ID=multi_sub_course_plan_fees.PLAN_ID WHERE institute_details.INSTITUTE_ID=$institute_id AND multi_sub_course_plan_fees.MULTI_SUB_COURSE_ID= $value LIMIT 0,1";
					
					$res = parent::execQuery($sql);
					if($res && $res->num_rows>0)
					{
						$data = $res->fetch_assoc();
						$PLAN_ID = $data['PLAN_ID'];
						$PLAN_FEES = $data['PLAN_FEES'];
						if(!$this->check_course_in_institute($value,$institute_id,$course_type))
						{
							$tableName = "institute_courses";
							$tabFields = "(INSTITUTE_COURSE_ID,INSTITUTE_ID,MULTI_SUB_COURSE_ID,COURSE_TYPE,PLAN_ID,PLAN_FEES,EXAM_FEES,COURSE_FEES,CREATED_BY,CREATED_ON,CREATED_ON_IP,MINIMUM_FEES)";
							$insertValues = "(NULL, '$institute_id','$value','$course_type','$PLAN_ID','$PLAN_FEES','$PLAN_FEES','$coursefees','".$_SESSION['user_name']."', NOW(), '".$_SESSION['ip_address']."','$coursefeesminimum')";
							$sql = parent::insertData($tableName,$tabFields,$insertValues);
							$exec = parent::execQuery($sql);
						}
					}
					
				}
				if($exec)
				{
					$data['success'] = true;
					$data['message'] = 'Success! Course list has been added successfully!';
				}else{
					$data['success'] = false;
					$data['message'] = 'Sorry! Something went wrong!';
				}
			}
		return json_encode($data);
	} 
	public function delete_multi_subject($subjectid,$courseid)
	{
		
		$sql = "UPDATE multi_sub_courses_subjects SET ACTIVE='0',DELETE_FLAG='0',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW() WHERE COURSE_SUBJECT_ID='$subjectid' AND MULTI_SUB_COURSE_ID='$courseid'";
		$res= parent::execQuery($sql);

		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
}
?>