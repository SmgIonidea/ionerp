<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * Description	:	Tier II MTE CO Level Attainment Model

 * Created		:	march 20th, 2017

 * Author		:	 Bhagyalaxmi S S

 * Modification History:
 * Date				Modified By				Description

  ---------------------------------------------------------------------------------------------- */
?>
<?php

class Mte_co_section_level_attainment_model extends CI_Model {
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
													)AND c.crclm_term_id = ' . $term);
        return $fetch_course_query->result_array();
    }
    
    public function section_drop_down($term_id,$crclm_id,$crs_id){
        
          $query = $this->db->query('select section_id from map_courseto_course_instructor where crclm_id = "'.$crclm_id .'" and crclm_term_id ="'. $term_id .'" and crs_id = "'. $crs_id .'" ');
          $query = $this->db->query('select section_id ,mt_details_name from map_courseto_course_instructor as c join master_type_details as m on m.mt_details_id = c.section_id  	where crclm_id = "'.$crclm_id .'" and crclm_term_id ="'. $term_id .'" and crs_id = "'. $crs_id .'"');
		  return $query->result_array();
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
////AND q.qp_rollout > 1  and AND a.qpd_id IS NOT NULL and AND q.qpd_id = a.qpd_id
        if ($term_id != 'select_all_term') {
            if ($crs_id != 'select_all_course') {
                $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description
													FROM assessment_occasions a,qp_definition q
													WHERE a.crclm_id = ' . $crclm_id . '
													AND a.term_id = ' . $term_id . '
													AND a.crs_id = ' . $crs_id . '																
													AND q.qpd_type = 3                                                    
													AND a.section_id = 0
													AND a.mte_flag = 1
													group by a.ao_id');
            } else if ($crs_id == 'select_all_course') {
                $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND a.term_id = ' . $term_id . ' 
													AND a.section_id = 0
													AND q.qpd_type = 3                                                     
													AND a.mte_flag = 1
													group by a.ao_id');
            }
        } else if ($term_id == 'select_all_term') {
            $fetch_occasion_query = $this->db->query('SELECT a.ao_id,a.ao_description,a.term_id,a.crs_id 
													FROM assessment_occasions a,qp_definition q 
													WHERE a.crclm_id = ' . $crclm_id . ' 
													AND q.qpd_type = 3 													
													AND a.section_id = 0
													AND a.mte_flag = 1
													group by a.ao_id');
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
	public function find_occsions_finalized_or_not($course, $qpd_id, $type ,$occasion_not_selected){
	
	for($i = 0; $i < count($qpd_id); $i++){
		$result = $this->db->query("SELECT a.ao_id , a.qpd_id , a.ao_description,q.qp_rollout , 
												CASE WHEN (q.qp_rollout = 1) THEN concat('','  <br/> >> Assessment data (student marks) are not imported/uploaded for these Occasions : <b>', group_concat(a.ao_description SEPARATOR  ' , ') ,'</b>')
												ELSE ' ' END as status_data
												FROM assessment_occasions as a
														left join qp_definition as q on q.qpd_id = a.qpd_id
														where a.crs_id = ". $course ."
														and section_id = 0
														AND a.mte_flag = 1
													and (q.qp_rollout < 2 OR q.qp_rollout IS NULL)
														group by qp_rollout");
		}
			return $result->result_array();
	}
    public function get_clo_attainment($course_id, $ao_id, $type ,$occasion_not_selected) {
       
		$res_wgt = $this->db->query("SELECT total_cia_weightage, total_mte_weightage ,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
		$mte_wgt = $wgt_data->total_mte_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
	   if ($type == 3) {
            if ($occasion_not_selected == 0) { 

					  $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,'Rolled0ut' as status_data,
					  if( threshold_direct_attainment  IS NULL ||  threshold_direct_attainment  = 0 ,'', CONCAT( ROUND(AVG(threshold_direct_attainment),2) ,'%')  ) as threshold_direct_attainment_display ,
					  ROUND(AVG(threshold_direct_attainment),2) as threshold_direct_attainment ,
					    ROUND(AVG(threshold_direct_attainment),2) as threshold_direct_attainment , ROUND(AVG(threshold_direct_attainment)*'" . $mte_wgt . "'/100,2) as threshold_clo_da_attainment_wgt ,
					  if( average_direct_attainment  IS NULL ||  average_direct_attainment  = 0 ,'', CONCAT( ROUND(AVG(average_direct_attainment),2) ,'%')  ) as average_direct_attainment_display ,
					  ROUND(AVG(average_direct_attainment),2) as average_direct_attainment   ,
					  ROUND(AVG(t.attainment_level),2)as attainment_level FROM tier_ii_section_clo_ao_attainment t,clo c
						WHERE c.clo_id = t.clo_id AND t.crs_id= '". $course_id ."' AND t.assess_type= '". $type ."' and section_id IS NULL  group by c.clo_code");

            } else {
					$occasion = implode("," , $ao_id);
				$occasion_selected = str_replace("'", "", $occasion);				
				$result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,'Rolled0ut' as status_data,
			  if( threshold_direct_attainment  IS NULL ||  threshold_direct_attainment  = 0 ,'', CONCAT( ROUND(AVG(threshold_direct_attainment),2) ,'%')  ) as threshold_direct_attainment_display ,
				ROUND(AVG(threshold_direct_attainment),2) as threshold_direct_attainment ,
				 ROUND(AVG(threshold_direct_attainment),2) as threshold_direct_attainment , ROUND(AVG(threshold_direct_attainment)*'" . $mte_wgt . "'/100,2) as threshold_clo_da_attainment_wgt ,
			  if( average_direct_attainment  IS NULL ||  average_direct_attainment  = 0 ,'', CONCAT( ROUND(AVG(average_direct_attainment),2) ,'%')  ) as average_direct_attainment_display ,
			  ROUND(AVG(average_direct_attainment),2) as average_direct_attainment   ,
			  (t.attainment_level) as attainment_level FROM tier_ii_section_clo_ao_attainment t,clo c
				WHERE c.clo_id = t.clo_id AND t.ao_id  IN(".$occasion_selected.") AND t.crs_id= '". $course_id ."' AND t.assess_type= '3' and section_id IS NULL group by c.clo_code");
            }
        } else if ($type == 5) {
               $result = $this->db->select('t.*,c.clo_code,c.clo_statement')
                    ->from('tier_ii_clo_ocassion_attainment t')
                    ->join('clo c', 'c.clo_id = t.clo_id')
                    ->where('t.assess_type', $type)
                    ->where('t.crs_id', $course_id)->order_by("c.clo_code", "asc")
                    ->get();
        } else if ($type == 0) {
            $result = $this->db->query("call get_tier_ii_CourseCOLevelAttainment('" . $course_id . "',0);");
        }

        return $result->result_array();
    }

	function fetch_status($course, $ao_id, $type ,$occasion_not_selected){
	if( $occasion_not_selected != 0){
		$occasion = implode("," , $ao_id);
		$occasion_selected = str_replace("'", "", $occasion);
		
		$query = $this->db->query("SELECT a.ao_id , a.qpd_id , a.ao_description,q.qp_rollout ,
									CASE WHEN  (a.qpd_id IS NULL) THEN 'Question Paper is not defined for this Occasion'
										 WHEN  (q.qp_rollout = 1) THEN 'Assessment data (student marks) are not uploaded/imported for this Occasion'  ELSE ' ' END as status_data
									FROM assessment_occasions as a
									left join qp_definition as q on q.qpd_id = a.qpd_id where a.crs_id = ". $course ."  and ao_id IN(". $occasion_selected.") and section_id = 0 AND a.mte_flag = 1 ");
		}else{
		$query =  $this->db->query("SELECT a.ao_id , a.qpd_id , a.ao_description,q.qp_rollout , 
												CASE WHEN  (a.qpd_id IS NULL) THEN concat(' >> Question Paper is not defined for these Occasions : <b>',  group_concat(a.ao_description SEPARATOR  '  ,  ') ,'</b>', '  ')
												WHEN  (q.qp_rollout = 1) THEN concat('','  <br/> >> Assessment data (student marks) are not imported/uploaded for these Occasions : <b>', group_concat(a.ao_description SEPARATOR  ' , ') ,'</b>')
												ELSE ' ' END as status_data
												FROM assessment_occasions as a
														left join qp_definition as q on q.qpd_id = a.qpd_id
														where a.crs_id = ". $course ."
														and section_id = 0 AND a.mte_flag = 1
													and (q.qp_rollout < 2 OR q.qp_rollout IS NULL)
														group by qp_rollout");
		}
		return $query->result_array();
	}
    function get_drilldown_for_co($clo_id, $ao_id, $crs_id , $section_id) {
        $res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $crs_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
		$mte_wgt = $wgt_data->total_mte_weightage;
        $wgt_arr = array(
            'cia_wgt' => $cia_wgt,
            'tee_wgt' => $tee_wgt,
			'mte_wgt' => $mte_wgt,
        );
        if($ao_id == "all_occasion") {
            $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,ROUND(AVG(t.attainment_level),2) AS al,t.threshold_direct_attainment AS ip,
										ROUND(AVG(t.threshold_direct_attainment)*'" . $cia_wgt . "'/100,2) as individual_da_percentage,
										ROUND(AVG(t.attainment_level)*'" . $cia_wgt . "'/100,2) as attainment_level,
										a.ao_description FROM tier_ii_section_clo_ao_attainment t,clo c,assessment_occasions a 
										WHERE t.clo_id=c.clo_id AND t.clo_id = '" . $clo_id . "' 
										AND t.section_id = '". $section_id ."'
										AND t.ao_id=a.ao_id AND t.assess_type=3 group by t.sclo_ao_id");
        }
        return array('wgt_arr' => $wgt_arr, 'query_data' => $result->result_array());
        //return $result->result_array();
    }
    public function section_dropdown_list_finalize($crclm_id, $term_id, $course_id ,$section_id){
     

	    $res_wgt = $this->db->query("SELECT total_cia_weightage, total_mte_weightage ,total_tee_weightage FROM course c where crs_id='" . $course_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
		$mte_wgt = $wgt_data->total_mte_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;

		
        $select_section_query = 'SELECT map.section_id, mtd.mt_details_name as section_name FROM map_courseto_course_instructor as map '
                . ' LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map.section_id WHERE map.crs_id = "'.$course_id.'" and map.section_id = "'. $section_id.'" ';
        $section_data = $this->db->query($select_section_query);
        $section_result = $section_data->result_array();
      
       /*  if(!empty($section_result))
		{
                    foreach($section_result as $section_val){ */
            $section_status_query = 'SELECT COUNT(ao_id) as status FROM tier_ii_section_clo_overall_cia_attainment WHERE section_id = 0 AND crs_id = "'.$course_id.'" ';
            $status_data = $this->db->query($section_status_query);
            $status_res = $status_data->row_array();
              
            $select_query = 'SELECT tier2.clo_id, tier2.threshold_clo_da_attainment as attainment,  ROUND((tier2.threshold_clo_da_attainment)*'. $mte_wgt . '/100,2) as threshold_clo_da_attainment_wgt ,tier2.average_clo_da_attainment as avg_attainment, tier2.section_id, tier2.crs_id,tier2.attainment_level, '
                            . ' cl.clo_code, cl.clo_statement, CAST(cl.cia_clo_minthreshhold as decimal(10,2)) as cia_clo_minthreshhold , cl.tee_clo_minthreshhold  '
                            . ' FROM tier_ii_section_clo_overall_cia_attainment as tier2 '                            
                            . ' LEFT JOIN clo as cl ON cl.clo_id = tier2.clo_id '
                            . ' WHERE tier2.crclm_id = "'.$crclm_id.'"'
                            . ' AND tier2.crclm_term_id = "'.$term_id.'"'
                            .'  AND tier2.crs_id = "'.$course_id.'"'
                            .'  AND tier2.section_id = 0  order by cl.clo_code';
            $overall_data = $this->db->query($select_query);
            $overall_attain_res = $overall_data->result_array();
            $section_over_all_attain[] = $overall_attain_res ;
            $status[] = $status_res;
     //   }
       
        $data['section_name_array'] =  $section_result;
        $data['secction_attainment_array'] = $section_over_all_attain;
        $data['status'] = $status;
		
        return $data;
    /*     }else{
            exit;
        } */
    }

	
		function get_drilldown_for_co_average($clo_id, $ao_id, $crs_id , $section_id) {
        $res_wgt = $this->db->query("SELECT total_cia_weightage,total_tee_weightage FROM course c where crs_id='" . $crs_id . "'");
        $wgt_data = $res_wgt->row();
        $cia_wgt = $wgt_data->total_cia_weightage;
        $tee_wgt = $wgt_data->total_tee_weightage;
        $wgt_arr = array(
            'cia_wgt' => $cia_wgt,
            'tee_wgt' => $tee_wgt,
        );
        if ($ao_id == "all_occasion") {
            $result = $this->db->query("SELECT c.clo_id,c.clo_code,c.clo_statement,t.attainment_level AS al,t.average_direct_attainment AS ip,
										ROUND(AVG(t.average_direct_attainment)*'" . $cia_wgt . "'/100,2) as individual_da_percentage,
										ROUND(AVG(t.attainment_level)*'" . $cia_wgt . "'/100,2) as attainment_level,
										a.ao_description FROM tier_ii_section_clo_ao_attainment t,clo c,assessment_occasions a 
										WHERE t.clo_id=c.clo_id AND t.clo_id = '" . $clo_id . "' 
										AND t.section_id = ". $section_id ."
										AND t.ao_id=a.ao_id AND t.assess_type=3 group by t.sclo_ao_id;");
        }
        return array('wgt_arr' => $wgt_arr, 'query_data' => $result->result_array());
        //return $result->result_array();
    }
	

    public function get_crs_level_attainment($crs_id) {
        return $this->db->get_where('attainment_level_course', array('crs_id' => $crs_id))->result_array();
    }

    public function finalize_clo_attainment_data($crclm_id, $term_id, $crs_id) {
        $where = array('crclm_id' => $crclm_id, 'crclm_term_id' => $term_id, 'crs_id' => $crs_id);
        $this->db->delete('tier_ii_crs_clo_overall_attainment', $where);
        $is_added = $this->db->query("call get_tier_ii_CourseCOLevelAttainment('" . $crs_id . "',1)");
        $this->db->delete('tier_ii_po_direct_attainment', $where);
        $this->db->query("call storeTierIIPOAttainmentLevel(" . $crclm_id . "," . $term_id . "," . $crs_id . ");");
        return $is_added;
    }
    
      
    public function finalize_clo_attainment($crclm_id,  $term_id, $course_id, $section_id, $type_id, $occasion_id){
        if($type_id == 2){
            $cia = 3;
        }
        
        $count_occasions = 'SELECT count(ao_id) as count_ao_id FROM assessment_occasions WHERE crs_id = "'.$course_id.'" AND section_id = "'.$section_id.'" ';
        $count_occasion_data = $this->db->query($count_occasions);
        $count_result = $count_occasion_data->row_array();
        $attainment_query = 'SELECT att.sclo_ao_id, att.assess_type, att.ao_id, att.crclm_id, att.crclm_term_id, att.crs_id, att.section_id, att.clo_id, '
                            . ' att.attainment_level, att.threshold_direct_attainment, cast(ROUND(AVG(att.average_direct_attainment))as decimal(10,2)) as average_ao_direct_attainment,cast(ROUND(AVG(att.threshold_direct_attainment)) as decimal(10,2)) as threshold_ao_direct_attainment, '
                            . ' cast((AVG(att.attainment_level))as decimal(10,2)) as avg_attainment_level, att.created_by, att.created_date, att.modified_by, att.modified_date, mtd.mt_details_name as section_name, '
                            . ' cl.clo_statement, cl.cia_clo_minthreshhold, cl.clo_code'
                            . ' FROM tier_ii_section_clo_ao_attainment as att'
                            . ' LEFT JOIN master_type_details  as mtd  ON mtd.mt_details_id  = att.section_id'
                            . ' LEFT JOIN clo as cl ON cl.clo_id = att.clo_id'
                            . ' WHERE att.assess_type = "'.$cia.'"  AND att.crclm_id = "'.$crclm_id.'" '
                            . ' AND att.crclm_term_id = "'.$term_id.'" AND att.crs_id = "'.$course_id.'"  AND section_id IS NULL GROUP BY att.clo_id ';
       $clo_attainment_data = $this->db->query($attainment_query);
       $clo_attainment_res = $clo_attainment_data->result_array();
        
        $data_check = 'SELECT COUNT(clo_oa_id) as data_size FROM tier_ii_section_clo_overall_cia_attainment WHERE crs_id = "'.$course_id.'"  AND section_id = "'.$section_id.'" AND section_id = 0 ';
        $data_data = $this->db->query($data_check);
        $data_res = $data_data->row_array();
        if($data_res['data_size'] == 0){
		foreach($clo_attainment_res as $clo_final){
                $insert_array = array(
                    'crclm_id'=>$crclm_id,
                    'crclm_term_id'=>$term_id,
                    'crs_id'=>$course_id,
                    'section_id'=>$section_id,
                    'assess_type' =>  $cia,
                    'ao_id'=>$clo_final['ao_id'],
                    'clo_id'=>$clo_final['clo_id'],
                    'individual_da_percentage'=>$clo_final['threshold_ao_direct_attainment'],					
                    'individual_weightage'=>$clo_final['average_ao_direct_attainment'],
                    'attainment_level' => $clo_final['avg_attainment_level'],
					'threshold_clo_da_attainment'=>$clo_final['threshold_ao_direct_attainment'],   
					'average_clo_da_attainment'=>$clo_final['average_ao_direct_attainment'],
                    'created_by'=>$this->ion_auth->user()->row()->id,
                    'created_date'=>date('y-m-d'),
                    'modified_by'=>$this->ion_auth->user()->row()->id,
                    'modified_date'=>date('y-m-d'),
                );
                $this->db->insert('tier_ii_section_clo_overall_cia_attainment',$insert_array);

            } 
        }else{		
            $delete_query  = 'DELETE FROM tier_ii_section_clo_overall_cia_attainment WHERE crs_id = "'.$course_id.'" AND section_id = 0';
            $delete_query_res  = $this->db->query($delete_query);
            
            foreach($clo_attainment_res as $clo_final){
                $insert_array = array(
                    'crclm_id'=>$crclm_id,
                    'crclm_term_id'=>$term_id,
                    'crs_id'=>$course_id,
                    'section_id'=>$section_id,
                    'assess_type' =>  $cia,
                    'ao_id'=>$clo_final['ao_id'],
                    'clo_id'=>$clo_final['clo_id'],
                    'individual_da_percentage'=>$clo_final['threshold_ao_direct_attainment'],
                    'individual_weightage'=>$clo_final['average_ao_direct_attainment'],
                    'attainment_level' => $clo_final['avg_attainment_level'],
					'threshold_clo_da_attainment'=>$clo_final['threshold_ao_direct_attainment'],   
					'average_clo_da_attainment'=>$clo_final['average_ao_direct_attainment'],
                    'created_by'=>$this->ion_auth->user()->row()->id,
                    'created_date'=>date('y-m-d'),
                    'modified_by'=>$this->ion_auth->user()->row()->id,
                    'modified_date'=>date('y-m-d'),
                );
                $this->db->insert('tier_ii_section_clo_overall_cia_attainment',$insert_array);

            }
            
            
        }
        
            $update_query = 'UPDATE course_clo_owner SET mte_finalize_flag = 1 WHERE  crs_id = "'.$course_id.'" ';
            $update_suc = $this->db->query($update_query);
       
       return true;
    }
    
    
    public function get_co_mapped_questions($clo_id, $type_data, $ao_id , $occasion_not_selected) {	
		$occasion = implode("," , $ao_id);
		$occasion_selected = str_replace("'", "", $occasion);	
        if ($type_data == 2) {
            $qpd_data = $this->db->select('qpd_id')->from('assessment_occasions')->where_in('ao_id', $occasion_selected)->get()->row();

            if (empty($qpd_data)) {
                $qpd_id = "";
            } else {
                $qpd_id = $qpd_data->qpd_id;
            }
            if ($occasion_not_selected == 0) {
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
										AND aocc.section_id  = 0
										AND aocc.mte_flag = 1
										ORDER BY mm.mt_details_name, mnq.qp_subq_code ASC');
            } else {

		
				$ao_data = implode("," , $ao_id);
				$ao_ids = str_replace("'", "", $ao_data);
                
				$ao_query = 'SELECT qpd_id  FROM assessment_occasions WHERE ao_id IN('.$ao_ids.') and mte_flag = 1 ';
                $ao_data = $this->db->query($ao_query);
                $ao_res = $ao_data->row_array(); 

				$qpd_id_val = implode("," , $ao_res);
				$qpd_data = str_replace("'", "", $qpd_id_val);  
				

                $result = $this->db->query('SELECT qpm.actual_mapped_id, qpm.entity_id, mnq.qp_subq_code, qpm.qp_mq_id, qpm.qp_map_id, mnq.qp_unitd_id, mnq.qp_content, un.qpd_id,  qpd.qpd_type,mm.mt_details_name,mnq.qp_subq_marks,aocc.ao_description,c.clo_code,c.clo_statement   
										FROM qp_mapping_definition as qpm
										LEFT JOIN qp_mainquestion_definition as mnq ON mnq.qp_mq_id = qpm.qp_mq_id
										LEFT JOIN qp_unit_definition as un ON un.qpd_unitd_id = mnq.qp_unitd_id
										LEFT JOIN qp_definition as qpd ON qpd.qpd_id = un.qpd_id
										LEFT JOIN master_type_details as mm ON mm.mt_details_id = qpd.qpd_type 
										LEFT JOIN assessment_occasions aocc ON aocc.ao_id IN ('. $ao_ids .') 
										LEFT JOIN clo c ON c.clo_id = "' . $clo_id . '"
										WHERE qpm.actual_mapped_id = "' . $clo_id . '" AND qpm.entity_id = 11 
										AND qpd.qpd_id IN( "' . $qpd_data . '") AND qpd.qpd_type = 3 
										AND qpd.qp_rollout = 2 
										AND aocc.section_id = 0
										AND aocc.mte_flag = 1
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
										AND aocc.section_id = 0
										AND aocc.mte_flag = 1
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
	public function fetch_co_details($param) {
        extract($param);
        $query = $this->db->query('SELECT c.crclm_name AS "crclm_name", t.term_name AS "term_name", crs.crs_code AS "crs_code", crs.crs_title AS "crs_title", mtd.mt_details_name AS "section_name", mtd.mt_details_name_desc AS "section_desc", o.ao_description AS "occasion"
                                        FROM curriculum c
                                        LEFT JOIN crclm_terms t ON t.crclm_id = c.crclm_id
                                        LEFT JOIN course crs ON (crs.crclm_id = c.crclm_id AND t.crclm_term_id = crs.crclm_term_id)
                                        LEFT JOIN master_type_details mtd ON mtd.mt_details_id = '.$section_id.'
                                        LEFT JOIN assessment_occasions o ON (o.crclm_id = c.crclm_id AND o.term_id = t.crclm_term_id AND o.crs_id = crs.crs_id AND o.section_id = '.$section_id.')
                                        WHERE c.crclm_id = '.$crclm_id.'
                                        AND t.crclm_term_id = '.$term_id.'
                                        AND crs.crs_id = '.$crs_id.'
                                        AND o.ao_id = '.$occasion_id);
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

}