<?php

/**
 * Description	:	Select activities that will elicit actions related to the verbs in the 
  learning outcomes.
  Select Curriculum and then select the related term (semester) which
  will display related course. For each course related CLO's and PO's
  are displayed.And their corresponding Mapped Performance Indicator(PI)
  and its Measures are displayed.

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 25-02-2016 		Shayisat Mulla		Added justification data and included justification data pdf
  ---------------------------------------------------------------------------------------------- */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_map_report_model extends CI_Model {

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
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $query = 'SELECT c.crclm_id, c.crclm_name 
					  FROM curriculum AS c, program AS p 
					  WHERE c.status = 1 
						AND c.pgm_id = p.pgm_id 
						AND p.dept_id = "' . $dept_id . '"
					  ORDER BY c.crclm_name ASC';
            $curriculum_result = $this->db->query($query);
            $curriculum_result = $curriculum_result->result_array();
        } else {
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

    /**
     * Function to fetch curriculum, term and course details from curriculum, term and course table respectively
     * @parameters: course id, term id and curriculum id
     * @return: curriculum name, term name, course learning objective id & statement and mapping details
     */
    public function clo_details($course_id, $term_id, $curriculum_id) {
        $data['curriculum_id'] = $curriculum_id;

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
        $map_details = $this->db
                ->select('clo.clo_id')
                ->select('clo_statement')
                ->select('clo_po_map.po_id')
                ->select('po_statement, po_reference')
                ->select('clo_po_map.pi_id')
                ->select('pi_statement')
                ->select('clo_po_map.msr_id')
                ->select('msr_statement, pi_codes')
                ->join('clo_po_map', 'clo.clo_id = clo_po_map.clo_id', 'left')
                ->join('po', 'po.po_id = clo_po_map.po_id', 'left')
                ->join('performance_indicator', 'performance_indicator.pi_id = clo_po_map.pi_id', 'left')
                ->join('measures', 'measures.msr_id = clo_po_map.msr_id', 'left')
                ->order_by("clo_po_map.clo_id", "asc")
                ->where('clo.crs_id', $course_id)
                ->get('clo')
                ->result_array();
        $co_po_approver = $this->db->query('SELECT c.approver_id,c.last_date,u.title,u.first_name,u.last_name
											FROM course_clo_approver c
											LEFT JOIN users u ON c.approver_id = u.id
											WHERE c.crclm_id = ' . $curriculum_id);
        $co_po_approver = $co_po_approver->row_array();
        $oe_pi_flag_query = 'SELECT oe_pi_flag FROM organisation';
        $oe_pi_flag_data = $this->db->query($oe_pi_flag_query);
        $oe_pi_flag = $oe_pi_flag_data->result_array();
        $data['co_po_approver'] = $co_po_approver;
        $data['map_details'] = $map_details;
        $data['oe_pi_flag'] = $oe_pi_flag[0]['oe_pi_flag'];
        return $data;
    }

    /* Function to fetch Justification of a particular curriculum from the notes table
     * @parameters: curriculum id ,term id, course id
     * @return: Justification data or null .
     */

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

    /* Function to fetch the value of individual mapping flag
     * @parameters: 
     * @return: Optional or Mandatory.
     */

    public function individual_map() {
        $query = $this->db->query('SELECT indv_mapping_justify_flag FROM organisation');
        $data = $query->result_array();

        return $data;
    }

    /* Function to fetch the value of justification data
     * @parameters: course_id
     * @return: Justification or null
     */

    public function justification_data($course_id) {
        $query = 'SELECT  justification 
		  FROM clo_po_map 
		  WHERE crs_id = "' . $course_id . '"';
        $query_data = $this->db->query($query);
        $data = $query_data->result_array();
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            if ($data[$i]['justification'] != null) {
                return $data;
            } else {
                return null;
            }
        }
    }

    /* Function to fetch the value of individual justification data
     * @parameters: curriculum_id, course_id
     * @return: Justification or null
     */

    public function individual_justification_details($curriculum_id, $course_id) {
        $notes = 'SELECT c.clo_id , c.po_id, c.crclm_id ,c.crs_id, c.justification , cl.clo_code ,p.po_reference from clo_po_map as c
			  LEFT JOIN clo as cl ON c.clo_id = cl.clo_id
			  LEFT JOIN po as p  ON c.po_id = p.po_id
			  WHERE c.crs_id = "' . $course_id . '"
			  ORDER BY cl.clo_code ASC ';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();

        $count = count($notes);

        for ($i = 0; $i < $count; $i++) {
            if (!empty($notes)) {
                return ($notes);
            } else {
                return null;
            }
        }
    }

}

/*
 * End of file clo_po_map_report_model.php
 * Location: .report/clo_po_map/clo_po_map_report_model.php 
 */
?>
