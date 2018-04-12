<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clopomap_approve extends CI_Controller {
	
	function index() {
		$this->load->model('curriculum/clo/cloadd_model');
		
		$crclm_name = $this->cloadd_model->crclm_fill();
	    $data['results'] = $crclm_name['res2'];
		
		$data['crclm_id'] = array(
			'name'  => 'crclm_id',
			'id'    => 'crclm_id',
			'class' => 'required', 
			'type'  => 'hidden',
		);
		
		$data['title']="CO to PO Mapping Page";
		$this->load->view('curriculum/clo/clopomap_approve_vw', $data);
	}
	
	public function select_term() {
		$crclm_id=$this->input->post('crclm_id');
		$this->load->model('curriculum/clo/cloadd_model');
		$term_data=$this->cloadd_model->term_fill($crclm_id);
		
		$term_data= $term_data['res2'];

		$i=0;
		$list[$i]='<option value="">Select Term</option>';
		$i++;
	
		foreach($term_data as $data) {
			$list[$i]="<option value=".$data['crclm_term_id'].">".$data['term_name']."</option>";
			++$i;
		}
		
		$list = implode(" ", $list);
		echo $list;
	}
	
	public function select_course() {
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		
		$this->load->model('curriculum/clo/cloadd_model');
		$course_data = $this->cloadd_model->course_fill($term_id);
		
		$course_data = $course_data['res2'];
		$i = 0;
		$list[$i] = '<option value="">Select Course</option>';
		$i++;

		foreach($course_data as $data) {
			$list[$i]="<option value=".$data['crs_id'].">".$data['crs_title']."</option>";
			$i++;
		}

		$list=implode(" ", $list);
		echo $list;
	
	}
	
	
	public function clo_add() {
		$data['title']="CO to PO Mapping Page";
		$this->load->view('curriculum/clo/clopomap_approve_vw',$data);
	}
	
	public function clo_details()
	{
		$crclm_id=$this->input->post('crclm_id');
		
		$course_id=$this->input->post('course_id');		
		$term_id=$this->input->post('term_id');
		$this->load->model('curriculum/clo/cloadd_model');
		
		$data= $this->cloadd_model->clomap_details($crclm_id,$course_id,$term_id);
		
		$data['title']="CO to PO Mapping Page";		
		$this->load->view('curriculum/clo/clopomap_approvedisplaygrid_vw',$data);

	}
	

	
	}
	?>