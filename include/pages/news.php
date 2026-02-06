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
		                    <h1 class="page-title">News</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>News</li>
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
            	    <h2>NEWS</h2>   
            	</div>
            	<div class="row">
                    <?php
                        $res = $websiteManage->list_news('', '');           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                                $img = 'resources/default_images/gallery_default.jpg';
                                if($image!='')
                                    $img = NEWS_PATH.'/'.$id.'/'.$image;
                    ?>
            		<div class="col-lg-4 col-md-6">
            			<div class="gallery-item" style="    border: 4px solid #000;
    box-shadow: 4px 4px #fff07d;     margin: 5px;">
            			    <img src="<?= $img ?>" style="width:100%; height:300px;"/>
            			    <div class="gallery-desc">
            					<h3><a href="#"><?= $name ?></a></h3>
            					<a class="image-popup" href="<?= $img ?>">
            						<i class="fa fa-search"></i>
            					</a>
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
        <!-- Gallery End -->
				