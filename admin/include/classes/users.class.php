<?php

include_once('database_results.class.php');

include_once('access.class.php');



class user extends access

{

	public function user_login($username, $password, $flag = NULL)

	{

		$info 	=	parent::check($username, $password, $flag);

		if ($info != NULL) {

			$result = parent::create_session($info);

			if (!$result) {

				$msg = LOGIN_USER_FAILED . " with username='$username'";

				parent::add_activity(LOGIN_USER_FAILED, $msg);

				return false;
			} else {

				$msg = LOGIN_USER_SUCCESS . " with username='$username' ";

				parent::add_activity(LOGIN_USER_SUCCESS, $msg);
			}
		}

		return true;
	}

	public function user_logout()

	{

		$res = parent::destroy_session();

		if (!$res)

			return false;

		return true;
	}

	/* get user resposbilities 

	@returns: array

	@param: user_id

	*/

	public function get_user_responsibilities($id)

	{

		$array = array();

		$sql = "SELECT RESPONSIBILTY_ID FROM admin_view_mapping WHERE ADMIN_ID=$id LIMIT 0,1";

		$ex = parent::execQuery($sql);

		if ($ex && $ex->num_rows > 0) {

			while ($data = $ex->fetch_assoc()) {

				$RESPONSIBILTY_ID = $data['RESPONSIBILTY_ID'];

				$array = explode(',', $RESPONSIBILTY_ID);
			}
		}

		return $array;
	}

	public function get_responsibility_name($resp_id)

	{

		$role_name = '';

		$sql = "SELECT RESPONSIBILITY_NAME FROM admin_responsibility_master WHERE RESPONSIBILITY_ID='$resp_id' LIMIT 0,1";

		$ex = parent::execQuery($sql);

		if ($ex && $ex->num_rows > 0) {

			$role = $ex->fetch_assoc();

			$role_name = $role['RESPONSIBILITY_NAME'];
		}

		return $role_name;
	}



	/* Get user role */

	public function get_user_role($role_id)

	{

		$role_name = '';

		$sql = "SELECT ROLE_NAME FROM admin_role_master WHERE ADMIN_ROLE_ID='$role_id' LIMIT 0,1";

		$ex = parent::execQuery($sql);

		if ($ex && $ex->num_rows > 0) {

			$role = $ex->fetch_assoc();

			$role_name = $role['ROLE_NAME'];
		}

		return $role_name;
	}

	/* show all the users by list*/

	public function list_users()

	{

		$role_id = $_SESSION['role_id'];

		$minDate = isset($_POST['minDate']) ? $_POST['minDate'] : '';

		$maxDate = isset($_POST['maxDate']) ? $_POST['maxDate'] : '';

		if ($minDate != '')

			$minDate = date('Y-m-d', strtotime($minDate));

		if ($maxDate != '')

			$maxDate = date('Y-m-d', strtotime($maxDate));



		$output = '<table class="table table-striped table-bordered table-hover" id="list_users_table">';

		$output .= '<thead>

					<tr>

						<th>Name</th>

						<th>Email</th>

						<th>Role</th>

						<th>Created On</th>

						<th>Status</th>

						<th>Action</th>

					</tr>

				</thead>';

		$output .= '<tbody>';



		$sql = "SELECT A.ADMIN_DETAIL_ID, CONCAT(A.FIRST_NAME,' ' , A.LAST_NAME) AS NAME, A.USER_EMAIL, A.CREATED_BY, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATE_DATE,A.UPDATED_BY, A.UPDATED_ON, C.ROLE_NAME,B.ROLE_ID, D.STATUS_NAME, B.ADMIN_STATUS FROM admin_details_master A LEFT JOIN admin_login_master B ON A.ADMIN_DETAIL_ID=B.ADMIN_DETAIL_ID LEFT JOIN admin_role_master C ON B.ROLE_ID=C.ADMIN_ROLE_ID LEFT JOIN admin_status_master D ON B.ADMIN_STATUS=D.ADMIN_STATUS_ID WHERE B.ROLE_ID!=" . $_SESSION['role_id'] . " ";

		$where = "";

		if ($minDate != '' && $maxDate == '')

			$where .=  " AND A.CREATED_ON >= '$minDate'";

		if ($maxDate != '' && $minDate == '')

			$where .=  " AND A.CREATED_ON <= '$maxDate'";

		if ($maxDate != '' && $minDate != '')

			$where .=  " AND A.CREATED_ON BETWEEN '$minDate' AND '$maxDate'";



		$where .= " ORDER BY A.ADMIN_DETAIL_ID DESC";

		$sql .= $where;

		$exc = parent::execQuery($sql);



		if ($exc->num_rows > 0) {

			while ($data = $exc->fetch_assoc()) {

				$ADMIN_DETAIL_ID = $data['ADMIN_DETAIL_ID'];

				$NAME			= $data['NAME'];

				$USER_EMAIL 	= $data['USER_EMAIL'];

				$CREATED_BY 	= $data['CREATED_BY'];

				$CREATED_ON 	= $data['CREATE_DATE'];

				$UPDATED_BY 	= $data['UPDATED_BY'];

				$UPDATED_ON 	= $data['UPDATED_ON'];

				$ROLE_NAME 		= $data['ROLE_NAME'];

				$ROLE_ID 		= $data['ROLE_ID'];

				$ADMIN_STATUS 	= $data['ADMIN_STATUS'];

				$STATUS_NAME 	= $data['STATUS_NAME'];



				$action			= '<a href="page.php?p=update-user&id=' . $ADMIN_DETAIL_ID . '">Edit</a>';



				$output .= '<tr class="odd gradeX">

					<td>' . $NAME . '</td>

					<td>' . $USER_EMAIL . '</td>

					<td>' . $ROLE_NAME . '</td>

					<td>' . $CREATED_ON . '</td>

					<td class="center">' . $STATUS_NAME . '</td>

					<td class="center">' . $action . '</td>

				</tr>';
			}
		}

		$output .= '</tbody></table>';

		return $output;
	}

	/* add new user 

	@param: post values

	@return: true or false

	*/

	public function add_user()

	{

		$action 		= isset($_POST['add_user']) ? $_POST['add_user'] : '';

		$first_name 	= parent::test(isset($_POST['first_name']) ? $_POST['first_name'] : '');

		$last_name 	= parent::test(isset($_POST['last_name']) ? $_POST['last_name'] : '');

		$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

		$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');

		$description	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

		$uname 		= parent::test(isset($_POST['uname']) ? $_POST['uname'] : '');

		$pword 		= parent::test(isset($_POST['pword']) ? $_POST['pword'] : '');

		$re_pword 	= parent::test(isset($_POST['re_pword']) ? $_POST['re_pword'] : '');

		$role 		= parent::test(isset($_POST['role']) ? $_POST['role'] : '');

		$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

		$responsibility 	= isset($_POST['responsibility']) ? $_POST['responsibility'] : '';

		$responsibility_str = '';

		if (!empty($responsibility)) {

			foreach ($responsibility as $value)

				$responsibility_str .= $value . ",";
		}

		$pass_err		= '';

		$valid		= true;

		$created_by  	= $_SESSION['user_name'];

		if ($action != '') {



			if ($valid) {

				/* insert data into tables */

				//insert user detail

				// start transaction 

				parent::start_transaction();

				$tableName 	= "admin_details_master";

				$tabFields 	= "(ADMIN_DETAIL_ID, FIRST_NAME, LAST_NAME, USER_EMAIL,MOBILE,DESCRIPTION, CREATED_BY, CREATED_ON)";

				$insertVals	= "(NULL, '$first_name', '$last_name', '$email','$mobile','$description','$created_by',NOW())";

				$insertSql	= parent::insertData($tableName, $tabFields, $insertVals);

				$exSql		=  parent::execQuery($insertSql);

				if ($exSql) {

					/* -----Get the last insert ID ----- */

					$last_insert_id = parent::last_id();



					// insert responsibilities details

					if (!empty($responsibility)) {

						foreach ($responsibility as $value) {

							$responsibility_str .= $value . ",";



							$tableName1 	= "admin_resposibility_details";

							$tabFields1 	= "(ADMIN_RESPONSIBILITY_DETAIL_ID, ADMIN_DETAIL_ID, RESPOSIBILITY, CREATED_BY,CREATED_ON)";

							$insertVals1	= "(NULL, '$last_insert_id', '$value', '$created_by',NOW())";

							$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);

							$exSql1		=  parent::execQuery($insertSql1);
						}
					}

					// insert login details



					$tableName2 	= "admin_login_master";

					$tabFields2 	= "(ADMIN_LOGIN_ID, ADMIN_DETAIL_ID, USER_NAME, PASS_WORD,ADMIN_STATUS, ROLE_ID, CREATED_BY,CREATED_ON)";

					$insertVals2	= "(NULL, '$last_insert_id', '$uname', MD5('$re_pword'),'$status','$role','$created_by',NOW())";

					$insertSql2	= parent::insertData($tableName2, $tabFields2, $insertVals2);

					$exSql2		= parent::execQuery($insertSql2);

					if ($exSql2) {

						// insert resposibilities

						$responsibility_str = rtrim($responsibility_str, ',');

						$tableName3 	= "admin_view_mapping";

						$tabFields3 	= "(ADMIN_VIEW_MAP_ID, ADMIN_ID, ROLE_ID, RESPONSIBILTY_ID, CREATED_BY,CREATED_ON)";

						$insertVals3	= "(NULL, '$last_insert_id', '$role', '$responsibility_str','$created_by',NOW())";

						$insertSql3		= parent::insertData($tableName3, $tabFields3, $insertVals3);

						$exSql3			= parent::execQuery($insertSql3);



						parent::commit();

						$msg = ADD_USER_SUCCESS . " $first_name $last_name ";

						parent::add_activity("ADD_USER_SUCCESS", $msg);

						return true;
					} else {

						parent::rollback();

						$msg = ADD_USER_FAILED . " $first_name $last_name ";

						parent::add_activity("ADD_USER_FAILED", $msg);

						return false;
					}
				}
			}
		}
	}

	/* update user 

	@param: int user_id

	@return: true or false

	*/

	public function update_user($user_id)

	{

		$action 		= isset($_POST['update_user']) ? $_POST['update_user'] : '';

		$user_id 		= isset($_POST['user_id']) ? $_POST['user_id'] : '';

		if ($action != '' && $user_id != '') {

			$id			= $user_id;

			$first_name 	= parent::test(isset($_POST['first_name']) ? $_POST['first_name'] : '');

			$last_name 	= parent::test(isset($_POST['last_name']) ? $_POST['last_name'] : '');

			$email 		= parent::test(isset($_POST['email']) ? $_POST['email'] : '');

			$mobile 		= parent::test(isset($_POST['mobile']) ? $_POST['mobile'] : '');

			$description	= parent::test(isset($_POST['description']) ? $_POST['description'] : '');

			$role 		= parent::test(isset($_POST['role']) ? $_POST['role'] : '');

			$status 		= parent::test(isset($_POST['status']) ? $_POST['status'] : '');

			$responsibility = isset($_POST['responsibility']) ? $_POST['responsibility'] : '';

			$responsibility_str = '';

			if (!empty($responsibility)) {

				foreach ($responsibility as $value)

					$responsibility_str .= $value . ",";
			}



			$valid		= true;

			$updated_by  = $_SESSION['user_name'];



			if ($valid) {



				//update user detail



				// start transaction 

				parent::start_transaction();

				$tableName 	= "admin_details_master";

				$setValues 	= "FIRST_NAME='$first_name', LAST_NAME='$last_name', USER_EMAIL='$email',MOBILE='$mobile',DESCRIPTION='$description', UPDATED_BY='$updated_by', UPDATED_ON=NOW()";

				$whereClause = " WHERE ADMIN_DETAIL_ID='$id'";

				$updateSql	= parent::updateData($tableName, $setValues, $whereClause);

				$exSql		=  parent::execQuery($updateSql);



				// insert responsibilities details

				if (!empty($responsibility)) {

					//delete all the records for admin details id

					$sqlDel = "DELETE FROM admin_resposibility_details WHERE ADMIN_DETAIL_ID='$id'";

					parent::execQuery($sqlDel);

					foreach ($responsibility as $value) {

						$responsibility_str .= $value . ",";



						$tableName1 	= "admin_resposibility_details";

						$tabFields1 	= "(ADMIN_RESPONSIBILITY_DETAIL_ID, ADMIN_DETAIL_ID, RESPOSIBILITY, CREATED_BY,CREATED_ON)";

						$insertVals1	= "(NULL, '$id', '$value', '$updated_by',NOW())";

						$insertSql1	= parent::insertData($tableName1, $tabFields1, $insertVals1);

						$exSql1		=  parent::execQuery($insertSql1);
					}
				}

				if ($exSql) {

					// update login details



					$tableName2 	= "admin_login_master";

					$setValues2 	= "ADMIN_STATUS='$status', ROLE_ID='$role', UPDATED_BY='$updated_by',UPDATED_ON=NOW()";

					$whereClause2	= " WHERE ADMIN_DETAIL_ID='$id'";

					$updateSql2	= parent::updateData($tableName2, $setValues2, $whereClause2);

					$exSql2		= parent::execQuery($updateSql2);

					if ($exSql2) {

						// update resposibilities



						if ($responsibility_str != '') {

							$responsibility_str = rtrim($responsibility_str, ',');

							$tableName3 	= "admin_view_mapping";

							$setValues3 	= "ADMIN_ID='$id',ROLE_ID='$role',RESPONSIBILTY_ID='$responsibility_str', UPDATED_BY='$updated_by',UPDATED_ON=NOW()";

							$whereClause3	= " WHERE ADMIN_ID='$id'";

							$updateSql3	= parent::updateData($tableName3, $setValues3, $whereClause3);

							$exSql3		= parent::execQuery($updateSql3);
						}



						parent::commit();

						$msg = UPDATE_USER_SUCCESS . " $first_name $last_name ";

						parent::add_activity("UPDATE_USER_SUCCESS", $msg);

						return true;
					} else {

						parent::rollback();

						$msg = UPDATE_USER_FAILED . " $first_name $last_name ";

						parent::add_activity("UPDATE_USER_FAILED", $msg);

						return false;
					}
				}
			}
		}
	}

	/* show user details 

	@param: int user_id

	@return mixed

	*/

	public function view_user($user_id)

	{

		$data = '';

		$user_id = parent::test($user_id);

		$sql = "SELECT A.*, B.USER_NAME,B.ADMIN_STATUS, B.ROLE_ID FROM admin_details_master A LEFT JOIN admin_login_master B ON A.ADMIN_DETAIL_ID=B.ADMIN_DETAIL_ID WHERE A.ADMIN_DETAIL_ID='$user_id' LIMIT 0,1";

		$res = parent::execQuery($sql);

		if ($res && $res->num_rows > 0) {

			$data = $res;
		}

		return $data;
	}

	/* show all the frontend users by list*/

	public function list_users_profiles()

	{

		$role_id = $_SESSION['role_id'];

		$output = '';

		$minDate = isset($_POST['minDate']) ? $_POST['minDate'] : '';

		$maxDate = isset($_POST['maxDate']) ? $_POST['maxDate'] : '';

		if ($minDate != '')

			$minDate = date('Y-m-d', strtotime($minDate));

		if ($maxDate != '')

			$maxDate = date('Y-m-d', strtotime($maxDate));



		$sql = "SELECT A.APP_USER_ID, A.APP_USER_DETAILS_ID,C.APP_USER_LOGIN_ID, CONCAT(A.FIRST_NAME,' ',A.LAST_NAME) AS NAME,D.USER_TYPE,  B.MOBILE,B.EMAIL,B.POSTALCODE,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE, C.ACTIVE FROM app_users A LEFT JOIN app_users_details B ON A.APP_USER_DETAILS_ID=B.APP_USERS_DETAIL_ID LEFT JOIN app_users_login_master C ON A.APP_USER_ID=C.APP_USER_ID LEFT JOIN app_users_type_master D ON A.USER_TYPE_ID=D.USER_TYPE_ID  WHERE 1";



		$where = "";

		if ($minDate != '' && $maxDate == '')

			$where .=  " AND A.CREATED_ON >= '$minDate'";

		if ($maxDate != '' && $minDate == '')

			$where .=  " AND A.CREATED_ON <= '$maxDate'";

		if ($maxDate != '' && $minDate != '')

			$where .=  " AND A.CREATED_ON BETWEEN '$minDate' AND '$maxDate'";

		$where .= " ORDER BY A.CREATED_ON DESC";



		$sql .= $where;

		$exc = parent::execQuery($sql);



		if ($exc->num_rows > 0) {

			while ($data = $exc->fetch_assoc()) {

				$APP_USER_ID 			= $data['APP_USER_ID'];

				$APP_USER_DETAILS_ID	= $data['APP_USER_DETAILS_ID'];

				$APP_USER_LOGIN_ID 		= $data['APP_USER_LOGIN_ID'];

				$NAME 					= $data['NAME'];



				$USER_TYPE 				= $data['USER_TYPE'];

				$MOBILE 				= $data['MOBILE'];

				$EMAIL 					= $data['EMAIL'];

				$POSTALCODE 			= $data['POSTALCODE'];

				$CREATED_DATE 			= $data['CREATED_DATE'];

				$ACTIVE 				= $data['ACTIVE'];

				if ($ACTIVE == 0)

					$ACTIVE = 'Inactive';

				elseif ($ACTIVE == 1)

					$ACTIVE = 'Active';

				//$action					= '<a href="page.php?p=update-user&id='.$ADMIN_DETAIL_ID.'">Edit</a>';



				$delete_action = '<a href="javascript:void(0);" id="del_' . $APP_USER_ID . '" onclick="deleteUser(this.id)">Delete</a>';



				$action					= '<a href="#">View</a> |' . $delete_action;



				$output .= '<tr class="odd gradeX">

					<td>' . $NAME . '</td>

					<td>' . $EMAIL . '</td>

					<td>' . $MOBILE . '</td>

					<td>' . $POSTALCODE . '</td>

					<td>' . $USER_TYPE . '</td>

					<td>' . $CREATED_DATE . '</td>

					<td class="center">' . $ACTIVE . '</td>

					<td class="center">' . $action . '</td>

				</tr>';
			}
		}

		return $output;
	}

	/* get users all table IDs using app_user_id*/

	public function getIDs($user_id)

	{

		$result = array();

		$sql = "SELECT A.APP_USERS_DETAIL_ID,B.APP_USER_ID,C.APP_USER_LOGIN_ID FROM app_users_details A LEFT JOIN  app_users B ON A.APP_USERS_DETAIL_ID=B.APP_USER_DETAILS_ID LEFT JOIN app_users_login_master C ON B.APP_USER_ID=C.APP_USER_ID WHERE B.APP_USER_ID= '$user_id'";

		$res = parent::execQuery($sql);

		if ($res) {

			if ($res->num_rows > 0) {

				while ($data = $res->fetch_assoc()) {

					$APP_USERS_DETAIL_ID = $data['APP_USERS_DETAIL_ID'];

					$APP_USER_ID		 = $data['APP_USER_ID'];

					$APP_USER_LOGIN_ID	 = $data['APP_USER_LOGIN_ID'];



					$result = array("APP_USERS_DETAIL_ID" => $APP_USERS_DETAIL_ID, "APP_USER_ID" => $APP_USER_ID, "APP_USER_LOGIN_ID" => $APP_USER_LOGIN_ID);



					/*

					array_push($result, "APP_USERS_DETAIL_ID"=>$APP_USERS_DETAIL_ID);

					array_push($result, "APP_USER_ID"=>$APP_USER_ID);

					array_push($result, "APP_USER_LOGIN_ID"=>$APP_USER_LOGIN_ID);

					*/
				}
			}
		}

		return $result;
	}

	/* delete the user */

	public function delete_user($user_id)

	{

		$res = '';

		$ids = $this->getIDs($user_id);

		if (!empty($ids)) {

			$APP_USERS_DETAIL_ID = $ids['APP_USERS_DETAIL_ID'];

			$APP_USER_ID		 = $ids['APP_USER_ID'];

			$APP_USER_LOGIN_ID	 = $ids['APP_USER_LOGIN_ID'];



			$sql1 = "DELETE FROM app_users_details WHERE APP_USERS_DETAIL_ID= '$APP_USERS_DETAIL_ID';";

			$sql2 = "DELETE FROM app_users  WHERE APP_USER_ID= '$APP_USER_ID';";

			$sql3 = "DELETE FROM app_users_login_master WHERE APP_USER_LOGIN_ID= '$APP_USER_LOGIN_ID';";

			$ex1 = parent::execQuery($sql1);

			$ex2 = parent::execQuery($sql2);

			$ex3 = parent::execQuery($sql3);

			if ($ex1 && $ex2 && $ex3) {

				return true;
			}
		}

		return false;
	}
}
