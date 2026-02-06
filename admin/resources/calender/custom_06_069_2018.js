  $(function () {
	 

	$(".loader-img").hide();
    $(".data-tbl").DataTable({
		"lengthMenu": [[100, 200,300,500, -1], [100, 200,300,500, "All"]]
	});  
     $(".select2").select2();	 
  });
  //Date picker
$('.datepicker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
		 orientation: "auto top"
    });

$('.datepicker2').datepicker({
	maxDate: new Date() ,
		format: 'dd-mm-yyyy',
		autoclose: true,
		

    });
    $('#dob,#doj,#expirationdate,#registrationdate,#amountpaidon, #dateto,#datefrom').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: true,
		 orientation: "auto top"
    });
    //Initialize Select2 Elements
    $(".select-city").select2();
    $(".select-state").select2();
   $("#compose-textarea, #detail, #eligibility, #question").wysihtml5();   
	
    	
		/*	
	$(document).ajaxStart(function(){
        pageLoaderOverlay('show');
    });
   
   $(document).ajaxComplete(function(){
       pageLoaderOverlay('hide');
    });
	*/
/* display page loader overlay */
function pageLoaderOverlay(param){if(param=='show'){$.LoadingOverlay("show", {image:"",fontawesome : "fa fa-spinner fa-spin"});	}else if(param=='hide'){$.LoadingOverlay("hide");}}

  function readURL(input) {
	  if (input.files && input.files[0]) {
		  var reader = new FileReader();
		  reader.onload = function (e) {	
		  $('#img_preview').attr('src', e.target.result);	
		  }
		  reader.readAsDataURL(input.files[0]);
		  }
		 }
  
   function readImg(input) {
	   if (input.files && input.files[0]) {
	   var reader = new FileReader();reader.onload = function (e) { 
	   var elementId = $(input).attr('id');    $('#'+elementId+'_preview').attr('src', e.target.result);   } 
	   reader.readAsDataURL(input.files[0]);
	   }
	  }
function smallLoader(element,status){ var html = ''; if(status==1) html = '<img src="resources/dist/img/loader_small.gif" />'; $('#'+element).html(html);} 

$("#staff_photo, #queimg").change(function(){readURL(this);});
$("#staff_photo_id, #staff_photoid").change(function(){readImg(this);});

//bulk select
$('#selectall').click(function(event) {
  if(this.checked) {
      $(':checkbox').each(function() {
          this.checked = true;
      });
  }
  else {
    $(':checkbox').each(function() {
          this.checked = false;
      });
  }
});

$('.selectall_cert').click(function(event) {
	var selectId = $(this).attr('id');	
	if(this.checked) {
	 $('.'+selectId).each(function() {
		   this.checked = true;
        });
	}else{
		$('.'+selectId).each(function() {          
		   this.checked = false;
        });
	}
});

$( "#add-enquiry-form" ).click(function() {
  $( "#enquiry-form" ).toggle();
});

$( "#add-enquiry-form" ).click(function() {
  $( "#enquiry-form" ).toggle();
});
/* ------------------------- insitute staff -------------------------------------- */

/* admin --->manage institute */
function getCitiesByState(stateId){ $.post('include/Controllers/ajax.php',{action:'get_city_list', state_id:stateId}, function(data){ $("#city").html(data);}); }
//bulk delete non aicpe courses
function bulkDeleteNonAicpeCourse()
{
 var checkValuesArr = $('input[name=check_course]:checked').map(function(){ return $(this).val();}).get();
 checkValues =  JSON.stringify(checkValuesArr);   
 if(checkValuesArr.length > 0){  
 var conf = confirm("Are you sure?");
 if(conf)
 {
    $.post('include/Controllers/ajax.php',{ action:'bulk_delete_nonaicpe_courses', course_id:checkValues}, function(data){ console.log(data);
      for(var i=0; i<checkValuesArr.length; i++)
      {
        $("#course-id"+checkValuesArr[i]).hide();
        console.log(checkValuesArr[i]);
      }
      alert("Courses deleted successfully.");
    }) ; 
  }
   }else{
    alert("Please select courses to delete.");
   }
}
function deleteInstStaff(staffId){ var conf = confirm('Delete this staff member?'); if(conf==true){ $.post('include/Controllers/ajax.php',{action:'delete_inst_staff',inst_staff_id:staffId }, function(data){$("#row-"+staffId).hide();});} };

function changePass(loginId, email){ var conf = confirm('Do you really want to change the password?'); if(conf==true){ pageLoaderOverlay('show'); $.post('include/Controllers/ajax.php',{action:'change_pass',login_id:loginId, email:email }, function(data){ console.log(data); /*$("#row-"+staffId).hide();*/ pageLoaderOverlay('hide'); alert(data); });} };
function generatePass()
{
	 $.post('include/Controllers/ajax.php',{action:'generate_pass'}, function(data){ console.log(data); $("#pword, #confpword").val(data); $("#show_pword").html(data); });
}
function printPayReciept(payId)
{
	var protocol = window.location.protocol ;
	var url = window.location.host;
	var path = window.location.pathname;
	 path = path.replace('page.php', "");
	 path = path+'/include/plugins/tcpdf/examples/print_reciept2.php?payid='+payId;
	 
//	alert(protocol+"//"+url+""+path);
	
window.open(
 path,'_new' 
);
}

function getUserListByRole(userRole)
{
	$.ajax({
		type:'post',
		url:'include/Controllers/ajax.php',
		data:{action:'get_user_list_by_role', user_role:userRole},
		success:function(data)
		{
			$("#userlist").html(data);
			console.log(data);
		}
	});
}

function forgotPassSMS(userId, userType){
	var conf = confirm('Do you want to send Username & Password?'); if(conf==true){ $.post('include/Controllers/ajax.php',{action:'forget_pass_sms',userId:userId,userType:userType }, function(data){ alert("Sent successfully!")});} 
}
function uploadDocsSMS(userId, userType){
	var conf = confirm('Do you want to send Reminder SMS for uploading documents?'); if(conf==true){ $.post('include/Controllers/ajax.php',{action:'upload_doc_sms',userId:userId,userType:userType }, function(data){ alert("Sent successfully!")});} 
}
  function sendBdaySMS(){ var conf = confirm('Do you want to send Birthday SMS?'); if(conf==true){ $.post('include/Controllers/ajax.php',{action:'send_bday_sms'}, function(data){ console.log(data); alert("Success! Birthday SMS has been sent successfully!"); });} }
 

 /* admin --->manage institute */
function getSemestersByCourse(courseId){ 
	$.post('include/Controllers/ajax.php', {action:'get_semester_by_course', course_id:courseId}, function(data){console.log(data); $("#semester").html(data);}); }

function getSubjectsBySemester(semesterId){ 
	var courseId = $('#course').val();
	$.post('include/Controllers/ajax.php', {action:'get_subject_by_semester', course_id:courseId,semester_id:semesterId}, function(data){console.log(data); $("#subject").html(data);}); }

/* admin --->manage attendence */
function getFacultyAppointments(facultyId){ $.post('include/Controllers/ajax.php',{action:'get_faculty_appointments', facultyId:facultyId}, function(data){ console.log(data); $("#appointment").html(data);}); }

function getAppointmentsAttendenceMonths(appointmentId){ $.post('include/Controllers/ajax.php',{action:'get_appointment_attendence_months', appointmentId:appointmentId}, function(data){ console.log(data); $("#month").html(data);}); }


function getAttendence(facultyId='', appointmentId='', fromDate='', toDate=''){ var output =''; $.post('include/Controllers/ajax.php',{action:'get_attendence', appointmentId:appointmentId,faculty_id:facultyId,fromDate:fromDate,toDate:toDate}, function(data){output =JSON.parse(data); }); return output ;}







