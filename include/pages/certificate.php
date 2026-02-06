	<div class="banner1">
		<div class="w3_agileits_service_banner_info">
			<h2>Courses</h2>
		</div>
	</div>
<div class="contact">
  <div class="container">
	<h3 class="w3l_header w3_agileits_header">Our <span>Courses</span></h3>
	<div class="agileits_banner_bottom_grids courses">
				 <?php
			$res = $db->list_courses('','','');
			if($res!='')
			{
				$srno=1;
				while($data = $res->fetch_assoc())
				{
					$COURSE_ID 		= $data['COURSE_ID'];
					$COURSE_CODE 	= $data['COURSE_CODE'];
					$COURSE_DURATION= $data['COURSE_DURATION'];
					$COURSE_NAME 	= $data['COURSE_NAME'];
					$COURSE_FEES 	= $data['COURSE_FEES'];
					$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
					$ACTIVE			= $data['ACTIVE'];
					$CREATED_BY 	= $data['CREATED_BY'];
					$CREATED_ON 	= $data['CREATED_ON'];
					$COURSE_NAME_MODIFY 	= $data['COURSE_NAME_MODIFY'];
					$COURSE_IMAGE 	= $data['COURSE_IMAGE'];
					
					$course_img_path = HTTP_HOST.'/resources/img/poetry.jpg';
					if($COURSE_IMAGE!='')
					$course_img_path = HTTP_HOST.'/'.COURSE_MATERIAL_PATH.'/'.$COURSE_ID.'/thumb/'.$COURSE_IMAGE;
				
					$course_title = $COURSE_NAME;
					if($COURSE_AWARD_NAME!='')
					$course_title = $COURSE_AWARD_NAME .' IN '.$COURSE_NAME;
					$url_course_name = $db->to_prety_url($course_title);				
					$course_link = HTTP_HOST."/courses-grid/$COURSE_ID/$url_course_name";					
					
					?>	
					<div class="col-md-3 agileits_banner_bottom_grid">
						<div class="hovereffect w3ls_banner_bottom_grid" style="height:167px;">
							<img src="<?= $course_img_path ?>" alt=" " class="img-responsive" />
							<div class="overlay">
							   <h4><a href="<?= $course_link ?>" style="color:#fff;"><?= $access->readmore($course_title,40) ?></a></h4>
							   
							</div>
						</div>
					</div>
					<?php				
					$srno++;
				}
			}			
			?>  
				
				<div class="clearfix"> </div>
				
			</div>
	
		<br>
	
		
		</div>	
	</div>	
	<!--//gallery-->