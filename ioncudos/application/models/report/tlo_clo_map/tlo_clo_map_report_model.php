<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: MOdel Logic for TLO CLO Mapping Report. 
 * Provides the fecility to have birds eye view on each course mapped with how many po's.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class tlo_clo_map_report_model extends CI_Model {

    public $tlo_clo_map = "tlo_clo_map";

        /*
        * Function is to fetch the curriculum name and id.
        * @param - ----.
        * returns  ----.
	*/
    public function curriculum_details_for_tlo_clo() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if (($this->ion_auth->is_admin())) {
			
			$crclm_details_query = 'SELECT crclm_id, crclm_name 
									FROM curriculum 
									WHERE status = 1
									ORDER BY crclm_name ASC';
			$crclm_details_data = $this->db->query($crclm_details_query);
			$crclm_details_result = $crclm_details_data->result_array();
			
			$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
			return $crclm_fetch_data;
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$crclm_details_query = 'SELECT c.crclm_id, c.crclm_name 
								    FROM curriculum AS c, program AS p 
								    WHERE c.status = 1 AND c.pgm_id = p.pgm_id 
										AND p.dept_id = "'.$dept_id.'"
									ORDER BY c.crclm_name ASC';
			$crclm_details_data = $this->db->query($crclm_details_query);
			$crclm_details_result = $crclm_details_data->result_array();
			
			$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
			return $crclm_fetch_data;
			
		}else{
		$crclm_details_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
			
			$crclm_details_data = $this->db->query($crclm_details_query);
            $crclm_details_result = $crclm_details_data->result_array();
			
			$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
			return $crclm_fetch_data;
		}
	}

    /*
        * Function is to fetch the curriculum term lis.
        * @param - crclm id is used to fetch the particular curriculum term list.
        * returns  term list.
	*/
    public function term_select($curriculum_id) {
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
        $term_list_data = $this->db->query($term_list_query);
        $term_list_result = $term_list_data->result_array();
        $term_data['term_lst'] = $term_list_result;

        return $term_data;
    }
    
     /*
        * Function is to fetch the curriculum term course details.
        * @param - term id is used to fetch the particular term course details.
        * returns  course list.
	*/
    public function term_course_details($term_id) {
        $course_name_query = 'SELECT crs_id, crs_title 
                              FROM course 
                              WHERE crclm_term_id = "'.$term_id.'" 
								  AND state_id > 1 
								  AND status = 1
							  ORDER BY crs_title ASC';
        $course_name_data = $this->db->query($course_name_query);
        $course_name_result = $course_name_data->result_array();
        $course_data['curriculum_name'] = $course_name_result;

        return $course_data;
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

    /*
	 * Function is to fetch the curriculum term course topic details.
	 * @param - term id and course id is used to fetch the particular term course topic details.
	 * returns  topic list.
	*/
    public function course_topic_details($term_id, $crs_id) {
        $course_topic_query = 'SELECT topic_id, topic_title 
                               FROM topic 
                               WHERE  term_id = "' . $term_id . '" 
								 AND course_id = "' . $crs_id .'"
							   ORDER BY topic_title ASC';
        $course_topic_data = $this->db->query($course_topic_query);
        $course_topic_result = $course_topic_data->result_array();
        $course_data['topic_data'] = $course_topic_result;
        return $course_data;
    }
    
	/*
	 * Function is to generate TLO to CLO Mapping Grid.
	 * @param - curriculum id, term id, course id and topic id  is used to fetch the particular topic tlo mapping with particular course.
	 * returns  TLO to CLO Mapping grid.
	*/
    public function tlo_details($crclm_id, $crs_id, $term_id, $topic_id) {
        $data['crclm_id'] = $crclm_id;
        $topic_query = $this->db
                ->select('topic.curriculum_id,topic.term_id,topic.course_id,topic.topic_title')
                ->select('crclm_name')
                ->select('term_name')
                ->select('crs_title,crs_code')
                ->join('curriculum', 'curriculum.crclm_id = topic.curriculum_id')
                ->join('crclm_terms', 'crclm_terms.crclm_term_id = topic.term_id')
                ->join('course', 'course.crs_id = topic.course_id')
                ->order_by("topic.term_id", "asc")
                ->where('topic_id', $topic_id)
                ->get('topic')
                ->result_array();

        $data['topic_list'] = $topic_query;
        
        $map_details = $this->db
                ->select('tlo_clo_map.clo_id,tlo_clo_map.topic_id,tlo_clo_map.tlo_id')
                ->select('clo_statement')
                ->select('tlo_statement')
                ->join('tlo', 'tlo.tlo_id = tlo_clo_map.tlo_id')
                ->join('clo', 'clo.clo_id = tlo_clo_map.clo_id')
                ->order_by("tlo_clo_map.tlo_id", "tlo_clo_map.clo_id", "asc")
                ->order_by("tlo_clo_map.clo_id", "asc")
                ->where('tlo_clo_map.topic_id', $topic_id)
                ->get('tlo_clo_map')
                ->result_array();

        $data['map_details'] = $map_details;
        return $data;
    }

}

?>