<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for CIA Adding, Editing operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2014		Jevi V G     	         
  26-11-2014		Jyoti					PDF creation,Added TEE and CIA for graph display
  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_attainment_model extends CI_Model {
    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_dept_title() {
        if ($this->ion_auth->is_admin()) {
            return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('status', 1)
                            ->get('department')->result_array();
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('dept_id, dept_name')
                            ->order_by('dept_name', 'asc')
                            ->where('dept_id', $logged_in_user_id)
                            ->where('status', 1)
                            ->get('department')->result_array();
        }
    }

    /*
     * Function is to get the program titles.
     * @param - ------.
     * returns -------.
     */

    public function dropdown_program_title($dept_id) {
        return $this->db->select('pgm_id, pgm_acronym')
                        ->order_by('pgm_acronym', 'asc')
                        ->where('dept_id', $dept_id)
                        // ->where('status',1)        		
                        ->get('program')
                        ->result_array();
    }

    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill_old($pgm_id) {
        return $this->db->select('crclm_id, crclm_name')
                        ->where('pgm_id', $pgm_id)
                        ->order_by('crclm_name', 'asc')
                        ->get('curriculum')
                        ->result_array();
    }

    /*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
     */

    public function term_drop_down_fill($curriculum_id) {
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
        return $result->result_array();
    }

    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
        }else{
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
        $resx = $this->db->query($curriculum_list);
        $crclm_data = $resx->result_array();

        return $crclm_data;
    }

    /*
     * Function is to fetch the term details.
     * @param - -----.
     * returns list of term names.
     */

    public function course_drop_down_fill($term) {
        // return $this->db->select('crs_id, crs_title')
        // ->where('crclm_term_id',$term)
        // ->order_by('crs_title','ASC')
        // ->get('course')
        // ->result_array();
        $fetch_course_query = $this->db->query('SELECT DISTINCT(c.crs_id),c.crs_title
												FROM course c,qp_definition qpd
												WHERE qpd.qpd_type IN (3,5) 
												AND c.crs_id = qpd.crs_id
												AND qpd.qp_rollout = 2 
												AND c.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
    }

    public function survey_drop_down_fill($course) {
        return $this->db->select('survey_id, name')
                        ->where('crs_id', $course)
                        ->where('su_for', 8)
                        //->where('status',1) //Edited by shivaraj B for getting closed surveys
                        ->get('su_survey')
                        ->result_array();
    }

    public function qp_rolled_out($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_rollout > 1')
                        ->where('qpd_type = 5')
                        ->get('qp_definition')
                        ->result_array();
    }

    public function course_po_list($crs) {

        return $this->db->distinct()
                        ->select('clo_po_map.po_id, po.po_reference')
                        ->join('po', 'po.po_id = clo_po_map.po_id')
                        ->where('crs_id', $crs)
                        ->order_by('po.po_reference', 'ASC')
                        ->get('clo_po_map')
                        ->result_array();
    }

    public function getCourseCOThreshholdAttainment($crs, $qp_rolled_out, $type) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseCOThreshholdAttainment(" . $crs . ", NULL, NULL, 'Both')");
        }
        return $r->result_array();
    }

    public function getCourseCOAttainment($crs, $qp_rolled_out, $type) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseCOAttainment(" . $crs . ", NULL, NULL, 'BOTH')");
        }
        return $r->result_array();
    }

    public function CourseBloomsLevelThresholdAttainment($crs, $qp_rolled_out, $type) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseBLThreshholdAttainment(" . $crs . ", NULL, NULL, 'BOTH')");
        }
        return $r->result_array();
    }

    public function CourseBloomsLevelAttainment($crs, $qp_rolled_out, $type) {
        if ($qp_rolled_out > 0) { //Individual CIA or TEE CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", " . $qp_rolled_out . ", NULL, '" . $type . "')");
        } else if ($qp_rolled_out == 'all_occasion') { //All CIA occasion CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", NULL, NULL, 'CIA')");
        } else { //Both CIA & TEE CO attainment
            $r = $this->db->query("call getCourseBloomsLevelAttainment(" . $crs . ", NULL, NULL, 'BOTH')");
        }
        return($r->result_array());
    }

    public function BloomsLevelMarksDistribution($crs) {
        $r = $this->db->query("call getBloomsLevelMarksDistribution(" . $crs . ", " . $qp_rolled_out . ")");

        return $r->result_array();
    }

    public function BloomsLevelPlannedCoverageDistribution($crs) {
        $r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(" . $crs . ", " . $qp_rolled_out . ")");

        return $r->result_array();
    }

    public function CoursePOCOAttainment($po_list, $crs, $qpd_id, $type) {
        if ($qpd_id == 'all_occasion' || $qpd_id == NULL) {
            $param = $crs . ", NULL, NULL,'" . $type;
        } else if ($qpd_id > 0) {
            $param = $crs . ", " . $qpd_id . ", NULL,'" . $type;
        }
        $i = 0;
        foreach ($po_list as $po_id) {
            $this->load->library('database_result');
            $result = $this->db->query("call getCoursePOCOAttainment(" . $po_id['po_id'] . ", " . $param . "')");
            $graph_data = $result->result_array();
            $this->database_result->next_result();
            $data['po_reference'][] = $po_id['po_reference'];
            $data['graph_data'][] = $graph_data;
        }
        return $data;
    }

    /*
     * Function is to fetch the qpd details.
     * @param - -----.
     * returns list of qpd_id.
     */

    public function select_qpd_all_terms($crclm_id, $term_id) {
        if ($term_id == 'select_all_term') {
            $fetch_qpd_all_terms_query = $this->db->query('SELECT q.qpd_id 
														   FROM qp_definition q 
														   WHERE q.crclm_id = ' . $crclm_id . ' 
														   AND q.qpd_type = 5 
														   AND q.qp_rollout = 2');
        } else {
            $fetch_qpd_all_terms_query = $this->db->query('SELECT q.qpd_id
															FROM qp_definition q
															WHERE q.crclm_id = ' . $crclm_id . ' 
															AND q.crclm_term_id = ' . $term_id . '
															AND q.qpd_type = 5
															AND q.qp_rollout = 2');
        }
        return $fetch_qpd_all_terms_query->result_array();
    }

    /*
     * Function is to fetch the occasion details.
     * @param - -----.
     * returns list of occasion names.
     */

    public function occasion_fill($crclm_id, $term_id, $crs_id) {

        if ($term_id != 'select_all_term') {
            if ($crs_id != 'select_all_course') {
                $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description
													FROM assessment_occasions a,qp_definition q
													WHERE a.crclm_id = ' . $crclm_id . '
													AND a.term_id = ' . $term_id . '
													AND a.crs_id = ' . $crs_id . '
													AND q.qpd_id = a.qpd_id
													AND q.qp_rollout > 1
													AND q.qpd_type = 3
													AND a.qpd_id IS NOT NULL');
            } else if ($crs_id == 'select_all_course') {
                $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND a.term_id = ' . $term_id . ' 
													AND q.qpd_id = a.qpd_id 
													AND q.qp_rollout > 1 
													AND q.qpd_type = 3 
													AND a.qpd_id IS NOT NULL');
            }
        } else if ($term_id == 'select_all_term') {
            $fetch_occasion_query = $this->db->query('SELECT a.qpd_id,a.ao_description,a.term_id,a.crs_id 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND q.qpd_id = a.qpd_id 
													AND q.qp_rollout > 1 
													AND q.qpd_type = 3 
													AND a.qpd_id IS NOT NULL');
        }
        return $fetch_occasion_query->result_array();
    }

    public function getCOSurveyReportData($survey_id) {

        $r = $this->db->query("call getCOSurveyReportData(" . $survey_id . ")");

        return $r->result_array();
    }

    public function qp_direct_indirect($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_rollout > 1')
                        ->where('qpd_type', 5)
                        ->get('qp_definition')
                        ->result_array();
    }

    public function getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data) {
        if ($type_data == 1) {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'TEE'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
        } else if ($type_data == 2) {
            if ($qpd_id != NULL) {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
            } else {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
            }
        } else {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'BOTH'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",0)");
        }
        return $r->result_array();
    }

    // Finalize CO Attainment and store onto the table direct_attainment
    public function finalize_getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id, $type_data) {
        if ($type_data == 1) {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'TEE'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
        } else if ($type_data == 2) {
            if ($qpd_id != NULL) {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
            } else {
                $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'CIA'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
            }
        } else {
            $r = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . ",NULL,NULL,'BOTH'," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ",1)");
        }
        return true;
    }

    /* Added by Shivaraj B  Date: 10-12-2015
     * Edited for displaying data depending on CLO ATTAINMENT TYPE
     */

    public function getCloAttainmentType() {
        $res = $this->db->select('value')->where('config', 'CLO_ATTAINMENT_TYPE')->get('org_config');
        return $res->result_array();
    }

    function getIndirectAttainmentData($survey_id, $crs_id) {
        $result = $this->db->query(" SELECT * FROM indirect_attainment i,clo c 
										WHERE i.survey_id='" . $survey_id . "' 
										AND i.crs_id='" . $crs_id . "' 
										AND i.actual_id=c.clo_id 
										ORDER BY c.clo_id; ");
        return $result->result_array();
    }

    function get_finalized_co_attainment($crclm_id, $term_id, $crs_id) {
        $co_result = $this->db->query("SELECT * FROM direct_attainment_clo t 
										LEFT JOIN clo c ON t.clo_id=c.clo_id 
										WHERE t.crclm_id='" . $crclm_id . "'
										AND t.crclm_term_id='" . $term_id . "' 
										AND t.crs_id='" . $crs_id . "';");
		//echo $this->db->last_query();
        $data['co_result'] = $co_result->result_array();

		// PO Attainment query after CO finalize option
		$po_result = $this->db->query("SELECT t.da_clo_id, ROUND(AVG(t.overall_attainment),2) as direct_attainment,t.crclm_id,
											t.crclm_term_id, t.crs_id,t.clo_id,cp.clo_id,cp.po_id, cp.map_level,
											t.overall_attainment,cp.clo_po_id,po.po_reference,po.po_statement,
										GROUP_CONCAT(DISTINCT c.clo_code ORDER BY c.clo_code ASC) AS co_mapping
										FROM direct_attainment_clo t
										LEFT JOIN clo_po_map cp ON t.clo_id = cp.clo_id
										LEFT JOIN po po ON po.po_id = cp.po_id
										LEFT JOIN clo c ON c.clo_id = cp.clo_id
										WHERE t.crclm_id = '" . $crclm_id . "'
										AND t.crclm_term_id = '" . $term_id . "'
										AND t.crs_id = '" . $crs_id . "'
										GROUP BY cp.po_id ;");
										
		 $data['po_result'] = $po_result->result_array();
		
		return $data;



	
		
		
    }

}
