 <?php
	include_once('include/classes/student.class.php');
	$student = new student();
	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
    $session_id = $db->test(isset($_GET['id'])?$_GET['id']:'');
    
	$res = $student->list_exam_demo_paper_student($user_id,$session_id);
 ?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title">View Demo Paper </h4> 
	                     
	      <div class="table-responsive pt-3">
	        <table id="order-listing" class="table">
	          <thead>
	            <tr>
	              <th>S/N</th>				  
	              <th>Question</th>
				  <th>Option A</th>
	              <th>Option B</th>
	              <th>Option C</th>
	              <th>Option D</th>
				  <th>Correct Answer</th>
				  <th>Choose Answer</th>
				  <th>Answer Status</th>
	            </tr>
	          </thead>
	          <tbody>
	         <?php						
				if($res!='')
				{
					$srno=1;
						while($data = $res->fetch_assoc())
						{	 
						  
							//print_r($data);			
							extract($data);                    
                           $chooseAnswer = '';
                           if($option_a_chk == '1'){
                               $chooseAnswer = 'option_a';
                           }
                           if($option_b_chk == '1'){
                               $chooseAnswer = 'option_b';
                           }
                           if($option_c_chk == '1'){
                               $chooseAnswer = 'option_c';
                           }
                           if($option_d_chk == '1'){
                               $chooseAnswer = 'option_d';
                           }
                          
                           if($answer_status == '1'){
                               $answer_status = '<span style="color:green"><strong>Correct</strong></span>';
                               $backClr = " style='background-color:aliceblue;'";
                           }
                           if($answer_status == '0'){
                               $answer_status = '<span style="color:red"><strong>In-Correct</strong></span>';
                               $backClr = " style='background-color:antiquewhite;'";
                           }
                          
							echo " <tr id='row-$id' $backClr>
									<td>$srno</td>
									<td>$question</td>	
									<td>$option_a</td>
									<td>$option_b</td>
									<td>$option_c</td>
									<td>$option_d</td>
									<td>$correct_ans</td>
									<td>$chooseAnswer</td>
									<td>$answer_status</td>
		                           </tr>";						
							$srno++;
						}
					}			
				?>                            
	          </tbody>
	        </table>
	      </div>
	    </div>
	  </div>
	</div>
</div>