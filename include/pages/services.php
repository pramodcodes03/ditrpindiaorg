<!-- Breadcrumbs Start -->
        <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($services!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$services;
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay"  style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title">Our Products</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>Our Products</li>
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
      
        <!-- Courses Categories Start -->
        <div id="rs-learning-objectives" class="rs-learning-objectives pt-100 pb-70">
            <div class="container">               
                    <div class="row">
                        <?php
                            $res = $websiteManage->list_services('', '');           
                            if($res!='')
                            {
                                while($data = $res->fetch_assoc())
                                {
                                    extract($data);
                                    $img = 'resources/default_images/achiever_default.jpg';
                                    if($image!='')
                                        $img = SERVICES_PATH.'/'.$id.'/'.$image;
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="courses-item"> 
                                <img src="<?= $img ?>" alt="" style="height:250px;">  
                                <h4 class="courses-title"><a href="#"><?= $name ?></a></h4>
                                <p><?= $description ?></p>
                            	<div class="event-btn">
                	        		  <a class="primary-btn" href="" data-toggle="modal" data-target="#servicesEnquiry">Enquiry Now</a>
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
        </div>
        <!-- Courses Categories End -->