<?php
include('db.php');
if(!empty( $_REQUEST['insid'] ))
{
	$institute_id = $_REQUEST['insid'];
	
	$sqlFrom = "SELECT * FROM ri_institute WHERE id = '".$institute_id."'";
	$resultFrom = mysql_query($sqlFrom);
	$rowFrom = mysql_fetch_array($resultFrom);
	
	$sqlto = "SELECT * FROM ri_institute WHERE id != '".$institute_id."'";
	$resultto = mysql_query($sqlto);
	
	$sqlExam = "SELECT * FROM ri_exam WHERE institute_id = '".$institute_id."'";
	$resultExam = mysql_query($sqlExam);
	
	?>

<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Institute Name</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" readonly="readonly" value="<?=$rowFrom['institute_name'];?>" class="form-control col-md-7 col-xs-12" />
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Owner's Name </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" readonly="readonly" value="<?=$rowFrom['institute_owner_name'];?>" class="form-control col-md-7 col-xs-12" />
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Address </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <textarea class="form-control col-md-7 col-xs-12" readonly="readonly"><?=$rowFrom['institute_address'];?>
</textarea>
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Mobile </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input class="form-control col-md-7 col-xs-12" type="text" value="<?=$rowFrom['institute_mobile'];?>" readonly="readonly">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Email </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input class="form-control col-md-7 col-xs-12" type="text" value="<?=$rowFrom['institute_email'];?>" readonly="">
  </div>
</div>
<h4 style="background:#2A3F54; text-align:center; padding:5px; color:#fff; font-weight:bold; border-radius:5px">Copy to Institute</h4><br /><br />
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Institute Code<span class="required">*</span> </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <select name="to_ins" id="to_ins" class="form-control col-md-7 col-xs-12" required onchange="getExam(this.value)">
      <option value="">--Select Institute Code--</option>
      <?php
        while($rowto = mysql_fetch_array($resultto))
		{
			?>
      <option value="<?=$rowto['id']?>">
      <?=$rowto['institute_code']?>
      </option>
      <?php
		}
		?>
    </select>
  </div>
</div>
<div id="todiv">
</div>
<h4 style="background:#2A3F54; text-align:center; padding:5px; color:#fff; font-weight:bold; border-radius:5px">Exam List</h4>
<span><input type="checkbox" name="select-all" id="select-all" />&nbsp; Select All</span><br /><br />
<?php
	while($rowExam = mysql_fetch_array($resultExam))
	{
		?>
<!--<div class="item form-group">
  <div class="col-md-6 col-sm-6 col-xs-12">-->
    <input type="checkbox" name="exam_id[]" id="exam_id" value="<?=$rowExam['id']?>" />
    <?=$rowExam['course_name']?>
    <br />
  <!--</div>
</div>-->
<?php
	}
}
?>

<div class="ln_solid"></div>
<div class="form-group">
  <div class="col-md-6 col-md-offset-3">
    <input type="submit" name="submit" id="submit"  value="Copy" class="btn btn-success">
  </div>
  </form>
</div>
