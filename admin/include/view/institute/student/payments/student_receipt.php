<?php
ob_clean();
ob_start();

$payid = isset($_GET['payid'])?$_GET['payid']:'';

//$checkstud = array(2);
if($payid!='' && !empty($payid))
{
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A5-L']);
    $mpdf->AddPageByArray([
        'margin-left' => 0,
        'margin-right' => 0,
        'margin-top' => 0,
        'margin-bottom' => 0,
    ]);

	include_once('include/classes/institute.class.php');
	$institute 	= new  institute();

	
	
    $sql = "SELECT *, get_student_name(STUDENT_ID) as STUDENT_NAME, get_institute_name(INSTITUTE_ID) AS INSTITUTE_NAME, get_institute_staff_name(STAFF_ID) AS STAFF_NAME FROM student_payments WHERE PAYMENT_ID='$payid'";
    $res = $db->execQuery($sql);
    if($res && $res->num_rows>0)
    {
        while($data = $res->fetch_assoc())
        {
            extract($data);
            //print_r($data); exit();
            $INSTITUTE_COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

            $batch_id = $db->get_batchid_fees($STUDENT_ID,$INSTITUTE_COURSE_ID);
            $batch_name = $db->get_batchname($batch_id);

            $RECIEPT_DATE = date('d-m-Y', strtotime($FEES_PAID_DATE));
        }
    }
	
//	$mpdf=new mPDF('c','A5-L','','',0,0,0,0,16,13); 
    $file = "";
	if($QRFILE !== ''){
	    $file = HTTP_HOST_ADMIN.'/'.$QRFILE;
	}
	
	include_once('include/classes/tools.class.php');
	$tools = new tools(); 
	
	$html='';	

	$resB = $tools->list_backgroundimages('',$INSTITUTE_ID,'');
	if($resB!='')
	{
	  $srno=1;
	  while($dataB = $resB->fetch_assoc())
	  {
		extract($dataB);		
		$feesreceipt_image    = BACKGROUND_IMAGE_PATH.'/'.$inst_id.'/'.$feesreceipt_image;
	  }
	}
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 8pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.receiptText{
        position:absolute;top:180px;text-align:left;left:50px;
    }
    .receiptNumber{
        position:absolute;top:170px;text-align:left;left:200px;border:1px solid #000; padding:10px; width:20%;
    }

    .receiptDateText{
        position:absolute;top:180px;text-align:right;;right:200px;
    }
    .receiptDateNumber{
        position:absolute;top:170px;text-align:right;right:78px;border:1px solid #000; padding:10px; width:11%;
    }

    .studentNameText{
        position:absolute;top:240px;text-align:left;left:50px;
    }
    .studentName{
        position:absolute;top:230px;width:100%;left:200px;border:1px solid #000; padding:10px; width:30%;
    }

    .courseNameText{
        position:absolute;top:300px;text-align:left;left:50px;
    }
    .courseName{
        position:absolute;top:290px;width:100%;left:200px;border:1px solid #000; padding:10px; width:62%;
    }

    .courseFeesText{
        position:absolute;top:350px;text-align:left;left:50px;
    }
    .courseFeesName{
        position:absolute;top:340px;text-align:left;left:165px;border:1px solid #000; padding:10px; width:8%;
    }

    .feesPaidText{
        position:absolute;top:350px;text-align:left;left:260px;
    }
    .feesPaidName{
        position:absolute;top:340px;text-align:left;left:390px;border:1px solid #000; padding:10px; width:8%;
    }

    .feesBalanceText{
        position:absolute;top:350px;text-align:left;left:510px;
    }
    .feesBalanceName{
        position:absolute;top:340px;text-align:left;left:620px;border:1px solid #000; padding:10px; width:8%;
    }
    
    .BatchText{
        position:absolute;top:240px;text-align:left;left:490px;
    }
    .BatchName{
        position:absolute;top:230px;width:100%;left:600px;border:1px solid #000; padding:10px; width:15%;
    }
    .qrheadtext{position:absolute;top:28.7%;left:73%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:77%;width:85px; height:85px; text-align:center; float:right; left:60%;}

	</style>';
	$html .= '
    <img src="'.$feesreceipt_image.'" style="width:100%;" />

        <h2 class="receiptText"> Receipt Number.:  </h2>
        <h3 class="receiptNumber"> '.$RECIEPT_NO.' </h3>

        <h2 class="receiptDateText">Receipt Date.: </h2>
        <h3 class="receiptDateNumber"> '.$RECIEPT_DATE.' </h3>

        <h2 class="studentNameText"> Student Name.: </h2>
        <h3 class="studentName"> '.$STUDENT_NAME.' </h3>

        <h2 class="courseNameText"> Course Name.:</h2>
        <h3 class="courseName"> '.$INSTITUTE_COURSE_NAME.' </h3>      

        <h2 class="courseFeesText"> Course Fees.: </h2>
        <h3 class="courseFeesName"> '.$TOTAL_COURSE_FEES.' </h3>

        <h2 class="feesPaidText"> Fees Received.:</h2>
        <h3 class="feesPaidName"> '.$FEES_PAID.' </h3>

        <h2 class="feesBalanceText"> Due Amount.: </h2>
        <h3 class="feesBalanceName"> '.$FEES_BALANCE.' </h3>

        <h2 class="BatchText"> Batch Name.:</h2>
        <h3 class="BatchName"> '.$batch_name.' </h3>       

        <div class="qrcodeimage"><img src="'.$file.'"></div> 
	
		';

	//==============================================================
	$mpdf->WriteHTML($html);
    $mpdf->Output($STUDENT_NAME.'_'.$RECIEPT_NO.'.pdf', 'I');
	//$mpdf->Output($file,'I');	
}
ob_flush();
ob_end_flush();
?>