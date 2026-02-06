<?php
$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['submit_enquiry']) ? $_POST['submit_enquiry'] : '';
if ($action != '') {
    $result = $websiteManage->submit_enquiry();
    $result = json_decode($result, true);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = isset($result['message']) ? $result['message'] : '';
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        unset($_POST);
    }
}

$action = isset($_POST['submit_job_enquiry']) ? $_POST['submit_job_enquiry'] : '';
if ($action != '') {
    $result = $websiteManage->submit_job_enquiry();
    $result = json_decode($result, true);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = isset($result['message']) ? $result['message'] : '';
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        unset($_POST);
    }
}

$action1 = isset($_POST['submit_services_enquiry']) ? $_POST['submit_services_enquiry'] : '';
if ($action1 != '') {
    $result = $websiteManage->submit_services_enquiry();
    $result = json_decode($result, true);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = isset($result['message']) ? $result['message'] : '';
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        unset($_POST);
    }
}
?>

<!-- Partner Start -->
<div id="rs-partner" class="rs-partner pt-70 pb-70">
    <div class="container">
        <div class="rs-carousel owl-carousel" data-loop="true" data-items="4" data-margin="80" data-autoplay="true" data-autoplay-timeout="5000" data-smart-speed="2000" data-dots="false" data-nav="false" data-nav-speed="false" data-mobile-device="2" data-mobile-device-nav="false" data-mobile-device-dots="false" data-ipad-device="4" data-ipad-device-nav="false" data-ipad-device-dots="false" data-md-device="4" data-md-device-nav="false" data-md-device-dots="false">
            <?php
            $res = $websiteManage->list_partners('', '');
            if ($res != '') {
                while ($data = $res->fetch_assoc()) {
                    extract($data);
                    $img = 'resources/images/partner/1.png';
                    if ($image != '')
                        $img     = '/' . PARTNERS_PATH . '/' . $id . '/' . $image;
            ?>
                    <div class="partner-item" style="padding: 15px">
                        <a><img src="<?= $img ?>" alt="<?= $name ?>" title="<?= $name ?>"></a>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<!-- Partner End -->


<!-- Footer Start -->
<footer id="rs-footer" class="bg8 blue-bg rs-footer">
    <div class="blue-overlay" style="background-color: <?= $footer_color ?>"></div>
    <div class="container">
        <!-- Footer Address -->
        <div>
            <?php
            $res = $websiteManage->list_contact('', '');
            if ($res != '') {
                while ($data = $res->fetch_assoc()) {
                    extract($data);
            ?>
                    <div class="row footer-contact-desc" style="background-color: <?= $address_box_color ?>">
                        <div class="col-md-4">
                            <div class="contact-inner">
                                <i class="fa fa-map-marker"></i>
                                <h4 class="contact-title">Address</h4>
                                <p class="contact-desc">
                                    <?= $address ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-inner">
                                <i class="fa fa-phone"></i>
                                <h4 class="contact-title">Phone Number</h4>
                                <p class="contact-desc">
                                    <?= $contact_number1 ?><br>
                                    <?= $contact_number2 ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-inner">
                                <i class="fa fa-map-marker"></i>
                                <h4 class="contact-title">Email Address</h4>
                                <p class="contact-desc">
                                    <?= $email_id ?>
                                </p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>

    <!-- Footer Top -->
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <h5 class="footer-title">RECENT BLOGS</h5>
                    <div class="recent-post-widget">
                        <?php
                        $res = $websiteManage->list_blogs('', '', ' LIMIT 0,2');
                        if ($res != '') {
                            while ($data = $res->fetch_assoc()) {
                                extract($data);
                                $photo = '';
                                $photo = '/' . BLOGS_PATH . '/' . $id . '/' . $image;
                        ?>
                                <div class="post-item">
                                    <div class="post-date">
                                        <img src="<?= $photo ?>" />
                                    </div>
                                    <div class="post-desc">
                                        <h5 class="post-title"><a href="/BlogDetails/<?= $id ?>"><?= $name ?></a></h5>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="sitemap-widget">
                        <li class="active"><a href="index.php"><i class="fa fa-angle-right" aria-hidden="true"></i>Home</a></li>

                        <li><a href="/terms"><i class="fa fa-angle-right" aria-hidden="true"></i>Terms & Conditions</a></li>

                        <li><a href="/downloadMaterial"><i class="fa fa-angle-right" aria-hidden="true"></i>Downloads</a></li>

                        <li><a href="/privacy"><i class="fa fa-angle-right" aria-hidden="true"></i>Privacy Policies</a></li>

                        <li><a href="/paymentMethods"><i class="fa fa-angle-right" aria-hidden="true"></i>Payments Methods</a></li>

                        <li><a href="/refundPolicy"><i class="fa fa-angle-right" aria-hidden="true"></i>Refund policies</a></li>

                        <li><a href="/disclaimer"><i class="fa fa-angle-right" aria-hidden="true"></i>Disclaimer</a></li>

                        <li><a href="/ourBlogs"><i class="fa fa-angle-right" aria-hidden="true"></i>Our Blogs</a></li>

                        <li><a href="/affiliations"><i class="fa fa-angle-right" aria-hidden="true"></i>Our Affiliations</a></li>

                        <li><a href="ourteam"><i class="fa fa-angle-right" aria-hidden="true"></i>Our Team</a></li>

                        <li><a href="/ourCenters"><i class="fa fa-angle-right" aria-hidden="true"></i>Our Register Center's</a></li>

                        <li><a href="/franchiseDetails"><i class="fa fa-angle-right" aria-hidden="true"></i>Franchise Details</a></li>

                        <li><a href="/ourCentersLocation" target="_blank"><i class="fa fa-angle-right" aria-hidden="true"></i>Our Center's Location</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-share">
                <ul>
                    <?php
                    $res = $websiteManage->list_social_links('', '');
                    if ($res != '') {
                        while ($data = $res->fetch_assoc()) {
                            extract($data);
                    ?>
                            <li><a href="<?= $link ?>"><i class="<?= $icon ?>"></i></a></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- <div style="text-align:center; margin-top:20px; float:right">                        
                        Visitor Count 
                        <a href='https://www.freevisitorcounters.com'>at freevisitorcounters.com</a> <script type='text/javascript' src='https://www.freevisitorcounters.com/auth.php?id=7289bb0eb47c0910f5b3402d7fba857f55ae76d9'></script>
<script type="text/javascript" src="https://www.freevisitorcounters.com/en/home/counter/1076061/t/3"></script>
                    </div> -->


        </div>
    </div>


    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="copyright">
                <p>© <?= date("Y"); ?> All Rights Reserved By <a href="https://hellodigitalindia.co.in" target="_blank"> DITRP </a> </p>
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->

<!-- start scrollUp  -->
<div id="scrollUp">
    <i class="fa fa-angle-up"></i>
</div>


<!--   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script> -->

<!-- modernizr js -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="resources/js/modernizr-2.8.3.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<!--        
        <script src="resources/js/jquery.min.js"></script>
       
         <script src="resources/js/bootstrap.min.js"></script> -->
<!-- owl.carousel js -->
<script src="resources/js/owl.carousel.min.js"></script>
<!-- slick.min js -->
<script src="resources/js/slick.min.js"></script>
<!-- isotope.pkgd.min js -->
<script src="resources/js/isotope.pkgd.min.js"></script>
<!-- imagesloaded.pkgd.min js -->
<script src="resources/js/imagesloaded.pkgd.min.js"></script>
<!-- wow js -->
<script src="resources/js/wow.min.js"></script>
<!-- counter top js -->
<script src="resources/js/waypoints.min.js"></script>
<script src="resources/js/jquery.counterup.min.js"></script>
<!-- magnific popup -->
<script src="resources/js/jquery.magnific-popup.min.js"></script>
<!-- rsmenu js -->
<script src="resources/js/rsmenu-main.js"></script>
<!-- plugins js -->
<script src="resources/js/plugins.js"></script>
<!-- main js -->
<script src="resources/js/main.js"></script>
<script src="resources/js/custom.js"></script>

<!-- Multi Language Code -->
<!--  <script type="text/javascript">
                function googleTranslateElementInit() {
                  new google.translate.TranslateElement({pageLanguage: 'en',layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

         <script type="text/javascript">
            var translatedText='';
                var interval=setInterval(function(){
                   var el=document.getElementsByClassName('goog-te-menu-value')[0];  
                   if(el && el.innerText!==translatedText){
                        translatedText=el.innerText;
                        console.log('changed');
                    }
                },200);
         </script> -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        setCookie('googtrans', '/en/pt', 1);
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,hi,mr,ur,bn,bho,gu,doi,kn,gom,mr,ne,ml,pa,sa,sd,te,ta'
        }, 'google_translate_element');
    }

    function setCookie(key, value, expiry) {
        var expires = new Date();
        expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Course Enquiry Form</h4>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Select Course*</label>
                                <select class="form-control" id="course_id" name="course_id">
                                    <?php
                                    $course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
                                    echo $db->MenuItemsDropdown('courses', 'COURSE_ID', 'COURSE_NAME', 'COURSE_ID,COURSE_NAME', $course_id, ' WHERE ACTIVE = 1');
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Full Name*</label>
                                <input id="fullname" name="fullname" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Email Id*</label>
                                <input name="emailId" class="form-control" type="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Mobile Number*</label>
                                <input id="mobile" name="mobile" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Message</label>
                                <input name="message" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn-send" type="submit" name="submit_enquiry" value="Submit">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>

        </div>

    </div>
</div>


<div class="modal fade" id="jobApply" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Job Enquiry Form</h4>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Select Job*</label>
                                <select class="form-control" id="job_id" name="job_id" required>
                                    <option value=""> Select </option>
                                    <?php
                                    $job_id = isset($_POST['job_id']) ? $_POST['job_id'] : '';
                                    echo $db->MenuItemsDropdown('job_updates', 'id', 'title', 'id,title', $course_id, ' WHERE active = 1');
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Full Name*</label>
                                <input id="fullname" name="fullname" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Email Id*</label>
                                <input name="emailId" class="form-control" type="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Mobile Number*</label>
                                <input id="mobile" name="mobile" class="form-control" type="text" maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Message</label>
                                <input name="message" class="form-control" type="text" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn-send" type="submit" name="submit_job_enquiry" value="Submit">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="servicesEnquiry" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Services Enquiry Form</h4>
            </div>
            <form action="" method="post">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Select Service*</label>
                                <select class="form-control" id="id" name="id" required>
                                    <option value=""> Select </option>
                                    <?php
                                    $id = isset($_POST['id']) ? $_POST['id'] : '';
                                    echo $db->MenuItemsDropdown('our_services', 'id', 'name', 'id,name', $id, ' WHERE active = 1');
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Center Name*</label>
                                <input id="fullname" name="fullname" class="form-control" type="text" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Email Id*</label>
                                <input name="emailId" class="form-control" type="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Mobile Number*</label>
                                <input id="mobile" name="mobile" class="form-control" type="text" maxlength="10" required>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>State</label>
                                <select name="state" class="form-control selectpicker des" data-show-subtext="false" data-live-search="true" style="-webkit-appearance: none;">
                                    <option>Please Select State</option>
                                    <?php
                                    $state = isset($_POST['state']) ? $_POST['state'] : '';
                                    echo $db->MenuItemsDropdown('states_master', 'STATE_ID', 'STATE_NAME', 'STATE_ID,STATE_NAME', $state, ' ORDER BY STATE_NAME ASC');
                                    ?>
                                </select>
                                <span class="help-block"><?= isset($errors['state']) ? $errors['state'] : '' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>City</label>
                                <input id="city" name="city" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Pincode</label>
                                <input id="pincode" name="pincode" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Remark</label>
                                <input name="remark" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input class="btn-send" type="submit" name="submit_services_enquiry" value="Submit">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- <script>
    var counterContainer = document.querySelector(".website-counter");
    var resetButton = document.querySelector("#reset");
    var visitCount = localStorage.getItem("page_view");

    // Check if page_view entry is present
    if (visitCount) {
    visitCount = Number(visitCount) + 1;
    localStorage.setItem("page_view", visitCount);
    } else {
    visitCount = 1;
    localStorage.setItem("page_view", 1);
    }
    counterContainer.innerHTML = visitCount;

    // Adding onClick event listener
    resetButton.addEventListener("click", () => {
    visitCount = 1;
    localStorage.setItem("page_view", 1);
    counterContainer.innerHTML = visitCount;
    });

</script> -->