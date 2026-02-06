	<!-- Breadcrumbs Start -->
        <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($achiever!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$achiever;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">OUR ACHIEVERS</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>ACHIEVERS</li>
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

	    
        <!-- Courses Start -->
        <div id="rs-courses" class="rs-courses rs-courses-style6 sec-color sec-spacer">
			<div class="container">
				<div class="row">
					<?php
                        $res = $websiteManage->list_achievers('', '');           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                                $achiever_img = 'resources/default_images/achiever_default.jpg';
                                if($image!='')
                                    $achiever_img = ACHIEVERS_PATH.'/'.$id.'/'.$image;
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="cource-item">
                            <div class="cource-img">
                                   <img src="<?= $achiever_img ?>" alt="" style="height:300px;">                        
                            </div>
                            <div class="course-body" style="padding-bottom: 25px;">
                                    <h4 class="title"><?= $name ?></h4>
                                    <p style="margin-bottom:10px"><?= $course ?></p>
                                	<span><?= $description ?></span>
                            </div>
                        </div> 
                    </div> 
                    <?php
                            }
                        }
                    ?>
			    </div>
			</div>
        </div>
        <!-- Courses End -->
