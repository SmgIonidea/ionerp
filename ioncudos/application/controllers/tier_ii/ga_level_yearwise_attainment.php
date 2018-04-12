<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Description	:	Tier II Graduate Attributes (GAs) Level Yearwise Attainment

* Created		:	December 31st, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
class Ga_level_yearwise_attainment extends CI_Controller{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
		$this->load->model('assessment_attainment/tier_ii/ga_level_yearwise_attainment/ga_level_yearwise_attainment_model');
	}
	
	function index(){
		$data["title"] = "GA level attainment";
		$data["department_list"] = $this->ga_level_yearwise_attainment_model->get_department_list();
		$this->load->view('assessment_attainment/tier_ii/ga_level_yearwise_attainment/ga_level_yearwise_attainment_vw',$data);
	}
	
	//function to list programs depending on department
	function get_programs_by_dept(){
		$dept_id = $this->input->post('dept_id');
		$program_data = $this->ga_level_yearwise_attainment_model->get_program_list($dept_id);
		$list = "";
		$list .= "<option value='0'>Select Program</option>";
		if(empty($program_data)){
			$list .= "<option value=''>No pograms to display</option>";
		}else{
			foreach($program_data as $program){
				$list .= "<option value='".$program['pgm_id']."'>".$program['pgm_acronym']."</option>";
			}
		}
		echo $list;
	}
	
	//function to get ga year wisereport
	function get_ga_report_year_wise(){
		$dept_id = $this->input->post('dept_id');
		$pgm_id = $this->input->post('pgm_id');
		$year = $this->input->post('year');
		$program_data = $this->ga_level_yearwise_attainment_model->get_ga_report($dept_id,$pgm_id,$year);
		echo json_encode($program_data);
	}
	//function to export data to pdf
	function export_to_pdf(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
			//redirect them to the home page because they must be an administrator or owner to view this
			redirect('curriculum/peo/blank', 'refresh');
		} else {
			$ga_attainment_graph_data = $this->input->post('ga_attainment_graph_data_hidden');
			
			$this->load->helper('pdf');
			$content = "<p>".$ga_attainment_graph_data."</p>";
			pdf_create($content,'ga_attainment','L',100);
			return;
		}
	}
	//function to get drill down for GA
	function get_ga_drill_down(){
		$ga_id = $this->input->post('ga_id');
		$pgm_id = $this->input->post('pgm_id');
		$year = $this->input->post('year');
		$drill_down_data = $this->ga_level_yearwise_attainment_model->get_ga_drill_down_data($ga_id,$pgm_id,$year);
		echo json_encode($drill_down_data);
	}
	//function to get drilldown for curriculum
	function get_crclm_drill_down(){
		$crclm_id = $this->input->post('crclm_id');
		$pgm_id = $this->input->post('pgm_id');
		$year = $this->input->post('year');
		$ga_id = $this->input->post('ga_id');
		$drill_down_data = $this->ga_level_yearwise_attainment_model->get_crclm_drill_down_data($ga_id,$crclm_id,$pgm_id,$year);
		echo json_encode($drill_down_data);
	}
}//end of class