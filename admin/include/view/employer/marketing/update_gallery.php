 <style>
    #capture{
        background-repeat: no-repeat;
        width: 600px;
        height: 850px;
        background-size: 100%;
    }
    .inst_name{
        width: 75%;
        font-size: 10px;
        padding: 15px;
        font-weight: 900;
        height: 90px;
        top: 83%;
        position: absolute;
        float: right;
        left: 26%;
    }
    .mobile{
        width: 80%;
        top: 86%;
        padding: 0px 15px;
        position: absolute;
        left: 26%;
        font-size: 10px;
    }
    .address{
        position: relative;
        color: #000;
        font-size: 10px;
        z-index: 999;
        /* padding: 0px 20px; */
        /* text-align: center; */
        height: 30px;
        top: 95%;
        left: 51%;
        width: 45%;
        line-height: 14px;
    }
    
    @media only screen and (max-width: 600px) {
        .social_icons {
            float: right;
            top: -90px;
            position: relative;
            right: 0px;
            width: 35%;
            height: 30px;
        }
        
        .social_icons .fa {
            background-color: #000;
            width: 15px;
            height: 15px;
            text-align: center;
        }
        

        .bottom-section {
            position: relative;
            width: 310px;
            bottom: -210px;
        }
        .contact {
            position: relative;
            font-size: 12px;
            padding: 0px 10px;
            top: -170px;
        }
        .col-md-4{
            padding: 0;
            width: 30%;
            float: left;
        }
        .col-md-8{
            width: 70%;
            float: right;
        }
        .top-section{
            height: 150px;
        }
        #capture{
            background-repeat: no-repeat;
            width: 310px;
            height: 343px;
            background-size: 100%;
        }
         p img {
            float: right;
            width: 90px !important;
            margin: 13px;
            position: relative;
            top: -90px;
            height: 100% !important;
        }
        
        .right_side img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            width: 150px;
            margin-bottom: 10px;
        }
       .fa {
            font-size: 8px;
            background-color: #000;
            color: #fff;
            padding: 3px;
            border-radius: 5px;
            margin-right: 2px;
        }
        .inst_name{
            font-size: 10px;
            padding: 15px;
            font-weight: 700;
        }
        .website_name{
            position: relative;
            top: -25px;
            padding: 0px 15px; 
        }
        .address {
            position: relative;
            color: #fff;
            font-size: 11px;
            z-index: 999;
            padding: 0px 15px;
            text-align: center;
            height: 30px;
            top: -170px;
        }
        .bottom_image {
            width: 100%;
            position: relative;
            height: 50px;
            top: -220px;
        }
        .contact{
            position: relative;
            bottom: -125px;
            font-size: 10px;
            padding: 0px 10px;
            display: inline-flex;

        }
        .contact p{
            padding: 0px 1px;
            font-size: 10px;
        }
        .select_btn{
            padding: 5px;
            position: relative;
            width: 150px;
        }
        .ft-45{
                font-size: 0px;
        }
        .mbB-30{
            margin-bottom: 30px;
            padding: 0;
        }
        .title_date{
            font-size: 12px;
            background-color: black;
            color: #fff;
            margin: 0px 2px;
        }
        .mobile-top{
            top: 10px;
        }
        .mobile-pd0{
            padding:0;
        }
        .box-body{
            padding:0;
        }
    }
 
    @media screen and (min-device-width: 601px)
and (max-width: 1500px) {
        .address {
            position: relative;
            color: #fff;
            font-size: 14px;
            z-index: 999;
            padding: 0px 20px;
            text-align: center;
            height: 30px;
            top: -170px;
        }
        .bottom_image {
            width: 100%;
            position: relative;
            height: 70px;
            top: -225px;
        }
        .contact {
            position: relative;
            font-size: 15px;
            padding: 0px 20px;
            top: -170px;
        }
        .bottom-section {
            position: relative;
            width: 600px;
            bottom: -445px;
        }
    }

      @media screen  and (min-device-width: 1501px) and (max-width: 1920px) {
        .bottom-section {
            position: relative;
            width: 600px;
            /*bottom: 38%;*/
        }
    }
    </style>
 
 <?php
  include_once('include/classes/institute.class.php');
  $institute 	= new  institute();
        
$gallery_id = isset($_GET['id'])?$_GET['id']:'';

$res = $db->list_gallery($gallery_id,''); 
while($data = $res->fetch_assoc())
{
	$GALLERY_ID			= isset($data['GALLERY_ID'])?$data['GALLERY_ID']:'';
	$GALLERY_TYPE			= isset($data['GALLERY_TYPE'])?$data['GALLERY_TYPE']:'';
	$GALLERY_TITLE 		= isset($data['GALLERY_TITLE'])?$data['GALLERY_TITLE']:'';
	$GALLERY_IMAGE 		= isset($data['GALLERY_IMAGE'])?$data['GALLERY_IMAGE']:'';
	$GALLERY_DESC 		= isset($data['GALLERY_DESC'])?$data['GALLERY_DESC']:'';
	
	$ACTIVE				= isset($data['ACTIVE'])?$data['ACTIVE']:'';
	$CREATED_BY			= isset($data['CREATED_BY'])?$data['CREATED_BY']:'';
	$CREATED_ON			= isset($data['CREATED_DATE'])?$data['CREATED_DATE']:'';
	
}

    $res1 = $institute->list_institute($_SESSION['user_id'],'');
    if($res1!='')
    {
    	$srno=1;
    	while($data = $res1->fetch_assoc())
    	{	//print($data);
    		extract($data);
    	}
    }
?>

 <div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
		<div class="card">
		<div class="card-body">
			<h4 class="card-title"> View Marketing Material
			</h4> 
							
			<div class="pt-3">
			<div class="row">
        <!-- left column -->
				
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
              <div class="box-body">
			  <div class="row">
				<div class="col-xs-12 col-md-12">
					
					<h3><?= $GALLERY_TITLE ?></h3>
					<p><?= $GALLERY_DESC ?></p>
					<hr>
					<?php
					$imgres = $db->list_gallery_files_all($GALLERY_ID, '','');
					$html='';
					if($imgres!='')
					{
						while($img = $imgres->fetch_assoc())
						{
							$GALLERY_FILE_ID 	= $img['GALLERY_FILE_ID'];
							$FILE_NAME 			= $img['FILE_NAME'];
							$FILE_MIME 			= $img['FILE_MIME'];
							$ACTIVE 			= $img['ACTIVE'];
							$GALLERY_TYPE 			= $img['GALLERY_TYPE'];
							
							$path = '../uploads/marketing';	
							
							//$filePath = $path.'/'.$GALLERY_ID.'/thumb/'.$FILE_NAME;
							$fileLink = $path.'/'.$GALLERY_ID.'/'.$FILE_NAME;
							
							$file_ico = $access->book_icon($FILE_MIME);
								
								
							$html .='<div id="capture" style="background-image: url('.$fileLink.');">
							                  <p class="inst_name">'.$INSTITUTE_NAME.'</p>
							                  <p class="mobile">'.$MOBILE.'</p>
							                  <p class="address">'.$ADDRESS_LINE1.' '.$CITY.' '.$STATE_NAME.'</p>
									</div>';
						}
					}
					echo $html;
					?>
					<br/>
					 <a href="#" style="width: fit-content;" class="btn btn-block btn-primary mb-3" id="btnDownload">Download</a>
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
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>