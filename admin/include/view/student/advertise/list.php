<?php
	$user_id= $_SESSION['user_id'];
	$inst_id = $db->get_student_institute_id($user_id);
?>

<div class="content-wrapper">
  <div class="col-lg-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Advertise Section </h4> 
        <div class="row">
          <?php
              include_once('include/classes/tools.class.php');
              $tools = new tools();
              $res = $tools->list_advertise(''," AND inst_id = $inst_id ");
              if($res!='')
              {
                $srno=1;
                while($data = $res->fetch_assoc())
                {                
                  extract($data); 
                  $photo ='';
                  $photo = IMS_ADVERTISE_PATH.'/'.$id.'/'.$image;

          ?>

          <div class="col-md-4 grid-margin stretch-card">       
            <div class="card">
            <a href="<?= $link ?>" target="_blank"> <h4 class="location font-weight-normal" style="text-transform: capitalize;font-weight: 900 !important;"><?= $name ?></h4></a>    
              <div class="card-people mt-auto">
                <a href="<?= $link ?>" target="_blank"> <img src="<?= $photo ?>" alt="<?= $name ?>" style="width: 250px; height: 250px;">  </a>            
              </div>
            </div>
          </div>

          <?php
                }
              }      
          ?> 
          </div>
        </div>
      </div>
    </div>
  </div>