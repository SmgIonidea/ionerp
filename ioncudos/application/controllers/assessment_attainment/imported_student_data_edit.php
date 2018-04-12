<?php
/*
* Description	:	CIA marks upload with total marks student usn wise using grid

* Created		:	Jan 25th, 2016

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Imported_student_data_edit extends CI_Controller{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}
		$this->load->model('assessment_attainment/imported_student_data_edit/imported_student_data_edit_model');
	}
	
	/* Function to edit imported student marks data
	* @param: Qpd id
	*/
	function index(){
		$data['title'] = "Student Data Edit";
		$data["dept_data"] = $this->imported_student_data_edit_model->dept_fill();
		$this->load->view('assessment_attainment/imported_student_data_edit/imported_student_data_edit_vw',$data);
	}
	
	/* Function is used to fetch program names from program table.
	* @param- 
	* @returns - an object.
	*/
    public function select_pgm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$dept_id = $this->input->post('dept_id');
			$pgm_data = $this->imported_student_data_edit_model->pgm_fill($dept_id);
			$pgm_data = $pgm_data['pgm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Program</option>';
			$i++;
			foreach ($pgm_data as $data) {
				$list[$i] = "<option value = " . $data['pgm_id'] . ">" . $data['pgm_acronym'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/* Function is used to fetch curriculum names from curriculum table.
	* @param- 
	* @returns - an object.
	*/
    public function select_crclm_list() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$pgm_id = $this->input->post('pgm_id');
			$curriculum_data = $this->imported_student_data_edit_model->crclm_fill($pgm_id);
			$curriculum_data = $curriculum_data['crclm_result'];
			$i = 0;
			$list[$i] = '<option value="">Select Curriculum</option>';
			$i++;
			foreach ($curriculum_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_id'] . ">" . $data['crclm_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/* Function is used to fetch term names from crclm_terms table.
	* @param- 
	* @returns - an object.
	*/
    public function select_termlist() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_data = $this->imported_student_data_edit_model->term_fill($crclm_id);
			$term_data = $term_data['res2'];
			$i = 0;
			$list[$i] = '<option value="">Select Term</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
				$i++;
			}
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
        * Function is to fetch term list for term drop down box.
        * @param - ------.
        * returns the list of terms.
	*/
    public function select_course() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$term = $this->input->post('term_id');
			$term_data = $this->imported_student_data_edit_model->course_fill($term);
			$i = 0;
			$list[$i] = '<option value="">Select Course</option>';
			$i++;
			foreach ($term_data as $data) {
				$list[$i] = "<option value=" . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
				$i++;
			}				
			$list = implode(" ", $list);
			echo $list;
		}
    }
	
	/*
        * Function is to fetch occasions list for occasion drop down box.
        * @param - ------.
        * returns the list of occasions.
	*/
    public function select_occasion() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$occasion_data = $this->imported_student_data_edit_model->occasion_fill($crclm_id,$term_id,$crs_id);
			$i = 0;
			$list = array();
			if($occasion_data){
				$list[$i] = '<option value="">Select Occasion</option>';
				$i++;
				foreach ($occasion_data as $data) {
					$list[$i] = "<option value=" . $data['qpd_id'] . ">" . $data['ao_description'] . "</option>";
					$i++;
				}				
				$list = implode(" ", $list);
			}
			if($list) {
				echo $list;
			}else {
				echo 0;
			}
		}
    }
	
	/**
	*	Function is to fetch the qp_id of TEE that is rolled out
	*	@param - -----.
	*	returns qp_id 
	*/
	public function getTEEQPId() {
			if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$TEE_data = $this->imported_student_data_edit_model->getTEEQPId($crclm_id,$term_id,$crs_id);
			if($TEE_data) {
				echo $TEE_data[0]['qpd_id'];
			} else {
				echo 0;
			}
		}
	}
	
	function get_imported_student_data(){
		$qpd_id = $this->input->post('qpd_id');
		$course_marks = $this->imported_student_data_edit_model->student_marks($qpd_id);
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($course_marks);
	}
	
	function get_table_fields_data(){
		$qpd_id = $this->input->post('qpd_id');
		$course_marks = $this->imported_student_data_edit_model->student_marks($qpd_id);
		if(!empty($course_marks)) {
			$count = 0;
			$fields_arr = array();
			$ques = 0;
			$data_ids = array();
			$data = '';
			//var_dump($course_marks[0]);exit;
			foreach ($course_marks[0] as $header => $value) {
				$count++;
				$field_name = '';
				$field_title = $header;
				$temp_student_data_title = str_replace("Q_No_", "", $header)."";
				$temp_student_title = str_replace("_", " ", $temp_student_data_title)."";
				$temp_student_usn_title = strtoupper($temp_student_title); 
				$is_visible = true;
				$is_editable = true;
				if($count % 2 !=0 && $count!=1 || $field_title=='qpd_id' ){
					$is_visible = false;
				}
				if($count==1){
					$is_editable = false;
				}
				$input = array(
				'name'=>$field_title,
				'type'=>'text',
				'width'=>40,
				'title'=>$temp_student_usn_title,
				'align'=> 'center',
				'visible'=>$is_visible,
				'editing'=>$is_editable,);
				
				array_push($fields_arr,$input);
				if($count==sizeof($course_marks[0])){
					array_push($fields_arr,array('type'=>"control",'deleteButton'=> false));
				}
			}
			$this->output->set_header('Content-Type: application/json; charset=utf-8');
			echo json_encode($fields_arr);
		}
	}
	
	function update_marks(){
		$update_data = array(); $where = array(); $i=0; $usn='';
		foreach($this->input->post() as $head=>$data){
			$i++;
			if($i % 2 !=0 && $i!=1){
				$where[$head]=$data;
			}else if($i!=1){
				$update_data[$head]=$data;
			}else{
				$usn = $data;
			}
		}
		$k=0;
		foreach($update_data as $data=>$val){
			if($data!='total_marks'){
				$where_cond = array(
				'qp_subq_code'=>$data,
				'qp_mq_id'=>$where[$data.'_id'],
				'student_usn'=>$usn,
				);
				if($val!=''){
					$this->imported_student_data_edit_model->update(array('secured_marks'=>$val),$where_cond);
				}
				}elseif($data == 'total_marks'){
					$this->imported_student_data_edit_model->update_total_marks(array('total_marks'=>$val),array('qpd_id'=>$where['qpd_id'],'student_usn'=>$usn));
				}
			
		}
	}
	
}//end of  class imported_student_data_edi