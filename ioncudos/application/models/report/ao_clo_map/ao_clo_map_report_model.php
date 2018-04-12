<?php

/**
 * Description	:	Select Curriculum and then select the related term (semester) which
					will display related course. For each Course its related Assessment Occasions (AO) 
					to Course Outcomes (CO) mapping grid will be displayed.

 * Created		:	Oct 26th, 2015

 * Author		:	Abhinay B.Angadi

 * Modification History:
 *   Date                Modified By                         Description
 * 27-10-2015		   Abhinay B.Angadi			File header, function headers, indentation 
												and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_clo_map_report_model extends CI_Model {

    public $clo_po_map = "clo_po_map";

	/**
	 * Function to fetch curriculum details from curriculum table
	 * @return: curriculum id and curriculum name
	 */
    function clo_po() {
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if (($this->ion_auth->is_admin())) {		
			$query = 'SELECT crclm_id, crclm_name 
					  FROM curriculum 
					  WHERE status = 1
					  ORDER BY crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result = $curriculum_result->result_array();
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$query = 'SELECT c.crclm_id, c.crclm_name 
					  FROM curriculum AS c, program AS p 
					  WHERE c.status = 1 
						AND c.pgm_id = p.pgm_id 
						AND p.dept_id = "'.$dept_id.'"
					  ORDER BY c.crclm_name ASC';
			$curriculum_result = $this->db->query($query);
			$curriculum_result = $curriculum_result->result_array();
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
				  $curriculum_result = $curriculum_result->result_array();
		
		}

        //sending to controller
        $curriculum_fetch_data['curriculum_result'] = $curriculum_result;
        return $curriculum_fetch_data;
    }

	/**
	 * Function to fetch term details from curriculum term table
	 * @parameters: curriculum id
	 * @return: term name and curriculum term id
	 */
    public function clo_po_select($curriculum_id) {
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
        $term_result_data = $term_result->result_array();
        $term_data['term_result_data'] = $term_result_data;

        return $term_data;
    }

	/**
	 * Function to fetch course details from course table
	 * @parameters: curriculum id and term id
	 * @return: course id and course title
	 */
    public function term_course_details($curriculum_id, $term_id) {		
		$course_name = 'SELECT crs_id, crs_title 
						FROM course 
						WHERE crclm_id = "' . $curriculum_id . '" 
							AND crclm_term_id = "' . $term_id . '" 
							AND state_id > 1 
							AND status = 1
						ORDER BY crs_title ASC';
        $course_result = $this->db->query($course_name);
        $course_result_data = $course_result->result_array();
        $course_data['course_result_data'] = $course_result_data;
		
        return $course_data;
    }

	public function ao_clo_mapping($course_id, $term_id, $curriculum_id) {
		
		$course_query = $this->db
							 ->select('course.crclm_id, course.crclm_term_id, crs_title, crs_code')
							 ->select('crclm_name')
							 ->select('term_name')
						 	 ->join('curriculum', 'curriculum.crclm_id = course.crclm_id')
							 ->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
						 	 ->order_by("course.crclm_term_id", "asc")
							 ->where('crs_id', $course_id)
							 ->get('course')
							 ->result_array();

        $data['course_list'] = $course_query;
		
		$query = $this->db->query('call ao_clo_mapping("'.$curriculum_id.'","'.$course_id.'")');
		mysqli_next_result($this->db->conn_id);
		
		if($query->num_rows() > 0){
			$data['columns_list_ao_clo'] = $query->list_fields();
			$data['row_list_ao_clo'] = $query->result_array();
		}else{
			$data['columns_list_ao_clo']  = $data['row_list_ao_clo'] = array();
		}
		
		return $data;
    }
}

/*
 * End of file ao_clo_map_report_model.php
 * Location: .report/ao_clo_map/ao_clo_map_report_model.php 
 */
?>