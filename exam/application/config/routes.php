<?php
/*-----------------------------------ADMIN START---------------*/

$route['admin'] 									= 'admin';
$route['admin/dashboard'] 							= 'admin/dashboard';
$route['admin/logout'] 								= 'admin/logout';
$route['admin/settings/settings'] 					= 'admin/settings';
$route['admin/site_settings/site_settings'] 		= 'admin/site_settings';
$route['admin/settings/change_password'] 			= 'admin/change_password';
$route['admin/institute/manage_institute'] 			= 'admin/institute';
$route['admin/institute/add_edit_institute/(:any)']	= 'admin/add_edit_institute/$1';
$route['admin/institute/manage_credit'] 			= 'admin/credit';
$route['admin/exam/view_exam/(:any)']				= 'admin/view_exam/$1';
$route['admin/exam/add_edit_exam/(:any)']			= 'admin/add_edit_exam/$1';
$route['admin/exam/manage_question/(:any)'] 		= 'admin/question/$1';
$route['admin/exam/add_edit_question/(:any)']		= 'admin/add_edit_question/$1';
$route['admin/student/manage_student/(:any)'] 		= 'admin/student/$1';
$route['admin/student/add_edit_student/(:any)']		= 'admin/add_edit_student/$1';
$route['admin/exam_terms/managet_exam_terms']		= 'admin/exam_terms';
$route['admin/institute/copy_questions'] 			= 'admin/copy_questions';
$route['admin/report'] 								= 'admin/report';
$route['admin/institute/upload_questions'] 			= 'admin/upload_questions';
$route['admin/institute/offline_exam'] 				= 'admin/offline_exam';

/*-----------------------------------ADMIN END----------------*/

/*-----------------------------------INSTITUTE START----------*/

$route['institute'] 								= 'institute';
$route['institute/dashboard'] 						= 'institute/dashboard';
$route['institute/logout'] 							= 'institute/logout';
$route['institute/settings/settings'] 				= 'institute/settings';
$route['institute/settings/change_password'] 		= 'institute/change_password';
$route['institute/exam/manage_exam'] 				= 'institute/exam';
$route['institute/exam/add_edit_exam/(:any)']		= 'institute/add_edit_exam/$1';
$route['institute/exam/manage_question/(:any)'] 	= 'institute/question/$1';
$route['institute/exam/add_edit_question/(:any)']	= 'institute/add_edit_question/$1';
$route['institute/student/manage_student'] 			= 'institute/student';
$route['institute/student/add_edit_student/(:any)']	= 'institute/add_edit_student/$1';
$route['institute/exm_list'] 						= 'institute/exm_list';
$route['institute/exam/offline_exam'] 				= 'institute/offline_exam';



/*-----------------------------------INSTITUTE END------------*/

/*-----------------------------------HOME START---------------*/
$route['home/logout'] 						= 'home/logout';
$route['exam'] 				  				= 'home/exam';
$route['otp'] 				  				= 'home/otp';
$route['terms'] 				  			= 'home/terms';
$route['result'] 				  			= 'home/result';
$route['practice_test'] 				  	= 'home/practice_test';
$route['practice_result'] 				  	= 'home/practice_result';
$route['default_controller'] 				= 'home';
$route['404_override'] 						= '';

/*-----------------------------------HOME END-----------------*/
?>