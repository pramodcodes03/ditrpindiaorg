<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="<?=base_url()?>assets/front_assets/bootstrap/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url()?>assets/front_assets/js/modernizr.custom.63321.js"></script>
<link href="<?=base_url()?>assets/front_assets/bootstrap/dist/css/sb-admin-2.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/css/style3.css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<title>ONLINE EXAM</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<!-- <script type = "text/javascript" >
       function preventBack(){window.history.forward();}
        setTimeout("preventBack()", 0);
        window.onunload=function(){null};
    </script>-->
</head>
<body onunload="bodyUnload();"  Onclick="clicked=true;">
<?php

$get_exam_result = $this->home_model->get_exam_result();

$student_course_detail_id = $this->session->userdata('student_course_detail_id');
$course_info 			= $this->home_model->get_student_course_details($student_course_detail_id);
$inst_course_info 		= $this->home_model->get_inst_course_info($course_info);
$ins_name 				= $this->home_model->get_institute($get_student['INSTITUTE_ID']);

$subject_id = $this->session->userdata('multi_subject_id');

$subject_name =array();
if(!empty($inst_course_info['COURSE_ID']) && $inst_course_info['COURSE_ID'] != '' && $inst_course_info['COURSE_ID'] != '0'){
  $exam_name 				= $this->home_model->get_exam($inst_course_info['COURSE_ID']);
}

if(!empty($inst_course_info['MULTI_SUB_COURSE_ID']) && $inst_course_info['MULTI_SUB_COURSE_ID'] != '' && $inst_course_info['MULTI_SUB_COURSE_ID'] != '0'){
  $exam_name 				= $this->home_model->get_exam_multi_subject($inst_course_info['MULTI_SUB_COURSE_ID'],$subject_id);
  $subject_name = $this->home_model->get_subject_name($inst_course_info['MULTI_SUB_COURSE_ID'],$subject_id);
}
?>
<div id="wrapper">
  <div id="page-wrapper">
  <div class="container">
  <header style="text-align:center"> 
  
   <div class="support-note"> <span class="note-ie">Sorry, only modern browsers.</span> </div>
  </header>
    <div class="row">
      <div class="col-lg-12">
        <div class="row" style="margin-top:10px;">
          <div class="col-lg-12">
            <div class="">
            <div class="col-md-8">
            <div class="panel-heading" style="background:#F5F5F5;padding:10px; font-weight:bold"> RESULT </div>
                       
                       <div class="panel-body" style="border:1px solid #ccc; margin-bottom:5px">
                <div class="panel-group" id="accordion">
                <?php
			
                      if($exam_name['SHOW_RESULT']==1)
					  {
					  ?>
                       <table class="table table-bordered  table-hover" width="50%" style="border:1px solid;">
                       <tbody>
                      <tr>
                        <th valign="top" align="left">Exam Duration</th>
                        <td><?=$exam_name['EXAM_TIME']?> Minutes</td>
                      </tr>
                      <tr>
                        <th valign="top" align="left">Total Questions : </th>
                        <td><?=$exam_name['TOTAL_QUESTIONS']?></td>
                      </tr>                      
                      <tr>
                        <th valign="top" align="left">Total Marks: </td>
                        <td><?=$exam_name['TOTAL_MARKS']?></td>
                      </tr>
                      <tr>
                        <th valign="top" align="left">Passing Marks:</th>
                        <td><?=$exam_name['PASSING_MARKS']?></td>
                     
                      <tr>
                      <th valign="top" align="left">Marks/Qus:</th>
                        <td valign="top"><?php $marks_per = $exam_name['TOTAL_MARKS']/$exam_name['TOTAL_QUESTIONS']; echo number_format($marks_per, 2);?></td>
                      </tr>
                     <tr>
                      <th valign="top" align="left">Correct answers:</th>
                        <td valign="top"><?= $get_exam_result['CORRECT_ANSWER'] ?></td>
                      </tr>
                      <tr>
                      <th valign="top" align="left">Incorrect answers:</th>
                        <td valign="top"><?=$get_exam_result['INCORRECT_ANSWER']?></td>
                      </tr>
                      <tr>
                      <th valign="top" align="left">Total Marks Obtained:</th>
                        <td valign="top"><?= $get_exam_result['MARKS_OBTAINED'] ?></td>
                      </tr>
                      <tr>
                      <th valign="top" align="left">Percentage marks &amp; Grade:</th>
                        <td valign="top"><?php echo $get_exam_result['MARKS_PER'].'% - '.$get_exam_result['GRADE'] ?></td>
                      </tr>
                      <tr>
                      <th valign="top" align="left">Status:</th>
                        <td valign="top"><?php if($get_exam_result['RESULT_STATUS']=='Passed'){ echo '<font color="#00FF00">'.$get_exam_result['RESULT_STATUS'].'</font>';}else{ echo '<font color="#FF0000">'.$get_exam_result['RESULT_STATUS'].'</font>';}?></td>
                      </tr>
                    </tbody>
                  </table>
                  <table class="table table-hover">
                 <tbody>
                <tr>
                  <td valign="top" >
				  <?php $adminFrm = array('name' => 'submitStu', 'id' => 'submitStu');?>
                    <?php echo form_open_multipart('',$adminFrm); ?>
                     </td>
                  <td style="float:right">
                    
                    <?php echo anchor('home/logout','EXIT', array('data-toggle' => 'tooltip', 'data-original-title' => 'Logout', 'data-placement' =>'bottom',  'class' => 'btn btn-success amazing2')); 
                ?></td>
                
                </form>
                </tr>
                 </tbody>
                  </table>
                  <?php
                  }
				  else
				  {
					?>
                    <table class="table table-hover">
                 <tbody>
                <tr>
                  
                  <td style="text-align:center">
                   <p>Your result is recorded by system and will be informed to you shortly by your institute</p>
                  <p style="margin: 20px;"> <?php 
                    echo anchor('home/logout','EXIT', array('data-toggle' => 'tooltip', 'data-original-title' => 'Logout', 'data-placement' =>'bottom',  'class' => 'btn btn-success amazing2')); 
                ?></p>
                    
                  </td>
                </tr>
                 </tbody>
                  </table>
                    <?php  
				  }
				  ?>
                </div>
              </div>
            </div>
            <div class="col-md-4">
            <div class="panel-heading" style="background:#F5F5F5;padding:10px; font-weight:bold"> STUDENT DETAILS </div>
              <!-- .panel-heading -->
              <div class="panel-body" style="border:1px solid #ccc; margin-bottom:5px">
                <div class="panel-group" id="accordion">
              <table class="table table-hover"  width="100%" cellpadding="10" cellspacing="10">
                    <tbody>
                      <tr>
                        <td><strong>NAME:  <?=ucwords($get_student['full_name'])?></strong></td>
                        <td><img src="<?=base_url()?>../uploads/student/<?= $get_student['STUDENT_ID'] ?>/<?=$get_student['stud_photo']?>" style="width:100px; float:right"></td>
                      </tr>
                      <tr>
                        <td style="position: absolute; top: 100px;"><strong>STUDENT ID: <?=$get_student['STUDENT_CODE']?></strong></td>                       
                      </tr>
							<table>
								<tr>
									<td>Date Of Birth</td>
									<td>:</td>
									<td><?=$get_student['DOB'];?></td>
								</tr>
							    <tr>
									<td>Gender</td>
									<td>:</td>
									<td><?php if($get_student['STUDENT_GENDER']=='male'){ echo "MALE";}else{ echo "FEMALE";}?></td>
							    </tr>
							    <tr>
									<td>Mobile</td>
									<td>:</td>
									<td><?=$get_student['STUDENT_MOBILE'];?></td>
							    </tr>
								<tr>
									<td>Institute</td>
									<td>:</td>
									<td><?=ucwords($ins_name['INSTITUTE_NAME'])?></td>
							    </tr>
                  <tr>
                    <td>Course</td>
                    <td>:</td>
                    <td><?=$inst_course_info['COURSE_NAME']?> <?=$inst_course_info['MULTI_SUB_COURSE_NAME']?></td>
                  </tr>
                  <tr>
                    <td>Subject Name</td>
                    <td>:</td>
                    <td><?=$subject_name['COURSE_SUBJECT_NAME']?> </td>
                  </tr>
							</table>
						</td>               
            </tr>         
            </tbody>
            </table>
            </div>
            </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Bootstrap Core JavaScript --> 
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="<?=base_url()?>assets/front_assets/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
<!-- Custom Theme JavaScript --> 
<script src="<?=base_url()?>assets/front_assets/bootstrap/bower_components/metisMenu/dist/metisMenu.min.js"></script> 
<script src="<?=base_url()?>assets/front_assets/bootstrap/dist/js/sb-admin-2.js"></script>
</body></html>
<style>
.amazing {
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: -moz-linear-gradient(center top , #5CB85C 0%, #3A933A 100%);
    background-origin: padding-box;
    background-position: 0 0;
    background-repeat: repeat;
    background-size: auto auto;
    border-bottom-color: #256E25;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-image-outset: 0 0 0 0;
    border-image-repeat: stretch stretch;
    border-image-slice: 100% 100% 100% 100%;
    border-image-source: none;
    border-image-width: 1 1 1 1;
    border-left-color: #256E25;
    border-left-style: solid;
    border-left-width: 1px;
    border-right-color: #256E25;
    border-right-style: solid;
    border-right-width: 1px;
    border-top-color: #256E25;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-top-style: solid;
    border-top-width: 1px;
    box-shadow: 0 1px 1px #d3d3d3, 0 1px 0 #fee395 inset;
    display: inline-block;
    height: 38px;
    margin: 10px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
    overflow-x: hidden;
    overflow-y: hidden;
    padding-bottom: 10px;
    padding-left: 50px;
    padding-right: 50px;
    padding-top: 10px;
    position: relative;
    transition-delay: 0s;
    transition-duration: 0.3s;
    transition-property: all;
    transition-timing-function: linear; margin-top:-2px
}
.amazing2 {
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: -moz-linear-gradient(center top , #FF0015 0%, #B71926 100%);
	background: -webkit-linear-gradient(#FF0015, #B71926);
    background-origin: padding-box;
    background-position: 0 0;
    background-repeat: repeat;
    background-size: auto auto;
    border-bottom-color: #B71926;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-image-outset: 0 0 0 0;
    border-image-repeat: stretch stretch;
    border-image-slice: 100% 100% 100% 100%;
    border-image-source: none;
    border-image-width: 1 1 1 1;
    border-left-color: #B71926;
    border-left-style: solid;
    border-left-width: 1px;
    border-right-color: #B71926;
    border-right-style: solid;
    border-right-width: 1px;
    border-top-color: #B71926;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-top-style: solid;
    border-top-width: 1px;
    box-shadow: 0 1px 1px #d3d3d3, 0 1px 0 #fee395 inset;
    display: inline-block;
    height: 38px;
    margin: 10px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
    overflow-x: hidden;
    overflow-y: hidden;
    padding-bottom: 10px;
    padding-left: 50px;
    padding-right: 50px;
    padding-top: 10px;
    position: relative;
    transition-delay: 0s;
    transition-duration: 0.3s;
    transition-property: all;
    transition-timing-function: linear; margin-top:-2px;
    color:#fff;
}
</style>
<script type="text/javascript">
 
var clicked = false;  
 function CheckBrowser()  
   {      
      if (clicked == false)   
         {      
          //Browser closed   
         }        else  
          {  
          //redirected
             clicked = false; 
           } 
   }  
  function bodyUnload() 
   {      
      if (clicked == false)//browser is closed  
          {   
         var request = GetRequest();  
           request.open  ("POST", "<?=base_url()?>home/logout", false);    
       request.send();    
        } 
   } 
 
   function GetRequest()  
     {       
     var xmlhttp;
        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        return xmlhttp;
      } 
 
</script>
<!--<script type="text/javascript">
 window.onbeforeunload = function () {
	 return"Are you sure you want to leave this page?";
              var request = GetRequest();  
           request.open  ("POST", "<?=base_url()?>home/logout", false);    
       request.send(); 
};
</script>-->