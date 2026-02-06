        <!-- Breadcrumbs Start -->
      
        <?php
          $title= '';
           if($page == 'ATCCertificates'){
               $title = 'ATC Certificates';
               $type = "ATC CERTIFICATES";
           }
            if($page == 'StudentCertificates'){
               $title = 'Student Certificates';
               $type = "STUDENT CERTIFICATES";
           }
             if($page == 'OurCertificates'){
               $title = 'Our Certificates';
               $type = "OUR CERTIFICATES";
           }
           
           
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($certificate!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$certificate;
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title"><?= $title ?></h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li><?= $title ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <?php
            }
        }
        ?>
        <!-- Breadcrumbs End -->

		<!-- Gallery Start -->
        <div class="rs-gallery sec-spacer">
            <div class="container">
            	<div class="sec-title-2 mb-50 text-center">
            	    <h2><?= $title ?></h2>   
            	</div>
            	<div class="row">
                    <?php
                    if($page == 'ATCCertificates' || $page == 'StudentCertificates'){
                        $res = $websiteManage->list_sample_certificates('', " AND A.type = '$type'");           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                                $img = 'resources/default_images/gallery_default.jpg';
                                if($image!='')
                                    $img = WEBSITES_SAMPLE_CERT_PATH.'/'.$id.'/'.$image;
                    ?>
            		<div class="col-lg-4 col-md-6">
            		    	<h3><?= $name ?></h3>
            			<div>
            			    <img src="<?= $img ?>" />
            			    
            			</div>
            		</div>  
                    <?php
                            }
                        }
                    }else{
                    ?>
                    <div class="wthree_services_grids">
				<div class="col-md-6 ">
						<h3 class="title-default-left">ACCREDITATION</h3> 
						<iframe src="<?= HTTP_HOST ?>/resources/pdf/ACCREDITATION.pdf" style="width:100%; height:750px;"></iframe> 
				</div>
				<div class="col-md-6 ">
						<h3 class="title-default-left">DITRP OPC PVT LTD ALL REGISTERED COURSE</h3> 
						<iframe src="<?= HTTP_HOST ?>/resources/pdf/DITRP OPC PVT LTD ALL REGISTERED COURSE.pdf" style="width:100%; height:750px;"></iframe> 
				</div>
				<div class="col-md-6 ">
						<h3 class="title-default-left">SCOPE </h3> 
						<iframe src="<?= HTTP_HOST ?>/resources/pdf/SCOPE.pdf" style="width:100%; height:750px;"></iframe> 
				</div>
				<div class="col-md-6 ">
						<h3 class="title-default-left">Udyam Registration Certificate </h3> 
						<iframe src="<?= HTTP_HOST ?>/resources/pdf/Udyam Registration Certificate.pdf" style="width:100%; height:750px;"></iframe> 
				</div>
				
				<div class="wthree_services_grids">	
					<div class="col-md-6 ">
						<h3 class="title-default-left">ISO 9001:2015</h3>						
						<img src="<?= HTTP_HOST ?>/resources/img/ourdocs/iso.jpeg" class="img img-responsive" style="margin:0 auto;" />
					</div>

					<div class="col-md-6 ">					
						<h3 class="title-default-left">ISO 10002:2018 INTERNATIONAL STANDARDS REGISTRATIONS</h3>
						<iframe src="<?= HTTP_HOST ?>/resources/img/ditrpdata/ISR.pdf" style="width:100%; height:750px;"></iframe>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6 ">					
						<h3 class="title-default-left">ISO 9001:2015 ROHS CERTIFICATIONS</h3>
						<iframe src="<?= HTTP_HOST ?>/resources/img/ditrpdata/ROHS.pdf" style="width:100%; height:750px;"></iframe>
					</div>
					
					<div class="col-md-6">
						<h3 class="title-default-left">AQC MIDDLE EAST FZE</h3>					
						<img src="<?= HTTP_HOST ?>/resources/img/ditrpdata/certificate_1.jpg" class="img img-responsive" style="margin:0 auto;" />
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6 ">
						<h3 class="title-default-left">ROHS CERTIFICATIONS PVT. LTD</h3>						
						<img src="<?= HTTP_HOST ?>/resources/img/ditrpdata/certificate_2.jpg" class="img img-responsive" style="margin:0 auto;" />
					</div>
					
					<div class="col-md-6 ">
						<h3 class="title-default-left">CENTRAL VIGILANCE COMMISION</h3>						
						<img src="<?= HTTP_HOST ?>/resources/img/ditrpdata/certificate_3.jpg" class="img img-responsive" style="margin:0 auto;" />
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6 ">
						<h3 class="title-default-left">UDYOG ADHAR</h3>						
						<img src="<?= HTTP_HOST ?>/resources/img/ditrpdata/certificate_4.jpg" class="img img-responsive" style="margin:0 auto;" />
					</div>
				
					<div class="col-md-6 ">		
						<h3 class="title-default-left">COMPUTER SOCIETY OF INDIA</h3>
						<img src="<?= HTTP_HOST ?>/resources/img/ditrpdata/certificate_5.jpg" class="img img-responsive" style="margin:0 auto;" />
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6 ">			
						<h3 class="title-default-left">CERTIFICATE OF INCORPORATION</h3>
						<iframe src="<?= HTTP_HOST ?>/resources/img/ditrpdata/CERTIFICATE OF INCORPORATION.PDF" style="width:100%; height:750px;"></iframe>
					</div>
				
					<div class="col-md-6 ">					
						<h3 class="title-default-left">DITRP REGISTRATION CERTIFICATE</h3>
						<iframe src="<?= HTTP_HOST ?>/resources/img/ditrpdata/DITRP REGISTRATION.pdf" style="width:100%; height:750px;"></iframe>
					</div>
				
			</div>
			<?php } ?>
                </div>    	    
            </div>
        </div>
        <!-- Gallery End -->
				