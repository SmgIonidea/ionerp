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

class Improvement_plan_model extends CI_Model {

	/**
     * Function is to fetch improvement plan details
     * @parameter: entity id, curriculum id, term id, course id, qp type id, qp id
     * @return: array
     */
    public function improvement_plan($sip_id) {
		$improvement_plan_query = 'SELECT sip.problem_statement, sip.root_cause, sip.corrective_action, sia.action_item 
								   FROM stud_improvement_action_item AS sia, stud_improvement_plan AS sip
								   WHERE sip.sip_id = "'.$sip_id.'"
									  AND sip.sip_id = sia.sip_id';
		$improvement_plan_details = $this->db->query($improvement_plan_query);
		$improvement_plan_data = $improvement_plan_details->result_array();

		return $improvement_plan_data;
    }
	
	
    /**
     * Function is used to insert improvement plan details
     * @parameters: entity id, curriculum id, term id, course id, type id, qpd id, 
			problem statement, root cause, corrective action, action item
     * @return: boolean
     */
	public function improvement_insert($entity_id, $crclm_id, $term_id, $crs_id, $qpType_id, $qpd_id, $problem_statement, $root_cause, $corrective_action, $action_item ,$student_usn) {
		$created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
	
		//fetch assessment occasion name (cia 1, minor 1, etc)
		$occasion_id_query = 'SELECT ao_name
							  FROM assessment_occasions
							  WHERE qpd_id = "'.$qpd_id.'"';
		$occasion_id_details = $this->db->query($occasion_id_query);
		$occasion_id_data = $occasion_id_details->result_array();
	
		if(!empty($occasion_id_data)) {
			$occasion_temp = $occasion_id_data[0]['ao_name'];
		} else {
			//for TEE
			$occasion_temp = NULL;
		}
		
		//for all occasions qpd id does not exist
		if($qpd_id == '') {
			$qpd_id = NULL;
			$occasion_temp = 'All Occasions';
		}
	
		$insert_improvement_plan_query = array(
			'entity_id' => $entity_id,
			'crclm_id'	=> $crclm_id,
			'crclm_term_id' => $term_id,
			'crs_id' => $crs_id,
			'qpd_type' => $qpType_id,
			'qpd_id' 	=> $qpd_id,
			'ao_name'	=> $occasion_temp,
			'problem_statement'	=> $this->db->escape_str($problem_statement),
			'root_cause'	=> $this->db->escape_str($root_cause),
			'corrective_action'	=> $this->db->escape_str($corrective_action),
			'student_usn' => $this->db->escape_str($student_usn),
			'created_by' => $created_by,
			'created_date' => $created_date
		);
		$stud_ip_result = $this->db->insert('stud_improvement_plan', $insert_improvement_plan_query);
		
		//fetch last inserted id
		$last_insert_id = $this->db->insert_id();
	
		$insert_improvement_plan_query = array(
			'sip_id' => $last_insert_id,
			'action_item'	=> $this->db->escape_str($action_item),
			'created_by' => $created_by,
			'created_date' => $created_date
		);
		$stud_ip_action_result = $this->db->insert('stud_improvement_action_item', $insert_improvement_plan_query);
		
		return true;
	}
	
	/**
     * Function is used to update improvement plan details
     * @parameters: entity id, curriculum id, term id, course id, type id, qpd id, 
			problem statement, root cause, corrective action, action item
     * @return: boolean
     */
	public function improvement_update($sip_id, $problem_statement, $root_cause, $corrective_action, $action_item ,$student_usn) {
		$modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
	
		$update_improvement_plan_query = 'UPDATE stud_improvement_plan AS sip, stud_improvement_action_item AS sia
										  SET sip.problem_statement = "'.$this->db->escape_str($problem_statement).'",
											sip.root_cause = "'.$this->db->escape_str($root_cause).'",
											sip.corrective_action = "'.$this->db->escape_str($corrective_action).'",
											sia.action_item = "'.$this->db->escape_str($action_item).'",
											sip.modified_by = "'.$modified_by.'",
											sip.modified_date = "'.$modified_date.'",
											sia.modified_by = "'.$modified_by.'",
											sia.modified_date = "'.$modified_date.'"
										  WHERE sip.sip_id =  "'.$sip_id.'"
											AND sip.sip_id = sia.sip_id';
		$update_improvement_plan_result = $this->db->query($update_improvement_plan_query);
		
		return $update_improvement_plan_result;
	}
}


/**
 * End of file improvement_plan_model.php 
 * Location: .configuration/improvement_plan_model.php 
 */
?>