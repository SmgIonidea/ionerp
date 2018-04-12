<?php
/**
 * Description	:	Model logic for creating Course Report

 * Created		:	1 July 2015

 * Author		:	Jyoti Shetti

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_report_model extends CI_Model {
	
	/* Function is used to fetch the curriculum id & name from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/	
	public function crclm_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if($this->ion_auth->is_admin()) {
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d 
								WHERE d.crclm_id = c.crclm_id 
								AND d.entity_id = 4 
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
							WHERE u.id = "'.$loggedin_user_id.'" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND d.crclm_id = c.crclm_id 
							AND d.entity_id = 4 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
		}else{
				$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
		}
		$resx = $this->db->query($curriculum_list);
		$res2 = $resx->result_array();
		$crclm_data['res2']=$res2;
		return $crclm_data;
		
	}// End of function crclm_fill.
	
	/* Function is used to fetch the term id & name from crclm_terms table.
	* @param - curriculum id.
	* @returns- a array of values of the term details.
	*/
    public function term_fill($curriculum_id) {
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
		$re = $this->db->query($term_list_query);
		return $re->result_array();
    }	
	
	/* Function is used to fetch the course, course type, term,course designer & course reviewer details 
	* from course, crclm_terms, course_clo_owner, course_validator, users & curse_type tables.
	* @param - curriculum id & term id.
	* @returns- a array of values of all the course details.
	*/	
    public function get_courses($crclm_id, $term_id) {
        $crs_list = 'SELECT c.crs_code, c.crs_title, lect_credits, tutorial_credits, practical_credits, c.self_study_credits,
							CONCAT(lect_credits,"-",tutorial_credits,"-",practical_credits,"-",c.self_study_credits) as "L-T-P-S",
							c.contact_hours, c.cie_marks, c.see_marks, c.ss_marks, c.total_marks, c.see_duration,
							c.crs_type_id, c.crs_acronym, crs_domain_id, c.total_credits,
							c.crs_mode, c.status, t.term_name,
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
					FROM  course AS c
					LEFT JOIN crclm_terms t ON t.crclm_term_id = "'.$term_id.'"
					LEFT JOIN course_clo_owner u ON u.crs_id = c.crs_id
					LEFT JOIN course_clo_validator r ON r.crs_id = c.crs_id 
					LEFT JOIN users s ON s.id = u.clo_owner_id
					LEFT JOIN course_type ct ON ct.crs_type_id = c.crs_type_id
					WHERE c.crclm_id = "'.$crclm_id.'"
					AND c.crclm_term_id = "'.$term_id.'"';
        $crs_list_result = $this->db->query($crs_list);
        $crs_list_data = $crs_list_result->result_array();
		
        return $crs_list_data;
    }// End of function course_list.
	
	public function get_ltps($crclm_id, $term_id) {
		$crs_list = 'SELECT  CONCAT(sum(lect_credits),"-", sum(tutorial_credits),"-", sum(practical_credits),"-", sum(c.self_study_credits)) as "total",
							sum(c.total_credits) as "total_credits", sum(c.contact_hours) as "total_contact_hours"
					FROM  course AS c
					WHERE c.crclm_id = "'.$crclm_id.'" AND c.status = 1
					AND c.crclm_term_id = "'.$term_id.'"';
        $crs_list_result = $this->db->query($crs_list);
        $crs_list_data = $crs_list_result->result_array();
		
        return $crs_list_data;
	}
	
	public function dept_name_by_crclm_id($crclm_id) {
		$dept_name_qry = 'SELECT dept_name 
						  FROM department 
						  WHERE dept_id = (SELECT dept_id 
										   FROM program 
										   WHERE pgm_id = (SELECT pgm_id 
														   FROM curriculum 
														   WHERE crclm_id= "' . $crclm_id . '"))';
		$dept_name_object = $this->db->query($dept_name_qry);
		$dept_name_array = $dept_name_object->result_array();
		
		return $dept_name_array[0]['dept_name'];
	}
	
}
?>