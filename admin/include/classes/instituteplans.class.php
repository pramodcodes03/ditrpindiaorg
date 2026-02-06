<?php
include_once('database_results.class.php');
include_once('access.class.php');

class instituteplans extends access
{	
	public function add_institue_plan()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $planname 		= parent::test(isset($_POST['planname'])?$_POST['planname']:'');
		
		  $status 		= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $created_by  		= $_SESSION['user_fullname'];
		 //new validations
		  if ($planname=='')
			$errors['planname'] = 'Plan name is required.';
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();
					
					$tableName 	= "institute_plans";
					$tabFields 	= "(PLAN_ID, PLAN_NAME, ACTIVE,DELETE_FLAG,CREATED_BY, CREATED_ON)";
					$insertVals	= "(NULL, UPPER('$planname'),'$status',0,'$created_by',NOW())";
										
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					
					$exSql		= parent::execQuery($insertSql);
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Institute Plan has been added successfully!';
			}				
						
		return json_encode($data);			
	}
	
	public function update_institue_plan($plan_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $plan_id 			= parent::test(isset($_POST['plan_id'])?$_POST['plan_id']:'');
		  $planname 		= parent::test(isset($_POST['planname'])?$_POST['planname']:'');		
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');		 
				 
		  $updated_by  		= $_SESSION['user_fullname'];
		
		 /* check validations */		 
		  if ($planname=='')
			$errors['planname'] = 'Plan name is required.';		
		 
		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "institute_plans";
					$setValues 	= "PLAN_NAME=UPPER('$planname'),ACTIVE='$status',DELETE_FLAG='0',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE PLAN_ID='$plan_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
				
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Institute Plan has been updated successfully!';
						
					
				 
			}
		return json_encode($data);			
	}
	public function list_institue_plan($plan_id='', $cond='')
	{
		$data = '';
		$sql= "SELECT * FROM institute_plans WHERE DELETE_FLAG=0";
		if($plan_id!='')
		{
			$sql .= " AND PLAN_ID='$plan_id' ";
		}
		if($cond!='')
		{
			$sql .= $cond;
		}
		//echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	public function deleteInstitutePlan($plan_id)
	{
		echo $sql = "UPDATE institute_plans SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE PLAN_ID='$plan_id'";
		$res= parent::execQuery($sql);
		return false;
	}
	/*-------------------------------------------------------------------------------------------------*/	
}
?>