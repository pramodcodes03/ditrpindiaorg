<?php
    include_once('include/classes/student.class.php');
    $student = new student();
    $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
    $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';


    $res = $student->list_attendance($user_id, '','', '');
    if($res!='')
    {
        $srno=1;
        while($data = $res->fetch_assoc())
        {				
            extract($data);
            // echo "<pre>";
            // print_r($data);
            $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID,$STUDENT_ID,$attendancedate);
        }
    }
    $date = date("Y-m-d");
?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Attendance View </h4>  
                    <?php
                    if(isset($success))
                        {
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                            <h4><i class="icon fa fa-check"></i> <?= ($success==true)?'Success':'Error' ?>:  <?= isset($message)?$message:'Please correct the errors.'; ?></h4>
                    
                            <?php
                            echo "<ul>";
                            foreach($errors as $error)
                            {
                            echo "<li>$error</li>";
                            }
                            echo "<ul>";
                            ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                   <form name="form1" class="forms-sample" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                
                        <div class="form-group col-sm-4 <?= (isset($errors['date']))?'has-error':'' ?>">
                            <label>Select Date</label>								
                            <input type="date" class="form-control" placeholder="date" name="date" value="<?= isset($_POST['date'])?$_POST['date']:$date ?>">
                            <span class="help-block"><?= (isset($errors['date']))?$errors['date']:'' ?></span>
                        </div>

                        <div class="form-group col-sm-2">
                             <button type="submit" class="btn btn-warning btn-block" style='border-radius:0%; position: absolute;
                             border-radius: 0%; top: 30px;' name="add_attendance" value="Filter"><i class="glyphicon glyphicon-ok-sign"></i> Filter
                             </button>
                        </div>

                    </div>                           
                    
                    <div id="calendar" class="full-calendar"></div>

                    </form>
                </div>
              </div>
            </div>
          </div>