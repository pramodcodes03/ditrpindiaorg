<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ONLINE EXAM</title>

<!-- Bootstrap core CSS -->

<link href="<?=base_url()?>assets/exam_assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url()?>assets/exam_assets/fonts/css/font-awesome.min.css" rel="stylesheet">
<link href="<?=base_url()?>assets/exam_assets/css/animate.min.css" rel="stylesheet">

<!-- Custom styling plus plugins -->
<link href="<?=base_url()?>assets/exam_assets/css/custom.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/exam_assets/css/maps/jquery-jvectormap-2.0.1.css" />
<link href="<?=base_url()?>assets/exam_assets/css/icheck/flat/green.css" rel="stylesheet" />
<link href="<?=base_url()?>assets/exam_assets/css/floatexamples.css" rel="stylesheet" type="text/css" />
<script src="<?=base_url()?>assets/exam_assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/paging/paging.css">
<style>
.btn-default1 {
	position: relative;
	text-align:center;
	color:#73879c;
	text-decoration:none;
	display: block;
	width:35px;
	font-size:12px !important;
	height:33px;

}
.left_pagi a {
	margin-right:-5px !important;
	margin-bottom:-4px !important;
}
.btn-default1:before {
	
	background:#D5D5D5;
	background: -webkit-linear-gradient(#D5D5D5, #EAEAEA);
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	-webkit-box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	-moz-box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	position: absolute;
	content: "";
	left: 4px;
	right: 4px;
	top: 4px;
	bottom: 4px;
	z-index: -1;
}
.btn-default1:active {
	-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset;
	top:5px;
}
.btn-default1:active:before {
	top: 1px;
	bottom: 1px;
	content: "";
}
.timespan {
	font-family: Tahoma, Verdana, Aial, sans-serif;
	font-size: 28px;
	line-height: 108%;
	font-weight: bold;
	height: 42px;
}
.timespans {
	font-size: 15px;
	line-height: 108%;
	font-weight: bold;
	height: 32px;
}

#minutes {
	font-family: Tahoma, Verdana, Aial, sans-serif;
	font-size: 28px;
	line-height: 108%;
	font-weight: bold;
	height: 42px;
	color: #c71585;
	/*width: 40px;*/
	padding: 5px 2px;
	text-align: center;
	
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#F7F7F7', endColorstr='#cccccc', GradientType=0 ); /* IE6-9 */
}
#seconds {
	font-family: Tahoma, Verdana, Aial, sans-serif;
	font-size: 28px;
	line-height: 108%;
	font-weight: bold;
	height: 42px;
	color: #c71585;
	width: 40px;
	padding: 5px 2px;
	text-align: center;
	margin-top:5px;
	
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#F7F7F7', endColorstr='#cccccc', GradientType=0 ); /* IE6-9 */
}
input[type=radio] {
	display:none;
	margin:10px;
}

input[type=radio] + label {
	display:inline-block;
	margin:2px;
	padding: 5px 12px;
    border:1px solid #d0d3d6;
	cursor:pointer;
	width: 34px;
	height: 33px;
	margin-right:4px;
	margin-bottom:7px;
	border-radius:15px;
}

input[type=radio]:checked + label {
 background:url(<?=base_url()?>assets/front_assets/images/CORRECT.png);
	background-repeat: no-repeat;
    background-position: center;
}
.push_button {
	position: relative;
	width:150px;
	text-align:center;
	color:#FFF;
	line-height:30px;
	font-family:'Oswald', Helvetica;
	display: block;
	margin: 10px;
	cursor:pointer;
}
a {
	text-decoration:none !important;
}
.push_button:before {
	background:#f0f0f0;
	background-image:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#D0D0D0), to(#f0f0f0));
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	-webkit-box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	-moz-box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	box-shadow:0 1px 2px rgba(0, 0, 0, .5) inset, 0 1px 0 #FFF;
	position: absolute;
	content: "";
	z-index: -1;
}
.push_button:active {
	-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset;
	top:5px;
}
.push_button:active:before {
	top: -5px;
	bottom: 0px;
	content: "";
}
.red {
	text-shadow:-1px -1px 0 #A84155;
	background: #D25068;
	border:1px solid #D25068;
	background-image:-webkit-linear-gradient(top, #F66C7B, #D25068);
	background-image:-moz-linear-gradient(top, #F66C7B, #D25068);
	background-image:-ms-linear-gradient(top, #F66C7B, #D25068);
	background-image:-o-linear-gradient(top, #F66C7B, #D25068);
	background-image:linear-gradient(to bottom, #F66C7B, #D25068);
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #AD4257, 0 4px 2px rgba(0, 0, 0, .5);
	-moz-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #AD4257, 0 4px 2px rgba(0, 0, 0, .5);
	box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #AD4257, 0 4px 2px rgba(0, 0, 0, .5);
}
.red:hover {
	background: #F66C7B;
	background-image:-webkit-linear-gradient(top, #D25068, #F66C7B);
	background-image:-moz-linear-gradient(top, #D25068, #F66C7B);
	background-image:-ms-linear-gradient(top, #D25068, #F66C7B);
	background-image:-o-linear-gradient(top, #D25068, #F66C7B);
	background-image:linear-gradient(top, #D25068, #F66C7B);
}
.blue {
	text-shadow:-1px -1px 0 #2C7982;
	background: #3EACBA;
	border:1px solid #379AA4;
	background-image:-webkit-linear-gradient(top, #48C6D4, #3EACBA);
	background-image:-moz-linear-gradient(top, #48C6D4, #3EACBA);
	background-image:-ms-linear-gradient(top, #48C6D4, #3EACBA);
	background-image:-o-linear-gradient(top, #48C6D4, #3EACBA);
	background-image:linear-gradient(top, #48C6D4, #3EACBA);
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #338A94, 0 4px 2px rgba(0, 0, 0, .5);
	-moz-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #338A94, 0 4px 2px rgba(0, 0, 0, .5);
	box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #338A94, 0 4px 2px rgba(0, 0, 0, .5);
}
.blue:hover {
	background: #48C6D4;
	background-image:-webkit-linear-gradient(top, #3EACBA, #48C6D4);
	background-image:-moz-linear-gradient(top, #3EACBA, #48C6D4);
	background-image:-ms-linear-gradient(top, #3EACBA, #48C6D4);
	background-image:-o-linear-gradient(top, #3EACBA, #48C6D4);
	background-image:linear-gradient(top, #3EACBA, #48C6D4);
}
.green {
	
}
.green:hover {
	background: #19A98B;
	
}

.orange:hover {
	background: #0a3f61;
	
}
.black {
	text-shadow:-1px -1px 0 #080808;
	background: #383838;
	border:1px solid #4C4B4B;
	background-image:-webkit-linear-gradient(top, #C1BEBE, #6A6868);
	background-image:-moz-linear-gradient(top, #C1BEBE, #6A6868);
	background-image:-ms-linear-gradient(top, #C1BEBE, #6A6868);
	background-image:-o-linear-gradient(top, #C1BEBE, #6A6868);
	background-image:linear-gradient(top, #C1BEBE, #6A6868);
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	-webkit-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #2E2B2B, 0 4px 2px rgba(0, 0, 0, .5);
	-moz-box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #2E2B2B, 0 4px 2px rgba(0, 0, 0, .5);
	box-shadow:0 1px 0 rgba(255, 255, 255, .5) inset, 0 -1px 0 rgba(255, 255, 255, .1) inset, 0 4px 0 #2E2B2B, 0 4px 2px rgba(0, 0, 0, .5);
}
.black:hover {
	background: #93DDAE;
	background-image:-webkit-linear-gradient(top, #4D4D4C, #B5B4B4);
	background-image:-moz-linear-gradient(top, #4D4D4C, #B5B4B4);
	background-image:-ms-linear-gradient(top, #4D4D4C, #B5B4B4);
	background-image:-o-linear-gradient(top, #4D4D4C, #B5B4B4);
	background-image:linear-gradient(top, #4D4D4C, #B5B4B4);
}
.gray {
	background: #6a6b6a;
}

.gray:hover {
	background: #2d312d;color:#fff;
	
}
</style>
<!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

 <script type = "text/javascript" >
       function preventBack(){window.history.forward(1);}
        setTimeout("preventBack()", 0);
        window.onunload=function(){null};
    </script>
</head>

<!--<body class="nav-md" onunload="bodyUnload();" Onclick="clicked=true;">-->
<body class="nav-md">
<?php 
$sess_name = $this->session->userdata('session_id');
$student_course_detail_id = $this->session->userdata('student_course_detail_id');

//echo "SEssion Course details id stud: $student_course_detail_id";
$inst_course_id 			= $this->home_model->get_student_course_details($student_course_detail_id);
$inst_course_info 		= $this->home_model->get_inst_course_info($inst_course_id);

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
<div class="container body">
  <div class="main_container">
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view"> 
        
        <!--<div class="navbar nav_title" style="border: 0;">
                        <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentellela Alela!</span></a>
                    </div>-->
        <div class="clearfix"></div>
        
        <!-- menu prile quick info --> 
        <!--<div class="row">-->
        <div class="profile">
		<?php 
					$stud_photo = base_url()."/assets/img/nouser.png";
					if(!empty($get_student['stud_photo']))
					{
						$stud_photo = "../uploads/student/".$get_student['STUDENT_ID']."/".$get_student['stud_photo'];
					}
					?>
          <div class="profile_pic"> <img src="<?= $stud_photo ?>" class="profile_img" style="width: 200px; border-radius: 20px; height:100%" /> </div>
        </div>
        <!-- </div>--> 
        
        <!-- /menu prile quick info --> 
        
        <br />
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
          <div class="menu_section">
            <h3>
              <?=strtoupper($get_student['full_name']);?>
            </h3>
            <ul class="nav side-menu">
              <li style="padding: 10px; color: #29396c; font-size: 13px; font-weight: 600;">
                <div class="col-md-12 std_detail">
					 <!--
				 <div class="col-md-6" style="padding:3px"> Gender </div>
                  <div class="col-md-6" style="padding:3px">
                    <?php if($get_student['STUDENT_GENDER']=='male'){  echo "MALE";}else{ echo "FEMALE";}?>
                  </div>
				 
                  <div class="col-md-6" style="padding:3px"> Date of Birth </div>
                  <div class="col-md-6" style="padding:3px">
                    <?=$get_student['DOB'];?>
                  </div>
				  -->
                  <div class="col-md-12" style="padding:3px"> Course Name : <?=$inst_course_info['COURSE_NAME'];?> <?=$inst_course_info['MULTI_SUB_COURSE_NAME']?></div>
                 
				  <div class="col-md-12" style="padding:3px"> Subject Name :  <?=$subject_name['COURSE_SUBJECT_NAME']?></div>
                  
				  <!--
                  <div class="col-md-6" style="padding:3px"> IP Address </div>
                  <div class="col-md-6" style="padding:3px">
                    <?=$this->input->ip_address();?>
                  </div>
				  -->
                  <div class="col-md-6" style="padding:3px"> Total Questions </div>
                  <div class="col-md-6" style="padding:3px">
                    : <?=$exam_name['TOTAL_QUESTIONS'];?>
                  </div>
                  <div class="col-md-6" style="padding:3px"> Exam Time </div>
                  <div class="col-md-6" style="padding:3px">
                    : <?=$exam_name['EXAM_TIME'];?>
                    Minutes </div>
                </div>
              </li>
            </ul>
            <br>
            
          </div>
        </div>
        <!-- sidebar menu --> 
        
        <!-- /sidebar menu --> 
      </div>
    </div>
    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
			<ul style="margin-top:0px;" class="nav navbar-nav navbar-left">            
			   <li class="">
				<label style="font-size: 25px;padding-top: 4%;text-shadow: 1px 1px 1px #000, 3px 3px 5px #ccc;color: #254e86;"><?= $get_student['institute_name'] ?></label>
            </li>
          </ul>
		 <ul class="nav navbar-nav navbar-right" style="margin-top:5px; margin-right:20px; width:20% !important;">
            
			<?php 
				$get_mins = $exam_name['EXAM_TIME'] * 60;
				$miliseconds = $get_mins * 1000;
				?>
            <li class="" >
              <label id="minutes">00</label>
              <span class="timespan"> :</span>
              <label id="seconds">00</label>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- /top navigation --> 
    
    <!-- page content -->
    <div class="right_col" role="main">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="dashboard_graph">
            <div class="row x_title">
              <div class="col-md-12"> 
           
                <div class="col-md-6">
                <h1 style="text-shadow: 1px 1px 1px #aba6a6, 3px 3px 5px #ccc; color:#254E86; margin-top:15px; float:left;font-size: 25px;"><strong>Online Practice Test</strong></h1>
                </div>
              </div>
            </div>
            
              <div class="col-md-12 col-sm-9 col-xs-12"> 
                <!--<div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>-->
                <div id="gallery" class="clearfix">
                
                <?php
                $i=1;
				?>
                <?php foreach($get_questions as $questions):?>
             
             <?php $adminFrm = array('name' => 'submitStu_'.$i, 'id' => 'submitStu_'.$i);?>
            <?php echo form_open_multipart('',$adminFrm); ?>
            <input type="hidden" name="exam_over" id="exam_over" value="">
            <input type="hidden" name="save_next" id="save_next" value="" />
            <input type="hidden" name="sess_end" id="sess_end" value="" />
            <input type="hidden" name="serial" id="serial" value="" />
             <div class="row">
                  <div class="col-md-12">
                    <p style="width:100%" ><strong>Question:</strong></p>
                    <div class="col-md-1">
                      <div class="btn btn-default"> <span class="counter"> </span> </div>
                    </div>
                    <div class="col-md-11">
                      <?=$questions['question'];?>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="question_id" id="question_id" value="<?=$questions['question_id'];?>">
                <input type="hidden" name="exam_attempt_id_<?=$i?>" id="exam_attempt_id_<?=$i?>" value="<?=$questions['id'];?>">
                <?php
                  if(!empty($questions['image']))
				  {
				  ?>
                <div class="row">
                  <div class="col-md-12" > <a class="btn btn-default fancybox-effects-a" href="<?=base_url()?>assets/question/<?=$questions['image'];?>" style="margin-right:10px;float:right; border-color:#F58121; color:#F58121" >view image</a> </div>
                </div>
                <?php
				  }
				  else
				  {
				  ?>
                  <div class="row">
                  <div class="col-md-12" > &nbsp; </div>
                </div>
                <?php
				  }
				?>
                   <?php
                if($questions['option_a']!='' || $questions['option_a']!=NULL)
				{
				?>
                <div class="row">
                  <div class="col-md-12">
                    <p style="width:100%"><strong>Options:</strong></p>
                    <div class="col-md-1">
                      <input type="radio" id="radio_a<?=$i?>" name="ad_opt_<?=$i?>" onChange="saveNnext('<?=$i?>')" value="option_a" <?php if($questions['option_a_chk']==1){ echo 'checked="checked"'; }?>>
                      <label for="radio_a<?=$i?>">A</label>
                    </div>
                    <div class="col-md-11">
                      <?=$questions['option_a'];?>
                    </div>
                  </div>
                </div>
                   <?php
				}
				
				?>
                <?php
                if($questions['option_b']!='' || $questions['option_b']!=NULL)
				{
				?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-1">
                      <input type="radio" id="radio_b<?=$i?>" name="ad_opt_<?=$i?>" onChange="saveNnext('<?=$i?>')" value="option_b" <?php if($questions['option_b_chk']==1){ echo 'checked="checked"'; }?>>
                      <label for="radio_b<?=$i?>">B</label>
                    </div>
                    <div class="col-md-11">
                      <?=$questions['option_b'];?>
                    </div>
                  </div>
                </div>
                   <?php
				}				
				?>
                <?php
                if($questions['option_c']!='' || $questions['option_c']!=NULL)
				{
				?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-1">
                      <input type="radio" id="radio_c<?=$i?>" name="ad_opt_<?=$i?>" onChange="saveNnext('<?=$i?>')" value="option_c" <?php if($questions['option_c_chk']==1){ echo 'checked="checked"'; }?>>
                      <label for="radio_c<?=$i?>">C</label>
                    </div>
                    <div class="col-md-11">
                      <?=$questions['option_c'];?>
                    </div>
                  </div>
                </div>
                <?php
				}
				
				?>
                <?php
                if($questions['option_d']!='' || $questions['option_d']!=NULL)
				{
				?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-1">
                      <input type="radio" id="radio_d<?=$i?>" name="ad_opt_<?=$i?>" onChange="saveNnext('<?=$i?>')" value="option_d" <?php if($questions['option_d_chk']==1){ echo 'checked="checked"'; }?>>
                      <label for="radio_d<?=$i?>">D</label>
                    </div>
                    <div class="col-md-11">
                      <?=$questions['option_d'];?>
                    </div>
                  </div>
                </div>
                <?php
                
				}
				
				?>
                </form>
               
                <?php
                $i++;
				?>
                <?php endforeach;?>
                </div>
				<hr>
                
              </div>
              
			 
			  <div class="col-md-12 col-sm-12 col-xs-12" style="background:#eae9e9">
                  <div>
                    <div>
                      <p>
					 <div class="col-sm-2"><span class="btn btn-primary next push_button orange" onClick="saveNnext(window.location.hash.replace('#', '')), saveForward(window.location.hash.replace('#', ''))">SAVE & NEXT</span></div>
					  
					  <div class="col-sm-2"><span class="btn btn-default prev push_button gray" onClick="saveNnext(window.location.hash.replace('#', ''))">BACK</span></div>
					  
					  <div class="col-sm-2"><span class="btn btn-default next push_button gray" onClick="skipNnext(window.location.hash.replace('#', ''))">SKIP</span></div>
					  
					  <div class="col-sm-2"><span class="btn btn-danger push_button green" onclick="examOver(window.location.hash.replace('#', ''))">END EXAM</span></div>
					  </p>
                    </div>
                  </div> 
              </div>
			 
             <div class="clearfix"></div>
			 <div class="row">
				<div class="col-sm-12">
					<h3 style="margin-bottom:10px; text-align:left; margin-left:10px">Quick Jump on Questions</h3>
            <div class="left_pagi">
              <div class="col-md-12">
                <div class="btn-group" style="text-align:center"> <span class="paging-nav"></span> </div>
              </div>
              <br>
			  	<div class="col-md-12">
					<div class="row col-md-12">
						<div style="    padding: 0px;
    text-align: center;
    margin: 10px 10px;
    font-size: 14px;
    color: #000;"> <span class="btn btn-default" style="background:#13803b !important;padding:10px"></span>&nbsp;Appeared <span class="btn btn-default" style="background:#fb430b !important;padding:10px" ></span>&nbsp;Not Appeared  </div>
					</div>
				</div>
           
            </div>
				</div>
			 </div>
           
          </div>
        </div>
      </div>
      <br />
      
      <!-- footer content -->
     
	 <footer>
      <!--  <div class="">
          <p style="text-align:center !important"> <span class="lead" style="font-size:15px; margin-top:50px"><img src="<?=base_url()?>assets/logo/icon.png" width="30px"> <strong>DIGITAL INFORMATION TECHNOLOGY &amp; RESEARCH PROFESSIONALS</strong></span> </p>
        </div>
        <div class="clearfix"></div> -->
      </footer>
      
      <!-- /footer content --> 
    </div>
    <!-- /page content --> 
    
  </div>
</div>
<div id="custom_notifications" class="custom-notifications dsp_none">
  <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
  </ul>
  <div class="clearfix"></div>
  <div id="notif-group" class="tabbed_notifications"></div>
</div>
<script src="<?=base_url()?>assets/exam_assets/js/bootstrap.min.js"></script> 

<!-- gauge js --> 

<!-- chart js --> 
<!-- bootstrap progress js --> 
<script src="<?=base_url()?>assets/exam_assets/js/progressbar/bootstrap-progressbar.min.js"></script> 
<script src="<?=base_url()?>assets/exam_assets/js/nicescroll/jquery.nicescroll.min.js"></script> 
<!-- icheck --> 
<script src="<?=base_url()?>assets/exam_assets/js/icheck/icheck.min.js"></script> 
<!-- daterangepicker --> 
<script type="text/javascript" src="<?=base_url()?>assets/exam_assets/js/moment.min.js"></script> 
<script src="<?=base_url()?>assets/exam_assets/js/custom.js"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/fancybox/lib/jquery.mousewheel.pack.js?v=3.1.3"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/front_assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script> 
<!-- flot js --> 
<!--[if lte IE 8]><script type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->

</body>
</html>
<?php $sess_over = array('name' => 'sess_over', 'id' => 'sess_over');?>
<?php echo form_open('',$sess_over); ?>
<input type="hidden" value="" name="sess_end1" id="sess_end1" />
<input type="hidden" value="" name="question_id2" id="question_id2" />
<input type="hidden" value="" name="exam_attempt_id2" id="exam_attempt_id2" />
<input type="hidden" value="" name="ad_opt2" id="ad_opt2" />
<input type="hidden" value="" name="sess_name" id="sess_name" />
<?php echo form_close(); ?> 

<script type="text/javascript">
 var minutesLabel = document.getElementById("minutes");
        var secondsLabel = document.getElementById("seconds");
        var totalSeconds = '<?=$get_mins?>';
		var miliseconds = '<?=$miliseconds?>';
        setInterval(setTime, 1000);

        function setTime()
        {
            --totalSeconds;
            secondsLabel.innerHTML = pad(totalSeconds%60);
            minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
        }

        function pad(val)
        {
            var valString = val + "";
            if(valString.length < 2)
            {
                return "0" + valString;
            }
            else
            {
				setTimeout(function()
				{ 
					alert("Session Expired contact admin!"); 
					var uris = window.location.hash.replace('#', '');
					document.getElementById('sess_over').action = "<?=base_url()?>home/psess_over";
					document.getElementById('sess_end1').value=1;
					document.getElementById('sess_name').value='<?=$sess_name?>';
					document.getElementById('question_id2').value = document.getElementById('question_id').value;
					document.getElementById('exam_attempt_id2').value = document.getElementById('exam_attempt_id_' + uris).value;
					if(document.getElementById('radio_a' + uris).checked  == true)
					{
						document.getElementById('ad_opt2').value = document.getElementById('radio_a' + uris).value;
					}
					else if(document.getElementById('radio_b' + uris).checked  == true)
					{
						document.getElementById('ad_opt2').value = document.getElementById('radio_b' + uris).value;
					}
					else if(document.getElementById('radio_c' + uris).checked  == true)
					{
						document.getElementById('ad_opt2').value = document.getElementById('radio_c' + uris).value;
					}
					else if(document.getElementById('radio_d' + uris).checked  == true)
					{
						document.getElementById('ad_opt2').value = document.getElementById('radio_d' + uris).value;
					}
					else
					{
						document.getElementById('ad_opt2').value = '';
					}
					//alert(document.getElementById('ad_opt2').value);
					document.sess_over.submit();
				}, miliseconds);
                return valString;
            }
        }
    </script>
<script src="<?=base_url()?>assets/paging/jquery.pagination-with-hash-change-2.js"></script> 
<script>
    $(document).ready(function() {
      $('#gallery').Paginationwithhashchange2({
        nextSelector: '.next',
        prevSelector: '.prev',
        counterSelector: '.counter',
        pagingSelector: '.paging-nav',
        itemsPerPage: 1,
        initialPage: 1
      });
    });
  </script>

<script>
function saveNnext(page_key)
{
	var r = document.getElementsByName("ad_opt_" + page_key)
	var c = -1
	
	for(var i=0; i < r.length; i++)
	{
	   if(r[i].checked) 
	   {
		  c = i; 
	   }
	}
	if (c == -1) 
	{
		document.getElementById("pg_"+page_key).className = "not-appeared";
	}
	else
	{
		document.getElementById("pg_"+page_key).className = "appeared";

	}
}
function skipNnext(page_key)
{
	var r = document.getElementsByName("ad_opt_" + page_key)
	var c = -1
	
	for(var i=0; i < r.length; i++)
	{
	   r[i].checked =false;
	   
	}
	document.getElementById("pg_"+page_key).className = "not-appeared";
	
}
</script>

<?php $exam_Over = array('name' => 'exam_Over', 'id' => 'exam_Over');?>
<?php echo form_open('',$exam_Over); ?>
<input type="hidden" value="" name="exam_over1" id="exam_over1" />
<input type="hidden" value="" name="question_id1" id="question_id1" />
<input type="hidden" value="" name="exam_attempt_id1" id="exam_attempt_id1" />
<input type="hidden" value="" name="ad_opt1" id="ad_opt1" />
<?php echo form_close(); ?>  



<script type="text/javascript">
function examOver(uri)
{
	var r=confirm("Are you sure you submit your answers?");
	if (r==true)
	{
		//var frm = 'submitStu_' + uri;
		//alert(frm);
		document.getElementById('exam_Over').action = "<?=base_url()?>home/pexam_overs";
		document.getElementById('exam_over1').value=1;
		document.getElementById('question_id1').value = document.getElementById('question_id').value;
		document.getElementById('exam_attempt_id1').value = document.getElementById('exam_attempt_id_' + uri).value;
		if(document.getElementById('radio_a' + uri).checked  == true)
		{
			document.getElementById('ad_opt1').value = document.getElementById('radio_a' + uri).value;
		}
		else if(document.getElementById('radio_b' + uri).checked  == true)
		{
			document.getElementById('ad_opt1').value = document.getElementById('radio_b' + uri).value;
		}
		else if(document.getElementById('radio_c' + uri).checked  == true)
		{
			document.getElementById('ad_opt1').value = document.getElementById('radio_c' + uri).value;
		}
		else if(document.getElementById('radio_d' + uri).checked  == true)
		{
			document.getElementById('ad_opt1').value = document.getElementById('radio_d' + uri).value;
		}
		else
		{
			document.getElementById('ad_opt1').value = '';
		}
		//document.forms[0];  
		//alert(document.getElementById('ad_opt1').value);         
		document.exam_Over.submit();
	}
}
function saveForward(uri)
{
	var form_id = '#submitStu_' + uri;
	var url = "<?=base_url()?>home/psave_n_nexts";
	$postData = $(form_id).serialize()  + '&save_next=1&serial=' + uri;
	$.post(url, $postData, function(data) {
		//window.location.href = button.attr('href');
	});
}
</script>
<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',
				helpers : {
					title : {
						type : 'over'
					}
				}
			});
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,
				openEffect : 'none',
				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});
			$(".fancybox-effects-d").fancybox({
				padding: 0,
				openEffect : 'elastic',
				openSpeed  : 150,
				closeEffect : 'elastic',
				closeSpeed  : 150,
				closeClick : true,
				helpers : {
					overlay : null
				}
			});
			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',
				prevEffect : 'none',
				nextEffect : 'none',
				closeBtn  : false,
				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},
				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});
			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});
			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});
			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});
			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});
		});
	</script>
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
           request.open  ("POST", "<?=base_url()?>home/end_exam_on_close", false);    
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