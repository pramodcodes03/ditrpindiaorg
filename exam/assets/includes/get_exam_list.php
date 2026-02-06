<?php
include('db.php');
if(!empty( $_REQUEST['examid']))
{
	$sql = "SELECT * FROM ri_exam WHERE institute_id = '".$_REQUEST['examid']."'";
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);
	
	if($num>0)
	{
		?>
        <select name="exam_id" id="exam_id" class="form-control col-md-7 col-xs-12" required>
        <option value="">--Select Course--</option>
        <?php
		while($row = mysql_fetch_array($result))
		{
	?>
    <option value="<?=$row['id'];?>"><?=$row['course_name'];?></option>
    <?php
		}
		?>
        </select>
        <?php
	}
	else
	{
		echo "<font color='#FF0000'>No Exam under this Institute</font>";
		?>
        
        <select name="exam_id" id="exam_id" class="form-control col-md-7 col-xs-12" required>
        <option value="">--Select Course--</option>
        </select>
        <?php
	}
}
?>
