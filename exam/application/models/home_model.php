<?php
class Home_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		//$this->output->enable_profiler(TRUE);
	}
	public function set_login()
	{
		if(!empty($_POST['login']))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('pwd');
			
			$query = $this->db->get_where('admin', array('user_name' => $username,'pwd' => $password, 'status' => 1));

			if ($query->num_rows() > 0)
			{
				$user_id = $query->row_array();
				
				$queryS   	  = $this->db->select('cdate')->from('admin')->where('id', $user_id['id']);
				$queryS   	  = $this->db->get();	
				$row 	  	  = $queryS->row();
				$date 	  	  = $row->cdate;
				
				$this->db->set('cdate', 'NOW()',FALSE);
				$this->db->set('last_login',$date);  
				$this->db->where('id', $user_id['id']);
				$this->db->update('admin');
				$this->session->set_userdata('user_id', $user_id['id']);
				redirect(base_url().'admin/dashboard');
				return $query->row_array();
			}
			else
			{
				$query1 = $this->db->get_where('institute', array('institute_code' => $username,'institute_password' => $password, 'status' => 1));
				if ($query1->num_rows() > 0)
				{ 
					$user_id1 = $query1->row_array();

					$this->db->set('last_login', 'NOW()',FALSE);
					$this->db->where('id', $user_id1['id']);
					$this->db->update('institute');
					$this->session->set_userdata('institute_id', $user_id1['id']);
					redirect(base_url().'institute/dashboard');
					return $query1->row_array();
				}
				else
				{
					$this->session->set_userdata('msg', '<font color="#FF0000">Invalid username or password!</font>');
					redirect(base_url());
				}
			}
		}
		else
		{
			return FALSE;
		}
	}
	public function site_settings()
	{
		$query = $this->db->get_where('site_settings', array('id' => 1));
		return $query->row_array();
	}
	/*public function admin_detail()
	{
		$query = $this->db->get_where('admin', array('id' => 1));
		return $query->row_array();
	}*/
	public function get_exam_terms()
	{
		$query = $this->db->get_where('exam_terms', array('id' => 1));
		return $query->row_array();
	}
/* 	public function enter_student_no()
	{
		if(!empty($_POST['student_id']))
		{
			$student_id = trim($_POST['student_id']);
			$query = $this->db->get_where('student', array('student_id' => $student_id));
			if ($query->num_rows() > 0)
			{
				$result  = $query->row_array();
				$exam_id =  $result['exam_id'];
				
				$queryS   	= $this->db->select('exam_status')->from('exam')->where('id', $exam_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$exam_status= $row->exam_status;
				
				
				if($exam_status==1)
				{
					$query1 = $this->db->get_where('student', array('student_id' => $student_id, 'status' => 1));
					if ($query1->num_rows() > 0)
					{
						$res_sess = random_string('alnum',16);
						$this->session->set_userdata('student_id', $student_id);
						$this->session->set_userdata('session_id', $res_sess);
						redirect(base_url().'terms');
					}
					else
					{
						$this->session->set_userdata('msg', '<font color="#FF0000">Your don not have permission to attened this exam!</font>');
						redirect(base_url());
					}
				}
				else
				{
					$this->session->set_userdata('msg', '<font color="#FF0000">Exam Status is not ready!</font>');
					redirect(base_url());
				}
			}
			else
			{
				$this->session->set_userdata('msg', '<font color="#FF0000">Entered student ID does not exist!</font>');
				redirect(base_url());	
			}
		}
	} */	
	public function enter_student_no()
	{
		if(!empty($_REQUEST['student_course_id']))
		{			
			$student_course_id = base64_decode(trim($_REQUEST['student_course_id']));
			//$subject_id = base64_decode(trim($_REQUEST['subject_id']));
			 //$student_course_id = trim($_REQUEST['student_course_id']);
			$query = $this->db->get_where('student_course_details', array('STUD_COURSE_DETAIL_ID' => $student_course_id));
			//echo $this->db->last_query();
			//var_dump( $query );
			if ($query->num_rows() > 0)
			{				
				$result  		= $query->row_array();
				$STUD_COURSE_DETAIL_ID 	= $result['STUD_COURSE_DETAIL_ID'];							
				$EXAM_STATUS 	= $result['EXAM_STATUS'];							
				$STUDENT_ID 	= $result['STUDENT_ID'];							
				$INSTITUTE_ID 	= $result['INSTITUTE_ID'];	

				
				//Not Applied
				switch($EXAM_STATUS)
				{
					//not applied
					case(1):
						$this->session->set_userdata('msg', '<font color="#FF0000">Exam Status is not ready!</font>'.$EXAM_STATUS);
					//redirect(base_url());
					break;
					
					//applied
					case(2):						
						$res_sess = random_string('alnum',16);
						$this->session->set_userdata('exam_type', $_REQUEST['exam']);
						$this->session->set_userdata('student_course_detail_id', $STUD_COURSE_DETAIL_ID);
						$this->session->set_userdata('multi_subject_id', $_REQUEST['subject_id']);
						$this->session->set_userdata('student_id', $STUDENT_ID);
						$this->session->set_userdata('institute_id', $INSTITUTE_ID);
						$this->session->set_userdata('session_id', $res_sess);
						$uniqueId = uniqid(time()+$STUD_COURSE_DETAIL_ID, TRUE);
						$this->session->set_userdata("my_session_id", md5($uniqueId));
						redirect(base_url().'terms');						
						break;
					//appeared
					case(3):
						$this->session->set_userdata('msg', '<font color="#FF0000">Exam is already completed!</font>');
					//redirect(base_url());
					break;
				}			
			}
			else
			{
				$this->session->set_userdata('msg', '<font color="#FF0000">Entered student ID does not exist!</font>');
				//redirect(base_url());	
			}
		}
	}
	public function generate_opt()
	{
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$session_id 	= $this->session->userdata('my_session_id');
		$student_id 	= $this->session->userdata('student_id');	

		
		//$queryOpt = $this->db->get_where('student_course_details', array('STUDENT_ID' => $std_id, 'LAST_SESSION' => $last_session, 'EXAM_ATTEMPT' =>0));
		/*		
		if ($EXAM_ATTEMPT==0)
		{
			$this->session->unset_userdata('session_id');
			$this->session->set_userdata('session_id', $last_session);
			//redirect(base_url().'otp');
			
		}
		*/
		if(!empty($_POST['terms']))
		{		
			$this->session->set_userdata('lang1',$_POST['lang1']);

			$querySess   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
			$querySess   	= $this->db->get();	
			//echo $this->db->last_query();
			$rowSess 	  	= $querySess->row();
			$INSTITUTE_ID 	= $rowSess->INSTITUTE_ID;
			$INSTITUTE_COURSE_ID= $rowSess->INSTITUTE_COURSE_ID;
			$courseinfo		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
			$exam_id 		= $courseinfo['COURSE_ID'];
			$last_session	= $rowSess->LAST_SESSION;
			//$last_session	= $session_id;
			$std_id			= $rowSess->STUDENT_ID;
			$EXAM_ATTEMPT	= $rowSess->EXAM_ATTEMPT;
			
			$queryS   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUDENT_NAME,get_stud_photo(STUDENT_ID) AS STUDENT_PHOTO')->from('student_details')->where('STUDENT_ID', $student_id);
			$queryS   	= $this->db->get();
//echo $this->db->last_query();			
			$row 	  	= $queryS->row();
			
			$id 			= $row->STUDENT_ID;
			$mobile 		= $row->STUDENT_MOBILE;
			$student_email	= $row->STUDENT_EMAIL;
			$full_name 		= $row->STUDENT_NAME;
			//$exam_id	 	= $row->exam_id;
			$INSTITUTE_ID 	= $row->INSTITUTE_ID;
			$gender		 	= $row->STUDENT_GENDER;
			$std_image	 	= $row->STUDENT_PHOTO;
			
			if($gender=='male')
			{
				$gender = 'Male';
			}
			else
			{
				$gender = 'Female';	
			}
			
			$queryI   	= $this->db->select('*')->from('institute_details')->where('INSTITUTE_ID', $INSTITUTE_ID);
			$queryI   	= $this->db->get();	
			$rowI 	  	= $queryI->row();
			
			$institute_email	= $rowI->EMAIL;
			$institute_name	  	= $rowI->INSTITUTE_NAME;
			$institute_mobile 	= $rowI->MOBILE;
			
			$queryExam   	= $this->db->select('COURSE_NAME')->from('courses')->where('COURSE_ID', $exam_id);
			$queryExam   	= $this->db->get();	
			$rowExam 	  	= $queryExam->row();
			$course_name	= $rowExam->COURSE_NAME;
			
			$ExmTrm   		= $this->db->select('pcontent')->from('exam_terms')->where('id', 1);
			$ExmTrm		   	= $this->db->get();	
			$rowExmTrm 	  	= $ExmTrm->row();
			$pcontent		= $rowExmTrm->pcontent;
			
			//$chars = '0123456789';
			//$otp = substr( str_shuffle( $chars ), 0, $length );
		  //$exam_secret_code = substr( str_shuffle( $chars ), 0, $length );
		
			$otp = random_string('numeric', 8);
			$exam_secret_code = random_string('numeric',8);
			$mails = TRUE;
			
			if($mails==TRUE)
			{
			
				$mail_institute = TRUE;	
				$ip_address	= $this->input->ip_address();
				if($mail_institute==TRUE)
				{
				
					$this->db->set('LAST_SESSION',trim($session_id));  
					$this->db->set('EXAM_SECRETE_CODE',trim($exam_secret_code));  
					$this->db->set('EXAM_SECRETE_CODE_DATE','NOW()', FALSE);
					$this->db->set('UPDATED_ON_IP',trim($ip_address));  
					$this->db->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
					$update = $this->db->update('student_course_details');
					//echo $this->db->last_query();
				}
			}
			if($this->db->affected_rows()>0)
			{
				
				redirect(base_url().'otp');
			}
		}
	}
	public function get_student()
	{
		$institute_id = $this->session->userdata('institute_id');
		$student_id = $this->session->userdata('student_id');
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		
		//$queryFE   	  = $this->db->select('*,  get_student_name(STUDENT_ID) AS full_name, get_institute_name(INSTITUTE_ID) AS institute_name, DATE_FORMAT(STUDENT_DOB, "%d/%m/%Y") AS DOB')->from('student_details')->where('STUDENT_ID', $student_id);
		
		$this->db->select('*');
		$this->db->select('get_student_name(STUDENT_ID) AS full_name');
		$this->db->select('get_stud_photo (STUDENT_ID) AS stud_photo');
		$this->db->select('get_institute_name(INSTITUTE_ID) AS institute_name');
		$this->db->select("DATE_FORMAT( STUDENT_DOB, '%d/%m/%Y' ) as DOB",  FALSE );
		
		$this->db->from('student_details');

		$this->db->where('STUDENT_ID', $student_id );
		
		$query  = $this->db->get();	
		return $query->row_array();
		
		
		//$query = $this->db->get_where('student_course_details', array('STUD_COURSE_DETAIL_ID' => $student_course_detail_id));
		//return $query->row_array();
	}
	public function get_exam_result()
	{
		$session_id = $this->session->userdata('my_session_id');
		$institute_id = $this->session->userdata('institute_id');
		$student_id = $this->session->userdata('student_id');
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');		
		$subject_id = $this->session->userdata('multi_subject_id');
		if(!empty($subject_id) && $subject_id !=''){
			$this->db->select('*');		
			$this->db->from('multi_sub_exam_result');

			$this->db->where('LAST_SESSION', $session_id );
			$this->db->where('STUDENT_ID', $student_id );
			$this->db->where('INSTITUTE_ID', $institute_id );
			$this->db->where('STUD_COURSE_ID', $student_course_detail_id );
		}else{
			$this->db->select('*');		
			$this->db->from('exam_result');

			$this->db->where('LAST_SESSION', $session_id );
			$this->db->where('STUDENT_ID', $student_id );
			$this->db->where('INSTITUTE_ID', $institute_id );
			$this->db->where('STUD_COURSE_ID', $student_course_detail_id );
		}
		
		
		$query  = $this->db->get();	
//echo $this->db->last_query();
		return $query->row_array();
		
	}
	public function get_institute($id)
	{
		$query = $this->db->get_where('institute_details', array('INSTITUTE_ID' => $id));
		return $query->row_array();
	}
	public function get_exam($course_id)
	{
		$query = $this->db->get_where('exam_structure', array('COURSE_ID' => $course_id));
		return $query->row_array();
	}
	public function get_exam_multi_subject($course_id,$subject_id)
	{
		$query = $this->db->get_where('multi_sub_course_exam_structure', array('MULTI_SUB_COURSE_ID' => $course_id,'COURSE_SUBJECT_ID' => $subject_id));
		return $query->row_array();
	}
	public function get_subject_name($course_id,$subject_id)
	{
		$query = $this->db->get_where('multi_sub_courses_subjects', array('MULTI_SUB_COURSE_ID' => $course_id,'COURSE_SUBJECT_ID' => $subject_id));		
		return $query->row_array();
	}
	public function get_student_course_details($id)
	{
		$queryFE   	  = $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID',$id);
	
		$queryFE   	  = $this->db->get();	
	//	echo $this->db->last_query();
		$rowFE 	  	  = $queryFE->row();
		return $rowFE->INSTITUTE_COURSE_ID;

	}
	public function get_exam_attempt($id)
	{
		$queryFE   	  = $this->db->select('EXAM_ATTEMPT')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID',$id);
		$queryFE   	  = $this->db->get();	
		$rowFE 	  	  = $queryFE->row();
		return $rowFE->EXAM_ATTEMPT;

	}
	public function get_exam_status($id)
	{
		$queryFE   	  = $this->db->select('EXAM_STATUS ')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID',$id);
		$queryFE   	  = $this->db->get();	
		$rowFE 	  	  = $queryFE->row();
		return $rowFE->EXAM_STATUS ;

	}
	public function get_exam_demo_stud($id)
	{
		$queryFE   	  = $this->db->select('DEMO_COUNT')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID',$id);
		$queryFE   	  = $this->db->get();	
		$rowFE 	  	  = $queryFE->row();
		return $rowFE->DEMO_COUNT;

	}

	public function enter_exam()
	{
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$student_id = $this->session->userdata('student_id');
		//$session_id = $this->session->userdata('session_id');
		$session_id = $this->session->userdata('my_session_id');

		if(!empty($_POST['exam_secret_code']))
		{
			//$otpP 				= trim($_POST['otp']);
			$exam_secret_codeP 	= trim($_POST['exam_secret_code']);
			$lang_id = $this->session->userdata('lang1');

			$queryS   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUDENT_NAME')->from('student_course_details')->where('EXAM_SECRETE_CODE', $exam_secret_codeP)->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id)->where('STUDENT_ID', $student_id)->where('EXAM_TYPE', '1');
			$queryS   	= $this->db->get();	
			//echo $this->db->last_query();
			$row 	  	= $queryS->row();
			$id 		= $row->STUD_COURSE_DETAIL_ID;			
			$STUDENT_ID = $row->STUDENT_ID;			
			$EXAM_SECRETE_CODE = $row->EXAM_SECRETE_CODE;			
			$EXAM_STATUS = $row->EXAM_STATUS;			
						
			if($exam_secret_codeP == $EXAM_SECRETE_CODE)
			{
				
				if($EXAM_STATUS==3)
				{
					$this->session->set_userdata('msg', '<font color="#FF0000">This exam code is already used!</font>');
					redirect(base_url().'otp');	
				}else{
				$this->session->set_userdata('exam_secrete_code', $EXAM_SECRETE_CODE);
				$full_name 					= $row->STUDENT_NAME;
				$INSTITUTE_COURSE_ID	 	= $row->INSTITUTE_COURSE_ID;
				$institute_id 				= $row->INSTITUTE_ID;
				$courseinfo					= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
				$COURSE_ID 					= $courseinfo['COURSE_ID'];
				$MULTI_SUB_COURSE_ID 					= $courseinfo['MULTI_SUB_COURSE_ID'];				
			
				if(!empty($COURSE_ID) && $COURSE_ID != '' && $COURSE_ID != '0'){
					$queryE   	= $this->db->select('EXAM_ID,TOTAL_QUESTIONS')->from('exam_structure')->where('COURSE_ID', $COURSE_ID);
					$queryE   	= $this->db->get();	
					$rowE 	  	= $queryE->row();
					$TOTAL_QUESTIONS = $rowE->TOTAL_QUESTIONS;
					$EXAM_ID = $rowE->EXAM_ID;
					$this->db->order_by('QUESTION_ID', 'RANDOM');
					$this->db->where('COURSE_ID', $COURSE_ID);
					$this->db->where('ACTIVE', 1);
					$this->db->where('LANG_ID', $lang_id);
					$this->db->limit($TOTAL_QUESTIONS, 0);
					$query = $this->db->get('exam_question_bank');
					
					if ($query->num_rows() > 0) 
					{
						$result = $query->result_array();
						//echo "<pre>";print_r($result);echo "</pre>";exit;
						for($i=0;$i<count($result);$i++)
						{
							
							$this->db->set('exam_id', $EXAM_ID);
							$this->db->set('student_id', $STUDENT_ID);
							$this->db->set('institute_id', $institute_id);
							$this->db->set('question_id', $result[$i]['QUESTION_ID']);
							$this->db->set('question', $result[$i]['QUESTION']);
							$this->db->set('session_id', $session_id);
							$this->db->set('option_a', $result[$i]['OPTION_A']);
							$this->db->set('option_b', $result[$i]['OPTION_B']);
							$this->db->set('option_c', $result[$i]['OPTION_C']);
							$this->db->set('option_d', $result[$i]['OPTION_D']);
							$this->db->set('image', $result[$i]['IMAGE']);
							$this->db->set('correct_ans', $result[$i]['CORRECT_ANS']);
							$this->db->set('LANG_ID', $lang_id);
							$this->db->insert('exam_attempt'); 
							$id1 = $this->db->insert_id();
							
						}					
						if($this->db->affected_rows()>0)
						{
							redirect(base_url().'exam');
						}
					}
					else
					{
						$this->session->set_userdata('msg', '<font color="#FF0000">Something went wrong! Contact your institute or exam co-ordinator!</font>');
						redirect(base_url().'otp');	
						return false;	
					}
				}
				if(!empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '' && $MULTI_SUB_COURSE_ID != '0'){
					$subject_id = $this->session->userdata('multi_subject_id');
					$queryE   	= $this->db->select('EXAM_ID,TOTAL_QUESTIONS')->from('multi_sub_course_exam_structure')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID,'COURSE_SUBJECT_ID'=>$subject_id));
					$queryE   	= $this->db->get();	
					$rowE 	  	= $queryE->row();
					$TOTAL_QUESTIONS = $rowE->TOTAL_QUESTIONS;
					$EXAM_ID = $rowE->EXAM_ID;
					$this->db->order_by('QUESTION_ID', 'RANDOM');
					$this->db->where('MULTI_SUB_COURSE_ID', $MULTI_SUB_COURSE_ID);
					$this->db->where('COURSE_SUBJECT_ID', $subject_id);
					$this->db->where('ACTIVE', 1);
					$this->db->where('LANG_ID', $lang_id);
					$this->db->limit($TOTAL_QUESTIONS, 0);
					$query = $this->db->get('multi_sub_exam_question_bank');
					
					if ($query->num_rows() > 0) 
					{
						$result = $query->result_array();
						//echo "<pre>";print_r($result);echo "</pre>";exit;
						for($i=0;$i<count($result);$i++)
						{
							
							$this->db->set('exam_id', $EXAM_ID);
							$this->db->set('student_id', $STUDENT_ID);
							$this->db->set('institute_id', $institute_id);
							$this->db->set('question_id', $result[$i]['QUESTION_ID']);
							$this->db->set('question', $result[$i]['QUESTION']);
							$this->db->set('session_id', $session_id);
							$this->db->set('option_a', $result[$i]['OPTION_A']);
							$this->db->set('option_b', $result[$i]['OPTION_B']);
							$this->db->set('option_c', $result[$i]['OPTION_C']);
							$this->db->set('option_d', $result[$i]['OPTION_D']);
							$this->db->set('image', $result[$i]['IMAGE']);
							$this->db->set('correct_ans', $result[$i]['CORRECT_ANS']);
							$this->db->set('LANG_ID', $lang_id);
							$this->db->insert('multi_sub_exam_attempt'); 
							$id1 = $this->db->insert_id();
							
						}					
						if($this->db->affected_rows()>0)
						{
							redirect(base_url().'exam');
						}
					}
					else
					{
						$this->session->set_userdata('msg', '<font color="#FF0000">Something went wrong! Contact your institute or exam co-ordinator!</font>');
						redirect(base_url().'otp');	
						return false;	
					}
				}
				
				}
			}
			else
			{
				$this->session->set_userdata('msg', '<font color="#FF0000">Invalid code entered!</font>');
				redirect(base_url().'otp');	
			}
		}
		else
		{
			return false;	
		}
	}
	public function get_questions()
	{
		$student_id = $this->session->userdata('student_id');
		$session_id = $this->session->userdata('my_session_id');

		$subject_id = $this->session->userdata('multi_subject_id');
	
		if(!empty($subject_id) && $subject_id !=''){
			$this->db->where('session_id', $session_id);
			//$this->db->limit($limit, $start);
			$query1 = $this->db->get('multi_sub_exam_attempt');
		}else{
			$this->db->where('session_id', $session_id);
			//$this->db->limit($limit, $start);
			$query1 = $this->db->get('exam_attempt');
		}
		
		return $query1->result_array();
	}
	public function get_exam_details()
	{
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$student_id = $this->session->userdata('student_id');		
		$queryS   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
		$queryS   	= $this->db->get();	
		$row 	  	= $queryS->row();
		$INSTITUTE_COURSE_ID 	= $row->INSTITUTE_COURSE_ID;
		$inst_course_info 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
	//	$query = $this->db->get_where('exam', array('id' => $exam_id));
		return $inst_course_info;
	}
	public function end_exam()
	{
		$session_id = $this->session->userdata('my_session_id');
		$subject_id = $this->session->userdata('multi_subject_id');
		if(!empty($subject_id) && $subject_id !=''){
			if(!empty($_POST['end_exam']))
			{ 
				$update=$this->db->delete('multi_sub_p_exam_attempt', array('session_id' => $session_id)); 
			
				if($update==TRUE)
				{
					$this->session->unset_userdata('session_id');
					$this->session->unset_userdata('student_id');
					$this->session->unset_userdata('end_exam');
					$this->session->unset_userdata('pend_exam');
					$this->session->unset_userdata('my_session_id');
					$this->session->unset_userdata('multi_subject_id');
					$this->session->sess_destroy();
					redirect(base_url());
				}
			}
			else
			{
				return false;	
			}
		}else{
			if(!empty($_POST['end_exam']))
			{ 
				$update=$this->db->delete('exam_attempt', array('session_id' => $session_id)); 
			
				if($update==TRUE)
				{
					$this->session->unset_userdata('session_id');
					$this->session->unset_userdata('student_id');
					$this->session->unset_userdata('end_exam');
					$this->session->unset_userdata('pend_exam');
					$this->session->unset_userdata('my_session_id');
					$this->session->unset_userdata('multi_subject_id');
					$this->session->sess_destroy();
					redirect(base_url());
				}
			}
			else
			{
				return false;	
			}
		}
	
	}
	public function exam_over()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$ip_address	= $this->input->ip_address();
		$subject_id = $this->session->userdata('multi_subject_id');
		if(!empty($subject_id) && $subject_id !=''){
			if(!empty($_POST['exam_over1']) && $_POST['exam_over1']==1)
			{
				$this->session->set_userdata('end_exam', 1);
			
				$post = $_POST['ad_opt1'];
				
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
			
				$exam_attempt_id = $_POST['exam_attempt_id1'];
			
				$queryS   	= $this->db->select('correct_ans')->from('multi_sub_exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
				
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1);
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$update = $this->db->update('multi_sub_exam_attempt');
				
				if($this->db->affected_rows()>=0)
				{
					$queryEXM11   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
					$queryEXM11   	= $this->db->get();	
					$rowEXM11 	  	= $queryEXM11->row();
					$INSTITUTE_ID= $rowEXM11->INSTITUTE_ID;
					$INSTITUTE_COURSE_ID= $rowEXM11->INSTITUTE_COURSE_ID;
					
					$inst_course_info11 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
					$MULTI_SUB_COURSE_ID11	=$inst_course_info11['MULTI_SUB_COURSE_ID'];

					$subjectCount =  $this->db->select('count(MULTI_SUB_COURSE_ID) AS SUBJECTCOUNT')->from('institute_course_subjects')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID11,'INSTITUTE_ID' => $INSTITUTE_ID));
					$querysubjectCount   	= $this->db->get();	
					$rowsubjectCount 	  	= $querysubjectCount->row();
					$SUBJECTCOUNT= $rowsubjectCount->SUBJECTCOUNT;

					$examDoneCount = $this->db->select('count(MULTI_SUB_COURSE_ID) AS EXAMCOUNT')->from('multi_sub_exam_result')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID11,'INSTITUTE_ID' => $INSTITUTE_ID,'STUDENT_ID' => $student_id));
					$queryexamDoneCount   	= $this->db->get();	
					$rowexamDoneCount 	  	= $queryexamDoneCount->row();
					$EXAMCOUNT= $rowexamDoneCount->EXAMCOUNT;

					$EXAMCOUNT = $EXAMCOUNT + 1;
					if($SUBJECTCOUNT == $EXAMCOUNT){
						$this->db->set('EXAM_STATUS','3'); //Exam attended
					}else{
						$this->db->set('EXAM_STATUS','2'); //Exam attended
					}					

					$this->db->set('EXAM_ATTEMPT',1);
					$this->db->set('DEMO_COUNT','get_institute_demo_count(INSTITUTE_ID)', FALSE);
					
					$this->db->set('UPDATED_ON','NOW()', FALSE);
					$this->db->set('UPDATED_BY','get_student_name(STUDENT_ID)', FALSE);
					$this->db->set('UPDATED_ON_IP',$ip_address);
					$this->db->set('LAST_SESSION',$session_id);
					$this->db->set('EXAM_IP',$ip_address);
					$this->db->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
					$update1 = $this->db->update('student_course_details');
				//	echo $this->db->last_query();
					if($update1==TRUE)
					{  
						$querySTU   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUD_NAME, get_stud_photo(STUDENT_ID) AS STUD_PHOTO, get_institute_name(INSTITUTE_ID) AS INST_NAME')->from('student_details')->where('STUDENT_ID', $student_id);
						$querySTU   	= $this->db->get();	
						$rowSTU 	  	= $querySTU->row();
				
						$mobile			= $rowSTU->STUDENT_MOBILE;
						//$exam_id		= $rowSTU->exam_id;
						$stu_email		= $rowSTU->STUDENT_EMAIL;
						$full_name		= $rowSTU->STUD_NAME;
						//$exam_date		= $rowSTU->exam_date;
						$gender			= $rowSTU->STUDENT_GENDER;
						$ins_id			= $rowSTU->INSTITUTE_ID;
						$std_image		= $rowSTU->STUD_PHOTO;
						$nins	= $rowSTU->INST_NAME;
						
						if($gender=='male')
						{
							$gender = "Male";
						}
						else
						{
							$gender = "Female";	
						}
						
						$queryEXM   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
						$queryEXM   	= $this->db->get();	
						$rowEXM 	  	= $queryEXM->row();
						$exam_secret_code= $rowEXM->EXAM_SECRETE_CODE;
						$INSTITUTE_ID= $rowEXM->INSTITUTE_ID;
						$INSTITUTE_COURSE_ID= $rowEXM->INSTITUTE_COURSE_ID;
						
						$inst_course_info 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
						$MULTI_SUB_COURSE_ID				=$inst_course_info['MULTI_SUB_COURSE_ID'];
						/*$exam_name 				= $this->get_exam($COURSE_ID);
						*/
						
						$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
						$course_name				=$course['MULTI_SUB_COURSE_NAME'];

						$queryEXM   	= $this->db->select('*')->from('multi_sub_course_exam_structure')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID,'COURSE_SUBJECT_ID' => $subject_id));
						$queryEXM   	= $this->db->get();	
						$rowEXM 	  	= $queryEXM->row();
						$EXAM_ID	= $rowEXM->EXAM_ID;
						$show_result	= $rowEXM->SHOW_RESULT;
						$total_questions= $rowEXM->TOTAL_QUESTIONS;
						$total_marks	= $rowEXM->TOTAL_MARKS;
						$passing_marks	= $rowEXM->PASSING_MARKS;
						$marks_per_que	= $rowEXM->MARKS_PER_QUE;
						$exam_time	= $rowEXM->EXAM_TIME;
						
						$perMarks 	= $total_marks/$total_questions;
						$perMarks 	= number_format($perMarks, 2);
						
						$queryEATM = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' => 1));
						$correct_answer= $queryEATM->num_rows();
						
						$queryICA = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' => 0));
						$incor_ans= $queryICA->num_rows();
						
						$gotMarks = $correct_answer*$perMarks;
						$gotMarks = round($gotMarks);
						$gotPercent = ($gotMarks*100)/$total_marks;
						
						if($gotPercent>=85)
						{
							$grade = "A+ : Excellent";
							$res_stat = "Passed";
						}
						elseif($gotPercent>=70 && $gotPercent<85)

						{
							$grade = "A : Very Good"; 
							$res_stat = "Passed";
						}
						elseif($gotPercent>=55 && $gotPercent<70)
						{
							$grade = "B : Good"; 
							$res_stat = "Passed";
						}
						elseif($gotPercent>=40 && $gotPercent<55)
						{
							$grade = "C : Average"; 
							$res_stat = "Passed";
						}
						else
						{
							$grade = ""; 
							$res_stat = "Failed";
						}
						$this->load->helper('date');
						$this->db->set('EXAM_ATTEMPT',1);
						$this->db->set('STUD_COURSE_ID',$student_course_detail_id);
						$this->db->set('STUDENT_SUBJECT_ID',$subject_id);
						$this->db->set('EXAM_ID',$EXAM_ID);
						$this->db->set('INSTITUTE_COURSE_ID',$INSTITUTE_COURSE_ID);
						$this->db->set('EXAM_SECRETE_CODE',$exam_secret_code);
						$this->db->set('EXAM_TOTAL_QUE',$total_questions);
						$this->db->set('EXAM_TOTAL_MARKS',$total_marks);
						$this->db->set('EXAM_PASSING_MARKS',$passing_marks);
						$this->db->set('EXAM_TITLE',$course_name);
						$this->db->set('MARKS_OBTAINED',$gotMarks);
						$this->db->set('EXAM_MARKS_PER_QUE',$marks_per_que);
						$this->db->set('MARKS_PER',$gotPercent);
						$this->db->set('GRADE',$grade);
						$this->db->set('CORRECT_ANSWER',$correct_answer);
						$this->db->set('INCORRECT_ANSWER',$incor_ans);
						$this->db->set('RESULT_STATUS',$res_stat);
						$this->db->set('EXAM_IP', $ip_address);
						$this->db->set('CREATED_ON', 'NOW()',FALSE);
						$this->db->set('CREATED_ON_IP', $ip_address);
						$this->db->set('INSTITUTE_ID', $INSTITUTE_ID);
						$this->db->set('STUDENT_ID', $student_id);						
						$this->db->set('LAST_SESSION', $session_id);
						$this->db->set('MULTI_SUB_COURSE_ID', $MULTI_SUB_COURSE_ID);
						$this->db->set('EXAM_TYPE', 1);
						$ALLupdate = $this->db->insert('multi_sub_exam_result');						
						
						$id1 = $this->db->insert_id();

						$examDoneCountFinal = $this->db->select('count(MULTI_SUB_COURSE_ID) AS EXAMDONEFINALCOUNT')->from('multi_sub_exam_result_final')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID11,'INSTITUTE_ID' => $INSTITUTE_ID,'STUDENT_ID' => $student_id,'INSTITUTE_COURSE_ID'=> $INSTITUTE_COURSE_ID));
						$queryDoneCountFinal   	= $this->db->get();	
						$rowDoneCountFinal 	  	= $queryDoneCountFinal->row();
						$EXAMDONEFINALCOUNT= $rowDoneCountFinal->EXAMDONEFINALCOUNT;

						if(empty($EXAMDONEFINALCOUNT) && $EXAMDONEFINALCOUNT == 0){
							$this->db->set('STUD_COURSE_ID', $student_course_detail_id);
							$this->db->set('STUDENT_ID', $student_id);						
							$this->db->set('INSTITUTE_ID', $INSTITUTE_ID);
							$this->db->set('INSTITUTE_COURSE_ID',$INSTITUTE_COURSE_ID);
							$this->db->set('EXAM_TITLE',$course_name);
							$this->db->set('EXAM_TYPE', 1);
							$this->db->set('EXAM_TOTAL_MARKS',$total_marks);
							$this->db->set('MARKS_OBTAINED',$gotMarks);
							$this->db->set('MARKS_PER',$gotPercent);
							$this->db->set('GRADE',$grade);
							$this->db->set('ACTIVE','1');
							$this->db->set('DELETE_FLAG','0');
							$this->db->set('CREATED_ON','NOW()',FALSE);
							$this->db->set('MULTI_SUB_COURSE_ID', $MULTI_SUB_COURSE_ID);
							$ALLupdate1 = $this->db->insert('multi_sub_exam_result_final');
						}else{		
							
							$examDoneMarks = $this->db->select('SUM(EXAM_TOTAL_MARKS) AS EXAM_TOTAL_MARKS1, SUM(EXAM_TOTAL_MARKS) AS EXAM_TOTAL_MARKS1,SUM(MARKS_OBTAINED) AS MARKS_OBTAINED1')->from('multi_sub_exam_result')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID11,'INSTITUTE_ID' => $INSTITUTE_ID,'STUDENT_ID' => $student_id,'INSTITUTE_COURSE_ID'=> $INSTITUTE_COURSE_ID));
							$queryDoneMarks   	= $this->db->get();	
							$rowDoneMarks 	  	= $queryDoneMarks->row();
							$EXAM_TOTAL_MARKS1	= $rowDoneMarks->EXAM_TOTAL_MARKS1;
							$MARKS_OBTAINED1	= $rowDoneMarks->MARKS_OBTAINED1;
							$gotPercent1 = ($MARKS_OBTAINED1*100)/$EXAM_TOTAL_MARKS1;
						
							if($gotPercent1>=85)
							{
								$grade = "A+ : Excellent";
								$res_stat = "Passed";
							}
							elseif($gotPercent1>=70 && $gotPercent1<85)

							{
								$grade = "A : Very Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent1>=55 && $gotPercent1<70)
							{
								$grade = "B : Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent1>=40 && $gotPercent1<55)
							{
								$grade = "C : Average"; 
								$res_stat = "Passed";
							}
							else
							{
								$grade = ""; 
								$res_stat = "Failed";
							}

							$this->db->set('EXAM_TOTAL_MARKS',$EXAM_TOTAL_MARKS1);
							$this->db->set('MARKS_OBTAINED',$MARKS_OBTAINED1);
							$this->db->set('MARKS_PER',$gotPercent1);
							$this->db->set('GRADE',$grade);
							$this->db->set('RESULT_STATUS',$res_stat);
							$this->db->set('ACTIVE','1');
							$this->db->set('DELETE_FLAG','0');
							$this->db->set('UPDATED_ON','NOW()',FALSE);
							$this->db->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID11,'INSTITUTE_ID' => $INSTITUTE_ID,'STUDENT_ID' => $student_id,'INSTITUTE_COURSE_ID'=> $INSTITUTE_COURSE_ID,'STUD_COURSE_ID'=>$student_course_detail_id));
							$ALLupdate1 = $this->db->update('multi_sub_exam_result_final');
						}		


							
							//echo $this->db->last_query();
						if($show_result==0)
						{
							$message = urlencode('Your result is recorded by system and will be informed to you shortly by your institute');
						}
						else
						{
							$message = urlencode('You have successfully completed your exam. You have obtained '.$gotPercent.'% marks. Grade : '.$grade);
						}
					
						redirect(base_url().'result');
					}
				}
			}
			else
			{
				return false;	
			}
		}else{
			if(!empty($_POST['exam_over1']) && $_POST['exam_over1']==1)
			{
				$this->session->set_userdata('end_exam', 1);
			
				$post = $_POST['ad_opt1'];
				
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
			
				$exam_attempt_id = $_POST['exam_attempt_id1'];
			
				$queryS   	= $this->db->select('correct_ans')->from('exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
				
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1);
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$update = $this->db->update('exam_attempt');
				
				if($this->db->affected_rows()>=0)
				{
					
					$this->db->set('EXAM_STATUS','3'); //Exam attended
					$this->db->set('EXAM_ATTEMPT',1);
					$this->db->set('DEMO_COUNT','get_institute_demo_count(INSTITUTE_ID)', FALSE);
					
					$this->db->set('UPDATED_ON','NOW()', FALSE);
					$this->db->set('UPDATED_BY','get_student_name(STUDENT_ID)', FALSE);
					$this->db->set('UPDATED_ON_IP',$ip_address);
					$this->db->set('LAST_SESSION',$session_id);
					$this->db->set('EXAM_IP',$ip_address);
					$this->db->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
					$update1 = $this->db->update('student_course_details');
				//	echo $this->db->last_query();
					if($update1==TRUE)
					{  
						$querySTU   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUD_NAME, get_stud_photo(STUDENT_ID) AS STUD_PHOTO, get_institute_name(INSTITUTE_ID) AS INST_NAME')->from('student_details')->where('STUDENT_ID', $student_id);
						$querySTU   	= $this->db->get();	
						$rowSTU 	  	= $querySTU->row();
				
						$mobile			= $rowSTU->STUDENT_MOBILE;
						//$exam_id		= $rowSTU->exam_id;
						$stu_email		= $rowSTU->STUDENT_EMAIL;
						$full_name		= $rowSTU->STUD_NAME;
						//$exam_date		= $rowSTU->exam_date;
						$gender			= $rowSTU->STUDENT_GENDER;
						$ins_id			= $rowSTU->INSTITUTE_ID;
						$std_image		= $rowSTU->STUD_PHOTO;
						$nins	= $rowSTU->INST_NAME;
						
						if($gender=='male')
						{
							$gender = "Male";
						}
						else
						{
							$gender = "Female";	
						}
						
						$queryEXM   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
						$queryEXM   	= $this->db->get();	
						$rowEXM 	  	= $queryEXM->row();
						$exam_secret_code= $rowEXM->EXAM_SECRETE_CODE;
						$INSTITUTE_ID= $rowEXM->INSTITUTE_ID;
						$INSTITUTE_COURSE_ID= $rowEXM->INSTITUTE_COURSE_ID;
						
						$inst_course_info 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
						$COURSE_ID				=$inst_course_info['COURSE_ID'];
						/*$exam_name 				= $this->get_exam($COURSE_ID);
						*/
						
						$queryEXM   	= $this->db->select('*')->from('exam_structure')->where('COURSE_ID', $COURSE_ID);
						$queryEXM   	= $this->db->get();	
						$rowEXM 	  	= $queryEXM->row();
						$EXAM_ID	= $rowEXM->EXAM_ID;
						$show_result	= $rowEXM->SHOW_RESULT;
						$total_questions= $rowEXM->TOTAL_QUESTIONS;
						$total_marks	= $rowEXM->TOTAL_MARKS;
						$course_name	= $rowEXM->EXAM_TITLE;
						$passing_marks	= $rowEXM->PASSING_MARKS;
						$marks_per_que	= $rowEXM->MARKS_PER_QUE;
						$exam_time	= $rowEXM->EXAM_TIME;
						
						$perMarks 	= $total_marks/$total_questions;
						$perMarks 	= number_format($perMarks, 2);
						
						$queryEATM = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' => 1));
						$correct_answer= $queryEATM->num_rows();
						
						$queryICA = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' => 0));
						$incor_ans= $queryICA->num_rows();
						
						$gotMarks = $correct_answer*$perMarks;
						$gotMarks = round($gotMarks);
						$gotPercent = ($gotMarks*100)/$total_marks;
						
						if($gotPercent>=85)
						{
							$grade = "A+ : Excellent";
							$res_stat = "Passed";
						}
						elseif($gotPercent>=70 && $gotPercent<85)

						{
							$grade = "A : Very Good"; 
							$res_stat = "Passed";
						}
						elseif($gotPercent>=55 && $gotPercent<70)
						{
							$grade = "B : Good"; 
							$res_stat = "Passed";
						}
						elseif($gotPercent>=40 && $gotPercent<55)
						{
							$grade = "C : Average"; 
							$res_stat = "Passed";
						}
						else
						{
							$grade = ""; 
							$res_stat = "Failed";
						}
						$this->load->helper('date');
						$this->db->set('EXAM_ATTEMPT',1);
						$this->db->set('STUD_COURSE_ID',$student_course_detail_id);
						$this->db->set('EXAM_ID',$EXAM_ID);
						$this->db->set('INSTITUTE_COURSE_ID',$INSTITUTE_COURSE_ID);
						$this->db->set('EXAM_SECRETE_CODE',$exam_secret_code);
						$this->db->set('EXAM_TOTAL_QUE',$total_questions);
						$this->db->set('EXAM_TOTAL_MARKS',$total_marks);
						$this->db->set('EXAM_PASSING_MARKS',$passing_marks);
						$this->db->set('EXAM_TITLE',$course_name);
						$this->db->set('MARKS_OBTAINED',$gotMarks);
						$this->db->set('EXAM_MARKS_PER_QUE',$marks_per_que);
						$this->db->set('MARKS_PER',$gotPercent);
						$this->db->set('GRADE',$grade);
						$this->db->set('CORRECT_ANSWER',$correct_answer);
						$this->db->set('INCORRECT_ANSWER',$incor_ans);
						$this->db->set('RESULT_STATUS',$res_stat);
						$this->db->set('EXAM_IP', $ip_address);
						$this->db->set('CREATED_ON', 'NOW()',FALSE);
						$this->db->set('CREATED_ON_IP', $ip_address);
						$this->db->set('INSTITUTE_ID', $INSTITUTE_ID);
						$this->db->set('STUDENT_ID', $student_id);						
						$this->db->set('LAST_SESSION', $session_id);
						$this->db->set('EXAM_TYPE', 1);
						$ALLupdate = $this->db->insert('exam_result');						
						
						$id1 = $this->db->insert_id();
							
							//echo $this->db->last_query();
						if($show_result==0)
						{
							$message = urlencode('Your result is recorded by system and will be informed to you shortly by your institute');
						}
						else
						{
							$message = urlencode('You have successfully completed your exam. You have obtained '.$gotPercent.'% marks. Grade : '.$grade);
						}
					
						redirect(base_url().'result');
					}
				}
			}
			else
			{
				return false;	
			}
		}
			
		//}
		
	}
	public function session_over()
	{
		/* $session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		 */
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$ip_address	= $this->input->ip_address();
		/*if(!empty($_POST))
		{
			echo "<pre>";	
			print_r($_POST);
			
			echo "</pre>";exit;
		}*/
		$subject_id = $this->session->userdata('multi_subject_id');
		if(!empty($subject_id) && $subject_id !=''){
			if(!empty($_POST['sess_end1']) && $_POST['sess_end1']==1)
			{
				$this->session->set_userdata('end_exam', 1);
				
				$post = $_POST['ad_opt2'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id2'];
				
					$queryS   	= $this->db->select('correct_ans')->from('multi_sub_exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($correct_ans==$ad_opt)
					{
						$answer_status = 1;
					}
					else
					{
						$answer_status = 0;
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}
					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1);
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$update = $this->db->update('multi_sub_exam_attempt');
					
					if($update==TRUE)
					{ 
						$querySTU   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUD_NAME, get_stud_photo(STUDENT_ID) AS STUD_PHOTO, get_institute_name(INSTITUTE_ID) AS INST_NAME')->from('student_details')->where('STUDENT_ID', $student_id);
							$querySTU   	= $this->db->get();	
							$rowSTU 	  	= $querySTU->row();
					
							$mobile			= $rowSTU->STUDENT_MOBILE;
							//$exam_id		= $rowSTU->exam_id;
							$stu_email		= $rowSTU->STUDENT_EMAIL;
							$full_name		= $rowSTU->STUD_NAME;
							//$exam_date		= $rowSTU->exam_date;
							$gender			= $rowSTU->STUDENT_GENDER;
							$ins_id			= $rowSTU->INSTITUTE_ID;
							$std_image		= $rowSTU->STUD_PHOTO;
							$nins	= $rowSTU->INST_NAME;
							
							if($gender=='male')
							{
								$gender = "Male";
							}
							else
							{
								$gender = "Female";	
							}
							
							$queryEXM   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
							$queryEXM   	= $this->db->get();	
							$rowEXM 	  	= $queryEXM->row();
							$exam_secret_code= $rowEXM->EXAM_SECRETE_CODE;
							$INSTITUTE_ID= $rowEXM->INSTITUTE_ID;
							$INSTITUTE_COURSE_ID= $rowEXM->INSTITUTE_COURSE_ID;
							
							$inst_course_info 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
							$MULTI_SUB_COURSE_ID 				=$inst_course_info['MULTI_SUB_COURSE_ID '];
							/*$exam_name 				= $this->get_exam($COURSE_ID);
							*/
							
							$queryEXM   	= $this->db->select('*')->from('multi_sub_course_exam_structure')->where(array('MULTI_SUB_COURSE_ID ' => $MULTI_SUB_COURSE_ID, 'COURSE_SUBJECT_ID' => $subject_id) );
							$queryEXM   	= $this->db->get();	
							$rowEXM 	  	= $queryEXM->row();
							$EXAM_ID	= $rowEXM->EXAM_ID;
							$show_result	= $rowEXM->SHOW_RESULT;
							$total_questions= $rowEXM->TOTAL_QUESTIONS;
							$total_marks	= $rowEXM->TOTAL_MARKS;
							$course_name	= $rowEXM->EXAM_TITLE;
							$passing_marks	= $rowEXM->PASSING_MARKS;
							$marks_per_que	= $rowEXM->MARKS_PER_QUE;
							$exam_time	= $rowEXM->EXAM_TIME;
							
							$perMarks 	= $total_marks/$total_questions;
							$perMarks 	= number_format($perMarks, 2);
							
							$queryEATM = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' => 1));
							$correct_answer= $queryEATM->num_rows();
							
							$queryICA = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' => 0));
							$incor_ans= $queryICA->num_rows();
							
							$gotMarks = $correct_answer*$perMarks;
							$gotMarks = round($gotMarks);
							$gotPercent = ($gotMarks*100)/$total_marks;
							
							if($gotPercent>=85)
							{
								$grade = "A+ : Excellent";
								$res_stat = "Passed";
							}
							elseif($gotPercent>=70 && $gotPercent<85)
	
							{
								$grade = "A : Very Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent>=55 && $gotPercent<70)
							{
								$grade = "B : Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent>=40 && $gotPercent<55)
							{
								$grade = "C : Average"; 
								$res_stat = "Passed";
							}
							else
							{
								$grade = ""; 
								$res_stat = "Failed";
							}
							$this->load->helper('date');
							$this->db->set('EXAM_ATTEMPT',1);
							$this->db->set('STUD_COURSE_ID',$student_course_detail_id);
							$this->db->set('EXAM_ID',$EXAM_ID);
							$this->db->set('INSTITUTE_COURSE_ID',$INSTITUTE_COURSE_ID);
							$this->db->set('EXAM_SECRETE_CODE',$exam_secret_code);
							$this->db->set('EXAM_TOTAL_QUE',$total_questions);
							$this->db->set('EXAM_TOTAL_MARKS',$total_marks);
							$this->db->set('EXAM_PASSING_MARKS',$passing_marks);
							$this->db->set('EXAM_TITLE',$course_name);
							$this->db->set('MARKS_OBTAINED',$gotMarks);
							$this->db->set('EXAM_MARKS_PER_QUE',$marks_per_que);
							$this->db->set('MARKS_PER',$gotPercent);
							$this->db->set('GRADE',$grade);
							$this->db->set('CORRECT_ANSWER',$correct_answer);
							$this->db->set('INCORRECT_ANSWER',$incor_ans);
							$this->db->set('RESULT_STATUS',$res_stat);
							$this->db->set('EXAM_IP', $ip_address);
							$this->db->set('CREATED_ON', 'NOW()',FALSE);
							$this->db->set('CREATED_ON_IP', $ip_address);
							$this->db->set('INSTITUTE_ID', $INSTITUTE_ID);
							$this->db->set('STUDENT_ID', $student_id);						
							$this->db->set('LAST_SESSION', $session_id);
							$this->db->set('EXAM_TYPE', 1);
							$ALLupdate = $this->db->insert(' multi_sub_exam_result');						
							
							$id1 = $this->db->insert_id();
								
								//echo $this->db->last_query();
							if($show_result==0)
							{
								$message = urlencode('Your result is recorded by system and will be informed to you shortly by your institute');
							}
							else
							{
								$message = urlencode('You have successfully completed your exam. You have obtained '.$gotPercent.'% marks. Grade : '.$grade);
							}
						
							redirect(base_url().'result');
					}
			
			}
			else
			{
				return false;	
			}
		}else{
			if(!empty($_POST['sess_end1']) && $_POST['sess_end1']==1)
			{
				$this->session->set_userdata('end_exam', 1);
				
				$post = $_POST['ad_opt2'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id2'];
				
					$queryS   	= $this->db->select('correct_ans')->from('exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($correct_ans==$ad_opt)
					{
						$answer_status = 1;
					}
					else
					{
						$answer_status = 0;
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}
					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1);
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$update = $this->db->update('exam_attempt');
					
					if($update==TRUE)
					{ 
						$querySTU   	= $this->db->select('*, get_student_name(STUDENT_ID) AS STUD_NAME, get_stud_photo(STUDENT_ID) AS STUD_PHOTO, get_institute_name(INSTITUTE_ID) AS INST_NAME')->from('student_details')->where('STUDENT_ID', $student_id);
							$querySTU   	= $this->db->get();	
							$rowSTU 	  	= $querySTU->row();
					
							$mobile			= $rowSTU->STUDENT_MOBILE;
							//$exam_id		= $rowSTU->exam_id;
							$stu_email		= $rowSTU->STUDENT_EMAIL;
							$full_name		= $rowSTU->STUD_NAME;
							//$exam_date		= $rowSTU->exam_date;
							$gender			= $rowSTU->STUDENT_GENDER;
							$ins_id			= $rowSTU->INSTITUTE_ID;
							$std_image		= $rowSTU->STUD_PHOTO;
							$nins	= $rowSTU->INST_NAME;
							
							if($gender=='male')
							{
								$gender = "Male";
							}
							else
							{
								$gender = "Female";	
							}
							
							$queryEXM   	= $this->db->select('*')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
							$queryEXM   	= $this->db->get();	
							$rowEXM 	  	= $queryEXM->row();
							$exam_secret_code= $rowEXM->EXAM_SECRETE_CODE;
							$INSTITUTE_ID= $rowEXM->INSTITUTE_ID;
							$INSTITUTE_COURSE_ID= $rowEXM->INSTITUTE_COURSE_ID;
							
							$inst_course_info 		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
							$COURSE_ID				=$inst_course_info['COURSE_ID'];
							/*$exam_name 				= $this->get_exam($COURSE_ID);
							*/
							
							$queryEXM   	= $this->db->select('*')->from('exam_structure')->where('COURSE_ID', $COURSE_ID);
							$queryEXM   	= $this->db->get();	
							$rowEXM 	  	= $queryEXM->row();
							$EXAM_ID	= $rowEXM->EXAM_ID;
							$show_result	= $rowEXM->SHOW_RESULT;
							$total_questions= $rowEXM->TOTAL_QUESTIONS;
							$total_marks	= $rowEXM->TOTAL_MARKS;
							$course_name	= $rowEXM->EXAM_TITLE;
							$passing_marks	= $rowEXM->PASSING_MARKS;
							$marks_per_que	= $rowEXM->MARKS_PER_QUE;
							$exam_time	= $rowEXM->EXAM_TIME;
							
							$perMarks 	= $total_marks/$total_questions;
							$perMarks 	= number_format($perMarks, 2);
							
							$queryEATM = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' => 1));
							$correct_answer= $queryEATM->num_rows();
							
							$queryICA = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' => 0));
							$incor_ans= $queryICA->num_rows();
							
							$gotMarks = $correct_answer*$perMarks;
							$gotMarks = round($gotMarks);
							$gotPercent = ($gotMarks*100)/$total_marks;
							
							if($gotPercent>=85)
							{
								$grade = "A+ : Excellent";
								$res_stat = "Passed";
							}
							elseif($gotPercent>=70 && $gotPercent<85)
	
							{
								$grade = "A : Very Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent>=55 && $gotPercent<70)
							{
								$grade = "B : Good"; 
								$res_stat = "Passed";
							}
							elseif($gotPercent>=40 && $gotPercent<55)
							{
								$grade = "C : Average"; 
								$res_stat = "Passed";
							}
							else
							{
								$grade = ""; 
								$res_stat = "Failed";
							}
							$this->load->helper('date');
							$this->db->set('EXAM_ATTEMPT',1);
							$this->db->set('STUD_COURSE_ID',$student_course_detail_id);
							$this->db->set('EXAM_ID',$EXAM_ID);
							$this->db->set('INSTITUTE_COURSE_ID',$INSTITUTE_COURSE_ID);
							$this->db->set('EXAM_SECRETE_CODE',$exam_secret_code);
							$this->db->set('EXAM_TOTAL_QUE',$total_questions);
							$this->db->set('EXAM_TOTAL_MARKS',$total_marks);
							$this->db->set('EXAM_PASSING_MARKS',$passing_marks);
							$this->db->set('EXAM_TITLE',$course_name);
							$this->db->set('MARKS_OBTAINED',$gotMarks);
							$this->db->set('EXAM_MARKS_PER_QUE',$marks_per_que);
							$this->db->set('MARKS_PER',$gotPercent);
							$this->db->set('GRADE',$grade);
							$this->db->set('CORRECT_ANSWER',$correct_answer);
							$this->db->set('INCORRECT_ANSWER',$incor_ans);
							$this->db->set('RESULT_STATUS',$res_stat);
							$this->db->set('EXAM_IP', $ip_address);
							$this->db->set('CREATED_ON', 'NOW()',FALSE);
							$this->db->set('CREATED_ON_IP', $ip_address);
							$this->db->set('INSTITUTE_ID', $INSTITUTE_ID);
							$this->db->set('STUDENT_ID', $student_id);						
							$this->db->set('LAST_SESSION', $session_id);
							$this->db->set('EXAM_TYPE', 1);
							$ALLupdate = $this->db->insert('exam_result');						
							
							$id1 = $this->db->insert_id();
								
								//echo $this->db->last_query();
							if($show_result==0)
							{
								$message = urlencode('Your result is recorded by system and will be informed to you shortly by your institute');
							}
							else
							{
								$message = urlencode('You have successfully completed your exam. You have obtained '.$gotPercent.'% marks. Grade : '.$grade);
							}
						
							redirect(base_url().'result');
					}
			
			}
			else
			{
				return false;	
			}
		}
		
	
	}
	public function save_n_next()
	{ 	
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){
			if( !empty($_POST['save_next'] ) && $_POST['save_next'] == 1) 
			{
				$serial = $_POST['serial'];
				$post = $_POST['ad_opt_'.$serial];
					if(!empty($post))
					{
						$ad_opt = $post;
					}
					else
					{
						$ad_opt = '';
					}
					$exam_attempt_id = $_POST['exam_attempt_id_'.$serial];
					
					$queryS   	= $this->db->select('correct_ans')->from('multi_sub_exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($ad_opt!=''){
						if($correct_ans==$ad_opt)
						{
							$answer_status = 1;
						}
						else
						{
							$answer_status = 0;
						}
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}
					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1); 
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$this->db->update('multi_sub_exam_attempt');
			}
			else
			{
				return false;	
			}
		}else{
			if( !empty($_POST['save_next'] ) && $_POST['save_next'] == 1) 
			{
				$serial = $_POST['serial'];
				$post = $_POST['ad_opt_'.$serial];
					if(!empty($post))
					{
						$ad_opt = $post;
					}
					else
					{
						$ad_opt = '';
					}
					$exam_attempt_id = $_POST['exam_attempt_id_'.$serial];
					
					$queryS   	= $this->db->select('correct_ans')->from('exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($ad_opt!=''){
						if($correct_ans==$ad_opt)
						{
							$answer_status = 1;
						}
						else
						{
							$answer_status = 0;
						}
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}
					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1); 
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$this->db->update('exam_attempt');
			}
			else
			{
				return false;	
			}
		}
		
	}
	public function correct_answer()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){
			$query = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' =>1, 'student_id'=>$student_id));
		}else{
			$query = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' =>1, 'student_id'=>$student_id));
		}
		
		return $query->num_rows;
		
	}
	public function incorrect_answer()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){
			$query = $this->db->get_where('multi_sub_exam_attempt', array('session_id' => $session_id, 'answer_status' =>0, 'student_id'=>$student_id));
		}else{
			$query = $this->db->get_where('exam_attempt', array('session_id' => $session_id, 'answer_status' =>0, 'student_id'=>$student_id));
		}
		
		return $query->num_rows;
		
	}
	public function set_logout()
	{
		$this->session->unset_userdata('student_id');
		$this->session->unset_userdata('session_id');
		$this->session->unset_userdata('my_session_id');
		$this->session->unset_userdata('end_exam');
		$this->session->unset_userdata('pend_exam');
		$this->session->unset_userdata('multi_subject_id');
		
	  	$this->session->sess_destroy();
	   	redirect(HOST);
	}
	public function set_forgot()
	{
		if($this->input->post('send'))
		{
			$user_type = $this->input->post('user_type');
			
			if($user_type == "admin")
			{
				$email = $this->input->post('foremail');

				$query = $this->db->get_where('admin', array('user_email' => trim($email)));
				
				if ($query->num_rows() > 0)
				{
					$queryS   	  = $this->db->select('*')->from('admin')->where('user_email', trim($email));
					$queryS   	  = $this->db->get();	
					$row 	  	  = $queryS->row();
					$pwd 	  	  = $row->pwd;
					$from_email   = $row->user_email;
					$from_email	  = trim($from_email);
					
					$config['protocol'] = 'sendmail';
					$config['charset'] = 'iso-8859-1';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
			
					$this->email->initialize($from_email);
			
					$this->email->from('info@akwebtechnology.com');
					$this->email->to($email);
			
					$this->email->subject('Retrive password');
		
					$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="https://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>Untitled Document</title>
							</head>
							
							<body>
							<div style="border:8px solid #F58221; border-radius:8px; padding:5px; width:90%" >
							  <div style="border:4px solid #254E86; border-radius:8px; padding:5px; width:97%;" >
								<table width="100%">
								  <tr>
									<td align="center" style="background:#F58221;" height="25">&nbsp;</td>
								  </tr>
								  <tr>
									<td align="center" style="" height="25">&nbsp;</td>
								  </tr>
								  <tr>
									<td align="center"><img src="https://akwebtechnology.com/DITRP/assets/logo/thumbnail/9023592969.png" /></td>
								  </tr>
								  <tr>
									<td align="center"><h1 style="text-shadow: 1px 1px 1px #000, 3px 3px 5px #ccc;"><strong>ALL INDIA COUNCIL FOR PROFESSIONAL EXCELLENCE</strong></h1></td>
								  </tr>
								</table>
								<table width="100%" align="center">
								  <tr align="center">
									<td width="77%" valign="top"><table style="margin-left:20px" width="75%" height="300">
										<tr>
										  <td valign="top" align="center"><strong>Hello Admin</strong></td>
										</tr>
										<tr>
										  <td valign="top" align="center"><h1 style="margin-bottom:-10px">Forgot your password?</h1></td>
										</tr>
										<tr>
										  <td valign="top" align="center"><h3 style="margin-bottom:-10px;">Your password is as below:</h3></td>
										</tr>
										<tr>
										  <td valign="" align="center"><span style="background:#F58221; border-radius:8px; padding:10px 100px; margin-top:-30px"><strong>'.$pwd.'</strong></span></td>
										</tr>
									  </table></td>
								  </tr>
								</table>
							  </div>
							</div>
							</body>
							</html>
								';
					 $this->email->message($html);
	
					$this->email->send();
					$this->session->set_userdata('msg','Password send check email!');	
					redirect(base_url());	
				}
				else
				{
					$this->session->set_userdata('msg', '<font color="#FF0000">Email does not exists!</font>');	
					redirect(base_url());
				}
			}
			else
			{
				$email 			= $this->input->post('foremail');
				$institute_code = $this->input->post('institute_code');
				
				$query = $this->db->get_where('institute', array('institute_email' => trim($email), 'institute_code' => trim($institute_code)));
				
				if ($query->num_rows() > 0)
				{
					$queryFE   	  = $this->db->select('user_email')->from('admin')->where('id', 1);
					$queryFE   	  = $this->db->get();	
					$rowFE 	  	  = $queryFE->row();
					$from_email   = $rowFE->user_email;
					$from_email	  = trim($from_email);
						
					$queryS   	  	= $this->db->select('*')->from('institute')->where('institute_email', trim($email));
					$queryS   	  	= $this->db->get();	
					$row 	  	  	= $queryS->row();
					$pwd 	  	  	= $row->institute_password;
					$institute_name = $row->institute_name;
					
					$config['protocol'] = 'sendmail';
					$config['charset'] = 'iso-8859-1';
					$config['wordwrap'] = TRUE;
					$config['mailtype'] = 'html';
			
					$this->email->initialize($config);
			
					$this->email->from($from_email);
					$this->email->to($email);
			
					$this->email->subject('Retrive password');
		
					$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
							<html xmlns="https://www.w3.org/1999/xhtml">
							<head>
							<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							<title>Untitled Document</title>
							</head>
							
							<body>
							<div style="border:8px solid #F58221; border-radius:8px; padding:5px; width:90%" >
							  <div style="border:4px solid #254E86; border-radius:8px; padding:5px; width:97%;" >
								<table width="100%">
								  <tr>
									<td align="center" style="background:#F58221;" height="25">&nbsp;</td>
								  </tr>
								  <tr>
									<td align="center" style="" height="25">&nbsp;</td>
								  </tr>
								  <tr>
									<td align="center"><img src="https://akwebtechnology.com/DITRP/assets/logo/thumbnail/9023592969.png" /></td>
								  </tr>
								  <tr>
									<td align="center"><h1 style="text-shadow: 1px 1px 1px #000, 3px 3px 5px #ccc;"><strong>ALL INDIA COUNCIL FOR PROFESSIONAL EXCELLENCE</strong></h1></td>
								  </tr>
								</table>
								<table width="100%" align="center">
								  <tr align="center">
									<td width="77%" valign="top"><table style="margin-left:20px" width="75%" height="300">
										<tr>
										  <td valign="top" align="center"><strong>Hello '.$institute_name.'</strong></td>
										</tr>
										<tr>
										  <td valign="top" align="center"><h1>Forgot your password?</h1></td>
										</tr>
										<tr>
										  <td valign="top" align="center"><h3>Your password is as below:</h3></td>
										</tr>
										<tr>
										  <td valign="" align="center"><span style="background:#F58221; border-radius:8px; padding:20px 100px; margin-top-30px"><strong>'.$pwd.'</strong></span></td>
										</tr>
									  </table></td>
								  </tr>
								</table>
							  </div>
							</div>
							</body>
							</html>
								';
					$this->email->message($html);
	
					$this->email->send();
					$this->session->set_userdata('msg','Password send check email!');	
					redirect(base_url());	
				}
				else
				{
					$this->session->set_userdata('msg', '<font color="#FF0000">Email does not exists!</font>');	
					redirect(base_url());
				}
			}
		}
		else
		{
			return FALSE;	
		}
	}
	public function penter_exam()
	{
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		$student_id = $this->session->userdata('student_id');
		$session_id = $this->session->userdata('my_session_id');
		$subject_id = $this->session->userdata('multi_subject_id');
		
		if(!empty($_POST['actval']))	
		{
			//print_r($_POST);exit();
			$lang_id = $_POST['lang_id'];
			$queryS   	= $this->db->select('*,get_student_name(STUDENT_ID) AS STUDENT_NAME')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
			$queryS   	= $this->db->get();	
			$row 	  	= $queryS->row();
				
			$id 			= $row->STUD_COURSE_DETAIL_ID;
			$STUDENT_ID 			= $row->STUDENT_ID;
			$full_name 		= $row->STUDENT_NAME;
			$INSTITUTE_COURSE_ID	 	= $row->INSTITUTE_COURSE_ID;
			$institute_id 	= $row->INSTITUTE_ID;
			$courseinfo		= $this->get_inst_course_info($INSTITUTE_COURSE_ID);
			$COURSE_ID = $courseinfo['COURSE_ID'];
			$MULTI_SUB_COURSE_ID = $courseinfo['MULTI_SUB_COURSE_ID'];

			if(!empty($COURSE_ID) && $COURSE_ID != '' && $COURSE_ID != '0'){
				$queryE   	= $this->db->select('EXAM_ID,TOTAL_QUESTIONS')->from('exam_structure')->where('COURSE_ID', $COURSE_ID);
			$queryE   	= $this->db->get();	
			$rowE 	  	= $queryE->row();
			$TOTAL_QUESTIONS = $rowE->TOTAL_QUESTIONS;
			$EXAM_ID = $rowE->EXAM_ID;
			
			$this->db->order_by('QUESTION_ID', 'RANDOM');
			$this->db->where('COURSE_ID', $COURSE_ID);
			$this->db->where('ACTIVE', 1);
			$this->db->where('LANG_ID', $lang_id);
			$this->db->limit($TOTAL_QUESTIONS, 0);
			$query = $this->db->get('exam_question_bank');
			//echo $query->num_rows();exit;
			if ($query->num_rows() > 0) 
			{
				$result = $query->result_array();
				///echo "<pre>";print_r($result);echo "</pre>";exit;
				for($i=0;$i<count($result);$i++)
				{
					$this->db->set('exam_id', $EXAM_ID);
					$this->db->set('student_id', $STUDENT_ID);
					$this->db->set('institute_id', $institute_id);
					$this->db->set('question_id', $result[$i]['QUESTION_ID']);
					$this->db->set('question', $result[$i]['QUESTION']);
					$this->db->set('session_id', $session_id);
					$this->db->set('option_a', $result[$i]['OPTION_A']);
					$this->db->set('option_b', $result[$i]['OPTION_B']);
					$this->db->set('option_c', $result[$i]['OPTION_C']);
					$this->db->set('option_d', $result[$i]['OPTION_D']);
					$this->db->set('image', $result[$i]['IMAGE']);
					$this->db->set('correct_ans', $result[$i]['CORRECT_ANS']);
					$this->db->set('LANG_ID', $lang_id);
					$this->db->insert('p_exam_attempt'); 
					$id1 = $this->db->insert_id();
					
				}
				if($id1==TRUE)
				{
					redirect(base_url().'practice_test');
				}
			}
			else
			{
				return false;	
			}
		}

		if(!empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '' && $MULTI_SUB_COURSE_ID != '0'){
			$queryE   	= $this->db->select('EXAM_ID,TOTAL_QUESTIONS')->from('multi_sub_course_exam_structure')->where(array('MULTI_SUB_COURSE_ID' => $MULTI_SUB_COURSE_ID, 'COURSE_SUBJECT_ID' => $subject_id));
			$queryE   	= $this->db->get();	
			$rowE 	  	= $queryE->row();
			$TOTAL_QUESTIONS = $rowE->TOTAL_QUESTIONS;
			$EXAM_ID = $rowE->EXAM_ID;
			
			$this->db->order_by('QUESTION_ID', 'RANDOM');
			$this->db->where('MULTI_SUB_COURSE_ID', $MULTI_SUB_COURSE_ID);
			$this->db->where('COURSE_SUBJECT_ID', $subject_id);
			$this->db->where('ACTIVE', 1);
			$this->db->where('LANG_ID', $lang_id);
			$this->db->limit($TOTAL_QUESTIONS, 0);
			$query = $this->db->get('multi_sub_exam_question_bank');
			//echo $query->num_rows();exit;
			if ($query->num_rows() > 0) 
			{
				$result = $query->result_array();
				///echo "<pre>";print_r($result);echo "</pre>";exit;
				for($i=0;$i<count($result);$i++)
				{
					$this->db->set('exam_id', $EXAM_ID);
					$this->db->set('student_id', $STUDENT_ID);
					$this->db->set('institute_id', $institute_id);
					$this->db->set('question_id', $result[$i]['QUESTION_ID']);
					$this->db->set('question', $result[$i]['QUESTION']);
					$this->db->set('session_id', $session_id);
					$this->db->set('option_a', $result[$i]['OPTION_A']);
					$this->db->set('option_b', $result[$i]['OPTION_B']);
					$this->db->set('option_c', $result[$i]['OPTION_C']);
					$this->db->set('option_d', $result[$i]['OPTION_D']);
					$this->db->set('image', $result[$i]['IMAGE']);
					$this->db->set('correct_ans', $result[$i]['CORRECT_ANS']);
					$this->db->set('LANG_ID', $lang_id);
					$this->db->insert('multi_sub_p_exam_attempt'); 
					$id1 = $this->db->insert_id();
					
				}
				if($id1==TRUE)
				{
					redirect(base_url().'practice_test');
				}
			}
			else
			{
				return false;	
			}
		}

			
		}
	}
	
	public function get_pquestions()
	{
		$student_id = $this->session->userdata('student_id');
		$session_id = $this->session->userdata('my_session_id');
			
		$subject_id = $this->session->userdata('multi_subject_id');
		if(!empty($subject_id) && $subject_id !=''){
			$this->db->where('session_id', $session_id);
			//$this->db->limit($limit, $start);
			$query1 = $this->db->get('multi_sub_p_exam_attempt');
		}else{
			$this->db->where('session_id', $session_id);
			//$this->db->limit($limit, $start);
			$query1 = $this->db->get('p_exam_attempt');
		}
		return $query1->result_array();
	}
	public function pend_exam()
	{
		$session_id = $this->session->userdata('my_session_id');
		if(!empty($_POST['end_exam']))
		{ 
			$update=$this->db->delete('p_exam_attempt', array('session_id' => $session_id)); 
		
			if($update==TRUE)
			{
				$this->session->unset_userdata('session_id');
				$this->session->unset_userdata('student_id');
				$this->session->unset_userdata('end_exam');
				$this->session->unset_userdata('pend_exam');
				
				$this->session->sess_destroy();
				redirect(base_url());
			}
		}
		else
		{
			return false;	
		}
	}
    public function pexam_over()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){
			if(!empty($_POST['exam_over1']) && $_POST['exam_over1']==1)
			{
				$this->session->set_userdata('pend_exam', 1);
				$post = $_POST['ad_opt1'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				
				$exam_attempt_id = $_POST['exam_attempt_id1'];
				
				$queryS   	= $this->db->select('correct_ans')->from('multi_sub_p_exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
					
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1);
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$update = $this->db->update('multi_sub_p_exam_attempt');
				
				if($update==TRUE)
				{
					redirect(base_url().'practice_result');
				}
			
			}
			else
			{
				return false;	
			}
		}else{
			if(!empty($_POST['exam_over1']) && $_POST['exam_over1']==1)
			{
				$this->session->set_userdata('pend_exam', 1);
				$post = $_POST['ad_opt1'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				
				$exam_attempt_id = $_POST['exam_attempt_id1'];
				
				$queryS   	= $this->db->select('correct_ans')->from('p_exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
					
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1);
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$update = $this->db->update('p_exam_attempt');
				
				if($update==TRUE)
				{
					redirect(base_url().'practice_result');
				}
			
			}
			else
			{
				return false;	
			}
		}
		
	}
	public function psession_over()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');

		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){
			if(!empty($_POST['sess_end1']) && $_POST['sess_end1']==1)
			{
				$this->session->set_userdata('pend_exam', 1);
				$post = $_POST['ad_opt2'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id2'];
				
					$queryS   	= $this->db->select('correct_ans')->from('multi_sub_p_exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($correct_ans==$ad_opt)
					{
						$answer_status = 1;
					}
					else
					{
						$answer_status = 0;
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}


					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1);
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$update = $this->db->update('multi_sub_p_exam_attempt');
					
					if($update==TRUE)
					{ 
						if(!empty($_POST['sess_name']))
						{
							echo $sess_name = $_POST['sess_name'];
						}
						else
						{
							$sess_name = '';
						}
						$this->session->set_userdata('session_id', $sess_name);
						redirect(base_url().'practice_result');
					}
			
			}
			else
			{
				return false;	
			}
		}else{
			if(!empty($_POST['sess_end1']) && $_POST['sess_end1']==1)
			{
				$this->session->set_userdata('pend_exam', 1);
				$post = $_POST['ad_opt2'];
					
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id2'];
				
					$queryS   	= $this->db->select('correct_ans')->from('p_exam_attempt')->where('id', $exam_attempt_id);
					$queryS   	= $this->db->get();	
					$row 	  	= $queryS->row();
					$correct_ans= $row->correct_ans;
					
					if($correct_ans==$ad_opt)
					{
						$answer_status = 1;
					}
					else
					{
						$answer_status = 0;
					}
					
					if($ad_opt=='option_a')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',1); 
					}
					elseif($ad_opt=='option_b')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_b_chk',1); 
					}
					elseif($ad_opt=='option_c')
					{
						$this->db->set('option_d_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_c_chk',1); 
					}


					elseif($ad_opt=='option_d')
					{
						$this->db->set('option_c_chk',0); 
						$this->db->set('option_b_chk',0); 
						$this->db->set('option_a_chk',0); 
						$this->db->set('option_d_chk',1);
					}
					$this->db->set('answer_status',$answer_status);
					$this->db->where('id', $exam_attempt_id);
					$update = $this->db->update('p_exam_attempt');
					
					if($update==TRUE)
					{ 
						if(!empty($_POST['sess_name']))
						{
							echo $sess_name = $_POST['sess_name'];
						}
						else
						{
							$sess_name = '';
						}
						$this->session->set_userdata('session_id', $sess_name);
						redirect(base_url().'practice_result');
					}
			
			}
			else
			{
				return false;	
			}
		}

		
		
	}
    public function psave_n_next()
	{ 
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){

			if( !empty($_POST['save_next'] ) && $_POST['save_next'] == 1) 
			{ 
				$serial = $_POST['serial'];
				$post = $_POST['ad_opt_'.$serial];
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id_'.$serial];
				
				$queryS   	= $this->db->select('correct_ans')->from('multi_sub_p_exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
				
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1); 
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$this->db->update('multi_sub_p_exam_attempt');
			}
			else
			{
				return false;	
			}
		}else{
			if( !empty($_POST['save_next'] ) && $_POST['save_next'] == 1) 
			{ 
				$serial = $_POST['serial'];
				$post = $_POST['ad_opt_'.$serial];
				if(!empty($post))
				{
					$ad_opt = $post;
				}
				else
				{
					$ad_opt = '';
				}
				$exam_attempt_id = $_POST['exam_attempt_id_'.$serial];
				
				$queryS   	= $this->db->select('correct_ans')->from('p_exam_attempt')->where('id', $exam_attempt_id);
				$queryS   	= $this->db->get();	
				$row 	  	= $queryS->row();
				$correct_ans= $row->correct_ans;
				if($correct_ans==$ad_opt)
				{
					$answer_status = 1;
				}
				else
				{
					$answer_status = 0;
				}
				
				if($ad_opt=='option_a')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',1); 
				}
				elseif($ad_opt=='option_b')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_b_chk',1); 
				}
				elseif($ad_opt=='option_c')
				{
					$this->db->set('option_d_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_c_chk',1); 
				}
				elseif($ad_opt=='option_d')
				{
					$this->db->set('option_c_chk',0); 
					$this->db->set('option_b_chk',0); 
					$this->db->set('option_a_chk',0); 
					$this->db->set('option_d_chk',1); 
				}
				$this->db->set('answer_status',$answer_status);
				$this->db->where('id', $exam_attempt_id);
				$this->db->update('p_exam_attempt');
			}
			else
			{
				return false;	
			}
		}
	}
	public function pcorrect_answer()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){

			$query = $this->db->get_where('multi_sub_p_exam_attempt', array('session_id' => $session_id, 'answer_status' =>1,'student_id'=>$student_id));
		}else{
			$query = $this->db->get_where('p_exam_attempt', array('session_id' => $session_id, 'answer_status' =>1,'student_id'=>$student_id));
		}
		return $query->num_rows;
		
	}
	public function pincorrect_answer()
	{
		$session_id = $this->session->userdata('my_session_id');
		$student_id = $this->session->userdata('student_id');
		$subject_id = $this->session->userdata('multi_subject_id');

		if(!empty($subject_id) && $subject_id !=''){

			$query = $this->db->get_where('multi_sub_p_exam_attempt', array('session_id' => $session_id, 'answer_status' =>0, 'student_id'=>$student_id));
		}else{
			$query = $this->db->get_where('p_exam_attempt', array('session_id' => $session_id, 'answer_status' =>0, 'student_id'=>$student_id));
		}
		return $query->num_rows;
		
	}
	public function end_exam_on_browser_close()
	{
		$session_id = $this->session->userdata('my_session_id');
		
		$update=$this->db->delete('exam_attempt', array('session_id' => $session_id)); 
	
		if($update==TRUE)
		{
			$this->session->unset_userdata('session_id');
			$this->session->unset_userdata('student_id');
			$this->session->unset_userdata('end_exam');
			$this->session->unset_userdata('pend_exam');
			$this->session->unset_userdata('my_session_id');
			
			$this->session->sess_destroy();
		}
	}
	public function demo_counts()
	{
		$student_course_detail_id = $this->session->userdata('student_course_detail_id');
		//$student_id = $this->session->userdata('student_id');
		
		$queryS   	  = $this->db->select('DEMO_COUNT')->from('student_course_details')->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
		$queryS   	  = $this->db->get();	
		$row 	  	  = $queryS->row();
		$demo_count   = $row->DEMO_COUNT;
		$new_count    = $demo_count+1;
		
		$this->db->set('DEMO_COUNT',$new_count);  
		$this->db->where('STUD_COURSE_DETAIL_ID', $student_course_detail_id);
		$this->db->update('student_course_details');
	}
	
	//get course name from institute couse table
	public function get_inst_course_info($inst_course_id)
	{
		$res = "";
		$queryExam   	= $this->db->select('*')->from('institute_courses')->where('INSTITUTE_COURSE_ID', $inst_course_id);
		$queryExam   	= $this->db->get();	
		$rowExam 	  	= $queryExam->row();
		$COURSE_ID		= $rowExam->COURSE_ID;
		$MULTI_SUB_COURSE_ID		= $rowExam->MULTI_SUB_COURSE_ID;
		$COURSE_TYPE	= $rowExam->COURSE_TYPE;	
		
		if($COURSE_ID !='' && !empty($COURSE_ID) && $COURSE_ID !='0'){
			$query = $this->db->get_where('institute_courses', array('INSTITUTE_COURSE_ID' => $inst_course_id));
				
			if ($query->num_rows() > 0)
			{	
				//$data 	= $ex->fetch_assoc();
				$course = $this->get_course_detail($COURSE_ID);
				$res	= $course;
			}
		}
		if($MULTI_SUB_COURSE_ID !='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID !='0'){
			$query = $this->db->get_where('institute_courses', array('INSTITUTE_COURSE_ID' => $inst_course_id));
				
			if ($query->num_rows() > 0)
			{	
				//$data 	= $ex->fetch_assoc();
				$course = $this->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
				$res	= $course;
			}
		}
		
		return $res;
	}
	public function get_course_detail($course_id)
	{
		$course_name = array();

	
		$tableName="courses";
	
		$queryExam   				= $this->db->select('*')->from($tableName)->where('COURSE_ID', $course_id);		
		$queryExam   				= $this->db->get();	
		
		$rowExam 	  				= $queryExam->row();
		$COURSE_ID					= $rowExam->COURSE_ID;
		$COURSE_NAME				= $rowExam->COURSE_NAME;
		$COURSE_FEES				= $rowExam->COURSE_FEES;
		$COURSE_DURATION			= $rowExam->COURSE_DURATION;
		$COURSE_DETAILS				= $rowExam->COURSE_DETAILS;
		$COURSE_ELIGIBILITY			= $rowExam->COURSE_ELIGIBILITY;
		//$COURSE_TYPE				= $rowExam->COURSE_TYPE;
		$course_name['COURSE_ID'] 	= $rowExam->COURSE_ID;
		$course_name['COURSE_NAME'] = $rowExam->COURSE_NAME;
		$course_name['COURSE_FEES'] = $rowExam->COURSE_FEES;
		$course_name['COURSE_DURATION'] = $rowExam->COURSE_DURATION;
		$course_name['COURSE_DETAILS'] = $rowExam->COURSE_DETAILS;
		$course_name['COURSE_ELIGIBILITY'] = $rowExam->COURSE_ELIGIBILITY;		
		$course_name['COURSE_CODE'] =  $rowExam->COURSE_CODE;
		
		return $course_name;
	}

	public function get_course_detail_multi_sub($multi_sub_course_id)
	{
		$course_name = array();

	
		$tableName="multi_sub_courses";
	
		$queryExam   				= $this->db->select('*')->from($tableName)->where('MULTI_SUB_COURSE_ID', $multi_sub_course_id);		
		$queryExam   				= $this->db->get();	
		
		$rowExam 	  				= $queryExam->row();
		$COURSE_ID					= $rowExam->MULTI_SUB_COURSE_ID;
		$COURSE_NAME				= $rowExam->MULTI_SUB_COURSE_NAME;
		$COURSE_FEES				= $rowExam->MULTI_SUB_COURSE_FEES;
		$COURSE_DURATION			= $rowExam->MULTI_SUB_COURSE_DURATION;
		$COURSE_DETAILS				= $rowExam->MULTI_SUB_COURSE_DETAILS;
		$COURSE_ELIGIBILITY			= $rowExam->MULTI_SUB_COURSE_ELIGIBILITY;
		//$COURSE_TYPE				= $rowExam->COURSE_TYPE;
		$course_name['MULTI_SUB_COURSE_ID'] 	= $rowExam->MULTI_SUB_COURSE_ID;
		$course_name['MULTI_SUB_COURSE_NAME'] = $rowExam->MULTI_SUB_COURSE_NAME;
		$course_name['MULTI_SUB_COURSE_FEES'] = $rowExam->MULTI_SUB_COURSE_FEES;
		$course_name['MULTI_SUB_COURSE_DURATION'] = $rowExam->MULTI_SUB_COURSE_DURATION;
		$course_name['MULTI_SUB_COURSE_DETAILS'] = $rowExam->MULTI_SUB_COURSE_DETAILS;
		$course_name['MULTI_SUB_COURSE_ELIGIBILITY'] = $rowExam->MULTI_SUB_COURSE_ELIGIBILITY;		
		$course_name['MULTI_SUB_COURSE_CODE'] =  $rowExam->MULTI_SUB_COURSE_CODE;
		
		return $course_name;
	}
	
}