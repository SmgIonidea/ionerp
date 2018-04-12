<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description         : Model Logic for Curriculum vision, mission, .	  
 * Modification History:
 * Date				Modified By				Description
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_details_model extends CI_Model {
    /*
     * Function is to get the list of all curriculum.
     * @param - ------.
     * returns the list of curriculums.
     */

    public function list_of_curriculum() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
								FROM curriculum AS c, dashboard AS d
								WHERE d.entity_id = 5
									AND c.crclm_id = d.crclm_id
									AND d.status = 1
									AND c.status = 1
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE p.dept_id = "' . $dept_id . '" 
									AND p.pgm_id = c.pgm_id
									AND c.crclm_id = d.crclm_id
									AND d.entity_id = 5
									AND c.status = 1 
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        } else {
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

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $curriculum_fetch_data['result_curriculum_list'] = $curriculum_list_result;

        return $curriculum_fetch_data;
    }

    /**
     * Function to fetch department name
     * @parameters: curriculum id
     * @return: department name
     */
    public function get_dept_name($crclm_id) {
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

    /**
     * Function to fetch department details
     */
    public function get_department($crclm_id) {
        $dept_id = $this->db->query('SELECT p.dept_id 
									 FROM program p 
									 WHERE p.pgm_id = (SELECT c.pgm_id 
													   FROM curriculum c 
													   WHERE c.crclm_id = ' . $crclm_id . ')');
        $dept_id = $dept_id->row_array();
        $dept_id = $dept_id['dept_id'];

        $vision_mission = $this->db->query('SELECT d.dept_mission, dept_vision 
											FROM dept_mission_map d 
											WHERE d.dept_id = ' . $dept_id);
        $data['vision_mission'] = $vision_mission->result_array();

        $mission = $this->db->query('SELECT * 
									 FROM dept_mission_element_map d 
									 WHERE d.dept_id = ' . $dept_id);
        $data['mission'] = $mission->result_array();

        $peo = $this->db->query('SELECT p.peo_reference, p.peo_statement 
								 FROM peo p 
								 WHERE p.crclm_id = ' . $crclm_id . '
								 ORDER BY p.peo_reference ASC');
        $data['peo'] = $peo->result_array();

        $po = $this->db->query('SELECT p.po_reference, p.po_statement 
								FROM po p 
								WHERE p.crclm_id = ' . $crclm_id . '
								ORDER BY CAST(p.po_reference AS UNSIGNED)');
        $data['po'] = $po->result_array();

//		$bloom_level = $this->db->query('SELECT * 
//										 FROM bloom_level b');

        $bloom_level = $this->db->query('SELECT *,  bd.bld_code
                                                 FROM bloom_level b, bloom_domain bd
                                                 where b.bld_id = bd.bld_id');
        $data['bloom_level'] = $bloom_level->result_array();
        return $data;
    }

    /**
     * Function to fetch attendees name, attendees notes and curriculum id from attendees notes table
     * @parameters: curriculum id
     * @return: attendees name, attendees notes and curriculum id
     */
    public function attendees_data($curriculum_id) {
        $attendees_data = 'SELECT attendees_name, crclm_id, attendees_notes 
						   FROM attendees_notes 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $attendees_data = $this->db->query($attendees_data);
        $attendees_data = $attendees_data->result_array();

        return $attendees_data;
    }

    /**
     * Function to fetch justification details
     * @parameters: curriculum id
     * @return: justification details
     */
    public function justification($crclm_id) {
        $justification = $this->db->query(' SELECT * 
											FROM notes
											WHERE crclm_id = "' . $crclm_id . '" 
												AND entity_id = 27 ');
        $justification = $justification->result_array();
        return $justification;
    }

}
