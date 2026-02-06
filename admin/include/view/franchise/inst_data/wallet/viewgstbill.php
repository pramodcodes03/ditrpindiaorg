<?php
ob_clean();

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

$file = "Payment Bill.pdf";


	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();
	$html='';	
	
	
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
    
    $cond='';
    
	$res = $access->get_payment_bill_details($id,$cond);	
	$data 	= $res->fetch_assoc();
	
	extract($data);
    //print_r($data); exit();
	
	$cgst='';
	$sgst='';
	if($STATE=='17'){
	    $cgst = $GST/2;
	    $sgst =$GST/2;
	    $GST='';
	}
	//==============================================================
	
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.transno{position:absolute;top:105px;left:42%;width:100%; }
	.date{position:absolute;top:105px;text-align:center;width:100%; left:24%; }
	.instname{position:absolute;top:235px;text-align:left;width:100%; left:10%; font-size:12px;}
    .gstnumber{position:absolute;top:348px;text-align:left;width:100%; left:15%; font-size:16px;}
    
    .srno{position:absolute;top:445px;text-align:left;width:100%; left:08%; font-size:14px; font-weight:100;}
    .rechargedetails{position:absolute;top:445px;text-align:left;width:100%; left:15%; font-size:14px; font-weight:100;}
    .amount{position:absolute;top:445px;text-align:left;width:100%; left:80%;  font-size:14px; font-weight:100;}
    
    .CGST{position:absolute;top:650px;text-align:left;width:100%; left:65%;  font-size:14px; font-weight:100;}
    .SGST{position:absolute;top:670px;text-align:left;width:100%; left:65%;  font-size:14px; font-weight:100;}
    .IGST{position:absolute;top:690px;text-align:left;width:100%; left:65%; font-size:14px; font-weight:100;}
    
    .totalamount{position:absolute;top:730px;text-align:left;width:100%; left:80%; font-size:16px;}
    
    .mg_left{margin-left:5%;}
    
    
	</style>';
	$html .= '
	<img src="resources/dist/gstinvoice.jpg" style="width:100%" />

				<h2 class="transno">#'.$TRANSACTION_NO.'</h2>
				<h3 class="date">'.$CREATED_DATE.'</h3>
				
				<h4 class="instname"> ATC Code : '.$INSTITUTE_CODE.' <br/> '.$INSTITUTE_NAME.' <br/> Owner Name : '.$INSTITUTE_OWNER_NAME.' &nbsp;&nbsp; Mobile : '.$MOBILE.' <br/> '.$ADDRESS_LINE1.'
				<br/> '.$TALUKA.' <br/> '.$CITY_NAME.' &nbsp;'.$POSTCODE.'&nbsp;('.$STATE_NAME.') 
				</h4>
				
				<h2 class="gstnumber"> '.$GSTNO.'</h2>
				
			    <h2 class="srno">1)</h2>
				<h2 class="rechargedetails">  Recharge Wallet For Certificate Issue </h2>
				<h2 class="amount">'.$PAYMENT_AMOUNT.'</h2>
				
		    	<h2 class="CGST">CGST 9 %  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> '.$cgst.'</span></h2>
				<h2 class="SGST">SGST 9 %  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> '.$sgst.'</span></h2>
				
				<h2 class="IGST">IGST 18%  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span> '.$GST.'</span></h2>
					
				<h2 class="totalamount">'.$TOTAL_AMOUNT.'</h2>
			
			
				';
				
	$mpdf->WriteHTML($html);
	$mpdf->Output($file,'I');

ob_end_flush();
?>