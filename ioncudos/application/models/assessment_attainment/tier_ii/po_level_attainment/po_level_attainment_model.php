<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for CIA Adding, Editing operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------------------------------------------
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_level_attainment_model extends CI_Model {
    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crlcm_drop_down_fill() {
/* 
        if ($this->ion_auth->is_admin()) {
            return $this->db->select('crclm_id, crclm_name')
                            ->order_by('crclm_name', 'ASC')
                            ->where('status', 1)
                            ->get('curriculum')->result_array();
        } else {
            $logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
            return $this->db->select('crclm_id, crclm_name')
                            ->order_by('crclm_name', 'ASC')
                            ->where('status', 1)
                            ->get('curriculum')->result_array();
        } */
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

    public function term_drop_down_fill($crclm_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$crclm_id.'"';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $crclm_id . '"';
				
	}
		
        $result = $this->db->query($term_list_query);
        return $result->result_array();
    }

    public function qp_rolled_out($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_roll_out', 1)
                        ->get('qp_definition')
                        ->result_array();
    }

    public function course_po_list($crs) {

        return $this->db->distinct()
                        ->select('po_id')
                        ->where('crs_id', $crs)
                        ->get('clo_po_map')
                        ->result_array();
    }

    public function getPOAttainment($term, $po = NULL) {
        $result = $this->db->query("call getPOAttainment(" . $term . ", NULL)");

        return $result->result_array();
    }

    public function getCourseList($term) {

        return $this->db->select('crs_id')
                        ->where('crclm_term_id', $term)
                        ->get('course')
                        ->result_array();
    }

    public function getCourseAttainment($crclm_id, $term, $type_data) {
        if ($term != 'All') {
            if ($type_data = 1) {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
				(CASE WHEN q.qpd_id IS NULL THEN  0
					ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"TEE")
					END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type = 5 
AND q.qp_rollout >= 1
WHERE c.crclm_term_id = "' . $term . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            } else if ($type_data = 2) {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
	(CASE WHEN q.qpd_id IS NULL THEN  0
		ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"CIA")
		END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type = 3 
AND q.qp_rollout >= 1
WHERE c.crclm_term_id = "' . $term . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            } else {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
	(CASE WHEN q.qpd_id IS NULL THEN  0
		ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"BOTH")
		END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type IN (5,3) 
AND q.qp_rollout >= 1
WHERE c.crclm_term_id = "' . $term . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            }
        } else {
            if ($type_data = 1) {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
		(CASE WHEN q.qpd_id IS NULL THEN  0
			ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"TEE")
			END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type = 5 
AND q.qp_rollout >= 1
WHERE c.crclm_id = "' . $crclm_id . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            } else if ($type_data = 2) {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
	(CASE WHEN q.qpd_id IS NULL THEN  0
		ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"CIA")
		END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type = 3 
AND q.qp_rollout >= 1
WHERE c.crclm_id = "' . $crclm_id . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            } else {
                $sql = ' SELECT c.crs_id,c.crs_code,c.crs_title,c.cia_course_minthreshhold,c.course_studentthreshhold,q.qpd_id,
	(CASE WHEN q.qpd_id IS NULL THEN  0
		ELSE   getCourseAttainment(c.crs_id,q.qpd_id,"BOTH")
		END) AS course_attainment
FROM course c 
LEFT JOIN qp_definition q ON c.crs_id=q.crs_id 
AND q.qpd_type IN (5,3) 
AND q.qp_rollout >= 1
WHERE c.crclm_id = "' . $crclm_id . '" GROUP BY c.crs_id';
                $course_data = $this->db->query($sql);
                $course_list = $course_data->result_array();
            }
        }
        return $course_list;
    }

    //Edited By Shivaraj B  for Po Level Attainment type (AVERAGE, CO_TARGEET)
    public function getPOLevelAttainment($crclm_id, $term, $crs_core_id) {
        $result = $this->db->query("call getTierIIPOAttainmentLevel(" . $crclm_id . ",'" . $term . "'," . $crs_core_id . ");");
        return $result->result_array();
    }

	function get_performance_attainment_list_data($po_id){
		$result = $this->db->select('*')->where('po_id',$po_id)->order_by('performance_level_value','desc')->get('performance_level_po');
		return $result->result_array();
	}
	
	
	function get_performance_attainment_list($po_id , $po_level){		
		$result = $this->db->query('select * from performance_level_po where po_id = "'. $po_id.'"  and "'.$po_level.'"  BETWEEN start_range and end_range;');
		return $result->result_array();
	}
	
	
    public function getCoursePOAttainment($crclm, $term, $po_id, $core_crs_id) {
        $result = $this->db->query("call getTieriiDrillDownForPO($crclm,'" . $term . "',$core_crs_id,$po_id)");
        return $result->result_array();
    }
	
	public function fetch_org_config_po(){
		$query = $this->db->query("SELECT * FROM org_config o where org_config_id = 6");
        return $query->result_array();	
	}	
	public function fetch_map_level_weightage(){
		$query = $this->db->query("SELECT * FROM map_level_weightage m");
        return $query->result_array();	
	}

    public function qp_direct_indirect($crs) {

        return $this->db->select('qpd_id')
                        ->where('crs_id', $crs)
                        ->where('qp_rollout > 1')
                        ->where('qpd_type', 5)
                        ->get('qp_definition')
                        ->result_array();
    }

    //Edited By Shivaraj code starts here
    public function getDirectIndirectPOAttaintmentData($crclm_id, $term_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id_arr, $survey_perc_arr, $po_attainment_type, $core_courses_cbk) {
        $r = $this->db->query("call tier_ii_getDirectIndirectPOAttaintmentDataFromCO(" . $crclm_id . ",'" . $term_id . "'," . $core_courses_cbk . "," . $direct_attainment . "," . $indirect_attainment . ",'" . $survey_id_arr . "','" . $survey_perc_arr . "',0)");
        //echo $this->db->last_query();
        return $r->result_array();
    }

    public function finalize_po_direct_indirect_attainment($crclm_id, $term_id, $core_courses_cbk, $direct_attainment, $indirect_attainment, $survey_id_arr, $survey_perc_arr) {
        $status = $this->db->query("call tier_ii_getDirectIndirectPOAttaintmentDataFromCO(" . $crclm_id . ",'" . $term_id . "'," . $core_courses_cbk . "," . $direct_attainment . "," . $indirect_attainment . ",'" . $survey_id_arr . "','" . $survey_perc_arr . "',1)");
        return $status;
    }

    //code ends here

    public function survey_drop_down_fill($crclm_id) {
        return $this->db->select('survey_id, name')
                        ->where('crclm_id', $crclm_id)
                        ->where('su_for', 7)
                        ->get('su_survey')
                        ->result_array();
    }

    public function getDirectIndirectCOAttaintmentData($course_id, $qpd_id, $usn, $qpd_type, $direct_attainment, $indirect_attainment, $survey_id) {
        $result = $this->db->query("call getDirectIndirectCOAttaintmentData(" . $course_id . "," . $qpd_id . ",NULL," . $qpd_type . "," . $direct_attainment . "," . $indirect_attainment . "," . $survey_id . ")");

        return $result->result_array();
    }

    //Edited by Shivaraj B

    public function getSurveyNames() {
        $result = $this->db->get('su_survey');
        return $result->result_array();
    }

    public function getPOAttainmentType() {
        $result = $this->db->select('value')->where('config', 'PO_ATTAINMENT_TYPE')->get('org_config');
        return $result->result_array();
    }

    public function get_survey_data($crclm_id, $survey_id) {
        $result = $this->db->query(" SELECT i.survey_id,i.crclm_id,i.actual_id,i.ia_percentage,i.attainment_level,
												p.po_statement,p.po_reference 
										FROM indirect_attainment i,po p 
										WHERE i.actual_id=p.po_id 
										AND i.crclm_id='" . $crclm_id . "' 
										AND i.survey_id='" . $survey_id . "' 
										ORDER BY LENGTH(p.po_reference), p.po_reference ");
        return $result->result_array();
    }
    
    public function get_finalized_po_data($crclm_id) {
        $po_result = $this->db->query("SELECT * FROM tier_ii_po_overall_attainment t LEFT JOIN po p ON t.po_id=p.po_id where t.crclm_id='".$crclm_id."'");
        return $po_result->result_array();
    }
    
    //Code added by Mritunjay B S Date: 26/12/2016.
    /*
     * Function to get the extra curricular activity.
     * @param:
     * @return:
     */
    public function get_activity_data($crclm_id,$term_array){
       
       $this->db->SELECT('po_extca_id, crclm_id, term_id, activity_name')
                    ->FROM('po_extra_cocurricular_activity')
                    ->WHERE_IN('term_id', $term_array)
                    ->WHERE('crclm_id', $crclm_id);
            $extra_curricular_activity_result = $this->db->get()->result_array();
            return $extra_curricular_activity_result;
    }
    
    /*
     * Function to get the Activity based po attainment.
     * @param:
     * @return:
     */
    public function get_activity_po_wise_attainment($activity_id,$crclm_id,$term_array){
               $po_attain_query = 'SELECT pod.po_extca_id, pod.rubrics_criteria_id, pod.po_id, pod.po_rubrics_attainment, '
                                    . ' cri.criteria, p.po_reference, p.po_statement '
                                    . ' FROM po_rubrics_direct_attainment as pod'
                                    . ' JOIN po_rubrics_criteria as cri ON cri.rubrics_criteria_id = pod.rubrics_criteria_id '
                                    . ' JOIN po as p ON p.po_id = pod.po_id '
                                    . ' WHERE pod.po_extca_id = "'.$activity_id.'" ';
               $po_attain_data = $this->db->query($po_attain_query);
               $po_attain_res = $po_attain_data->result_array();
               return $po_attain_res;
                       
    }
	
		public function fetch_co_details($param) {
        extract($param);
        $query = $this->db->query('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", crs.crs_title AS "crs_title",  o.ao_description AS "occasion"
                                        FROM curriculum c
                                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id)                                        
                                        LEFT JOIN assessment_occasions o ON (o.crclm_id = c.crclm_id AND o.term_id = t.crclm_term_id AND o.crs_id = crs.crs_id )
                                        WHERE c.crclm_id = '.$crclm_id.'
                                        AND t.crclm_term_id = '.$term_id.'
                                        AND crs.crs_id = '.$crs_id);
        $data = $query->row_array();
        return $data;
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
	
	public function fetch_selected_param_details($params = NULL) {
        extract($params);
        if(isset($occasion_type)) {
            $type_string = ($occasion_type == '3') ? $this->lang->line('entity_cie') : 
                            (($occasion_type == '5') ? $this->lang->line('entity_tee') : 
                               ( $this->lang->line('entity_cie').'_'.$this->lang->line('entity_tee') ) ) ;
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title", "'.$type_string.'" AS "type"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }  else {
            $query = ('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", 
                        crs.crs_title AS "crs_title"
                        FROM curriculum c
                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id )
                        WHERE c.crclm_id = '.$crclm_id.'
                        AND t.crclm_term_id = '.$term_id.'
                        AND crs.crs_id = '.$crs_id.'');
        }
        
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }	
    
    public function fetch_extra_curricular_param_details($params = NULL) {
        extract($params);
        $terms = implode(",", $term_id);
        $query = ('SELECT c.crclm_name AS "crclm_name", GROUP_CONCAT(DISTINCT(ct.term_name)) AS "terms", p.activity_name AS "activity_name"
                    FROM po_extra_cocurricular_activity p
                    LEFT JOIN curriculum c ON c.crclm_id = p.crclm_id
                    LEFT JOIN crclm_terms ct ON ct.crclm_id = c.crclm_id
                    WHERE c.crclm_id = '.$crclm_id.'
                    AND ct.crclm_term_id IN ('.$terms.')');
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }
    
    public function fetch_survey_selected_param_details($params = NULL) {
        extract($params);
        $query = ('SELECT c.crclm_name AS "crclm_name", s.name AS "survey_name"
                    FROM indirect_attainment i
                    LEFT JOIN curriculum c ON i.crclm_id = c.crclm_id
                    LEFT JOIN su_survey s ON s.survey_id = i.survey_id
                    WHERE c.crclm_id = '.$crclm_id.'
                    AND s.survey_id = '.$survey_id);
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }

}// End of class
