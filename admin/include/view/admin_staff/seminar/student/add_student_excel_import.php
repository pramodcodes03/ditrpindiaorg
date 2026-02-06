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

  require_once('resources/excel/php-excel-reader/excel_reader2.php');
  require_once('resources/excel/SpreadsheetReader.php');

  include_once('include/classes/student.class.php');
  $student = new student();

  if (isset($_POST["import"])) {


    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

      $ext    = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
      $randno   = @date('d_m_Y') . '_' . mt_rand(0, 123456789);
      $file_name  = 'studentexcel_' . $randno . '.' . $ext;

      $targetPath = STUDENT_EXCEL_DOCUMENTS_PATH . '/' . $institute_id . '/' . $file_name;
      @mkdir(STUDENT_EXCEL_DOCUMENTS_PATH . '/' . $institute_id, 0777, true);
      @move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

      $Reader = new SpreadsheetReader($targetPath);

      $sheetCount = count($Reader->sheets());

      for ($i = 0; $i < 1; $i++) {

        $Reader->ChangeSheet($i);

        $S = 1;
        $C = 0;

        foreach ($Reader as $Row) {
          if ($S > $C) {
            $C++;
          } else {


            //print_r($Row); exit();
            $CourseCode                 = $db->test(isset($Row[0]) ? $Row[0] : '');
            $MultiSubjectCourseCode     = $db->test(isset($Row[1]) ? $Row[1] : '');

            $abbreviation               = strtoupper($db->test(isset($Row[2]) ? $Row[2] : ''));
            $son_of                     = strtoupper($db->test(isset($Row[3]) ? $Row[3] : ''));
            $fname                      = strtoupper($db->test(isset($Row[4]) ? $Row[4] : ''));
            $mname                      = strtoupper($db->test(isset($Row[5]) ? $Row[5] : ''));
            $lname                      = strtoupper($db->test(isset($Row[6]) ? $Row[6] : ''));
            $mothername                 = strtoupper($db->test(isset($Row[7]) ? $Row[7] : ''));

            $mobile                     = $db->test(isset($Row[8]) ? $Row[8] : '');
            $dob                        = $db->test(isset($Row[9]) ? $Row[9] : '');
            $gender                     = $db->test(isset($Row[10]) ? $Row[10] : '');

            $adharid                    = isset($Row[11]) ? $Row[11] : '';

            $postcode                   = $db->test(isset($Row[12]) ? $Row[12] : '');
            $per_add                    = $db->test(isset($Row[13]) ? $Row[13] : '');

            $joing_date                 = $db->test(isset($Row[14]) ? $Row[14] : '');

            if ($joing_date != '')
              $joing_date = @date('Y-m-d', strtotime($joing_date));

            $curr_date = @date('Y-m-d');

            $newEndingDate = @date("Y-m-d", strtotime($curr_date . " - 1 year"));

            if ($joing_date < $newEndingDate) {
              echo $errors['joing_date'] = "Date Should be greater than one year span";
            }


            $interested_course = '';
            if ($CourseCode != '') {

              $course_id = $db->get_course_id_code($CourseCode);
              //$COURSE_ID = $course_id['COURSE_ID'];

              $interested_course = $db->get_inst_course_id($course_id, $institute_id);
            }

            if ($MultiSubjectCourseCode != '') {

              $multi_course_id = $db->get_multi_course_id_code($MultiSubjectCourseCode);
              // $MULTI_SUB_COURSE_ID = $course_id['MULTI_SUB_COURSE_ID'];

              $interested_course = $db->get_inst_multi_course_id($multi_course_id, $institute_id);
            }

            if ($dob != '') {
              $dob = @date('Y-m-d', strtotime($dob));
            }

            if ($interested_course != '')
              $interested_course = json_encode($interested_course);
            if (!is_array($interested_course) && $interested_course == '') {
              $errors['interested_course'] = "Required! Add atleast one course code.";
            }



            $created_by     = $_SESSION['user_fullname'];
            $created_by_ip    = $_SESSION['ip_address'];

            $tableName  = "student_enquiry";

            $tabFields  = "(ENQUIRY_ID,INSTITUTE_ID, ABBREVIATION, STUDENT_FNAME,STUDENT_MNAME,STUDENT_LNAME,STUDENT_MOTHERNAME, STUDENT_DOB,STUDENT_GENDER,STUDENT_MOBILE,STUDENT_PER_ADD,STUDENT_PINCODE,STUDENT_ADHAR_NUMBER,INSTRESTED_COURSE,CERT_MNAME,CERT_LNAME,SONOF,DATE_JOINING,ENQUIRY_BY, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

            $insertVals = "(NULL, '$institute_id','$abbreviation','$fname','$mname','$lname','$mothername','$dob','$gender','$mobile','$per_add','$postcode','$adharid','[$interested_course]','1', '1','$son_of','$joing_date','$institute_id','$created_by',NOW(),'$created_by_ip')";

            $insertSql  = $db->insertData($tableName, $tabFields, $insertVals);
            $exSql      = $db->execQuery($insertSql);
            if (! empty($exSql)) {
              $type = "success";
              $message = "Excel Data Imported into the Database";
              header('location:page.php?page=list-student-enquiries');
            } else {
              $type = "error";
              $message = "Problem in Importing Excel Data";
            }
          }
        }
      }
    }
  }

  ?>

 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Add Student By Excel Sheet
     </h1>
     <ol class="breadcrumb">
       <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="page.php?page=list-student-enquiries">Student Enquiry</a></li>
       <li class="active">Add Sheet</li>
     </ol>
   </section>

   <!-- Main content -->
   <section class="content">
     <form class="form-horizontal" name="frmExcelImport" id="frmExcelImport" action="" method="post" enctype="multipart/form-data">

       <!-- left column -->
       <?php
        if (isset($success)) {
        ?>
         <div class="row">
           <div class="col-sm-12">

             <div id="response" class="alert alert-<?php if (!empty($type)) {
                                                      echo $type . " display-block";
                                                    } ?>"> <?php if (!empty($message)) {
                                                                                                                      echo $message;
                                                                                                                    } ?></div>
           </div>
         </div>
       <?php
        }
        ?>

       <div class="row">


         <div class="col-md-2">
         </div>
         <div class="col-md-8">
           <!-- general form elements -->
           <div class="box box-primary">
             <div class="box-header with-border">
               <h3 class="box-title"> Add Student By Excel Sheet </h3>
               <a href="<?= STUDENT_EXCEL_DOCUMENTS_PATH ?>/sample/student_excel_sample.xlsx" target="_blank" class="btn btn-sm btn-success pull-right"><i class="fa fa-cloud-download"></i> Download Sample CSV</a>
             </div>
             <div class="box-body">

               <div class="form-group <?= (isset($errors['file'])) ? 'has-error' : '' ?>">
                 <label for="file" class="col-sm-3 control-label">Upload CSV Of Student Data</label>
                 <div class="col-sm-9">
                   <input id="file" name="file" type="file" accept=".xls,.xlsx">
                   <span class="help-block"><?= isset($errors['file']) ? $errors['file'] : '' ?></span>
                 </div>
               </div>

             </div>
             <!-- /.box-body -->
             <div class="box-footer text-center">
               <a href="page.php?page=list-student-enquiries" class="btn btn-default">Cancel</a>
               <input type="submit" name="import" class="btn btn-info" value="Upload" />
             </div>
           </div>
         </div>
       </div>
     </form>
     <!-- /.row -->
   </section>
   <!-- /.content -->
 </div>