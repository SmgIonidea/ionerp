<?php
/**
 * Description	:	Continuous Improvement - Section 7 - Controller
 * Created		:	06-09-2016
 * Author		:	Arihant Prasad
 * Date					Author				Description
--------------------------------------------------------------------------------------*/
?>

<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class T1ug_c7_continuous_improvement extends CI_Controller {
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'nba_sar/nba_sar_list_model' );
		$this->load->model ( 'nba_sar/ug/tier1/criterion_7/t1ug_c7_continuous_improvement_model' );
	}
	
	/**
	 * Show all navigation groups
	 * @parameters:
	 * @return void
	 */
	public function index() {
		if (! $this->ion_auth->logged_in ()) {
			// redirect them to the login page
			redirect ( 'dashboard/dashboard', 'refresh' );
		} else if (! ($this->ion_auth->is_admin () || $this->ion_auth->in_group ( 'Program Owner' ) || $this->ion_auth->in_group ( 'Course Owner' ))) {
			// redirect them to the home page because they must be an administrator or program owner to view this
			redirect ( 'curriculum/clo/blank', 'refresh' );
		} else {
			$data ['node_id'] = $this->input->post('node_id',true);
			$data ['include_js'] = $this->nba_sar_list_model->get_js ( $data ['node_id'] );
			$this->load->view ( 'nba_sar/list/index', $data );
		}
	}
	
	/**
	 * Function to fetch and display actions based on the result of evaluation of each of the POs & PSOs - Section 7.1
	 * @parameters: id, nba sar id, nba sar report, basic info, export id
	 * @return: load section 7.1
	 */
	public function results_of_evaluation ($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
		extract($other_info);
		$filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
		
		$this->data['view_id'] = $id;
		$this->data['nba_report_id'] = $nba_report_id;
		$crs_list = $filter_list_data = $content = array();
		$curriculum_id = '';
		
		if(!empty($filters['filter_list'])) {
			foreach($filters['filter_list'] as $filter_list_value) {
				$filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
				
				if($filter_list_value['filter_ids'] == 'curriculum_list') {
					$curriculum_id = $filter_list_value['filter_value'];
				}
			}
		}
		
		$this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
		$this->data['filter_list'] = @$crclm_id;
		
		//fetch details related to results of evaluation of the POs and PSOs
		$success_rate = $this->t1ug_c7_continuous_improvement_model->fetch_result_of_evaluation();
		
		$this->data['is_export'] = $is_export;
		
		if ($is_export) {
			$this->data['fetch_result_of_evaluation_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_1_continuous_improvement_vw', $this->data, true);
			return $this->data['fetch_result_of_evaluation_vw'];
		} else {
			return $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_1_continuous_improvement_vw', $this->data, true);
		}
	}
	
	/**
	 * Function to fetch and display academic audit and action taken - Section 7.2
	 * @parameters: id, nba sar id, nba sar report, basic info, export id
	 * @return: load section 7.2
	 */
	public function academic_audit ($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
		extract($other_info);
		$filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
		
		$this->data['view_id'] = $id;
		$this->data['nba_report_id'] = $nba_report_id;
		$crs_list = $filter_list_data = $content = array();
		$curriculum_id = '';
		
		if(!empty($filters['filter_list'])) {
			foreach($filters['filter_list'] as $filter_list_value) {
				$filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
				
				if($filter_list_value['filter_ids'] == 'curriculum_list') {
					$curriculum_id = $filter_list_value['filter_value'];
				}
			}
		}
		
		$this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
		$this->data['filter_list'] = @$crclm_id;
		
		//fetch details related to results of evaluation of the POs and PSOs
		$success_rate = $this->t1ug_c7_continuous_improvement_model->academic_audit();
		
		$this->data['is_export'] = $is_export;
		
		if ($is_export) {
			$this->data['academic_audit_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_2_academic_audit_vw', $this->data, true);
			return $this->data['academic_audit_vw'];
		} else {
			return $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_2_academic_audit_vw', $this->data, true);
		}
	}
	
	/**
	 * Function to fetch and display improvement in placement, higher studies & entrepreneurship - Section 7.3
	 * @parameters: id, nba sar id, nba sar report, basic info, export id
	 * @return: load section 7.3
	 */
	public function plcmt_higher_studies_entrepreneur ($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
		extract($other_info);
		$filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
		
		$this->data['view_id'] = $id;
		$this->data['nba_report_id'] = $nba_report_id;
		$crs_list = $filter_list_data = $content = array();
		$curriculum_id = '';
		
		if(!empty($filters['filter_list'])) {
			foreach($filters['filter_list'] as $filter_list_value) {
				$filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
				
				if($filter_list_value['filter_ids'] == 'curriculum_list') {
					$curriculum_id = $filter_list_value['filter_value'];
				}
			}
		}
		
		$this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
		$this->data['filter_list'] = @$crclm_id;
		
		//fetch details related to placement, higher studies & entrepreneurship
		$success_rate = $this->t1ug_c7_continuous_improvement_model->plcmt_higher_studies_entrepreneur();
		
		$this->data['is_export'] = $is_export;
		
		if ($is_export) {
			$this->data['plcmt_higher_studies_entrepreneur_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_3_plcmt_higher_studies_entrepreneur_vw', $this->data, true);
			return $this->data['plcmt_higher_studies_entrepreneur_vw'];
		} else {
			return $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_3_plcmt_higher_studies_entrepreneur_vw', $this->data, true);
		}
	}
	
	/**
	 * Function to fetch and display quality of students admitted to the program - Section 7.4
	 * @parameters: id, nba sar id, nba sar report, basic info, export id
	 * @return: load section 7.4
	 */
	public function quality_of_students ($id = '', $nba_sar_id = '', $nba_report_id = '', $other_info = array(), $is_export = 0, $param = '') {
		extract($other_info);
		$filters = $this->nba_sar_list_model->nba_filters_list($id, $nba_report_id);
		
		$this->data['view_id'] = $id;
		$this->data['nba_report_id'] = $nba_report_id;
		$crs_list = $filter_list_data = $content = array();
		$curriculum_id = '';
		
		if(!empty($filters['filter_list'])) {
			foreach($filters['filter_list'] as $filter_list_value) {
				$filter_list_data [$filter_list_value ['filter_ids']] = $filter_list_value ['filter_value'];
				
				if($filter_list_value['filter_ids'] == 'curriculum_list') {
					$curriculum_id = $filter_list_value['filter_value'];
				}
			}
		}
		
		$this->data['crclm_id'] = @$crclm_id = $filter_list_data['curriculum_list'];
		$this->data['filter_list'] = @$crclm_id;
		$this->data['curriculum_list'] = $this->nba_sar_list_model->list_curriculum($pgm_id);
		
		if(!empty($filter_list_data['curriculum_list'])) {
			$this->data['success_rate'] = $this->fetch_quality_of_students($crclm_id);
		}
		
		$this->data['is_export'] = $is_export;
		
		if ($is_export) {
			$this->data['quality_of_students_vw'] = $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_4_quality_of_students_vw', $this->data, true);
			return $this->data['quality_of_students_vw'];
		} else {
			return $this->load->view('nba_sar/ug/tier1/criterion_7/t1ug_c7_4_quality_of_students_vw', $this->data, true);
		}
	}
	
	/**
	 *
	 */
	public function fetch_quality_of_students($curriculum = NULL) {
		if($curriculum == NULL) {
			$crclm_id = $this->input->post('curriculum');
			$success_rate= $this->t1ug_c7_continuous_improvement_model->fetch_quality_of_students($crclm_id);
			echo $this->table->generate($success_rate);
		} else {
			$success_rate = $this->t1ug_c7_continuous_improvement_model->fetch_quality_of_students($curriculum);
			return $this->table->generate($success_rate);
		}
	}
}