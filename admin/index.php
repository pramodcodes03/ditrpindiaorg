<?php
ini_set('display_errors', 1);
include('include/common/html_header.php');
if (!isset($_SESSION['user_login_id'])) {
	header('location:login.php');
}
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
?>
<?php
//include('include/common/header.php'); 
//include('include/common/headerIMS.php'); 
switch ($user_role) {
		/* ------------------------------------Admin--------------------------------- */
	case (1):
		include('include/common/headerIMS.php');
		include('include/view/admin/institute/instute.php');
		break;
		/* ------------------------------------Institute--------------------------------- */
	case (2):
		include('include/dashboard.php');
		break;

		/* ------------------------------------Institute--------------------------------- */
	case (8):

		$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
		$is_verified = $access->check_institute_verified($user_id);
		if ($is_verified) {
			include('include/common/headerIMS.php');
			include('include/view/franchise/dashboard/dashboard.php');
		} else {
			include('include/common/headerNotVerifiedFranchise.php');
			include('include/view/franchise/account/update_institute.php');
		}
		break;
		/* ------------------------------------Student--------------------------------- */
	case (4):
		include('include/common/headerIMS.php');
		include('include/view/student/dashboard/dashboard.php');
		break;

	case (3):
		include('include/common/headerIMS.php');
		include('include/view/employer/dashboard/dashboard.php');
		break;

	case (6):
		include('include/common/headerIMS.php');
		include('include/view/admin_staff/dashboard/dashboard.php');
		break;

	default:
		include('include/view/default/404.php');
		break;
}
include('include/common/footer.php'); ?>
</div>
</body>

</html>
<script>
	$("#myModal").modal('show');
</script>