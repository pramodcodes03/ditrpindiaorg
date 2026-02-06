<?php
error_reporting(1);
session_start();
ob_start();

include('include/classes/database_results.class.php');
include('include/classes/access.class.php');
include('include/classes/websiteManage.class.php');

$db   = new  database_results();
$access = new  access();
$websiteManage = new  websiteManage();


?>
<?php
$res = $websiteManage->list_logo('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $logo = LOGO_PATH . '/' . $id . '/' . $image;
    }
}

if ($logo != '') {
    $logo = $logo;
} else {
    $logo = HTTP_HOST . 'resources/images/logo.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
    <title><?= $name ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/admin/resources/vendors/feather/feather.css">
    <link rel="stylesheet" href="/admin/resources/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/admin/resources/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <link rel="stylesheet" href="/admin/resources/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/admin/resources/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/admin/resources/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/admin/resources/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="/admin/resources/js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/admin/resources/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/admin/resources/vendors/fullcalendar/fullcalendar.min.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="/admin/resources/css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="stylesheet" href="/admin/resources/css/style.css">
    <link rel="shortcut icon" href="<?= $logo ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- clock code -->
    <script>
        function display_ct7() {
            var x = new Date();
            var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
            hours = x.getHours() % 12;
            hours = hours ? hours : 12;
            hours = hours.toString().length == 1 ? 0 + hours.toString() : hours;

            var minutes = x.getMinutes().toString()
            minutes = minutes.length == 1 ? 0 + minutes : minutes;

            var seconds = x.getSeconds().toString()
            seconds = seconds.length == 1 ? 0 + seconds : seconds;

            //var x1=x.toUTCString("d M Y");

            var month = (x.getMonth() + 1).toString();
            month = month.length == 1 ? 0 + month : month;

            var dt = x.getDate().toString();
            dt = dt.length == 1 ? 0 + dt : dt;

            var x1 = dt + "-" + month + "-" + x.getFullYear();
            x1 = x1 + " " + hours + ":" + minutes + ":" + seconds + " " + ampm;
            document.getElementById('ct7').innerHTML = x1;
            display_c7();
        }

        function display_c7() {
            var refresh = 1000; // Refresh rate in milli seconds
            mytime = setTimeout('display_ct7()', refresh)
        }
        display_c7()
    </script>
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=645245faece41100193705f5&product=inline-share-buttons&source=platform" async="async"></script>

    <script>
        let defaultMerchantConfiguration = {
            "root": "",
            "style": {
                "bodyColor": "",
                "themeBackgroundColor": "",
                "themeColor": "",
                "headerBackgroundColor": "",
                "headerColor": "",
                "errorColor": "",
                "successColor": ""
            },
            "flow": "DEFAULT",
            "data": {
                "orderId": "",
                "token": "",
                "tokenType": "TXN_TOKEN",
                "amount": "",
                "userDetail": {
                    "mobileNumber": "",
                    "name": ""
                }
            },
            "merchant": {
                "mid": "",
                "name": "",
                "redirect": true
            },
            "labels": {},
            "payMode": {
                "labels": {},
                "filter": [],
                "order": []
            },
            "handler": {}
        };
    </script>
</head>