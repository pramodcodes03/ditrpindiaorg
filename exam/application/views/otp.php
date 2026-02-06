<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ONLINE EXAM</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="shortcut icon" href="">
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/front_assets/css/style.css" />
<script src="<?=base_url()?>assets/front_assets/js/modernizr.custom.63321.js"></script>
<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
<script type = "text/javascript" >
       function preventBack(){window.history.forward(1);}
        setTimeout("preventBack()", 0);
        window.onunload=function(){null};
    </script>
</head>
<body onunload="bodyUnload();" Onclick="clicked=true;">

<div class="container">
  <header>
 
    <h1  style="margin-top:50px;">Student Verification </h1>
    <h2 style=" font-style:normal; color:#F58220; font-size:18px; font-weight:bold">Please collect and enter the Exam Secret Code received from your institute.</h2>
    <!-- <h2 style="margin-botton:-50px; font-style:normal">IP ADDRESS: <?=$this->input->ip_address();?></h2> -->
    <div class="support-note"> <span class="note-ie">Sorry, only modern browsers.</span> </div>
  </header>
  <section class="main">
     <?php $msg = $this->session->userdata('msg');?>
	 <?php $adminFrm = array('class' => 'form-1', 'name' => 'submitStu', 'id' => 'submitStu');?>
     <?php echo form_open_multipart('',$adminFrm); ?>
     <?php
		if(!empty($msg))
		{
			echo '<p align="center" style="margin-bottom:-10px;"><font color="#00cc00"><b>'.$msg.'</b></font></p><br>';
			$this->session->unset_userdata('msg');
		}
	 ?>
      <p class="field">
        <!-- <input type="text" name="otp" placeholder="OTP" value="" required> -->
        <i class="icon-lock icon-large"></i></p>
        <p class="field">
                <input type="hidden" name="lang_id" id="lang_id" value="<?= $lang1  ?>">
                <input type="text" name="exam_secret_code" placeholder="Exam Secret Code" value="" required>
                <i class="icon-lock icon-large"></i>
        </p>
      <p class="submit1">
        <button type="submit" name="submit"><i class="icon-arrow-right icon-large"></i></button>
      </p>
    </form>
  </section>
</div>
</body>
</html>
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