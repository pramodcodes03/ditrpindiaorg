  		<!-- Breadcrumbs Start -->
         <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($download_materials!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$download_materials;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">Download Material</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>Download</li>
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

        <div class="shipping-area sec-spacer">
            <div class="container">
                <div class="tab-content">
                    <div class="tab-pane active" id="checkout">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="product-list">
                                    <table style="width: 100%;">
                                        <tbody>
                                        <?php
                                            $res = $websiteManage->list_download_materials('', '');           
                                            if($res!='')
                                            {
                                                while($data = $res->fetch_assoc())
                                                {
                                                    extract($data);
                                                    $download1 = 'resources/default_images/pdf.jpg';
                                                    if($files!='')
                                                        $download = DOWNLOADMATERIAL_PATH.'/'.$id.'/'.$files;
                                        ?> 
                                        <tr>
                                            <td>
                                                <div class="des-pro">
                                                    <h4><?= $title ?></h4>
                                                </div>
                                            </td>
                                             <td><a href="<?= $download ?>" alt="" target="_blank"><img src="<?= $download1 ?>" alt="" style="width:100px"></a></td>
                                        </tr>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </tbody></table>                               
                                </div><!-- .product-list end -->
                            </div>
                        </div>
                    </div>                                 
                </div>
            </div>
        </div>