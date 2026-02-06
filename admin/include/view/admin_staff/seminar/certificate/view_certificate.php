<?php
ob_clean();
ob_start();

$id = isset($_GET['id'])?$_GET['id']:'';

if($id!='' && !empty($id))
{
	date_default_timezone_set("Asia/Kolkata");
	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L','orientation' => 'L']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
    
	include_once('include/classes/seminar.class.php');
	$seminar 	= new  seminar();	

	include_once('include/classes/tools.class.php');
	$tools = new tools(); 
	
	$html='';	

	$resB = $tools->list_backgroundimages('','1','');
	if($resB!='')
	{
	  $srno=1;
	  while($dataB = $resB->fetch_assoc())
	  {
		$imageId = $dataB['inst_id'];
		$seminar_image = $dataB['seminar_image'];
		$seminar_image    = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$seminar_image;
	  }
	}
	
	$res = $seminar->list_seminar_student_print($id);	
	$data 	= $res->fetch_assoc();
	
	extract($data);
	//print_r($data); exit();	    
	
	$sign = SEMINAR_DOCUMENTS_PATH.'/'.$seminar_id.'/'.$sign; 
	$stamp = SEMINAR_DOCUMENTS_PATH.'/'.$seminar_id.'/'.$stamp;
	
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}

	.heading{position:absolute;top:210px;text-align:center;width:100%;text-decoration: underline; font-weight:bold;font-size: 26px;}

	.text-box{position:absolute;top:250px;width:80%;left:10%;right:30%;}
	.text{position:absolute;text-align:justify;width:100%; font-size:21px; line-height:32px}

	.place{position:absolute;top:600px;text-align:justify;font-size:20px; line-height:30px;width:80%;left:10%;right:30%;}
	.date{position:absolute;top:630px;text-align:justify;font-size:20px; line-height:30px;width:80%;left:10%;right:30%;}

	.sign{position:absolute;bottom:130px;left:70%;width:200px; height:60px;background-image:url("'.$sign.'"); background-size:200px 60px; background-repeat:no-repeat;}

	.stamp{position:absolute;bottom:75px;left:70%;width:200px; height:60px;background-image:url("'.$stamp.'"); background-size:200px 60px; background-repeat:no-repeat;}

	.directorname{position:absolute;bottom:45px;text-align:justify;font-size:16px; line-height:30px;width:80%;left:75%;font-weight:bold;}
   
	</style>';
	$html .= '
				<img src="'.$seminar_image.'" style="width:100%" />
				<h2 class="heading"> CERTIFICATE OF PARTICIPATION </h2>

				<div class="text-box"> 
					<p class="text">
						This is to certify that Ms./Mrs./Shri/Dr. '.$name.', CRR No. '.$crr_no.' has participated
						in the ';
						$seminar_name1 = $seminar_name2 = $seminar_name3 = $seminar_name4 = '';

						if($seminar_type == 'CRE Programme'){
							$seminar_name1 = '<strong> CRE Programme </strong>';
						}else{
							$seminar_name1 = 'CRE Programme';
						}
						
						if($seminar_type == 'Workshop'){
							$seminar_name2 = '<strong> Workshop </strong>';
						}else{
							$seminar_name2 = 'Workshop';
						}
						if($seminar_type == 'Seminar'){
							$seminar_name3 = '<strong> Seminar </strong>';
						}else{
							$seminar_name3 = 'Seminar';
						}
						if($seminar_type == 'Conference'){
							$seminar_name4 = '<strong> Conference </strong>';
						}else{
							$seminar_name4 = 'Conference';
						}
						
						$html .= $seminar_name1.' / '.$seminar_name2.' / '.$seminar_name3.' / '.$seminar_name4;
						
						$html .= ' <strong>('.$mode.')</strong> on the topic <strong>" '.$topic_name.' "</strong> approved by the Rehabilitation Council of
						India, a Statutory Body of the Ministry of Social Justice and Empowerment, Department
						of Empowerment of Persons with Disabilities (Divyangjan), Govt. of India vide approval
						No. <strong>'.$approval_no.'</strong> conducted <strong>'.$fee_date.'</strong> at <strong>'.$college_name.', '.$address.'</strong> as ';

						$typename1 = $typename2 = $typename3 = $typename4 = $typename5 = $typename6 = $typename7 = $typename8 = '';

						if($type == 'Chairperson'){
							$typename1 = '<strong> Chairperson </strong>';
						}else{
							$typename1 = 'Chairperson';
						}
						
						if($type == 'Resource persons'){
							$typename2 = '<strong> Resource persons </strong>';
						}else{
							$typename2 = 'Resource persons';
						}
						if($type == 'Keynote Speaker'){
							$typename3 = '<strong> Keynote Speaker </strong>';
						}else{
							$typename3 = 'Keynote Speaker';
						}
						if($type == 'Paper Presentation'){
							$typename4 = '<strong> Paper Presentation </strong>';
						}else{
							$typename4 = 'Paper Presentation';
						}
						if($type == 'Poster Presentation'){
							$typename5 = '<strong> Poster Presentation </strong>';
						}else{
							$typename5 = 'Poster Presentation';
						}
						if($type == 'Instructor'){
							$typename6 = '<strong> Instructor </strong>';
						}else{
							$typename6 = 'Instructor';
						}
						if($type == 'Coordinator'){
							$typename7 = '<strong> Coordinator </strong>';
						}else{
							$typename7 = 'Coordinator';
						}
						if($type == 'Participant'){
							$typename8 = '<strong> Participant </strong>';
						}else{
							$typename8 = 'Participant';
						}
						
						$html .= $typename1.' / '.$typename2.' / '.$typename3.' / '.$typename4.' / '.$typename5.' / '.$typename6.' / '.$typename7.' / '.$typename8;
						
						$html .=' with <strong>'.$cre_points.'</strong> CRE Points '.$session.'. 
					</p>
					
				</div>
				<p class="place"> Place: <strong>'.$place.' </strong></p>
				<p class="date"> Date: <strong>'.date("d/m/Y",strtotime($date)).'</strong> </p>

				<div class="sign"></div>
				<div class="stamp"></div>
				<div class="directorname"> ( '.$conductor_name.' ) </div>
			
				';

	//==============================================================
 
     $mpdf->WriteHTML($html);
	 //$blankpage = $mpdf->page + 1;
 	 //$mpdf->DeletePages($blankpage);
	 $mpdf->Output($name.'_certificate.pdf', 'I');	
	
}
ob_flush();
ob_end_flush();
?>