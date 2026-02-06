<!-- Breadcrumbs Start -->
      
        <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($aboutus!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$aboutus;
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title">About Us</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>About Us</li>
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
        <?php
            $res = $websiteManage->list_about('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $about_home = 'resources/default_images/about_default.jpg';
                    if($homepage_image!='')
                        $about_home     = ABOUTUS_PATH.'/'.$id.'/'.$homepage_image;
                    if($mission_image!='')
                        $about_mission  = ABOUTUS_PATH.'/'.$id.'/'.$mission_image;
                    if($vision_image!='')
                        $about_vision   = ABOUTUS_PATH.'/'.$id.'/'.$vision_image;
        ?>
        <!-- History Start -->
        <div class="rs-history sec-spacer">
            <div class="container">
                <div class="row">
                    <div class="abt-title">
                            <h2>ABOUT US</h2>
                        </div>
                    <div class="col-lg-6 col-md-12 rs-vertical-bottom mobile-mb-50">
                        <a href="#">
                            <img src="<?= $about_home ?>"/>
                        </a>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        
                        <div class="about-desc">
                            <?= html_entity_decode($about_long) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- History End -->

        <!-- Mission Start -->
        <div class="rs-mission sec-color">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 mobile-mb-50">
                        <div class="abt-title">
                            <h2>OUR MISSION</h2>
                        </div>
                        <div class="about-desc">
                            <?= html_entity_decode($mission_long) ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="row">
                            <div class="col-md-12 mobile-mb-30">
                                <a href="#">
                                    <img src="<?= $about_mission ?>" />
                                </a> 
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mission End -->

        <!-- Vision Start -->
        <div class="rs-vision sec-spacer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-12 mobile-mb-50">
                        <div class="vision-img rs-animation-hover">
                            <img src="<?= $about_vision ?>"/>                           
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="abt-title">
                            <h2>OUR VISION</h2>
                        </div>
                        <div class="vision-desc">
                            <?= html_entity_decode($vision_long) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Vision End -->
        <?php
            }
        }
        ?>