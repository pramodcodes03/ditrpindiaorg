<?php
	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	$html='';	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);

	
	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$user_id,'');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
	    //extract($dataB);	
	    $imageId = $dataB['inst_id'];
	    $idcard_image = $dataB['idcard_image'];
	    $idcard_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$idcard_image;
      }
    }

	$STUD_PHOTO = 'resources/dummy/dummy-photo.png';
	$STUD_SIGN = 'resources/dummy/dummy-signature.png';
	$file1 = 'resources/dummy/dummy_qr.png';
	
	
	$html2 = '
<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.instname{position:absolute;top:239px;text-align:left;width:100%; font-size:07px; left:1%;}
	.studcode{position:absolute;top:278px;text-align:left;width:100%; font-size:10px; left:12.8%;}
	.admissiondate{position:absolute;top:184px;text-align:left;width:100%; font-size:08px; left:08%;}

	.rollnumber{position:absolute;top:293px;text-align:left;width:100%; font-size:12px; left:1.8%;}
	
	.instaddress1{position:absolute;top:262px;text-align:left;width:19.5%; font-size:07px; left:1.4%;}
	.instaddress2{position:absolute;top:282px;text-align:left;width:19.5%; font-size:07px; left:1.4%;}
	
	.instmobile{position:absolute;top:304px;text-align:left;width:100%; font-size:08px; left:8%;}
	
	.studentfirstname{position:absolute;top:217px;text-align:left;width:100%; font-size:10px; left:12.5%;}
	.studentmiddlename{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:30%;}
	.studentlastname{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:53%;}
	.studentmothername{position:absolute;top:310px;text-align:left;width:100%; font-size:12px; left:76%;}

	.studentheading{position:absolute;top:185px;text-align:left;width:100%; font-size:08px; left:11.5%;}
	.courseheading{position:absolute;top:248px;text-align:left;width:100%; font-size:08px; left:11.5%;}
	.mobileheading{position:absolute;top:290px;text-align:left;width:100%; font-size:08px; left:11.5%;}

	
	.coursename{position:absolute;top:235px;text-align:left;width:15%; font-size:06px; left:12.5%; line-height:9px; }
	
	.adharno{position:absolute;top:375px;text-align:left;width:100%; font-size:12px; left:7%;}
	.mobileno{position:absolute;top:258px;text-align:left;width:100%; font-size:10px; left:14%;}
	.mobilealtno{position:absolute;top:375px;text-align:left;width:100%; font-size:12px; left:71%;}
	
	.email{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:47%;}
	
	.dob{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:30.6%;}
	
	.gender{position:absolute;top:440px;text-align:left;width:100%; font-size:12px; left:7%;}
	
	.occupation{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:40%;}
	.qualifiaction{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:21%;}
	.state{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:65%;}
	.postcode{position:absolute;top:505px;text-align:left;width:100%; font-size:12px; left:86%;}
	.permanantaddress{position:absolute;top:575px;text-align:left;width:100%; font-size:12px; left:7%;}
	
	.studphoto{position:absolute;top:127px;width:80px; height:90px;background-image:url("'.$STUD_PHOTO.'"); background-size:80px 90px; background-repeat:no-repeat; left:10%;}

	.qrheadtext{position:absolute;top:5.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:27.1%;width:40px; height:40px; text-align:center; float:right; left:20%;}
    

	</style>';
	$html = '	 <div class="qrcodeimage"><img src="'.$file1.'"></div> 
	            <div class="studphoto"></div>
			
	           
	            <p class="studentfirstname">RAHUL</p>
			
	            <p class="coursename">ADVANCE DIPLOMA IN COMPUTER APPLICATION (M-10001)</p>
	           <!-- <p class="admissiondate">'.$JOINING_FORMATED.'</p> -->
			

				<p class="mobileno">9898989898</p>
				
				<p class="studcode">Y32FD6BG</p>

				<p class="rollnumber"><strong>  Roll Number :101 </strong></p>

				';
	
	
	$html2 .= '<img src="'.$idcard_image.'" style="width:30%;" />';
	

	$html2 .= $html;
    
	//==============================================================
	//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
   $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
   $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output('IDCard.pdf', 'I');

?>