<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* Description	:	Tier II Graduate Attributes (GAs) Level Attainment

* Created		:	December 21st, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
?>
<?php
class Ga_level_attainment extends CI_Controller{
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
		$this->load->model('assessment_attainment/tier_ii/ga_level_attainment/ga_level_attainment_model','ga_model',TRUE);
	}
	
	function index(){
		$data["title"] = "GA level attainment";
		$data["department_list"] = $this->ga_model->get_department_list();
		$this->load->view('assessment_attainment/tier_ii/ga_level_attainment/ga_level_attainment_vw',$data);
	}
	function populate_program_list(){
		$program_type_list = $this->ga_model->get_program_type();
		$options = "";
		$options .= '<option value="">Select Program Type</option>';
		if(empty($program_type_list)){
			$options .= '<option value="">No program type to display</option>';	
		}else{
			foreach($program_type_list as $pgm){
				$options .= '<option value="'.$pgm['pgmtype_id'].'">'.$pgm["pgmtype_name"].'</option>';
			}
		}
		echo $options;
	}
	
	function poppulate_ga(){
		$pgm_type_id = $this->input->post("pgm_type_id");
		$ga_list = $this->ga_model->get_ga_for_pgm_type($pgm_type_id);
		$options = "";
		$options .= '<option value="0">Select GA</option>';
		if(empty($ga_list)){
			$options .= '<option value="0">No GA to display</option>';	
		}else{
			foreach($ga_list as $ga){
				$options .= '<option value="'.$ga['ga_id'].'">'.$ga["ga_statement"].'</option>';
			}
		}
		echo $options;
	}
	
	function get_ga_data(){
		$ga_id = $this->input->post('ga_id');
		$dept_id = $this->input->post('dept_id');
		$pgm_type = $this->input->post('pgmtype_id');
		$ga_graph_data = $this->ga_model->get_crclmwise_ga_data($dept_id,$pgm_type,$ga_id);
		if($ga_graph_data){
			echo json_encode($ga_graph_data);
		}else{
			echo 0;
		}
	}
	function get_ga_drill_down(){
		$ga_id = $this->input->post('ga_id');
		$crclm_id = $this->input->post('crclm_id');
		$ga_drilldown_data = $this->ga_model->get_drilldown_data($ga_id,$crclm_id);
		if($ga_drilldown_data){
			echo json_encode($ga_drilldown_data);
		}else{
			echo 0;
		}
	}
	function get_assessment_levels_po(){
		$po_id = $this->input->post('po_id');
		$levels_data = $this->ga_model->get_assessment_levels($po_id);
		if($levels_data){
			echo json_encode($levels_data);
		}else{
			echo 0;
		}
	}
	public function export_to_pdf() {
		if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {   
            //redirect them to the home page because they must be an administrator or owner to view this
            redirect('curriculum/peo/blank', 'refresh');
        } else {
			
			$ga_attainment_graph_data = $this->input->post('ga_attainment_graph_data_hidden');
			$this->load->helper('pdf');
			pdf_create($ga_attainment_graph_data,'tier_ii_ga_attainment','P');
			return;
		}
}


}//end of class