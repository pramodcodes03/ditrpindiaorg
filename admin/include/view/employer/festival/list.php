<?php
$action = isset($_POST['select_date']) ? $_POST['select_date'] : '';

if ($action != '') {
    $date_select = isset($_POST['date_select']) ? $_POST['date_select'] : '';
    $month_select = date("m", strtotime($date_select));
    $date_select = date("d", strtotime($date_select));

    header('location:page.php?page=https://ditrp.digitalnexstep.com/admin/list-festival&day=' . $date_select . '&month=' . $month_select);
}


$todays_date = date("Y-m-d");

$month_select_todays_date = date("m", strtotime($todays_date));
$date_select_todays_date = date("d", strtotime($todays_date));

$tomorrow = date("Y-m-d", strtotime("+1 day"));
$month_select_tomorrow = date("m", strtotime($tomorrow));
$date_select_tomorrow = date("d", strtotime($tomorrow));


$yesterday = date("Y-m-d", strtotime("-1 day"));
$month_select_yesterday = date("m", strtotime($yesterday));
$date_select_yesterday = date("d", strtotime($yesterday));

$date = isset($_GET['date']) ? $_GET['date'] : $todays_date;

$month_select = isset($_GET['month']) ? $_GET['month'] : $month_select;
$date_select = isset($_GET['day']) ? $_GET['day'] : $date_select;

// if($date !=''){
//     $todays_date = $date;
// }

$image_path = 'resources/festival_noimage.jpg';
$action = isset($_POST['call_image']) ? $_POST['call_image'] : '';
$image_id = isset($_POST['image_id']) ? $_POST['image_id'] : '';
include_once('include/classes/festival.class.php');
$festival = new festival();
if ($action == 'Select Image') {
    $result = $festival->list_festival_images($image_id, '', '');

    if ($result != '') {
        $srno = 1;
        while ($data_result = $result->fetch_assoc()) {
            extract($data_result);
            $image_path = FESTIVAL_IMAGES_PATH . '/' . $data_id . '/' . $image;
        }
    }
}
?>

<style>
    .right_side {
        left: 50px;
    }

    #capture {
        background-repeat: no-repeat;
        width: 600px;
        height: 600px;
        background-size: 100%;
    }

    .top-section {
        height: 100px;
    }

    .bottom-section {
        position: relative;
        width: 600px;
        bottom: -48%;
    }

    p img {
        float: right;
        width: 55px !important;
        margin: 10px;
        position: relative;
        top: -100px;
        height: 100% !important;
        width: 20%;
        right: 5px;
    }

    .right_side img {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
        width: 150px;
        margin-bottom: 10px;
    }

    .fa {
        font-size: 12px;
        background-color: #000;
        color: #fff;
        padding: 3px;
        border-radius: 5px;
        margin-right: 5px;
    }

    .inst_name {
        width: 75%;
        font-size: 12px;
        padding: 15px;
        font-weight: 700;
        height: 90px;
    }

    .website_name {
        width: 80%;
        position: relative;
        top: -55px;
        padding: 0px 15px;
    }

    .address {
        position: relative;
        color: #fff;
        font-size: 14px;
        z-index: 999;
        padding: 0px 20px;
        text-align: center;
        height: 30px;
        top: 0px;
    }

    .bottom_image {
        width: 100%;
        position: relative;
        height: 70px;
        top: -55px;
    }

    .contact {
        position: relative;
        font-size: 15px;
        padding: 0px 20px;
        top: -15px;
    }

    .contact p {
        padding: 0px 5px;
    }

    .select_btn {
        padding: 5px;
        position: relative;
        width: 150px;
    }

    .ft-45 {
        font-size: 20px;
    }

    .mbB-30 {
        margin-bottom: 30px;
    }

    .title_date {
        font-size: 16px;
        background-color: black;
        color: #fff;
        margin: 0px 15px;
    }

    .social_icons {
        float: right;
        top: -80px;
        position: relative;
        right: 0;
        width: 25%;
        height: 30px;
    }

    .social_icons .fa {
        background-color: #000;
        width: 20px;
        height: 20px;
        text-align: center;
    }

    @media only screen and (max-width: 600px) {
        .social_icons {
            float: right;
            top: -90px;
            position: relative;
            right: 0px;
            width: 35%;
            height: 30px;
        }

        .social_icons .fa {
            background-color: #000;
            width: 15px;
            height: 15px;
            text-align: center;
        }


        .bottom-section {
            position: relative;
            width: 310px;
            bottom: -210px;
        }

        .contact {
            position: relative;
            font-size: 12px;
            padding: 0px 10px;
            top: -170px;
        }

        .col-md-4 {
            padding: 0;
            width: 30%;
            float: left;
        }

        .col-md-8 {
            width: 70%;
            float: right;
        }

        .top-section {
            height: 150px;
        }

        #capture {
            background-repeat: no-repeat;
            width: 310px;
            height: 343px;
            background-size: 100%;
        }

        p img {
            float: right;
            width: 90px !important;
            margin: 13px;
            position: relative;
            top: -90px;
            height: 100% !important;
        }

        .right_side img {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            width: 150px;
            margin-bottom: 10px;
        }

        .fa {
            font-size: 8px;
            background-color: #000;
            color: #fff;
            padding: 3px;
            border-radius: 5px;
            margin-right: 2px;
        }

        .inst_name {
            font-size: 10px;
            padding: 15px;
            font-weight: 700;
        }

        .website_name {
            position: relative;
            top: -25px;
            padding: 0px 15px;
        }

        .address {
            position: relative;
            color: #fff;
            font-size: 11px;
            z-index: 999;
            padding: 0px 15px;
            text-align: center;
            height: 30px;
            top: -170px;
        }

        .bottom_image {
            width: 100%;
            position: relative;
            height: 50px;
            top: -220px;
        }

        .contact {
            position: relative;
            bottom: -125px;
            font-size: 10px;
            padding: 0px 10px;
            display: inline-flex;

        }

        .contact p {
            padding: 0px 1px;
            font-size: 10px;
        }

        .select_btn {
            padding: 5px;
            position: relative;
            width: 150px;
        }

        .ft-45 {
            font-size: 0px;
        }

        .mbB-30 {
            margin-bottom: 30px;
            padding: 0;
        }

        .title_date {
            font-size: 12px;
            background-color: black;
            color: #fff;
            margin: 0px 2px;
        }

        .mobile-top {
            top: 10px;
        }

        .mobile-pd0 {
            padding: 0;
        }

        .box-body {
            padding: 0;
        }
    }

    @media screen and (min-device-width: 601px) and (max-width: 1500px) {
        .address {
            position: relative;
            color: #fff;
            font-size: 14px;
            z-index: 999;
            padding: 0px 20px;
            text-align: center;
            height: 30px;
            top: -170px;
        }

        .bottom_image {
            width: 100%;
            position: relative;
            height: 70px;
            top: -225px;
        }

        .contact {
            position: relative;
            font-size: 15px;
            padding: 0px 20px;
            top: -170px;
        }

        .bottom-section {
            position: relative;
            width: 600px;
            bottom: -445px;
        }
    }

    @media screen and (min-device-width: 1501px) and (max-width: 1920px) {
        .bottom-section {
            position: relative;
            width: 600px;
            /*bottom: 38%;*/
        }
    }
</style>
<div class="content-wrapper">
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Festivals Images</h4>


                    <section class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box">
                                    <!-- /.box-header -->
                                    <div class="box-body row" style="padding-bottom:100px;">

                                        <?php
                                        include_once('include/classes/institute.class.php');
                                        $institute     = new  institute();
                                        $res1 = $institute->list_institute_festival($_SESSION['user_id'], '');
                                        if ($res1 != '') {
                                            $srno = 1;
                                            while ($data = $res1->fetch_assoc()) {    //print($data);
                                                extract($data);

                                                $logo = $institute->get_institute_docs_single($_SESSION['user_id'], 'logo');
                                        ?>
                                                <div class="col-md-6 mobile-pd0">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <a href="#" style="width: fit-content;" class="btn btn-block btn-primary mb-3" id="btnDownload">Download</a>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="form-group col-md-6">
                                                                        <input class="form-control" type="date" name="date_select" placeholder="Select Date" value="" />
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="submit" name="select_date" class="btn btn-primary mr-2" value="Submit">
                                                                    </div>

                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>


                                                    <div id="capture" style="background-image: url('<?php echo $image_path ?>');">

                                                        <div class="top-section">
                                                            <p class="inst_name"><?= $INSTITUTE_NAME ?></p>

                                                            <p class="social_icons">
                                                                <i class="fa fa-facebook " aria-hidden="true"></i>
                                                                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                                                <i class="fa fa-twitter" aria-hidden="true"></i>
                                                                <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                                <i class="fa fa-instagram" aria-hidden="true"></i>
                                                            </p>
                                                            <div class="clearfix"></div>
                                                            <p><?= $logo ?></p>

                                                        </div>

                                                        <div class="bottom-section">
                                                            <div class="contact">
                                                                <div class="com-md-12 row" style="width: 100%;">
                                                                    <div class="col-md-4" style="padding: 0;">
                                                                        <p> <i class="fa fa-phone" aria-hidden="true"></i> <?= $MOBILE ?> </p>
                                                                        <?php if ($WEBSITE != '') {
                                                                        ?>
                                                                            <p><i class="fa fa-globe" aria-hidden="true"></i><?= $WEBSITE ?></p>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <div class="col-md-8">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <p class="address"><i class="fa fa-map-marker" aria-hidden="true" style="    background-color: transparent;"></i><?= $ADDRESS_LINE1 ?>, <?= $CITY_NAME ?>, <?= $STATE_NAME ?>, <?= $POSTCODE ?> </p>
                                                            <img class="bottom_image" src="resources/festival_bottom.png" id="img_prev" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mobile-top">


                                                    <div class="btn-group col-md-12 mbB-30" role="group" aria-label="Basic example">
                                                        <a href="https://ditrp.digitalnexstep.com/admin/list-festival&day=<?= $date_select_yesterday; ?>&month=<?= $month_select_yesterday; ?>" class="btn btn-outline-secondary col-md-4 title_date" title="Yesterday">
                                                            <i class="fa fa-calendar-minus-o ft-45" aria-hidden="true"></i> Yesterday
                                                        </a>
                                                        <a href="https://ditrp.digitalnexstep.com/admin/list-festival&day=<?= $date_select_todays_date; ?>&month=<?= $month_select_todays_date; ?>" class="btn btn-outline-secondary col-md-4 title_date" title="Today">
                                                            <i class="fa fa-calendar ft-45" aria-hidden="true"></i>Today
                                                        </a>'
                                                        <a href="https://ditrp.digitalnexstep.com/admin/list-festival&day=<?= $date_select_tomorrow; ?>&month=<?= $month_select_tomorrow; ?>" class="btn btn-outline-secondary col-md-4 title_date" title="Tomorrow">
                                                            <i class="fa fa-calendar-plus-o ft-45" aria-hidden="true"></i>Tomorrow
                                                        </a>
                                                    </div>





                                                    <?php

                                                    $res = $festival->list_festival('', " AND MONTH(A.date) = '$month_select' AND DAY(A.date) = '$date_select'", '');
                                                    if ($res != '') {
                                                        $srno = 1;
                                                        while ($data = $res->fetch_assoc()) {
                                                            extract($data);

                                                    ?>

                                                            <?php
                                                            $res1 = $festival->list_festival_images('', $id, '');
                                                            if ($res1 != '') {
                                                                $srno1 = 1;
                                                                while ($data1 = $res1->fetch_assoc()) {
                                                                    extract($data1);
                                                                    if ($image != '') {
                                                                        $image_path = FESTIVAL_IMAGES_PATH . '/' . $data_id . '/' . $image;
                                                            ?>
                                                                        <div class="col-md-4 right_side">
                                                                            <form method="post" action="" style="padding-right:25px">

                                                                                <a href="#">
                                                                                    <img src="<?= $image_path ?>" alt="<?= $name ?>" style="width:150px">
                                                                                    <input type="hidden" name="image_id" value="<?= $id ?>" />
                                                                                    <input class="btn btn-success select_btn" type="submit" name="call_image" value="Select Image" />
                                                                                </a>
                                                                            </form>
                                                                        </div>
                                                            <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>



                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->


                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </section>
                    <!-- /.content -->

                    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

                    <script type="text/javascript">
                        function date(date) {
                            var data = "";
                            data = 'https://ditrp.digitalnexstep.com/admin/list-festival&date=' + date;
                            window.location.href = data;
                        }
                    </script>