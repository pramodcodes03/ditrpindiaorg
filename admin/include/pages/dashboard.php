 <?php 
    $_SESSION['web'] = '1';
    include_once('include/classes/websiteManage.class.php');
    $websiteManage = new websiteManage();

    $res = $websiteManage->list_student_website_enquiry('','');
    $enquiry_count = ($res!='')?$res->num_rows:0;

    $res1 = $websiteManage->list_student_admission('','');
    $admission_count = ($res1!='')?$res1->num_rows:0;

    $res2 = $websiteManage->list_job_apply_student('','');
    $job_apply_count = ($res2!='')?$res2->num_rows:0;

    $res3 = $websiteManage->list_jobpost('','');
    $job_count = ($res3!='')?$res3->num_rows:0;

    $res4 = $websiteManage->list_achievers('','');
    $achievers_count = ($res4!='')?$res4->num_rows:0;

    $res5 = $websiteManage->list_blogs('','');
    $blogs_count = ($res5!='')?$res5->num_rows:0;

    $res6 = $websiteManage->list_team('','');
    $team_count = ($res6!='')?$res6->num_rows:0;

    $res7 = $websiteManage->list_testimonial('','');
    $testimonial_count = ($res7!='')?$res7->num_rows:0;


?>

<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">

        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome, <?= $_SESSION['user_fullname']; ?></h3>
        </div>
        
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin transparent">
     <div class="row">      
         <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4 textColor">Total Jobs Apply</p>
              <p class="fs-30 mb-2"><?= $job_apply_count ?></p>             
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4 textColor">Total Jobs</p>
              <p class="fs-30 mb-2"><?= $job_count ?></p>            
            </div>
          </div>
        </div>

    
        <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4 textColor">Total Achievers</p>
              <p class="fs-30 mb-2"><?= $achievers_count ?></p>             
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4 textColor">Total Blogs</p>
              <p class="fs-30 mb-2"><?= $blogs_count ?></p>
            </div>
          </div>
        </div>
         <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4 textColor">Total Team Members</p>
              <p class="fs-30 mb-2"><?= $team_count ?></p>            
            </div>
          </div>
        </div>
         <div class="col-md-3 mb-4 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4 textColor">Total Testimonial</p>
              <p class="fs-30 mb-2"><?= $testimonial_count ?></p>             
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
   
<!-- content-wrapper ends -->