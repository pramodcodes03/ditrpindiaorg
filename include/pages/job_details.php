<?php
//print_r($_GET);

$id = isset($id) ?? '';
if ($id != '') {
    $res = $websiteManage->list_jobpost($id, '', ' LIMIT 0,1');
    if ($res && $res->num_rows > 0) {
        while ($data = $res->fetch_assoc()) {
            extract($data);
            $job_img = 'resources/default_images/job_default.jpg';
            if ($image != '')
                $job_img = JOBPOST_PATH . '/' . $id . '/' . $image;
        }
    }
}
?>


<?php
$res = $websiteManage->list_headimages('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $image = 'resources/default_images/about_default.jpg';
        if ($aboutus != '')
            $image     = BANNERS_PATH . '/' . $id . '/' . $aboutus;
?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title"><?= $title ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<!-- Event Details Start -->
<div class="rs-event-details pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="event-details-content">
                    <h3 class="event-title"><?= $title ?></h3>
                    <div class="event-meta">
                        <div class="event-date">
                            <i class="fa fa-calendar"></i>
                            <span>Start Date : <?= $post_date ?></span>
                        </div>
                        <div class="event-date">
                            <i class="fa fa-calendar"></i>
                            <span>Last Date : <?= $last_date ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="event-img">
                            <img src="<?= $job_img ?>" style="height:250px;" alt="<?= $title ?>" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="event-desc">
                            <h3> Job Description</h3>
                            <?= html_entity_decode($description) ?>
                        </div>
                        <div class="event-desc">
                            <h3> Required Skills</h3>
                            <?= html_entity_decode($skills) ?>
                        </div>
                        <div class="share-area">
                            <div class="row rs-vertical-middle">
                                <div class="col-md-4">
                                    <div class="book-btn">
                                        <a class="primary-btn" href="" data-toggle="modal" data-target="#jobApply">Apply Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Event Details End -->