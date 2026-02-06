<?php
include_once('database_results.class.php');
include_once('access.class.php');

class graphs extends access
{
	public function get_website_visits_monthly_graph()
	{
		$sql = "SELECT COUNT(VISIT_COUNT) AS VISITS, DATE_FORMAT(VISIT_TIME, '%b %Y') AS VISIT_DATE FROM website_visits GROUP BY MONTH(VISIT_TIME) ORDER BY MONTH(VISIT_TIME) DESC";
		$res = parent::execQuery($sql);
		$output = '';
		 while($data = $res->fetch_assoc())
		 {
			 $VISIT_DATE = $data['VISIT_DATE'];
			 $VISIT_COUNT = $data['VISITS'];
			 $output .= "{y:'$VISIT_DATE',a:$VISIT_COUNT},";
		 }
		 $output = rtrim($output, ",");
		 return $output;
	}
	public function get_website_visits_daily_graph()
	{
		$sql = "SELECT COUNT(VISIT_COUNT) AS VISITS, DATE_FORMAT(VISIT_TIME, '%b %d') AS VISIT_DATE FROM website_visits GROUP BY DAY(VISIT_TIME) ORDER BY MONTH(VISIT_TIME) DESC";
		$res = parent::execQuery($sql);
		$output = '';
		 while($data = $res->fetch_assoc())
		 {
			 $VISIT_DATE = $data['VISIT_DATE'];
			 $VISIT_COUNT = $data['VISITS'];
			 $output .= "{y:'$VISIT_DATE',a:$VISIT_COUNT},";
		 }
		 $output = rtrim($output, ",");
		 return $output;
	}
	public function get_ads_searched_monthly_graph()
	{
		$sql = "SELECT COUNT(*) AS VISITS, DATE_FORMAT(SEARCH_DATE, '%b %d') AS SEARCHED_ON FROM ad_post_search_tracking_master GROUP BY MONTH(SEARCH_DATE) ORDER BY MONTH(SEARCH_DATE) DESC";
		$res = parent::execQuery($sql);
		$output = '';
		 while($data = $res->fetch_assoc())
		 {
			 $VISIT_DATE = $data['SEARCHED_ON'];
			 $VISIT_COUNT = $data['VISITS'];
			 $output .= "{y:'$VISIT_DATE',a:$VISIT_COUNT},";
		 }
		 $output = rtrim($output, ",");
		 return $output;
	}
}
?>