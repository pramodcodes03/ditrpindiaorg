<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'ssl://smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'sparkwiz.dev@gmail.com';                 // SMTP username
$mail->Password = 'SparkwizDev@2016';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->setFrom('sparkwiz.dev@gmail.com', 'Mailer');
$mail->addAddress('ritesh.dhawad@gmail.com', 'Joe User');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = '<!DOCTYPE html>
<html>
<head>
<title>PDF</title>
<style type="text/css">
*{color:#000;} table{border-collapse: collapse; width:100%; margin:auto; padding:auto;} .logo, .inst-name{text-align:center;} .logo img{height:50px;} .inst-name h2{text-transform: capitalize;} .sub-table{width:100%;} .course-name{text-align:left ;} .exam-date{text-align:right;} .answers{list-style:none;} .answers li{float:left; margin-right:30px} .b-btm{border-bottom: 1px solid;} .ans-opts td{padding: 10px 0px 30px 0px;} .que-ppr{font-size:17px;} .sub-table td{padding:10px} .check{height: 17px; width: 17px; border: 1px solid; margin-right: 5px;} .check, .check-lbl{float:left;} .clear{clear:both;}
</style>
</head>
<body>
<table class="main-table">
<tr>
	<td class="logo">
		<img src="resources/dist/img/logo.png" />
	</td>
</tr>
<tr>	
	<td class="inst-name">
		<h2>ALL INDIA COUNCIL FOR PROFESSIONAL EXCELLENCE</h2>
	</td>	
</tr>
<tr>
<td class="b-btm">
	<table class="sub-table">
		<tr>
			<td class="course-name"><strong>Course Name:</strong> JAVA PROGRAMMING</td>
			<td class="exam-date"><strong>Dated:</strong> 15/07/2016</td>
		</tr>
	</table>
</td>
</tr>
<tr>
	<td class="b-btm">
		<ul>
			<li><strong>Exam Duration:</strong> 60 Minutes</li>
			<li><strong>Total Questions:</strong> 60</li>
			<li><strong>Type of Questions:</strong>Multiple Choice, Single Answer</li>
			<li><strong>Total Marks:</strong> 100</li>
			<li><strong>Passing Marks:</strong> 40</li>
			<li><strong>Marks/Question:</strong> 1.67</li>
		</ul>
	</td>
</tr>
<tr>
	<td>
		<table class="que-ppr">
			<!-- Questions -->
			<tr>
				<td colspan="4">
					<strong>1.</strong> How many types of Measurement Units we can create in Tally?
				</td>
			</tr>
			<tr class="ans-opts">			
				<td>					
					<table>
						<tr>
							<td width="10%" valign="top">
								<div class="check"></div>
							</td>
							<td>cvcv</td>
						</tr>
					</table>
				</td>
				<td>					
					<table>
						<tr>
							<td width="10%" valign="top">
								<div class="check"></div>
							</td>
							<td>vcvcvc</td>
						</tr>
					</table>
				</td>
				<td>					
					<table>
						<tr> 
							<td width="10%" valign="top">
								<div class="check"></div>
							</td>
							<td>vcvcv</td>
						</tr>
					</table>
				</td>
				<td>					
					<table>
						<tr>
							<td width="10%" valign="top">
								<div class="check"></div>
							</td>
							<td>vcvcvc</td>
						</tr>
					</table>
				</td>
			</tr>		
		</table>
	</td>
</tr>
</table>
</body>
</html>
';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}