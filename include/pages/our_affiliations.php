	<!-- Breadcrumbs Start -->
        <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($affiliations!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$affiliations;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">OUR AFFILIATIONS</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>AFFILIATIONS</li>
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
                        $res = $websiteManage->list_affiliations('', '');           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                                $achiever_img = 'resources/default_images/achiever_default.jpg';
                                if($image!='')
                                    $achiever_img = AFFILIATION_PATH.'/'.$id.'/'.$image;
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="cource-item">
                            <div class="cource-img">
                                   <img src="<?= $achiever_img ?>" alt="">                        
                            </div>
                            <div class="course-body">
                                    <h4 class="title"><a href="#"><?= $name ?></a></h4>                                    
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
