<?php
ob_clean();
include('include/common/html_header.php');
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '';
if (!isset($_SESSION['user_login_id'])) {
	header('location:login.php');
}

?>
<!-- <body onload="window.print();" > -->

<body>

	<?php
	switch ($page) {
		case ('print-student-certificate'):
			include('include/view/student/certificates/print_student_certificate.php');
			break;
		case ('print-hallticket'):
			include('include/view/institute/hallticket/print_hall_tickets.php');
			break;

		case ('print-hallticket-franchise'):
			if ($user_role == 3) {
				include('include/view/employer/hallticket/print_hall_tickets.php');
				break;
			} else {
				include('include/view/franchise/hallticket/print_hall_tickets.php');
				break;
			}

		default:
			header('location:index.php');
			exit;
	}

	ob_end_flush();
	?>

</body>

</html>