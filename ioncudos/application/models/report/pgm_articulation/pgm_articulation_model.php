<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Program Articulation Matrix, 
 * Provides the facility to have birds eye view on each course CO's mapping with the PO's.	  
 * Author : Abhinay B.Angadi
 * Modification History:
 * Date							Modified By								Description
 * 28-10-2015                   Abhinay B.Angadi                        Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pgm_articulation_model extends CI_Model {

    public $clo_po_map = "clo_po_map";

        /*
        * Function is to fetch the curriculum name and id.
        * @param - ----.
        * returns  ----.
	*/
    public function curriculum_detals_for_clo_po() {
		if (!($this->ion_auth->is_admin())) {
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$crclm_details_query = 'SELECT c.crclm_id, c.crclm_name 
									FROM curriculum AS c, program AS p 
									WHERE c.status = 1 
										AND c.pgm_id = p.pgm_id 
										AND p.dept_id = "' . $dept_id . '"
									ORDER BY c.crclm_name ASC';
			$crclm_details_data = $this->db->query($crclm_details_query);
			$crclm_details_result = $crclm_details_data->result_array();

		} else {
			$crclm_details_query = 'SELECT crclm_id, crclm_name 
									FROM curriculum 
									WHERE status = 1
									ORDER BY crclm_name ASC';
			$crclm_details_data = $this->db->query($crclm_details_query);
			$crclm_details_result = $crclm_details_data->result_array();
		}
		
		$crclm_fetch_data['curriculum_details'] = $crclm_details_result;
        return $crclm_fetch_data;
    }

	/*
	 * Function is to fetch the curriculum term lis.
	 * @param - crclm id is used to fetch the particular curriculum term list.
	 * returns  term list.
	*/
    public function term_select($crclm_id) {
        $term_list_query = 'SELECT term_name, crclm_term_id 
                            FROM crclm_terms 
                            WHERE crclm_id = "'.$crclm_id.'" ';
        $term_list_data = $this->db->query($term_list_query);
        $term_list_result = $term_list_data->result_array();
        $term_data['term_lst'] = $term_list_result;
        return $term_data;
    }
    
	/*
	 * Function is to fetch the clo data of particular course.
	 * @param - crclm id, term id  is used to fetch the particular curriculum term course and its clo data.
	 * returns  the list of course and clo data.
	
    public function clo_details($term_id, $crclm_id, $core) {
        $data['crclm_id'] = $crclm_id;

        if ($core == 0) {
            $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
                             WHERE crclm_term_id = "'.$term_id.'" 
								AND crclm_id = "'.$crclm_id.'"';
            $course_list = $this->db->query($course_query);
            $course_list_data = $course_list->result_array();
            $data['course_list'] = $course_list_data;
        } else if ($core == 1) {
            $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code 
                             FROM course 
                             WHERE crclm_term_id = "'.$term_id.'" && crclm_id = "'.$crclm_id.'" AND crs_type_id = 5 ';
            $course_list = $this->db->query($course_query);
            $course_list_data = $course_list->result_array();
            $data['course_list'] = $course_list_data;
        }

        $po_list_query = 'SELECT po_id,crclm_id, po_statement, po_reference 
                          FROM po 
                          WHERE crclm_id = "'.$crclm_id.'" ';
        $po_list_data = $this->db->query($po_list_query);
        $po_list_result = $po_list_data->result_array();
        $data['po_list'] = $po_list_result;

        $course_po_map_query = 'SELECT DISTINCT(crs_id),po_id,crclm_id 
                                FROM clo_po_map 
                                WHERE crclm_id = "'.$crclm_id.'" ORDER BY crs_id ';
        $course_po_map_data = $this->db->query($course_po_map_query);
        $course_po_map_result = $course_po_map_data->result_array();
		
		if(!empty($course_po_map_result)) {
			$data['map_list'] = $course_po_map_result;
			return $data;
		} else {
			$data['map_list'] = NULL;
			return $data;
		}
    }*/
    
    /*
        * Function is to fetch the po data of particular curriculum.
        * @param - crclm id, term id  is used to fetch the particular curriculum po data.
        * returns  the list of po data.
	*/
    public function po_details($term_id, $crclm_id) {
        $po_list_query = "SELECT po_id,crclm_id, po_reference, po_statement FROM po WHERE crclm_id='$crclm_id'";
        $po_list_data = $this->db->query($po_list_query);

        $po_list_result = $po_list_data->result_array();
        return $po_list_result;
    }

    /*
        * Function is to fetch the clo data of particular curriculum term course list.
        * @param - course id used to fetch the particular course clo data.
        * returns  the list of clo data.
	*/
    public function fetch_clo($crs_id) {
        $clo_list_query = "SELECT map.crs_id, clo.clo_id, po.po_statement, clo.clo_statement
				FROM  clo JOIN clo_po_map AS map JOIN po
				WHERE map.clo_id = clo.clo_id
				AND map.po_id = po.po_id
				AND map.crs_id = clo.crs_id
				AND map.crs_id='$crs_id' group by clo.clo_id";
        $clo_list_data = $this->db->query($clo_list_query);
        $clo_list_result = $clo_list_data->result_array();
        $data['clo_list'] = $clo_list_result;
        return $data;
    }

	/** 
	 * Function to fetch course outcomes details that are mapped
	 * @parameters: curriculum id and term id
	 * @return: curriculum id, course id, course title, course code, course outcome id,
	  			course outcome statements, program outcome id, program outcome statements,
				course outcome mapped to program outcome id, comments, entity id,
				particular id, state and status
	 */
    public function clo_details($curriculum_id, $term_id) {
        $data['curriculum_id'] = $curriculum_id;

        // Fetch course details from the course table
        $course_query = 'SELECT c.crclm_id, c.crs_id, c.crs_title, c.crs_code, c.state_id, 
							o.crs_id, o.clo_owner_id, u.title, u.first_name, u.last_name
						 FROM course AS c, course_clo_owner AS o, users AS u
						 WHERE c.crclm_id = "' . $curriculum_id . '" 
							AND c.crclm_term_id = "' . $term_id . '" 
							AND c.status = 1
							AND o.crs_id = c.crs_id
							AND u.id = o.clo_owner_id
							ORDER BY c.crs_title ASC';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();
        $data['course_list'] = $course_list_data;
        $size = sizeof($data['course_list']);
			
		$term_course_count_details = 'SELECT count(crs_id) AS term_course_count
									  FROM course 
									  WHERE crclm_id = "' . $curriculum_id . '" 
										AND crclm_term_id = "' . $term_id . '" 
										AND status = 1';
        $term_course_count_data = $this->db->query($term_course_count_details);
        $term_course_count = $term_course_count_data->result_array();
			
        // Fetch course outcome details from the course outcome table
        $clo = 'SELECT clo.clo_id, clo.crclm_id, clo.crs_id, clo.clo_statement,clo.clo_code, clo.crs_id, c.crs_id, 
					c.state_id, dac.overall_attainment
				FROM clo AS clo
				JOIN course AS c ON clo.crs_id = c.crs_id
				LEFT JOIN direct_attainment_clo AS dac ON clo.crs_id = dac.crs_id AND clo.crclm_id = dac.crclm_id AND clo.clo_id = dac.clo_id
				WHERE clo.crclm_id = "' . $curriculum_id . '" 
					AND clo.term_id = "' . $term_id . '" ';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;
		
        // Fetch program outcome details from the program outcome table
        $po = 'SELECT po_id, crclm_id, po_statement, po_reference
			   FROM po 
			   WHERE crclm_id = "' . $curriculum_id . '"
			   ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

        /**
		 * Fetch course outcomes mapped to program outcome details from course 
		   outcomes to program outcome mapping table
		 */
        $clo_po_map = 'SELECT DISTINCT clo_id, po_id, crclm_id, map_level
					   FROM clo_po_map 
					   WHERE crclm_id = "' . $curriculum_id . '"';
        $clo_po_map_list = $this->db->query($clo_po_map);
        $map_list = $clo_po_map_list->result_array();
        $data['map_list'] = $map_list;
        
		$data['map_level'] = $this->db->select('map_level_name,map_level_short_form,map_level,map_level_weightage,status')
									  ->where('status',1)
									  ->get('map_level_weightage')
									  ->result_array();
		
		return $data;
    }
}