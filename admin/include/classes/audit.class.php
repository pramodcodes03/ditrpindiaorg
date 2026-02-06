<?php
include_once('database_results.class.php');
include_once('access.class.php');
$access = new access();
$db = new database_results();

class audit extends database_results
{
	public function list_audits()
	{
		$role_id = $_SESSION['role_id'];
		$output = '<thead>
					<tr>
						<th>Activity</th>
						<th>Message</th>
						<th>User</th>
						<th>Role</th>
						<th>IP</th>
						<!--<th>Agent</th>
						<th>Session ID</th>-->
						<th>Created On</th>						
					</tr>
				</thead>';
		$output .= '<tbody>';
		
		 $sql = "SELECT A.*,DATE_FORMAT(A.CREATED_ON,'%d-%m-%Y %h:%i %p') AS CREATED_DATE,DATE_FORMAT(A.UPDATED_ON,'%d-%m-%Y %h:%i %p') AS UPDATED_DATE FROM audit_master A WHERE 1 ORDER BY A.AUDIT_ID DESC";
		$exc = parent::execQuery($sql);
		
		if($exc->num_rows>0)
		{
				while($data = $exc->fetch_assoc())
				{
					$AUDIT_ID	 	= $data['AUDIT_ID'];
					$ACTIVITY 		= $data['ACTIVITY'];
					$USER_LOGIN_ID	= $data['USER_LOGIN_ID'];
					$USER_TYPE 		= $data['USER_TYPE'];
					$MESSAGE		= $data['MESSAGE'];
					$SESSION_ID		= $data['SESSION_ID'];
					$IP_ADDRESS		= $data['IP_ADDRESS'];
					$AGENT			= $data['AGENT'];
					$CREATED_BY 	= $data['CREATED_BY'];
					$CREATED_ON 	= $data['CREATED_DATE'];
					$UPDATED_BY 	= $data['UPDATED_BY'];
					$UPDATED_ON 	= $data['UPDATED_DATE'];				
					
					$action			= '<a href="page.php?p=update-post&id='.$AUDIT_ID.'">Edit</a>';
				
				$output.= '<tr class="odd gradeX">
					<td>'.$ACTIVITY .'</td>
					<td>'. $MESSAGE .'</td>
					<td>'. $CREATED_BY .'</td>
					<td>'. $USER_TYPE .'</td>
					<td>'. $IP_ADDRESS .'</td>
					<!--<td>'. $AGENT .'</td>
					<td>'. $SESSION_ID .'</td>-->
					<td>'. $CREATED_ON .'</td>
				</tr>';
				
				}
		}else{
			$output .='<tr><td colaspan="5"> No records at this moment.</td></tr>';
		}
		$output .= '</tbody>';
		return $output;	
	}	
}
?>