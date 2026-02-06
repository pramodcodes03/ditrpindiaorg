<?php

include('include/common/html_header.php');
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : '';

$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$segments = explode('/', $page);

$page = isset($segments[0]) ? $segments[0] : 'default';
$id = isset($segments[1]) ? $segments[1] : null;
if (!isset($_SESSION['user_login_id'])) {
	header('location:login.php');
}

?>

<body class="hold-transition skin-blue sidebar-mini" <?php if ($page == "pay-online") echo 'onload="submitPayuForm()"'; ?>>
	<div class="wrapper">
		<?php
		include('include/common/header.php');
		switch ($user_role) {
				/* ------------------------------------Super Admin--------------------------------- */
			case (1):
				switch ($page) {
						//Admin pages Will come soons

						/* ------------------------------Default------------------------ */
					default:
						include('include/pages/default/404.php');
						break;
				}
				break;
				/* ------------------------------------Institute--------------------------------- */
			case (2):
				switch ($page) {
						/* ------------------------------ Account ------------------------ */
					case ('updateInstitute'):
						include('include/view/institute/account/update_institute.php');
						break;
					case ('websiteDashboard'):
						include('include/pages/dashboard.php');
						break;
					case ('manageLogo'):
						include('include/pages/logo/list.php');
						break;
					case ('editLogo'):
						include('include/pages/logo/edit.php');
						break;

					case ('manageSlider'):
						include('include/pages/slider/list.php');
						break;
					case ('addSlider'):
						include('include/pages/slider/add.php');
						break;
					case ('editSlider'):
						include('include/pages/slider/edit.php');
						break;

					case ('manageMarquee'):
						include('include/pages/marquee/add.php');
						break;

					case ('manageTestimonial'):
						include('include/pages/testimonial/list.php');
						break;
					case ('addTestimonial'):
						include('include/pages/testimonial/add.php');
						break;
					case ('editTestimonial'):
						include('include/pages/testimonial/edit.php');
						break;

					case ('manageSocialLinks'):
						include('include/pages/social_links/list.php');
						break;
					case ('addSocialLinks'):
						include('include/pages/social_links/add.php');
						break;
					case ('editSocialLinks'):
						include('include/pages/social_links/edit.php');
						break;

					case ('manageAdvertise'):
						include('include/pages/offers/list.php');
						break;
					case ('addAdvertise'):
						include('include/pages/offers/add.php');
						break;
					case ('editAdvertise'):
						include('include/pages/offers/edit.php');
						break;

					case ('AboutUs'):
						include('include/pages/aboutus/list.php');
						break;

					case ('editAboutUs'):
						include('include/pages/aboutus/add.php');
						break;

					case ('SliderBox'):
						include('include/pages/sliderBottomBox/list.php');
						break;

					case ('editSliderBox'):
						include('include/pages/sliderBottomBox/add.php');
						break;

					case ('ContactUs'):
						include('include/pages/contact/add.php');
						break;

					case ('manageColors'):
						include('include/pages/color_manage/add.php');
						break;

					case ('manageHeaderImages'):
						include('include/pages/page_images_head/add.php');
						break;

					case ('manageServices'):
						include('include/pages/services/list.php');
						break;
					case ('addServices'):
						include('include/pages/services/add.php');
						break;
					case ('editServices'):
						include('include/pages/services/edit.php');
						break;

					case ('manageAffiliations'):
						include('include/pages/our_affiliations/list.php');
						break;
					case ('addAffiliations'):
						include('include/pages/our_affiliations/add.php');
						break;
					case ('editAffiliations'):
						include('include/pages/our_affiliations/edit.php');
						break;

					case ('manageAchievers'):
						include('include/pages/our_achievers/list.php');
						break;
					case ('addAchievers'):
						include('include/pages/our_achievers/add.php');
						break;
					case ('editAchievers'):
						include('include/pages/our_achievers/edit.php');
						break;

					case ('manageTeam'):
						include('include/pages/team/list.php');
						break;
					case ('addTeam'):
						include('include/pages/team/add.php');
						break;
					case ('editTeam'):
						include('include/pages/team/edit.php');
						break;

					case ('manageNews'):
						include('include/pages/news/list.php');
						break;
					case ('addNews'):
						include('include/pages/news/add.php');
						break;
					case ('editNews'):
						include('include/pages/news/update.php');
						break;

					case ('manageGalleryImages'):
						include('include/pages/gallery_images/list.php');
						break;
					case ('addGalleryImages'):
						include('include/pages/gallery_images/add.php');
						break;
					case ('editGalleryImages'):
						include('include/pages/gallery_images/edit.php');
						break;

					case ('manageGalleryVideos'):
						include('include/pages/gallery_videos/list.php');
						break;
					case ('addGalleryVideos'):
						include('include/pages/gallery_videos/add.php');
						break;
					case ('editGalleryVideos'):
						include('include/pages/gallery_videos/edit.php');
						break;

					case ('manageJobs'):
						include('include/pages/job_updates/list.php');
						break;
					case ('addJobs'):
						include('include/pages/job_updates/add.php');
						break;
					case ('editJobs'):
						include('include/pages/job_updates/edit.php');
						break;

						//student applied for job
					case ('listJobApply'):
						include('include/pages/job_apply_student/list.php');
						break;

					case ('managePolicies'):
						include('include/pages/privacy_policies/add.php');
						break;

					case ('franchiseDetails'):
						include('include/pages/franchise_details/add.php');
						break;

					case ('manageVerification'):
						include('include/pages/verification/list.php');
						break;

					case ('addVerification'):
						include('include/pages/verification/add.php');
						break;

					case ('editVerification'):
						include('include/pages/verification/edit.php');
						break;

					case ('manageBlogs'):
						include('include/pages/blogs/list.php');
						break;

					case ('addBlogs'):
						include('include/pages/blogs/add.php');
						break;

					case ('editBlogs'):
						include('include/pages/blogs/edit.php');
						break;

					case ('managePartners'):
						include('include/pages/partners/list.php');
						break;

					case ('addPartners'):
						include('include/pages/partners/add.php');
						break;

					case ('editPartners'):
						include('include/pages/partners/edit.php');
						break;

					case ('managePayments'):
						include('include/pages/payment/list.php');
						break;

					case ('addPayments'):
						include('include/pages/payment/add.php');
						break;

					case ('editPayments'):
						include('include/pages/payment/edit.php');
						break;

						//sample certificates
					case ('sampleCert'):
						include('include/pages/samplecert/list.php');
						break;

					case ('addsampleCert'):
						include('include/pages/samplecert/add.php');
						break;

					case ('editsampleCert'):
						include('include/pages/samplecert/edit.php');
						break;
						//////////////////////////
					case ('manageDownload'):
						include('include/pages/download_material/list.php');
						break;

					case ('addDownload'):
						include('include/pages/download_material/add.php');
						break;

					case ('editDownload'):
						include('include/pages/download_material/edit.php');
						break;

					case ('listJobApply'):
						include('include/pages/job_apply_student/list.php');
						break;

					case ('settings'):
						include('include/pages/setting/settings.php');
						break;

					case ('changePassword'):
						include('include/pages/setting/change_password.php');
						break;
						/* ------------------------------Default------------------------ */
					default:
						include('include/view/default/404.php');
						break;
				}
				break;

				/* ------------------------------------ Student ------------------------------- */
			case (4):
				/* ------------------------------------Student--------------------------------- */
				include('include/common/headerIMS.php');
				switch ($page) {
						/*IMS system */
					case ('IMSDashboard'):
						include('include/view/student/dashboard/dashboard.php');
						break;

						//Birthday List
					case ('listBirthday'):
						include('include/view/student/birthday/bday_reports.php');
						break;

						//Advertise List
					case ('listAdvertise'):
						include('include/view/student/advertise/list.php');
						break;

						//Institute Details
					case ('myInstituteDetails'):
						include('include/view/student/institute/institute_details.php');
						break;

						//online classes
					case ('listOnlineClasses'):
						include('include/view/student/onlineclasses/list.php');
						break;

						//student Details
					case ('studentDetails'):
						include('include/view/student/account/view_student.php');
						break;
					case ('updateStudent'):
						include('include/view/student/account/update_student.php');
						break;

						//Student Forms
					case ('viewStudentForm'):
						include('include/view/student/courses/view_student_form.php');
						break;

					case ('viewStudentIdcard'):
						include('include/view/student/courses/view_student_idcard.php');
						break;



						//course details
					case ('myCoursesList'):
						include('include/view/student/courses/mycourseslist.php');
						break;
					case ('coursesDetails'):
						include('include/view/student/courses/course_details.php');
						break;

					case ('allCoursesList'):
						include('include/view/student/courses/allcourseslist.php');
						break;

					case ('purchaseCourse'):
						include('include/view/student/courses/course_purchase.php');
						break;

					case ('rechargeWallet'):
						include('include/view/student/wallet/recharge_wallet.php');
						break;
					case ('Wallet'):
						include('include/view/student/wallet/wallet.php');
						break;
					case ('rechargeHistory'):
						include('include/view/student/wallet/recharge_history.php');
						break;
					case ('refferalAmount'):
						include('include/view/student/wallet/recharge_history.php');
						break;

					case ('myResume'):
						include('include/view/student/wallet/wallet.php');
						break;

						//List Exam Results

					case ('listExamResults'):
						include('include/view/student/exams/list_student_exams.php');
						break;

						/* ------------------------------ Certificates -------------------------------- */
					case ('viewCertificate'):
						include('include/view/student/certificates/print_student_certificate.php');
						break;
					case ('viewMarksheet'):
						include('include/view/student/certificates/print_student_marksheet.php');
						break;

						/* ------------------------- Student Payments ---------------------------- */
					case ('studentPayments'):
						include('include/view/student/payments/list_student_payments.php');
						break;
					case ('studentPaymentsDetails'):
						include('include/view/student/payments/list_student_payments.php');
						break;
						//Notification

						//Job Updates
					case ('listJobUpdates'):
						include('include/view/student/jobs/list_job_updates.php');
						break;
					case ('jobDetails'):
						include('include/view/student/jobs/list_job_updates.php');
						break;
					case ('jobEnquiry'):
						include('include/view/student/jobs/list_job_updates.php');
						break;

						//attendance
					case ('listAttendance'):
						include('include/view/student/attendance/list.php');
						break;

					case ('showAttendanceCourse'):
						include('include/view/student/jobs/list_job_updates.php');
						break;

						//products
					case ('listProducts'):
						include('include/view/student/jobs/list_job_updates.php');
						break;
					case ('buyProducts'):
						include('include/view/student/jobs/list_job_updates.php');
						break;
					case ('myPurchasedProducts'):
						include('include/view/student/jobs/list_job_updates.php');
						break;

						/* ------------------------------Student Courses Info---------------------- */
					case ('list-student-courses'):
						include('include/view/student/courses/list_student_courses.php');
						break;
					case ('studentCoursesDetails'):
						include('include/view/student/courses/list_student_course_details.php');
						break;
						/* ------------------------- Student Payments ---------------------------- */
					case ('list-student-payments'):
						include('include/view/student/payments/list_student_payments.php');
						break;
						/* ------------------------------Exam------------------------------------ */
					case ('list-exams'):
						include('include/view/student/exams/list_student_exams.php');
						break;
					case ('download-offline-papers'):
						include('include/view/student/exams/download_offline_papers.php');
						break;
					case ('print-offline-papers'):
						include('include/view/student/exams/print_offline_paper.php');
						break;

						/* ------------------------------Account---------------------------------- */
					case ('update-student'):
						include('include/view/student/account/update_student.php');
						break;
					case ('change-password'):
						include('include/view/student/account/change_password.php');
						break;
					case ('generate-resume'):
						include('include/view/student/account/generate_resume.php');
						break;
					case ('view-resume'):
						include('include/view/student/account/view_resume.php');
						break;
					case ('view-student'):
						include('include/view/student/account/view_student.php');
						break;
						/* ------------------------------Storage---------------------------------- */
					case ('list-student-storage'):
						include('include/view/student/storage/list_student_storage.php');
						break;
					case ('download-files'):
						include('include/controller/student/storage/download.php');
						break;
						/* ------------------------------ Jobs -------------------------------- */
					case ('list-job-updates'):
						include('include/view/student/jobs/list_job_updates.php');
						break;
						/* ------------------------------ Certificates -------------------------------- */
					case ('print-student-certificate'):
						include('include/view/student/certificates/print_student_certificate.php');
						break;
					case ('print-student-marksheet'):
						include('include/view/student/certificates/print_student_marksheet.php');
						break;

						/*---------------------------- HELP SUPPORT --------------------------------*/
					case ('listSupport'):
						include('include/view/student/helpsupport/list_support.php');
						break;
					case ('replySupport'):
						include('include/view/student/helpsupport/reply_support.php');
						break;

					case ('addSupport'):
						include('include/view/student/helpsupport/add_support.php');
						break;

						/* ------------------------------Default---------------------------------- */
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