<?php
/**
* Description	:	Student Data Series List View
* Created		:	22-12-2014. 
* Author 		:   Jevi V. G.
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Student_data_series_model extends CI_Model {
	
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
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
		$resx = $this->db->query($crclm_name);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data;	
	}
	
	/* Function is used to fetch the term id & name from crclm_terms table.
	* @param - curriculum id.
	* @returns- a array of values of the term details.
	*/
    public function term_fill($crclm_id) {
        $term_name = 'SELECT crclm_term_id, term_name 
						FROM crclm_terms 
						WHERE crclm_id = "'.$crclm_id.'" ';
        $result = $this->db->query($term_name);
        $data = $result->result_array();
        $term_data['res2'] = $data;

        return $term_data;
    }
	
	/*
        * Function is to fetch the term details.
        * @param - -----.
        * returns list of term names.
	*/   
    public function course_fill($term) {
		$fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
												FROM course c,qp_definition qpd
												WHERE c.crs_id = qpd.crs_id
												AND c.crclm_term_id = '.$term.'
												ORDER BY c.crs_title ASC ');
		return  $fetch_course_query->result_array();
    }
	
	/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function occasion_fill($crclm_id,$term_id,$crs_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
												AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
    }
	
	/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function usn_fill($qpd_id) {
		$fetch_course_query = $this->db->query('SELECT  distinct student_usn  FROM student_assessment s
												where qp_mq_id in (select qp_mq_id from qp_mainquestion_definition
												where qp_unitd_id in(select qpd_unitd_id from qp_unit_definition where qpd_id='.$qpd_id.') )');
		return  $fetch_course_query->result_array();
    }
	
	public function StudentAttainmentAnalysis($qpd_id,$usn) {
		$r = $this->db->query("call getStudentAttainmentAnalysis(".$qpd_id.", '".$usn."')");
		
			return $r->result_array();
	}
	
	/*
        * Function is to fetch the qp_id of TEE which is rolled out.
        * @param - -----.
        * returns TEE info.
	*/   
    public function getTEEQPId($crclm_id,$term_id,$crs_id) {
		$fetch_tee_info_query = $this->db->query('SELECT q.qpd_id
												FROM qp_definition q
												WHERE q.crclm_id = '.$crclm_id.'
												AND q.crclm_term_id = '.$term_id.'
												AND q.crs_id = '.$crs_id.'
												AND q.qp_rollout > 1
												AND q.qpd_type = 5;');
		return  $fetch_tee_info_query->result_array();
    }
}
?>
