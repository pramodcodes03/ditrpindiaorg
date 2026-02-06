<!-- Breadcrumbs Start -->
<?php
$res = $websiteManage->list_headimages('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $image = 'resources/default_images/about_default.jpg';
        if ($our_blogs != '')
            $image     = BANNERS_PATH . '/' . $id . '/' . $our_blogs;
?>
        <div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
            <div class="breadcrumbs-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="page-title">OUR BLOGS</h1>
                            <ul>
                                <li>
                                    <a class="active" href="index.php">Home</a>
                                </li>
                                <li>Our Blogs</li>
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

<!-- Blog Section Start Here -->
<div class="blog-page-area sec-spacer">
    <div class="container">
        <?php
        $res = $websiteManage->list_blogs('', '');
        if ($res != '') {
            while ($data = $res->fetch_assoc()) {
                extract($data);
                $img = 'resources/default_images/job_default.jpg';
                if ($image != '')
                    $img = BLOGS_PATH . '/' . $id . '/' . $image;
        ?>
                <div class="row mb-50 blog-inner">
                    <div class="col-lg-6 col-md-12">
                        <div class="blog-images">
                            <a href="/BlogDetails?id=<?= $id ?>"><i class="fa fa-link" aria-hidden="true"></i> <img src="<?= $img ?>" alt=""></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="blog-content">
                            <h4><a href="/BlogDetails?id=<?= $id ?>"><?= $name ?></a></h4>

                            <p><?= html_entity_decode($description) ?></p>
                            <a class="primary-btn" href="/BlogDetails/<?= $id ?>">Read More</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>
<!-- Blog End  -->