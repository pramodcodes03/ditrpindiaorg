<?php 

	include('include/common/html_header.php'); 
	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
	$page = isset($_GET['page'])?$_GET['page']:'';
	if(!isset($_SESSION['user_login_id']))
	{
		header('location:login.php');
	}

?>
<body class="hold-transition skin-blue sidebar-mini" <?php if($page=="pay-online") echo 'onload="submitPayuForm()"';    if($page=="courier-pay-online") echo 'onload="submitPayuForm()"'; ?> >
<div class="wrapper">
<?php 
	
	include('include/common/headerIMS.php'); 
	switch($user_role)
	{
		/* ------------------------------------Super Admin--------------------------------- */
		case(1):			
			switch($page)
			{	
				case('Dashboard'):
					include('include/view/admin/dashboard/dashboard.php');
					break;	

				case('listInstitute'):
					include('include/view/admin/institute/instute.php');
					break;	
				case('student_list'):
					include('include/view/admin/institute/instute_student_view.php');
					break;	
				case('tbledit'):
					include('include/view/admin/institute/institute_add_remark.php');
					break;	
					
								
				/* ------------------------------Default------------------------ */
				default:
					include('include/pages/default/404.php');
					break;
			}
			break;
		/* ------------------------------------Institute--------------------------------- */
		case(2):		
			switch($page)
			{	
			    /* ------------------------------ Account ------------------------ */
				
				case('listRechargeRequest'):
					include('include/view/institute/rechargeoffers/list_recharge_request.php');
					break;
					
				case('updateRechargeRequest'):
					include('include/view/institute/rechargeoffers/update_recharge_request.php');
					break;
				
				case('listRechargeOffers'):
					include('include/view/institute/rechargeoffers/list.php');
					break;
					
				case('updateRechargeOffers'):
					include('include/view/institute/rechargeoffers/update.php');
					break;
				case('addRechargeOffers'):
					include('include/view/institute/rechargeoffers/add.php');
					break;
				
						/* ------------------------------Gallery------------------------ */
				case('listMarkeing'):
					include('include/view/institute/website/gallery/list_gallery.php');
					break;
				case('updateMarkeing'):
					include('include/view/institute/website/gallery/update_gallery.php');
					break;
				case('addMarkeing'):
					include('include/view/institute/website/gallery/add_gallery.php');
					break;
					
				// case('masterPassword'):
				// 	include('include/view/institute/institute/master_password.php');
				// 	break;	
					
				case('updateInstitute'):
					include('include/view/institute/account/update_institute.php');
					break;
					
					    /* ------------- Festival ------------------------ */
			    case('list-festival'):
					include('include/view/institute/festival/list.php');
					break;
				case('update-festival'):
					include('include/view/institute/festival/edit.php');
					break;
				case('add-festival'):
					include('include/view/institute/festival/add.php');
					break;

				/* -------------------------- ourProduct ------------------------- */
				case('viewProduct'):
					include('include/view/institute/product/list.php');
					break;
				case('updateProduct'):
					include('include/view/institute/product/update.php');
					break;
				case('addProduct'):
					include('include/view/institute/product/add.php');
					break;
                
                /* -------------------------- Seminar ------------------------- */
				case('listSeminar'):
					include('include/view/institute/seminar/list_seminar.php');
					break;
				case('updateSeminar'):
					include('include/view/institute/seminar/update_seminar.php');
					break;
				case('addSeminar'):
					include('include/view/institute/seminar/add_seminar.php');
					break;

				/* -------------------------- Seminar Student------------------------- */
				case('listSeminarStudent'):
					include('include/view/institute/seminar/student/list_student.php');
					break;
				case('updateSeminarStudent'):
					include('include/view/institute/seminar/student/update_student.php');
					break;
				case('addSeminarStudent'):
					include('include/view/institute/seminar/student/add_student.php');
					break;

				case('viewStudentCertificate'):
					include('include/view/institute/seminar/certificate/view_certificate.php');
					break;
				case('printStudentCertificate'):
					include('include/view/institute/seminar/certificate/print_certificate.php');
					break;

	           case('listServicesEnquiry'):
					include('include/view/institute/institute/list_services_enquiry.php');
					break;
					
				   
				/* -------------------------- Franchise ------------------------- */
				case('listFranchiseEnquiry'):
					include('include/view/institute/institute/list_franchise_enquiry.php');
					break;
					
				case('updateFranchiseEnquiry'):
					include('include/view/institute/institute/update_franchise_enquiry.php');
					break;
					
				case('listFranchise'):
					include('include/view/institute/institute/list_institutes.php');
					break;
				case('updateFranchise'):
					include('include/view/institute/institute/update_institute.php');
					break;
				case('addFranchise'):
					include('include/view/institute/institute/add_institute.php');
					break;

				/*IMS system */
				case('IMSDashboard'):						
					include('include/view/dashboard.php');
					break;
				case('IMSDashboardStudent'):						
					include('include/view/institute/dashboard/student_dashboard.php');
					break;

				//Admission Enquiry
				case('studentEnquiry'):
					include('include/view/institute/student/enquiries/list_student_enquiries.php');
					break;
				case('studentAddEnquiry'):
					include('include/view/institute/student/enquiries/add_student_enquiry.php');
					break;
				case('studentUpdateEnquiry'):
					include('include/view/institute/student/enquiries/update_student_enquiry.php');
					break;
           
				case('studentRegisterEnquiry'):
					include('include/view/institute/student/enquiries/register_student.php');
					break;

				//Direct Admission
				case('studentAdmission'):
					include('include/view/institute/student/direct_admission/list_student.php');
					break;
				case('studentReAdmission'):
					include('include/view/institute/student/direct_admission/readmission_student.php');
					break;
				case('studentAddAdmission'):
					include('include/view/institute/student/direct_admission/add_student.php');
					break;
				case('studentUpdateAdmission'):
					include('include/view/institute/student/direct_admission/update_student.php');
					break;

				case('viewResume'):
					include('include/view/institute/student/direct_admission/studentResume.php');
					break;
					
				//Fees Management One Page	

				case('listStudentFees'):
					include('include/view/institute/student/payments/list_student_payments.php');
					break;

				case('studentFees'):
					include('include/view/institute/student/payments/add_student_payment.php');
					break;

				case('studentAddFees'):
					include('include/view/institute/student/payments/add_student_payment.php');
					break;
				case('studentUpdateFees'):
					include('include/view/institute/student/payments/update_student_payment.php');
					break;
				case('viewStudentReceipt'):
					include('include/view/institute/student/payments/student_receipt.php');
					break;

				case('viewPaymentHistory'):
					include('include/view/institute/student/payments/view_payment_history.php');
					break;
					
				case('ourStudentFeesHistory'):
					include('include/view/institute/student/payments/list_student_payments_history.php');
					break;
	

				//Refferral Amount
				case('refferalAmount'):
					include('include/view/institute/refferal/add.php');
					break;

				//Student Forms
				case('viewStudentForm'):
					include('include/view/institute/student/view_student_form.php');
					break;
					
				case('viewStudentIdcard'):
					include('include/view/institute/student/view_student_idcard.php');
					break;

				//course Awards
				case('listAwardCategories'):					
					include('include/view/institute/award/list_award.php');
					break;
				case('addAwardCategories'):					
					include('include/view/institute/award/add_award.php');
					break;
				case('updateAwardCategories'):					
					include('include/view/institute/award/update_award.php');
					break;
				//institute plans
				case('listPlans'):
					include('include/view/institute/instituteplans/institute_plans.php');
					break;
				case('addPlans'):
					include('include/view/institute/instituteplans/add_institute_plans.php');
					break;
				case('updatePlans'):
					include('include/view/institute/instituteplans/update_institute_plans.php');
					break;

				//courses
				case('listCourse'):					
					include('include/view/institute/courses/list_courses.php');
					break;
				case('addCourse'):					
					include('include/view/institute/courses/add_course.php');
					break;
				case('updateCourse'):					
					include('include/view/institute/courses/update_course.php');
					break;

				//Exams
				case('listExams'):					
					include('include/view/institute/exams/list_exams.php');
					break;
				case('addExam'):					
					include('include/view/institute/exams/add_exam.php');
					break;
				case('updateExam'):					
					include('include/view/institute/exams/update_exam.php');
					break;

				//Question Bank
				case('listQueBank'):					
					include('include/view/institute/quebank/list_quebank.php');
					break;
				case('addQueBank'):					
					include('include/view/institute/quebank/add_quebank.php');
					break;
				case('updateQueBank'):					
					include('include/view/institute/quebank/update_quebank.php');
					break;
				case('viewQueBank'):					
					include('include/view/institute/quebank/view_quebank.php');
					break;
				case('addQuestion'):					
					include('include/view/institute/quebank/add_question.php');
					break;
				case('editQuestion'):					
					include('include/view/institute/quebank/edit_question.php');
					break;

				//Courses Multiple Subjects
				case('listCourseMultiSub'):					
					include('include/view/institute/courses_multi_sub/course/list_courses_multi_sub.php');
					break;
				case('addCourseMultiSub'):					
					include('include/view/institute/courses_multi_sub/course/add_course_multi_sub.php');
					break;
				case('updateCourseMultiSub'):					
					include('include/view/institute/courses_multi_sub/course/update_course_multi_sub.php');
					break;

				//Exam Multiple Subjects
				case('listExamsMultiSub'):					
					include('include/view/institute/courses_multi_sub/exam/list_exam_multi_sub.php');
					break;
				case('addExamMultiSub'):					
					include('include/view/institute/courses_multi_sub/exam/add_exam_multi_sub.php');
					break;
				case('updateExamMultiSub'):					
					include('include/view/institute/courses_multi_sub/exam/update_exam_multi_sub.php');
					break;

				//Questions Multiple Subjects
				case('listQueBankMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/list_quebank_multi_sub.php');
					break;
				case('addQueBankMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/add_quebank_multi_sub.php');
					break;
				case('updateQueBankMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/update_quebank_multi_sub.php');
					break;
				case('viewQueBankMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/view_quebank_multi_sub.php');
					break;
				case('addQuestionMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/add_question_multi_sub.php');
					break;
				case('editQuestionMultiSub'):					
					include('include/view/institute/courses_multi_sub/quebank/edit_question_multi_sub.php');
					break;

				//Exam Section
				case('resetExam'):
					include('include/view/institute/exam/list_student_exams.php');
					break;
				case('updateExams'):
					include('include/view/institute/exam/update_student_exam.php');
					break;
				case('addExams'):
					include('include/view/institute/exam/add_student_exam.php');
					break;
				case('examOTP'):
					include('include/view/institute/exam/list_student_exams_secrete_codes.php');
					break;	

				// offline marks update list practical exams results
				case('listPracticalExamResult'):
					include('include/view/institute/exam/practical/list_practical_exam_papers.php');
					break;
				case('addPracticalExamResult'):
					include('include/view/institute/exam/practical/add_practical_exam_result.php');
					break;
				case('updatePracticalExamResult'):
					include('include/view/institute/exam/practical/update_practical_exam_result.php');
					break;	

				// list practical exams results		
				case('listExamResults'):
					include('include/view/institute/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/institute/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/institute/exam/update_exam_results.php');

				//Hall Tickets
				// case('listHallticket'):
				// 	include('include/view/institute/hallticket/list_hall_ticket.php');
				// 	break;
				case('generateHallticket'):
					include('include/view/institute/hallticket/generate_hall_ticket.php');
					break;
				case('listHallticket'):
					include('include/view/institute/hallticket/list.php');
					break;
				case('printHallticket'):
					include('include/view/institute/hallticket/print_hall_tickets.php');
					break;

				//Marksheet & Certificates
				/* ------------------------------ Certificates ------------------------ */
				case('listExamResults'):
					include('include/view/institute/exam/list_exam_results.php');
					break;

				case('listRequestedCertificates'):
					include('include/view/institute/certificates/list_requested_certificates.php');
					break;
				case('printCertificate'):
					include('include/view/institute/certificates/print_certificates.php');
					break;
				case('viewStudentCertificate'):
					include('include/view/institute/certificates/view_student_certificate.php');
					break;
				case('printModifyCertificate'):
					include('include/view/institute/certificates/print_modify_certificate.php');
					break;
				case('printFranchiseCertificate'):
					include('include/view/institute/certificates/print_franchise_certificates.php');
					break;
					
				
				//order certificate list
				case('listOrderRequestedCertificates'):
					include('include/view/institute/certificates/list_order_certificate_request.php');
					break;
				case('printOrderCertificate'):
					include('include/view/institute/certificates/print_order_certificate.php');
					break;
				case('viewOrderStudentCertificate'):
					include('include/view/institute/certificates/view_order_student_certificate.php');
					break;
				case('printOrderModifyCertificate'):
					include('include/view/institute/certificates/print_order_modify_certificate.php');
					break;
				case('printOrderRequestedMarksheet'):
					include('include/view/institute/marksheets/print_order_marksheet.php');
					break;

				/*.....................Marksheet......................................*/
				case('listRequestedMarksheet'):
					include('include/view/institute/marksheets/list_req_marksheet.php');
					break;
				case('printRequestedMarksheet'):
					include('include/view/institute/marksheets/print_marksheet.php');
					break;
				case('printMarksheet'):
					include('include/view/institute/marksheets/print_Bulk_marksheet.php');
					break;
				case('printFranchiseAddress'):
					include('include/view/institute/certificates/print_franchise_address.php');
					break;

				/* ------------------------------Franchise Wallet------------------------ */
				case('rechargeFranchiseWallet'):
					include('include/view/institute/franchise_wallet/recharge_wallet.php');
					break;
				case('franchiseWallet'):
					include('include/view/institute/franchise_wallet/wallet.php');
					break;
				case('rechargeFranchiseHistory'):
					include('include/view/institute/franchise_wallet/recharge_history.php');
					break;			
						
				/* ------------------------------Student Wallet------------------------ */
				case('rechargeWallet'):
					include('include/view/institute/wallet/recharge_wallet.php');
					break;
				case('Wallet'):
					include('include/view/institute/wallet/wallet.php');
					break;
				case('rechargeHistory'):
					include('include/view/institute/wallet/recharge_history.php');
					break;

				case('ourRechargeHistory'):
					include('include/view/institute/wallet/our_recharge_history.php');
					break;

				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/institute/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/institute/helpsupport/reply_support.php');
					break;

				case('listSupportType'):
					include('include/view/institute/helpsupport/type/list_support_type.php');
					break;
				case('addSupportType'):
					include('include/view/institute/helpsupport/type/add_support_type.php');
					break;
				case('updateSupportType'):
					include('include/view/institute/helpsupport/type/update_support_type.php');
					break;

				case('listSupportCat'):
					include('include/view/institute/helpsupport/category/list_support_cat.php');
					break;
				case('addSupportCat'):
					include('include/view/institute/helpsupport/category/add_support_cat.php');
					break;
				case('updateSupportCat'):
					include('include/view/institute/helpsupport/category/update_support_cat.php');
					break;	

				//online classes
				case('listOnlineClasses'):
					include('include/view/institute/onlineclasses/list.php');
					break;
				case('addOnlineClasses'):
					include('include/view/institute/onlineclasses/add.php');
					break;
				case('updateOnlineClasses'):
					include('include/view/institute/onlineclasses/edit.php');
					break;	

				//Marquee IMS
				case('listMarqueeNotification'):
					include('include/view/institute/marquee/add.php');
					break;

				case('manageBackground'):
					include('include/view/institute/backgroundupload/add.php');
					break;

				//Advertise IMS
				case('listAdvertisement'):
					include('include/view/institute/advertise/list.php');
					break;
				case('addAdvertisement'):
					include('include/view/institute/advertise/add.php');
					break;
				case('updateAdvertisement'):
					include('include/view/institute/advertise/edit.php');
					break;	

				//Birthday List
				case('listBirthday'):
					include('include/view/institute/reports/bday_reports.php');
					break;

				//User Management
				case('listStaff'):
					include('include/view/institute/staff/list_staffs.php');
					break;
				case('addStaff'):
					include('include/view/institute/staff/add_staff.php');
					break;
				case('updateStaff'):
					include('include/view/institute/staff/update_staff.php');
					break;	

				//Batches 
				case('listBatches'):
					include('include/view/institute/batch/list_batch.php');
					break;
				case('addBatches'):
					include('include/view/institute/batch/add_batch.php');
					break;
				case('updateBatches'):
					include('include/view/institute/batch/update_batch.php');
					break;
					
				//Batch Details
				case('BatchDetails'):
					include('include/view/institute/batch/batch_details.php');
					break;

				//Attendance
				case('listMarksheet'):
					include('include/view/institute/Marksheet/list_marksheet.php');
					break;
				case('generateMarksheet'):
					include('include/view/institute/Marksheet/generate_marksheet.php');
					break;
					
				case('generateOfflineExam'):
					include('include/view/institute/exam/offline/generate_offline_exam.php');
					break;
					
				case('list-offline-exam-papers'):
					include('include/view/institute/exam/offline/list_offline_exam_papers.php');
					break;
				case('addOfflineExamResult'):
					include('include/view/institute/exam/offline/add_offline_exam_result.php');
					break;
				case('updateOfflineExamResult'):
					include('include/view/institute/exam/offline/update_offline_exam_result.php');
					break;		
			
				// list practical exams results				
				case('listExamResults'):
					include('include/view/institute/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/institute/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/institute/exam/update_exam_results.php');
					break;
				/* ------------------------------Certificates------------------------ */
				case('listRequestedCertificates'):
					include('include/view/institute/certificates/list_requested_certificates.php');
					break;
				case('print-certificate'):
						include('include/view/institute/certificates/print_certificates.php');
						break;

             
					break;
				case('printStudentMarksheet'):
					include('include/view/institute/marksheet/print_marksheet.php');
					break;					
				
				case('printPerformanceCertificateCover'):
					include('include/view/institute/certificates/print_performance_certificate_cover.php');
					break;	

				case('view-student-certificate'):
                	include('include/view/institute/certificates/view_student_certificate.php');
                	break;
                case('print-requested-marksheet'):
                	include('include/view/institute/inst_data/certificates/viewcertificatemarksheet/print_marksheet.php');
                	break;   
				case('print-modify-certificate'):
						include('include/view/institute/certificates/print_modify_certificate.php');
						break;  
				
				//Print Hard Copy
				case('certificatePrint'):
					include('include/view/institute/certificates/certificatePrint.php');
					break;
				case('marksheetPrint'):
					include('include/view/institute/inst_data/certificates/viewcertificatemarksheet/marksheetPrint.php');
					break;  
				
						
				//Attendance		
				case('Attendance'):
					include('include/view/institute/attendance/list.php');
					break;

				case('AttendanceReport'):
					include('include/view/institute/attendance/attendance_report.php');
					break;
					
				case('AttendanceStudentReport'):
					include('include/view/institute/attendance/attendance_student_report.php');
					break;

				case('addAttendance'):
					include('include/view/institute/attendance/add.php');
					break;
				case('editAttendance'):
					include('include/view/institute/attendance/update.php');
					break;
              			
				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/institute/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/institute/helpsupport/reply_support.php');
					break;
				/* ------------------------------Expenses------------------------ */
				case('listExpenses'):
					include('include/view/institute/expenses/listExpenses.php');
					break;
				case('addExpense'):
					include('include/view/institute/expenses/addExpense.php');
					break;
				case('updateExpense'):					
					include('include/view/institute/expenses/update_expense.php');
					break;
				case('listExpenseCategory'):
					include('include/view/institute/expenses/listExpenseCategory.php');
					break;
				case('listSubExpenseCategory'):
					include('include/view/institute/expenses/listSubExpenseCategory.php');
					break;
				case('addexpensetype'):
					include('include/view/institute/expenses/addexpensetype.php');
					break;
				case('updateexpensetype'):					
					include('include/view/institute/expenses/updateexpensetype.php');
					break;
				case('addexpensesubtype'):
					include('include/view/institute/expenses/addexpensesubtype.php');
					break;
				case('updateexpensesubtype'):					
					include('include/view/institute/expenses/updateexpensesubtype.php');
					break;
					
			

				/*---------- Old Certificate ----------*/
				case('oldCertificate'):
					include('include/view/institute/old_certificate/list.php');
					break;
				case('addOldCert'):
					include('include/view/institute/old_certificate/add.php');
					break;
				case('updateOldCertficate'):
					include('include/view/institute/old_certificate/edit.php');
					break;

				case('previewCertificate'):
					include('include/view/institute/cert_preview/view_student_certificate.php');
					break;

				case('previewMarksheet'):
					include('include/view/institute/cert_preview/print_marksheet.php');
					break;

				case('previewAdmissionForm'):
					include('include/view/institute/cert_preview/view_student_form.php');
					break;

				case('previewIdcard'):
					include('include/view/institute/cert_preview/view_student_idcard.php');
					break;

				case('previewHallTicket'):
					include('include/view/institute/cert_preview/print_hall_tickets.php');
					break;

				case('previewFeesReceipt'):
					include('include/view/institute/cert_preview/student_receipt.php');
					break;
				
				case('previewFranchiseCertificate'):
					include('include/view/institute/cert_preview/view_franchise_certificate.php');
					break;
				

				//Typing Courses
				case('listTypingCourses'):					
					include('include/view/institute/course_typing/list_courses_typing.php');
					break;
				case('addTypingCourses'):					
					include('include/view/institute/course_typing/add_course_typing.php');
					break;
				case('updateTypingCourses'):					
					include('include/view/institute/course_typing/update_course_typing.php');
					break;	

				case('listExamsTypingCourses'):					
					include('include/view/institute/course_typing_exam/list_exam.php');
					break;
				case('addExamTypingCourses'):					
					include('include/view/institute/course_typing_exam/add_exam.php');
					break;
				case('updateExamTypingCourses'):					
					include('include/view/institute/course_typing_exam/update_exam.php');
					break;
					
				case('previewTypingMarksheet'):
					include('include/view/institute/cert_preview/view_typing_marksheet.php');
					break;

				case('courierWallet'):
					include('include/view/institute/courier_wallet/courier_wallet.php');
					break;
				case('courierWalletHistory'):
					include('include/view/institute/courier_wallet/courier_recharge_history.php');
					break;
				case('courierWalletRecharge'):
					include('include/view/institute/courier_wallet/courier_recharge_wallet.php');
					break;

				/* ------------------------------Default------------------------ */
				default:
					include('include/view/default/404.php');
					break;
			}
			break;
		case(8):
			/* ------------------------------------Franchise--------------------------------- */
				
			switch($page)
			{
			    /* ------------------------------ Wallet ------------------------ */
				case('pay-online'):
					include('include/view/franchise/franchise_wallet/payonline.php');
					break;
					
				case('courier-pay-online'):
					include('include/view/franchise/courier_wallet/courier_payonline.php');
					break;
					
			    case('viewBirthdayCard'):
					include('include/view/franchise/student/direct_admission/birthdayCard.php');
					break;
					
			    case('listTeacher'):
					include('include/view/franchise/teacher/list.php');
					break;
					
				case('addTeacher'):
					include('include/view/franchise/teacher/add.php');
					break;
					
				case('updateTeacher'):
					include('include/view/franchise/teacher/update.php');
					break;
					
				case('viewTeacher'):
					include('include/view/franchise/teacher/view.php');
					break;
					
			    case('listRechargeRequest'):
					include('include/view/franchise/recharge_request/list.php');
					break;
				case('addRechargeRequest'):
					include('include/view/franchise/recharge_request/add.php');
					break;
				case('updateRechargeRequest'):
					include('include/view/franchise/recharge_request/update.php');
					break;
					
			     case('list-festival'):
					include('include/view/franchise/festival/list.php');
					break;
			    
			    case('print-performance-certificate-cover'):
					include('include/view/franchise/certificates/print_performance_certificate_cover.php');
					
					break;
					
				case('listMarkeing'):
					include('include/view/franchise/marketing/list_gallery.php');
					break;
				case('updateMarkeing'):
					include('include/view/franchise/marketing/update_gallery.php');
	            break;

				/* ------------------------------ Account ------------------------ */
				case('updateInstitute'):
					include('include/view/franchise/account/update_institute.php');
					break;	

				/*IMS system */
				case('IMSDashboard'):						
					include('include/view/franchise/dashboard/dashboard.php');
					break;

				case('IMSDashboardStudent'):						
					include('include/view/franchise/dashboard/student_dashboard.php');
					break;

				/* ------------------------------Courses------------------------ */
				
				case('listCourses'):
					include('include/view/franchise/courses/ditrp/list_courses.php');
					break;
				case('updateCourses'):
					include('include/view/franchise/courses/ditrp/update_course.php');
					break;
				case('addCourses'):
					include('include/view/franchise/courses/ditrp/add_course.php');
					break;	
					
				//for multi subjects courses
				case('listCoursesMultiSub'):
					include('include/view/franchise/courses/ditrp/multi_sub/list_courses_multi_sub.php');
					break;
				case('updateCoursesMultiSub'):
					include('include/view/franchise/courses/ditrp/multi_sub/update_course_multi_sub.php');
					break;
				case('addCoursesMultiSub'):
					include('include/view/franchise/courses/ditrp/multi_sub/add_course_multi_sub.php');
					break;	
				//course subjects add and remove
				case('addCoursesMultiSubSubjects'):
					include('include/view/franchise/courses/ditrp/multi_sub/add_course_subjects_multi_sub.php');
					break;	

				//for typing courses
				case('listCoursesTyping'):
					include('include/view/franchise/courses/ditrp/typing/list_courses_typing.php');
					break;
				case('updateCoursesTyping'):
					include('include/view/franchise/courses/ditrp/typing/update_course_typing.php');
					break;
				case('addCoursesTyping'):
					include('include/view/franchise/courses/ditrp/typing/add_course_typing.php');
					break;

				//course subjects add and remove
				case('addCoursesTypingSubjects'):
					include('include/view/franchise/courses/ditrp/typing/add_course_subjects_typing.php');
					break;

				//Admission Enquiry
				case('studentEnquiry'):
					include('include/view/franchise/student/enquiries/list_student_enquiries.php');
					break;
				case('studentAddEnquiry'):
					include('include/view/franchise/student/enquiries/add_student_enquiry.php');
					break;
				case('studentUpdateEnquiry'):
					include('include/view/franchise/student/enquiries/update_student_enquiry.php');
					break;

				case('studentRegisterEnquiry'):
					include('include/view/franchise/student/enquiries/register_student.php');
					break;

				//Direct Admission
				case('studentAdmission'):
					include('include/view/franchise/student/direct_admission/list_student.php');
					break;
				case('studentReAdmission'):
					include('include/view/franchise/student/direct_admission/readmission_student.php');
					break;
				case('studentAddAdmission'):
					include('include/view/franchise/student/direct_admission/add_student.php');
					break;
				case('studentUpdateAdmission'):
					include('include/view/franchise/student/direct_admission/update_student.php');
					break;
				case('viewResume'):
					include('include/view/franchise/student/direct_admission/studentResume.php');
					break;

				//Fees Management One Page	
				case('listStudentFees'):
					include('include/view/franchise/student/payments/list_student_payments.php');
					break;

				case('studentFees'):
					include('include/view/franchise/student/payments/add_student_payment.php');
					break;

				case('studentAddFees'):
					include('include/view/franchise/student/payments/add_student_payment.php');
					break;
				case('studentUpdateFees'):
					include('include/view/franchise/student/payments/update_student_payment.php');
					break;
				case('viewStudentReceipt'):
					include('include/view/franchise/student/payments/student_receipt.php');
					break;
				
				case('ourStudentFeesHistory'):
					include('include/view/franchise/student/payments/list_student_payments_history.php');
					break;
					
				case('viewPaymentHistory'):
					include('include/view/franchise/student/payments/view_payment_history.php');
					break;

				//Refferral Amount
				case('refferalAmount'):
					include('include/view/franchise/refferal/add.php');
					break;

				//Student Forms
				case('viewStudentForm'):
					include('include/view/franchise/student/view_student_form.php');
					break;
					
				case('viewStudentIdcard'):
					include('include/view/franchise/student/view_student_idcard.php');
					break;				

				//Exam Section
				case('resetExam'):
					include('include/view/franchise/exam/list_student_exams.php');
					break;
				case('updateExams'):
					include('include/view/franchise/exam/update_student_exam.php');
					break;
				case('addExams'):
					include('include/view/franchise/exam/add_student_exam.php');
					break;
				case('examOTP'):
					include('include/view/franchise/exam/list_student_exams_secrete_codes.php');
					break;	

				// offline marks update list practical exams results
				case('listPracticalExamResult'):
					include('include/view/franchise/exam/practical/list_practical_exam_papers.php');
					break;
				case('addPracticalExamResult'):
					include('include/view/franchise/exam/practical/add_practical_exam_result.php');
					break;
				case('updatePracticalExamResult'):
					include('include/view/franchise/exam/practical/update_practical_exam_result.php');
					break;	

				// list practical exams results		
				case('listExamResults'):
					include('include/view/franchise/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/franchise/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/franchise/exam/update_exam_results.php');

				//Hall Tickets
				// case('listHallticket'):
				// 	include('include/view/institute/hallticket/list_hall_ticket.php');
				// 	break;
				case('generateHallticket'):
					include('include/view/franchise/hallticket/generate_hall_ticket.php');
					break;
				case('listHallticket'):
					include('include/view/franchise/hallticket/list.php');
					break;
				case('printHallticket'):
					include('include/view/franchise/hallticket/print_hall_tickets.php');
					break;

				//Marksheet & Certificates
				/* ------------------------------ Certificates ------------------------ */
				case('listExamResults'):
					include('include/view/franchise/exam/list_exam_results.php');
					break;

				case('listRequestedCertificates'):
					include('include/view/franchise/certificates/list_requested_certificates.php');
					break;
				case('printCertificate'):
					include('include/view/franchise/certificates/print_certificates.php');
					break;
				case('viewStudentCertificate'):
					include('include/view/franchise/certificates/list_requested_certificates.php');
					break;
				case('printModifyCertificate'):
					include('include/view/franchise/certificates/print_modify_certificate.php');
					break;
				case('printFranchiseCertificate'):
					include('include/view/franchise/certificates/print_franchise_certificates.php');
					break;

				//order certficate

				case('orderStudentCertificate'):
					include('include/view/franchise/certificates/list_order_certificate.php');
					break;
				case('viewOrderStudentCertificate'):
					include('include/view/franchise/certificates/view_order_certificate.php');
					break;

				/*.....................Marksheet......................................*/
				case('listRequestedMarksheet'):
					include('include/view/franchise/marksheets/list_req_marksheet.php');
					break;
				case('printRequestedMarksheet'):
					include('include/view/franchise/marksheets/print_marksheet.php');
					break;
				case('printMarksheet'):
					include('include/view/franchise/marksheets/print_Bulk_marksheet.php');
					break;
				case('printFranchiseAddress'):
					include('include/view/franchise/certificates/print_franchise_address.php');
					break;
						
				/* ------------------------------Wallet------------------------ */
				case('rechargeWallet'):
					include('include/view/franchise/wallet/recharge_wallet.php');
					break;
				case('Wallet'):
					include('include/view/franchise/wallet/wallet.php');
					break;
				case('rechargeHistory'):
					include('include/view/franchise/wallet/recharge_history.php');
					break;
				
				case('ourRechargeHistory'):
					include('include/view/franchise/wallet/our_recharge_history.php');
					break;

				/* ------------------------------Franchise Wallet------------------------ */
				case('rechargeWalletFranchise'):
					include('include/view/franchise/franchise_wallet/recharge_wallet.php');
					break;
				case('WalletFranchise'):
					include('include/view/franchise/franchise_wallet/wallet.php');
					break;
				case('rechargeHistoryFranchise'):
					include('include/view/franchise/franchise_wallet/recharge_history.php');
					break;
				
				case('ourRechargeHistoryFranchise'):
					include('include/view/franchise/franchise_wallet/our_recharge_history.php');
					break;

				
				/* ------------------------------Courier Wallet------------------------ */
				case('rechargeWalletCourier'):
					include('include/view/franchise/courier_wallet/courier_payonline.php');
					break;
				case('WalletCourier'):
					include('include/view/franchise/courier_wallet/courier_wallet.php');
					break;
				case('rechargeHistoryCourier'):
					include('include/view/franchise/courier_wallet/courier_recharge_history.php');
					break;			
				
				
				
					/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/franchise/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/franchise/helpsupport/reply_support.php');
					break;
					
				case('listAdminSupport'):
					include('include/view/franchise/helpsupport/list_admin_support.php');
					break;
				case('replyAdminSupport'):
					include('include/view/franchise/helpsupport/reply_admin_support.php');
					break;
				case('addAdminSupport'):
					include('include/view/franchise/helpsupport/add_admin_support.php');
					break;

				case('listSupportType'):
					include('include/view/franchise/helpsupport/type/list_support_type.php');
					break;
				case('addSupportType'):
					include('include/view/franchise/helpsupport/type/add_support_type.php');
					break;
				case('updateSupportType'):
					include('include/view/franchise/helpsupport/type/update_support_type.php');
					break;

				case('listSupportCat'):
					include('include/view/franchise/helpsupport/category/list_support_cat.php');
					break;
				case('addSupportCat'):
					include('include/view/franchise/helpsupport/category/add_support_cat.php');
					break;
				case('updateSupportCat'):
					include('include/view/franchise/helpsupport/category/update_support_cat.php');
					break;	

				//online classes
				case('listOnlineClasses'):
					include('include/view/franchise/onlineclasses/list.php');
					break;
				case('addOnlineClasses'):
					include('include/view/franchise/onlineclasses/add.php');
					break;
				case('updateOnlineClasses'):
					include('include/view/franchise/onlineclasses/edit.php');
					break;	

				//Marquee IMS
				case('listMarqueeNotification'):
					include('include/view/franchise/marquee/add.php');
					break;

				case('manageBackground'):
					include('include/view/franchise/backgroundupload/add.php');
					break;

				//Advertise IMS
				case('listAdvertisement'):
					include('include/view/franchise/advertise/list.php');
					break;
				case('addAdvertisement'):
					include('include/view/franchise/advertise/add.php');
					break;
				case('updateAdvertisement'):
					include('include/view/franchise/advertise/edit.php');
					break;	

				//Birthday List
				case('listBirthday'):
					include('include/view/franchise/reports/bday_reports.php');
					break;

				//User Management
				case('listStaff'):
					include('include/view/franchise/staff/list_staffs.php');
					break;
				case('addStaff'):
					include('include/view/franchise/staff/add_staff.php');
					break;
				case('updateStaff'):
					include('include/view/franchise/staff/update_staff.php');
					break;	

				//Batches 
				case('listBatches'):
					include('include/view/franchise/batch/list_batch.php');
					break;
				case('addBatches'):
					include('include/view/franchise/batch/add_batch.php');
					break;
				case('updateBatches'):
					include('include/view/franchise/batch/update_batch.php');
					break;
					
				//Batch Details
				case('BatchDetails'):
					include('include/view/franchise/batch/batch_details.php');
					break;

				//Attendance
				case('listMarksheet'):
					include('include/view/franchise/Marksheet/list_marksheet.php');
					break;
				case('generateMarksheet'):
					include('include/view/franchise/Marksheet/generate_marksheet.php');
					break;
					
				case('generateOfflineExam'):
					include('include/view/franchise/exam/offline/generate_offline_exam.php');
					break;
					
				case('list-offline-exam-papers'):
					include('include/view/franchise/exam/offline/list_offline_exam_papers.php');
					break;
				case('addOfflineExamResult'):
					include('include/view/franchise/exam/offline/add_offline_exam_result.php');
					break;
				case('updateOfflineExamResult'):
					include('include/view/franchise/exam/offline/update_offline_exam_result.php');
					break;		
			
				// list practical exams results				
				case('listExamResults'):
					include('include/view/franchise/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/franchise/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/franchise/exam/update_exam_results.php');
					break;
				/* ------------------------------Certificates------------------------ */
				case('listRequestedCertificates'):
					include('include/view/franchise/certificates/list_requested_certificates.php');
					break;
				case('print-certificate'):
						include('include/view/franchise/certificates/print_certificates.php');
						break;

                
					break;
				case('printStudentMarksheet'):
					include('include/view/franchise/marksheet/print_marksheet.php');
					break;					
				
				case('printPerformanceCertificateCover'):
					include('include/view/franchise/certificates/print_performance_certificate_cover.php');
					break;	

				case('view-student-certificate'):
                	include('include/view/franchise/certificates/view_student_certificate.php');
                	break;
                case('print-requested-marksheet'):
                	include('include/view/franchise/inst_data/certificates/viewcertificatemarksheet/print_marksheet.php');
                	break;   
				case('print-modify-certificate'):
						include('include/view/franchise/certificates/print_modify_certificate.php');
						break;  
				
				//Print Hard Copy
				case('certificatePrint'):
					include('include/view/franchise/certificates/certificatePrint.php');
					break;
				case('marksheetPrint'):
					include('include/view/franchise/inst_data/certificates/viewcertificatemarksheet/marksheetPrint.php');
					break;  
				
						
				//Attendance		
				case('Attendance'):
					include('include/view/franchise/attendance/list.php');
					break;

				case('AttendanceReport'):
					include('include/view/franchise/attendance/attendance_report.php');
					break;
					
				case('AttendanceStudentReport'):
					include('include/view/franchise/attendance/attendance_student_report.php');
					break;

				case('addAttendance'):
					include('include/view/franchise/attendance/add.php');
					break;
				case('editAttendance'):
					include('include/view/franchise/attendance/update.php');
					break;
              			
				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/franchise/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/franchise/helpsupport/reply_support.php');
					break;
				/* ------------------------------Expenses------------------------ */
				case('listExpenses'):
					include('include/view/franchise/expenses/listExpenses.php');
					break;
				case('addExpense'):
					include('include/view/franchise/expenses/addExpense.php');
					break;
				case('updateExpense'):					
					include('include/view/franchise/expenses/update_expense.php');
					break;
				case('listExpenseCategory'):
					include('include/view/franchise/expenses/listExpenseCategory.php');
					break;
				case('listSubExpenseCategory'):
					include('include/view/franchise/expenses/listSubExpenseCategory.php');
					break;
				case('addexpensetype'):
					include('include/view/franchise/expenses/addexpensetype.php');
					break;
				case('updateexpensetype'):					
					include('include/view/franchise/expenses/updateexpensetype.php');
					break;
				case('addexpensesubtype'):
					include('include/view/franchise/expenses/addexpensesubtype.php');
					break;
				case('updateexpensesubtype'):					
					include('include/view/franchise/expenses/updateexpensesubtype.php');
					break;
					
				/*---------- Expense Ends Here ----------*/				

				case('previewAdmissionForm'):
					include('include/view/franchise/cert_preview/view_student_form.php');
					break;

				case('previewIdcard'):
					include('include/view/franchise/cert_preview/view_student_idcard.php');
					break;

				case('previewHallTicket'):
					include('include/view/franchise/cert_preview/print_hall_tickets.php');
					break;

				case('previewFeesReceipt'):
					include('include/view/franchise/cert_preview/student_receipt.php');
					break;
				
				
				case('previewSingleMarksheet'):
					include('include/view/franchise/cert_preview/previewSingleMarksheet.php');
					break;
					
				case('previewMultipleMarksheet'):
					include('include/view/franchise/cert_preview/previewMultipleMarksheet.php');
					break;
					
				case('previewTypingMarksheet'):
					include('include/view/franchise/cert_preview/previewTypingMarksheet.php');
					break;
				/* ------------------------------Default------------------------ */
				default:
					include('include/view/default/404.php');
					break;
			}
			break;

		
		case(4):
		/* ------------------------------------Student--------------------------------- */
			
			switch($page)
			{	
				/*IMS system */
				case('IMSDashboard'):					
					include('include/view/student/dashboard/dashboard.php');
					break;

				//Birthday List
				case('listBirthday'):
					include('include/view/student/birthday/bday_reports.php');
					break;
				
				//Advertise List
				case('listAdvertise'):
					include('include/view/student/advertise/list.php');
					break;

				//Institute Details
				case('myInstituteDetails'):
					include('include/view/student/institute/institute_details.php');
					break;

				//online classes
				case('listOnlineClasses'):
					include('include/view/student/onlineclasses/list.php');
					break;

				//student Details
				case('studentDetails'):
					include('include/view/student/account/view_student.php');
					break;
				case('updateStudent'):
					include('include/view/student/account/update_student.php');
					break;

				//Student Forms
				case('viewStudentForm'):
					include('include/view/student/courses/view_student_form.php');
					break;
					
				case('viewStudentIdcard'):
					include('include/view/student/courses/view_student_idcard.php');
					break;

				case('viewResume'):
					include('include/view/student/account/studentResume.php');
					break;


			
				//course details
				case('myCoursesList'):
					include('include/view/student/courses/mycourseslist.php');
					break;
				case('coursesDetails'):
					include('include/view/student/courses/course_details.php');
					break;

				case('allCoursesList'):
					include('include/view/student/courses/allcourseslist.php');
					break;
				
				case('purchaseCourse'):
					include('include/view/student/courses/course_purchase.php');
					break;

				//demo Exam
				//final Exam
				//purchase course

				//wallet
				case('pay-online'):
					include('include/view/student/wallet/payonline.php');
					break;

				case('rechargeWallet'):
					include('include/view/student/wallet/recharge_wallet.php');
					break;
				case('Wallet'):
					include('include/view/student/wallet/wallet.php');
					break;
				case('rechargeHistory'):
					include('include/view/student/wallet/recharge_history.php');
					break;
				case('refferalAmount'):
					include('include/view/student/wallet/recharge_history.php');
					break;

				case('myResume'):
					include('include/view/student/wallet/wallet.php');
					break;

				//List Exam Results

				case('listExamResults'):
					include('include/view/student/exams/list_student_exams.php');
					break;

				case('listDemoExamResults'):
					include('include/view/student/exams/list_demo_exams_result.php');
					break;
					
				case('viewDemoResult'):
					include('include/view/student/exams/view_demo_paper.php');
					break;

				/* ------------------------------ Certificates -------------------------------- */
				case('viewCertificate'):
					include('include/view/student/certificates/print_student_certificate.php');
					break;
				case('viewMarksheet'):
					include('include/view/student/certificates/print_student_marksheet.php');
					break;

				/* ------------------------- Student Payments ---------------------------- */
				case('feesDetails'):
					include('include/view/student/payments/list_student_payments.php');
					break;

				case('viewStudentReceipt'):
					include('include/view/student/payments/student_receipt.php');
					break;

					// case('studentFees'):
					// 	include('include/view/institute/student/payments/add_student_payment.php');
					// 	break;

				case('studentPaymentsDetails'):
					include('include/view/student/payments/list_student_payments.php');
					break;
				//Notification

				//Job Updates
				case('listJobUpdates'):
					include('include/view/student/jobs/list_job_updates.php');
					break;	
				case('jobDetails'):
					include('include/view/student/jobs/list_job_updates.php');
					break;
				case('jobEnquiry'):
					include('include/view/student/jobs/list_job_updates.php');
					break;

				//attendance
				case('listAttendance'):
					include('include/view/student/attendance/list.php');
					break;
			
				
					
				case('showAttendanceCourse'):
					include('include/view/student/jobs/list_job_updates.php');
					break;

				//products
				case('listProducts'):
					include('include/view/student/jobs/list_job_updates.php');
					break;
				case('buyProducts'):
					include('include/view/student/jobs/list_job_updates.php');
					break;
				case('myPurchasedProducts'):
					include('include/view/student/jobs/list_job_updates.php');
					break;

				/* ------------------------------Student Courses Info---------------------- */
				case('list-student-courses'):
					include('include/view/student/courses/list_student_courses.php');
					break;
				case('studentCoursesDetails'):
					include('include/view/student/courses/list_student_course_details.php');
					break;
				/* ------------------------- Student Payments ---------------------------- */
				case('list-student-payments'):
					include('include/view/student/payments/list_student_payments.php');
					break;				
				/* ------------------------------Exam------------------------------------ */
				case('list-exams'):
					include('include/view/student/exams/list_student_exams.php');
					break;	
				case('download-offline-papers'):
					include('include/view/student/exams/download_offline_papers.php');
					break;
				case('print-offline-papers'):
					include('include/view/student/exams/print_offline_paper.php');
					break;	

				/* ------------------------------Account---------------------------------- */				
				case('update-student'):
					include('include/view/student/account/update_student.php');
					break;
				case('change-password'):
					include('include/view/student/account/change_password.php');
					break;
				case('generate-resume'):
					include('include/view/student/account/generate_resume.php');
					break;					
				case('view-resume'):
					include('include/view/student/account/view_resume.php');
					break;
				case('view-student'):
					include('include/view/student/account/view_student.php');
					break;
				/* ------------------------------Storage---------------------------------- */				
				case('list-student-storage'):
					include('include/view/student/storage/list_student_storage.php');
					break;
				case('download-files'):
					include('include/controller/student/storage/download.php');
					break;			
				/* ------------------------------ Jobs -------------------------------- */
				case('list-job-updates'):
					include('include/view/student/jobs/list_job_updates.php');
					break;					
				/* ------------------------------ Certificates -------------------------------- */
				case('print-student-certificate'):
					include('include/view/student/certificates/print_student_certificate.php');
					break;
				case('print-student-marksheet'):
					include('include/view/student/certificates/print_student_marksheet.php');
					break;

				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/student/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/student/helpsupport/reply_support.php');
					break;

				case('addSupport'):
					include('include/view/student/helpsupport/add_support.php');
					break;
				
				/* ------------------------------Default---------------------------------- */
				default:
					include('include/view/default/404.php');
					break;
			}
			break;

		/* ------------------------------------Institute Employee--------------------------------- */
		case(3):		
			switch($page)
			{	
			case('viewBirthdayCard'):
					include('include/view/employer/student/direct_admission/birthdayCard.php');
					break;
					
			    case('listTeacher'):
					include('include/view/employer/teacher/list.php');
					break;
					
				case('addTeacher'):
					include('include/view/employer/teacher/add.php');
					break;
					
				case('updateTeacher'):
					include('include/view/employer/teacher/update.php');
					break;
					
				case('viewTeacher'):
					include('include/view/employer/teacher/view.php');
					break;
					
			    case('listRechargeRequest'):
					include('include/view/employer/recharge_request/list.php');
					break;
				case('addRechargeRequest'):
					include('include/view/employer/recharge_request/add.php');
					break;
				case('updateRechargeRequest'):
					include('include/view/employer/recharge_request/update.php');
					break;
					
			     case('list-festival'):
					include('include/view/employer/festival/list.php');
					break;
			    
			    case('print-performance-certificate-cover'):
					include('include/view/employer/certificates/print_performance_certificate_cover.php');
					
					break;
					
				case('listMarkeing'):
					include('include/view/employer/marketing/list_gallery.php');
					break;
				case('updateMarkeing'):
					include('include/view/employer/marketing/update_gallery.php');
	            break;

				/* ------------------------------ Account ------------------------ */
				case('updateInstitute'):
					include('include/view/employer/account/update_institute.php');
					break;	

				/*IMS system */
				case('IMSDashboard'):						
					include('include/view/employer/dashboard/dashboard.php');
					break;

				case('IMSDashboardStudent'):						
					include('include/view/employer/dashboard/student_dashboard.php');
					break;

				/* ------------------------------Courses------------------------ */
				case('listCourses'):
					include('include/view/employer/courses/ditrp/list_courses.php');
					break;
				case('updateCourses'):
					include('include/view/employer/courses/ditrp/update_course.php');
					break;
				case('addCourses'):
					include('include/view/employer/courses/ditrp/add_course.php');
					break;	
				//for multi subjects courses
				case('listCoursesMultiSub'):
					include('include/view/employer/courses/ditrp/multi_sub/list_courses_multi_sub.php');
					break;
				case('updateCoursesMultiSub'):
					include('include/view/employer/courses/ditrp/multi_sub/update_course_multi_sub.php');
					break;
				case('addCoursesMultiSub'):
					include('include/view/employer/courses/ditrp/multi_sub/add_course_multi_sub.php');
					break;	
				//course subjects add and remove
				case('addCoursesMultiSubSubjects'):
					include('include/view/employer/courses/ditrp/multi_sub/add_course_subjects_multi_sub.php');
					break;	

				//for typing courses
				case('listCoursesTyping'):
					include('include/view/employer/courses/ditrp/typing/list_courses_typing.php');
					break;
				case('updateCoursesTyping'):
					include('include/view/employer/courses/ditrp/typing/update_course_typing.php');
					break;
				case('addCoursesTyping'):
					include('include/view/employer/courses/ditrp/typing/add_course_typing.php');
					break;

				//course subjects add and remove
				case('addCoursesTypingSubjects'):
					include('include/view/employer/courses/ditrp/typing/add_course_subjects_typing.php');
					break;

				//Admission Enquiry
				case('studentEnquiry'):
					include('include/view/employer/student/enquiries/list_student_enquiries.php');
					break;
				case('studentAddEnquiry'):
					include('include/view/employer/student/enquiries/add_student_enquiry.php');
					break;
				case('studentUpdateEnquiry'):
					include('include/view/employer/student/enquiries/update_student_enquiry.php');
					break;

				case('studentRegisterEnquiry'):
					include('include/view/employer/student/enquiries/register_student.php');
					break;

				//Direct Admission
				case('studentAdmission'):
					include('include/view/employer/student/direct_admission/list_student.php');
					break;
				case('studentReAdmission'):
					include('include/view/employer/student/direct_admission/readmission_student.php');
					break;
				case('studentAddAdmission'):
					include('include/view/employer/student/direct_admission/add_student.php');
					break;
				case('studentUpdateAdmission'):
					include('include/view/employer/student/direct_admission/update_student.php');
					break;
				case('viewResume'):
					include('include/view/employer/student/direct_admission/studentResume.php');
					break;

				//Fees Management One Page	
				case('listStudentFees'):
					include('include/view/employer/student/payments/list_student_payments.php');
					break;

				case('studentFees'):
					include('include/view/employer/student/payments/add_student_payment.php');
					break;

				case('studentAddFees'):
					include('include/view/employer/student/payments/add_student_payment.php');
					break;
				case('studentUpdateFees'):
					include('include/view/employer/student/payments/update_student_payment.php');
					break;
				case('viewStudentReceipt'):
					include('include/view/employer/student/payments/student_receipt.php');
					break;
				
				case('ourStudentFeesHistory'):
					include('include/view/employer/student/payments/list_student_payments_history.php');
					break;
					
				case('viewPaymentHistory'):
					include('include/view/employer/student/payments/view_payment_history.php');
					break;

				//Refferral Amount
				case('refferalAmount'):
					include('include/view/employer/refferal/add.php');
					break;

				//Student Forms
				case('viewStudentForm'):
					include('include/view/employer/student/view_student_form.php');
					break;
					
				case('viewStudentIdcard'):
					include('include/view/employer/student/view_student_idcard.php');
					break;				

				//Exam Section
				case('resetExam'):
					include('include/view/employer/exam/list_student_exams.php');
					break;
				case('updateExams'):
					include('include/view/employer/exam/update_student_exam.php');
					break;
				case('addExams'):
					include('include/view/employer/exam/add_student_exam.php');
					break;
				case('examOTP'):
					include('include/view/employer/exam/list_student_exams_secrete_codes.php');
					break;	

				// offline marks update list practical exams results
				case('listPracticalExamResult'):
					include('include/view/employer/exam/practical/list_practical_exam_papers.php');
					break;
				case('addPracticalExamResult'):
					include('include/view/employer/exam/practical/add_practical_exam_result.php');
					break;
				case('updatePracticalExamResult'):
					include('include/view/employer/exam/practical/update_practical_exam_result.php');
					break;	

				// list practical exams results		
				case('listExamResults'):
					include('include/view/employer/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/employer/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/employer/exam/update_exam_results.php');

				//Hall Tickets
				// case('listHallticket'):
				// 	include('include/view/institute/hallticket/list_hall_ticket.php');
				// 	break;
				case('generateHallticket'):
					include('include/view/employer/hallticket/generate_hall_ticket.php');
					break;
				case('listHallticket'):
					include('include/view/employer/hallticket/list.php');
					break;
				case('printHallticket'):
					include('include/view/employer/hallticket/print_hall_tickets.php');
					break;

				//Marksheet & Certificates
				/* ------------------------------ Certificates ------------------------ */
				case('listExamResults'):
					include('include/view/employer/exam/list_exam_results.php');
					break;

				case('listRequestedCertificates'):
					include('include/view/employer/certificates/list_requested_certificates.php');
					break;
				case('printCertificate'):
					include('include/view/employer/certificates/print_certificates.php');
					break;
				case('viewStudentCertificate'):
					include('include/view/employer/certificates/list_requested_certificates.php');
					break;
				case('printModifyCertificate'):
					include('include/view/employer/certificates/print_modify_certificate.php');
					break;
				case('printFranchiseCertificate'):
					include('include/view/employer/certificates/print_franchise_certificates.php');
					break;

				//order certficate

				case('orderStudentCertificate'):
					include('include/view/employer/certificates/list_order_certificate.php');
					break;
				case('viewOrderStudentCertificate'):
					include('include/view/employer/certificates/view_order_certificate.php');
					break;

				/*.....................Marksheet......................................*/
				case('listRequestedMarksheet'):
					include('include/view/employer/marksheets/list_req_marksheet.php');
					break;
				case('printRequestedMarksheet'):
					include('include/view/employer/marksheets/print_marksheet.php');
					break;
				case('printMarksheet'):
					include('include/view/employer/marksheets/print_Bulk_marksheet.php');
					break;
				case('printFranchiseAddress'):
					include('include/view/employer/certificates/print_franchise_address.php');
					break;
						
				/* ------------------------------Wallet------------------------ */
				case('rechargeWallet'):
					include('include/view/employer/wallet/recharge_wallet.php');
					break;
				case('Wallet'):
					include('include/view/employer/wallet/wallet.php');
					break;
				case('rechargeHistory'):
					include('include/view/employer/wallet/recharge_history.php');
					break;
				
				case('ourRechargeHistory'):
					include('include/view/employer/wallet/our_recharge_history.php');
					break;

				/* ------------------------------Franchise Wallet------------------------ */
				case('rechargeWalletFranchise'):
					include('include/view/employer/franchise_wallet/recharge_wallet.php');
					break;
				case('WalletFranchise'):
					include('include/view/employer/franchise_wallet/wallet.php');
					break;
				case('rechargeHistoryFranchise'):
					include('include/view/employer/franchise_wallet/recharge_history.php');
					break;
				
				case('ourRechargeHistoryFranchise'):
					include('include/view/employer/franchise_wallet/our_recharge_history.php');
					break;

				
				/* ------------------------------Courier Wallet------------------------ */
				case('rechargeWalletCourier'):
					include('include/view/employer/courier_wallet/courier_payonline.php');
					break;
				case('WalletCourier'):
					include('include/view/employer/courier_wallet/courier_wallet.php');
					break;
				case('rechargeHistoryCourier'):
					include('include/view/employer/courier_wallet/courier_recharge_history.php');
					break;			
				
				
				
					/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/employer/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/employer/helpsupport/reply_support.php');
					break;
					
				case('listAdminSupport'):
					include('include/view/employer/helpsupport/list_admin_support.php');
					break;
				case('replyAdminSupport'):
					include('include/view/employer/helpsupport/reply_admin_support.php');
					break;
				case('addAdminSupport'):
					include('include/view/employer/helpsupport/add_admin_support.php');
					break;

				case('listSupportType'):
					include('include/view/employer/helpsupport/type/list_support_type.php');
					break;
				case('addSupportType'):
					include('include/view/employer/helpsupport/type/add_support_type.php');
					break;
				case('updateSupportType'):
					include('include/view/employer/helpsupport/type/update_support_type.php');
					break;

				case('listSupportCat'):
					include('include/view/employer/helpsupport/category/list_support_cat.php');
					break;
				case('addSupportCat'):
					include('include/view/employer/helpsupport/category/add_support_cat.php');
					break;
				case('updateSupportCat'):
					include('include/view/employer/helpsupport/category/update_support_cat.php');
					break;	

				//online classes
				case('listOnlineClasses'):
					include('include/view/employer/onlineclasses/list.php');
					break;
				case('addOnlineClasses'):
					include('include/view/employer/onlineclasses/add.php');
					break;
				case('updateOnlineClasses'):
					include('include/view/employer/onlineclasses/edit.php');
					break;	

				//Marquee IMS
				case('listMarqueeNotification'):
					include('include/view/employer/marquee/add.php');
					break;

				case('manageBackground'):
					include('include/view/employer/backgroundupload/add.php');
					break;

				//Advertise IMS
				case('listAdvertisement'):
					include('include/view/employer/advertise/list.php');
					break;
				case('addAdvertisement'):
					include('include/view/employer/advertise/add.php');
					break;
				case('updateAdvertisement'):
					include('include/view/employer/advertise/edit.php');
					break;	

				//Birthday List
				case('listBirthday'):
					include('include/view/employer/reports/bday_reports.php');
					break;

				//User Management
				case('listStaff'):
					include('include/view/employer/staff/list_staffs.php');
					break;
				case('addStaff'):
					include('include/view/employer/staff/add_staff.php');
					break;
				case('updateStaff'):
					include('include/view/employer/staff/update_staff.php');
					break;	

				//Batches 
				case('listBatches'):
					include('include/view/employer/batch/list_batch.php');
					break;
				case('addBatches'):
					include('include/view/employer/batch/add_batch.php');
					break;
				case('updateBatches'):
					include('include/view/employer/batch/update_batch.php');
					break;
					
				//Batch Details
				case('BatchDetails'):
					include('include/view/employer/batch/batch_details.php');
					break;

				//Attendance
				case('listMarksheet'):
					include('include/view/employer/Marksheet/list_marksheet.php');
					break;
				case('generateMarksheet'):
					include('include/view/employer/Marksheet/generate_marksheet.php');
					break;
					
				case('generateOfflineExam'):
					include('include/view/employer/exam/offline/generate_offline_exam.php');
					break;
					
				case('list-offline-exam-papers'):
					include('include/view/employer/exam/offline/list_offline_exam_papers.php');
					break;
				case('addOfflineExamResult'):
					include('include/view/employer/exam/offline/add_offline_exam_result.php');
					break;
				case('updateOfflineExamResult'):
					include('include/view/employer/exam/offline/update_offline_exam_result.php');
					break;		
			
				// list practical exams results				
				case('listExamResults'):
					include('include/view/employer/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/employer/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/employer/exam/update_exam_results.php');
					break;
				/* ------------------------------Certificates------------------------ */
				case('listRequestedCertificates'):
					include('include/view/employer/certificates/list_requested_certificates.php');
					break;
				case('print-certificate'):
						include('include/view/employer/certificates/print_certificates.php');
						break;

                
					break;
				case('printStudentMarksheet'):
					include('include/view/employer/marksheet/print_marksheet.php');
					break;					
				
				case('printPerformanceCertificateCover'):
					include('include/view/employer/certificates/print_performance_certificate_cover.php');
					break;	

				case('view-student-certificate'):
                	include('include/view/employer/certificates/view_student_certificate.php');
                	break;
                case('print-requested-marksheet'):
                	include('include/view/employer/inst_data/certificates/viewcertificatemarksheet/print_marksheet.php');
                	break;   
				case('print-modify-certificate'):
						include('include/view/employer/certificates/print_modify_certificate.php');
						break;  
				
				//Print Hard Copy
				case('certificatePrint'):
					include('include/view/employer/certificates/certificatePrint.php');
					break;
				case('marksheetPrint'):
					include('include/view/employer/inst_data/certificates/viewcertificatemarksheet/marksheetPrint.php');
					break;  
				
						
				//Attendance		
				case('Attendance'):
					include('include/view/employer/attendance/list.php');
					break;

				case('AttendanceReport'):
					include('include/view/employer/attendance/attendance_report.php');
					break;
					
				case('AttendanceStudentReport'):
					include('include/view/employer/attendance/attendance_student_report.php');
					break;

				case('addAttendance'):
					include('include/view/employer/attendance/add.php');
					break;
				case('editAttendance'):
					include('include/view/employer/attendance/update.php');
					break;
              			
				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/employer/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/employer/helpsupport/reply_support.php');
					break;
				/* ------------------------------Expenses------------------------ */
				case('listExpenses'):
					include('include/view/employer/expenses/listExpenses.php');
					break;
				case('addExpense'):
					include('include/view/employer/expenses/addExpense.php');
					break;
				case('updateExpense'):					
					include('include/view/employer/expenses/update_expense.php');
					break;
				case('listExpenseCategory'):
					include('include/view/employer/expenses/listExpenseCategory.php');
					break;
				case('listSubExpenseCategory'):
					include('include/view/employer/expenses/listSubExpenseCategory.php');
					break;
				case('addexpensetype'):
					include('include/view/employer/expenses/addexpensetype.php');
					break;
				case('updateexpensetype'):					
					include('include/view/employer/expenses/updateexpensetype.php');
					break;
				case('addexpensesubtype'):
					include('include/view/employer/expenses/addexpensesubtype.php');
					break;
				case('updateexpensesubtype'):					
					include('include/view/employer/expenses/updateexpensesubtype.php');
					break;
					
				/*---------- Expense Ends Here ----------*/				

				case('previewAdmissionForm'):
					include('include/view/employer/cert_preview/view_student_form.php');
					break;

				case('previewIdcard'):
					include('include/view/employer/cert_preview/view_student_idcard.php');
					break;

				case('previewHallTicket'):
					include('include/view/employer/cert_preview/print_hall_tickets.php');
					break;

				case('previewFeesReceipt'):
					include('include/view/employer/cert_preview/student_receipt.php');
					break;
				
				
				case('previewSingleMarksheet'):
					include('include/view/employer/cert_preview/previewSingleMarksheet.php');
					break;
					
				case('previewMultipleMarksheet'):
					include('include/view/employer/cert_preview/previewMultipleMarksheet.php');
					break;
					
				case('previewTypingMarksheet'):
					include('include/view/employer/cert_preview/previewTypingMarksheet.php');
					break;
				/* ------------------------------Default------------------------ */
				default:
					include('include/view/default/404.php');
					break;
			}
			break;
			
			/* ------------------------------------Admin Employee --------------------------------- */
		case(6):		
			switch($page)
			{	
				/* ------------------------------ Account ------------------------ */
				
				
				case('listRechargeRequest'):
					include('include/view/admin_staff/rechargeoffers/list_recharge_request.php');
					break;
					
				case('updateRechargeRequest'):
					include('include/view/admin_staff/rechargeoffers/update_recharge_request.php');
					break;
				
				case('listRechargeOffers'):
					include('include/view/admin_staff/rechargeoffers/list.php');
					break;
					
				case('updateRechargeOffers'):
					include('include/view/admin_staff/rechargeoffers/update.php');
					break;
				case('addRechargeOffers'):
					include('include/view/admin_staff/rechargeoffers/add.php');
					break;
				
						/* ------------------------------Gallery------------------------ */
				case('listMarkeing'):
					include('include/view/admin_staff/website/gallery/list_gallery.php');
					break;
				case('updateMarkeing'):
					include('include/view/admin_staff/website/gallery/update_gallery.php');
					break;
				case('addMarkeing'):
					include('include/view/admin_staff/website/gallery/add_gallery.php');
					break;
					
				// case('masterPassword'):
				// 	include('include/view/admin_staff/institute/master_password.php');
				// 	break;	
					
				case('updateInstitute'):
					include('include/view/admin_staff/account/update_institute.php');
					break;
					
					    /* ------------- Festival ------------------------ */
			    case('list-festival'):
					include('include/view/admin_staff/festival/list.php');
					break;
				case('update-festival'):
					include('include/view/admin_staff/festival/edit.php');
					break;
				case('add-festival'):
					include('include/view/admin_staff/festival/add.php');
					break;

				/* -------------------------- ourProduct ------------------------- */
				case('viewProduct'):
					include('include/view/admin_staff/product/list.php');
					break;
				case('updateProduct'):
					include('include/view/admin_staff/product/update.php');
					break;
				case('addProduct'):
					include('include/view/admin_staff/product/add.php');
					break;
                
                /* -------------------------- Seminar ------------------------- */
				case('listSeminar'):
					include('include/view/admin_staff/seminar/list_seminar.php');
					break;
				case('updateSeminar'):
					include('include/view/admin_staff/seminar/update_seminar.php');
					break;
				case('addSeminar'):
					include('include/view/admin_staff/seminar/add_seminar.php');
					break;

				/* -------------------------- Seminar Student------------------------- */
				case('listSeminarStudent'):
					include('include/view/admin_staff/seminar/student/list_student.php');
					break;
				case('updateSeminarStudent'):
					include('include/view/admin_staff/seminar/student/update_student.php');
					break;
				case('addSeminarStudent'):
					include('include/view/admin_staff/seminar/student/add_student.php');
					break;

				case('viewStudentCertificate'):
					include('include/view/admin_staff/seminar/certificate/view_certificate.php');
					break;
				case('printStudentCertificate'):
					include('include/view/admin_staff/seminar/certificate/print_certificate.php');
					break;

	           case('listServicesEnquiry'):
					include('include/view/admin_staff/institute/list_services_enquiry.php');
					break;
					
				   
				/* -------------------------- Franchise ------------------------- */
				case('listFranchiseEnquiry'):
					include('include/view/admin_staff/institute/list_franchise_enquiry.php');
					break;
					
				case('updateFranchiseEnquiry'):
					include('include/view/admin_staff/institute/update_franchise_enquiry.php');
					break;
					
				case('listFranchise'):
					include('include/view/admin_staff/institute/list_institutes.php');
					break;
				case('updateFranchise'):
					include('include/view/admin_staff/institute/update_institute.php');
					break;
				case('addFranchise'):
					include('include/view/admin_staff/institute/add_institute.php');
					break;

				/*IMS system */
				case('IMSDashboard'):						
					include('include/view/dashboard.php');
					break;
				case('IMSDashboardStudent'):						
					include('include/view/admin_staff/dashboard/student_dashboard.php');
					break;

				//Admission Enquiry
				case('studentEnquiry'):
					include('include/view/admin_staff/student/enquiries/list_student_enquiries.php');
					break;
				case('studentAddEnquiry'):
					include('include/view/admin_staff/student/enquiries/add_student_enquiry.php');
					break;
				case('studentUpdateEnquiry'):
					include('include/view/admin_staff/student/enquiries/update_student_enquiry.php');
					break;
           
				case('studentRegisterEnquiry'):
					include('include/view/admin_staff/student/enquiries/register_student.php');
					break;

				//Direct Admission
				case('studentAdmission'):
					include('include/view/admin_staff/student/direct_admission/list_student.php');
					break;
				case('studentReAdmission'):
					include('include/view/admin_staff/student/direct_admission/readmission_student.php');
					break;
				case('studentAddAdmission'):
					include('include/view/admin_staff/student/direct_admission/add_student.php');
					break;
				case('studentUpdateAdmission'):
					include('include/view/admin_staff/student/direct_admission/update_student.php');
					break;

				case('viewResume'):
					include('include/view/admin_staff/student/direct_admission/studentResume.php');
					break;
					
				//Fees Management One Page	

				case('listStudentFees'):
					include('include/view/admin_staff/student/payments/list_student_payments.php');
					break;

				case('studentFees'):
					include('include/view/admin_staff/student/payments/add_student_payment.php');
					break;

				case('studentAddFees'):
					include('include/view/admin_staff/student/payments/add_student_payment.php');
					break;
				case('studentUpdateFees'):
					include('include/view/admin_staff/student/payments/update_student_payment.php');
					break;
				case('viewStudentReceipt'):
					include('include/view/admin_staff/student/payments/student_receipt.php');
					break;

				case('viewPaymentHistory'):
					include('include/view/admin_staff/student/payments/view_payment_history.php');
					break;
					
				case('ourStudentFeesHistory'):
					include('include/view/admin_staff/student/payments/list_student_payments_history.php');
					break;
	

				//Refferral Amount
				case('refferalAmount'):
					include('include/view/admin_staff/refferal/add.php');
					break;

				//Student Forms
				case('viewStudentForm'):
					include('include/view/admin_staff/student/view_student_form.php');
					break;
					
				case('viewStudentIdcard'):
					include('include/view/admin_staff/student/view_student_idcard.php');
					break;

				//course Awards
				case('listAwardCategories'):					
					include('include/view/admin_staff/award/list_award.php');
					break;
				case('addAwardCategories'):					
					include('include/view/admin_staff/award/add_award.php');
					break;
				case('updateAwardCategories'):					
					include('include/view/admin_staff/award/update_award.php');
					break;
				//institute plans
				case('listPlans'):
					include('include/view/admin_staff/instituteplans/institute_plans.php');
					break;
				case('addPlans'):
					include('include/view/admin_staff/instituteplans/add_institute_plans.php');
					break;
				case('updatePlans'):
					include('include/view/admin_staff/instituteplans/update_institute_plans.php');
					break;

				//courses
				case('listCourse'):					
					include('include/view/admin_staff/courses/list_courses.php');
					break;
				case('addCourse'):					
					include('include/view/admin_staff/courses/add_course.php');
					break;
				case('updateCourse'):					
					include('include/view/admin_staff/courses/update_course.php');
					break;

				//Exams
				case('listExams'):					
					include('include/view/admin_staff/exams/list_exams.php');
					break;
				case('addExam'):					
					include('include/view/admin_staff/exams/add_exam.php');
					break;
				case('updateExam'):					
					include('include/view/admin_staff/exams/update_exam.php');
					break;

				//Question Bank
				case('listQueBank'):					
					include('include/view/admin_staff/quebank/list_quebank.php');
					break;
				case('addQueBank'):					
					include('include/view/admin_staff/quebank/add_quebank.php');
					break;
				case('updateQueBank'):					
					include('include/view/admin_staff/quebank/update_quebank.php');
					break;
				case('viewQueBank'):					
					include('include/view/admin_staff/quebank/view_quebank.php');
					break;
				case('addQuestion'):					
					include('include/view/admin_staff/quebank/add_question.php');
					break;
				case('editQuestion'):					
					include('include/view/admin_staff/quebank/edit_question.php');
					break;

				//Courses Multiple Subjects
				case('listCourseMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/course/list_courses_multi_sub.php');
					break;
				case('addCourseMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/course/add_course_multi_sub.php');
					break;
				case('updateCourseMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/course/update_course_multi_sub.php');
					break;

				//Exam Multiple Subjects
				case('listExamsMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/exam/list_exam_multi_sub.php');
					break;
				case('addExamMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/exam/add_exam_multi_sub.php');
					break;
				case('updateExamMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/exam/update_exam_multi_sub.php');
					break;

				//Questions Multiple Subjects
				case('listQueBankMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/list_quebank_multi_sub.php');
					break;
				case('addQueBankMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/add_quebank_multi_sub.php');
					break;
				case('updateQueBankMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/update_quebank_multi_sub.php');
					break;
				case('viewQueBankMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/view_quebank_multi_sub.php');
					break;
				case('addQuestionMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/add_question_multi_sub.php');
					break;
				case('editQuestionMultiSub'):					
					include('include/view/admin_staff/courses_multi_sub/quebank/edit_question_multi_sub.php');
					break;

				//Exam Section
				case('resetExam'):
					include('include/view/admin_staff/exam/list_student_exams.php');
					break;
				case('updateExams'):
					include('include/view/admin_staff/exam/update_student_exam.php');
					break;
				case('addExams'):
					include('include/view/admin_staff/exam/add_student_exam.php');
					break;
				case('examOTP'):
					include('include/view/admin_staff/exam/list_student_exams_secrete_codes.php');
					break;	

				// offline marks update list practical exams results
				case('listPracticalExamResult'):
					include('include/view/admin_staff/exam/practical/list_practical_exam_papers.php');
					break;
				case('addPracticalExamResult'):
					include('include/view/institute/exam/practical/add_practical_exam_result.php');
					break;
				case('updatePracticalExamResult'):
					include('include/view/admin_staff/exam/practical/update_practical_exam_result.php');
					break;	

				// list practical exams results		
				case('listExamResults'):
					include('include/view/admin_staff/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/admin_staff/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/admin_staff/exam/update_exam_results.php');

				//Hall Tickets
				// case('listHallticket'):
				// 	include('include/view/admin_staff/hallticket/list_hall_ticket.php');
				// 	break;
				case('generateHallticket'):
					include('include/view/admin_staff/hallticket/generate_hall_ticket.php');
					break;
				case('listHallticket'):
					include('include/view/admin_staff/hallticket/list.php');
					break;
				case('printHallticket'):
					include('include/view/admin_staff/hallticket/print_hall_tickets.php');
					break;

				//Marksheet & Certificates
				/* ------------------------------ Certificates ------------------------ */
				case('listExamResults'):
					include('include/view/admin_staff/exam/list_exam_results.php');
					break;

				case('listRequestedCertificates'):
					include('include/view/admin_staff/certificates/list_requested_certificates.php');
					break;
				case('printCertificate'):
					include('include/view/admin_staff/certificates/print_certificates.php');
					break;
				case('viewStudentCertificate'):
					include('include/view/admin_staff/certificates/view_student_certificate.php');
					break;
				case('printModifyCertificate'):
					include('include/view/admin_staff/certificates/print_modify_certificate.php');
					break;
				case('printFranchiseCertificate'):
					include('include/view/admin_staff/certificates/print_franchise_certificates.php');
					break;
					
				
				//order certificate list
				case('listOrderRequestedCertificates'):
					include('include/view/admin_staff/certificates/list_order_certificate_request.php');
					break;
				case('printOrderCertificate'):
					include('include/view/admin_staff/certificates/print_order_certificate.php');
					break;
				case('viewOrderStudentCertificate'):
					include('include/view/admin_staff/certificates/view_order_student_certificate.php');
					break;
				case('printOrderModifyCertificate'):
					include('include/view/admin_staff/certificates/print_order_modify_certificate.php');
					break;
				case('printOrderRequestedMarksheet'):
					include('include/view/admin_staff/marksheets/print_order_marksheet.php');
					break;

				/*.....................Marksheet......................................*/
				case('listRequestedMarksheet'):
					include('include/view/admin_staff/marksheets/list_req_marksheet.php');
					break;
				case('printRequestedMarksheet'):
					include('include/view/admin_staff/marksheets/print_marksheet.php');
					break;
				case('printMarksheet'):
					include('include/view/admin_staff/marksheets/print_Bulk_marksheet.php');
					break;
				case('printFranchiseAddress'):
					include('include/view/admin_staff/certificates/print_franchise_address.php');
					break;

				/* ------------------------------Franchise Wallet------------------------ */
				case('rechargeFranchiseWallet'):
					include('include/view/admin_staff/franchise_wallet/recharge_wallet.php');
					break;
				case('franchiseWallet'):
					include('include/view/admin_staff/franchise_wallet/wallet.php');
					break;
				case('rechargeFranchiseHistory'):
					include('include/view/admin_staff/franchise_wallet/recharge_history.php');
					break;			
						
				/* ------------------------------Student Wallet------------------------ */
				case('rechargeWallet'):
					include('include/view/admin_staff/wallet/recharge_wallet.php');
					break;
				case('Wallet'):
					include('include/view/admin_staff/wallet/wallet.php');
					break;
				case('rechargeHistory'):
					include('include/view/admin_staff/wallet/recharge_history.php');
					break;

				case('ourRechargeHistory'):
					include('include/view/admin_staff/wallet/our_recharge_history.php');
					break;

				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/admin_staff/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/admin_staff/helpsupport/reply_support.php');
					break;

				case('listSupportType'):
					include('include/view/admin_staff/helpsupport/type/list_support_type.php');
					break;
				case('addSupportType'):
					include('include/view/admin_staff/helpsupport/type/add_support_type.php');
					break;
				case('updateSupportType'):
					include('include/view/admin_staff/helpsupport/type/update_support_type.php');
					break;

				case('listSupportCat'):
					include('include/view/admin_staff/helpsupport/category/list_support_cat.php');
					break;
				case('addSupportCat'):
					include('include/view/admin_staff/helpsupport/category/add_support_cat.php');
					break;
				case('updateSupportCat'):
					include('include/view/admin_staff/helpsupport/category/update_support_cat.php');
					break;	

				//online classes
				case('listOnlineClasses'):
					include('include/view/admin_staff/onlineclasses/list.php');
					break;
				case('addOnlineClasses'):
					include('include/view/admin_staff/onlineclasses/add.php');
					break;
				case('updateOnlineClasses'):
					include('include/view/admin_staff/onlineclasses/edit.php');
					break;	

				//Marquee IMS
				case('listMarqueeNotification'):
					include('include/view/admin_staff/marquee/add.php');
					break;

				case('manageBackground'):
					include('include/view/admin_staff/backgroundupload/add.php');
					break;

				//Advertise IMS
				case('listAdvertisement'):
					include('include/view/admin_staff/advertise/list.php');
					break;
				case('addAdvertisement'):
					include('include/view/admin_staff/advertise/add.php');
					break;
				case('updateAdvertisement'):
					include('include/view/admin_staff/advertise/edit.php');
					break;	

				//Birthday List
				case('listBirthday'):
					include('include/view/admin_staff/reports/bday_reports.php');
					break;

				//User Management
				case('listStaff'):
					include('include/view/admin_staff/staff/list_staffs.php');
					break;
				case('addStaff'):
					include('include/view/admin_staff/staff/add_staff.php');
					break;
				case('updateStaff'):
					include('include/view/admin_staff/staff/update_staff.php');
					break;	

				//Batches 
				case('listBatches'):
					include('include/view/admin_staff/batch/list_batch.php');
					break;
				case('addBatches'):
					include('include/view/admin_staff/batch/add_batch.php');
					break;
				case('updateBatches'):
					include('include/view/admin_staff/batch/update_batch.php');
					break;
					
				//Batch Details
				case('BatchDetails'):
					include('include/view/admin_staff/batch/batch_details.php');
					break;

				//Attendance
				case('listMarksheet'):
					include('include/view/admin_staff/Marksheet/list_marksheet.php');
					break;
				case('generateMarksheet'):
					include('include/view/admin_staff/Marksheet/generate_marksheet.php');
					break;
					
				case('generateOfflineExam'):
					include('include/view/admin_staff/exam/offline/generate_offline_exam.php');
					break;
					
				case('list-offline-exam-papers'):
					include('include/view/admin_staff/exam/offline/list_offline_exam_papers.php');
					break;
				case('addOfflineExamResult'):
					include('include/view/admin_staff/exam/offline/add_offline_exam_result.php');
					break;
				case('updateOfflineExamResult'):
					include('include/view/admin_staff/exam/offline/update_offline_exam_result.php');
					break;		
			
				// list practical exams results				
				case('listExamResults'):
					include('include/view/admin_staff/exam/list_exam_results.php');
					break;
				case('listExamResultsAll'):
					include('include/view/admin_staff/exam/list_exam_results_all.php');
					break;
				case('updateExamResults'):
					include('include/view/admin_staff/exam/update_exam_results.php');
					break;
				/* ------------------------------Certificates------------------------ */
				case('listRequestedCertificates'):
					include('include/view/admin_staff/certificates/list_requested_certificates.php');
					break;
				case('print-certificate'):
						include('include/view/admin_staff/certificates/print_certificates.php');
						break;

             
					break;
				case('printStudentMarksheet'):
					include('include/view/admin_staff/marksheet/print_marksheet.php');
					break;					
				
				case('printPerformanceCertificateCover'):
					include('include/view/admin_staff/certificates/print_performance_certificate_cover.php');
					break;	

				case('view-student-certificate'):
                	include('include/view/admin_staff/certificates/view_student_certificate.php');
                	break;
                case('print-requested-marksheet'):
                	include('include/view/admin_staff/inst_data/certificates/viewcertificatemarksheet/print_marksheet.php');
                	break;   
				case('print-modify-certificate'):
						include('include/view/admin_staff/certificates/print_modify_certificate.php');
						break;  
				
				//Print Hard Copy
				case('certificatePrint'):
					include('include/view/admin_staff/certificates/certificatePrint.php');
					break;
				case('marksheetPrint'):
					include('include/view/admin_staff/inst_data/certificates/viewcertificatemarksheet/marksheetPrint.php');
					break;  
				
						
				//Attendance		
				case('Attendance'):
					include('include/view/admin_staff/attendance/list.php');
					break;

				case('AttendanceReport'):
					include('include/view/admin_staff/attendance/attendance_report.php');
					break;
					
				case('AttendanceStudentReport'):
					include('include/view/admin_staff/attendance/attendance_student_report.php');
					break;

				case('addAttendance'):
					include('include/view/admin_staff/attendance/add.php');
					break;
				case('editAttendance'):
					include('include/view/admin_staff/attendance/update.php');
					break;
              			
				/*---------------------------- HELP SUPPORT --------------------------------*/
				case('listSupport'):
					include('include/view/admin_staff/helpsupport/list_support.php');
					break;
				case('replySupport'):
					include('include/view/admin_staff/helpsupport/reply_support.php');
					break;
				/* ------------------------------Expenses------------------------ */
				case('listExpenses'):
					include('include/view/admin_staff/expenses/listExpenses.php');
					break;
				case('addExpense'):
					include('include/view/admin_staff/expenses/addExpense.php');
					break;
				case('updateExpense'):					
					include('include/view/admin_staff/expenses/update_expense.php');
					break;
				case('listExpenseCategory'):
					include('include/view/admin_staff/expenses/listExpenseCategory.php');
					break;
				case('listSubExpenseCategory'):
					include('include/view/admin_staff/expenses/listSubExpenseCategory.php');
					break;
				case('addexpensetype'):
					include('include/view/admin_staff/expenses/addexpensetype.php');
					break;
				case('updateexpensetype'):					
					include('include/view/admin_staff/expenses/updateexpensetype.php');
					break;
				case('addexpensesubtype'):
					include('include/view/admin_staff/expenses/addexpensesubtype.php');
					break;
				case('updateexpensesubtype'):					
					include('include/view/admin_staff/expenses/updateexpensesubtype.php');
					break;
					
			

				/*---------- Old Certificate ----------*/
				case('oldCertificate'):
					include('include/view/admin_staff/old_certificate/list.php');
					break;
				case('addOldCert'):
					include('include/view/admin_staff/old_certificate/add.php');
					break;
				case('updateOldCertficate'):
					include('include/view/admin_staff/old_certificate/edit.php');
					break;

				case('previewCertificate'):
					include('include/view/admin_staff/cert_preview/view_student_certificate.php');
					break;

				case('previewMarksheet'):
					include('include/view/admin_staff/cert_preview/print_marksheet.php');
					break;

				case('previewAdmissionForm'):
					include('include/view/admin_staff/cert_preview/view_student_form.php');
					break;

				case('previewIdcard'):
					include('include/view/admin_staff/cert_preview/view_student_idcard.php');
					break;

				case('previewHallTicket'):
					include('include/view/admin_staff/cert_preview/print_hall_tickets.php');
					break;

				case('previewFeesReceipt'):
					include('include/view/admin_staff/cert_preview/student_receipt.php');
					break;
				
				case('previewFranchiseCertificate'):
					include('include/view/admin_staff/cert_preview/view_franchise_certificate.php');
					break;
				

				//Typing Courses
				case('listTypingCourses'):					
					include('include/view/admin_staff/course_typing/list_courses_typing.php');
					break;
				case('addTypingCourses'):					
					include('include/view/admin_staff/course_typing/add_course_typing.php');
					break;
				case('updateTypingCourses'):					
					include('include/view/admin_staff/course_typing/update_course_typing.php');
					break;	

				case('listExamsTypingCourses'):					
					include('include/view/admin_staff/course_typing_exam/list_exam.php');
					break;
				case('addExamTypingCourses'):					
					include('include/view/admin_staff/course_typing_exam/add_exam.php');
					break;
				case('updateExamTypingCourses'):					
					include('include/view/admin_staff/course_typing_exam/update_exam.php');
					break;
					
				case('previewTypingMarksheet'):
					include('include/view/admin_staff/cert_preview/view_typing_marksheet.php');
					break;

				case('courierWallet'):
					include('include/view/admin_staff/courier_wallet/courier_wallet.php');
					break;
				case('courierWalletHistory'):
					include('include/view/admin_staff/courier_wallet/courier_recharge_history.php');
					break;
				case('courierWalletRecharge'):
					include('include/view/admin_staff/courier_wallet/courier_recharge_wallet.php');
					break;

				/* ------------------------------Default------------------------ */
				default:
					include('include/view/default/404.php');
					break;
			}
			break;
			
		
			
		default:
			include('include/view/default/404.php');
			break;
	
	}
	include('include/common/footer.php'); 
?>
</div>
<!-- ./wrapper -->
</body>
</html>
