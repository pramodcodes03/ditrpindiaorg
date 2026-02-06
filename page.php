<!doctype html>
<html lang="en">
<?php
$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $request_uri);

$page = isset($segments[0]) ? $segments[0] : 'default';
$id = isset($segments[1]) ? $segments[1] : null;

include('include/common/html_header.php'); ?>

<body>
	<?php
	if ($page != 'franchise-certificate-verify')
		include "include/common/header.php";

	switch ($page) {

		case ('BlogDetails'):
			include("include/pages/blog_details.php");
			break;
		case "about_us":
			include "include/pages/about_us.php";
			break;
		case "courses":
			include "include/pages/courses.php";
			break;
		case "course-details":
			include "include/pages/course_details.php";
			break;

		case "course-enquiry":
			include "include/pages/student_enquiry.php";
			break;

		case "services":
			include "include/pages/services.php";
			break;
		case "achievers":
			include "include/pages/our_achievers.php";
			break;
		case "affiliations":
			include "include/pages/our_affiliations.php";
			break;
		case "ourteam":
			include "include/pages/our_team.php";
			break;

		case "disclaimer":
			include "include/pages/privacy_policy.php";
			break;
		case "privacy":
			include "include/pages/privacy_policy.php";
			break;
		case "terms":
			include "include/pages/privacy_policy.php";
			break;
		case "refundPolicy":
			include "include/pages/privacy_policy.php";
			break;

			//Gallery
		case ('galleryImages'):
			include('include/pages/gallery.php');
			break;

		case ('galleryVideos'):
			include('include/pages/galleryVideos.php');
			break;

		case ('jobupdates'):
			include('include/pages/job_updates.php');
			break;
		case ('job-details'):
			include('include/pages/job_details.php');
			break;

		case ('contact'):
			include('include/pages/contactus.php');
			break;

		case ('admissionForm'):
			include('include/pages/student_admission.php');
			break;

		case ('studentVerification'):
			include('include/pages/student_verification.php');
			break;

		case ('atcVerification'):
			include('include/pages/franchise_verify.php');
			break;

		case ('oldCertificateVerification'):
			include('include/pages/old_certificate_verify.php');
			break;


		case ('ourBlogs'):
			include('include/pages/blogs.php');
			break;



		case ('downloadMaterial'):
			include('include/pages/download_material.php');
			break;

		case ('paymentMethods'):
			include('include/pages/payment_methods.php');
			break;

		case ('Certifications'):
			include('include/pages/sample_certificates.php');
			break;

		case ('FranchiseRegistration'):
			include('include/pages/franchise_registration.php');
			break;
		case ('FranchiseRegistrationSuccess'):
			include('include/pages/franchise_registration_succcess.php');
			break;

		case ('ourCenters'):
			include('include/pages/our_centers.php');
			break;

		case ('ourCentersLocation'):
			include('include/pages/our_centers_location.php');
			break;

		case ('franchiseDetails'):
			include('include/pages/franchise.php');
			break;

		case ('FranchiseEnquiry'):
			include('include/pages/franchise_enquiry.php');
			break;

		case ('FranchiseEnquirySuccess'):
			include('include/pages/franchise_enquiry_succcess.php');
			break;

		case ('News'):
			include('include/pages/news.php');
			break;

		case ('ATCCertificates'):
			include('include/pages/sample_certificates.php');
			break;

		case ('StudentCertificates'):
			include('include/pages/sample_certificates.php');
			break;

		case ('OurCertificates'):
			include('include/pages/sample_certificates.php');
			break;

		default:
			include "include/pages/default.php";
			break;
	}
	include "include/common/footer.php" ?>
</body>

</html>
<?php ob_flush(); ?>