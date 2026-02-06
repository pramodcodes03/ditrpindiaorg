<?php
include_once('database_results.class.php');
include_once('access.class.php');

class reports extends access
{
	//get total enquiries
	public function getTotalEnquiries($inst_id)
	{
		$res='';
		$sql = "SELECT COUNT(*) AS TOTAL_ENQUIRY FROM student_enquiry WHERE INSTITUTE_ID='$inst_id' AND DELETE_FLAG=0";
		$rec = parent::execQuery($sql);
		$data = $rec->fetch_assoc();
		$res = $data['TOTAL_ENQUIRY'];
		return $res;
	}
	// get total enquiries by course
	public function getDistinctCoursesEnquiry($inst_id,$datefrom, $dateto)
	{
		$ditrp=0; $nonditrp=0;
		$resArr	= array();
		$res	= array();
		$sql 	= "SELECT INSTRESTED_COURSE FROM student_enquiry WHERE INSTITUTE_ID='$inst_id' AND DELETE_FLAG=0 ";
		if($datefrom!='' && $dateto!='')
		{
			$sql .= " AND CREATED_ON BETWEEN '$datefrom' AND '$dateto'";
		}	
		
		$rec 	= parent::execQuery($sql);
		if($rec && $rec->num_rows>0)
		{
			while($data = $rec->fetch_assoc())
			{
				$instrCourseArr = json_decode($data['INSTRESTED_COURSE']);				
				foreach($instrCourseArr as $val){
					array_push($res,$val);
					///echo "Val:<br>$val";
				}			
			}	
			
			/* get types */
			foreach($res as $val)
			{
				$sql1 = "SELECT COURSE_TYPE FROM institute_courses WHERE INSTITUTE_COURSE_ID=$val";
				$res1 = parent::execQuery($sql1);
				if($res1!='' && $res1->num_rows>0)
				{
					while($data1 = $res1->fetch_assoc())
					{
						$type = $data1['COURSE_TYPE'];
						if($type==1) $ditrp++;
						else if($type==2) $nonditrp++;
					}
				}
			}
		
		}
		$resArr['DITRP'] = $ditrp;
		$resArr['NON-DITRP'] = $nonditrp;
		return $resArr;
	}
	//get total admissions
	public function getTotalAdmissions($inst_id)
	{
		$res='0';
		$sql = "SELECT COUNT(*) AS TOTAL_ADMISSION FROM student_course_details WHERE INSTITUTE_ID='$inst_id' AND DELETE_FLAG=0";
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data = $rec->fetch_assoc();
		$res = $data['TOTAL_ADMISSION'];
			}
		return $res;
	}
	//get total admissions course
	public function getTotalAdmissionsCourse($inst_id,$course,$datefrom,$dateto)
	{
		$res = '0';
		$sql = "SELECT COUNT(*) AS TOTAL_ADMISSION FROM student_course_details A LEFT JOIN institute_courses B ON B.INSTITUTE_COURSE_ID=A.INSTITUTE_COURSE_ID WHERE  B.COURSE_TYPE='$course' AND A.INSTITUTE_ID='$inst_id'";
		if($datefrom!='' && $dateto!='')
		{
			$sql .= " AND A.CREATED_ON BETWEEN '$datefrom' AND '$dateto'";
		}
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data= $rec->fetch_assoc();
		$res = $data['TOTAL_ADMISSION'];
			}
		return $res;
	}
	
	//get total fees collection
	public function getTotalFeesCollection($inst_id)
	{
		$res='0';
		$sql = "SELECT SUM(FEES_PAID) AS TOTAL_COLLECTION FROM student_payments WHERE INSTITUTE_ID='$inst_id' AND DELETE_FLAG=0";
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data = $rec->fetch_assoc();
		$res = $data['TOTAL_COLLECTION'];
			}
		return $res;
	}

	//get total fees collection
	public function getTotalFeesCollectionCourse($inst_id,$course)
	{
		$res='0';
		$sql = "SELECT SUM(A.FEES_PAID) AS TOTAL_FEES FROM student_payments A LEFT JOIN institute_courses B ON B.INSTITUTE_COURSE_ID=A.INSTITUTE_COURSE_ID WHERE A.INSTITUTE_ID='$inst_id' AND B.COURSE_TYPE='$course' AND A.DELETE_FLAG=0";
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data = $rec->fetch_assoc();
		$res = ($data['TOTAL_FEES']!='')?$data['TOTAL_FEES']:0;
			}
		return $res;
	}
	
	//get total fees business
	public function getTotalFeesBusinessCourse($inst_id,$course,$datefrom,$dateto)
	{
		$res='0';
		$sql = "SELECT SUM(A.TOTAL_COURSE_FEES) AS TOTAL_FEES FROM student_payments A LEFT JOIN institute_courses B ON B.INSTITUTE_COURSE_ID=A.INSTITUTE_COURSE_ID WHERE A.INSTITUTE_ID='$inst_id' AND B.COURSE_TYPE='$course' AND A.DELETE_FLAG=0";
		if($datefrom!='' && $dateto!='')
		{
			$sql .= " AND A.CREATED_ON BETWEEN '$datefrom' AND '$dateto'";
		}
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data = $rec->fetch_assoc();
		$res =  ($data['TOTAL_FEES']!='')?$data['TOTAL_FEES']:0;
			}
		return $res;
	}
	//get total admissions by status
	public function getTotalExam($inst_id,$status)
	{
		$res = '0';
		$sql = "SELECT COUNT(*) AS TOTAL_EXAM FROM student_course_details A LEFT JOIN institute_courses B ON B.INSTITUTE_COURSE_ID=A.INSTITUTE_COURSE_ID WHERE A.DELETE_FLAG=0 AND B.COURSE_TYPE=1 AND A.INSTITUTE_ID='$inst_id' AND A.EXAM_STATUS=$status";
		$rec = parent::execQuery($sql);
			if($rec && $rec->num_rows>0){
		$data= $rec->fetch_assoc();
		$res = $data['TOTAL_EXAM'];
			}
		return $res;
	}
	
	//get total certificates ordered
	public function getTotalCertificateOrder($inst_id)
	{
		$res = '0';
		$sql = "SELECT COUNT(*) AS TOTAL_ORDER FROM exam_result A LEFT JOIN institute_courses B ON A.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID WHERE A.INSTITUTE_ID='$inst_id' AND A.APPLY_FOR_CERTIFICATE=1";
		$rec = parent::execQuery($sql);
		if($rec && $rec->num_rows>0){
		    $data= $rec->fetch_assoc();
			 $res = $data['TOTAL_ORDER'];
		}
		return $res;
	}
	

}
?>