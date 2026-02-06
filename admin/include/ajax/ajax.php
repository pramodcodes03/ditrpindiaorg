<?php
include("../classes/database_results.php");
include("../common/connection.php");

$database_resultsObj = new database_results();
$action = isset($_REQUEST['action']) ? htmlspecialchars($_REQUEST['action'], ENT_QUOTES, 'UTF-8') : '';

// Get testimonials
if ($action === 'show_testimonial') {
	$res = $database_resultsObj->get_testimonials();
	if ($res) {
		while ($test = $res->fetch_assoc()) { // Assuming $res is a MySQLi result
?>
			<h2><?php echo htmlspecialchars($test['TEST_NAME'], ENT_QUOTES, 'UTF-8'); ?></h2>
			<div><?php echo nl2br(htmlspecialchars($test['TEST'], ENT_QUOTES, 'UTF-8')); ?></div><br />
			<div><span class="pull-right"><?php echo htmlspecialchars($database_resultsObj->get_test_date(strtotime($test['CREATION_DATE'])), ENT_QUOTES, 'UTF-8'); ?></span></div>
			<hr />
		<?php
		}
	}
}

// Get the latest testimonial
if ($action === 'get_latest_testimonial') {
	$latest_test = $database_resultsObj->get_latest_testimonial();
	if (!empty($latest_test)) {
		?>
		<i><?php echo nl2br(htmlspecialchars($latest_test['TEST'], ENT_QUOTES, 'UTF-8')); ?></i>
		<p>
			<strong><?php echo htmlspecialchars($latest_test['TEST_NAME'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
			<span><?php echo htmlspecialchars($latest_test['TEST_COMPANY'], ENT_QUOTES, 'UTF-8'); ?></span>
		</p>
		<?php
	}
}

// Get all news
if ($action === 'get_news') {
	$res = $database_resultsObj->get_news();
	if ($res) {
		while ($news = $res->fetch_assoc()) {
		?>
			<div class="row">
				<div class="col-lg-9">
					<div class="pull-left" style="width:100px; height:100px; margin-right:5px;">
						<img src="tools/blogs_images/<?php echo htmlspecialchars($news['BLOG_IMAGE'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="img-rectangle img-responsive">
					</div>
					<strong><?php echo htmlspecialchars($news['BLOG_TITLE'], ENT_QUOTES, 'UTF-8'); ?></strong>
					<p><?php echo nl2br(htmlspecialchars($news['BLOG_CONTENT'], ENT_QUOTES, 'UTF-8')); ?></p>
				</div>
			</div>
			<hr />
		<?php
		}
	}
}

// Get the latest news
if ($action === 'get_latest_news') {
	$res = $database_resultsObj->get_latest_news_feed();
	if ($res) {
		while ($news = $res->fetch_assoc()) {
			$NEW_TITLE = htmlspecialchars($news['BLOG_TITLE'], ENT_QUOTES, 'UTF-8');
			$NEWS_IMAGE = "tools/blogs_images/" . htmlspecialchars($news['BLOG_IMAGE'], ENT_QUOTES, 'UTF-8');
			$news_content = strip_tags($news['BLOG_CONTENT']);
			$NEWS_CONTENT = htmlspecialchars(substr($news_content, 0, 110), ENT_QUOTES, 'UTF-8');
		?>
			<li class="mar-bottom clearfix">
				<div class="story-image pull-left">
					<img src="<?php echo $NEWS_IMAGE; ?>" alt="" class="img-responsive">
				</div>
				<div class="story-content pull-left">
					<strong><?php echo $NEW_TITLE; ?></strong>
					<p><?php echo $NEWS_CONTENT; ?></p>
				</div>
			</li>
<?php
		}
	}
}
?>