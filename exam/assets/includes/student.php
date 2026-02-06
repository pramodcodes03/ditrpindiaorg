<?php
date_default_timezone_set('Asia/Kolkata');
include('db.php');
if(!empty( $_REQUEST['id']))
{
	$sql = "SELECT * FROM ri_student WHERE id = '".$_REQUEST['id']."'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	$sqlExam = "SELECT * FROM ri_exam WHERE id = '".$row['exam_id']."'";
	$resExam = mysql_query($sqlExam);
	$rowExam = mysql_fetch_array($resExam);
	
	$perMarks = $rowExam['total_marks']/$rowExam['total_questions'];
	$perMarks = number_format($perMarks,2);
	$sqlCorrect = "SELECT * FROM ri_exam_attempt WHERE exam_id = '".$row['exam_id']."' AND student_id = '".$_REQUEST['id']."' AND institute_id = '".$row['institute_id']."' AND answer_status =1 AND session_id = '".$row['last_session']."'";
	$resCorrect = mysql_query($sqlCorrect);
	$numCorrect = mysql_num_rows($resCorrect);
	
	
	$sqlResult = "SELECT * FROM ri_exam_attempt WHERE exam_id = '".$row['exam_id']."' AND student_id = '".$_REQUEST['id']."' AND institute_id = '".$row['institute_id']."'  AND session_id = '".$row['last_session']."'";
	$resResult = mysql_query($sqlResult);
	
?>

<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="col-md-6"> <strong>Course Name:
    <?=$rowExam['course_name'];?>
    </strong> </div>
  <div class="col-md-6" style="text-align:right"> <strong>Exam Date: <?php echo date('D, jS F, Y  h:i:s A',strtotime($row['exam_date']));?></strong> </div>
</div>
<hr />
<table width="100%" cellpadding="5px" cellspacing="5px">
  <?php
$i=1;
while($rowResult = mysql_fetch_array($resResult))
{
?>
  <tr>
    <td colspan="3"><strong>Q.
      <?=$i;?>
      </strong>
      <?=$rowResult['question']?></td>
  </tr>
  <tr>
    <?php
	if($rowResult['option_a_chk']==1)
	{
		$answer = $rowResult['option_a'];
	}
	elseif($rowResult['option_b_chk']==1)
	{
		$answer = $rowResult['option_b'];
	}
	elseif($rowResult['option_c_chk']==1)
	{
		$answer = $rowResult['option_c'];
	}
	elseif($rowResult['option_d_chk']==1)
	{
		$answer = $rowResult['option_d'];
	}
	?>
    <td><strong>Answered:</strong>
      <?=$answer?></td>
    <td><strong>Correct Answer:</strong>
      <?=$rowResult[$rowResult['correct_ans']]?></td>
    <td><strong>Answer Status:</strong>
      <?php 
	if( $rowResult['answer_status'] ==1)
	{
		echo '<font color="#009933">Correct</font>';	
	}
	else
	{
		echo '<font color="#FF0000">Wrong</font>';	
	}
	?>
</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
 
  <?php
$i++;
}
?>
</table>
<hr />
<table width="100%" cellpadding="5px" cellspacing="5px">
<tr>
<td><strong>Marks Obtained:</strong> <?php $gotMarks = $numCorrect*$perMarks; echo $gotMarks = round($gotMarks);?>
<?php 
$gotPercent = ($gotMarks*100)/$rowExam['total_marks'];
if($gotPercent>=85)
{
	$grade = "A+ : Excellent";
	$result_status = '<font color="#00CC66">Passed</font>';  
}
elseif($gotPercent>=70 && $gotPercent<85)
{
  $grade = "A : Very Good"; 
  $result_status = '<font color="#00CC66">Passed</font>'; 
}
elseif($gotPercent>=55 && $gotPercent<70)
{
  $grade = "B : Good"; 
  $result_status = '<font color="#00CC66">Passed</font>'; 
}
elseif($gotPercent>=40 && $gotPercent<55)
{
  $grade = "C : Average"; 
  $result_status = '<font color="#00CC66">Passed</font>'; 
}
else
{
  $grade = ""; 
  $result_status = '<font color="#FF0000">Failed</font>'; 
}
?>
</td>
<td><strong>Grade:</strong> <?php echo $gotPercent.'% - '.$grade?></td>
<td><strong>Result Status:</strong> <?php echo $result_status?></td>
</tr>
</table>

<?php
}
?>
