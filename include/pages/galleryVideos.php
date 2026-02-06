        <!-- Breadcrumbs Start -->
         <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($gallery!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$gallery;
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title">GALLERY VIDEOS</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>Gallery Videos</li>
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
                    <h2>GALLERY VIDEOS</h2>   
                </div>
                <div class="row">
                    <?php
                        $res = $websiteManage->list_galleryVideos('', '');           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                    ?>
                    <div class="col-lg-4 col-md-6">
                      
                            <iframe id="player" type="text/html" 
                              src="<?= $video ?>"
                              frameborder="0" style="width:100%;height:200px" allowfullscreen></iframe>
                              <h3><?= $name ?></h3>                          
                        
                    </div>  
                    <?php
                            }
                        }
                    ?>
                </div>          
            </div>
        </div>
        <!-- Gallery End -->
                