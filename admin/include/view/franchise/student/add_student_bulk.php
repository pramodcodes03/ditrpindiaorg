<?php
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}
?>
<style type="text/css">
  .form-control {
    padding: 0px;
    border-radius: 1px;
    height: 28px;
  }

  .err {
    border: 1px solid #f00;
  }

  .upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    cursor: pointer;
  }

  .upload-btn-wrapper .btn {
    font-size: 15px;
    cursor: pointer;
  }

  .upload-btn-wrapper .btn:hover {
    cursor: pointer;
  }

  .upload-btn-wrapper input[type=file]:hover {
    cursor: pointer;
  }

  .upload-btn-wrapper input[type=file] {
    cursor: pointer;
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
  }

  .upload-btn-wrapper .btn {
    max-width: 100px;
    overflow: hidden;
  }
</style>
<div class="content-wrapper" ng-controller="globalController">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Add Bulk Student</h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-students">Students</a></li>
      <li class="active">Add New Student</li>
    </ol>
  </section>
  <p style="margin:5px 50px; color:red; background-color: yellow; padding: 5px; font-size:16px; font-weight:600; ">Note: Dear ATC's Add Student in Group Of 5 And Click On Submit Once Your Admission Will Be Register. In Course List Only Single Course Is Available Multiple Subject Course Is Not Available. If You Want Student Admission In Multiple Subject Course You Want To Add Student Manually By Enquiry Form. Please Do Not Click Again On Submit Button Otherwise Repeated Admission Registered. If Any Query Call Our Customer Care. </p>

  <!-- Main content -->
  <section class="content">

    <div class="row" ng-if="message">
      <div class="col-sm-12">
        <div class="alert alert-dismissible" id="messages" ng-class="{'alert-success':success==true, 'alert-danger':success==false}">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
          <h4><i class="icon fa fa-check"></i> </h4>
          {{message}}

        </div>
      </div>
    </div>

    <div class="row">
      <!-- left column -->

      <div class="col-md-12" ng-init="init(<?php echo "$institute_id, $staff_id, '" . $_SESSION['user_name'] . "', '" . $_SESSION['ip_address'] . "'"; ?>)">


        <div class="row lazyloader">
          <form role="form" name="studForm" method='post' class="form-validate" ng-submit="saveBulkStudent(students)" id="add_student" novalidate="true">

            <table class="table table-striped table-bordered" ng-class="{'loading':loader}">
              <thead>
                <tr>
                  <td>#</td>
                  <td>Abbrivation</td>
                  <td>First Name</td>
                  <td>Father/Husband</td>
                  <td>Last Name</td>
                  <td>Mother</td>
                  <td>Aadhar No.</td>
                  <td>Mobile</td>
                  <td width="10%">Date Of Birth</td>
                  <td>Course</td>
                  <td>Photo</td>
                  <td>Photo ID</td>
                  <td>Action</td>
                </tr>
              </thead>
              <tbody>
                <tr ng-class="{'has-error':(studForm.fname_{{$index}}.$invalid) || (studForm.lname_{{$index}}.$invalid||(studForm.adharno_{{$index}}.$invalid && studForm.photoid_{{$index}}.$invalid)) || (studForm.mobile_{{$index}}.$invalid) || (studForm.course_{{$index}}.$invalid)  || (studForm.result_{{$index}}.$invalid)}" ng-repeat="stud in students track by $index">
                  <td>{{$index+1}}</td>
                  <td>
                    <select class="form-control" name="abbr_{{$index}}" ng-model="stud.abbr" ng-class="{'err': abbr_{{$index}} }">
                      <option selected="selected" value="">--select--</option>
                      <option ng-repeat="abr in abbr" value="{{abr.value}}">{{abr.name}}</option>
                    </select>
                    <p>{{abbr_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="fname_{{$index}}" ng-model="stud.fname" maxlength="20" placeholder="" uppercase ng-class="{ 'err': fname_{{$index}} }" />
                    <p>{{fname_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control " name="mname_{{$index}}" ng-model="stud.mname" maxlength="20" uppercase ng-class="{ 'err': mname_{{$index}} }" />
                    <p>Certificate: <input type="checkbox" name="cert_mname_{{$index}}" ng-model="stud.cert_mname" ng-checked="stud.cert_mname==1" tabindex="-1" /></p>
                    <p class="help-block">{{mname_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="lname_{{$index}}" ng-model="stud.lname" maxlength="20" uppercase ng-class="{ 'err': lname_{{$index}} }" />
                    <p>Certificate: <input type="checkbox" name="cert_lname_{{$index}}" ng-model="stud.cert_lname" ng-checked="stud.cert_lname==1" tabindex="-1" /></p>
                    <p class="help-block">{{lname_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="mothername_{{$index}}" ng-model="stud.mothername" maxlength="20" uppercase ng-class="{ 'err': mothername_{{$index}} }" />
                    <p class="help-block">{{mothername_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="adharno_{{$index}}" ng-model="stud.adharno" maxlength="12" numbers ng-pattern="/^[0-9]{1,12}$/" ng-required="!stud.lname">
                    <p class="help-block">{{adharno_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="mobile_{{$index}}" ng-model="stud.mobile" maxlength="10" numbers required="required">
                    <p class="help-block">{{mobile_$index}}</p>
                  </td>
                  <td>
                    <input type="text" class="form-control calendar" name="dob_{{$index}}" ng-model="stud.dob" maxlength="10" placeholder="dd-mm-yyyy" ng-class="{ 'err': dob_{{$index}} }" />
                    <p class="help-block">{{dob_$index}}</p>
                  </td>
                  <td>
                    <select class="form-control" selectpicker name="course_{{$index}}" ng-model="stud.course" ng-class="{ 'err': course_{{$index}} }">
                      <option selected="selected" value="">--select course--</option>
                      <option ng-repeat="course in courses" value="{{course.INSTITUTE_COURSE_ID}}">{{course.COURSE_NAME_MODIFY}}</option>
                    </select>
                    <p class="help-block">{{course_$index}}</p>
                  </td>
                  <td>

                    <div class="upload-btn-wrapper">
                      <button class="btn">
                        <span ng-if="stud.photo==''"><i class="fa fa-picture-o"></i></span>
                        <span ng-if="stud.photo!=''">{{stud.photo}}</span>
                      </button>
                      <input type="file" name="photo{{$index}}" ng-files="getTheMultipleFiles($files,'studPhoto',$index)" ng-model="stud.photo" accept="image/x-png,image/gif,image/jpeg" />
                    </div>
                    <p class="help-block" ng-if="stud.photoErr!=''">{{stud.photoErr}}</p>
                  </td>
                  <td>
                    <div class="upload-btn-wrapper">
                      <button class="btn">
                        <span ng-if="stud.photoid=='' || !stud.lname"><i class="fa fa-file-image-o" aria-hidden="true"></i></span>
                        <span ng-if="stud.photoid!=''">{{stud.photoid}}</span>
                      </button>
                      <input type="file" name="photoid{{$index}}" ng-files="getTheMultipleFiles($files,'studPhotoId',$index)" ng-model="stud.photoid" ng-required="!stud.lname" accept="image/x-png,image/gif,image/jpeg" />
                    </div>
                    <p class="help-block" ng-if="stud.photoErr!=''">{{stud.photoIdErr}}</p>
                  </td>
                  <td>
                    <button ng-if="students.length>1" type="button" ng-disabled="$index==0" class="btn btn-danger btn-xs" href="javascript:void(0)" ng-click="removeRow($index)"><i class="fa fa-times"></i></button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="13">
                    <a href="javascript:void(0)" class="btn btn-lg btn-primary pull-right" ng-click="addRow()" ng-disabled="studForm.$invalid"><i class="fa fa-plus"></i> </a>
                  </td>
                </tr>
              </tfoot>
            </table>
            <div class="row text-center">
              <input type="submit" class="btn btn-primary" name="action" value="SUBMIT" ng-disabled="studForm.$invalid" />
              <a href="page.php?page=list-students" class="btn btn-warning">CANCEL</a>
            </div>
          </form>


        </div>



      </div>
      </form>
    </div>
  </section>
</div>