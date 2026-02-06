<?php
include_once('database_results.class.php');
include_once('access.class.php');

class festival extends access
{
	public function add_festival()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $name 		= parent::test(isset($_POST['name'])?$_POST['name']:'');
		  $date 		= parent::test(isset($_POST['date'])?$_POST['date']:'');
		  
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  
		  /* Files */
		  $filecount 		= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		  if ($name=='')
			$errors['$name'] = 'Name is required!'; 
		  if ($date=='')
			$errors['$date'] = 'Date is required!';
	
		  if($filecount>=1)
		  {
			  for($i=0; $i<$filecount; $i++)
			  {
				$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
				if($coursematerial!='')
				{
					$filetitle 		= parent::test(isset($_POST['filetitle'.$i])?$_POST['filetitle'.$i]:'');				
					$allowed_ext 	= array('jpg','jpeg','png','JPG', 'PNG', 'JPEG');				
					$extension 		= pathinfo($coursematerial, PATHINFO_EXTENSION);
					if(!in_array($extension, $allowed_ext))
					{					
						$errors['coursematerial'.$i] = 'Invalid file format! Please select valid file.';
					}
				}
			  }
		  }
		
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
					parent::start_transaction();
					$tableName 	= "festival_images_data";
					$tabFields 	= "(id, name,date, active, delete_flag,created_by,created_at)";
					$insertVals	= "(NULL, '$name','$date','$status','0','$created_by',NOW())";
					echo $insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{
						/* upload course files */
						$last_insert_id 	= parent::last_id();
						
						$courseImgPathDir 	= FESTIVAL_IMAGES_PATH.'/'.$last_insert_id.'/';
				
              	         $tableName3 		= " festival_images";
						  if($filecount>=1)
						  {
							  for($i=0; $i<$filecount; $i++)
							  {
								$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
								if($coursematerial!='')
								{
									$ext 			= pathinfo($_FILES["coursematerial".$i]["name"], PATHINFO_EXTENSION);
									$file_name 		= $name.'_'.mt_rand(0,123456789).'.'.$ext;								
									$tabFields3 	= "(id,data_id,image,active,delete_flag,created_by,created_at)";
									$insertVals3	= "(NULL, '$last_insert_id', '$file_name','1',0,'$created_by',NOW())";
									$insertSql3		= parent::insertData($tableName3,$tabFields3,$insertVals3);
									$exec3   		= parent::execQuery ($insertSql3);
									
									$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
									@mkdir($courseImgPathDir,0777,true);
									@move_uploaded_file($_FILES["coursematerial".$i]["tmp_name"], $courseImgPathFile);
								}								
							  }
						  }	
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Festival has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the course.';
						$data['success'] = false;
						$data['errors']  = $errors;
					}			 
			}
		return json_encode($data);			
	}

	public function update_festival($festival_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $id 		= parent::test(isset($_POST['id'])?$_POST['id']:'');
		  
		  $name 		= parent::test(isset($_POST['name'])?$_POST['name']:'');
		  $date 		= parent::test(isset($_POST['date'])?$_POST['date']:'');
		  
		  $status 	= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  
		  /* Files */
		  $filecount 	= parent::test(isset($_POST['filecount'])?$_POST['filecount']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= 2; //institute staff;
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		    /* check validations */
	        if ($name=='')
			$errors['$name'] = 'Name is required!'; 
		    if ($date=='')
			$errors['$date'] = 'Date is required!';
	
		  if($filecount>=1)
		  {
			  for($i=0; $i<$filecount; $i++)
			  {
				$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
				if($coursematerial!='')
				{
					$allowed_ext 	= array('jpg','jpeg','png','JPG', 'PNG', 'JPEG');				
					$extension 		= pathinfo($coursematerial, PATHINFO_EXTENSION);
					if(!in_array($extension, $allowed_ext))
					{					
						$errors['coursematerial'.$i] = 'Invalid file format! Please select valid file.';
					}
				}
			  }
		  }
	
		  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "festival_images_data";
					$setValues 	= "name='$name', date='$date', active='$status',updated_by='$updated_by', updated_at=NOW()";
					$whereClause= " WHERE id='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);	
					$exSql		= parent::execQuery($updateSql);
					
					$courseImgPathDir = FESTIVAL_IMAGES_PATH.'/'.$id.'/';
					
				
					
                    $tableName3 	  = "festival_images";
					/* upload files */
					 if($filecount>=1)
					  {
						  for($i=0; $i<$filecount; $i++)
						  {
							$coursematerial = isset($_FILES['coursematerial'.$i]['name'])?$_FILES['coursematerial'.$i]['name']:'';
							if($coursematerial!='')
							{
								
								$ext 			= pathinfo($_FILES["coursematerial".$i]["name"], PATHINFO_EXTENSION);
								$file_name 		= $name.'_'.mt_rand(0,123456789).'.'.$ext;								
								$tabFields3 	= "(id,data_id,image,active,delete_flag,created_by,created_at)";
								$insertVals3	= "(NULL, '$id','$file_namet','1','0','$updated_by',NOW())";
								$insertSql3		= parent::insertData($tableName3,$tabFields3,$insertVals3);
								$exec3   		= parent::execQuery ($insertSql3);
								
								$courseImgPathFile 		= 	$courseImgPathDir.''.$file_name;
								@mkdir($courseImgPathDir,0777,true);
								@move_uploaded_file($_FILES["coursematerial".$i]["tmp_name"], $courseImgPathFile); 
							}
							
						  }
					  }				
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Festival has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function list_festival($festival_id='',$condition='',$limit='')
	{
		$data = '';
		$sql= "SELECT A.* FROM festival_images_data A WHERE A.active=1 ";
		
		if($festival_id!='')
		{
			$sql .= " AND A.id='$festival_id' ";
		}
		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= 'ORDER BY A.created_at DESC';
		//echo $sql;
		if($limit!='')
		{
			$sql .= " $limit ";
		}
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	public function list_festival_images($festival_image_id='',$festival_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM festival_images A WHERE A.active=1 ";
		
		if($festival_image_id!='')
		{
			$sql .= " AND A.id='$festival_image_id' ";
		}
		if($festival_id!='')
		{
			$sql .= " AND A.data_id='$festival_id' ";
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
	
	public function get_docs_all($id='', $display=true)
	{
		$img = '';
		$data= array();
		$target='';
		
		$sql ="SELECT id,data_id,image FROM  festival_images WHERE active = 1";
		if($id!='')
			$sql .= " AND data_id='$id'";		
		$sql .= ' ORDER BY id DESC';
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$img .='<table class="table table-responsive table-bordered">';
			while($rec = $res->fetch_assoc())
			{
				$id 		= $rec['id'];
				$image 		= $rec['image'];
				$data_id 		= $rec['data_id'];
				if($image!='')
				{
					
					$fileLink = FESTIVAL_IMAGES_PATH.'/'.$data_id.'/'.$image;
					
					$img .= '<tr id="file-area'.$id.'">
					
					<td>
					<img src="'.$fileLink.'" style="width: 150px;
    height: 150px;
    border-radius: 0px;" />
						<a href="javascript:void(0)" title= "Delete File" onclick="deleteFestivalFile('.$id.','.$data_id.')" class="btn btn-danger table-btn"><i class=" mdi mdi-delete"></i></a>
				
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="'.$fileLink.'" target="_blank" title="View File" class="btn btn-primary table-btn"><i class="fa fa-eye"></i>View</a>
					</td>
				  </tr>';
				}
					
							
			}			
		}
		$img .= '</table>';		
		if(!$display)
			return $data;
		else return $img;
	}
	
	/* delete festival file */
	public function delete_festival_file($file_id, $course_id='')
	{
		$sql = "DELETE FROM festival_images WHERE id='$file_id'";
		if($course_id!='')
		 $sql .= " AND data_id='$course_id'";
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	/* delete festival */
	public function delete_festival($course_id)
	{
		
		$sql = "DELETE FROM festival_images_data WHERE id='$course_id'";
		$sql2 = "DELETE FROM festival_images WHERE data_id='$course_id'";
		
		$res2 = parent::execQuery($sql2);
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}

}
?>