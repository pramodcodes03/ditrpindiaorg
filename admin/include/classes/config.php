<?php

define("APP_TITLE", "Demo Site");
define('HTTP_HOST', 'https://ditrpindia.org/');
define('HTTP_HOST_ADMIN', 'https://ditrpindia.org/admin');

define("MOBILE_START_DIGIT", serialize(array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)));

//Institute Website Section
define("LOGO_PATH", "../uploads/logo");
define("SLIDERIMAGE_PATH", "../uploads/slider");
define("TESTIMONIAL_PATH", "../uploads/testmonial");
define("ADVERTISE_PATH", "../uploads/advertise");
define("ABOUTUS_PATH", "../uploads/aboutus");
define("SERVICES_PATH", "../uploads/services");
define("AFFILIATION_PATH", "../uploads/affiliations");
define("ACHIEVERS_PATH", "../uploads/achivers");
define("OURTEAM_PATH", "../uploads/ourteam");
define("GALLERYIMAGE_PATH", "../uploads/galleryimage");
define("JOBPOST_PATH", "../uploads/jobpost");
define("NEWS_PATH", "../uploads/news");


define("VERIFICATION_PATH", "../uploads/verification");
define("BLOGS_PATH", "../uploads/blogs");
define("PARTNERS_PATH", "../uploads/partners");
define("PAYMENTS_PATH", "../uploads/payments");
define("BANNERS_PATH", "../uploads/banners");
define("DOWNLOADMATERIAL_PATH", "../uploads/download_material");

define("FESTIVAL_IMAGES_PATH", "../uploads/festival");

define("RECHARGEOFFER_PATH", "../uploads/rechargeoffers");

//website sample certificates
define("WEBSITES_SAMPLE_CERT_PATH", "../uploads/website_sample_cert");

define("DEFAULT_USER_LOGO", "../uploads/default_user.png");

define("INSTITUTE_DOCUMENTS_PATH", "../uploads/institute/docs");
define("IMS_ADVERTISE_PATH", "../uploads/ims_advertise");

define("BACKGROUND_IMAGE_PATH", "../uploads/background_images");

define("ROOT_DIR", '/');
define("DOMAIN", ROOT_DIR . 'admin');
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . '/' . DOMAIN);
define("HOST", $_SERVER['HTTP_HOST'] . '/' . DOMAIN);
define('HTTP_HOST_SERVER', 'https://' . $_SERVER['HTTP_HOST']);

define("TEACHERPHOTO_PATH", "../uploads/teacher");

define("COURSE_MATERIAL_PATH", "../uploads/course/material");
define("COURSE_WITH_SUB_MATERIAL_PATH", "../uploads/course_with_sub/material");

define("COURSE_WITH_TYPING_MATERIAL_PATH", "../uploads/course_typing/material");

define("QUEBANK_PATH", "../uploads/quebank");
define("QUEBANK_PATH_FOR_MULTI_SUB", "../uploads/quebank_for_multi_sub");

define("INST_LOGO", "logo");
define("INST_OWNER_PHOTO", "owner_photo");
define("INST_PHOTO_PROOF", "owner_photo_id_proof");
define("INST_REG_CERTIFICATE", "institute_registration_certificate");
define("INST_EDU_DOCS", "educational_certificates");
define("INST_PROF_COURSE_DOCS", "professional_courses_certificates");
define("INST_OTHER_PHOTOS", "other_photos");

define("INST_SIGN", "sign");
define("INST_STAMP", "stamp");


define('CERTIFICATE_PATH', '../uploads/certificates/');
define('INST_CERTIFICATE_PATH', '../uploads/institute/certificates/');

define("OLD_CERTIFICATE_PATH", "../uploads/oldcert");
define("OLDCERTIFICATE_SAVE_PATH", "../uploads/oldcert/fileSave");

//helpsupport
define("HELPSUPPORT_PHOTO_PATH", "../uploads/helpsupport");
/* student files info */

//excel
define("STUDENT_EXCEL_DOCUMENTS_PATH", "../uploads/student_excel");

define("SEMINAR_DOCUMENTS_PATH", "../uploads/seminar");


/* Institute exam */
define("EXAM_OFFLINE_PAPER_PATH", $_SERVER['DOCUMENT_ROOT'] . '/' . ROOT_DIR . '/uploads/exam/offline');
//PDF operations
define("GENERATE_OFFLINE_EXAM_LINK", HOST . "/include/plugins/tcpdf/examples/generate_offline_paper.php");

define("STUDENT_RESUME_DOWNLOAD", $_SERVER['HTTP_HOST'] . '/' . ROOT_DIR . '/uploads/student');

//student files info 
define("STUDENT_DOCUMENTS_PATH", "../uploads/student");
define("STUD_PHOTO", "photo");
define("STUD_PHOTO_ID", "photo_identity");
define("STUD_PHOTO_SIGN", "photo_sign");

//exam application url
define('EXAM_PORTAL_URL', '../exam');
define("ENCRYPTION_KEY", "!@#$%^&*");

define("ADMIN_PHOTO_PATH", "../uploads/admin/");
define("ADMIN_STAFF_PHOTO_PATH", "../uploads/admin/staff");
define("GALLERY", "../uploads/gallery");


//QR Url
define('STUDENT_VERIFY_QRURL', 'https://ditrpindia.org/student_verify.php?');
define('STUDENT_PAYMENT_QRURL', 'https://ditrpindia.org/payment_verify.php?');
define('STUDENT_CERT_QRURL', 'https://ditrpindia.org/verification.php?');
define('ATC_CERT_QRURL', 'https://ditrpindia.org/atc_verification.php?');
define('TEACHER_QRURL', 'https://ditrpindia.org/teacher_verification.php?');

//sms whatsapp api
define('INSTANCE_ID', '6461FD234B587');
define('TOKEN', 'e614902fe12202189548ac89c72dd6d8');
define("SMS_SEND_URL", "https://ditrpindia.in/api/send.php");

//payment gateway

define("MERCHANT_KEY", "61YcjL");
define("SALT", "9SwX58tgiwcWQijFDg4FKhnQ6IhnFoEI");
define("PAYU_BASE_URL", "https://test.payu.in");

//define("MERCHANT_KEY", "KnT61oXY");
//define("SALT","DTLwfcebBm");
//define("PAYU_BASE_URL", "https://secure.payu.in");
define("SUCCESS_URL", "https://ditrpindia.org/admin/payment_success.php");
define("FAILURE_URL", "https://ditrpindia.org/admin/payment_failure.php");
define("SUCCESS_URL_COURIER", "https://ditrpindia.org/admin/payment_success_courier.php");
define("FAILURE_URL_COURIER", "https://ditrpindia.org/admin/payment_failure_courier.php");


/////////////////////////////////////////////////////////////////////////////
//database connection
// 	define("DB_HOST", "localhost");
// 	define("DB_USER", "root");
// 	define("DB_PASSWORD", "");
// 	define("DB_DATABASE", "ditrpindiaorg");

define("DB_HOST", "localhost");
define("DB_USER", "ditrpindia_ditrporg");
define("DB_PASSWORD", "Z@gv7EW2GRiN");
define("DB_DATABASE", "ditrpindia_ditrporg");

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
