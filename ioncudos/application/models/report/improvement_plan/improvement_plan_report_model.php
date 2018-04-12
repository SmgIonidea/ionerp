<?php
/** 
* Description	:	Improvement Plan Report
* Created on	:	24-08-2015
* Create by		: 	Arihant Prasad
* Modification History:
* Date                Modified By           Description
------------------------------------------------------------------------------------------------------------*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Improvement_plan_report_model extends CI_Model {

    /* Function is used to fetch the curriculum details from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/ 
	public function fetch_crclm_list() {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
        if (($this->ion_auth->is_admin())) {

			$query = "SELECT crclm_id, crclm_name 
					  FROM curriculum 
					  WHERE status = 1
					  ORDER BY crclm_name ASC";
			$curriculum_result = $this->db->query($query);
			$curriculum_fetch_data = $curriculum_result->result_array();
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			
			$query = 'SELECT c.crclm_id, c.crclm_name 
					  FROM curriculum AS c, program AS p 
					  WHERE c.status = 1 
						  AND c.pgm_id = p.pgm_id 
						  AND p.dept_id = "'.$dept_id.'"
					  ORDER BY c.crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_fetch_data = $curriculum_result->result_array();
		}else{
			$query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_fetch_data = $curriculum_result->result_array();
		}
		
		return $curriculum_fetch_data;
    }
	
	/**
	 * Function to fetch term details from curriculum term table
	 * @parameters: curriculum id
	 * @return: term name and curriculum term id
	 */
    public function fetch_term_list($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$curriculum_id.'"';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
				
	}		
        $term_result = $this->db->query($term_list_query);
        $term_data = $term_result->result_array();
		
        return $term_data;
    }

	/**
	 * Function to fetch course details from course table
	 * @parameters: curriculum id and term id
	 * @return: course id and course title
	 */
    public function fetch_course_list($crclm_id, $term_id) {
		$course_query = 'SELECT crs_id, crs_title 
						 FROM course 
						 WHERE crclm_id = "' . $crclm_id . '" 
							AND crclm_term_id = "' . $term_id . '" 
							AND state_id > 1 
							AND status = 1
						 ORDER BY crs_title ASC';
        $course_result = $this->db->query($course_query);
        $course_data = $course_result->result_array();
		
        return $course_data;
    }
	
	/**
	 * Function to fetch curriculum, term and course details
	 * @parameters: curriculum id, term id, course id
	 * @return: curriculum, term and course name
	 */
	public function crclm_term_crs_details($crclm_id, $term_id, $crs_id) {
		$crclm_term_crs_query = 'SELECT c.crclm_name, t.term_name, cr.crs_title, cr.crs_code
								 FROM curriculum AS c, crclm_terms AS t, course AS cr
								 WHERE c.crclm_id = t.crclm_id
									AND c.crclm_id = cr.crclm_id
									AND c.crclm_id = "'.$crclm_id.'"
									AND t.crclm_term_id = "'.$term_id.'"
									AND cr.crs_id = "'.$crs_id.'"';
		$crclm_term_crs_result = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_data = $crclm_term_crs_result->result_array();
		
		return $crclm_term_crs_data;
	}
	 
	
	/**
	 * Function to fetch improvement plan details - table view
	 * @parameters: curriculum id, term id and course id
	 * @return: improvement plan details
	 */
    public function improvement_plan_display($entity_id, $crclm_id, $term_id, $crs_id) {
		$improvement_plan_query = 'SELECT sip.problem_statement,sip.student_usn, sip.root_cause, sip.corrective_action, sip.qpd_type, sip.ao_name,
									sia.action_item, ac.ao_description
								   FROM stud_improvement_action_item AS sia
								   JOIN stud_improvement_plan AS sip ON sip.entity_id = "'.$entity_id.'"
									  AND sip.crclm_id = "'.$crclm_id.'"
									  AND sip.crclm_term_id = "'.$term_id.'"
									  AND sip.crs_id = "'.$crs_id.'"
								   LEFT JOIN assessment_occasions AS ac ON sip.qpd_id = ac.qpd_id
								   WHERE sip.sip_id = sia.sip_id';
        $improvement_plan_result = $this->db->query($improvement_plan_query);
        $improvement_plan_data = $improvement_plan_result->result_array();
		
        return $improvement_plan_data;
    }
} ?>