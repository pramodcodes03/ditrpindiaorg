<!-- Breadcrumbs Start -->
<?php
$res = $websiteManage->list_headimages('', '');
if ($res != '') {
	while ($data = $res->fetch_assoc()) {
		extract($data);
		$image = 'resources/default_images/about_default.jpg';
		if ($jobs != '')
			$image     = BANNERS_PATH . '/' . $id . '/' . $jobs;
?>
		<div class="rs-breadcrumbs bg7 breadcrumbs-overlay" style="background-image: url(<?= $image ?>);">
			<div class="breadcrumbs-inner">
				<div class="container">
					<div class="row">
						<div class="col-md-12 text-center">
							<h1 class="page-title">JOB UPDATES</h1>
							<ul>
								<li>
									<a class="active" href="index.php">Home</a>
								</li>
								<li>Job Updates</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
?>
<!-- Breadcrumbs End -->

<div class="rs-events-2 sec-spacer">
	<div class="container">
		<div class="row space-bt30">
			<?php
			$res = $websiteManage->list_jobpost('', '');
			if ($res != '') {
				while ($data = $res->fetch_assoc()) {
					extract($data);
					$job_img = 'resources/default_images/job_default.jpg';
					if ($image != '')
						$job_img = JOBPOST_PATH . '/' . $id . '/' . $image;
			?>
					<div class="col-lg-4 col-md-12 md-mb-30">
						<div class="event-item">
							<div class="row col-md-12 rs-vertical-middle">
								<div class="col-md-12">
									<div class="event-img">
										<img src="<?= $job_img ?>" alt="<?= $title ?>" style="height:300px;" />
										<a class="image-link" href="job-details&id=<?= $id ?>" title="<?= $title ?>">
											<i class="fa fa-link"></i>
										</a>
									</div>
								</div>
								<div class="col-md-12">
									<div class="event-content">
										<div class="event-meta">
											<div class="event-date">
												<i class="fa fa-calendar"></i>
												<span>Start Date : <?= $post_date ?></span>
											</div>
											<div class="event-date pull-right">
												<i class="fa fa-calendar"></i>
												<span>Last Date : <?= $last_date ?></span>
											</div>
										</div>
										<br />
										<h3 class="event-title"><a href="job-details&id=<?= $id ?>"><?= $title ?></a></h3>

										<div class="event-meta">
											<div class="event-location">
												<span>Job Code : <?= $job_code ?></span>
											</div>
										</div>

										<div class="event-btn">
											<a class="primary-btn" href="" data-toggle="modal" data-target="#jobApply">Apply Now</a>
										</div>
									</div>
								</div>
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