<?php
/**
* Description	:	Data Series List View
* Created		:	22-12-2014. 
* Author 		:   Jyoti V. Shetti
* Modification History:
* Date				Modified By				Description
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data_series_model extends CI_Model {
	
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
	/* 	$crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
		$resx = $this->db->query($crclm_name);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data;	 */
		if($this->ion_auth->is_admin()) {
			$curriculum_list_query = 'SELECT DISTINCT crclm_id, crclm_name 
									  FROM curriculum AS c
									  WHERE status = 1
									  ORDER BY c.crclm_name ASC';
		
		} elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
		}else{
		$curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				ORDER BY c.crclm_name ASC';
		}
		$resx = $this->db->query($curriculum_list_query);
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
    public function occasion_fill($crclm_id,$term_id,$crs_id,$section_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q , master_type_details  m
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'											
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0												
												AND a.qpd_id IS NOT NULL  group by q.qpd_id');
		return  $fetch_course_query->result_array();
    } 	
	
	/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function occasion_fill_student($crclm_id,$term_id,$crs_id,$section_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q , master_type_details  m
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'											
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0
												AND a.section_id = "'. $section_id.'"
												AND a.qpd_id IS NOT NULL  group by q.qpd_id');
		return  $fetch_course_query->result_array();
    }   	
	
	
	/*
        * Function is to fetch the occasion details.
        * @param - -----.
        * returns list of occasion names.
	*/   
    public function occasion_fill_mte($crclm_id,$term_id,$crs_id,$section_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q 
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'											
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0
												AND a.section_id = 0 
												AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
    }    
	
	public function occasion_fill1($crclm_id,$term_id,$crs_id,$section_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id,a.ao_description
												FROM assessment_occasions a,qp_definition q
												WHERE a.crclm_id = '.$crclm_id.'
												AND a.term_id = '.$term_id.'
												AND a.crs_id = '.$crs_id.'
												AND a.section_id = "'. $section_id .'"
												AND q.qpd_id = a.qpd_id
												AND q.qp_rollout > 1
												AND q.qpd_type = 3
                                                AND cia_model_qp = 0
												AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
    }	
	
	/*
        * Function is to fetch the Section details.
        * @param - -----.
        * returns list of Section names.
	*/   
    public function section_fill($crclm_id,$term_id,$crs_id) {
		 $query = $this->db->query('select section_id ,mt_details_name from map_courseto_course_instructor as c join master_type_details as m on m.mt_details_id = c.section_id  	where crclm_id = "'.$crclm_id .'" and crclm_term_id ="'. $term_id .'" and crs_id = "'. $crs_id .'"');
		  return $query->result_array();
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
	
	public function fetch_type_data($crclm_id, $term_id, $course_id){
	
		$query  = $this->db->query('select cia_flag , mte_flag , tee_flag from course where crs_id = "'. $course_id .'" and crclm_id = "'. $crclm_id .'" and crclm_term_id = "'. $term_id.'" ');
		$type_data =  $query->result_array();
		$types = array();
		$key = array();
		$t ='';
		$types [''] = 'Select Type';
		foreach($type_data as $type ){
			if($type['cia_flag'] == 1){$types ['3'] = 'CIA';}
			if($type['mte_flag'] == 1){$types ['6'] = 'MTE';}
			if($type['tee_flag'] == 1){$types ['5'] = 'TEE';}			
		
		}	
			return $types;
			
	}
}
?>