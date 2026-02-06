<?php
if(!empty($_REQUEST['type']))
{
	 $type= $_REQUEST['type'];	
	
	if($type=='institute')
	{
		?>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                  <input type="text" id="institute_code" placeholder="Enter Institute Code" name="institute_code" value="" class="form-control login-field" required="required">
            </div>
            <span class="help-block has-error" data-error='0' id="femail-error"></span>
        </div>
        <?php
	}
	else
	{
		return FALSE;	
	}
}
?>