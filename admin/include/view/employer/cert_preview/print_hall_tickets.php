<?php

      ob_clean();
      ob_start();
      ini_set("memory_limit","128M");

	  $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
	  $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
	  
      include_once('include/classes/tools.class.php');
      $tools = new tools(); 

      $resB = $tools->list_backgroundimages('',$user_id,'');
      if($resB!='')
      {
        $srno=1;
        while($dataB = $resB->fetch_assoc())
        {
          extract($dataB);
            if($hallticket_image !=''){
                $hallticket_image     = BACKGROUND_IMAGE_PATH.'/'.$inst_id.'/'.$hallticket_image;
              }else{
                   $hallticket_image  = "resources/default/hall.jpg";
              }
         
        }
      }
	  
      include("include/plugins/pdf/mpdf.php");
      $mpdf=new mPDF('c','A4','','',0,0,0,0,16,13);     

      	$STUD_PHOTO = 'resources/dummy/dummy-photo.png';
		$STUD_SIGN = 'resources/dummy/dummy-signature.png';
        $file1 = 'resources/dummy/dummy_qr.png';
		$html ='';
		$html = '
		<style>
			#rcorners{position:absolute; margin:400px; right:55px;border:1px solid #333; top:0.2%;padding:5px; border-radius:25px;width:30%; height:5%; text-align:bottom;	
		};

		  #rcor{position:absolute; margin:20px; border:1px solid #333; top:1%;padding:5px; border-radius:25px;width:30%; height:10%; text-align:bottom;	margin-left:60%;	
		};
	   
	body {font-family:Arial; font-size: 12pt;position:absolute;z-index:0;top:0px; width:100%;}
	.studphoto{position:absolute;  width:135px; height:135px;background-image:url("'.$STUD_PHOTO.'"); background-size:135px 135px; background-repeat:no-repeat; top:23.9%; left:6.5%;}
    
    .studusername {position:absolute; top:355px; left:45%; font-size:10pt;}
    .studpassword {position:absolute; top:380px; left:45%; font-size:10pt;}
    
	.studname {position:absolute; top:430px; left:26%; font-size:10pt;}
	.fathername{position:absolute; top:478px; left:26%; font-size:10pt;}
	.surname{position:absolute; top:525px; left:26%; font-size:10pt;}
	.mothername{position:absolute; top:575px; left:26%; font-size:10pt;}

	.coursename{position:absolute; top:24.9%; left:40%; font-size:8pt; width:35%}

	.courseduration{position:absolute; top:27.5%; left:40%; font-size:8pt; width:35%}
	
	.examcenteraddress{position:absolute; top:55.8%; left:43%; font-size:8px; width:45%; line-height:10px}
	.institutename{position:absolute; top:54.7%; left:43%; font-size:7pt;}
	.centercode{position:absolute; top:48.5%; left:77%; font-size:9pt;}
	
	.examdate{position:absolute; top:38.3%; left:67%; font-size:9pt;}
	.examtime{position:absolute; top:42.5%; left:67%; font-size:9pt;}
	.examduration{position:absolute; top:46.6%; left:67%; font-size:9pt;}
	.reportingtime{position:absolute; top:50.9%; left:67%; font-size:9pt;}
	    
	.institutecontactnumber{position:absolute; top:62.5%; left:77%; font-size:9pt;}


	</style> ';
		$html .= '<img src="'.$hallticket_image.'" style="width:100%" />';
		$html .="<div class='studphoto'></div>";
		
		$html .="<div class='studusername'><b>Y32FD6BG</b></div>";
		$html .="<div class='studpassword'><b>5190473628</b></div>";
		
		$html .="<div class='studname'><b>RAHUL</b></div>";
		$html .="<div class='fathername'><b>RAM</b></div>";
		$html .="<div class='surname'><b>DAS</b></div>";
		$html .="<div class='mothername'><b>PUJA DAS</b></div>";

		$html .="<div class='coursename'><b>ADVANCE DIPLOMA IN COMPUTER APPLICATION (M-10001)</b></div>";

		$html .="<div class='courseduration'><b>1 Year</b></div>";

		$html .="<div class='examcenteraddress'><b>New Rushikesh Apartment, Near Cricket Ground, Virat Nagar, Virar West, Palghar, Mumbai, 401303, Maharashtra, India. Contact : 9898989898</b></div>";

		$html .="<div class='institutename'><b>NEXSTEP COMPUTER ACADEMY</b></div>";

		//$html .="<div class='centercode'><b>".$studentData['INSTITUTE_CODE']."</b></div>";

		$html .="<div class='examdate'><b>10-8-2023</b></div>";

		$html .="<div class='examtime'><b>9.00 AM TO 10.00 AM</b></div>";

		$html .="<div class='examduration'><b>60 MINS </b></div>";

		$html .="<div class='reportingtime'><b>8.30 AM </b></div>";

		//$html .="<div class='institutecontactnumber'><b>".$studentData['MOBILE']."</b></div>";

				
		$mpdf->allow_charset_conversion=true;  // Set by default to TRUE
   		$mpdf->charset_in = 'iso-8859-4';
		$mpdf->WriteHTML($html);		
		$mpdf->Output('Hall_Tikect.pdf','I');

?>