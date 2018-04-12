<?php

/**
 * Description		:	Generates Mapping Report

 * Created		:	February 23 2016

 * Author		:	Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mapping_report_model extends CI_Model {
    /*
     * Function to fetch all the curriculum details for the logged in user
     * @return: dashboard state and status
     */

    public function fetch_curriculum() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if (($this->ion_auth->is_admin())) {
            //logged in user is admin
            $curriculum_list_details = 'SELECT crclm_id, crclm_name
									    FROM curriculum
									    WHERE status = 1
										ORDER BY crclm_name ASC';
            $curriculum_list_data = $this->db->query($curriculum_list_details);
            $curriculum = $curriculum_list_data->result_array();

            return $curriculum;
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            //logged in user is other than admin
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
									    FROM curriculum AS c, program AS p
									    WHERE c.status = 1
											AND c.pgm_id = p.pgm_id
											AND p.dept_id = "' . $dept_id . '"
										ORDER BY c.crclm_name ASC';
            $curriculum_list_data = $this->db->query($curriculum_list_details);
            $curriculum = $curriculum_list_data->result_array();

            return $curriculum;
        } else {
            $curriculum_list_details = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
            $curriculum_list_data = $this->db->query($curriculum_list_details);
            $curriculum = $curriculum_list_data->result_array();

            return $curriculum;
        }
    }

    /**
     * Function to fetch term details using curriculum id from curriculum terms table
     * @parameters: curriculum id
     * @return: term id and term name
     */
    public function fetch_term($curriculum_id) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
            $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="' . $loggedin_user_id . '"
					  AND c.crclm_id = "' . $curriculum_id . '"';
        } else {
            $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
        }
        $term_name_result = $this->db->query($term_list_query);
        $term_name_result = $term_name_result->result_array();
        $data['term_name_result'] = $term_name_result;

        return $data;
    }

    /**
     * Function to program outcomes details from po table
     * @parameters: curriculum id
     * @return: program outcome statements, program outcome references
     */
    public function fetch_po_statement($curriculum_id) {
        //program outcomes details
//        $po_details_list = 'SELECT po_statement, po_reference,pso_flag
//								FROM po 
//								WHERE crclm_id = "' . $curriculum_id . '"';

        $po_details_list = 'SELECT peo_statement, peo_reference
								FROM peo 
								WHERE crclm_id = "' . $curriculum_id . '"';

        $po_list_data = $this->db->query($po_details_list);
        $po_list = $po_list_data->result_array();

        return $po_list;
    }

    /*
     * Function is to fetch the course list.
     * @param term id is used to fetch the respective course list.
     * returns course list.
     */

    public function course_drop_down_fill($crclm_id, $term_id) {
        $user = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_list_query = ' SELECT DISTINCT c.crs_id,c.crs_title  
										FROM course as c, dashboard as d 
										WHERE d.entity_id NOT IN(2,5,6,13,20)
										AND c.crclm_term_id = "' . $term_id . '" 
										AND d.crclm_id = "' . $crclm_id . '" 
										AND c.state_id != 1 
										AND d.particular_id = c.crs_id 
										AND d.status = 1 
										ORDER BY c.crs_title ASC';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_result = $course_list_data->result_array();
            $course_data['course_list'] = $course_list_result;
            return $course_data;
        } else if ($this->ion_auth->in_group('Course Owner')) {
            $course_info_query = ' SELECT DISTINCT o.crs_id,c.crs_title  
										FROM course as c, course_clo_owner as o, dashboard as d 
										WHERE d.entity_id NOT IN(2,5,6,13,20)
										AND o.crclm_term_id = "' . $term_id . '"  
										AND d.crclm_id = "' . $crclm_id . '"  
										AND o.clo_owner_id = "' . $user . '" 
										AND o.crs_id = c.crs_id 
										AND c.state_id != 1 
										AND d.particular_id = o.crs_id 
										AND d.status = 1
										ORDER BY c.crs_title ASC';
            $course_info_data = $this->db->query($course_info_query);
            $course_info_result = $course_info_data->result_array();
            $course_data['course_list'] = $course_info_result;
            return $course_data;
        } else {
            $course_info_query = ' SELECT DISTINCT o.crs_id,c.crs_title  
										FROM course as c, course_clo_owner as o, dashboard as d 
										WHERE d.entity_id NOT IN(2,5,6,13,20)
										AND o.crclm_term_id = "' . $term_id . '" 
										AND o.crs_id = c.crs_id 
										AND d.particular_id = o.crs_id 
										AND d.status = 1
										ORDER BY c.crs_title ASC';
            $course_info_data = $this->db->query($course_info_query);
            $course_info_result = $course_info_data->result_array();
            $course_data['course_list'] = $course_info_result;
            return $course_data;
        }
    }

    /* Function to fetch po details, peo details, po_peo mapping details from po, peo and po_peo_map tables.
     * @parameters: curriculum id
     * @return: an array of po, peo, po_peo_map details.
     */

    public function map_po_peo($curriculum_id) {
        $po = 'SELECT po_id, po_reference, po_statement, crclm_id,pso_flag 
					FROM po 
					WHERE crclm_id = "' . $curriculum_id . '" 
					ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $number_of_po = $po_list->num_rows();
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;
        $data['number_of_po'] = $number_of_po;

        $peo = 'SELECT peo_id, peo_statement, peo_reference 
					FROM peo 
					WHERE crclm_id = "' . $curriculum_id . '" ';
        $peo_list = $this->db->query($peo);
        $number_of_peo = $peo_list->num_rows();
        $peo_list = $peo_list->result_array();
        $data['peo_list'] = $peo_list;
        $data['number_of_peo'] = $number_of_peo;

        $mapped_po_peo = 'SELECT pp_id, peo_id, po_id, crclm_id,map_level
								FROM po_peo_map as p
								WHERE crclm_id = "' . $curriculum_id . '" ';
        $mapped_po_peo = $this->db->query($mapped_po_peo);
        $mapped_po_peo = $mapped_po_peo->result_array();
        $data['mapped_po_peo'] = $mapped_po_peo;
        $map_level = $this->db->query('select map_level,map_level_short_form from map_level_weightage');
        $map_level = $map_level->result_array();
        $data['map_level'] = $map_level;
        return $data;
    }

    /* Function to fetch po details,curriculum,co details,clo_po_map details from po,co,curriculum and clo_po_map tables.
     * @parameters: curriculum id,term id ,course id
     * @return: an array of po, co, clo_po_map details.
     */

    public function map_co_to_po($curriculum_id, $term_id, $course_id) {

        $po_list_query = 'SELECT po_id, crclm_id, po_reference, po_statement,pso_flag  
		                  FROM po
		                  WHERE crclm_id = "' . $curriculum_id . '"
						  ORDER BY LENGTH(po_reference), po_reference';
        $po_list_data = $this->db->query($po_list_query);
        $po_list_result = $po_list_data->result_array();
        $data['po_list'] = $po_list_result;

        $course_query = $this->db
                ->select('crs_id, crs_title, crs_code')
                ->join('curriculum', 'curriculum.crclm_id = course.crclm_id')
                ->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
                ->order_by("course.crclm_term_id", "asc")
                ->where('crs_id', $course_id)
                ->get('course')
                ->result_array();

        $data['course_list'] = $course_query;

        $clo = 'SELECT clo_id, crs_id, clo_statement 
					FROM clo 
					WHERE term_id = "' . $term_id . '"
					AND crs_id = "' . $course_id . '"';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;

        $map = 'SELECT DISTINCT clo_id, po_id, map_level
					FROM clo_po_map 
					WHERE crclm_id = "' . $curriculum_id . '"';
        $map_list = $this->db->query($map);
        $map_list = $map_list->result_array();
        $data['clo_po_map_details'] = $map_list;
        return $data;
    }

    /* Function to fetch Justification of a particular curriculum from the notes table
     * @parameters: curriculum id ,term id, course id
     * @return: Justification data or null .
     */

    public function justification_details($curriculum_id, $term_id, $course_id) {
        $notes = 'SELECT notes 
						FROM notes 
						WHERE crclm_id = "' . $curriculum_id . '" 
						AND entity_id = 16 
						AND term_id="' . $term_id . '"
						AND particular_id="' . $course_id . '"';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();

        if (!empty($notes[0]['notes'])) {
            return ($notes[0]['notes']);
        } else {
            return null;
        }
    }

    /* Function to fetch peo justification of a particular curriculum from the notes table
     * @parameters: curriculum id
     * @return: peo justification details.
     */

    public function peo_justification($curriculum_id) {
        $notes = 'SELECT notes 
				FROM notes 
				WHERE crclm_id = "' . $curriculum_id . '" 
				AND entity_id = 13 ';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();

        if (!empty($notes[0]['notes'])) {
            return ($notes[0]['notes']);
        } else {
            return null;
        }
    }

}
?>
