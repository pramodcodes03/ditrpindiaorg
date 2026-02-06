<?php
include_once('database_results.class.php');
include_once('access.class.php');

class seminar extends access
{  
	public function add_seminar()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data
		  
		  //print_r($_POST); exit();
		  $topic_name 		= parent::test(isset($_POST['topic_name'])?$_POST['topic_name']:'');
		  $seminar_type 	= parent::test(isset($_POST['seminar_type'])?$_POST['seminar_type']:'');
		  $mode 		    = parent::test(isset($_POST['mode'])?$_POST['mode']:'');
		  $approval_no 		= parent::test(isset($_POST['approval_no'])?$_POST['approval_no']:'');
		  $college_name 	= parent::test(isset($_POST['college_name'])?$_POST['college_name']:'');
		  $fee_date 		= parent::test(isset($_POST['fee_date'])?$_POST['fee_date']:'');
		  $address 		    = parent::test(isset($_POST['address'])?$_POST['address']:'');
		  $place 			= parent::test(isset($_POST['place'])?$_POST['place']:'');
		  $date 			= parent::test(isset($_POST['date'])?$_POST['date']:'');
          $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $conductor_name 	= parent::test(isset($_POST['conductor_name'])?$_POST['conductor_name']:'');
		
		 /* Files */
		 $sign 			= isset($_FILES['sign']['name'])?$_FILES['sign']['name']:'';
		 $stamp 		= isset($_FILES['stamp']['name'])?$_FILES['stamp']['name']:'';		
	
		  $created_by  		= $_SESSION['user_fullname'];
		 /* check validations */
		  if ($topic_name=='')
			$errors['topic_name'] = 'Topic Name is required.';
		  if ($seminar_type=='')
			$errors['seminar_type'] = 'Seminar Type is required.';
		  if ($mode=='')
			$errors['mode'] = 'Seminar Mode is required.';
		  if ($approval_no=='')
			$errors['approval_no'] = 'Approval Number is required.';
		  if ($college_name=='')
			$errors['college_name'] = 'College Name is required.';
		  if ($fee_date=='')
			$errors['fee_date'] = 'Fees Date is required.';
		  if ($address=='')
			$errors['address'] = 'Address is required.';
		  if ($place=='')
			$errors['place'] = 'Place is required.';
		  if($date=='')
		     $errors['date'] = 'Date is required.';
		  if($conductor_name=='')
		     $errors['conductor_name'] = 'Director Name is required.';
		  
		  if($sign!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($sign, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['sign'] = 'Invalid file format! Please select valid file.';
				}
		  }
		  if($stamp!='')
		  {
				$allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
				$extension = pathinfo($stamp, PATHINFO_EXTENSION);
				if(!in_array($extension, $allowed_ext))
				{					
					$errors['stamp'] = 'Invalid file format! Please select valid file.';
				}
		  }
		 
		 //$errors=array();
           if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				if($dob!='') 
				$dob = @date('Y-m-d', strtotime($dob));
					parent::start_transaction();
					
					$tableName 	= "seminar";
					$tabFields 	= "(id, topic_name, seminar_type, mode,approval_no,college_name,fee_date,address,place,date,active,delete_flag,created_by,created_on,conductor_name)";
					$insertVals	= "(NULL, '$topic_name', '$seminar_type', '$mode','$approval_no','$college_name','$fee_date','$address','$place','$date','$status','0','$created_by',NOW(),'$conductor_name')";

					 $insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{
						    $last_insert_id 	= parent::last_id();
							$courseImgPathDir 		= 	SEMINAR_DOCUMENTS_PATH.'/'.$last_insert_id.'/';
							$tableName3 			= "seminar";

							if($sign!='')
							{								
								$ext 			= pathinfo($_FILES["sign"]["name"], PATHINFO_EXTENSION);
								$file_name 		= 'sign_'.mt_rand(0,123456789).'.'.$ext;
                                $setValues 		= "sign='$file_name'";
                                $whereClause	= " WHERE id='$last_insert_id'";
                                $updateSql		= parent::updateData($tableName3,$setValues,$whereClause);	
                                $exSql2		    = parent::execQuery($updateSql);								
								
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);
								//@mkdir($courseImgThumbPathDir,0777,true);								
								parent::create_thumb_img($_FILES["sign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
								//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
							}
							if($stamp!='')
							{								
								$ext 			= pathinfo($_FILES["stamp"]["name"], PATHINFO_EXTENSION);
								$file_name 		= 'stamp_'.mt_rand(0,123456789).'.'.$ext;	
                                $setValues 		= "stamp='$file_name'";
                                $whereClause	= " WHERE id = '$last_insert_id'";
                                $updateSql		= parent::updateData($tableName3,$setValues,$whereClause);	
                                $exSql3		    = parent::execQuery($updateSql);		
								
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
								$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);
								//@mkdir($courseImgThumbPathDir,0777,true);								
								parent::create_thumb_img($_FILES["stamp"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
								//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
							}
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Seminar has been added successfully!';
						
						}else{
							parent::rollback();
							$data['message'] = 'Sorry! Something went wrong! Could not add the seminar.';
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
	public function update_seminar($inst_id)
	{
	     //print_r($_POST); exit();
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $id               = parent::test(isset($_POST['id'])?$_POST['id']:'');
          $topic_name 		= parent::test(isset($_POST['topic_name'])?$_POST['topic_name']:'');
		  $seminar_type 	= parent::test(isset($_POST['seminar_type'])?$_POST['seminar_type']:'');
		  $mode 		    = parent::test(isset($_POST['mode'])?$_POST['mode']:'');
		  $approval_no 		= parent::test(isset($_POST['approval_no'])?$_POST['approval_no']:'');
		  $college_name 	= parent::test(isset($_POST['college_name'])?$_POST['college_name']:'');
		  $fee_date 		= parent::test(isset($_POST['fee_date'])?$_POST['fee_date']:'');
		  $address 		    = parent::test(isset($_POST['address'])?$_POST['address']:'');
		  $place 			= parent::test(isset($_POST['place'])?$_POST['place']:'');
		  $date 			= parent::test(isset($_POST['date'])?$_POST['date']:'');
          $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $conductor_name 	= parent::test(isset($_POST['conductor_name'])?$_POST['conductor_name']:'');
		
		 /* Files */
		 $sign 			= isset($_FILES['sign']['name'])?$_FILES['sign']['name']:'';
		 $stamp 		= isset($_FILES['stamp']['name'])?$_FILES['stamp']['name']:'';	
		
		 $updated_by  	= $_SESSION['user_fullname'];

         if ($topic_name=='')
         $errors['topic_name'] = 'Topic Name is required.';
        if ($seminar_type=='')
            $errors['seminar_type'] = 'Seminar Type is required.';
        if ($mode=='')
            $errors['mode'] = 'Seminar Mode is required.';
        if ($approval_no=='')
            $errors['approval_no'] = 'Approval Number is required.';
        if ($college_name=='')
            $errors['college_name'] = 'College Name is required.';
        if ($fee_date=='')
            $errors['fee_date'] = 'Fees Date is required.';
        if ($address=='')
            $errors['address'] = 'Address is required.';
        if ($place=='')
            $errors['place'] = 'Place is required.';
        if($date=='')
            $errors['date'] = 'Date is required.';
		if($conductor_name=='')
			$errors['conductor_name'] = 'Director Name is required.';
        
        if($sign!='')
        {
                $allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
                $extension = pathinfo($sign, PATHINFO_EXTENSION);
                if(!in_array($extension, $allowed_ext))
                {					
                    $errors['sign'] = 'Invalid file format! Please select valid file.';
                }
        }
        if($stamp!='')
        {
                $allowed_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF');				
                $extension = pathinfo($stamp, PATHINFO_EXTENSION);
                if(!in_array($extension, $allowed_ext))
                {					
                    $errors['stamp'] = 'Invalid file format! Please select valid file.';
                }
        }	

		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				if($dob!='') 
				$dob = @date('Y-m-d', strtotime($dob));
					parent::start_transaction();										
					$tableName 	= "seminar";
					$setValues 	= "topic_name='$topic_name', seminar_type='$seminar_type', mode='$mode', approval_no='$approval_no', college_name='$college_name',fee_date='$fee_date', address='$address',place='$place',date='$date',updated_by='$updated_by', updated_on=NOW(),conductor_name='$conductor_name'";
					$whereClause= " WHERE id='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
				
					$exSql	= parent::execQuery($updateSql);					
					
					$courseImgPathDir 		= SEMINAR_DOCUMENTS_PATH.'/'.$id.'/';

					$tableName3 			= "seminar";
					/* upload files */
					if($sign!='')
					{								
						$ext 			= pathinfo($_FILES["sign"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'sign_'.mt_rand(0,123456789).'.'.$ext;								
									
                        $setValues 		= "sign='$file_name'";
                        $whereClause	= " WHERE id='$id'";
                        $updateSql		= parent::updateData($tableName3,$setValues,$whereClause);	
                        $exSql2		    = parent::execQuery($updateSql);	
						
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["sign"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						//parent::create_thumb_img($_FILES["instlogo"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}
					if($stamp!='')
					{								
						$ext 			= pathinfo($_FILES["stamp"]["name"], PATHINFO_EXTENSION);
						$file_name 		= 'stamp_'.mt_rand(0,123456789).'.'.$ext;	

						$setValues 		= "stamp='$file_name'";
                        $whereClause	= " WHERE id='$id'";
                        $updateSql		= parent::updateData($tableName3,$setValues,$whereClause);	
                        $exSql2		    = parent::execQuery($updateSql);						
			
						$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
						$courseImgThumbPathDir 	= 	$courseImgPathDir.'/thumb/';
						$courseImgThumbPathFile = 	$courseImgThumbPathDir.''.$file_name;
						@mkdir($courseImgPathDir,0777,true);
						//@mkdir($courseImgThumbPathDir,0777,true);								
						parent::create_thumb_img($_FILES["stamp"]["tmp_name"], $courseImgPathFile,  $ext, 800, 750) ;
						//parent::create_thumb_img($_FILES["passphoto"]["tmp_name"], $courseImgThumbPathFile,  $ext, 300, 280);									
					}	
				
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Seminar has been updated successfully!';
				 
			}
		return json_encode($data);			
	}
	
	public function list_seminar($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM seminar A WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id='$id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.created_on DESC';
	    //echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

    public function delete_seminar($id)
	{
		$sql = "UPDATE seminar SET active='0',delete_flag='1', updated_by='".$_SESSION['user_fullname']."' WHERE id='$id'";	 
		$res= parent::execQuery($sql);		
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

    //seminar student
    public function add_seminar_student()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data
		  
		  //print_r($_POST); exit();
		  $seminar_id 		= parent::test(isset($_POST['seminar_id'])?$_POST['seminar_id']:'');
		  $name 	= parent::test(isset($_POST['name'])?$_POST['name']:'');
		  $crr_no 		    = parent::test(isset($_POST['crr_no'])?$_POST['crr_no']:'');
		  $type 		= parent::test(isset($_POST['type'])?$_POST['type']:'');
		  $cre_points 	= parent::test(isset($_POST['cre_points'])?$_POST['cre_points']:'');
		  $session 		= parent::test(isset($_POST['session'])?$_POST['session']:'');
		  $active 		    = parent::test(isset($_POST['active'])?$_POST['active']:'');
	
		  $created_by  		= $_SESSION['user_fullname'];
		 /* check validations */
		  if ($seminar_id=='')
			$errors['seminar_id'] = 'Please select seminar.';
		  if ($name=='')
			$errors['name'] = 'Student Name is required.';
		  if ($crr_no=='')
			$errors['crr_no'] = 'CRR Number is required.';
		  if ($type=='')
			$errors['type'] = 'Student Type is required.';
		  if ($cre_points=='')
			$errors['cre_points'] = 'CRE Points is required.';
		  if ($session=='')
			$errors['session'] = 'Session Type is required.';

		 //$errors=array();
           if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				if($dob!='') 
				$dob = @date('Y-m-d', strtotime($dob));
					parent::start_transaction();
					
					$tableName 	= "seminar_student";
					$tabFields 	= "(id, seminar_id, name, crr_no,type,cre_points,session,active,delete_flag,created_by,created_on)";
					$insertVals	= "(NULL, '$seminar_id', '$name', '$crr_no','$type','$cre_points','$session','$active','0','$created_by',NOW())";

					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{						 
                        parent::commit();
                        $data['success'] = true;
                        $data['message'] = 'Success! New Student has been added successfully!';
                    
                    }else{
                        parent::rollback();
                        $data['message'] = 'Sorry! Something went wrong! Could not add the Student.';
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
	public function update_seminar_student($inst_id)
	{
	     //print_r($_POST); exit();
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $id               = parent::test(isset($_POST['id'])?$_POST['id']:'');
          $seminar_id 		= parent::test(isset($_POST['seminar_id'])?$_POST['seminar_id']:'');
		  $name 	= parent::test(isset($_POST['name'])?$_POST['name']:'');
		  $crr_no 		    = parent::test(isset($_POST['crr_no'])?$_POST['crr_no']:'');
		  $type 		= parent::test(isset($_POST['type'])?$_POST['type']:'');
		  $cre_points 	= parent::test(isset($_POST['cre_points'])?$_POST['cre_points']:'');
		  $session 		= parent::test(isset($_POST['session'])?$_POST['session']:'');
		  $active 		    = parent::test(isset($_POST['active'])?$_POST['active']:'');
		
		 $updated_by  	= $_SESSION['user_fullname'];

            /* check validations */
		    if ($seminar_id=='')
            $errors['seminar_id'] = 'Please select seminar.';
            if ($name=='')
            $errors['name'] = 'Student Name is required.';
            if ($crr_no=='')
            $errors['crr_no'] = 'CRR Number is required.';
            if ($type=='')
            $errors['type'] = 'Student Type is required.';
            if ($cre_points=='')
            $errors['cre_points'] = 'CRE Points is required.';
            if ($session=='')
            $errors['session'] = 'Session Type is required.';

		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
				if($dob!='') 
				$dob = @date('Y-m-d', strtotime($dob));
					parent::start_transaction();										
					$tableName 	= "seminar_student";
					$setValues 	= "seminar_id='$seminar_id', name='$name', crr_no='$crr_no', type='$type', cre_points='$cre_points',session='$session', active='$active',updated_by='$updated_by', updated_on=NOW()";
					$whereClause= " WHERE id='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
				
					$exSql	= parent::execQuery($updateSql);					
				
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Student has been updated successfully!';
				 
			}
		return json_encode($data);			
	}
	
	public function list_seminar_student($id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.*,B.topic_name FROM seminar_student A LEFT JOIN seminar B ON A.seminar_id = B.id WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id='$id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.created_on DESC';
	    //echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}

    public function delete_seminar_student($id)
	{
		$sql = "UPDATE seminar_student SET active='0',delete_flag='1', updated_by='".$_SESSION['user_fullname']."' WHERE id='$id'";	 
		$res= parent::execQuery($sql);		
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

	public function list_seminar_student_print($id='')
	{
		$data = '';
		$sql= "SELECT A.*,B.topic_name,B.seminar_type,B.mode,B.approval_no,B.college_name,B.fee_date,B.address,B.place,B.date,B.sign,B.stamp,B.conductor_name FROM seminar_student A LEFT JOIN seminar B ON A.seminar_id = B.id WHERE A.delete_flag=0 ";
		
		if($id!='')
		{
			$sql .= " AND A.id='$id' ";
		}		
		$sql .= ' ORDER BY A.created_on DESC';
	    //echo $sql; exit();
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
}
?>