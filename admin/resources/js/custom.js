$(document).ready(function () {
	//change selectboxes to selectize mode to be searchable
	$(".select2").select2();
});

//count of subject in multi sub course
function validateordercount() {
	var val = [];
	$(':checkbox:checked').each(function (i) {
		val[i] = $(this).val();
	});
	if (val.length >= 1 && val.length <= 10) {
		//alert("OK");
		$("#ordercert").attr('disabled', false);
	} else {
		$("#ordercert").attr('disabled', true);
		alert("Please Select Only 10 Certificate To Order");
	}
}

function deleteNews(Id) { var conf = confirm('Delete this News ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_news', id: Id }, function (data) { $("#id" + Id).hide(); }); } };


function deleteTeacher(Id) { var conf = confirm('Delete this Teacher ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_teacher', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

function deleteRechargeRequest(Id) { var conf = confirm('Delete this Recharge Offer ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_rechargerequest', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

function deleteRechargeOffers(Id) { var conf = confirm('Delete this Recharge Offer ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_recharge_offer', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

function getCourseCondition(stateId) { $.post('include/classes/ajax.php', { action: 'get_course_condition', state_id: stateId }, function (data) { $("#coursename").html(data); }); }


function deleteFranchiseEnquiry(Id) { var conf = confirm('Delete this Franchise Enquiry ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_franchise_enquiry', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

//seminar 
function deleteProduct(Id) { var conf = confirm('Delete this Product ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_product', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

function deleteSeminar(Id) { var conf = confirm('Delete this Seminar ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_seminar', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

function deleteSeminarStudent(Id) { var conf = confirm('Delete this Seminar Student?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_seminar_student', id: Id }, function (data) { $("#id" + Id).hide(); }); } };

//institute
function deleteInstitueFile(fileId, instId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_institute_file', file_id: fileId, inst_id: instId }, function (data) { $("#file-area" + fileId).hide(); }); } };


//delete Data Functions
//delete festival
function deleteFestivalFile(fileId, courseId) { var conf = confirm('Delete this festival image?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_festival_file', file_id: fileId, course_id: courseId }, function (data) { $("#file-area" + fileId).hide(); }); } };

function deleteFestival(courseId) { var conf = confirm('Delete this festival?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_festival', course_id: courseId }, function (data) { $("#id" + courseId).hide(); }); } };


//deleteCourseFile
function deleteCourseFile(fileId, courseId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_course_file', file_id: fileId, course_id: courseId }, function (data) { $("#file-area" + fileId).hide(); }); } };

//deleteCourseVideo
function deleteCourseVideos(fileId, courseId) { var conf = confirm('Delete this video?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_course_video', file_id: fileId, course_id: courseId }, function (data) { $("#videos" + fileId).hide(); }); } };

//deleteCourseFile
function deleteCourseMultiSubFile(fileId, courseId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_course_multi_sub_file', file_id: fileId, course_id: courseId }, function (data) { $("#file-area" + fileId).hide(); }); } };

//deleteCourseVideo
function deleteCourseMultiSubVideo(fileId, courseId) { var conf = confirm('Delete this video?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_course_multi_sub_video', file_id: fileId, course_id: courseId }, function (data) { $("#videos" + fileId).hide(); }); } };


function deleteBatches(batchId) { var conf = confirm('Delete this Batch ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_batch', id: batchId }, function (data) { $("#id" + batchId).hide(); }); } };

function deleteAward(awardId) { var conf = confirm('Delete this Award ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_award', id: awardId }, function (data) { $("#id" + awardId).hide(); }); } };

function deleteSlider(examId) { var conf = confirm('Delete this Slider ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_slider', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteTestimonial(examId) { var conf = confirm('Delete this Testimonial ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_testimonial', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteSocialLink(examId) { var conf = confirm('Delete this Social Link ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_socialinks', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteAdvertise(examId) { var conf = confirm('Delete this Advertise ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_advertise', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteCourse(examId) { var conf = confirm('Delete this Course ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_courses', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteServices(examId) { var conf = confirm('Delete this Services ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_services', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteAffiliations(examId) { var conf = confirm('Delete this Affiliations ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_affiliations', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteAchievers(examId) { var conf = confirm('Delete this Achievers ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_achievers', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteTeam(examId) { var conf = confirm('Delete this Team ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_team', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteGalleryImages(examId) { var conf = confirm('Delete this Gallery Images ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_galleryImages', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deletegalleryVideos(examId) { var conf = confirm('Delete this Gallery Videos ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_galleryVideos', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deletejobupdate(examId) { var conf = confirm('Delete this job ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_jobupdate', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteVerification(examId) { var conf = confirm('Delete this Verification ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_verification', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteBlogs(examId) { var conf = confirm('Delete this Blog ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_blog', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deletePartner(examId) { var conf = confirm('Delete this Partner ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_partner', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deletePayment(examId) { var conf = confirm('Delete this Payment ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_payment', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteDownload(examId) { var conf = confirm('Delete this Download Material ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_download_material', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteSampleCert(examId) { var conf = confirm('Delete this Sample Certificate ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_sample_certificate', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

function deleteOnlineClasses(classId) { var conf = confirm('Delete this Online Class Link ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_onlineclasses_details', id: classId }, function (data) { $("#id" + classId).hide(); }); } };

function deleteOldCertificate(id) { var conf = confirm('Delete this Certificate ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_old_certificate', id: id }, function (data) { $("#id" + id).hide(); }); } };

function deleteStudentAdmission(id) { var i = 0; for (i = 0; i < 2; i++) { alert("Delete this student admission"); }; var conf = confirm('Delete this student admission ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student', id: id }, function (data) { $("#id" + id).hide(); }); } };

function deleteupdateAdvertisement(examId) { var conf = confirm('Delete this Advertise ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_advertise_updated', id: examId }, function (data) { $("#id" + examId).hide(); }); } };

//multi subject delete
function deleteSubjectMultiSub(subjectId, courseId) { var conf = confirm('Delete this subject ?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_multi_subject', subjectid: subjectId, courseid: courseId }, function (data) { $("#id" + subjectId).hide(); }); } };

// admin-> manage gallery
function deleteGallery(galleryId) { var conf = confirm('Delete this gallery?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_gallery', gallery_id: galleryId }, function (data) { $("#row-" + galleryId).hide(); }); } };

function deleteGalleryFile(galleryFileId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_gallery_file', gallery_file_id: galleryFileId }, function (data) { $("#gallery-file-id-" + galleryFileId).hide(); }); } };

// set course name by course id
function setCourseName(courseId) { $.post('include/classes/ajax.php', { action: 'set_course_name_by_course_id', course_id: courseId }, function (data) { console.log(data); $("#examname").val(data); }); }

//set marks per questions
function setMarkPerQue() {
	var marksPerQue = 0;
	var totalMarks = parseInt($("#totalmarks").val());
	var totalque = parseInt($("#totalque").val());
	if (totalMarks != 'NaN' && totalque != 'NaN')
		marksPerQue = totalMarks / totalque;
	if (isNaN(marksPerQue) || marksPerQue == 'Infinity') marksPerQue = 0;
	$("#markperque").val(marksPerQue);
}

// admin-> manage question bank--> question  
function deleteQuestion(queId) { var conf = confirm('Delete this institute?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_question', question_id: queId }, function (data) { $("#row-" + queId).hide(); }); } };

//chage question bank active status
function changeQueBankStatus(queBank, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_quebank_status', quebank_id: queBank, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + queBank).html('<a href="javascript:void(0)" onclick="changeQueBankStatus(' + queBank + ',1)"><small class="label bg-red">In-Active</small></a>');
			else if (flag == 1)
				$("#status-" + queBank).html('<a href="javascript:void(0)" onclick="changeQueBankStatus(' + queBank + ',0)"><small class="label bg-green">Active</small></a>');

		});
	}
}

// view question bank
function changeQueBank(queBank) { if (queBank != '') location.href = "page.php?page=viewQueBank&id=" + queBank; }
function changeCourseQueBank(course) { if (course != '') location.href = "page.php?page=viewQueBank&course=" + course; }


$(".send-email-inst").click(function () { // Click to only happen on announce links
	$("#inst_email").val($(this).data('email'));
	$("#inst_id").val($(this).data('id'));
});
/* send emai to the institute */
$('#send_email_form').on('submit', function (e) {
	$('.loader-mg-modal').show(); e.preventDefault();
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: $('#send_email_form').serialize(),
		success: function (data) {
			var data = JSON.parse(data); var success = data.success;
			if (!success) {
				$('.loader-mg-modal').hide();
				var message = data.errors.message;
				var email = data.errors.email;
				if (message != 'undefined' && message != '') {
					$("#msg-error").addClass('has-error'); $("#msg-error .help-block").html(message);
				}
			} else {
				$(".bs-example-modal-md").hide(); $('.loader-mg-modal').hide(); location.reload();
			}
		}
	});
});
//Institutes

//Courses-->delete institute DITRP course

function deleteInstCourse(instCourseId) { var conf = confirm('Delete this course?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_inst_course', inst_course_id: instCourseId }, function (data) { $("#row-" + instCourseId).hide(); }); } };

function deleteInstCourseTyping(instCourseId) { var conf = confirm('Delete this course?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_inst_course', inst_course_id: instCourseId }, function (data) { $("#row-" + instCourseId).hide(); }); } };

function bulkDeleteInstCourse() {
	var checkValuesArr = $('input[name=check_course]:checked').map(function () { return $(this).val(); }).get();
	checkValues = JSON.stringify(checkValuesArr);
	if (checkValuesArr.length > 0) {
		var conf = confirm("Are you sure?");
		if (conf) {
			$.post('include/classes/ajax.php', { action: 'bulk_delete_inst_courses', inst_course_id: checkValues }, function (data) {
				console.log(data);
				for (var i = 0; i < checkValuesArr.length; i++) {
					$("#row-" + checkValuesArr[i]).hide();
					console.log(checkValuesArr[i]);
				}
				alert("Courses deleted successfully.");
			});
		}
	} else {
		alert("Please select courses to delete.");
	}
}

//change course status flag
function changeInstCourseStatus(instCourseId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_inst_course_status', inst_course_id: instCourseId, flag: flag }, function (data) {

			if (flag == 0)
				$("#status-" + instCourseId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatus(' + instCourseId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#status-" + instCourseId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatus(' + instCourseId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
};

//change non DITRP course status flag
function changeCourseStatus(courseId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_nonaicpe_course_status', course_id: courseId, flag: flag }, function (data) {
			console.log(data);
			if (flag == 0)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeCourseStatus(' + courseId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCourseStatus(' + courseId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
};

// delete non ditrp course
function deleteNonCourse(courseId) { var conf = confirm('Delete this course?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_nonaicpe_course', course_id: courseId }, function (data) { $("#course-id" + courseId).hide(); }); } };


//bulk delete non ditrp courses
function bulkDeleteNonCourse() {
	var checkValuesArr = $('input[name=check_course]:checked').map(function () { return $(this).val(); }).get();
	checkValues = JSON.stringify(checkValuesArr);
	if (checkValuesArr.length > 0) {
		var conf = confirm("Are you sure?");
		if (conf) {
			$.post('include/classes/ajax.php', { action: 'bulk_delete_non_courses', course_id: checkValues }, function (data) {
				console.log(data);
				for (var i = 0; i < checkValuesArr.length; i++) {
					$("#course-id" + checkValuesArr[i]).hide();
					console.log(checkValuesArr[i]);
				}
				alert("Courses deleted successfully.");
			});
		}
	} else {
		alert("Please select courses to delete.");
	}
}

//change course status flag
function changeInstStaffStatus(staffId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_inst_staff_status', staff_id: staffId, flag: flag }, function (data) {
			console.log(data);
			if (flag == 0)
				$("#status-" + staffId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStaffStatus(' + staffId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#status-" + staffId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStaffStatus(' + staffId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
};

function deleteInstStaff(staffId) { var conf = confirm('Delete this staff member?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_inst_staff', inst_staff_id: staffId }, function (data) { $("#row-" + staffId).hide(); }); } };

function changePass(loginId, email) { var conf = confirm('Do you really want to change the password?'); if (conf == true) { pageLoaderOverlay('show'); $.post('include/classes/ajax.php', { action: 'change_pass', login_id: loginId, email: email }, function (data) { console.log(data); /*$("#row-"+staffId).hide();*/ pageLoaderOverlay('hide'); alert(data); }); } };
//change gallery status flag
function changeGalleryStatus(galleryId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_gallery_status', gallery_id: galleryId, flag: flag }, function (data) {
			console.log(data);
			if (flag == 0)
				$("#status-" + galleryId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeGalleryStatus(' + galleryId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#status-" + galleryId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeGalleryStatus(' + galleryId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
};
// student--->add student
function generatePass() {
	$.post('include/classes/ajax.php', { action: 'generate_pass' }, function (data) { console.log(data); $("#pword, #confpword").val(data); $("#show_pword").html(data); });
}
// get institute courses by course type
function getInstituteCourses(courseType) { $.post('include/classes/ajax.php', { action: 'get_institute_courses', course_type: courseType }, function (data) { $("#course").html(data); }); }

// institute----> student --->enquiry
//delete student
function deleteStudentEnquiry(enqId) { var conf = confirm('Delete this enquiry?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student_enquiry', enq_id: enqId }, function (data) { console.log(data); $("#row-" + enqId).hide(); }); } };

// institute----> student
function deleteStudFile(fileId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_stud_file', stud_file_id: fileId }, function (data) { console.log(data); $("#img-" + fileId).hide(); }); } };

// get student courses by student id
function getStudentCourses(studId) { $.post('include/classes/ajax.php', { action: 'get_student_courses', stud_id: studId }, function (data) { $("#course").html(data); }); }

// get student courses by student id
function getStudentAllCourses(studId) { $.post('include/classes/ajax.php', { action: 'get_student_allcourses', stud_id: studId }, function (data) { $("#course").html(data); }); }

//delete expenses
function deleteExpenses(enqId) { var conf = confirm('Delete this expense?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_expense', enq_id: enqId }, function (data) { console.log(data); $("#row-" + enqId).hide(); }); } };

function deleteExpensesCat(enqId) { var conf = confirm('Delete this expense category?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_expense_category', enq_id: enqId }, function (data) { console.log(data); $("#row-" + enqId).hide(); }); } };

function deleteExpensesSubCategory(enqId) { var conf = confirm('Delete this expense subcategory?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_expense_subcategory', enq_id: enqId }, function (data) { console.log(data); $("#row-" + enqId).hide(); }); } };

//change student status
function changeStudentStatus(studentId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_student_status', student_id: studentId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + studentId).html('<a href="javascript:void(0)" style="color:#f00;font-size: 14px; font-weight: 900;" onclick="changeStudentStatus(' + studentId + ',1)"><i class="mdi mdi-account-off"></i> In-Active</a>');
			else if (flag == 1)
				$("#status-" + studentId).html('<a href="javascript:void(0)" style="color:#3c763d;font-size: 14px;font-weight: 900;"  onclick="changeStudentStatus(' + studentId + ',0)"><i class="mdi mdi-account-check"></i></i> Active</a>');
		});
	}
};

//delete student
function deleteStudent(studId) { var conf = confirm('Delete this staff member?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student', stud_id: studId }, function (data) { console.log(data); $("#row-" + studId).hide(); }); } };

function getStudCourseDetails(studId) {
	$.post('include/classes/ajax.php', { action: 'get_stud_course_details', stud_id: studId }, function (data) { $("#course-info").html(data); });
}

$(".show-stud-course-info").click(function () { //$(".course-info-studname").html($(this).data('name'));
	var studid = $(this).data('id');
	var studname = $(this).data('name');
	var studemail = $(this).data('email');
	$(".course-info-studname").html(studname);
	$("#add_stud_course_info_form #stud_id").val(studid);
	$(".add-stud-course-info").attr('data-id', studid);
	$(".add-stud-course-info").attr('data-name', studname);
	$(".add-stud-course-info").attr('data-email', studemail);
	getStudCourseDetails(studid);
});

$(".add-stud-course-info").click(function () {
	$(".show-stud-course-details").modal('hide');
});
/* add course to student */
$('#add_stud_course_info_form').on('submit', function (e) {
	$('.loader-mg-modal').show(); e.preventDefault();
	var error = false;
	if ($("#course_type").val() == '') {
		error = true;
		$(".course-type-error").addClass('has-error'); $(".course-type-error .help-block").html('Required fields!');
	}
	if ($("#course").val() == '') {
		error = true;
		$(".course-error").addClass('has-error'); $(".course-error .help-block").html('Required fields!');
	}

	if (error == false) {
		$.ajax({
			type: 'post',
			url: 'include/classes/ajax.php',
			data: $('#add_stud_course_info_form').serialize(),
			success: function (data) {
				var data = JSON.parse(data);
				var success = data.success;
				if (!success) {
					$('.loader-mg-modal').hide();
					var course = data.errors.course;
					var course_type = data.errors.course_type;
					if (course != 'undefined' && course != '') {
						$("#msg-error").addClass('has-error'); $("#msg-error .help-block").html(course);
					}
					if (course_type != 'undefined' && course_type != '') {
						$("#msg-error").addClass('has-error'); $("#msg-error .help-block").html(course_type);
					}
				} else {
					$(".add-stud-course-details").modal('hide'); $('.loader-mg-modal').hide(); location.reload();
				}
			}
		});
	}
	$('.loader-mg-modal').hide();
});

//insititute -->student-->payments
//dipsplay course info by course id
function dispCoursePaymentInfo(courseId) {
	var studId = $("#student_id").val(); $.post('include/classes/ajax.php', { action: 'get_course_details', course_id: courseId, stud_id: studId }, function (data) {
		//console.log(data);
		var data = JSON.parse(data);
		var courseId = data.courseId;
		var examFees = data.examFees;
		var courseName = data.courseName;
		var courseDuration = data.courseDuration;
		var html = ''
		html += '<table class="table">';
		html += '<tr><th>Course Name</th>';
		html += '<td>' + courseName + '</td></tr>';
		html += '<tr><th>Course Duration</th>';
		html += '<td>' + courseDuration + '</td></tr>';
		html += '<tr><th>Exam Fees</th>';
		html += '<td>' + examFees + '</td></tr>';
		$("#totalexamfees").val(examFees);
		$("#course-info").html(html);
	});

}

//dipsplay course info by course id

//delete student payment
function deleteStudentPayment(payId) { var conf = confirm('Delete this payment detail?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student_payment', payment_id: payId }, function (data) { console.log(data); $("#row-" + payId).hide(); }); } };

// institute--->student--->exams-->change exam type
function changeStudentExamType(courrseDetailId, flag) {
	var conf = confirm('Are you sure? \r\n Do you really want to change the student exam type?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_student_exam_type', course_detail_id: courrseDetailId, flag: flag }, function (data) {
			var examtype_class = '';
			switch (flag) {
				case ('1'): examtype_class = 'btn-success'; break;
				case ('2'): examtype_class = 'btn-danger'; break;
				case ('3'): examtype_class = 'btn-warning'; break;
				default: examtype_class = 'btn-primary'; break;
			}
			var html = '<select class="btn btn-xs ' + examtype_class + '" name="changeexamtype" onchange="changeStudentExamType(' + courrseDetailId + ', this.value)" id="changeexamtype' + courrseDetailId + '">';
			html += data;
			html += '</select>';
			$("#exam-type-" + courrseDetailId).html(html);
			if (flag == '') {
				changeStudentExamStatus(courrseDetailId, '');
				$("#changeexamstatus" + courrseDetailId).prop("disabled", true);
			} else {
				$("#changeexamstatus" + courrseDetailId).prop("disabled", false);

			}
		});
	}
};
// institute--->student--->exams-->change exam status
function changeStudentExamStatus(courrseDetailId, flag) {
	var conf = confirm('Are you sure? \r\n Do you really want to change student exam status?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_student_exam_status', course_detail_id: courrseDetailId, flag: flag }, function (data) {
			//console.log(data);
			var examstatus_class = '';
			switch (flag) {
				case ('1'): examstatus_class = 'btn-warning'; break;
				case ('2'): examstatus_class = 'btn-success'; break;
				case ('3'): examstatus_class = 'btn-info'; break;
				default: examstatus_class = 'btn-primary'; break;
			}
			var html = '<select class="btn btn-xs ' + examstatus_class + '" name="changeexamstatus" onchange="changeStudentExamStatus(' + courrseDetailId + ', this.value)" id="changeexamstatus' + courrseDetailId + '">';
			html += data;
			html += '</select>';
			$("#exam-status-" + courrseDetailId).html(html);
			validateExamApply(courrseDetailId);
		});
	}
};

//delete student exam details
function deleteStudentExamDetail(courseDetailId) { var conf = confirm('Delete this exam detail?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student_exam_detail', course_detail_id: courseDetailId }, function (data) { $("#row-" + courseDetailId).hide(); }); } };

function toggleEditBox(element) {
	var elementid = element.substr(9);
	$("#editbox_" + elementid).toggle();
}
function changeInstCourseFees(instCourseId) {
	var conf = confirm('Are you sure? Update the course fees?'); if (conf == true) {
		var inst_course_id = instCourseId.substr(12);
		var course_fees = $("#coursefees_" + inst_course_id).val();
		course_fees = parseFloat(course_fees).toFixed(2);
		$.post('include/classes/ajax.php', { action: 'chage_inst_course_fees', inst_course_id: inst_course_id, course_fees: course_fees }, function (data) {
			console.log(data);
			//var html = course_fees+"<a href='javascript:void(0)' onclick='toggleEditBox(this.id)' class='pull-right' id='editfees_"+inst_course_id+"'><i class='fa fa-pencil'></i></a>";
			$("#editbox_" + inst_course_id).hide();
			$("#dis_fee_" + inst_course_id).html(course_fees);
			//$("#inst_course_fees_td_"+inst_course_id).html(html);
		});
	}
};

function setInstCourseInfo(instId, instCourseId) {
	$.post('include/classes/ajax.php', { action: 'get_inst_course_info', inst_id: instId, inst_course_id: instCourseId }, function (data) {
		var output = JSON.stringify(data);
		var data = JSON.parse(data);
		var courseFee = data.inst_course_fee;
		var courseName = data.course_name;
		var courseType = data.course_type;
		var amtpaid = $("#amtpaid").val();
		if (amtpaid == '') amtpaid = 0;
		amtpaid = parseFloat(amtpaid);
		if (amtpaid > parseFloat(courseFee)) {
			$(".amtpaid").addClass('has-error');
			$(".amtpaid .help-block").html('Amount to be paid must be less than total course fees.');
		} else {
			var balance = parseFloat(courseFee) - parseFloat(amtpaid);
			//var courseDuration = data.courseDuration;
			var html = '<table class="table table-bordered"><tr><th>Selected Course Name</th><td>' + courseName + '</td></tr><tr><th>Total Course Fees</th><td>' + courseFee + '</td></tr><tr><th>Amount Paid</th><td>' + amtpaid + '</td></tr><tr class="danger"><th>Amount Balance</th><td>' + balance.toFixed(2) + '</td></tr></table>';

			$("#disp_course_name").val(courseName);
			$("#disp_course_type").val(courseType);
			$("#disp_course_fees").val(parseFloat(courseFee));
			$("#disp_amtbalance").val(parseFloat(balance));
			$("#payment-details").html(html);
		}
	});
}
function calBalAmt(instId, paidamt) {
	$(".amtpaid").removeClass('has-error'); $(".amtpaid .help-block").html('');
	var course = document.getElementById("course");
	if (course.selectedIndex == 0) {
		$("#amtpaid").val('');
		alert('Sorry! Please select the course first.');
		$("#course").focus();
	} else {
		var instCourseId = $("#course").val();

		var RE = /^\d*\.?\d{0,2}$/;
		if (RE.test(paidamt)) {
			setInstCourseInfo(instId, instCourseId);
		} else {
			$(".amtpaid").addClass('has-error');
			$(".amtpaid .help-block").html('Please enter valid amount!');
		}

	}
}

function addCourseRow() {
	var lastRowIndex = parseInt($("#countcourses").val()) + 1;
	$.post('include/classes/ajax.php', { action: 'add_new_course_row', lastrowindex: lastRowIndex }, function (data) { //console.log(data);
		$("#countcourses").val(parseInt(lastRowIndex));
		$("#courses-rows").append(data);
	});
}
function deleteCourseRow(rowIndex) {
	$("#courserow-" + rowIndex).remove();
	var lastRowIndex = $("#countcourses").val();
	$("#countcourses").val(lastRowIndex - 1)
}
function getInstCourseFees(instCourseId, elementid) {
	var eleid = elementid.substr(6);
	//alert(eleid);
	$.post('include/classes/ajax.php', { action: 'get_inst_course_fees', inst_course_id: instCourseId }, function (data) { //console.log(data);
		$("#coursefees" + eleid).val(data);
		calDiscountedAmt(eleid);
	});
}
function calDiscountedAmt(elementId) {
	var totalFees = 0;
	var discAmt = $("#discamt").val();
	var discRate = $("#discrate").val();
	var courseFee = $("#coursefees").val();
	var amtrecieved = $("#amtrecieved").val(0);

	if (discAmt == 'NaN' || discAmt == 0 || discAmt == '')
		totalFees = parseFloat(courseFee).toFixed(2);
	else {
		discAmt = parseFloat(discAmt);
		switch (discRate) {
			case ('amtminus'):
				totalFees = parseFloat(courseFee) - parseFloat(discAmt);
				break;
			case ('amtplus'):
				totalFees = parseFloat(courseFee) + parseFloat(discAmt);
				break;
			case ('perminus'):
				totalFees = parseFloat(courseFee) - ((parseFloat(courseFee) * parseFloat(discAmt)) / 100);
				break;
			case ('perplus'):
				totalFees = parseFloat(courseFee) + ((parseFloat(courseFee) * parseFloat(discAmt)) / 100);
				break;
		}
	}

	totalFees = parseFloat(totalFees).toFixed(2);
	$("#totalcoursefee").val(totalFees);
	if (amtrecieved != '' && !isNaN(amtrecieved))
		totalFees = parseFloat(totalFees) - parseFloat(amtrecieved);
	$("#amtbalance").val(parseFloat(totalFees).toFixed(2));

	//console.log("Total Fees: "+totalFees);
}
function calDiscountedAmtUpd(elementId) {
	var totalFees = 0;
	var totalPaid = $("#total_paid").val();
	if (totalPaid == 'NaN' || totalPaid == '' || isNaN(totalPaid))
		totalPaid = 0;
	var discAmt = $("#discamt" + elementId).val();
	var discRate = $("#discrate" + elementId).val();
	var courseFee = $("#coursefees" + elementId).val();
	var amtrecieved = $("#amtrecieved" + elementId).val();
	/*console.log("Element ID: "+elementId);	
	console.log("Course Fees: "+courseFee);			
	console.log("Discount Amt: "+discAmt);
	console.log("Discount Rate: "+discRate);	*/
	console.log("Total Paid:" + totalPaid);
	if (totalPaid != 0)
		amtrecieved = parseFloat(amtrecieved) + parseFloat(totalPaid);
	if (discAmt == 'NaN' || discAmt == 0 || discAmt == '')
		totalFees = parseFloat(courseFee).toFixed(2);
	else {
		discAmt = parseFloat(discAmt);
		switch (discRate) {
			case ('amtminus'):
				totalFees = parseFloat(courseFee) - parseFloat(discAmt);
				break;
			case ('amtplus'):
				totalFees = parseFloat(courseFee) + parseFloat(discAmt);
				break;
			case ('perminus'):
				totalFees = parseFloat(courseFee) - ((parseFloat(courseFee) * parseFloat(discAmt)) / 100);
				break;
			case ('perplus'):
				totalFees = parseFloat(courseFee) + ((parseFloat(courseFee) * parseFloat(discAmt)) / 100);
				break;
		}
	}

	totalFees = parseFloat(totalFees).toFixed(2);
	$("#totalcoursefee" + elementId).val(totalFees);
	if (amtrecieved != '' && !isNaN(amtrecieved))
		totalFees = parseFloat(totalFees) - parseFloat(amtrecieved);
	$("#amtbalance" + elementId).val(parseFloat(totalFees).toFixed(2));

	//console.log("Total Fees: "+totalFees);
}
function getInstCourseFeesUpd(instCourseId, elementid) {
	var eleid = elementid.substr(6);
	//alert(eleid);
	$.post('include/classes/ajax.php', { action: 'get_inst_course_fees', inst_course_id: instCourseId }, function (data) { //console.log(data);
		$("#coursefees" + eleid).val(data);
		calDiscountedAmtUpd(eleid);
	});
}
function calTotalPerCourseUpd(elementId) {
	var totalFees = 0;
	var totalPaid = $("#total_paid").val();
	var total_paid1 = $("#total_paid1").val();
	if (totalPaid == 'NaN' || totalPaid == '' || isNaN(totalPaid))
		totalPaid = 0;
	//$("#amtrecieved_err"+elementId).html('');
	var totalcoursefee = $("#totalcoursefee" + elementId).val();
	var amtrecieved = $("#amtrecieved" + elementId).val();
	var totalbal = 0;
	if (amtrecieved == '' || amtrecieved == 'NaN' || amtrecieved == 'undefined') {
		//amtrecieved = parseFloat(amtrecieved) + parseFloat(totalcoursefee);
		amtrecieved = 0;
	}
	if (totalPaid != 0)
		amtrecieved = parseFloat(amtrecieved) + parseFloat(totalPaid);
	if (parseFloat(amtrecieved) <= parseFloat(totalcoursefee)) {
		totalbal = parseFloat(totalcoursefee) - parseFloat(amtrecieved);

	} else {

		$("#amtrecieved" + elementId).val(totalcoursefee);
		//$("#amtrecieved_err"+elementId).html('');
	}

	$("#amtbalance" + elementId).val(parseFloat(totalbal).toFixed(2));
	console.log(totalbal);
	$("#total_paid1").val(amtrecieved);
}
function calTotalPerCourse(elementId) {

	//$("#amtrecieved_err"+elementId).html('');
	var totalcoursefee = $("#totalcoursefee").val();
	var amtrecieved = $("#amtrecieved").val();
	var totalbal = 0;
	if (amtrecieved == '' || amtrecieved == 'NaN' || amtrecieved == 'undefined')
		amtrecieved = parseFloat(totalcoursefee);
	if (parseFloat(amtrecieved) <= parseFloat(totalcoursefee)) {
		totalbal = parseFloat(totalcoursefee) - parseFloat(amtrecieved);

	} else {

		$("#amtrecieved").val(totalcoursefee);
		//$("#amtrecieved_err"+elementId).html('');
	}

	$("#amtbalance").val(parseFloat(totalbal).toFixed(2));
	console.log(totalbal);
}

// Institute--->Student--->Add payment
//get student payment info by stud id or course id
function getStudPaymentInfo() { var courseId = $("#course").val(); var studId = $("#student_id").val(); $.post('include/classes/ajax.php', { action: 'get_stud_payment_info', course_id: courseId, stud_id: studId }, function (data) { $("#payment_info").html(data); }); }




function getBalAmtCourse() {
	var courseId = $("#course").val();
	var studId = $("#student_id").val();
	var fees_balance = '';
	var total_course_fees = '';

	$.post('include/classes/ajax.php', { action: 'get_stud_course_fee_bal', course_id: courseId, stud_id: studId }, function (data) {
		console.log(data);
		//var output = JSON.stringify(data);
		var data = JSON.parse(data);
		//console.log(data);
		total_course_fees = data.total_course_fees;
		fees_balance = data.fees_balance;
		// console.log("total_course_fees: "+ total_course_fees);
		// console.log("fees_balance: "+ fees_balance);
		$("#amountbalance").val(fees_balance);
		$("#totalbal").val(fees_balance);
		$("#totalBAmount").val(fees_balance);
		$("#coursefees").val(total_course_fees);

	});
}
function addPayShowBal1() {
	var courseId = $("#course").val();
	var studId = $("#student_id").val();
	var fees_balance = '';
	var total_course_fees = '';
	var amountpaid = $("#amountpaid").val();
	var feespaid1 = $("#feespaid1").val();

	var a = '';
	var b = '';
	var c = '';


	$.post('include/classes/ajax.php', { action: 'get_stud_course_fee_bal', course_id: courseId, stud_id: studId }, function (data) {
		//console.log(data);
		//var output = JSON.stringify(data);
		var data = JSON.parse(data);

		total_course_fees = data.total_course_fees;

		if (parseFloat(amountpaid) > parseFloat(feespaid1)) {
			a = parseFloat(amountpaid) - parseFloat(feespaid1);
			fees_balance = parseFloat(data.fees_balance) - parseFloat(a);

		} else {
			b = parseFloat(feespaid1) - parseFloat(amountpaid);
			fees_balance = parseFloat(data.fees_balance) + parseFloat(b);
		}

		$("#amountbalance").val(fees_balance);

	});

}
function calBalAmt() {
	var amountpaid = $("#amountpaid").val();
	var amountbalance = $("#amountbalance").val();
	var totalBal = 0;
	if (!isNaN(amountpaid) && amountbalance != '' && amountbalance != 0) {
		totalBal = parseFloat(amountbalance) - parseFloat(amountpaid);
		console.log("Amt Bal: " + amountbalance);
		console.log("Amt Paid: " + amountpaid);
		console.log("Total Bal: " + totalBal);
	}
	if ((amountpaid > totalBal) || (totalBal <= 0) || isNaN(amountbalance)) {
		getBalAmtCourse();
	}
	$("#amountbalance").val(parseFloat(totalBal).toFixed(2));
}
function addPayShowBal() {
	var amountpaid = $("#amountpaid").val();
	var amountbalance = $("#totalbal").val();
	if (amountpaid == '' && isNaN(amountpaid)) {
		amountpaid = 0;

	}
	else {
		if (parseFloat(amountpaid) > parseFloat(amountbalance)) {
			$("#amountpaid").val(amountbalance);
			amountpaid = parseFloat(amountbalance);
		}	//;
		amountbalance = parseFloat(amountbalance) - parseFloat(amountpaid);

	}
	if (isNaN(amountbalance)) amountbalance = $("#totalbal").val();
	$("#amountbalance").val(amountbalance);

}
function calTotalBalAmt() {
	var totalexamfees = $("#totalexamfees").val();
	var totalamtrecieved = $("#totalamtrecieved").val();
	var totalamtbalance = $("#totalamtbalance").val();
	var totalBal = 0;

	if (!isNaN(totalamtrecieved) && totalamtrecieved != '' && totalamtrecieved != 0) {
		totalBal = parseFloat(totalamtbalance) - parseFloat(totalamtrecieved);

	}

	$("#totalamtbalance").val(parseFloat(totalBal).toFixed(2));
}
/*
function getInstCourseFees(instCourseId)
{
	$.post('include/classes/ajax.php',{action:'get_stud_course_fee_bal', course_id:courseId, stud_id:studId}, function(data){
		console.log(data);
		$("#amountbalance").val(data);});
}
*/
//print pay recipet
function printPayReciept(payId) {
	var protocol = window.location.protocol;
	var url = window.location.host;
	var path = window.location.pathname;
	path = path.replace('page.php', "");
	path = path + 'include/plugins/tcpdf/examples/print_reciept2.php?payid=' + payId;

	//	alert(protocol+"//"+url+""+path);

	window.open(
		path, '_new'
	);
}
function togglePaymentInfo(studDetailId) {
	$("#paymentinfo_" + studDetailId).toggle();
}
function downloadFile(fileid) {
	var href = $('#downloadLink' + fileid).val();
	window.location.href = href;
}

// student apply demo exam
$(".send-email-inst").click(function () { // Click to only happen on announce links
	$("#inst_email").val($(this).data('email'));
	$("#inst_id").val($(this).data('id'));
});

// student apply jobs email
$(".apply-job-email").click(function () { // Click to only happen on announce links
	$("#emp_email").val($(this).data('email'));
	$("#subject").val($(this).data('name'));

	$("#job_post_id").val($(this).data('id'));
});

function generateESC(elem) {
	$.post('include/classes/ajax.php', { action: 'generate_esc', elem: elem }, function (data) {
		location.href = 'page.php?page=download-offline-papers';
	});
}
function calOfflineResult() {
	var gotPercent = 0;
	var grade = '';
	var res_stat = '';
	var totalcorrect = $('#totalcorrect').val();
	var totalincorrect = $('#totalincorrect').val();
	var totalque = $('#exam_total_que').val();
	var total_marks = $('#exam_total_marks').val();
	var perMarks = $('#exam_marks_per_que').val();
	totalcorrect = parseInt(totalcorrect);
	totalque = parseInt(totalque);
	if (totalcorrect > totalque) {
		$('#totalcorrect').val(totalque);
		$('#totalincorrect').val('0');
		totalcorrect = totalque;
	}

	var gotMarks = parseFloat(totalcorrect) * parseFloat(perMarks);
	gotMarks = parseInt(Math.round(gotMarks));
	gotPercent = parseFloat((gotMarks * 100) / parseInt(total_marks));


	//alert("totalcorrect:"+totalcorrect+"totalque:"+totalque);
	totalincorrect = parseInt(totalque) - parseInt(totalcorrect);

	if (gotPercent >= 85) {
		grade = "A+ : Excellent";
		res_stat = "Passed";
	}
	else if (gotPercent >= 70 && gotPercent < 85) {
		grade = "A : Very Good";
		res_stat = "Passed";
	}
	else if (gotPercent >= 55 && gotPercent < 70) {
		grade = "B : Good";
		res_stat = "Passed";
	}
	else if (gotPercent >= 40 && gotPercent < 55) {
		grade = "C : Average";
		res_stat = "Passed";
	}
	else {
		grade = "";
		res_stat = "Failed";
	}
	$('#result_status').val(res_stat);
	$('#grade').val(grade);
	$('#marks_per').val(gotPercent);
	$('#marksobt').val(gotMarks);
	$('#totalincorrect').val(totalincorrect);
}
function getpercent() {
	var marksobt = parseInt($("#marksobt").val());
	var marksobtpract = parseInt($("#marksobtpract").val());
	var outofmarks = parseInt($("#exam_total_marks").val());
	var percentage = 0;
	percentage = ((marksobtpract + marksobt) / 100) * 100;
	return percentage.toFixed(2);
}
function calPracticalResult() {

	var gotPercent = getpercent();
	gotPercent = parseFloat(gotPercent);
	if (gotPercent > 100) {
		$('#marks_per').val(100);
		gotPercent = 100;
	}
	if (gotPercent >= 85) {
		grade = "A+";
		res_stat = "Passed";
	}
	else if (gotPercent >= 70 && gotPercent < 85) {
		grade = "A";
		res_stat = "Passed";
	}
	else if (gotPercent >= 55 && gotPercent < 70) {
		grade = "B";
		res_stat = "Passed";
	}
	else if (gotPercent >= 40 && gotPercent < 55) {
		grade = "C";
		res_stat = "Passed";
	}
	else {
		grade = "";
		res_stat = "Failed";
	}
	$("#marks_per").val(gotPercent.toFixed(2));
	$('#result_status').val(res_stat);
	$('#grade').val(grade);
}
// Multi sub percentage and obtain total
function calTotalPerSub(id) {
	var finalObtTotal = 0;
	var finalOutOfTotal = 0;
	var thobt = $("#thobt" + id).val();

	if (thobt == '') { thobt = 0; }
	var probt = $("#probt" + id).val();
	if (probt == '') { probt = 0; }
	obt_marks_sub_total = parseInt(thobt) + parseInt(probt);
	$('#tot_obt' + id).val(obt_marks_sub_total);

	totalSubjectCount = $('#totalSubjectCount').val();
	for (var i = 1; i <= totalSubjectCount; i++) {
		finalObtTotal += ($("#tot_obt" + i).val() != '') ? parseInt($("#tot_obt" + i).val()) : 0;
		finalOutOfTotal += ($("#tot_marks" + i).val() != '') ? parseInt($("#tot_marks" + i).val()) : 0;
	}
	$("#total_obt_marks").val(finalObtTotal);
	$("#total_marks").val(finalOutOfTotal);

	percentage = (parseInt(finalObtTotal) / parseInt(finalOutOfTotal)) * 100;
	percentage = percentage.toFixed(2);
	$("#percentage").val(percentage);

	if (percentage > 100) {
		$('#percentage').val(100);
		percentage = 100;
	}
	if (percentage >= 85) {
		grade_multi = "A+";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 70 && percentage < 85) {
		grade_multi = "A";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 55 && percentage < 70) {
		grade_multi = "B";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 40 && percentage < 55) {
		grade_multi = "C";
		res_stat_multi = "Passed";
	}
	else {
		grade_multi = "";
		res_stat_multi = "Failed";
	}

	$('#result_status_multi').val(res_stat_multi);
	$('#grade_multi').val(grade_multi);
}

//course typing calculation 

function calTotalPerSubTyping(id) {
	var finalObtTotal = 0;
	var finalOutOfTotal = 0;
	var thobt = $("#thobt" + id).val();

	if (thobt == '') { thobt = 0; }

	obt_marks_sub_total = parseInt(thobt);

	$('#thobt' + id).val(obt_marks_sub_total);

	totalSubjectCount = $('#totalSubjectCount').val();
	for (var i = 1; i <= totalSubjectCount; i++) {
		console.log(i);
		finalObtTotal += ($("#thobt" + i).val() != '') ? parseInt($("#thobt" + i).val()) : 0;
		finalOutOfTotal += ($("#totaltheory" + i).val() != '') ? parseInt($("#totaltheory" + i).val()) : 0;
	}
	$("#total_obt_marks").val(finalObtTotal);
	$("#total_marks").val(finalOutOfTotal);

	percentage = (parseInt(finalObtTotal) / parseInt(finalOutOfTotal)) * 100;
	percentage = percentage.toFixed(2);
	$("#percentage").val(percentage);

	if (percentage > 100) {
		$('#percentage').val(100);
		percentage = 100;
	}
	if (percentage >= 85) {
		grade_multi = "A+";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 70 && percentage < 85) {
		grade_multi = "A";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 55 && percentage < 70) {
		grade_multi = "B";
		res_stat_multi = "Passed";
	}
	else if (percentage >= 40 && percentage < 55) {
		grade_multi = "C";
		res_stat_multi = "Passed";
	}
	else {
		grade_multi = "";
		res_stat_multi = "Failed";
	}

	$('#result_status_multi').val(res_stat_multi);
	$('#grade_multi').val(grade_multi);
}

function getpercentMultiSub() {
	var total_obt_marks = parseInt($("#total_obt_marks").val());
	var total_marks = parseInt($("#total_marks").val());
	var percentage = 0;
	percentage = ((total_obt_marks) / (total_marks)) * 100;
	return percentage.toFixed(2);
}
function calTotalSubObtMarks() {
	var total_obt_marks = parseInt($("#total_obt_marks").val());
	var total_marks = parseInt($("#total_marks").val());
	var percentage = 0;
	percentage = ((total_obt_marks) / (total_marks)) * 100;
	return percentage.toFixed(2);
}
//delete student results
function deleteStudentResult(resultId) { var conf = confirm('Delete this exam result detail?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student_exam_result', exam_result_id: resultId }, function (data) { $("#row-" + resultId).hide(); }); } };


//change job post status flag
function changeJobPostStatus(jobpostId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_jobpost_status', job_id: jobpostId, flag: flag }, function (data) {
			console.log(data);
			if (flag == 0)
				$("#status-" + jobpostId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeJobPostStatus(' + jobpostId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#status-" + jobpostId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeJobPostStatus(' + jobpostId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
};

//delete job post
function deleteJobPost(jobpostId) { var conf = confirm('Delete this job post?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_jobpost', job_id: jobpostId }, function (data) { console.log(data); $("#row-" + jobpostId).hide(); }); } };

function getUserListByRole(userRole) {
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: { action: 'get_user_list_by_role', user_role: userRole },
		success: function (data) {
			$("#userlist").html(data);
			console.log(data);
		}
	});
}
// institute-->exams->list exams
/* check if the exam status is applied or not */

function validateExamApply(id) {
	var examStatus = $("#changeexamtype" + id).val();
	console.log(examStatus);
	if (examStatus == '') {
		$("#changeexamstatus" + id).prop("readonly", "readonly");
		$("#changeexamstatus" + id).prop("disabled", true);
	}
}

//delete certificate request
function deleteCertificateRequest(reqId) { var conf = confirm('Delete this certificate request?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_certificate_request', cert_req_id: reqId }, function (data) { $("#irow-" + reqId).hide(); }); } };
//delete all certificate request 
function deleteCertificateRequestAll(reqId, subtable) { var conf = confirm('Delete this certificate request?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_certificate_request_all', cert_req_id: reqId }, function (data) { $("#req-" + reqId).hide(); $("#row-" + subtable).hide(); }); } };

//delete all order certificate
function deleteCertificateRequestAllOrder(reqId, subtable) { var conf = confirm('Delete this order certificate request?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_order_certificate_request_all', cert_req_id: reqId }, function (data) { $("#req-" + reqId).hide(); $("#row-" + subtable).hide(); }); } };

//delete order certificate request
function deleteCertificateRequestOrder(reqId) { var conf = confirm('Delete this Order certificate request?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_order_certificate_request', cert_req_id: reqId }, function (data) { $("#irow-" + reqId).hide(); }); } };


//reset student exam
$(".resetexam").click(function (e) { var conf = confirm('Are you sure? Do you really want to RESET student exam?'); if (conf == true) { var id = $(this).attr('id'); var stud_course_id = id.substr(4); $.post('include/classes/ajax.php', { action: 'reset_student_exam', stud_course_id: stud_course_id }, function (data) { alert(data); location.reload(); }); } });

function forgotPassSMS(userId, userType) {
	var conf = confirm('Do you want to send Username & Password?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'forget_pass_sms', userId: userId, userType: userType }, function (data) { alert("Sent successfully!") }); }
}
function uploadDocsSMS(userId, userType) {
	var conf = confirm('Do you want to send Reminder SMS for uploading documents?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'upload_doc_sms', userId: userId, userType: userType }, function (data) { alert("Sent successfully!") }); }
}

//send certificate dispatch SMS
$(".send-cert-dispatch-sms").click(function () {
	$("#total_cert").val($(this).data('total')); $("#cert_req_mast_id").val($(this).data('id')); $("#inst_name").val($(this).data('name')); $("#inst_mobile").val($(this).data('mobile'));
});

$('#total_cert, #reciept_no, #dispatch_mode').on('keyup change click mouseenter mouseleave', function () {
	var total = $("#total_cert").val(); var inst_mobile = $("#inst_mobile").val(); var reciept_no = $("#reciept_no").val();
	var dispatch_mode = $("#dispatch_mode").val(); var date_dispatch = $(".date_dispatch").val(); var smsTxt = total + " DITRP Certificates are dispatched by " + dispatch_mode + " on " + date_dispatch + " with Rec. No. " + reciept_no + "\r\nDITRP\r\n9975554765";
	$("#message").val(smsTxt);
});

/* submit certifcate dispatch SMS form */
$('#send_cert_dispatch_sms_form').on('submit', function (e) {
	$('.loader-mg-modal').show(); e.preventDefault();
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: $('#send_cert_dispatch_sms_form').serialize(),
		success: function (data) {
			$('.loader-mg-modal').hide();
			var data = JSON.parse(data); var success = data.success;
			if (!success) {
				$('.loader-mg-modal').hide(); var message = data.errors.message;
				if (message != 'undefined' && message != '') { $("#msg-error").addClass('has-error'); $("#msg-error .help-block").html(message); }
			} else { alert(data.message); $(".cert_disptach_sms").modal('hide'); location.reload(); }
		}
	});
});

$("#addMoreDynamicFields").on('click', function () {
	var rowcount = parseInt($("#rowcount").val()) + 1;
	var html = '<tr id="row' + rowcount + '"><td><input type="text" name="field_name[]"  class="form-control" id="field_name"  placeholder="Field name" /></td><td><input type="text" name="field_value[]"  class="form-control" id="field_value"  placeholder="Field value" /></td></tr>';
	//alert(html);
	$("#drow").append(html);
});

/*------------------------ Parcel Track Institute --------------------------------*/
$(".send-parcel-details").click(function () {
	$("#reciept_no").val($(this).data('receipt'));
	$("#sms_message").val($(this).data('smsmessage'));
	$("#receiveddate").val($(this).data('receiveddate'));
	$("#status").val($(this).data('status'));
	$("#dispatchid").val($(this).data('dispatchid'));
});

$('#receive_parcel_status').on('submit', function (e) {
	$('.loader-mg-modal').show(); e.preventDefault();
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: $('#receive_parcel_status').serialize(),
		success: function (data) {
			$('.loader-mg-modal').hide();
			var data = JSON.parse(data); var success = data.success;
			if (!success) {
				$('.loader-mg-modal').hide(); var message = data.errors.message;
				if (message != 'undefined' && message != '') { $("#msg-error").addClass('has-error'); $("#msg-error .help-block").html(message); }
			} else { alert(data.message); $(".cert_parcel-details").modal('hide'); location.reload(); }
		}
	});
});

function receivedParcelStatus(dispatchId) { var conf = confirm('Are You Received Parcel?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'parcel_received_status', dispatch_id: dispatchId }, function (data) { console.log(data); location.reload(); }); } };


/*------------------------------------TYping Software -------------------------------------------*/
function deletePlan(planId) { var conf = confirm('Delete this Plan?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_plan', plan_id: planId }, function (data) { console.log(data); $("#row-" + planId).hide(); }); } };

function deleteTypingInstitute(instId) { var conf = confirm('Delete this Institute?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_typing_institute', inst_id: instId }, function (data) { console.log(data); $("#row-" + instId).hide(); }); } };

//SEnd Activation Key Via SMS
function sendActivactionkeySMS(userId, userType) {
	var conf = confirm('Do You Want To Send Activation Key Via SMS?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'send_activationkey_sms', userId: userId, userType: userType }, function (data) { alert("Sent successfully!") }); }
};

function sendActivactionkeyEMAIL(instId, userType) { var conf = confirm('Do You Want To Send Activation Key Via EMAIL?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'send_activationkey_email', inst_id: instId, userType: userType }, function (data) { alert("Sent successfully!") }); } };

function getpopup(id, inst_course_id) {
	//  alert(id);
	$("#frmmarksheet  #exam_id").val(id);
	$("#frmmarksheet  #inst_course_id").val(inst_course_id);
	//$("#certificate_requests_id").attr("value", id);

	var data = $('#frmmarksheet').serializeArray();
	data.push({ name: 'exam_id', value: id });

	//console.log(data);
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: data,

		success: function (data) {
			$("#frmmarksheet  #exam_id").val('');
			$("#frmmarksheet  #subject").val('');
			$("#frmmarksheet  #marks").val('');
			$("#frmmarksheet  #marksobj").val('');
			if (data.length > 0) {
				var cdata = JSON.parse(data);
				$("#frmmarksheet  #exam_id").val(cdata.markshitreq);
				// $("#frmmarksheet  #certificate_requests_id").val(data.certificate_requests_id);
				$("#frmmarksheet  #subject").val(cdata.markshitsub);
				$("#frmmarksheet  #marks").val(cdata.markshitmark);
				$("#frmmarksheet  #marksobj").val(cdata.markshitmarkobj);
			}

		}
	});
	$("#myModal").modal();
}
function getInstCourseSubjects(id) {

	$("#instCourseSubjects  #subject").val('');
	$("#instCourseSubjects  #inst_course_id").val('');
	$.ajax({
		type: 'post',
		url: 'include/classes/ajax.php',
		data: { action: 'get_inst_course_subjects', inst_course_id: id },
		success: function (data) {

			console.log(data);
			if (data.length > 0) {
				var data = JSON.parse(data);
				data = data.data;
				console.log(data.id);
				console.log(data.subjects);
				$("#instCourseSubjects  #subject").val(data.subjects);
				$("#instCourseSubjects  #inst_course_id").val(data.id);
			}
		},
		error: function (data) {
			console.log(data);
		}
	});
	$("#instCourseSubjectsModal").modal();
}

$("#instCourseSubjects").submit(function (event) {
	event.preventDefault();
	var subject = $("#instCourseSubjects  #subject").val();
	var inst_course_id = $("#instCourseSubjects  #inst_course_id").val();
	if (subject == '') { alert('Please enter subjects!'); return; };
	$.ajax({
		type: "POST",
		url: 'include/classes/ajax.php',
		data: { action: 'add_inst_course_subjects', inst_course_id: inst_course_id, subject: subject },
		success: function (data) {
			if (data == 'success')
				alert('Subjects added successfully!');
			else if (data == 'failed')
				alert("Error in adding subjects!");
			location.reload();
		}
	});
});

//Direct Login institute from admin
function loginToInst(u, p) {
	var conf = confirm('Are you sure? Do you want to logout from admin and Login to franchise?');
	if (conf == false) return;
	$.ajax({
		type: 'POST',
		url: 'include/classes/ajax.php',
		data: { action: 'direct_login', login: 'login', uname: u, pword: p },
		success: function (data) {
			//console.log(data);
			window.location.href = APP_PATH;
		},
		error: function (data) {
			console.log(data);
		}
	});

}

// Approve After SMS To Order Certificate
function orderbeforeSMS(instMobile) { var conf = confirm('Are you sure? Do you want to send SMS for order certificate?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'order_before_sms', inst_mobile: instMobile }, function (data) { alert("Sent successfully!") }); } };

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

//delete amc
function delete_amc(amcid) { var conf = confirm('Delete this AMC?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_amc', amc_id: amcid }, function (data) { console.log(data); $("#row-" + amcid).hide(); }); } };
//Deassign institute
function deassign_amc(assign_id, INSTITUTE_ID) { var conf = confirm('De-Assign this Institute?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'deassign_amc', assign_id: assign_id, INSTITUTE_ID: INSTITUTE_ID }, function (data) { console.log(data); $("#row-" + INSTITUTE_ID).hide(); }); } };

//change AMC verfiy flag
function changeAmcVerify(amcid, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		smallLoader('verify-' + amcid, 1); $.post('include/classes/ajax.php', { action: 'change_amc_verify', amc_id: amcid, flag: flag }, function (data) {
			console.log(data); smallLoader('verify-' + amcid, 0);
			if (flag == 0)
				$("#verify-" + amcid).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeAmcVerify(' + amcid + ',1)"><i class="mdi mdi-close"></i> NO');
			else if (flag == 1)
				$("#verify-" + amcid).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeAmcVerify(' + amcid + ',0)"><i class="mdi mdi-check"></i></i> YES');
		});
	}
};

//Change Categorty By Support Type
function Category_by_type(typeId) { $.post('include/classes/ajax.php', { action: 'categrybyType_act', type_id: typeId }, function (data) { $("#supportcat1").html(data); }); }

//delete institute plans
function deleteInstitutePlan(planid) { var conf = confirm('Delete this Institute Plan?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_institute_plan', plan_id: planid }, function (data) { console.log(data); $("#row-" + planid).hide(); }); } };

// admin-> manage institutes 
function deleteInstitute(instId) { var conf = confirm('Delete this institute?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_institute', inst_id: instId }, function (data) { console.log(data); $("#row-" + instId).hide(); }); } };

// set accpunt expiry date
function setAccExpDate(startDate) { $.post('include/classes/ajax.php', { action: 'set_acc_expiry_date', start_date: startDate }, function (data) { console.log(data); $("#expirationdate").val(data); }); }

//change institue status active inactive
function changeInstStatus(instId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_inst_status', inst_id: instId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + instId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatus(' + instId + ',1)"><i class="mdi mdi-close"></i> In-Active </a>');
			else if (flag == 1)
				$("#status-" + instId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatus(' + instId + ',0)"><i class="mdi mdi-check"></i>Acive </a>');
			//console.log(data);
		});
	}
};

//change institue status website
function changeInstStatusWebsite(instId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_inst_status_website', inst_id: instId, flag: flag }, function (data) {
			if (flag == 0)
				$("#website-" + instId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatusWebsite(' + instId + ',1)"><i class="mdi mdi-close"></i>  Not Shown On Website </a>');
			else if (flag == 1)
				$("#website-" + instId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatusWebsite(' + instId + ',0)"><i class="mdi mdi-check"></i> Shown On Website </a>');
			//console.log(data);
		});
	}
};



//change institue verfiy flag
function changeInstVerify(instId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		smallLoader('verify-' + instId, 1); $.post('include/classes/ajax.php', { action: 'change_inst_verify', inst_id: instId, flag: flag }, function (data) {
			console.log(data); smallLoader('verify-' + instId, 0);
			if (flag == 0)
				$("#verify-" + instId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeInstVerify(' + instId + ',1)"><i class="fa fa-close"></i> NO');
			else if (flag == 1)
				$("#verify-" + instId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstVerify(' + instId + ',0)"><i class="fa fa-check"></i></i> YES');
		});
	}
};

function smallLoader(element, status) { var html = ''; if (status == 1) html = '<img src="resources/loader_small.gif" />'; $('#' + element).html(html); }

//ADMIN
//get course list not purchase
function getCourseListNotPurchase(studentId) { $.post('include/classes/ajax.php', { action: 'get_course_not_purchase', student_id: studentId }, function (data) { $("#coursename").html(data); }); }

//delete new course with subject course material
function deleteCourseMultiSubFile(fileId, courseId) { var conf = confirm('Delete this file?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_course_multi_sub_file', file_id: fileId, course_id: courseId }, function (data) { $("#file-area" + fileId).hide(); }); } };

// set course name by course id for multi subject course
function getSubjectId(courseId) { $.post('include/classes/ajax.php', { action: 'get_subject_id', course_id: courseId }, function (data) { $("#subjectid").html(data); }); }

function getSubjectIdTyping(courseId) { $.post('include/classes/ajax.php', { action: 'get_subject_id_typing', course_id: courseId }, function (data) { $("#subjectid").html(data); }); }

//delete exam multi subjects
function deleteExamMultiSub(examId) { var conf = confirm('Delete this exam?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_exam_multi_sub', exam_id: examId }, function (data) { $("#exam-id" + examId).hide(); }); } };

// admin-> delete question bank
function deleteQueBank(queBankId) { var conf = confirm('Delete this question bank?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_quebank', quebank_id: queBankId }, function (data) { $("#quebank-id" + queBankId).hide(); }); } };

// admin-> empty question bank
function emptyQueBank(queBankId) {
	var conf = confirm('Delete all the data of this question bank?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'empty_quebank', quebank_id: queBankId }, function (data) {
			$("#total-" + queBankId).html('0'); console.log(data);
		});
	}
};


// admin-> delete question bank for multi subject
function deleteQueBankMultiSub(queBankId) { var conf = confirm('Delete this question bank?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_quebank_multi_sub', quebank_id: queBankId }, function (data) { $("#quebank-id" + queBankId).hide(); }); } };

// admin-> empty question bank for multi subject
function emptyQueBankMultiSub(queBankId) {
	var conf = confirm('Delete all the data of this question bank?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'empty_quebank_multi_sub', quebank_id: queBankId }, function (data) {
			$("#total-" + queBankId).html('0'); console.log(data);
		});
	}
}

// admin-> delete question  for multi subject
function deleteQuestionMultiSub(queId) { var conf = confirm('Delete this Question?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_question_multi_sub', question_id: queId }, function (data) { $("#row-" + queId).hide(); }); } };

// change exam status for multi sub
function changeExamStatusMultiSub(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_status_multi_sub', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + examId).html('<a href="javascript:void(0)" onclick="changeExamStatusMultiSub(' + examId + ',1)" style="color:#f00"><i class="fa fa-times"></i>In-Active</a>');
			else if (flag == 1)
				$("#status-" + examId).html('<a href="javascript:void(0)" onclick="changeExamStatusMultiSub(' + examId + ',0)" style="color:#3c763d"><i class="mdi mdi-check"></i>Active</a>');
		});
	}
}

//chage exam result display status for multi sub
function changeExamDispResultFlagMultiSub(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_result_display_multi_sub', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#disp-result-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDispResultFlagMultiSub(' + examId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#disp-result-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDispResultFlagMultiSub(' + examId + ',0)"><i class="mdi mdi-check"></i></i>');
			console.log(data);
		});
	}
}

//chage exam result display status for multi sub
function changeExamDemoFlagMultiSub(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_demo_status_multi_sub', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#demo-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDemoFlagMultiSub(' + examId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#demo-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDemoFlagMultiSub(' + examId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
}

//institute -> delete course subject added in multi sub course

function deleteInstCourseSubject(instCourseSubId) { var conf = confirm('Remove this course subject?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_inst_course_sub', inst_course_sub_id: instCourseSubId }, function (data) { location.reload(true); }); } };
//institute -> delete course multi sub
function deleteInstCourseMultiSub(instCourseId) { var conf = confirm('Delete this course?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_inst_coursemultisub', inst_course_id: instCourseId }, function (data) { $("#row-" + instCourseId).hide(); }); } };
//institute -> delete multiple courses multi sub
function bulkDeleteInstCourseMultisub() {
	var checkValuesArr = $('input[name=check_course]:checked').map(function () { return $(this).val(); }).get();
	checkValues = JSON.stringify(checkValuesArr);
	if (checkValuesArr.length > 0) {
		var conf = confirm("Are you sure?");
		if (conf) {
			$.post('include/classes/ajax.php', { action: 'bulk_delete_inst_courses_multi_sub', inst_course_id: checkValues }, function (data) {
				console.log(data);
				for (var i = 0; i < checkValuesArr.length; i++) {
					$("#row-" + checkValuesArr[i]).hide();
					console.log(checkValuesArr[i]);
				}
				alert("Courses deleted successfully.");
			});
		}
	} else {
		alert("Please select courses to delete.");
	}
}

//delete student results multi sub
function deleteStudentResult_MultiSub(resultId) { var conf = confirm('Delete this exam result detail?'); if (conf == true) { $.post('include/classes/ajax.php', { action: 'delete_student_exam_result_multi_sub', exam_result_id: resultId }, function (data) { $("#row-" + resultId).hide(); }); } };

//change multi sub course status
function changeCoureStatusMultiSub(courseId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_course_status_multi_sub', course_id: courseId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatusMultiSub(' + courseId + ',1)"><i class="mdi mdi-close"></i> In-Active</a>');
			else if (flag == 1)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatusMultiSub(' + courseId + ',0)"><i class="mdi mdi-check"></i></i> Active</a>');
		});
	}
};

//count of subject in multi sub course
function validatesubjectcount() {
	var val = [];
	$(':checkbox:checked').each(function (i) {
		val[i] = $(this).val();
	});
	if (val.length >= 1 && val.length <= 10) {
		//alert("OK");
		$("#add_subject").attr('disabled', false);
	} else {
		$("#add_subject").attr('disabled', true);
		//alert("Please Select Minimum 1 And Maximum 10 Subjects");
	}
}

//Print Status For Staff DITRP Internal Option In Print Order Certificate

function changePrintStatus(instId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_print_status', inst_id: instId, flag: flag }, function (data) {
			if (flag == 0)
				$("#print-" + instId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changePrintStatus(' + instId + ',1)"><i class="mdi mdi-close"> NO PRINT</i>');
			else if (flag == 1)
				$("#print-" + instId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changePrintStatus(' + instId + ',0)"><i class="mdi mdi-check">YES PRINTED</i>');
			console.log(data);
		});
	}
};


//GST Details 
function getgstdetails() {
	/*udf1 ->amount
	udf2 ->gst*/

	var udf1 = parseInt($("#udf1").val());

	//var udf2=0;
	var amount = 0;

	//udf2 = ((udf1 * 18)/100);

	//amount = udf2 + udf1;
	amount = udf1;

	//$("#udf2").val(udf2);
	$('#txnAmount').val(amount);
}

//change course status flag
function changeCoureStatus(courseId, flag) {
	var conf = confirm('Are you sure?'); if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_course_status', course_id: courseId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatus(' + courseId + ',1)"><i class="mdi mdi-close"></i> In-Active</a>');
			else if (flag == 1)
				$("#status-" + courseId).html('<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatus(' + courseId + ',0)"><i class="mdi mdi-check"></i></i> Active</a>');
		});
	}
};

function bulkEditCourse() {
	var checkValues = $('input[name=check_course]:checked').map(function () { return $(this).val(); }).get();
	checkValues = JSON.stringify(checkValues);
	$.post('include/classes/ajax.php', { action: 'bulk_edit_course', courseIdArr: checkValues }, function (data) { $("#ajax-data").html(data); });
}
$("#bulk_edit_course_form").submit(function (event) {
	event.preventDefault(); /*$(".loader-img").show();*/
	$.ajax({ url: 'include/classes/ajax.php', type: 'POST', data: $(this).serialize(), dataType: 'html' })
		.done(function (data) {
			var data = JSON.parse(data);
			if (!data.success) {
				$("#exam_fees_err").addClass('has-error'); $("#exam_fees_err .help-block").html(data.error);
			} else if (data.success == true) { $(".loader-img").hide(); location.reload(); }
		}).fail(function () { alert('Ajax Submit Failed ...'); });
});

// bulk delete courses
function bulkDeleteCourse() {
	var checkValuesArr = $('input[name=check_course]:checked').map(function () { return $(this).val(); }).get();
	checkValues = JSON.stringify(checkValuesArr);
	// console.log(checkValues);
	if (checkValuesArr.length > 0) {
		var conf = confirm("Are you sure?");
		if (conf) {
			$.post('include/classes/ajax.php', { action: 'bulk_delete_courses', course_id: checkValues }, function (data) { //console.log(data);
				for (var i = 0; i < checkValuesArr.length; i++) {
					$("#course-id" + checkValuesArr[i]).hide();
					//console.log(checkValuesArr[i]);
				}
				alert("Courses deleted successfully.");
			});
		}
	} else {
		alert("Please select courses to delete.");
	}
}
//chage exam active status
function changeExamStatus(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_status', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#status-" + examId).html('<a href="javascript:void(0)" onclick="changeExamStatus(' + examId + ',1)" style="color:#f00"><i class="fa fa-times"></i>In-Active</a>');
			else if (flag == 1)
				$("#status-" + examId).html('<a href="javascript:void(0)" onclick="changeExamStatus(' + examId + ',0)" style="color:#3c763d"><i class="mdi mdi-check"></i>Active</a>');
		});
	}
}

//chage exam result display status
function changeExamDispResultFlag(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_result_display', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#disp-result-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDispResultFlag(' + examId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#disp-result-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDispResultFlag(' + examId + ',0)"><i class="mdi mdi-check"></i></i>');
			console.log(data);
		});
	}
}
//chage exam result display status
function changeExamDemoFlag(examId, flag) {
	var conf = confirm('Are you sure?');
	if (conf == true) {
		$.post('include/classes/ajax.php', { action: 'change_exam_demo_status', exam_id: examId, flag: flag }, function (data) {
			if (flag == 0)
				$("#demo-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDemoFlag(' + examId + ',1)"><i class="mdi mdi-close"></i>');
			else if (flag == 1)
				$("#demo-" + examId).html('<a href="javascript:void(0)" onclick="changeExamDemoFlag(' + examId + ',0)"><i class="mdi mdi-check"></i></i>');
		});
	}
}

$('.selectall_stud').click(function (event) {
	var selectId = $(this).attr('id');
	if (this.checked) {
		$('.' + selectId).each(function () {
			this.checked = true;
		});
	} else {
		$('.' + selectId).each(function () {
			this.checked = false;
		});
	}
});

//bulk select
$('#selectall').click(function (event) {
	if (this.checked) {
		$('.is_present').each(function () {
			this.checked = true;
		});
	}
	else {
		$('.is_present').each(function () {
			this.checked = false;
		});
	}
});

//bulk select
$('#selectall1').click(function (event) {
	if (this.checked) {
		$('.is_absent').each(function () {
			this.checked = true;
		});
	}
	else {
		$('.is_absent').each(function () {
			this.checked = false;
		});
	}
});

//batch remaining count
function seeRemaining(batchId, instId) { $.post('include/classes/ajax.php', { action: 'get_batch_remaining_count', batch_id: batchId, inst_id: instId }, function (data) { console.log(data); $("#remainingStudent").val(data); }); }

//download festival images

function download(url) {
	var a = $("<a style='display:none' id='js-downloder'>")
		.attr("href", url)
		.attr("download", "festival.png")
		.appendTo("body");

	a[0].click();

	a.remove();
}

function saveCapture(element) {
	html2canvas(element).then(function (canvas) {
		download(canvas.toDataURL("image/png"));
	})
}

$('#btnDownload').click(function () {
	var element = document.querySelector("#capture");
	saveCapture(element)
})

//GST Details 
function getgstdetails() {
	/*udf1 ->amount
	udf2 ->gst*/

	var udf1 = parseInt($("#udf1").val());

	var udf2 = 0;
	var amount = 0;

	udf2 = ((udf1 * 18) / 100);

	amount = udf2 + udf1;

	$("#udf2").val(udf2);
	$('#amount').val(amount);
}



