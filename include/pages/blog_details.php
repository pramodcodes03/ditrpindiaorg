<?php
//print_r($_GET);

if ($id != '') {

    $res = $websiteManage->list_blogs($id, '', ' LIMIT 0,1');
    if ($res && $res->num_rows > 0) {
        while ($data = $res->fetch_assoc()) {
            extract($data);
            $img = 'resources/default_images/job_default.jpg';
            if ($image != '')
                $img = '/' . BLOGS_PATH . '/' . $id . '/' . $image;
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
        if ($our_blogs != '')
            $image     = '/' . BANNERS_PATH . '/' . $id . '/' . $our_blogs;
?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title"><?= $name ?></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>

<!-- Courses Details Start -->
<div class="rs-courses-details pt-100 pb-70">
    <div class="container">
        <div class="row mb-30">
            <div class="col-lg-12 col-md-12">
                <div class="detail-img">
                    <img src="<?= $img ?>" alt="<?= $name ?>" />
                </div>

                <div class="course-desc">
                    <div class="desc-text">
                        <?= html_entity_decode($description); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>