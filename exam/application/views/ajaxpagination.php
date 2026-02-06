<div class="col-md-9 col-sm-9 col-xs-12 x_title_right"> 
  <!--<div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>-->
  
  <?php foreach($get_questions as $questions):?>
  <div class="row">
    <div class="col-md-12">
      <p style="width:100%" ><strong>Question:</strong></p>
      <div class="col-md-1">
        <div id="ajax_pagingsearc"  class="btn btn-default"> <?php echo $numbs ?> </div>
      </div>
      <div class="col-md-11">
        <?=$questions['question'];?>
      </div>
    </div>
  </div>
  <input type="hidden" name="question_id" id="question_id" value="<?=$questions['question_id'];?>">
  <input type="hidden" name="exam_attempt_id" id="exam_attempt_id" value="<?=$questions['id'];?>">
  <?php
                  if(!empty($questions['image']))
				  {
				  ?>
  <div class="row">
    <div class="col-md-12" > <a class="btn btn-default fancybox-effects-a" href="<?=base_url()?>assets/question/<?=$questions['image'];?>" style="margin-right:10px;float:right; border-color:#F58121; color:#F58121" >view image</a> </div>
  </div>
  <?php
				  }
				  else
				  {
				  ?>
  <div class="row">
    <div class="col-md-12" > &nbsp; </div>
  </div>
  <?php
				  }
				?>
  <p style="width:100%"><strong>Options:</strong></p>
   <?php
                if($questions['option_a']!='')
				{
				?>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-1">
        <input type="radio" id="radio1" name="ad_opt" value="option_a" <?php if($questions['option_a_chk']==1){ echo 'checked="checked"'; }?>>
        <label for="radio1">A</label>
      </div>
      <div class="col-md-11">
        <?=$questions['option_a'];?>
      </div>
    </div>
  </div>
  <?php
				}
  ?>
   <?php
                if($questions['option_b']!='')
				{
				?>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-1">
        <input type="radio" id="radio2" name="ad_opt" value="option_b" <?php if($questions['option_b_chk']==1){ echo 'checked="checked"'; }?>>
        <label for="radio2">B</label>
      </div>
      <div class="col-md-11">
        <?=$questions['option_b'];?>
      </div>
    </div>
  </div>
  <?php
				}
  ?>
   <?php
                if($questions['option_c']!='')
				{
				?>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-1">
        <input type="radio" id="radio3" name="ad_opt" value="option_c" <?php if($questions['option_c_chk']==1){ echo 'checked="checked"'; }?>>
        <label for="radio3">C</label>
      </div>
      <div class="col-md-11">
        <?=$questions['option_c'];?>
      </div>
    </div>
  </div>
  <?php
				}
  ?>
   <?php
                if($questions['option_d']!='')
				{
				?>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-1">
        <input type="radio" id="radio4" name="ad_opt" value="option_d" <?php if($questions['option_d_chk']==1){ echo 'checked="checked"'; }?>>
        <label for="radio4">D</label>
      </div>
      <div class="col-md-11">
        <?=$questions['option_d'];?>
      </div>
    </div>
  </div>
  <?php
				}
  ?>
  <?php endforeach;?>
</div>
<div class="col-md-3 col-sm-3 col-xs-12 bg-white">
  <div class="col-md-12 col-sm-12 col-xs-6">
    <div>
      <div class="" id="ajax_pagingsearc">
        <p><?php echo $sn_linking; ?></p>
      </div>
    </div>
    <div>
      <div class="" id="ajax_pagingsearc">
        <p><?php echo $p_linking;?></p>
      </div>
    </div>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-6">
    <div>
      <div class="" id="ajax_pagingsearc">
        <p><?php echo $skips; ?></p>
      </div>
    </div>
    <div>
      <div class="">
        <p><a href="#" class="push_button green" onclick="examOver()">SUBMIT</a></p>
      </div>
    </div>
  </div>
  <!--<div class="col-md-12 col-sm-12 col-xs-6">
    
    <div>
      <div class="">
        <p><a href="#" class="push_button red"  onclick="endExam()">END EXAM</a></p>
      </div>
    </div>
    <div> </div>
  </div>--> 
</div>
