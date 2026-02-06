 <?php

 $quebank_id = isset($_GET['id'])?$_GET['id']:0;

 $course_id = isset($_GET['course'])?$_GET['course']:'';

include_once('include/classes/exam.class.php');

			$exam = new exam();

			

 ?>

 <!-- Content Wrapper. Contains page content -->

  <div class<a href="page.php?page=-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        View Question Banks Details

        <small>View Question Banks Details</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-quebank"> Question Banks</a></li>

        <li class="active"> View Question Banks</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

			<?php

			if(isset($_SESSION['msg']))

			{

				$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';

				$msg_flag =$_SESSION['msg_flag'];

			?>

			<div class="row">

			<div class="col-sm-12">

			<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

                <h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>

				<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>

            </div>

			 </div>

			 </div>

			<?php

			unset($_SESSION['msg']);

			unset($_SESSION['msg_flag']);

			}

			?>

      <div class="row">

		

	

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">

           

			Course Name: <select class="col-sm-4 select2 pull-left" onchange="changeCourseQueBank(this.value)">

			 	<?php

			 	echo $db->MenuItemsDropdown ("exam_question_bank A","AICPE_COURSE_ID","COURSE_NAME","DISTINCT A.AICPE_COURSE_ID, get_course_title_modify(A.AICPE_COURSE_ID) AS COURSE_NAME",$course_id," ") ;

			 	?>

			 </select>

			 <?php if($db->permission('add_question')){ ?>

			 <a href="page.php?page=add-question&course=<?= $course_id ?>&quebank=<?= $quebank_id ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Question</a>

			 <?php } ?>

            </div>

            <!-- /.box-header -->

            <div class="box-body">

			 

			 <table class="table table-bordered list-exams data-tbl">

                <thead>

                <tr>

                  <th>Sr.</th>

				  <th>Quetion</th>  

				  <th>Option 1</th>                

                  <th>Option 2</th>

                  <th>Option 3</th>

                  <th>Option 4</th>

                  <th>Image</th>

                  <th>Correct Ans</th>               

                  <th>Status</th>

                  <th>Action</th>

                </tr>

                </thead>

                <tbody>

			<?php

			

			

			$res = $exam->view_quetion_bank(''," AND AICPE_COURSE_ID='$course_id'");

			$html='';

			if($res!='')

			{

				$srno=1;

				while($data = $res->fetch_assoc())

				{

					$QUESTION_ID 	= $data['QUESTION_ID'];

					$QUEBANK_ID		= $data['QUEBANK_ID'];					

					$IMAGE 			= $data['IMAGE'];	

					//$s = "abcde?cde?xtz?bb()*&b?";					

					$QUESTION 		= $data['QUESTION'];

					$QUESTION 		= preg_replace('/[^\x00-\x7f]/', '_', $QUESTION);

				

					$OPTION_A 		= $data['OPTION_A'];

					$OPTION_B 		= $data['OPTION_B'];

					$OPTION_C 		= $data['OPTION_C'];

					$OPTION_D 		= $data['OPTION_D'];

					$CORRECT_ANS 		= $data['CORRECT_ANS'];

					$imgPreview = '';

					if($IMAGE!='')

					{

						$path = QUEBANK_PATH.'/'.$QUEBANK_ID.'/images/'.$IMAGE;	

						if(file_exists($path))		

						$imgPreview = '<img src="'.$path.'" class="img img-responsive" style="height:35px; width:35px;" id="img_preview"/>';

					}

					$ACTIVE			= $data['ACTIVE'];

					$CREATED_BY 	= $data['CREATED_BY'];

					$action			= "";

					$rowclass		= ($ACTIVE==0)?'class="danger"':'';

					if($db->permission('add_que_bank')){

						$ACTIVE = ($ACTIVE==1)?'<small style="color:#3c763d"><i class="fa fa-check"></i></small>':'<small style="color:#f00"><i class="fa fa-times"></i></small>';

					}else{

						$ACTIVE = ($ACTIVE==1)?'<small style="color:#3c763d">Active</small></a>':'<small style="color:#f00"><i class="fa fa-times"></i></small>';

					}

					if($db->permission('update_question'))

					$action .= "<a href='page.php?page=edit-question&id=$QUESTION_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

				

					if($db->permission('delete_question'))

					$action .= "<a href='javascript:void(0)' onclick='deleteQuestion($QUESTION_ID)' class='btn btn-xs btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";

					

					 $html .=  " <tr id='row-".$QUESTION_ID."' $rowclass>

							<td>$srno</td>						

							<td width='50%'>$QUESTION</td>

							

							<td>$OPTION_A</td>

							<td>$OPTION_B</td>

							<td>$OPTION_C</td>

							<td>$OPTION_D</td> 

							<td  width='20%'>$imgPreview</td>

							<td width='20%'>$CORRECT_ANS</td>

							<td  width='20%'>$ACTIVE</td>

							<td width='30%'>$action</td>

                           </tr>";

				

					$srno++;

				}

				echo $html;

			}

			

			?>

                </tbody>

              

              </table>

            </div>

            <!-- /.box-body -->

          </div>

          <!-- /.box -->



     

          <!-- /.box -->

        </div>

        <!-- /.col -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>

  