<!DOCTYPE html>
<html lang="en">
<?php 
$page = isset($_GET['page']) ? $_GET['page'] : '';

include('include/common/html_header.php'); ?>

<body>
    <?php
    include "include/common/header.php";

    include "include/common/slider.php";

    include("include/pages/home.php");

    include "include/common/footer.php";
    ?>
</body>

</html>
<?php
$res = $websiteManage->list_advertise('', ' AND website = 1', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        //print_r($data);
        $ads_image = 'resources/default_images/about_default.jpg';
        if ($image != '' && $website == '1')
            $ads_image = ADVERTISE_PATH . '/' . $id . '/' . $image;
?>

        <div class="modal fade" id="myModalsdsfs">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <a href="<?= $link ?>" target="<?= $link ?>"> <img src='<?= $ads_image ?>' style='border-radius:0;'/></a> -->

                        <a href="https://ditrp.digitalnexstep.com/" target="https://ditrp.digitalnexstep.com/"> <img src='uploads/popup/_119921648_logo.jpg' style='border-radius:0;' /></a>

                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<script type="text/javascript">
    $(window).on('load', function() {
        var delayMs = 1500; // delay in milliseconds

        setTimeout(function() {
            $('#myModalsdsfs').modal('show');
        }, delayMs);
    });
</script>