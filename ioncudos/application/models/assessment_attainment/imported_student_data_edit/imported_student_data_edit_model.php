<?php

class Imported_student_data_edit_model extends CI_Model{
	
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
		return $resx->result_array();
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
		$fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title FROM course c,qp_definition qpd WHERE c.crs_id = qpd.crs_id AND c.crclm_term_id = '.$term.' ORDER BY c.crs_id ASC');
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
	
	public function student_marks($qpd_id) {
		$this->load->library('database_result');
		$marks = $this->db->query("call edit_CIA_TEE_StudentMarks(".$qpd_id.")");
		$data = $marks->result_array();
		$this->database_result->next_result();
		return $data;
	}
	
	function update($update_data,$where){
		return $this->db->update('student_assessment',$update_data,$where);
	}
	function update_total_marks($update_data,$where){
		return $this->db->update('student_assessment_totalmarks',$update_data,$where);
	}
	
}//end of class Imported_student_data_edit_model