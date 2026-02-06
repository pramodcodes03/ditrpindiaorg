<!-- Slider Area Start -->
<div id="rs-slider" class="slider-overlay-2" style="margin-top:10px">     
    <div id="home-slider" class="rs-carousel owl-carousel" data-loop="true" data-items="1" data-margin="0" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="1200" data-dots="false" data-nav="true" data-nav-speed="false" data-mobile-device="1" data-mobile-device-nav="true" data-mobile-device-dots="true" data-ipad-device="1" data-ipad-device-nav="true" data-ipad-device-dots="true" data-md-device="1" data-md-device-nav="true" data-md-device-dots="false">
         <?php
            $res = $websiteManage->list_slider('', '');           
            if($res!='')
            {
                while($data = $res->fetch_assoc())
                {
                    extract($data);
                    $PHOTO = 'resources/default_images/slider_default.jpg';
                    if($SLIDER_IMG!='')
                        $PHOTO = SLIDERIMAGE_PATH.'/'.$SLIDER_ID.'/'.$SLIDER_IMG;
            ?>
            <div class="item active">
                <img src="<?=$PHOTO ?>"/>                    
            </div>                 
        <?php
            }
        }
        ?>     

    </div>         
</div>
<!-- Slider Area End -->