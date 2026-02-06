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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<link href="<?=base_url()?>assets/front_assets/css/facebook.alert.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/js/jquery_facebook.alert.js"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<title>ONLINE EXAM</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
 <script type = "text/javascript" >
       function preventBack(){window.history.forward(1);}
        setTimeout("preventBack()", 0);
        window.onunload=function(){null};
    </script>
</head>
<body onunload="bodyUnload();" Onclick="clicked=true;">

<?php
$exam_type = $this->session->userdata('exam_type');
$student_id = $this->session->userdata('student_id');
$student_course_detail_id = $this->session->userdata('student_course_detail_id');
$subject_id = $this->session->userdata('multi_subject_id');
$ins_name 				= $this->home_model->get_institute($get_student['INSTITUTE_ID']);

$inst_course_id 			= $this->home_model->get_student_course_details($student_course_detail_id);
$attempt_info 			= $this->home_model->get_exam_attempt($student_course_detail_id);
$exam_status 			= $this->home_model->get_exam_status($student_course_detail_id);
//var_dump($exam_status);
//echo $inst_course_id;
$inst_course_info 		= $this->home_model->get_inst_course_info($inst_course_id);
//print_r($inst_course_info);
$subject_name =array();
if(!empty($inst_course_info['COURSE_ID']) && $inst_course_info['COURSE_ID'] != '' && $inst_course_info['COURSE_ID'] != '0'){
  $exam_name 				= $this->home_model->get_exam($inst_course_info['COURSE_ID']);
}
if(!empty($inst_course_info['MULTI_SUB_COURSE_ID']) && $inst_course_info['MULTI_SUB_COURSE_ID'] != '' && $inst_course_info['MULTI_SUB_COURSE_ID'] != '0'){
  $exam_name 				= $this->home_model->get_exam_multi_subject($inst_course_info['MULTI_SUB_COURSE_ID'],$subject_id);
  $subject_name = $this->home_model->get_subject_name($inst_course_info['MULTI_SUB_COURSE_ID'],$subject_id);
}
//print_r($exam_name);
//print_r($subject_name);

//var_dump($exam_name);
 $demo_per = $ins_name['DEMO_PER'];

 $stdu_demo= $this->home_model->get_exam_demo_stud($student_course_detail_id);
 //var_dump($stdu_demo);
?>
<div id="wrapper">
  <div id="page-wrapper">
  <div class="container">
  <header> 
    <div class="row">    
      <div class="col-md-12" style="text-align:center; color:#000;">
        <h1 style="color: #00565f;
    font-weight: 600;
    font-family: sans-serif;
    line-height: 24px;
    font-size: 28px;"> Online Exam Portal </h1>
      </div>
      <div class="support-note"> <span class="note-ie">Sorry, only modern browsers.</span> </div>
    </div>
  </header>
    <div class="row">
      <div class="col-lg-12">
        <div class="row" style="margin-top:10px;">
          <div class="col-lg-12">
            <div class="">
              <div class="col-md-6">
              <div class="panel-heading" style="background:#F5F5F5; padding:10px; font-weight:bold"> STUDENT DETAILS </div>
              <!-- .panel-heading -->
              <div class="panel-body" style="border:1px solid #ccc; margin-bottom:5px">
                <div class="panel-group" id="accordion">
                  <table class="table table-hover"  width="100%" cellpadding="10" cellspacing="10">
                    <tbody>
                      <tr>
                        <td><strong>NAME:  <?=ucwords($get_student['full_name'])?></strong></td>
                        <td style="float:right"><strong>STUDENT ID: <?=$get_student['STUDENT_CODE']?></strong></td>
                      </tr>					  
                      <tr>
                      <td>
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
                              <td>Course Name</td>
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
                      <?php 
                      $stud_photo = base_url()."assets/img/nouser.png";
                      if(!empty($get_student['stud_photo']))
                      {
                        $stud_photo = "../uploads/student/".$get_student['STUDENT_ID']."/".$get_student['stud_photo'];
                      }
                      ?>
					
						  
                          <td><img src="<?= $stud_photo ?>" style="width: 200px; float: right; border-radius: 20px;"></td>
                      </tr>
                      </tbody>
                      </table>
                      </div>
                      </div>
              </div>
              <div class="col-md-6">
              <div class="panel-heading" style="background:#F5F5F5; padding:10px; font-weight:bold"> EXAM TERMS </div>
                       <div class="panel-body" style="border:1px solid #ccc">
                      <div class="panel-group" id="accordion" >
                       <table class="table table-hover" width="100%">
                       <tbody>
                      <tr>
                        <th valign="top">Exam Duration</th>
                        <td valign="top"><?=$exam_name['EXAM_TIME']?> Minutes</td>
                      </tr>
                      <tr>
                        <th valign="top">Total Questions : </th>
                        <td valign="top"><?=$exam_name['TOTAL_QUESTIONS']?></td>
                      </tr>
                      <tr>
                        <th valign="top">Type of Questions:</th>
                        <td valign="top">Multiple Choice, Single Answer</td>
                      </tr>
                      <tr>
                        <th valign="top">Total Marks: </td>
                        <td valign="top"><?=$exam_name['TOTAL_MARKS']?></td>
                      </tr>
                      <tr>
                        <th valign="top">Passing Marks:</th>
                        <td valign="top"><?=$exam_name['PASSING_MARKS']?></td>
                     
                      <tr>
                      <th valign="top">Marks/Qus:</th>
                        <td valign="top"><?php $marks_per = $exam_name['TOTAL_MARKS']/$exam_name['TOTAL_QUESTIONS']; echo number_format($marks_per, 2);?></td>
                      </tr>
                      <!-- <th valign="top">Select Language <span style="color:red">*</span>:</th>
                      <td valign="top"><select class="form-control" name="lang_id1" id="lang_id1" onchange="">
                            <option value="1" selected="selected"> Select Language </option>
                            <option value="1" selected="selected"> English </option>
                            <option value="2"> Hindi </option>
                        </select></td>
                      </tr> -->
                     
                    </tbody>
                  </table>
                  <table class="table table-hover">
                 <tbody>
                 <?php
                  if($exam_status==3)
				  {
					  echo '<tr><td valign="top" colspan="2" style="text-align:center">';
				  	echo '<font color="#EE0619">You have already appeared for exam!</font>';
					echo "</td></tr>";
				  }
				  ?><br />
                <tr>
                  <td valign="top" >
				  <?php $adminFrm = array('name' => 'submitStu', 'id' => 'submitStu');?>
                    <?php echo form_open_multipart('',$adminFrm); ?>
                    <input type="hidden" id="lang1" name="lang1" value="">
                    <input type="checkbox" id="terms" name="terms" value="1" required >
                    &nbsp;
                    I agree to terms and condition of exam. (Tick this box)
					</td>
        </tr>
          <tr>
                  <td>
                  <?php
                  if($exam_name['DEMO_TEST']==1 && $exam_type=='demo')
                      {
                      ?>
                                          
                        <a href="#"  class="btn btn-success amazing" style="color:#fff;" onclick="getPractice()" >DEMO EXAM</a>
                                 
                                
                        
                                <?php
                      
                      }
				            ?>
                  <?php
                    if($exam_status==2)
                    {
                      $disabled = '';
                    }
                    else
                    {
                    $disabled =	'disabled="disabled" style="background:#ccc"';  
                    }
                    if($exam_type=='final'){
                    ?>
                    <span id="submites" style="cursor:pointer; color:#fff;" class="btn btn-success amazing3" <?=$disabled?> onclick="Validate()"> FINAL EXAM </span>
                   
				   <?php } echo anchor('home/logout','EXIT', array('data-toggle' => 'tooltip', 'data-original-title' => 'Logout', 'data-placement' =>'bottom',  'class' => 'btn btn-success amazing2', 'style'=>'color:#fff')); 
                ?></td>
                </form>
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
	background: -webkit-linear-gradient(#5CB85C, #3A933A);
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
    transition-timing-function: linear; margin-top:-2px
}
.amazing3 {
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-attachment: scroll;
    background-clip: border-box;
    background-color: rgba(0, 0, 0, 0);
    background-image: -moz-linear-gradient(center top , #FF984F 0%, #F67A22 100%);
	background: -webkit-linear-gradient(#FF984F, #F67A22);
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
    transition-timing-function: linear; margin-top:-2px
}
</style>
<?php $StatusFrmId = array('name' => 'actFrm', 'id' => 'actFrm');?>
<?php echo form_open('',$StatusFrmId); ?>
<input type="hidden" value="1" name="actval" id="actval" />
<div style="    position: relative;
        top: -200px;
        width: 20%;
        margin: 5px 80px;">
    <label> Select Language <span style="color:red">*</span>:</label>
    <select class="form-control" name="lang_id" id="lang_id" required="required" onchange="getLanguageValue(this.value)" >  
        <option value=""> Please Select Lanaguge </option>    
        <option value="1"> English </option>
        <option value="2"> Hindi </option>
    </select>
    <span class="help-block"><?= isset($errors['lang_id'])?$errors['lang_id']:'' ?></span>    
</div>

<?php echo form_close(); ?>

<script>
function getPractice()
{
  var e = document.getElementById("lang_id");
  var strUser = e.options[e.selectedIndex].value;

  var strUser1 = e.options[e.selectedIndex].text;
  if(strUser==0)
  {
      alert("Please select a language");
  }else{
    document.getElementById('actval').value=1;	
	  document.actFrm.submit();
  }
}
</script>
<script type="text/javascript">
 
var clicked = false;  
 function CheckBrowser()  
   {      
      if (clicked == false)   
         {      
          //Browser closed   
         }        
		 else  
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
<script type="text/javascript">
$(document).ready( function() 
{
	$("#submites").click( function() 
	{
		if(document.getElementById('terms').checked==false)
		{
			jAlert('Please check the terms and condition checkbox!');
			return false; 
		}
		else
		{
			jConfirm('<strong>Are you sure that you are going to appear for Final Online Exam ?</strong>\n\nTo appear for Final Online Exam, you need An ESC (Exam Secret Code) which will be provided by institute.\n\nPlease contact your institute for Exam Secret Code. You cannot re-generate this ESC.\n\nThis ESC is valid to appear for exam only once.\n\nThis will block the practice test (if available). You will not be able to give practice test once you applied for Final Exam.', 'Confirmation Dialog', 
			function(r) 
			{
				if(r==true)
				{
					$("#submitStu").submit();
				}
			});
		}
	});
});
</script>
<!--/window.onbeforeunload = null;-->
<script type="text/javascript">
  function getLanguageValue(input1) {
    var input2 = document.getElementById('lang1');
    input2.value = input1;
  }
</script>
<script type="text/javascript">
    function Validate()
    {
        var e = document.getElementById("lang_id");
        var strUser = e.options[e.selectedIndex].value;

        var strUser1 = e.options[e.selectedIndex].text;
        if(strUser==0)
        {
            alert("Please select a language");
        }
    }
</script>