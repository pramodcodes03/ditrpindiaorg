<?php
class database_results
{

	function connect_selct_db($host,$username,$password,$database_name){
		$mysql_host = ($host != "") ? $host : "localhost";
		$mysql_user = ($username != "") ? $username : "root";
		$mysql_pwd = ($password != "") ? $password : "";
		$database_name = ($database_name != "") ? $database_name : "erp";
		
		$link = mysql_connect($mysql_host,$mysql_user,$mysql_pwd) or die("Unable to connect to Host");
		mysql_select_db($database_name, $link);
		return $link;
    }
	


	function insertData($tableName,$tabFields,$insertValues) // function to insert records in table 
	{
		$iQuery = "insert into"." ".$tableName." ".$tabFields."  "."values"." ".$insertValues;
		return $iQuery; 
	}
	function updateData($tableName,$setValues,$whereClause) // function to update records in table 
	{
		$uQuery = "update"." ".$tableName." "."set"." ".$setValues."  ".$whereClause;
		return $uQuery; 
	}
	function selectData ($selVals,$tableName,$whereClause)
	{
		$selQry = "select"." ".$selVals." "."from"." ".$tableName." ".$whereClause;
		return $selQry;
	}
	function deleteData ($tableName,$whereClause)
	{
		$uQuery = "delete from "." ".$tableName." ".$whereClause;
		return $uQuery; 
	}

	function execQuery( $query ) // function to execute queries
	{   
		$exexQry = mysql_query( $query);
		
		return $exexQry;
	}
	
	function MenuItemsDropdown ($tableName,$value,$option,$selVals,$selected,$whereClause) // to list all existing 
		{
			$selVals 		= "$selVals";
			$whereClause	= "$whereClause";
		    $selectAM 		= $this -> selectData($selVals,$tableName,$whereClause);
			$execAM 		= $this -> execQuery( $selectAM );
			
		    $dropdown = '';
			$dropdown = '<option value="">--Select--</option>';
			if ( mysql_num_rows ($execAM) > 0 )
			{	
			while ( $Row = mysql_fetch_array($execAM) )
				{
			 $id 	 	= $Row[''.$value.''];
			 $name 		= $Row[''.$option.''];
				if ( $id == $selected)
					{
				$dropdown .= '<option value="'.$Row[''.$value.''].'" selected>'.$Row[''.$option.''].'</option>';
					}	
				else
					{
				$dropdown .= '<option value="'.$Row[''.$value.''].'" >'.$Row[''.$option.''].'</option>';
					}
				}
			}
			echo $dropdown;
		}
		
	function get_user_name($user_id)
		{
		
			$tableName 		= 'app_users';
			$selVals 		= '*';
			$whereClause	= 'where USER_ID = '."'$user_id'";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 			= $this ->execQuery( $select );
			
			if( !$exec )
			{
				echo'select:fail';
			}
			else
			{
				$result = mysql_fetch_assoc($exec); 
			}
			return $result['USER_NAME'];
		
		
		}		
		
		function ordinal_suffix($day)
		{
			$day = $day % 100; // protect against large numbers
			if($day < 11 || $day > 13){
				 switch($day % 10){
					case 1: return 'st';
					case 2: return 'nd';
					case 3: return 'rd';
				}
			}
			return 'th';
		}
		function get_test_date($date)
		{
			$day_name = date('l',$date);
			$day = date('d',$date);
			$month_name = date('F',$date);
			$year = date('Y',$date);
			
			$date_string = $day_name." ";
			$date_string.= $day.$this->ordinal_suffix($day)." ";
			$date_string.=$month_name." ";
			$date_string.= $year;
			return $date_string;
		}

		function get_page_content($page_link)
		{
			$tableName 		= 'page';
			$selVals 		= '*';
			$whereClause	= "where PAGE_LINK='".$page_link."' AND PAGE_STATUS=1";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			while($data = mysql_fetch_array($exec)){
				$res[]= array("PAGE_DATA"=>$data['PAGE_DATA'],
									"PAGE_LINK"=>$data['PAGE_LINK']);
			}
			 if(isset($res) || !empty($res)){
					foreach($res as $data)
					{
						echo $data['PAGE_DATA'];
					}
			}else	echo "";
		}
		function get_testimonials()
		{
			$tableName 		= 'testimonials_master';
			$selVals 		= 'TEST_NAME, TEST, CREATION_DATE';
			$whereClause	= "where TEST_STATUS=1 ORDER BY CREATION_DATE DESC";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			return $exec;
		}
		
		function get_latest_testimonial()
		{
			$tableName 		= 'testimonials_master';
			$selVals 		= 'TEST_NAME, TEST,TEST_COMPANY, CREATION_DATE';
			$whereClause	= "where TEST_STATUS=1 ORDER BY CREATION_DATE DESC LIMIT 0,1";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			if(!$exec) return false;
			$res = mysql_fetch_array($exec);
			return $res;
		}
		
		
		function get_news()
		{
			$tableName 		= 'blogs_master';
			$selVals 		= 'BLOG_TITLE, BLOG_CONTENT, BLOG_IMAGE, BLOG_CREATION_DATE';
			$whereClause	= "where STATUS=1 ORDER BY BLOG_CREATION_DATE DESC";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			return $exec;
		}
		function get_latest_news_feed()
		{
			$tableName 		= 'blogs_master';
			$selVals 		= 'BLOG_TITLE, BLOG_CONTENT, BLOG_IMAGE, BLOG_CREATION_DATE';
			$whereClause	= "where STATUS=1 ORDER BY BLOG_CREATION_DATE DESC LIMIT 0, 2";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			return $exec;
		}
		
		function get_courses()
		{
			$tableName 		= 'courses_master';
			$selVals 		= 'COURSE_NAME, COURSE_DESCRIPTION, COURSE_START_DATE, COURSE_START_TIME,COURSE_END_TIME,COURSE_FEES,COURSE_LOCATION,COURSE_SEATS_REMAINING,COURSE_PAYPAL_CODE';
			$whereClause	= "where STATUS=1";
		    $select 		= $this ->selectData($selVals,$tableName,$whereClause);
			$exec 		= $this ->execQuery( $select );
			return $exec;
		}
		
}	 

?>