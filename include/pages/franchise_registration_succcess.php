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
                    <h1 class="page-title">Franchise Registration Success</h1>                       
                </div>
            </div>
        </div>
    </div>
</div>
    <?php
    }
}
?>
<!-- Mission Start -->
<div class="rs-mission sec-color">
    <div class="container">
        <div class="row col-lg-12 col-md-12" style="padding:25px;">
            <div id="survey_container" class="wizard" style="color: #3a9234;">	
                <div id="middle-wizard" class="wizard-branch wizard-wrapper">
                    <div class="submit step wizard-step current" id="complete" style="display: block;">
                        <i class="icon-check"></i>						
                        <h3 style="line-height: 1.5em;">Your application submitted! Thank you for your time.<br>
                        Please check your mail for your Login ID and Password.<br>
                        </h3>
                        <p style="line-height: 3.5em;"> Kindly login to your account and upload the required documents (within 7 days) to move authorization process ahead.<p>
                        <a class="btn btn-success" href="<?= HTTP_HOST ?>/admin/login">Click to Login</a>
                    </div>            
                </div>
                <br>
            </div>
        </div>
    </div>
</div>