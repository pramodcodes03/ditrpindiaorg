<?php

include_once('database_results.class.php');

include_once('access.class.php');



class emails extends access

{



	/*----------------- institute --------------------------------- */

	public function institute_to_staff()

	{

		

	}

	https://

	

	//student apply for jobs

	public function apply_for_job()

	{

		$job_post_id 	= parent::test($_POST['job_post_id'])?$_POST['job_post_id']:'';

		$emp_email		= parent::test($_POST['emp_email'])?$_POST['emp_email']:'';

		$subject 		= parent::test($_POST['subject'])?$_POST['subject']:'';

		$message 		= isset($_POST['message'])?$_POST['message']:'';

		$resume 		= isset($_POST['resume'])?$_POST['resume']:'';

		

		if($resume!='')

			$message .= '<br><br> <a href="http://'.$resume.'" title="Link: '.$resume.'" target="_blank">Download Resume</a>'; 

		

		return parent::send_email(EMAIL_USERNAME,'Admin',$emp_email,'Employer', $subject, $message);

	}



}

?>