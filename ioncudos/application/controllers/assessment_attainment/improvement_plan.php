<?php
/**
 * Description	:	Improvement Plan
 * 					
 * Created		:	17-08-2015
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Improvement_plan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('assessment_attainment/improvement_plan/improvement_plan_model');
    }

    /* Function is to check for the authentication and redirect to improvement plan page
     * @parameters:
	 * @return: redirect to improvement plan page
	 */
    public function index() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {		

			$entity_id = $this->input->post('entity_id');
			$crclm_id = $this->input->post('crclm_name');
			$term_id = $this->input->post('term');
			$crs_id = $this->input->post('course');
			$type_id = $this->input->post('type_data');
			$tee_qpd_id = $this->input->post('tee_qpd_id');
			$occasion_id = $this->input->post('occasion');
			$status = 0;
			
			if($type_id == 5) {
				//TEE
				$qpType_id = 5;
				$qpd_id = $tee_qpd_id;
				$occasion_name = 'TEE';
			} else if($type_id == 3) {
				//CIA
				$qpType_id = 3;
				//$occasion_name = $this->input->post('occasion_name');
				
				if($occasion_id != 'all_occasion') {
					//either CIA Minor 1, 2, ...
					$qpd_id = $occasion_id;
				} else {
					//all occasions (minor 1 + minor 2 + ...)
					$qpd_id = NULL;
				}
			} else if($type_id == 6) {
				//CIA
				$qpType_id = 3;
				//$occasion_name = $this->input->post('occasion_name');
				
				if($occasion_id != 'all_occasion') {
					//either CIA Minor 1, 2, ...
					$qpd_id = $occasion_id;
				} else {
					//all occasions (minor 1 + minor 2 + ...)
					$qpd_id = NULL;
				}
			} else {
				//BOTH - CIA + TEE
				$qpType_id = 'BOTH';
				$qpd_id = NULL;
				$occasion_name = 'CIA & TEE';
			}
			
			//sip id
			$data['sip_id'] = array(
				'name'	=> 'sip_id',
				'id'	=> 'sip_id',
				'type'	=> 'hidden',
				'value'	=> NULL
			);
			
			//entity id
			$data['entity_id'] = array(
				'name'	=> 'entity_id',
				'id'	=> 'entity_id',
				'type'	=> 'hidden',
				'value'	=> $entity_id
			);
			
			//curriculum id
			$data['crclm_id'] = array(
				'name'	=> 'crclm_id',
				'id'	=> 'crclm_id',
				'type'	=> 'hidden',
				'value'	=> $crclm_id
			);
			
			//term id
			$data['term_id'] = array(
				'name'	=> 'term_id',
				'id'	=> 'term_id',
				'type'	=> 'hidden',
				'value'	=> $term_id
			);
			
			//course id
			$data['crs_id'] = array(
				'name'	=> 'crs_id',
				'id'	=> 'crs_id',
				'type'	=> 'hidden',
				'value'	=> $crs_id
			);
			
			//qp type id
			$data['qpType_id'] = array(
				'name'	=> 'qpType_id',
				'id'	=> 'qpType_id',
				'type'	=> 'hidden',
				'value'	=> $qpType_id
			);
			
			//qp id
			$data['qpd_id'] = array(
				'name'	=> 'qpd_id',
				'id'	=> 'qpd_id',
				'type'	=> 'hidden',
				'value'	=> $qpd_id
			);
			
			//flag value
			$data['flag_val'] = array(
				'name'	=> 'flag_val',
				'id'	=> 'flag_val',
				'type'	=> 'hidden',
				'value'	=> 1
			);
			
			//problem statement - tinymce
			$data['problem_stmt'] = array(
				'name'	=> 'problem_stmt',
				'id'	=> 'problem_stmt',
				'class'	=> 'myTextEditor input-xxlarge',
				'placeholder' => 'Enter Problem Statement',
				'style'	=> 'width: 100%; height: 25%;',
				'value'	=> NULL
			);
			
			//root cause
			$data['root_cause'] = array(
				'name'	=> 'root_cause',
				'id'	=> 'root_cause',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Root Cause Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> NULL
			);
			
			//corrective action
			$data['corrective_action'] = array(
				'name'	=> 'corrective_action',
				'id'	=> 'corrective_action',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Corrective Action Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> NULL
			);
			
			//action plan
			$data['action_plan'] = array(
				'name'	=> 'action_plan',
				'id'	=> 'action_plan',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Action Plan Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> NULL
			);		
			$data['student_usn'] = array(
				'name'	=> 'student_usn',
				'id'	=> 'student_usn',
				'type'	=> 'hidden',
				'rows'	=> '5',
                'cols'	=> '50',				
				'placeholder' => 'Student USN',
				'value'	=> $this->input->post('student_usn')
			);
			
			$data['title'] = 'Improvement Plan';
			$this->load->view('assessment_attainment/improvement_plan/improvement_plan_vw', $data);
        }
    }
	
	/* Function is to check for the authentication, fetch improvement plan details and redirect to improvement plan page
     * @parameters:
	 * @return: redirect to improvement plan page
	 */
    public function improvement_plan_edit_index($sip_id, $entity_id, $crclm_id, $term_id, $crs_id, $qpType_id, $qpd_id = NULL) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$improvement_plan_data = $this->improvement_plan_model->improvement_plan($sip_id);
			
			//sip id
			$data['sip_id'] = array(
				'name'	=> 'sip_id',
				'id'	=> 'sip_id',
				'type'	=> 'hidden',
				'value'	=> $sip_id
			);
			
			//entity id
			$data['entity_id'] = array(
				'name'	=> 'entity_id',
				'id'	=> 'entity_id',
				'type'	=> 'hidden',
				'value'	=> $entity_id
			);
			
			//curriculum id
			$data['crclm_id'] = array(
				'name'	=> 'crclm_id',
				'id'	=> 'crclm_id',
				'type'	=> 'hidden',
				'value'	=> $crclm_id
			);
			
			//term id
			$data['term_id'] = array(
				'name'	=> 'term_id',
				'id'	=> 'term_id',
				'type'	=> 'hidden',
				'value'	=> $term_id
			);
			
			//course id
			$data['crs_id'] = array(
				'name'	=> 'crs_id',
				'id'	=> 'crs_id',
				'type'	=> 'hidden',
				'value'	=> $crs_id
			);
			
			//qp type id
			$data['qpType_id'] = array(
				'name'	=> 'qpType_id',
				'id'	=> 'qpType_id',
				'type'	=> 'hidden',
				'value'	=> $qpType_id
			);
			
			//qp id
			$data['qpd_id'] = array(
				'name'	=> 'qpd_id',
				'id'	=> 'qpd_id',
				'type'	=> 'hidden',
				'value'	=> $qpd_id
			);
			
			//flag value
			$data['flag_val'] = array(
				'name'	=> 'flag_val',
				'id'	=> 'flag_val',
				'type'	=> 'hidden',
				'value'	=> 2
			);
			
			//problem statement - tinymce
			$data['problem_stmt'] = array(
				'name'	=> 'problem_stmt',
				'id'	=> 'problem_stmt',
				'class'	=> 'myTextEditor input-xxlarge',
				'placeholder' => 'Enter Problem Statement',
				'style'	=> 'width: 100%; height: 25%;',
				'value'	=> $improvement_plan_data[0]['problem_statement']
			);
			
			//root cause
			$data['root_cause'] = array(
				'name'	=> 'root_cause',
				'id'	=> 'root_cause',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Root Cause Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> $improvement_plan_data[0]['root_cause']
			);
			
			//corrective action
			$data['corrective_action'] = array(
				'name'	=> 'corrective_action',
				'id'	=> 'corrective_action',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Corrective Action Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> $improvement_plan_data[0]['corrective_action']
			);
			
			//action plan
			$data['action_plan'] = array(
				'name'	=> 'action_plan',
				'id'	=> 'action_plan',
				'type'	=> 'text',
				'rows'	=> '5',
                'cols'	=> '50',
				'maxlength' => '2000',
				'placeholder' => 'Enter Action Plan Description',
				'style'	=> 'width: 100%; height: 10%;',
				'value'	=> $improvement_plan_data[0]['action_item']
			);
			$data['student_usn'] = array(
				'name'	=> 'student_usn',
				'id'	=> 'student_usn',
				'type'	=> 'hidden',		
				'placeholder' => 'Student USN',
				'value'	=> $this->input->post('student_usn')
			);
			$data['title'] = 'Improvement Plan';
			$this->load->view('assessment_attainment/improvement_plan/improvement_plan_vw', $data);
		}
	}

    /**
	 * Function is to check for the authentication and to insert the improvement plan details
	 * @parameters:
	 * @return: boolean
	 */
    public function improvement_insert() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {
			$entity_id = $this->input->post('entity_id');
			$crclm_id = $this->input->post('crclm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			$qpType_id = $this->input->post('qpType_id');
			$qpd_id = $this->input->post('qpd_id');
			//trim qpd id
			$qpd_id = trim($qpd_id);
			$problem_statement = $this->input->post('problem_stmt');
			$root_cause = $this->input->post('root_cause');
			$corrective_action = $this->input->post('corrective_action');
			$action_item = $this->input->post('action_plan');
			$student_usn = $this->input->post('student_usn');
			
            $is_added = $this->improvement_plan_model->improvement_insert($entity_id, $crclm_id, $term_id, $crs_id, $qpType_id, $qpd_id, $problem_statement, $root_cause, $corrective_action, $action_item ,$student_usn);
			
           // echo json_encode($is_added);
        }
    }
	
	/**
	 * Function is to check for the authentication and to insert the improvement plan details
	 * @parameters:
	 * @return: boolean
	 */
    public function improvement_update() {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } else {		
			$sip_id = $this->input->post('sip_id');
			$problem_statement = $this->input->post('problem_stmt');
			$root_cause = $this->input->post('root_cause');
			$corrective_action = $this->input->post('corrective_action');
			$action_item = $this->input->post('action_plan');
			$student_usn = $this->input->post('student_usn');
            $is_added = $this->improvement_plan_model->improvement_update($sip_id, $problem_statement, $root_cause, $corrective_action, $action_item ,$student_usn);
			
            echo json_encode($is_added);
        }
    }

	
}

/*
 * End of file organisation.php
 * Location: .configuration/organisation.php 
 */
?>