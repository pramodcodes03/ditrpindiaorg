<?php

include_once('config.php');

	class connection{
		
		public function getDbConnection(){
		    
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
			if ($mysqli->connect_errno) {
    			//echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . 
				$mysqli->connect_error;
			}
			return $mysqli;
		}
	}

?>