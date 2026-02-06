<?php

	define('HTTP_HOST','https://ditrpindia.org');
	define('HTTP_HOST_SERVER','https://ditrpindia.org');	 
	define("ADMIN_FOLDER_NAME","admin");		
	
	
	define("INSTITUTE_DOCUMENTS_PATH","uploads/institute/docs/");
	define("INSTITUTE_STAFF_PHOTO_PATH","uploads/institute/staff/");

	//Institute Website Section
	define("LOGO_PATH","uploads/logo");
	define("SLIDERIMAGE_PATH","uploads/slider");
	define("TESTIMONIAL_PATH","uploads/testmonial");
	define("ADVERTISE_PATH","uploads/advertise");
	define("ABOUTUS_PATH","uploads/aboutus");
	define("SERVICES_PATH","uploads/services");
	define("AFFILIATION_PATH","uploads/affiliations");
	define("ACHIEVERS_PATH","uploads/achivers");
	define("OURTEAM_PATH","uploads/ourteam");
	define("GALLERYIMAGE_PATH","uploads/galleryimage");
	define("JOBPOST_PATH","uploads/jobpost");
	define("COURSE_PATH","uploads/course");
	define("NEWS_PATH","uploads/news");

	define("VERIFICATION_PATH","uploads/verification");
	define("BLOGS_PATH","uploads/blogs");
	define("PARTNERS_PATH","uploads/partners");
	define("PAYMENTS_PATH","uploads/payments");
	define("BANNERS_PATH","uploads/banners");
	define("DOWNLOADMATERIAL_PATH","uploads/download_material");
    //website sample certificates
	define("WEBSITES_SAMPLE_CERT_PATH","uploads/website_sample_cert");
	
	define("COURSE_MATERIAL_PATH","uploads/course/material");
	define("COURSE_WITH_SUB_MATERIAL_PATH","uploads/course_with_sub/material");

	define("COURSE_WITH_TYPING_MATERIAL_PATH","uploads/course_typing/material");

	define("BACKGROUND_IMAGE_PATH","uploads/background_images");

	define("SEMINAR_DOCUMENTS_PATH","uploads/seminar");
	
	define("FESTIVAL_IMAGES_PATH","uploads/festival");

	/* email configurations */
	define('PHP_MAILER_PATH',$_SERVER['DOCUMENT_ROOT'].'/phpmailer/PHPMailerAutoload.php');
	
	define('EMAIL_HOST', 'hellodigitalindia.co.in');
	define('EMAIL_USERNAME', 'info@hellodigitalindia.co.in');
	define('EMAIL_PASSWORD', '91ms_MIhJa_U');
	define('EMAIL_PORT','465');
	define("EMAIL_IS_SMTP",true);
	define("EMAIL_LOG_URL",HTTP_HOST."resources/images/logo.png");

	//student files info 
	define("STUDENT_DOCUMENTS_PATH","uploads/student");
	define("STUD_PHOTO","photo");
	define("STUD_PHOTO_ID","photo_identity");
	define("STUD_PHOTO_SIGN","photo_sign");

	define('ATC_CERT_QRURL','https://ditrpindia.org/atc_verification.php?');
 
// 	define("DB_HOST", "localhost");
// 	define("DB_USER", "root");
// 	define("DB_PASSWORD", "");
// 	define("DB_DATABASE", "ditrpindiaorg");
	
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD", "Letsfindindia@102");
    define("DB_DATABASE", "ditrpindia_ditrporg");


	class connection{
		
		public function getDbConnection(){
		    
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . 
				$mysqli->connect_error;
			}
			return $mysqli;
		}
	}

?>
