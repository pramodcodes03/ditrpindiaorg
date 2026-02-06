<!-- partial:partials/_footer.html -->
<footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. Premium <a href="#" target="_blank">All rights reserved.</span>
  </div>
</footer>
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<!-- search box for options-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="/admin/resources/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="/admin/resources/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="/admin/resources/vendors/select2/select2.min.js"></script>
<!-- End plugin js for this page -->
<!-- Plugin js for this page -->
<script src="/admin/resources/vendors/chart.js/Chart.min.js"></script>
<script src="/admin/resources/vendors/datatables.net/jquery.dataTables.js"></script>
<script src="/admin/resources/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="/admin/resources/js/dataTables.select.min.js"></script>
<script src="/admin/resources/js/custom.js"></script>
<!-- End plugin js for this page -->
<script src="/admin/resources/vendors/moment/moment.min.js"></script>
<script src="/admin/resources/vendors/fullcalendar/fullcalendar.min.js"></script>

<!-- inject:js -->
<script src="/admin/resources/js/off-canvas.js"></script>
<script src="/admin/resources/js/hoverable-collapse.js"></script>
<script src="/admin/resources/js/template.js"></script>
<script src="/admin/resources/js/settings.js"></script>
<script src="/admin/resources/js/todolist.js"></script>
<script src="/admin/resources/js/calendar.js"></script>
<script src="/admin/resources/js/tabs.js"></script>

<!-- endinject -->
<!-- Custom js for this page-->
<script src="/admin/resources/js/dashboard.js"></script>
<script src="/admin/resources/js/Chart.roundedBarCharts.js"></script>
<!-- End custom js for this page-->
<!-- Custom js for this page-->
<script src="/admin/resources/js/file-upload.js"></script>
<script src="/admin/resources/js/typeahead.js"></script>
<script src="/admin/resources/js/select2.js"></script>
<!-- End custom js for this page-->

<!-- plugin js for this page -->
<script src="/admin/resources/vendors/tinymce/tinymce.min.js"></script>
<script src="/admin/resources/vendors/quill/quill.min.js"></script>
<script src="/admin/resources/vendors/simplemde/simplemde.min.js"></script>
<script src="/admin/resources/js/editorDemo.js"></script>

<!-- Custom js for this page-->
<script src="/admin/resources/js/data-table.js"></script>



<script>
  var institute_id = '<?= isset($institute_id) ? $institute_id : '' ?>';
  var staff_id = '<?= isset($staff_id) ? $staff_id : '' ?>';
  var APP_PATH = '<?php echo  HTTP_HOST_ADMIN; ?>';
</script>

<script type="text/javascript">
  // Add Course Plans Multiple
  function addMorePlans() {
    var count1 = $("#filecount1").val();
    var html_data = '<div class="col-md-8"><div class="row form-group <?= (isset($errors['fees'])) ? 'has-error' : '' ?>"><div class="col-sm-6"><select class="form-control" id="plan' + count1 + '" name="plan' + count1 + '"><?php $plan = isset($_POST['plan']) ? $_POST['plan'] : '';
                                                                                                                                                                                                                                echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan, ' WHERE ACTIVE=1 AND DELETE_FLAG=0'); ?></select><span class="help-block"><?= isset($errors['plan']) ? $errors['plan'] : '' ?></span></div><div class="col-sm-6"><input class="form-control" id="fees' + count1 + '" name="fees' + count1 + '" placeholder="Exam Fees" value="<?= isset($_POST['fees']) ? $_POST['fees'] : '' ?>" type="text"><span class="help-block"><?= isset($errors['fees']) ? $errors['fees'] : '' ?></span></div></div></div></div>';
    $("#filecount1").val(parseInt(count1) + parseInt(1));
    $("#add_more_plans").append(html_data);
  }

  // Add Installments
  function addMoreInstallments() {
    var count1 = $("#filecount4").val();
    var html_data = '<tr><td><input type="text" class="form-control" name="installment_name' + count1 + '" id="installment_name' + count1 + '" value="" /></td><td><input type="text" class="form-control" name="installment_amount' + count1 + '" id="installment_amount' + count1 + '" value="" /></td><td><input type="date" class="form-control" name="installment_date' + count1 + '" id="installment_date' + count1 + '" value="" max="2999-12-31" /></td><td></td></tr>';
    $("#filecount4").val(parseInt(count1) + parseInt(1));
    $("#add_more_installments").append(html_data);
  }

  // admin->manage courses
  function addMoreCourseMaterial() {
    var count = $("#filecount").val();
    var html_data = '<div class="form-group <?= (isset($errors['filetitle'])) ? 'has-error' : '' ?>"><div class="row" style="margin:15px"><input class="col-md-6 form-control" id="filetitle' + count + '" name="filetitle' + count + '" placeholder="File Title" value="<?= isset($_POST['filetitle']) ? $_POST['filetitle'] : '' ?>" type="text"><span class="help-block"><?= isset($errors['coursematerial']) ? $errors['coursematerial'] : '' ?></span><input class="col-md-6" id="coursematerial' + count + '" name="coursematerial' + count + '" type="file"><p class="help-block"><?= (isset($errors['coursematerial'])) ? $errors['coursematerial'] : '' ?> </p></div></div>';
    $("#filecount").val(parseInt(count) + parseInt(1));
    $("#add_more_files").append(html_data);
  }

  //add videos
  function addMoreVideoMaterial() {
    var count = $("#filecount3").val();
    var html_data = '<div class="form-group <?= (isset($errors['videotitle'])) ? 'has-error' : '' ?>"><div class="row" style="margin:15px"><div class="col-md-6 "><input class="form-control" id="videotitle' + count + '" name="videotitle' + count + '" placeholder="Video Title" value="<?= isset($_POST['videotitle']) ? $_POST['videotitle'] : '' ?>" type="text"><span class="help-block"><?= isset($errors['videotitle']) ? $errors['videotitle'] : '' ?></span></div><div class="col-md-6 "><input class="form-control" id="videomaterial' + count + '" name="videomaterial' + count + '" type="text" placeholder="Video Link"><p class="help-block"><?= (isset($errors['videomaterial'])) ? $errors['videomaterial'] : '' ?> </p></div></div></div>';
    $("#filecount3").val(parseInt(count) + parseInt(1));
    $("#add_more_files3").append(html_data);
  }


  // Add Subject To Course (New Course)
  function addMoreSubjects() {
    //var max = 14;

    var count2 = $("#filecount2").val();
    var html_data = '<input class="col-md-8 form-control" id="subject' + count2 + '" name="subject' + count2 + '" placeholder="Enter Subject Name" value="<?= isset($_POST['subject']) ? $_POST['subject'] : '' ?>" type="text" style="margin: 15px 0px;" maxlength="100"><span class="help-block"><?= isset($errors['subject']) ? $errors['subject'] : '' ?></span>';
    $("#filecount2").val(parseInt(count2) + parseInt(1));
    $("#add_more_subjects").append(html_data);

    //   if (count2 <= max) {

    //   }

  }

  // Add Subject To Course Typing
  function addMoreSubjectsTyping() {
    var max = 9;

    var count2 = $("#filecount2").val();
    if (count2 <= max) {
      var html_data = '<div class="row"><input class="col-md-4 form-control" id="subject' + count2 + '" name="subject' + count2 + '" placeholder="Enter Subject Name" value="<?= isset($_POST['subject']) ? $_POST['subject'] : '' ?>" type="text" style="margin: 15px 15px;" maxlength="100"><input class="col-md-3 form-control" id="speed' + count2 + '" name="speed' + count2 + '" placeholder="Enter Speed (WPM)" value="<?= isset($_POST['speed']) ? $_POST['speed'] : '' ?>" type="text" style="margin: 15px 15px;" maxlength="100"><span class="help-block"><?= isset($errors['subject']) ? $errors['subject'] : '' ?></span></div>';
      $("#filecount2").val(parseInt(count2) + parseInt(1));
      $("#add_more_subjects_typing").append(html_data);
    }

  }


  <?php
  if (isset($student_id) && $student_id != '')
    echo ' getStudPaymentInfo(); ';
  ?>

  function toggleRow(rowid) {
    $("#row-" + rowid).toggle();
  }

  function calBalAmt() {
    var totalexamfees = $("#totalexamfees").val();
    var totalamtrecieved = $("#totalamtrecieved").val();
    var totalbalance = 0;
    totalbalance = parseFloat(totalexamfees) - parseFloat(totalamtrecieved);
    $("#totalamtbalance").val(totalbalance.toFixed(2));
  }
</script>
<?php ob_end_flush(); ?>
<?php ob_flush(); ?>

<!-- ANGULAR -->
<script type="text/javascript">
  $(document).on('focus', '.select2', function(e) {
    if (e.originalEvent) {
      $(this).siblings('select').select2('open');
    }
  });

  $(function() {
    // open edit familydetails  modal
    //alert("id");
    $(".pay").click(function() {

      $("#institute_id").val($(this).data('id'));
      $("#paymentid").val($(this).data('paymentid'));
      $("#amc").val($(this).data('amc'));
      $("#paymentmode").val($(this).data('paymentmode'));
      $("#amount").val($(this).data('comission'));

      /*   var id             = $(this).data('id'); 
         var payment_id     = $(this).data('paymentid');
         var amc            = $(this).data('amc'); 
         var payment_mode    = $(this).data('paymentmode');
         var comission      = $(this).data('comission');
         
         $("#paydetailsfrm #paymentid").val(payment_id);
         $("#paydetailsfrm .amount").val(comission);
         $("#paydetailsfrm #institute_id").val(id);
         $("#paydetailsfrm #amc").val(amc);
         $("#paydetailsfrm #paymentmode").val(payment_mode);*/

    })
  });

  $('#paydetailsfrm').on('submit', function(e) {
    $('.loader-mg-modal').show();
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: '/admin/include/classes/ajax.php',
      data: $('#paydetailsfrm').serialize(),
      success: function(data) {
        alert(data);
        $('.loader-mg-modal').hide();
        var data = JSON.parse(data);
        var success = data.success;
        if (!success) {
          $('.loader-mg-modal').hide();
          var message = data.errors.message;
          if (message != 'undefined' && message != '') {
            $("#msg-error").addClass('has-error');
            $("#msg-error .help-block").html(message);
          }
        } else {
          alert(data.message);
          $(".amc_payment_modal").modal('hide');
          location.reload();
        }
      }
    });
  });
</script>
<!-- For Rating Star Script -->
<script type="text/javascript">
  $(document).ready(function() {
    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function() {
      var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

      // Now highlight all the stars that's not after the current hovered star
      $(this).parent().children('li.star').each(function(e) {
        if (e < onStar) {
          $(this).addClass('hover');
        } else {
          $(this).removeClass('hover');
        }
      });

    }).on('mouseout', function() {
      $(this).parent().children('li.star').each(function(e) {
        $(this).removeClass('hover');
      });
    });


    /* 2. Action to perform on click */
    $('#stars li').on('click', function() {
      var onStar = parseInt($(this).data('value'), 10); // The star currently selected
      var stars = $(this).parent().children('li.star');
      $("#ticketRating").val(onStar);
      for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('selected');
      }

      for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass('selected');
      }

      // JUST RESPONSE (Not needed)
      var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
      var msg = "";
      if (ratingValue > 1) {
        msg = "Thanks! You rated this " + ratingValue + " stars.";
      } else {
        msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
      }
      responseMessage(msg);

    });
  });

  function responseMessage(msg) {
    $('.success-box').fadeIn(200);
    $('.success-box div.text-message').html("<span>" + msg + "</span>");
  }

  $(document).on("click", ".send-rating-details", function() {
    var id = $(this).data('id');
    var rating = $(this).data('rating');
    $("#ticketId").val(id);
    //$("#ticketRating").val(rating);
    //$('#stars li').data('value',rating);
    // var onStar = parseInt($('#stars li').data('value'), rating); 
    var stars = $('#stars li').parent().children('li.star');
    $("#ticketRating").val(rating);


    for (i = 0; i < rating; i++) {
      $(stars[i]).addClass('selected');
    }

  });

  $('#save_ticket_rating_form').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      type: 'post',
      url: '/admin/include/classes/ajax.php',
      data: $('#save_ticket_rating_form').serialize(),
      success: function(data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.success == true) {
          location.reload();
        } else if (data.success == false) {

          $("#message").html(data.message);
          $("#message").show();
        }
        // location.reload();
      }
    });
  });
</script>

<script type="text/javascript">
  function toggle_div_fun(id, status) {
    var divelement = document.getElementById(id);
    if (status == 1) {
      divelement.style.display = 'block';
    } else
      divelement.style.display = 'none';
  }
</script>
<script>
  function dispSubexpense(catId) {
    $.post('/admin/include/classes/ajax.php', {
      action: 'get_subcategory_list',
      cat_id: catId
    }, function(data) {
      $("#subcategory_id").html(data);
    });
  }
</script>