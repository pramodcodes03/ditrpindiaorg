<?php 
 include ("../classes/database_results.php");
include ("../common/connection.php");
$database_resultsObj = new database_results();
$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

// get testimonials
if($action !='' && $action == 'show_testimonial')
{
	$res = $database_resultsObj-> get_testimonials();
	if($res)
	{
	while($test = mysql_fetch_array($res))
	{?>
		<h2><?php echo $test['TEST_NAME'];?></h2>
		<div><?php echo $test['TEST']; ?></div><br />
		<div><span class="pull-right"><?php echo $database_resultsObj->get_test_date(strtotime($test['CREATION_DATE']));?></span></div>

		<hr />
<?php }//while ends
	}//if ends

}

//get latest testimonials
// get testimonials
if($action !='' && $action == 'get_latest_testimonial')
{
	$latest_test = $database_resultsObj-> get_latest_testimonial();
	if(!empty($latest_test))
	{
	?>
	<i><?php echo $latest_test['TEST']; ?></i>
    <p><strong><?php echo $latest_test['TEST_NAME']; ?></strong><br> <span><?php echo $latest_test['TEST_COMPANY']; ?></span></p>

<?php 
	}//if ends

}

//get all news
if($action !='' && $action=='get_news')
{
	$res = $database_resultsObj->get_news();
	if($res)
	{
		while($news = mysql_fetch_array($res))
		{?>
			<div class="row">
				<div class="col-lg-9">
					<div class="pull-left"  style="width:100px; height:100px; margin-right:5px;">
						<img src="tools/blogs_images/<?php echo $news['BLOG_IMAGE']; ?>" alt="" class="img-rectangle img-responsive" >
					</div>

					<strong><?php echo $news['BLOG_TITLE']; ?></strong>
					<p><?php echo $news['BLOG_CONTENT']; ?></p>
					
				</div>
			</div><hr>
	<?php	}
	}
}

// get latest news
if($action !='' && $action=='get_latest_news')
{
	$res = $database_resultsObj->get_latest_news_feed();
	if($res)
	{
		while($news = mysql_fetch_array($res))
		{
			$NEW_TITLE = $news['BLOG_TITLE'];
			$NEWS_IMAGE = "tools/blogs_images/".$news['BLOG_IMAGE'];
			$news_content = strip_tags($news['BLOG_CONTENT']);
			$NEWS_CONTENT = substr($news_content,0,110);
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
	<?php	}
	}
}

?>