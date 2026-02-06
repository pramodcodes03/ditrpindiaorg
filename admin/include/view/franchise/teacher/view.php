<?php
//error_reporting(E_ALL);
ini_set("memory_limit","128M");
$id =  isset($_GET['id'])?$_GET['id']:'';

if($id!='')
{
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	ob_clean(); 
	include_once('include/classes/websiteManage.class.php');
		
	$html='';	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => sys_get_temp_dir() . '/mpdf']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);
    
	//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	$websiteManage = new websiteManage();
	$res = $websiteManage->list_teacher($id,'');
	$data 	= $res->fetch_assoc();	
	extract($data);	
	
	include_once('include/classes/tools.class.php');
    $tools = new tools(); 
    
    $resB = $tools->list_backgroundimages('',$inst_id,'');
    if($resB!='')
    {
      $srno=1;
      while($dataB = $resB->fetch_assoc())
      {
	    //extract($dataB);	
	    //print_r($dataB);
	    if($dataB['teacherid_image'] !='' || $dataB['teacherid_image'] != NULL){
	        $imageId = $dataB['inst_id'];
    	    $teacherid_image = $dataB['teacherid_image'];
    	    $teacherid_image  = BACKGROUND_IMAGE_PATH.'/'.$imageId.'/'.$teacherid_image;
	    }else{
	        $teacherid_image  = "resources/default/teacherid.jpg";
	    }
	    
      }
    }
    $photo1="";
	if($photo !== ''){
	    $photo1 = TEACHERPHOTO_PATH.'/'.$id.'/'.$photo;
	}
    //echo $photo1; exit();
	$file1 = "";
	if($qr_file !== ''){
	    $file1 = HTTP_HOST_ADMIN.'/'.$qr_file;
	}
	
	$html2 = '
<style>
		body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studphoto{border:2px solid #9481ff;border-radius:50%;position:absolute;top:100px;width:100px; height:100px;background-image:url("'.$photo1.'"); background-size:100px 100px; background-repeat:no-repeat; left:10%;}

	.qrheadtext{position:absolute;top:5.5%;left:70.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:32%;width:40px; height:40px; text-align:center; float:right; left:20%;}
    
    .topname{position:absolute;top:195px;text-align:left;width:30%; font-size:20px; font-weight:900;  text-align:center;}
    
    .topdesignation{position:absolute;top:230px;text-align:left;width:30%; font-size:16px; font-weight:900; background-color:#9481ff; text-align:center;}
    
    .idnumber{position:absolute;top:260px;text-align:left;width:100%; font-size:10px; left:4%;}
    .designation{position:absolute;top:280px;text-align:left;width:100%; font-size:10px; left:4%;}
    .mobile{position:absolute;top:300px;text-align:left;width:100%; font-size:10px; left:4%;}
    .emailid{position:absolute;top:320px;text-align:left;width:100%; font-size:10px; left:4%;}
    

	</style>';
	$html = '	 <div class="qrcodeimage"><img src="'.$file1.'"></div> 
	            <div class="studphoto"></div>
	            
	            <p class="topname">'.$name.'</p>
	            <p class="topdesignation">'.$designation.'</p>
	            
	            <p class="idnumber"> ID Number : '.$code.'</p>
	            <p class="designation"> Designation : '.$designation.'</p>
	            <p class="mobile"> Mobile : '.$mobile.'</p>
	            <p class="emailid"> Email ID :'.$email.'</p>
			
	           
	          

				';
	
	
	$html2 .= '<img src="'.$teacherid_image.'" style="width:30%;" />';
	

	$html2 .= $html;
    
	//==============================================================
//	$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
   $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
   $mpdf->charset_in = 'iso-8859-4';
	
	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output($STUDENT_FNAME.' '.$STUDENT_LNAME.'_IDCard.pdf', 'I');
}
?>