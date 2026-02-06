<?php
session_start();
ob_start();

$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($action != '') {
	include('include/classes/database_results.class.php');
	include('include/classes/access.class.php');
	$db 	= new  database_results();
	$access = new  access();

	switch ($action) {
		case ('institute_report'):
			include_once('include/classes/institute.class.php');
			include_once('include/classes/reports.class.php');
			$reports = new reports();
			$institute = new institute();
			$datefrom 	= isset($_REQUEST['datefrom2']) ? $_REQUEST['datefrom2'] : date('d-m-Y', strtotime("-30 days"));;
			$dateto 	= isset($_REQUEST['dateto2']) ? $_REQUEST['dateto2'] : date('d-m-Y');
			$city	 	= isset($_REQUEST['city2']) ? $_REQUEST['city2'] : '';
			$institute_id = isset($_REQUEST['institute_id2']) ? $_REQUEST['institute_id2'] : '';

			$datefromEX = date('d_m_Y', strtotime($datefrom));
			$datetoEX = date('d_m_Y', strtotime($dateto));
			$filename = date('d_m_Y', strtotime($datefrom)) . '_to_' . date('d_m_Y', strtotime($dateto));
			header('Content-Type: application/excel');
			header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

			$cond = '';
			if ($city != '') {
				$cond .= " AND A.CITY='$city'";
			}
			if ($institute_id != '') {
				$cond .= " AND A.INSTITUTE_ID='$institute_id'";
			}
			$res = $institute->list_institute('', $cond);
			if ($res != '') {
				$srno = 1;
				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'INSTITUTE_CODE',
					'INSTITUTE_NAME',
					'CITY',
					'STATE',
					'ENQUIRY DITRP',
					'ENQUIRY NON-DITRP',
					'ADMISSION DITRP',
					'ADMISSION NONAICPE',
					'FEES COLLECTION DITRP',
					'FEES COLLECTION NONAICPE',
					'FEE BUSINESS DITRP',
					'FEE BUSINESS NONAICPE',
					'EXAM PENDING',
					'EXAM APPEARED',
					'CERTIFICATES ORDERED'
				);

				fputcsv($fp, $exportArr);
				$datefrom = date('Y-m-d', strtotime($datefrom));
				$dateto = date('Y-m-d', strtotime($dateto));
				while ($data = $res->fetch_assoc()) {
					$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
					$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
					$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
					$CITY_NAME = $data['CITY_NAME'];
					$STATE_NAME = $data['STATE_NAME'];
					$enqArr = $reports->getDistinctCoursesEnquiry($INSTITUTE_ID, $datefrom, $dateto);
					$admAICPE = $reports->getTotalAdmissionsCourse($INSTITUTE_ID, 1, $datefrom, $dateto);
					$admNONAICPE = $reports->getTotalAdmissionsCourse($INSTITUTE_ID, 2, $datefrom, $dateto);

					$feeBusinessAICPE = $reports->getTotalFeesBusinessCourse($INSTITUTE_ID, 1, $datefrom, $dateto);
					$feeBusinessNONAICPE = $reports->getTotalFeesBusinessCourse($INSTITUTE_ID, 2, $datefrom, $dateto);

					$feeCollectionAICPE = $reports->getTotalFeesCollectionCourse($INSTITUTE_ID, 1);
					$feeCollectionNONAICPE = $reports->getTotalFeesCollectionCourse($INSTITUTE_ID, 2);

					$pendingExam = $reports->getTotalExam($INSTITUTE_ID, 2);
					$appearedExam = $reports->getTotalExam($INSTITUTE_ID, 3);
					$totalCertificateOrder = $reports->getTotalCertificateOrder($INSTITUTE_ID);

					$exportArr = array(
						$srno,
						$INSTITUTE_CODE,
						$INSTITUTE_NAME,
						$CITY_NAME,
						$STATE_NAME,
						$enqArr['DITRP'],
						$enqArr['NON-DITRP'],
						$admAICPE,
						$admNONAICPE,
						$feeCollectionAICPE,
						$feeCollectionNONAICPE,
						$feeBusinessAICPE,
						$feeBusinessNONAICPE,
						$pendingExam,
						$appearedExam,
						$totalCertificateOrder
					);

					fputcsv($fp, $exportArr);

					$srno++;
				}
				fclose($fp);
			}

			break;
		case ('institute_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Institutes.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");
			//headers

			include_once('include/classes/institute.class.php');
			$institute = new institute();

			include_once('include/classes/student.class.php');
			$student = new student();

			$res = $institute->list_institute('', ' AND B.USER_ROLE=8');
			//print_r($res); exit();
			if ($res != '') {
				$srno = 1;

				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'INSTITUTE_CODE',
					'INSTITUTE_NAME',
					'INSTITUTE_OWNER_NAME',
					'DOB',
					'STUDENT_ADMISSION',
					'STUDENT_ENQUIRY',
					'MAIN WALLET',
					'COURIER WALLET',
					'ADDRESS1',
					'CITY',
					'STATE',
					'PINCODE',
					'EMAIL',
					'MOBILE',
					'USERNAME',
					'STATUS',
					'REGISTER_DATE',
					'VERIFIED',
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);

					$ACTIVE 			= ($data['ACTIVE'] == 1) ? 'Active' : 'In-Active';
					$VERIFIED 			= ($data['VERIFIED'] == 1) ? 'Verified' : 'Un-Verified';

					$PRIMEMEMBER 			= ($data['PRIMEMEMBER'] == 1) ? 'Yes' : 'No';

					$count = '';
					$cond21  = " AND INSTITUTE_ID = $INSTITUTE_ID";
					$count = $student->get_admission_count($cond21);
					$count_enquiry = 0;
					$count_enquiry = $student->get_admission_count_enquiry($cond21);

					$courier_wallet = 0;
					$res11 = $access->get_courier_wallet('', $INSTITUTE_ID, 8);
					if ($res11 != '') {
						while ($data11 = $res11->fetch_assoc()) {
							$courier_wallet = $data11['TOTAL_BALANCE'];
						}
					}
					$main_wallet = 0;
					$res111 = $access->get_wallet('', $INSTITUTE_ID, 8);
					if ($res111 != '') {
						while ($data111 = $res111->fetch_assoc()) {
							$main_wallet = $data111['TOTAL_BALANCE'];
						}
					}
					$CREATED_ON = date("d-m-Y", strtotime($CREATED_ON));

					$exportArr = array(
						$srno,
						$INSTITUTE_CODE,
						strtoupper($INSTITUTE_NAME),
						strtoupper($INSTITUTE_OWNER_NAME),
						$DOB,
						$count,
						$count_enquiry,
						$main_wallet,
						$courier_wallet,
						strtoupper($ADDRESS_LINE1),
						strtoupper($CITY),
						strtoupper($STATE_NAME),
						strtoupper($POSTCODE),
						$EMAIL,
						$MOBILE,
						$USER_NAME,
						$ACTIVE,
						$CREATED_ON,
						$VERIFIED
					);
					//$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;
			//wallet export file
		case ('wallet_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Transactions History.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");
			//headers

			include_once('include/classes/admin.class.php');
			$admin = new admin();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$search 	= isset($_POST['search']) ? $_POST['search'] : '';
			$wallet_id 	= isset($_REQUEST['wallet']) ? $_REQUEST['wallet'] : '';


			$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '';
			$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '';
			$paymentmode1 = isset($_REQUEST['paymentmode1']) ? $_REQUEST['paymentmode1'] : '';
			$cond = '';
			$history = $admin->get_recharge_history($paymentmode1, $wallet_id, $user_id, $user_role, $cond);
			arsort($history);
			//print_r($history);
			$walletres = $access->get_wallet('', '', '');

			if (!empty($history)) {
				$srno = 1;

				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'Transaction No',
					'Name',
					'Mode',
					'Status',
					'Transaction Type',
					'Amount',
					'Recharge Date'
				);
				fputcsv($fp, $exportArr);
				foreach ($history as $trans => $transArr) {
					if (is_array($transArr) && !empty($transArr)) {
						extract($transArr);
						$exportArr = array(
							$srno,
							$TRANSACTION_NO,
							strtoupper($USER_FULLNAME),
							strtoupper($PAYMENT_MODE),
							strtoupper($STATUS),
							strtoupper($TRANSACTION_TYPE),
							$AMOUNT,
							$CREATED_DATE
						);
						//$exportArr = array_map("utf8_decode", $exportArr);
						fputcsv($fp, $exportArr);
					}
					$srno++;
				}
				fclose($fp);
			}
			break;

			//student export
		case ('student_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Student Admission List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/student.class.php');
			$student = new student();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $student->list_student_direct_admission('', $user_id, '', '');
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'STUDENT_CODE',
					'STUDENT_FULL_NAME',
					'FATHER_NAME',
					'MOTHER_NAME',
					'COURSE_NAME',
					'MOBILE_NUMBER',
					'EMAIL',
					'ADDRESS',
					'CITY',
					'STATE',
					'PINCODE',
					'BATCH',
					'USERNAME',
					'WALLET_AMOUNT',
					'TOTAL_FEES',
					'PAID_FEES',
					'TOTAL_BALANCE_FEES',
					'REFFERAL_CODE',
					'REFFERAL_NAME'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);
					//print_r($data); exit();
					$course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
					$WALLET_AMOUNT = $db->get_institute_walletamount($STUDENT_ID, '4');
					$ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;
					$res1 = $student->total_payments($STUDENT_ID, '');
					if (!empty($res1)) {
						$ALL_COURSE_FEES = isset($res1['ALL_COURSE_FEES']) ? $res1['ALL_COURSE_FEES'] : 0;
						$TOTAL_FEES_PAID = isset($res1['TOTAL_FEES_PAID']) ? $res1['TOTAL_FEES_PAID'] : 0;
						$TOTAL_FEES_BALANCE = isset($res1['TOTAL_FEES_BALANCE']) ? $res1['TOTAL_FEES_BALANCE'] : 0;
					}
					$batch_name = '';
					if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
						$batch_name = $db->get_batchname($BATCH_ID);
					}
					$STUDENT_STATE = $db->get_state_name($STUDENT_STATE);
					$referal = '';
					if ($REFFERAL_CODE != '') {
						$referral_name = $db->get_refferar_name($REFFERAL_CODE);
						$referal = $referral_name['STUDENT_FNAME'] . ' ' . $referral_name['STUDENT_MNAME'] . ' ' . $referral_name['STUDENT_LNAME'];
					}

					$exportArr = array(
						$srno,
						$STUDENT_CODE,
						strtoupper($STUDENT_FULLNAME),
						strtoupper($STUDENT_MNAME),
						strtoupper($STUDENT_MOTHERNAME),
						strtoupper($course_name),
						$STUDENT_MOBILE,
						$STUDENT_EMAIL,
						$STUDENT_PER_ADD,
						$STUDENT_CITY,
						$STUDENT_STATE,
						$STUDENT_PINCODE,
						$batch_name,
						$USER_NAME,
						$WALLET_AMOUNT,
						$ALL_COURSE_FEES,
						$TOTAL_FEES_PAID,
						$TOTAL_FEES_BALANCE,
						$REFFERAL_CODE,
						$referal
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;

			//student export enquires
		case ('student_export_enquiry'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Student Enquiry List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/student.class.php');
			$student = new student();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $student->list_student_enquiry('', $user_id, '', '');
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'STUDENT_FULLNAME',
					'FATHER_NAME',
					'MOTHER_NAME',
					'COURSE_NAME',
					'MOBILE_NUMBER',
					'EMAIL',
					'ADDRESS',
					'CITY',
					'STATE',
					'PINCODE',
					'REFFERAL_CODE',
					'REFFERAL_NAME'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);
					//print_r($data); exit();
					$course_name = $db->get_inst_course_name($INSTRESTED_COURSE);
					$STUDENT_STATE = $db->get_state_name($STUDENT_STATE);
					$referal = '';
					if ($REFFERAL_CODE != '') {
						$referral_name = $db->get_refferar_name($REFFERAL_CODE);
						$referal = $referral_name['STUDENT_FNAME'] . ' ' . $referral_name['STUDENT_MNAME'] . ' ' . $referral_name['STUDENT_LNAME'];
					}

					$exportArr = array(
						$srno,
						strtoupper($STUDENT_FULLNAME),
						strtoupper($STUDENT_MNAME),
						strtoupper($STUDENT_MOTHERNAME),
						strtoupper($course_name),
						$STUDENT_MOBILE,
						$STUDENT_EMAIL,
						$STUDENT_PER_ADD,
						$STUDENT_CITY,
						$STUDENT_STATE,
						$STUDENT_PINCODE,
						$REFFERAL_CODE,
						$referal
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;

			//expense export
		case ('expense_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Expenses List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/expense.class.php');
			$expense = new expense();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $expense->list_expenses('', " AND INSTITUTE_ID = $user_id");
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'CATEGORY',
					'SUBCATEGORY',
					'ISSUE_NAME',
					'NAME_OF_PERSON',
					'AMOUNT',
					'EDATE',
					'VNO',
					'CBFNO',
					'REMARKS',
					'PAYMENT_MODE',
					'GSTNO'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);
					//print_r($data); exit();
					$exportArr = array(
						$srno,
						strtoupper($CATEGORYNAME),
						strtoupper($SUBCATEGORYNAME),
						strtoupper($ISSUE_NAME),
						strtoupper($NAME_OF_PERSON),
						$AMOUNT,
						$EDATE,
						$VNO,
						$CBFNO,
						$REMARKS,
						$PAYMENT_MODE,
						$GSTNO
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;

			//fees export
		case ('studentfees_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Student Fees List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/institute.class.php');
			$institute = new institute();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $institute->list_student_payments_upd('', '', $user_id, '', '');
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'Reciept No',
					'Date',
					'Student Name',
					'Course Name',
					'Total Course Fees',
					'Fees Paid',
					'Fees Balance'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);
					//print_r($data); exit();

					$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
					$FEES_PAID_ON = date("d-m-Y", strtotime($FEES_PAID_ON));
					$FEES_BALANCE = $TOTAL_COURSE_FEES - $FEES_PAID;

					$exportArr = array(
						$srno,
						$RECIEPT_NO,
						$FEES_PAID_ON,
						strtoupper($STUDENT_NAME),
						strtoupper($COURSE_NAME),
						$TOTAL_COURSE_FEES,
						$FEES_PAID,
						$FEES_BALANCE
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;

			//fees export history
		case ('studentfeesHistory_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Student Fees List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/institute.class.php');
			$institute = new institute();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $institute->list_student_payments_upd_history('', '', $user_id, '', '');
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'Date',
					'Student Name',
					'Course Name',
					'Total Course Fees',
					'Fees Paid',
					'Fees Balance'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);
					//print_r($data); exit();

					$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

					$exportArr = array(
						$srno,
						$FEES_PAID_ON,
						strtoupper($STUDENT_NAME),
						strtoupper($COURSE_NAME),
						$TOTAL_COURSE_FEES,
						$FEES_PAID,
						$FEES_BALANCE
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;

			//student export
		case ('franchise_enquiry_export'):
			header('Content-Encoding: UTF-8');
			header("Content-type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="Franchise Enquiry List.csv"');
			header("Pragma: no-cache");
			header("Expires: 0");

			include_once('include/classes/institute.class.php');
			$institute = new institute();
			$user_id = $_SESSION['user_id'];
			$user_role = $_SESSION['user_role'];

			$res = $institute->list_franchise_enquiry('', '');
			if ($res != '') {
				$srno = 1;


				$fp = fopen('php://output', 'w');
				$exportArr = array(
					'Sr',
					'Institute Name',
					'Owner Name',
					'Email Id',
					'Mobile',
					'State',
					'City',
					'Remark',
					'Date'
				);
				fputcsv($fp, $exportArr);
				while ($data = $res->fetch_assoc()) {
					extract($data);

					$exportArr = array(
						$srno,
						$instname,
						$owner_name,
						$emailid,
						$mobile_number,
						$STATE_NAME,
						$city,
						$remark,
						date("d-m-Y", strtotime($created_at))
					);
					$exportArr = array_map("utf8_decode", $exportArr);
					fputcsv($fp, $exportArr);
					$srno++;
				}

				fclose($fp);
			}
			break;
	}
}
