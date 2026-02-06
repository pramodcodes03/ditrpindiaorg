  		<!-- Breadcrumbs Start -->
         <?php
            $res = $websiteManage->list_headimages('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $image = 'resources/default_images/about_default.jpg';
                    if($verification!='')
                        $image     = BANNERS_PATH.'/'.$id.'/'.$verification;
        ?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
		    <div class="breadcrumbs-inner">
		        <div class="container">
		            <div class="row">
		                <div class="col-md-12 text-center">
		                    <h1 class="page-title">Payment Methods</h1>
		                    <ul>
		                        <li>
		                            <a class="active" href="index.php">Home</a>
		                        </li>
		                        <li>Payment Methods</li>
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
                                            $res = $websiteManage->list_payment('', '');           
                                            if($res!='')
                                            {
                                                while($data = $res->fetch_assoc())
                                                {
                                                    extract($data);
                                                    $img = 'resources/default_images/pdf.jpg';
                                                    if($image!='')
                                                        $img = PAYMENTS_PATH.'/'.$id.'/'.$image;
                                        ?> 
                                        <tr>
                                            <td>
                                                <div class="des-pro">
                                                    <h4><?= $name ?></h4>
                                                </div>
                                            </td>
                                             <td><a href="<?= $link ?>" alt="" target="_blank"><img src="<?= $img ?>" alt="" style="width:100px"></a></td>
                                             <td><a href="<?= $link ?>" alt="" target="_blank"><?= $link ?></a></td>
                                             <td><?= $description ?> </td>
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