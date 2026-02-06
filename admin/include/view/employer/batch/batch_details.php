<?php
    include_once('include/classes/student.class.php');
    $student = new student();
    $user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
    $user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
    
    $errors = array();
    $batch_id = $db->test(isset($_REQUEST['batch_id'])?$_REQUEST['batch_id']:'');
    $cond = '';
    
    if($batch_id=='') $errors['batch_id'] = 'Please select batch';	
    if($batch_id!='') $cond .= " AND A.id='$batch_id'";

?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Batch Report </h4>  
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

                      <?php 
                      
                        $sr=1; 
                        $res = $student->list_batch_report('',$user_id,'');
                      ?>           
                    <div class="table-responsive pt-3">
                        <table class="table">
                        <thead>
                            <tr class="tableRowColor">
                                <th>S/N</th> 
                                <th>Batch Name</th>
                                <th>Batch Limit</th>
                                <th>Current Admission</th>
                                <th>Remaining Admission</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php			
                                if($res!='')
                                {
                                    $srno=1;
                                    while($data = $res->fetch_assoc())
                                    {				
                                        extract($data);
                                       
                                        $current = 0;
                                        $remaining = 0;
                                        $current = $student->batchStudentCounts($id,'');
                                        $current =  $current['TOTAL'];
                                        $remaining = $numberofstudent - $current;
                                        
                                        echo " <tr id='row-$id'>
                                                <td> $srno </td>
                                                <td>$batch_name</td>
                                                <td>$numberofstudent</td>
                                                <td>$current</td>
                                                 <td>$remaining</td>
                                            </tr>";						
                                        $srno++;
                                    }
                                }
                           
                                    
                            ?>                                
                        </tbody>
                        </table>
                    </div>
              
                  
                    </form>
                </div>
              </div>
            </div>
          </div>

<script type="text/javascript">
    function selectAll(form1) {
    
    var check = document.getElementsByName("group0"),
    radios = document.form1.elements;
    
    //If the first radio is checked
        if (check[0].checked) {
    
            for( i = 0; i < radios.length; i++ ) {
                
        //And the elements are radios
                if( radios[i].type == "radio" ) {
            
            //And the radio elements's value are 1
                    if (radios[i].value == 1 ) {
            //Check all radio elements with value = 1
                        radios[i].checked = true;
                    }
            
                }//if
        
            }//for
        
    //If the second radio is checked
        } else {
        
            for( i = 0; i < radios.length; i++ ) {
                
        //And the elements are radios
                if( radios[i].type == "radio" ) {
            
            //And the radio elements's value are 0
                    if (radios[i].value == 0 ) {
    
            //Check all radio elements with value = 0
                        radios[i].checked = true;
    
                    }
            
                }//if
        
            }//for
        
        };//if
    return null;
    }

    function changeBatch(batch){
        alert(batch);

    }
    function changeDate(date){
        alert(date);

    }

    function refreshPage(){
        location.reload(true);
    }
</script>