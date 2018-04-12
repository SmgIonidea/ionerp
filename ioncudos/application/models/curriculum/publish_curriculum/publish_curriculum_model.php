<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Publish_curriculum_model extends CI_Model {

    /* Function is used to fetch the curriculum id & name from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/	
	public function crclm_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$crclm_name='SELECT c.crclm_id, c.crclm_name 
						FROM curriculum AS c, users AS u, program AS p
						WHERE u.id = "'.$loggedin_user_id.'" 
						AND u.user_dept_id = p.dept_id 
						AND c.pgm_id = p.pgm_id 
						AND c.status = 1 
						ORDER BY c.crclm_name ASC';
		$resx = $this->db->query($crclm_name);
		$res2 = $resx->result_array();
		$crclm_data['res2']=$res2;
		
		return $crclm_data;
	}// End of function crclm_fill.
	
	public function course_level_fetch_entity_state($crclm_id) {
        // Get Terms data
        $term_query = $this->db
                ->select('crclm_terms.crclm_term_id, crclm_terms.term_name, publish_flag')
                ->where('crclm_terms.crclm_id', $crclm_id)// ->join('course','course.state_id >= 2')
                ->group_by('crclm_terms.crclm_term_id')
                ->get('crclm_terms')
                ->result_array();
        $data['term_list'] = $term_query;
		$term_array_size = sizeof($data['term_list']);
		
		for($i=0; $i < $term_array_size; $i++) {
			$course_publish_count = ' SELECT MIN(topic_publish_flag) as term_publish_criteria
										FROM course 
										WHERE crclm_term_id = "'. $data['term_list'][$i]['crclm_term_id'] . '" 
										AND state_id = 7
										AND status = 1 
										GROUP BY crclm_term_id';
			$course_publish_count_query_result = $this->db->query($course_publish_count);
			$course_publish_count_query_result_result = $course_publish_count_query_result->result_array();
			if( !empty($course_publish_count_query_result_result) ) {
				
				if($course_publish_count_query_result_result[0]['term_publish_criteria'] == 1) {
					$term_update_query = 'UPDATE crclm_terms 
											SET publish_flag = 1
											WHERE crclm_term_id = "' . $data['term_list'][$i]['crclm_term_id']. '" 
											AND publish_flag < 2 ';
					$term_update_data = $this->db->query($term_update_query);
				}else { 
					$term_update_query = 'UPDATE crclm_terms 
											SET publish_flag = 0
											WHERE crclm_term_id = "' . $data['term_list'][$i]['crclm_term_id']. '" ';
					$term_update_data = $this->db->query($term_update_query);
				}
			}
			else {
			$course_publish_count_query_result_result[$i]['term_publish_criteria'] = -1;
			/* $term_update_query = 'UPDATE crclm_terms 
											SET publish_flag = 0
											WHERE crclm_term_id = "' . $data['term_list'][$i]['crclm_term_id']. '" ';
			$term_update_data = $this->db->query($term_update_query); */
			}
		}
		
		// Get Terms data
        $term_query = $this->db
                ->select('crclm_terms.crclm_term_id, crclm_terms.term_name, publish_flag')
                ->where('crclm_terms.crclm_id', $crclm_id)// ->join('course','course.state_id >= 2')
                ->group_by('crclm_terms.crclm_term_id')
                ->get('crclm_terms')
                ->result_array();
        $data['term_list'] = $term_query;
		
        // Courses Data
        $course_list = ' SELECT DISTINCT c.crs_id, c.crs_code, c.crs_title, c.crclm_term_id, d.dashboard_id, d.entity_id, d.status, 		
							d.state, d.description,	d.particular_id, u.username, u.first_name, u.last_name, o.clo_owner_id
							FROM course AS c, dashboard d, users as u, course_clo_owner as o
							WHERE d.entity_id
							IN (4,11,16,10,12) 
							AND d.crclm_id = "'.$crclm_id.'" 
							AND d.status = 1 
							AND c.status = 1 
							AND o.crs_id = c.crs_id
							AND d.particular_id = c.crs_id 
							AND u.id = o.clo_owner_id ';

        $query_result = $this->db->query($course_list);
        $result = $query_result->result_array();
        $data['course_list'] = $result;
		// Check for Term-wise publish criteria 
		$course_publish_count = ' SELECT MIN(state_id) as term_publish_criteria, crclm_term_id
									FROM course 
									WHERE status = 1
									GROUP BY crclm_term_id';
        $course_publish_count_query_result = $this->db->query($course_publish_count);
        $course_publish_count_query_result_result = $course_publish_count_query_result->result_array();
        $data['course_publish_count'] = $course_publish_count_query_result;

        return $data;
    }
	/* Function is used to update the term publish flag from term table.
	* @param - 
	* @returns- a boolean value.
	*/	
	public function termwise_publish($crclm_term_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$term_update_query = 'UPDATE crclm_terms 
									SET publish_flag = 2
									WHERE crclm_term_id = "' . $crclm_term_id. '" ';
		$term_update_data = $this->db->query($term_update_query);
		
		return true;
	}// End of function crclm_fill.
	

}
?>