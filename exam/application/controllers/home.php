<?php
class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('home_model');
		$this->load->library('email');
		$this->load->library('encrypt');
		$this->load->helper('path');
		$this->load->helper('download');
		$this->load->library('pagination');
		$this->load->library("My_pagination");
		$this->load->library("spagination");
		$this->load->library("npagination");
		$this->load->library("allpagination");
		$this->load->library("snpagination");
		$this->load->library("ppagination");
	}
	public function index()
	{
		$student_id = $this->session->userdata('student_id');
		if(!empty($student_id))
		{
			redirect(base_url().'terms');
		}
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['enter_student_no'] 	= $this->home_model->enter_student_no();
		//$data['login_status'] 		= $this->home_model->set_login();
		//$data['set_forgot'] 		= $this->home_model->set_forgot();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('index', $data);
	}
	public function terms()
	{	
		// echo '<pre>';	
		// print_r($this->session);
		$student_id = $this->session->userdata('student_id');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		$data['penter_exam']   		= $this->home_model->penter_exam();
		$data['student_course_detail_id'] =  $this->session->userdata('student_course_detail_id');
		$data['multi_subject_id'] =  base64_decode(trim($_REQUEST['subject_id']));
		$data['get_exam_terms'] 	= $this->home_model->get_exam_terms();
		$data['generate_opt'] 		= $this->home_model->generate_opt();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('terms', $data);
	}
	public function otp()
	{
		$student_id = $this->session->userdata('student_id');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['enter_exam'] 		= $this->home_model->enter_exam();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('otp', $data);
	}
	public function logout()
    {
	   $this->home_model->set_logout();
	}
	public function result()
	{
		$student_id = $this->session->userdata('student_id');
		$end_exam = $this->session->userdata('end_exam');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		if(empty($end_exam))
		{
			$this->home_model->set_logout();
		}
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		$data['correct_answer']		= $this->home_model->correct_answer();
		$data['incorrect_answer']	= $this->home_model->incorrect_answer();
		$data['get_exam_result']	= $this->home_model->get_exam_result();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('result', $data);
	}
	/*public function practice_test()
	{
		$student_id = $this->session->userdata('student_id');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		$data['exam_details'] 		= $this->home_model->get_exam_details();
		$count_row = $data['exam_details']['total_questions'];
		$config = array();
		$config["base_url"] = base_url() . "home/practice_test";
		$config["total_rows"] = $count_row;
		$config['per_page'] = 1;
		$config['uri_segment'] = 3;
		$config['num_links'] = 1;
		$config['display_prev_link'] =TRUE; 
		$config['display_next_link']=TRUE; 
		$config['display_first_link'] =TRUE; 
		$config['display_last_link']=TRUE; 
		$config['first_link'] = '&lt;';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '&gt;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '&gt;&gt;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lt;&lt;';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="" class="btn-default">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="hidden">';
		$config['num_tag_close'] = '</li>';
		
		$config1 = array();
		$config1["base_url"] = base_url() . "home/practice_test";
		$config1["total_rows"] = $count_row;
		$config1['per_page'] = 1;
		$config1['uri_segment'] = 3;
		$config1['display_pages'] = FALSE;
		$config1['first_link'] = FALSE;
		$config1['last_link'] = FALSE;
		$config1['prev_link'] = FALSE;
		$config1['display_next_link']=TRUE; 
		$config1['next_link'] = 'SKIP';
		$config1['next_tag_open'] = '';
		$config1['next_tag_close'] = '';
		
		$config2 = array();
		$config2["base_url"] = base_url() . "home/practice_test";
		$config2["total_rows"] = $count_row;
		$config2['per_page'] = 1;
		$config2['uri_segment'] = 3;
		$config2['num_links'] = 1;
		$config2['first_link'] = FALSE;
		$config2['first_tag_open'] = '';
		$config2['first_tag_close'] = '';
		$config2['last_link'] = FALSE;
		$config2['last_tag_open'] = '';
		$config2['last_tag_close'] = '';
		$config2['next_link'] = FALSE;
		$config2['next_tag_open'] = '';
		$config2['next_tag_close'] = '';
		$config2['prev_link'] = FALSE;
		$config2['prev_tag_open'] = '';
		$config2['prev_tag_close'] = '';
		$config2['cur_tag_open'] = '';
		$config2['cur_tag_close'] = '';
		$config2['num_tag_open'] = '<span class="hidden">';
		$config2['num_tag_close'] = '</span>';
		
		$config3 = array();
		$config3["base_url"] = base_url() . "home/practice_test";
		$config3["total_rows"] = $count_row;
		$config3['per_page'] = 1;
		$config3['uri_segment'] = 3;
		$config3['num_links'] = 50;
		$config3['first_link'] = FALSE;
		$config3['first_tag_open'] = '';
		$config3['first_tag_close'] = '';
		$config3['last_link'] = FALSE;
		$config3['last_tag_open'] = '';
		$config3['last_tag_close'] = '';
		$config3['next_link'] = FALSE;
		$config3['next_tag_open'] = '';
		$config3['next_tag_close'] = '';
		$config3['prev_link'] = FALSE;
		$config3['prev_tag_open'] = '';
		$config3['prev_tag_close'] = '';
		$config3['cur_tag_open'] = '<a href="#" id="pg_1" onclick="submitFrm(2)" class="btn btn-default1">';
		$config3['cur_tag_close'] = '</a>';
		
		$config4 = array();
		$config4["base_url"] = base_url() . "home/practice_test";
		$config4["total_rows"] = $count_row;
		$config4['per_page'] = 1;
		$config4['uri_segment'] = 3;
		$config4['display_pages'] = FALSE;
		$config4['first_link'] = FALSE;
		$config4['last_link'] = FALSE;
		$config4['prev_link'] = FALSE;
		$config4['display_next_link']=TRUE; 
		$config4['next_link'] = 'SAVE & NEXT';
		$config4['next_tag_open'] = '';
		$config4['next_tag_close'] = '';
		
		$config5 = array();
		$config5["base_url"] = base_url() . "home/practice_test";
		$config5["total_rows"] = $count_row;
		$config5['per_page'] = 1;
		$config5['uri_segment'] = 3;
		$config5['display_pages'] = FALSE;
		$config5['first_link'] = FALSE;
		$config5['last_link'] = FALSE;
		$config5['prev_link'] = 'BACK';
		$config5['display_prev_link']=TRUE; 
		$config5['next_link'] = FALSE;
		$config5['next_tag_open'] = '';
		$config5['next_tag_close'] = '';
		
		
		$this->my_pagination->initialize($config);
		$this->spagination->initialize($config1);
		$this->npagination->initialize($config2);
		$this->allpagination->initialize($config3);
		$this->snpagination->initialize($config4);
		$this->ppagination->initialize($config5);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
        
		$data["get_questions"] = $this->home_model->get_pquestions($config["per_page"], $page);
		
		$data["links"] 		= $this->my_pagination->create_links();
		$data["skips"] 		= $this->spagination->create_linking();
		$data["numbs"] 		= $this->npagination->create_linkings();
		$data["all_links"] 	= $this->allpagination->create_all_links();
		$data["sn_linking"] = $this->snpagination->create_sn_linking();
		$data["p_linking"]  = $this->ppagination->create_p_linking();
		
		
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		//$data['set_session_out'] 	= $this->home_model->set_session_out();
        
		$data['pend_exam'] 			= $this->home_model->pend_exam();
		$data['pexam_over'] 		= $this->home_model->pexam_over();
		$data['psession_over'] 		= $this->home_model->psession_over();
		$data['save_n_next'] 		= $this->home_model->psave_n_next();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
	 	if($this->input->post('save_next')) {
		 $this->load->view('ajaxpagination',$data);
		} 
		elseif($this->input->post('other')) {
		 $this->load->view('ajaxpagination',$data);
		}
		else {
		 $this->load->view('practice_test',$data);
	   }
		
	}*/
	public function practice_result()
	{
		$student_id = $this->session->userdata('student_id');
		$end_exam = $this->session->userdata('pend_exam');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		if(empty($end_exam))
		{
			$this->home_model->set_logout();
		}
		$data['student_course_detail_id'] =  $this->session->userdata('student_course_detail_id');
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		$data['correct_answer']		= $this->home_model->pcorrect_answer();
		$data['incorrect_answer']	= $this->home_model->pincorrect_answer();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('practice_result', $data);
	}
	public function end_exam_on_close()
	{
		$data['end_exam_on_browser_close']	= $this->home_model->end_exam_on_browser_close();
	}
	public function exam()
	{
		$student_id = $this->session->userdata('student_id');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		$data['exam_details'] 		= $this->home_model->get_exam_details();
		$data["get_questions"] 		= $this->home_model->get_questions();
		
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		//$data['exam_over'] 			= $this->home_model->exam_over();
		//$data['session_over'] 		= $this->home_model->session_over();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('exam',$data);
	}
	public function save_n_next()
	{
		$data['save_n_next'] = $this->home_model->save_n_next();
		
	}
	public function exam_overs()
	{
		$data['exam_over'] 		= $this->home_model->exam_over();
		
	}
	public function sess_overs()
	{
		$data['session_over'] 		= $this->home_model->session_over();
		
	}
	public function practice_test()
	{
		$student_id = $this->session->userdata('student_id');
		$data['student_course_detail_id'] =  $this->session->userdata('student_course_detail_id');
		if(empty($student_id))
		{
			redirect(base_url());
		}
		$data['demo_counts'] 		= $this->home_model->demo_counts();
		$data['exam_details'] 		= $this->home_model->get_exam_details();
		$data["get_questions"] 		= $this->home_model->get_pquestions();
		
		$data['site_settings'] 		= $this->home_model->site_settings();
		$data['get_student'] 		= $this->home_model->get_student();
		//$data['exam_over'] 			= $this->home_model->exam_over();
		//$data['session_over'] 		= $this->home_model->session_over();
		$data['title'] 		     	= '';
		$data['desc'] 		 		= '';
		$data['keywords']  	 		= '';
		
		$this->load->view('practice_test',$data);
	}
	public function psave_n_nexts()
	{
		$data['save_n_next'] 		= $this->home_model->psave_n_next();
		
	}
	public function pexam_overs()
	{
		$data['pexam_over'] 		= $this->home_model->pexam_over();
		
	}
	public function psess_over()
	{
		$data['session_over'] 		= $this->home_model->psession_over();
		
	}
}