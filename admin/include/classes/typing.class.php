<?php
include_once('database_results.class.php');
include_once('access.class.php');

class typing extends access
{	
	/* add new Activation Plan 
	@param: 
	@return: json
	*/
	public function add_typing_activation()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

		  $planname 		= parent::test(isset($_POST['planname'])?$_POST['planname']:'');
		  $planvalidity 	= parent::test(isset($_POST['planvalidity'])?$_POST['planvalidity']:'');
		  $amount 			= parent::test(isset($_POST['amount'])?$_POST['amount']:'');		  
		  $status 		= parent::test(isset($_POST['status'])?$_POST['status']:'');
		  $created_by  		= $_SESSION['user_fullname'];
		 //new validations
		  if ($planname=='')
			$errors['planname'] = 'Plan name is required.';
		  if ($planvalidity=='')
			$errors['planvalidity'] = 'Plan Validity is required.';
		  if ($amount=='')
			$errors['amount'] = 'Amount is required.';		 
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();
					
					$tableName 	= "typing_software_plans";
					$tabFields 	= "(PLAN_ID, PLAN_NAME, VALIDITY, AMOUNT,ACTIVE,CREATED_BY, CREATED_ON)";
					$insertVals	= "(NULL, UPPER('$planname'),'$planvalidity','$amount','$status','$created_by',NOW())";
										
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					
					$exSql		= parent::execQuery($insertSql);
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Plan has been added successfully!';
			}				
						
		return json_encode($data);			
	}
	
	/* update Activation Plan  
	@param: 
	@return: json
	*/
	public function update_typing_activation($plan_id)
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data 

		  $plan_id 			= parent::test(isset($_POST['plan_id'])?$_POST['plan_id']:'');
		  $planname 		= parent::test(isset($_POST['planname'])?$_POST['planname']:'');
		  $planvalidity 	= parent::test(isset($_POST['planvalidity'])?$_POST['planvalidity']:'');
		  $amount 			= parent::test(isset($_POST['amount'])?$_POST['amount']:'');	
		
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');		 
				 
		  $updated_by  		= $_SESSION['user_fullname'];
		
		 /* check validations */		 
		  if ($planname=='')
			$errors['planname'] = 'Plan name is required.';
		  if ($planvalidity=='')
			$errors['planvalidity'] = 'Plan Validity is required.';
		  if ($amount=='')
			$errors['amount'] = 'Amount is required.';
		
		 
		  if ( ! empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "typing_software_plans";
					$setValues 	= "PLAN_NAME=UPPER('$planname'),VALIDITY='$planvalidity',AMOUNT='$amount', ACTIVE='$status',UPDATED_BY='$updated_by', UPDATED_ON=NOW()";
					$whereClause= " WHERE PLAN_ID='$plan_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause);
				
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Plan has been updated successfully!';
						
					
				 
			}
		return json_encode($data);			
	}
	public function list_typing_activation_plan($plan_id='', $cond='')
	{
		$data = '';
		$sql= "SELECT * FROM typing_software_plans WHERE DELETE_FLAG=0";
		if($plan_id!='')
		{
			$sql .= " AND PLAN_ID='$plan_id' ";
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
	public function deletePlan($plan_id)
	{
		echo $sql = "UPDATE typing_software_plans SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE PLAN_ID='$plan_id'";
		$res= parent::execQuery($sql);
		return false;
	}
	/*------------------------------------------------------------------------------------------------------------------------*/
	
	/*------------------------Institute Registration For Typing Software ------------------------------------------------------*/
	/*------------------Using as a webservice ---------------------------------------------------------------------------------*/
	public function add_typing_institute()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data

                 $str='';

		  $institutecode 		= parent::test(isset($_GET['txt_institute_code'])?$_GET['txt_institute_code']:'');
		  $institutename 		= parent::test(isset($_GET['txt_institute_name'])?$_GET['txt_institute_name']:'');
		  $ownername 			= parent::test(isset($_GET['txt_owner_name'])?$_GET['txt_owner_name']:'');	
		  $email 			= parent::test(isset($_GET['txt_email'])?$_GET['txt_email']:'');
		  $mobile 			= parent::test(isset($_GET['txt_mobile'])?$_GET['txt_mobile']:'');
		  $address 			= parent::test(isset($_GET['rtb_address'])?$_GET['rtb_address']:'');
		  $pincode 			= parent::test(isset($_GET['txt_postal_code'])?$_GET['txt_postal_code']:'');
		  $username 			= parent::test(isset($_GET['Txt_user_name'])?$_GET['Txt_user_name']:'');
		  $password 			= parent::test(isset($_GET['txt_password'])?$_GET['txt_password']:'');
		  
		  $plan_id 			= 0;
		  $key				= parent::test(isset($_GET['lbl_uniq_id'])?$_GET['lbl_uniq_id']:'');
		  
		  $user_role_id 		= 7;
		  $status 			= 0;
		 //new validations
		  /*if ($institutename=='')
			$errors['institutename'] = 'Institute name is required.';
		  if ($ownername=='')
			$errors['ownername'] = 'Owner name is required.';
		  if ($mobile=='')
			$errors['mobile'] = 'Mobile number is required.';	*/	 
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();
					
					$tableName 	= "typing_institute_details";
					$tabFields 	= "(INSTITUTE_ID, INSTITUTE_CODE, INSTITUTE_NAME, OWNER_NAME,EMAIL,MOBILE, ADDRESS,PINCODE,USERNAME,PASSWORD,USER_ROLE_ID,PLAN_ID,ACTIVATION_KEY,ACTIVATION_DATE,ACTIVE,DELETE_FLAG,CREATED_BY,CREATED_ON)";
					$insertVals	= "(NULL,'$institutecode',UPPER('$institutename'),UPPER('$ownername'),'$email','$mobile','$address','$pincode','$username','$password','$user_role_id','$plan_id','$key',NOW(),'$status','0','$ownername',NOW())";
										
					$insertSql	= parent::insertData($tableName,$tabFields,$insertVals);
					
					$exSql		= parent::execQuery($insertSql);
							parent::commit();
							$data['success'] = true;
							$data['message'] = 'Success! New Institute has been registered successfully!';
$str="\n $institutecode\n $institutename\n $ownername\n $email\n $mobile\n $address\n $pincode\n $username\n $password\n\n$key";
			}				
                 	
		return ($str);			
	}
	
	public function list_typing_institute($institute_id='', $cond='')
	{
		$data = '';
		$this->check_typing_expired($institute_id);
		$sql= "SELECT A.*, B.PLAN_NAME,B.VALIDITY FROM typing_institute_details A left join typing_software_plans B ON A.PLAN_ID=B.PLAN_ID WHERE A.DELETE_FLAG=0";
		
		//$sql= "SELECT A.*, B.PLAN_NAME, check_typing_expired(A.INSTITUTE_ID) AS IS_EXPIRED FROM typing_institute_details A left join typing_software_plans B ON A.PLAN_ID=B.PLAN_ID WHERE A.DELETE_FLAG=0";
		if($institute_id!='')
		{
			$sql .= " AND A.INSTITUTE_ID='$institute_id' ";
		}
		if($cond!='')
		{
			$sql .= $cond;
		}
	//	echo $sql;
		$res = parent:: execQuery($sql);
		if($res && $res->num_rows>0)
			$data = $res;
		return $data;
	}
	
	public function update_typing_institute()
	{
		  $errors = array();  // array to hold validation errors
		  $data = array();        // array to pass back data
		  
		  $institute_id 		= parent::test(isset($_POST['institute_id'])?$_POST['institute_id']:'');
		  $institute_code 		= parent::test(isset($_POST['institute_code'])?$_POST['institute_code']:'');
		  $institute_name 		= parent::test(isset($_POST['institute_name'])?$_POST['institute_name']:'');
		  $owner_name 			= parent::test(isset($_POST['owner_name'])?$_POST['owner_name']:'');	
		  $email 				= parent::test(isset($_POST['email'])?$_POST['email']:'');
		  $mobile 				= parent::test(isset($_POST['mobile'])?$_POST['mobile']:'');
		  $address 				= parent::test(isset($_POST['address'])?$_POST['address']:'');
		  $pincode 				= parent::test(isset($_POST['pincode'])?$_POST['pincode']:'');
		  $username 			= parent::test(isset($_POST['username'])?$_POST['username']:'');
		  $password 			= parent::test(isset($_POST['password'])?$_POST['password']:'');
		  
		  $plan 				= parent::test(isset($_POST['plan'])?$_POST['plan']:'');
		  $activation_key		= parent::test(isset($_POST['activation_key'])?$_POST['activation_key']:'');
		  $status 			= parent::test(isset($_POST['status'])?$_POST['status']:'');
		 //new validations
		 /* if ($institute_name=='')
			$errors['institute_name'] = 'Institute name is required.';
		  if ($owner_name=='')
			$errors['owner_name'] = 'Owner name is required.';
		  if ($mobile=='')
			$errors['mobile'] = 'Mobile number is required.';	*/	 
		
		 //$errors=array();
                  if (!empty($errors)) {
			  // if there are items in our errors array, return those errors
			  $data['success'] = false;
			  $data['errors']  = $errors;
			  $data['message']  = 'Please correct all the errors.';
			} else {
					parent::start_transaction();										
					$tableName 	= "typing_institute_details";
					$setValues 	= "INSTITUTE_CODE='$institute_code',INSTITUTE_NAME=UPPER('$institute_name'),OWNER_NAME='$owner_name',EMAIL='$email',MOBILE='$mobile',ADDRESS='$address',PINCODE='$pincode',USERNAME='$username',PASSWORD='$password',PLAN_ID='$plan',ACTIVATION_KEY='$activation_key',ACTIVE='$status',UPDATED_BY='$username', UPDATED_ON=NOW()";
				   if($status==1)
				    $setValues .= " , ACTIVATION_DATE=NOW()";
				   else if($status==0)
				    $setValues .= " , DEACTIVATION_DATE=NOW()";
					$whereClause= " WHERE INSTITUTE_ID='$institute_id'";
					$updateSql	= parent::updateData($tableName,$setValues,$whereClause); 
				  
					$exSql		= parent::execQuery($updateSql);					
					
					parent::commit();
					$data['success'] = true;
					$data['message'] = 'Success! Institute has been updated successfully!';
			}				
						
		return json_encode($data);			
	}
	
	public function deleteInstitute($inst_id)
	{
		echo $sql = "UPDATE typing_institute_details SET ACTIVE='0', DELETE_FLAG='1', UPDATED_ON=NOW(),UPDATED_BY='".$_SESSION['user_fullname']."' WHERE INSTITUTE_ID='$inst_id'";
		$res= parent::execQuery($sql);
		return false;
	}

        
    public function verify_typing_institute()
	{
                
        $email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
        $mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';
        $activation_key = isset($_REQUEST['activation_key'])?$_REQUEST['activation_key']:'';

		$result = '';
		$sql= "SELECT A.*, B.PLAN_NAME, B.VALIDITY, B.AMOUNT FROM typing_institute_details A INNER JOIN typing_software_plans B ON A.PLAN_ID = B.PLAN_ID WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1";
              
        if($email!='')
		{
			$sql .= " AND A.EMAIL='$email' ";
		}
        if($mobile!='')
		{
			$sql .= " AND A.MOBILE='$mobile' ";
		}
		if($activation_key!='')
		{
			$sql .= " AND A.ACTIVATION_KEY='$activation_key' ";
		}
		$sql .= " LIMIT 0,1";
		$res = parent:: execQuery($sql);

		if($res && $res->num_rows>0)
        {
			while($data = $res->fetch_assoc())
            {
                $PLAN_NAME=$data['PLAN_NAME'];
                $VALIDITY=$data['VALIDITY'];
                $AMOUNT=$data['AMOUNT'];
                $ACTIVE=$data['ACTIVE'];

                $result="$PLAN_NAME:$VALIDITY:$AMOUNT:$ACTIVE";
            }
        }
	    echo $result;
	}
	
	/* change institute name website visibility flag */
	public function sendActivationEmail($inst_id, $userType)
	{

		//send email			
		require_once("../email/config.php");
		require_once("../email/templates/typing_activationkey_email.php");
			
		return false;
	}
	
	
	public function check_typing_expired($inst_id='')
	{
		
	$dayDiff = 0;
    $validity = 0;
    $result =0;
    $cond='';
   
   $sql = "SELECT A.INSTITUTE_ID, DATEDIFF(NOW(),A.ACTIVATION_DATE) as dayDiff,  B.VALIDITY as  validity FROM typing_institute_details A inner join typing_software_plans B ON A.PLAN_ID=B.PLAN_ID WHERE A.DELETE_FLAG=0 AND A.ACTIVE=1";
   if($inst_id!=''){
        $sql .=" AND A.INSTITUTE_ID=$inst_id";
    }
    $sql .=" ORDER BY A.INSTITUTE_ID DESC";
       $res = parent:: execQuery($sql);
       if($res && $res->num_rows>0)
        {  
                while($data = $res->fetch_assoc())
                {
                    $INSTITUTE_ID =$data['INSTITUTE_ID'];
                $dayDiff=$data['dayDiff'];
                $validity=$data['validity'];
                  
                   if($dayDiff>$validity){
                       $result =1;
                      $sql1 = "UPDATE typing_institute_details SET ACTIVE='0', DELETE_FLAG='1' WHERE INSTITUTE_ID=$INSTITUTE_ID";
                       $res1 = parent:: execQuery($sql1);
                       
                       $sql2="INSERT INTO typing_institute_details (INSTITUTE_CODE, INSTITUTE_NAME,OWNER_NAME, EMAIL, MOBILE, ADDRESS, PINCODE, USERNAME, PASSWORD, USER_ROLE_ID, PLAN_ID, ACTIVATION_STATUS, ACTIVE, ACTIVATION_KEY, ACTIVATION_DATE, DELETE_FLAG, CREATED_BY, CREATED_ON) SELECT INSTITUTE_CODE, INSTITUTE_NAME,OWNER_NAME, EMAIL, MOBILE, ADDRESS, PINCODE, USERNAME, PASSWORD, USER_ROLE_ID, PLAN_ID, ACTIVATION_STATUS, '0', ACTIVATION_KEY, NULL, 0, CREATED_BY, NOW() FROM typing_institute_details WHERE INSTITUTE_ID=$INSTITUTE_ID";
                       $res2 = parent:: execQuery($sql2);
                   }
                }
               
        }
      
   	}
	
}
?>