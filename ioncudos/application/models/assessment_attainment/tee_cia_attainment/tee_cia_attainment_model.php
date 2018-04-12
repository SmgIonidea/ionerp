<?php
/**
* Description	:	TEE & CIA Attainment Database Logic
* Created		:	30-09-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tee_cia_attainment_model extends CI_Model {

	/* Function is used to fetch the dept id & name from dept table.
	 * @param - 
	* @returns- a array of values of the dept details.
	*/	
	public function dept_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if($this->ion_auth->is_admin()) {
			$dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
		} else {
			$dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name
						  FROM department AS d, users AS u
						  WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = d.dept_id 
							AND d.status = 1 
							ORDER BY dept_name ASC';
		}
		
		$resx = $this->db->query($dept_name);
		$result = $resx->result_array();
		$dept_data['dept_result'] = $result;
		
		return $dept_data;
	}

	/* Function is used to fetch the pgm id & name from program table.
	 * @param - 
	* @returns- a array of values of the program details.
	*/	
	public function pgm_fill($dept_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 	
					 FROM program
					 WHERE dept_id = "' . $dept_id . '"
						AND status = 1
						ORDER BY pgm_acronym ASC';
		$resx = $this->db->query($pgm_name);
		$result = $resx->result_array();
		$pgm_data['pgm_result'] = $result;
		
		return $pgm_data;	
	}
	
	/* Function is used to fetch the curriculum id & name from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/	
	public function crclm_fill($pgm_id) {
/*		$loggedin_user_id = $this->ion_auth->user()->row()->id;
 		$crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC'; */
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name
								FROM curriculum AS c 
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))  {
		
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p
							    WHERE u.id = "'.$loggedin_user_id.'" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.pgm_id = "'.$pgm_id.'"
								   AND c.status = 1
								   ORDER BY c.crclm_name ASC';
        }else{
			        $dept_id = $this->ion_auth->user()->row()->user_dept_id;
					$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p ,course_clo_owner AS clo
							    WHERE u.id = "'.$loggedin_user_id.'" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.status = 1
								   AND c.pgm_id = "'.$pgm_id.'"
								   AND u.id = clo.clo_owner_id
								   AND c.crclm_id = clo.crclm_id
								   ORDER BY c.crclm_name ASC';
		}
	
 /*        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $crclm_data['curriculum_list'] = $curriculum_list_result;
		
		return $curriculum_list_result; */
		$resx = $this->db->query($curriculum_list);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data;
		
	}
    
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
        $result = $this->db->query($term_list_query);
        $data = $result->result_array();
        $term_data['res2'] = $data;

        return $term_data;
    }
	
	/* Function is used to fetch the course, course type, term,course designer & course reviewer details 
	* from course, crclm_terms, course_clo_owner, course_validator, users & curse_type tables.
	* @param - curriculum id & term id.
	* @returns- a array of values of all the course details.
	*/	
    public function course_list($crclm_id, $term_id) {
        $crs_list = 'SELECT q.qpd_id, c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status, t.term_name, 
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
					FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
						course_type ct, qp_definition AS q
					WHERE c.crclm_id = "'.$crclm_id.'" 
					AND c.crclm_term_id = "'.$term_id.'" 
					AND t.crclm_term_id = "'.$term_id.'" 
					AND q.crclm_id = "'.$crclm_id.'" 
					AND q.crclm_term_id = "'.$term_id.'" 
					AND q.crs_id = c.crs_id  
					AND u.crs_id = c.crs_id 
					AND r.crs_id = c.crs_id 
					AND s.id = u.clo_owner_id 
					AND ct.crs_type_id = c.crs_type_id ';
        $crs_list_result = $this->db->query($crs_list);
        $crs_list_data = $crs_list_result->result_array();
        $crs_list_return['crs_list_data'] = $crs_list_data;
				
        return $crs_list_return;
    }
	
	/* Function is to fetch department name, program name, curriculum name, term name
	 * @param - department id, program id, curriculum id, term id
	 * @returns - department name, program name, curriculum name, term name
	 */	
	public function fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id) {
		$dept_prog_query = 'SELECT d.dept_name, p.pgm_acronym
							FROM program AS p, department as d
							WHERE d.dept_id = "' . $dept_id . '"
								AND p.pgm_id = "' . $prog_id . '"';
		$dept_prog_data = $this->db->query($dept_prog_query);
		$dept_prog_name = $dept_prog_data->result_array();
		$data['dept_prog_name'] = $dept_prog_name;
		
		$crclm_term_crs_query = 'SELECT cr.crclm_name, ct.term_name, c.crs_title
								 FROM curriculum AS cr, crclm_terms AS ct, course AS c
								 WHERE cr.crclm_id = "' . $crclm_id . '"
									AND ct.crclm_term_id = "' . $term_id . '"
									AND c.crs_id = "' . $crs_id . '"';
		$crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_name = $crclm_term_crs_data->result_array();
		$data['crclm_term_crs_name'] = $crclm_term_crs_name;
		
		return $data;
	}
	
	public function fetch_attainment_data($term_id) {
		$attainment = $this->db->query("call getTEECIAAttainment(".$term_id.")");
		$attainment_data = $attainment->result_array();
		$this->database_result->next_result();
				
		return $attainment_data;
	}
	
	/*
	public function fetch_attainment($term_id) {
		$attainment = ' SELECT c.crs_id, c.crs_code, q.qpd_id,
							(CASE q.qpd_id
							WHEN q.qpd_id IS NOT NULL THEN getTEECIAAttainment(c.crs_id,q.qpd_id)
							ELSE 0 end) AS term_attainment
						FROM course c 
						LEFT JOIN qp_definition q on c.crs_id = q.crs_id 
						WHERE c.crclm_term_id = "' . $term_id . '" ';
		
		$attainment_data = $this->db->query($attainment);
		$attainment_result = $attainment_data->result_array();
		$this->database_result->next_result();
		var_dump($attainment_result);
		exit;
				
		return $attainment_data;
	}
	*/
}
?>