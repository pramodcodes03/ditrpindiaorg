<?php
include('db.php');
if(!empty( $_REQUEST['examid']))
{
	$sql1 = "SELECT * FROM ri_institute WHERE id = '".$_REQUEST['examid']."'";
	$result1 = mysql_query($sql1);
	$num1 = mysql_num_rows($result1);
	$rowtoins = mysql_fetch_array($result1);
	?>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Institute Name</label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" readonly="readonly" value="<?=$rowtoins['institute_name'];?>" class="form-control col-md-7 col-xs-12" />
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Owner's Name </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input type="text" readonly="readonly" value="<?=$rowtoins['institute_owner_name'];?>" class="form-control col-md-7 col-xs-12" />
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Address </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <textarea class="form-control col-md-7 col-xs-12" readonly="readonly"><?=$rowtoins['institute_address'];?>
</textarea>
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Mobile </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input class="form-control col-md-7 col-xs-12" type="text" value="<?=$rowtoins['institute_mobile'];?>" readonly="readonly">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Email </label>
  <div class="col-md-6 col-sm-6 col-xs-12">
    <input class="form-control col-md-7 col-xs-12" type="text" value="<?=$rowtoins['institute_email'];?>" readonly="">
  </div>
</div>
<?php

}
?>
