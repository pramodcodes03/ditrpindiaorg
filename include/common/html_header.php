<?php
//error_reporting(0);
session_start();
ob_start();
date_default_timezone_set('Asia/Kolkata');
include('include/classes/database_results.class.php');
include('include/classes/access.class.php');
include('include/classes/websiteManage.class.php');

$db            = new  database_results();
$access        = new  access();
$websiteManage = new  websiteManage();

// Handle AJAX request to close popup
if (isset($_POST['close_popup'])) {
    $_SESSION['popup_closed'] = true;
    exit('success');
}

// Check if popup should be shown (not closed in this session)
$showPopup = !isset($_SESSION['popup_closed']);
?>

<?php
$res = $websiteManage->list_contact('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
    }
}
?>
<?php
$res = $websiteManage->list_logo('', '');
if ($res != '') {
    while ($data = $res->fetch_assoc()) {
        extract($data);
        $logo = LOGO_PATH . '/' . $id . '/' . $image;
    }
}
?>

<head>
    <!-- meta tag -->
    <meta charset="utf-8">
    <title><?= $name ?></title>
    <meta name="description" content="">
    <!-- responsive tag -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $logo ?>">
    <!-- bootstrap v4 css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="resources/css/bootstrap.min.css"> -->
    <!-- font-awesome css -->
    <link rel="stylesheet" type="text/css" href="resources/css/font-awesome.min.css">
    <!-- animate css -->
    <link rel="stylesheet" type="text/css" href="resources/css/animate.css">
    <!-- owl.carousel css -->
    <link rel="stylesheet" type="text/css" href="resources/css/owl.carousel.css">
    <!-- slick css -->
    <link rel="stylesheet" type="text/css" href="resources/css/slick.css">
    <!-- magnific popup css -->
    <link rel="stylesheet" type="text/css" href="resources/css/magnific-popup.css">
    <!-- Offcanvas CSS -->
    <link rel="stylesheet" type="text/css" href="resources/css/off-canvas.css">
    <!-- flaticon css  -->
    <link rel="stylesheet" type="text/css" href="resources/fonts/flaticon.css">
    <!-- flaticon2 css  -->
    <link rel="stylesheet" type="text/css" href="resources/fonts/fonts2/flaticon.css">
    <!-- rsmenu CSS -->
    <link rel="stylesheet" type="text/css" href="resources/css/rsmenu-main.css">
    <!-- rsmenu transitions CSS -->
    <link rel="stylesheet" type="text/css" href="resources/css/rsmenu-transitions.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="resources/style.css">
    <!-- responsive css -->
    <link rel="stylesheet" type="text/css" href="resources/css/responsive.css">
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />

    <style>
        .selectpicker option {
            border: none;
            background-color: white;
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            color: #000;
            margin: 0;
            padding-left: 0;
            background: none;
        }

        .selectpicker {
            border: 1px solid #afafaf;
            background-color: white;
            -webkit-appearance: none;
            -moz-appearance: none;
            color: #000;
            margin: 0;
            padding: 10px;
        }

        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 380px;
        }

        .selectpicker option {
            border: none;
            background-color: white;
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            color: #000;
            margin: 0;
            padding-left: 0;
            background: none;
        }

        select.selectpicker {
            border: none;
            background-color: white;
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            color: #000;
            font-weight: bold;
            margin: 0;
            padding-left: 0;
            background: none;
        }

        /* Promotional Popup Banner Styles */
        .popup-banner {
            background: linear-gradient(135deg, rgb(30, 42, 171) 0%, rgb(56, 94, 177) 100%);
            color: white;
            padding: 12px 20px;
            text-align: center;
            position: relative;
            display: <?php echo $showPopup ? 'block' : 'none'; ?>;
            font-size: 14px;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            top: 0;
        }

        .popup-content {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        .popup-banner h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            display: inline;
        }

        .popup-banner .highlight {
            color: #ffff00;
            font-weight: bold;
        }

        .popup-banner .code {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: #ffff00;
        }

        .popup-banner .enroll-link {
            color: #ffff00;
            text-decoration: underline;
            font-weight: bold;
        }

        .popup-banner .enroll-link:hover {
            color: #fff;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-50%) scale(1.1);
        }

        /* Responsive Design for Popup */
        @media (max-width: 768px) {
            .popup-banner {
                padding: 10px 15px;
                font-size: 12px;
            }

            .popup-banner h2 {
                font-size: 18px;
                display: block;
                margin-bottom: 8px;
            }

            .close-btn {
                right: 15px;
                width: 25px;
                height: 25px;
                font-size: 16px;
            }
        }

        /* Animation for popup */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .popup-banner {
            animation: slideDown 0.5s ease-out;
        }
    </style>

    <!-- GetButton.io widget -->
    <script type="text/javascript">
        (function() {
            var options = {
                whatsapp: "<?= $contact_number1 ?>", // WhatsApp number
                call_to_action: "Message us", // Call to action
                button_color: "#FF6550", // Color of button
                position: "left", // Position may be 'right' or 'left'
            };
            var proto = 'https:',
                host = "getbutton.io",
                url = proto + '//static.' + host;
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = url + '/widget-send-button/js/init.js';
            s.onload = function() {
                WhWidgetSendButton.init(host, proto, options);
            };
            var x = document.getElementsByTagName('script')[0];
            x.parentNode.insertBefore(s, x);
        })();
    </script>
    <!-- /GetButton.io widget -->

    <!-- Popup Banner JavaScript -->
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('popupBanner');
            if (popup && <?php echo $showPopup ? 'true' : 'false'; ?>) {}
            popup.style.display = 'block';
        });
        // Function to close popup
        function closePopup() {
            const popup = document.getElementById('popupBanner');
            if (popup) {
                popup.style.display = 'none';

                // Send AJAX request to set session variable
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('close_popup=1');
            }
        }

        // Optional: Auto-close popup after 15 seconds (uncomment if needed)
        /*
        setTimeout(function() {
            const popup = document.getElementById('popupBanner');
            if (popup && popup.style.display !== 'none') {
                closePopup();
            }
        }, 15000);
        */
    </script>

</head>

<!-- Add this HTML right after the opening <body> tag in your main template -->
<!-- Promotional Popup Banner -->
<div class="popup-banner" id="popupBanner">
    <div class="popup-content">
        <h2 style=><span class="highlight">WELCOME TO DITRP INDIA</span></h2>
        
        <button class="close-btn" onclick="closePopup()" title="Close">&times;</button>
    </div>
</div>
<!-- GetButton.io widget -->
<script type="text/javascript">
    (function() {
        var options = {
            whatsapp: "<?= $contact_number1 ?>", // WhatsApp number
            call_to_action: "Message us", // Call to action
            button_color: "#FF6550", // Color of button
            position: "left", // Position may be 'right' or 'left'
        };
        var proto = 'https:',
            host = "getbutton.io",
            url = proto + '//static.' + host;
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url + '/widget-send-button/js/init.js';
        s.onload = function() {
            WhWidgetSendButton.init(host, proto, options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    })();
</script>
<!-- /GetButton.io widget -->

</head>