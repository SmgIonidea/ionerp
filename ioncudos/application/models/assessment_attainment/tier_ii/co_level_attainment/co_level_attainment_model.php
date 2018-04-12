<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Description	:	Tier II CO Level Attainment Model

 * Created		:	December 14th, 2015

 * Author		:	 Shivaraj B

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */
?>
<?php

class Co_level_attainment_model extends CI_Model {
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

    public function term_drop_down_fill($crclm_id) {
/*         return $this->db->select('crclm_term_id, term_name')
                        ->where('crclm_id', $crclm_id)
                        ->get('crclm_terms')
                        ->result_array(); */
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		 if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
			$term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
						  where c.crclm_term_id = ct.crclm_term_id
						  AND c.clo_owner_id="'.$loggedin_user_id.'"
						  AND c.crclm_id = "'.$crclm_id.'"';
		} else {

			$term_list_query = 'SELECT term_name, crclm_term_id 
						  FROM crclm_terms 
						  WHERE crclm_id = "' . $crclm_id . '"';
		
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
        } elseif($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, users AS u, program AS p
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id 
							AND c.pgm_id = p.pgm_id 
							AND c.status = 1
							ORDER BY c.crclm_name ASC';
        }else{
			 $dept_id = $this->ion_auth->user()->row()->user_dept_id;
					$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p ,course_clo_owner AS clo
							    WHERE u.id = "'.$loggedin_user_id.'" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.status = 1								   
								   AND u.id = clo.clo_owner_id
								   AND c.crclm_id = clo.crclm_id
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
												WHERE c.crs_id = qpd.crs_id
												AND (( qpd.qpd_type = 3 AND qpd.qp_rollout = 2 )
														OR ( qpd.qpd_type = 5 AND qpd.qp_rollout = 2 )
													)AND c.crclm_term_id = ' . $term);
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
        return $r->result_array();
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
                $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description
													FROM assessment_occasions a,qp_definition q
													WHERE a.crclm_id = ' . $crclm_id . '
													AND a.term_id = ' . $term_id . '
													AND a.crs_id = ' . $crs_id . '
													AND q.qpd_id = a.qpd_id
													AND q.qp_rollout > 1
													AND q.qpd_type = 3
                                                                                                        AND cia_model_qp = 0
													AND a.qpd_id IS NOT NULL');
            } else if ($crs_id == 'select_all_course') {
                $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND a.term_id = ' . $term_id . ' 
													AND q.qpd_id = a.qpd_id 
													AND q.qp_rollout > 1 
													AND q.qpd_type = 3 
                                                                                                        AND cia_model_qp = 0
													AND a.qpd_id IS NOT NULL');
            }
        } else if ($term_id == 'select_all_term') {
            $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description,a.term_id,a.crs_id 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND q.qpd_id = a.qpd_id 
													AND q.qp_rollout > 1 
													AND q.qpd_type = 3 
                                                                                                        AND cia_model_qp = 0
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

    public function getCloAttainmentType() {
        $res = $this->db->select('value')->where('config', 'CLO_ATTAINMENT_TYPE')->get('org_config');
        return $res->result_array();
    }

    public function get_clo_attainment($course_id, $ao_id, $type) {
        if ($type == 3) {
            if ($ao_id == "all_occasion") {
                $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,ROUND(AVG(t.individual_da_percentage),2)as individual_da_percentage,ROUND(AVG(t.attainment_level),2) as attainment_level FROM tier_ii_section_clo_overall_cia_attainment t,clo c WHERE c.clo_id = t.clo_id AND t.crs_id='" . $course_id . "' AND t.assess_type='" . $type . "' group by c.clo_code;");
            } else {
                $result = $this->db->select('t.*,c.clo_code,c.clo_statement')
                        ->from('tier_ii_section_clo_overall_cia_attainment t')
                        ->join('clo c', 'c.clo_id = t.clo_id')
                        ->where('t.assess_type', '3')
                        ->where('t.ao_id', $ao_id)
                        ->where('t.crs_id', $course_id)
                        ->group_by('c.clo_code')
                        ->get();
            }
        } else if ($type == 5) {
            $result = $this->db->select('t.*,c.clo_code,c.clo_statement')
                    ->from('tier_ii_section_clo_overall_cia_attainment t')
                    ->join('clo c', 'c.clo_id = t.clo_id')
                    ->where('t.assess_type', $type)
                    ->where('t.crs_id', $course_id)->order_by("c.clo_code", "asc")
                    ->get();
        } else if ($type == 0) {
            $result = $this->db->query("call get_tier_ii_CourseCOLevelAttainment('" . $course_id . "',0);");
        }
        return $result->result_array();
    }

    function get_drilldown_for_co($clo_id, $ao_id, $crs_id) {
        $res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $crs_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
        $wgt_arr = array(
            'cia_wgt' => $cia_wgt,
            'tee_wgt' => $tee_wgt,
        );
        if ($ao_id == "all_occasion") {
            $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,t.attainment_level AS al,t.individual_da_percentage AS ip, ROUND(AVG(t.individual_da_percentage)*'" . $cia_wgt . "'/100,2) as individual_da_percentage,ROUND(AVG(t.attainment_level)*'" . $cia_wgt . "'/100,2) as attainment_level,a.ao_description FROM tier_ii_section_clo_overall_cia_attainment t,clo c,assessment_occasions a WHERE t.clo_id=c.clo_id AND t.clo_id = '" . $clo_id . "' AND t.ao_id=a.ao_id AND t.assess_type=3;");
        } else {
            $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,t.individual_da_percentage AS ip,t.attainment_level AS al, ROUND(AVG(t.individual_da_percentage)* '" . $cia_wgt . "'/100,2) as individual_da_percentage,ROUND(AVG(t.attainment_level) * '" . $cia_wgt . "'/100,2) as attainment_level,a.ao_description FROM tier_ii_section_clo_overall_cia_attainment t,clo c,assessment_occasions a WHERE t.clo_id=c.clo_id AND t.clo_id = '" . $clo_id . "' AND t.ao_id=a.ao_id AND t.assess_type=3 
			UNION ALL 
			SELECT c.clo_id,c.clo_code,c.clo_statement,t.individual_da_percentage AS ip,t.attainment_level AS al, ROUND(AVG(t.individual_da_percentage)* '" . $tee_wgt . "'/100,2) as individual_da_percentage,ROUND(AVG(t.attainment_level)* '" . $tee_wgt . "'/100,2) as attainment_level,0 ao_description 
			FROM tier_ii_section_clo_overall_cia_attainment t,clo c 
			WHERE t.clo_id=c.clo_id AND t.clo_id = '" . $clo_id . "' AND t.assess_type=5
			GROUP BY t.clo_id ;");
        }
        return array('wgt_arr' => $wgt_arr, 'query_data' => $result->result_array());
        //return $result->result_array();
    }
	

    public function get_crs_level_attainment($crs_id) {
        return $this->db->get_where('attainment_level_course', array('crs_id' => $crs_id))->result_array();
    }

    public function finalize_clo_attainment($crclm_id, $term_id, $crs_id) {
        $where = array('crclm_id' => $crclm_id, 'crclm_term_id' => $term_id, 'crs_id' => $crs_id);
        $this->db->delete('tier_ii_crs_clo_overall_attainment', $where);
        $is_added = $this->db->query("call get_tier_ii_CourseCOLevelAttainment('" . $crs_id . "',1)");
        $this->db->delete('tier_ii_po_direct_attainment', $where);
        $this->db->query("call storeTierIIPOAttainmentLevel(" . $crclm_id . "," . $term_id . "," . $crs_id . ");");
        return $is_added;
    }

    public function get_co_mapped_questions($clo_id, $type_data, $ao_id) {
        if ($type_data == 2) {
            $qpd_data = $this->db->select('qpd_id')->from('assessment_occasions')->where('ao_id', $ao_id)->get()->row();
            if (empty($qpd_data)) {
                $qpd_id = "";
            } else {
                $qpd_id = $qpd_data->qpd_id;
            }
            if ($ao_id == "all_occasion") {
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.qpd_id = un.qpd_id 
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_type = 3 
										AND qpd.qp_rollout = 2 
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC');
            } else {
                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.ao_id = "' . $ao_id . '" 
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_id = "' . $qpd_id . '" AND qpd.qpd_type = 3 
										AND qpd.qp_rollout = 2 
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC');
            }
        } else if ($type_data == 1) {
            $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id, qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,c.clo_code,c.clo_statement 
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_type = 5 AND qpd.qp_rollout = 2
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC');
        } else {
            $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id, qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks, c.clo_code,c.clo_statement,aocc.ao_description  
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type
										LEFT JOIN assessment_occasions aocc ON aocc.qpd_id = un.qpd_id  
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_type IN (5,3) AND qpd.qp_rollout = 2
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC');
        }
        return $result->result_array();
    }

    function get_finalized_co_attainment($crclm_id, $term_id, $crs_id) {
	
	// CO Attainment query after CO finalize option
		$co_result = $this->db->query("SELECT * FROM tier_ii_crs_clo_overall_attainment t LEFT JOIN clo c ON t.clo_id=c.clo_id 
										WHERE t.crclm_id='" . $crclm_id . "' 
										AND t.crclm_term_id = '" . $term_id . "' 
										AND t.crs_id='" . $crs_id . "';");
        //echo $this->db->last_query();
        $data['co_result'] = $co_result->result_array();

	// PO Attainment query after CO finalize option
		$po_result = $this->db->query("SELECT t.clo_oall_id, ROUND(AVG(t.overall_attainment),2) as direct_attainment
											,ROUND(AVG(t.attainment_level),2),t.crclm_id, t.crclm_term_id, 		t.crs_id,t.clo_id,cp.clo_id,cp.po_id, cp.map_level,
											t.overall_attainment, t.attainment_level,cp.clo_po_id,po.po_reference,po.po_statement,
										GROUP_CONCAT(DISTINCT c.clo_code ORDER BY c.clo_code ASC) AS co_mapping
										FROM tier_ii_crs_clo_overall_attainment t
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
