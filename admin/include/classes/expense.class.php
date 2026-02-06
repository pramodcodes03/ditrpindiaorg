<?php
include_once('database_results.class.php');
include_once('access.class.php');
class expense extends access
{

	
	/* add new staff in institute 
	@param: 
	@return: json
	*/
	public function add_expense()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $category_id 		= parent::test(isset($_POST['category_id'])?$_POST['category_id']:'');
		  $subcategory_id 			= parent::test(isset($_POST['subcategory_id'])?$_POST['subcategory_id']:'');
		  $issue_name 		= parent::test(isset($_POST['issue_name'])?$_POST['issue_name']:'');
		  $name_of_person 		= parent::test(isset($_POST['name_of_person'])?$_POST['name_of_person']:'');
		  $amount 		= parent::test(isset($_POST['amount'])?$_POST['amount']:'');
		  $edate 		= parent::test(isset($_POST['edate'])?$_POST['edate']:'');
		  $vno 		= parent::test(isset($_POST['vno'])?$_POST['vno']:'');
		  $cbfno 			= parent::test(isset($_POST['cbfno'])?$_POST['cbfno']:'');
		  $remarks 		= parent::test(isset($_POST['remarks'])?$_POST['remarks']:'');
		  $payment_mode 			= parent::test(isset($_POST['payment_mode'])?$_POST['payment_mode']:'');
		  $gstno 			= parent::test(isset($_POST['gstno'])?$_POST['gstno']:'');		  
		  $created_by  		= $_SESSION['user_fullname'];
		  $admin_id 		= $_SESSION['user_id'];
		  $role 		= $_SESSION['user_role'];
		  
		    $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
            $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
    		
    		if($user_role==3){
               $institute_id = parent::get_parent_id($user_role,$user_id);
               $staff_id = $user_id;
            }
            else{
               $institute_id = $user_id;
               $staff_id = 0;
            }
            
		 /* check validations */
		  if ($category_id=='')
			$errors['category_id'] = 'Expense Type is required!'; 
		  if ($issue_name=='')
			$errors['issue_name'] = 'Issue Name is required!';
		  if ($name_of_person=='')
			$errors['name_of_person'] = 'Person name is required!';
		  if ($amount=='')
			$errors['amount'] = 'Amount is required!';

		  if ($edate=='')
			$errors['edate'] = 'Date is required!';
	
		
		  if ($remarks=='')
			$errors['remarks'] = 'Remarks is required!';
		  if ($payment_mode=='')
			$errors['payment_mode'] = 'Payment mode is required!';
		 
		  $role 			= $_SESSION['user_role'];

		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
					parent::start_transaction();
					$tableName 	= "expenses";
					$tabFields 	= "(EXPENSE_ID,INSTITUTE_ID,CATEGORY,SUBCATEGORY , ISSUE_NAME, NAME_OF_PERSON,AMOUNT,EDATE,VNO,CBFNO,REMARKS,PAYMENT_MODE,GSTNO,CREATED_BY, CREATED_ON)";
					$insertVals	= "(NULL,'$institute_id','$category_id','$subcategory_id', '$issue_name', '$name_of_person','$amount','$edate','$vno','$cbfno','$remarks','$payment_mode','$gstno','$created_by',NOW())";
					echo $insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{
						if($role != 8){
						    $wallet_id='';
    						$res = parent::get_wallet('',$institute_id,$role);
    						if($res!='')
    						{
    							$data1 = $res->fetch_assoc();
    							$walletBal = $data1['TOTAL_BALANCE'];
    							$wallet_id = $data1['WALLET_ID'];
    
    							$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $amount, UPDATED_BY='$created_by', UPDATED_ON=NOW() WHERE WALLET_ID='$wallet_id'";
    							$reswallet = parent::execQuery($sqlwallet);
    							
    						}
						}
						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New expense has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the expense.';
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
	public function update_expense($expense_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $category_id 		= parent::test(isset($_POST['category_id'])?$_POST['category_id']:'');
		  $subcategory_id 			= parent::test(isset($_POST['subcategory_id'])?$_POST['subcategory_id']:'');
		  $issue_name 		= parent::test(isset($_POST['issue_name'])?$_POST['issue_name']:'');
		  $name_of_person 		= parent::test(isset($_POST['name_of_person'])?$_POST['name_of_person']:'');
		  $amount 		= parent::test(isset($_POST['amount'])?$_POST['amount']:'');
		  $amountprevious 		= parent::test(isset($_POST['amountprevious'])?$_POST['amountprevious']:'');
		  $edate 		= parent::test(isset($_POST['edate'])?$_POST['edate']:'');
		  $vno 		= parent::test(isset($_POST['vno'])?$_POST['vno']:'');
		  $cbfno 			= parent::test(isset($_POST['cbfno'])?$_POST['cbfno']:'');
		  $remarks 		= parent::test(isset($_POST['remarks'])?$_POST['remarks']:'');
		  $payment_mode 			= parent::test(isset($_POST['payment_mode'])?$_POST['payment_mode']:'');
		  $gstno 			= parent::test(isset($_POST['gstno'])?$_POST['gstno']:'');		  
		  $created_by  		= $_SESSION['user_fullname'];
		  $admin_id 		= $_SESSION['user_id'];
		  $role 			= $_SESSION['user_role'];
		  
		   $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
            $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
    		
    		if($user_role==3){
               $institute_id = parent::get_parent_id($user_role,$user_id);
               $staff_id = $user_id;
            }
            else{
               $institute_id = $user_id;
               $staff_id = 0;
            }
            
		 /* check validations */
		  if ($category_id=='')
			$errors['category_id'] = 'Expense Type is required!'; 
		  if ($issue_name=='')
			$errors['issue_name'] = 'Issue Name is required!';
		  if ($name_of_person=='')
			$errors['name_of_person'] = 'Person name is required!';
		  if ($amount=='')
			$errors['amount'] = 'Amount is required!';

		  if ($edate=='')
			$errors['edate'] = 'Date is required!';
		 
		  if ($remarks=='')
			$errors['remarks'] = 'Remarks is required!';
		  if ($payment_mode=='')
			$errors['payment_mode'] = 'Payment mode is required!';
		
		  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "expenses";
					$setValues 	= "CATEGORY= '$category_id', SUBCATEGORY= '$subcategory_id' , issue_name= '$issue_name' , NAME_OF_PERSON= '$name_of_person' ,AMOUNT='$amount',EDATE='$edate',VNO='$vno',CBFNO='$cbfno',REMARKS='$remarks',PAYMENT_MODE='$payment_mode',GSTNO='$gstno',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE EXPENSE_ID='$expense_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);			
					$exSql		= parent::execQuery($updateSql);

					if($exSql && ($amount != $amountprevious) && $role != 8){
						$wallet_id='';
						$res = parent::get_wallet('',$institute_id,$role);
						if($res!='')
						{
							$data1 = $res->fetch_assoc();
							$walletBal = $data1['TOTAL_BALANCE'];
							$wallet_id = $data1['WALLET_ID'];

							$sqlwallet = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE + $amountprevious, UPDATED_BY='$created_by', UPDATED_ON=NOW() WHERE WALLET_ID='$wallet_id'";
							$reswallet = parent::execQuery($sqlwallet);

							$sqlwallet1 = "UPDATE wallet SET TOTAL_BALANCE= TOTAL_BALANCE - $amount, UPDATED_BY='$created_by', UPDATED_ON=NOW() WHERE WALLET_ID='$wallet_id'";
							$reswallet1 = parent::execQuery($sqlwallet1);
							
						}

					}

					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Expense has been updated successfully!';
			}
		return json_encode($data);			
	}
	
	public function list_expenses($expense_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.*, (SELECT B.CATEGORY FROM expense_category B WHERE B.CATEGORY_ID=A.CATEGORY) AS CATEGORYNAME, (SELECT C.SUBCATEGORY FROM expense_subcategory C WHERE C.SUBCATEGORY_ID=A.SUBCATEGORY) AS SUBCATEGORYNAME FROM expenses A WHERE A.DELETE_FLAG=0 ";
		
		if($expense_id!='')
		{
			$sql .= " AND A.EXPENSE_ID='$expense_id' ";
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
	
	public function list_expensestype($expense_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.* FROM expense_category A WHERE A.DELETE_FLAG=0 ";
		
		
		if($expense_id!='')
		{
			$sql .= " AND A.CATEGORY_ID='$expense_id' ";
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
	
	
	public function list_expense_subcategory($expense_category)
	{
		$data = '';
		$sql= "SELECT A.* FROM expense_subcategory A WHERE A.DELETE_FLAG=0 ";
		
		if($expense_category!='')
		{
			$sql .= " AND CATEGORY_ID = '".@$expense_category."' ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		// echo $sql;exit;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	public function list_expensesubcategory($expense_id='',$condition='')
	{
		$data = '';
		$sql= "SELECT A.*, (SELECT B.CATEGORY FROM expense_category B WHERE B.CATEGORY_ID=A.CATEGORY_ID) AS CATEGORY FROM expense_subcategory A WHERE A.DELETE_FLAG=0 ";
		
		if($expense_id!='')
		{
			$sql .= " AND A.SUBCATEGORY_ID =  '$expense_id'";
		}

		if($condition!='')
		{
			$sql .= " $condition ";
		}
		$sql .= ' ORDER BY A.CREATED_ON DESC';
		//echo $sql; 
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	/* delete course file */
	public function delete_course($course_id)
	{
		
		$sql = "DELETE FROM courses WHERE COURSE_ID='$course_id'";
		$sql2 = "DELETE FROM courses_files WHERE COURSE_ID='$course_id'";
		
		$res2 = parent::execQuery($sql2);
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return false;
		}
		return true;
	}
	
	/* change course status */
	public function changeStatusFlag($course_id, $flag)
	{
		$sql = "UPDATE courses SET ACTIVE='$flag',UPDATED_BY='".$_SESSION['user_fullname']."', UPDATED_ON=NOW(),UPDATED_ON_IP='".$_SESSION['ip_address']."' WHERE COURSE_ID='$course_id'";		
		$res= parent::execQuery($sql);
		
		if($res)
		{
			return true;
		}
		return false;
	}
	
	public function addexpensetype()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $expensetype 		= parent::test(isset($_POST['expensetype'])?$_POST['expensetype']:'');
		
		    $admin_id 		= $_SESSION['user_id'];
		   	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
            $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
			
			if($user_role==3){
               $institute_id = parent::get_parent_id($user_role,$user_id);
               $staff_id = $user_id;
            }
            else{
               $institute_id = $user_id;
               $staff_id = 0;
            }

		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		 if ($expensetype=='') $errors['expensetype'] = 'Expense type is required!';		    
	
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "expense_category";
					$tabFields 	= "(INSTITUTE_ID,CATEGORY,ACTIVE,DELETE_FLAG, CREATED_BY,CREATED_ON)";
					$insertVals	= "('$institute_id','$expensetype','1','0','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 

						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Expense type has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Award name .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
					
				 
			}
		return json_encode($data);			
	}	
	public function updateexpensetype($id='')
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		  $id 		= parent::test(isset($_POST['id'])?$_POST['id']:'');

		  $expensetype 		= parent::test(isset($_POST['expensetype'])?$_POST['expensetype']:'');
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  /* check validations */
		  if ($expensetype=='') $errors['expensetype'] = 'Expense type is required!';
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {				
					parent::start_transaction();										
					$tableName 	= "expense_category";
					$setValues 	= " CATEGORY='$expensetype',UPDATED_BY='$updated_by',UPDATED_ON=NOW()";
					$whereClause= " WHERE CATEGORY_ID ='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);
                
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Expense type has been updated successfully!';
			}
		return json_encode($data);			
	}
	
	
	public function addexpensesubtype()
	{ 
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $expensetype 		= parent::test(isset($_POST['expensetype'])?$_POST['expensetype']:'');
		  $expensesubtype 		= parent::test(isset($_POST['expensesubtype'])?$_POST['expensesubtype']:'');
		
		  $admin_id 		= $_SESSION['user_id'];
		    $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
            $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
    		
    		if($user_role==3){
               $institute_id = parent::get_parent_id($user_role,$user_id);
               $staff_id = $user_id;
            }
            else{
               $institute_id = $user_id;
               $staff_id = 0;
            }
            
		  $created_by  		= $_SESSION['user_fullname'];
		  
		 /* check validations */
		 if ($expensetype=='') $errors['expensetype'] = 'Expense type is required!';		    
		 if ($expensesubtype=='') $errors['expensesubtype'] = 'Expense subtype is required!';		    
	
		  if (! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			}else{
				 
					parent::start_transaction();
					$tableName 	= "expense_subcategory";
					$tabFields 	= "(INSTITUTE_ID,CATEGORY_ID,SUBCATEGORY, ACTIVE,DELETE_FLAG, CREATED_BY,CREATED_ON)";
					$insertVals	= "('$institute_id','$expensetype','$expensesubtype','1','0','$created_by',NOW())";
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					$exSql		= parent::execQuery($insertSql);
					if($exSql)
					{	 

						parent::commit();
						$data['success'] = true;
						$data['message'] = 'Success! New Expense type has been added successfully!';
					}else{
						parent::rollback();
						$errors['message'] = 'Sorry! Something went wrong! Could not add the Award name .';
						$data['success'] = false;
						$data['errors']  = $errors;
					}
			}
		return json_encode($data);			
	}
	
	public function updateexpensesubtype($id='')
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 
		 
		  $id 		= parent::test(isset($_POST['id'])?$_POST['id']:'');

		  $expensetype 		= parent::test(isset($_POST['expensetype'])?$_POST['expensetype']:'');
		  $expensesubtype 		= parent::test(isset($_POST['expensesubtype'])?$_POST['expensesubtype']:'');
		  $admin_id 		= $_SESSION['user_id'];
		  $updated_by  		= $_SESSION['user_fullname'];
		  
		  
		 /* check validations */
		  /* check validations */
		  if ($expensetype=='') $errors['expensetype'] = 'Expense type is required!';
		  if ($expensesubtype=='') $errors['expensesubtype'] = 'Expense sub type is required!';
		
		  if (!empty($errors)) { 
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {				
					parent::start_transaction();										
					$tableName 	= "expense_subcategory";
					$setValues 	= " SUBCATEGORY='$expensesubtype', CATEGORY_ID='.$expensetype.' ,UPDATED_BY='$updated_by',UPDATED_ON=NOW()";
					$whereClause= " WHERE SUBCATEGORY_ID ='$id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);				
					$exSql		= parent::execQuery($updateSql);
                
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Expense type has been updated successfully!';
			}
		return json_encode($data);			
	}

	public function display_expense_subcategory($expense_category){
		$data = '';
		$sql= "SELECT A.* FROM expense_subcategory A WHERE A.DELETE_FLAG=0 ";
		
		if($expense_category!='')
		{
			$sql .= " AND CATEGORY_ID = '".@$expense_category."' ";
		}
		$sql .= 'ORDER BY A.CREATED_ON DESC';
		// echo $sql;exit;
		$res = parent:: execQuery($sql);
		$html = '<option value="">Select Expense Sub-Type</option>';
		if($res && $res->num_rows>0)
		{
			while($data = $res->fetch_assoc()){
				$html .='<option value="'.$data['SUBCATEGORY_ID'].'">'.$data['SUBCATEGORY'].'</option>';
			}
		}
		echo $html;exit;
	}

	public function delete_expense($enq_id='')
	{
		$sql = "UPDATE expenses SET DELETE_FLAG=1 WHERE EXPENSE_ID='$enq_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return true;
		}
		return false;
	}	

	public function delete_expense_category($enq_id='')
	{
		$sql = "UPDATE expense_category SET DELETE_FLAG=1 WHERE CATEGORY_ID='$enq_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return true;
		}
		return false;
	}	

	public function delete_expense_subcategory($enq_id='')
	{
		$sql = "UPDATE expense_subcategory SET DELETE_FLAG=1 WHERE SUBCATEGORY_ID='$enq_id'";		
		$res = parent::execQuery($sql);
		if($res && parent::rows_affected()>0)
		{
			return true;
		}
		return false;
	}	
}
?>