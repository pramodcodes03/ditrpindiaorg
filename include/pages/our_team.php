  		<!-- Breadcrumbs Start -->
         <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($team!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$team;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">OUR TEAM</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>Team</li>
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


   <!-- Team Start -->
        <div id="rs-team" class="rs-team sec-color sec-spacer">
            <div class="container">
                <div class="sec-title mb-50 text-center">
                    <h2>OUR TEAM</h2>   
                </div>
                <div class="row" >
                    <?php
                        $res = $websiteManage->list_team('', '');           
                        if($res!='')
                        {
                            while($data = $res->fetch_assoc())
                            {
                                extract($data);
                                $team_img = 'resources/default_images/team_default.jpg';
                                if($image!='')
                                    $team_img = OURTEAM_PATH.'/'.$id.'/'.$image;
                    ?>
                    <div class="col-md-4 team-item pd-25">
                        <div class="team-img">
                            <img class="team-home" src="<?= $team_img ?>" alt="team Image" />
                            <div class="normal-text">
                                <h3 class="team-name"><?= $name ?></h3>
                                <span class="subtitle"><?= $designation ?></span>
                            </div>
                        </div>
                        <div class="team-content">
                            <div class="overly-border"></div>
                            <div class="display-table">
                                <div class="display-table-cell">
                                    <h3 class="team-name"><a><?= $name ?></a></h3>
                                    <span class="team-title"><?= $designation ?></span>
                                    <p class="team-desc"><?= $description ?></p>
                                    <!-- <div class="team-social">
                                        <a href="#" class="social-icon"><i class="fa fa-facebook"></i></a>
                                        <a href="#" class="social-icon"><i class="fa fa-google-plus"></i></a>
                                        <a href="#" class="social-icon"><i class="fa fa-twitter"></i></a>
                                        <a href="#" class="social-icon"><i class="fa fa-pinterest-p"></i></a>
                                    </div> -->
                                </div>
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
        <!-- Team End -->
