<?php	
	
	switch($user_role)
	{	
	//Admin
	case(1):
		include('include/common/navbars/nav_left_admin.php');
		break;
	//Institute
	case(2):	
		include('include/common/navbars/nav_left_institute.php');		
		break;	
	//franchise
	case(8):	
		include('include/common/navbars/nav_left_franchise.php');		
		break;	
	//Student
	case(4):
		include('include/common/navbars/nav_left_student.php');
		break;	
		
	//Employee
	case(3):	
		include('include/common/navbars/nav_left_staff.php');		
		break;
		
	//Admin Staff
	case(6):	
		include('include/common/navbars/nav_left_admin_staff.php');		
		break;	
	}
		
?>