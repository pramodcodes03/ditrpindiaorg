<?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($policies!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$policies;
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">                         
                            <h1 class="page-title">Franchise Details</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>Franchise Details</li>
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
        <?php
            $res = $websiteManage->list_franchise_details('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);                    
        ?>
        <div class="rs-history sec-spacer">
            <div class="container">
                <div class="row">                
                    <div class="col-lg-12 col-md-12">
                        <div class="abt-title">
                            <h2>Franchise Details</h2>
                        </div>
                        <div class="about-desc">
                            <?php
                               echo html_entity_decode($details);                               
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        }
        ?>