        <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';

                    if($page == "terms"){
                        if($term_condition!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$term_condition;
                    }else if($page == "privacy"){
                        if($policies!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$policies;
                    }else if($page == "disclaimer"){
                        if($disclaimer!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$disclaimer;
                    }else if($page == "refundPolicy"){
                        if($refund_policy!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$refund_policy;
                    }

                    
        ?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php

                                if($page == "terms"){
                                    $title = "Terms & Conditios";
                                }else if($page == "privacy"){
                                    $title = "Privacy Policies";
                                }else if($page == "disclaimer"){
                                    $title = "Disclaimer";
                                }else if($page == "refundPolicy"){
                                    $title = "Refund Policies";
                                }
                            ?>
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
        <?php
            $res = $websiteManage->list_policy('', '');           
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
                            <h2><?= $title ?></h2>
                        </div>
                        <div class="about-desc">
                            <?php
                                if($page == "terms"){
                                    echo html_entity_decode($terms_condition);
                                }else if($page == "privacy"){
                                    echo html_entity_decode($privacy_policies);
                                }else if($page == "disclaimer"){
                                    echo html_entity_decode($disclaimer);
                                }else if($page == "refundPolicy"){
                                    echo html_entity_decode($refund_policy);
                                }
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