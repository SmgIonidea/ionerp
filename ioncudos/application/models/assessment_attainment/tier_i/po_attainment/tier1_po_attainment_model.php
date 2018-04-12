<?php 
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for CIA Adding, Editing operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 
 * 20-08-2014		Jevi V G     	         
  ---------------------------------------------------------------------------------------------------------------------------------
 */
  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Tier1_po_attainment_model extends CI_Model 
  {

	 /*
        * Function is to fetch the curriculum details.
        * @param - -----.
        * returns list of curriculum names.
	*/   
	 public function crlcm_drop_down_fill() {

/* 	 	if($this->ion_auth->is_admin()) {
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	} else {
	 		$logged_in_user_id = $this->ion_auth->user()->row()->user_dept_id;
	 		return $this->db->select('crclm_id, crclm_name')
	 		->order_by('crclm_name','ASC')
	 		->where('status',1)        		
	 		->get('curriculum')->result_array();
	 	}  */
		
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
	 public function term_drop_down_fill($crclm_id) {
	 	return $this->db->select('crclm_term_id, term_name')
	 	->where('crclm_id',$crclm_id)        		
	 	->get('crclm_terms')
	 	->result_array();
	 } 


	 public function qp_rolled_out($crs){

	 	return $this->db->select('qpd_id')
	 	->where('crs_id',$crs)
	 	->where('qp_roll_out',1)
	 	->get('qp_definition')
	 	->result_array();

	 }

	 public function course_po_list($crs){

	 	return $this->db->distinct()
	 	->select('po_id')
	 	->where('crs_id',$crs)
	 	->get('clo_po_map')
	 	->result_array();
	 }

	 public function getPOAttainment($term, $po = NULL) {
	 	$result = $this->db->query("call getPOAttainment(".$term.", NULL)");

	 	return $result->result_array();
	 }

	 public function getCourseList($term){

	 	return $this->db->select('crs_id')
	 	->where('crclm_term_id',$term)
	 	->get('course')
	 	->result_array();

	 }

	 public function getCourseAttainment($crclm_id, $term, $type_data){
	 	if($term != 'All') {
	 		if($type_data = 1) { 
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
			} else if($type_data = 2) {
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
			if($type_data = 1) { 
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
			} else if($type_data = 2) {
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
public function getPOThreshholdAttainment($crclm_id, $term, $course=NULL, $qpid=NULL, $student_usn=NULL, $qptype,$po_attainment_type,$core_crs_id) {
	if($term == 'All'){
		 
		 $qptype_val = NULL;
		  
		  if($qptype == 1){
		 	  $qptype_val = "TEE";
		  } else if($qptype == 2){
			  $qptype_val = "CIA";
		  } else{
		 	  $qptype_val = NULL;
		  }
		  if($po_attainment_type=='CO_TARGET'){
			$result = $this->db->query("call getPOAttainmentFromCO(".$crclm_id.",NULL,'".$core_crs_id."',NULL)");
		  }else{
			$result = $this->db->query("call getPOThreshholdAttainment(".$crclm_id.", NULL,'".$core_crs_id."', NULL, NULL, NULL, '".$qptype_val."')");  	
		  }
		return $result->result_array();
	
	} else {
		$qptype_val = NULL;
		if($qptype == 1){
		 	  $qptype_val = "TEE";
		  } else if($qptype == 2){
			  $qptype_val = "CIA";
		  } else{
		 	  $qptype_val = NULL;
		  }
		  if($po_attainment_type=='CO_TARGET'){
			$result1 = $this->db->query("call getPOAttainmentFromCO(".$crclm_id.",'".$term."','".$core_crs_id."',NULL)");
		  }else{
			$result1 = $this->db->query("call getPOThreshholdAttainment(".$crclm_id.",'".$term."','".$core_crs_id."', NULL, NULL, NULL, '".$qptype_val."')");  	
		  }

		 //$result1 = $this->db->query("call getPOThreshholdAttainment(".$crclm_id.",'".$term."', NULL, NULL, NULL, '".$qptype_val."')");
			return $result1->result_array();
	} 
}
	public function fetch_org_config_po(){
		$query = $this->db->query("SELECT * FROM org_config o where org_config_id = 6");
        return $query->result_array();	
	}	
	public function fetch_map_level_weightage(){
		$query = $this->db->query("SELECT * FROM map_level_weightage m");
        return $query->result_array();	
	}
/*
 * Function to get the Course based PO attainment.
 */

public function getCourse_po_threshold_based_attainment($crclm_id, $term,$po_attainment_type,$core_crs_id){
    if($core_crs_id !=1 ){
        $po_attainment_query = 'SELECT po_att.po_id, po_att.crclm_id, po_att.crclm_term_id, p.po_reference, p.po_statement, 
									p.po_minthreshhold,
								po_att.threshold_po_direct_attainment, po_att.average_po_direct_attainment,po_att.average_da ,po_att.hml_weighted_average_da , po_att.hml_weighted_multiply_maplevel_da ,'
                            . ' cast(ROUND(AVG(average_da)) as decimal(10,2)) as po_threshold_attainment, '
                            . ' cast(ROUND(AVG(average_po_direct_attainment)) as decimal(10,2)) as avg_po_attainment ,' 
							. ' cast(ROUND(AVG(hml_weighted_average_da)) as decimal(10,2)) as hml_weighted_average_da_avg, '
                            . ' cast(ROUND(AVG(hml_weighted_multiply_maplevel_da)) as decimal(10,2)) as hml_weighted_multiply_maplevel_da_avg '
                            . ' FROM tier1_po_direct_attainment as po_att '
                            . ' LEFT JOIN po as p ON p.po_id = po_att.po_id '
                            . ' WHERE po_att.crclm_id = "'.$crclm_id.'" '
                            . ' AND po_att.crclm_term_id IN('.$term.') GROUP BY po_att.po_id
							ORDER BY LENGTH(p.po_reference), p.po_reference';
    }else{
       $po_attainment_query = 'SELECT po_att.crs_id, po_att.po_id, po_att.crclm_id, po_att.crclm_term_id, p.po_reference, p.po_statement, p.po_minthreshhold,
                                cast(ROUND(AVG(average_da)) as decimal(10,2)) as po_threshold_attainment,
                                cast(ROUND(AVG(average_po_direct_attainment)) as decimal(10,2)) as avg_po_attainment,
								cast(ROUND(AVG(hml_weighted_average_da)) as decimal(10,2)) as hml_weighted_average_da_avg, 
								cast(ROUND(AVG(hml_weighted_multiply_maplevel_da)) as decimal(10,2)) as hml_weighted_multiply_maplevel_da_avg,
								po_att.threshold_po_direct_attainment, po_att.average_po_direct_attainment,po_att.average_da ,po_att.hml_weighted_average_da , po_att.hml_weighted_multiply_maplevel_da ,
                                typ.crs_type_id, cr.crs_title, cr.crs_code
                                FROM tier1_po_direct_attainment as po_att
                                LEFT JOIN course_type as typ ON typ.core_crs_flag = 1
                                LEFT JOIN course as cr ON cr.crs_type_id = typ.crs_type_id AND cr.crs_id = po_att.crs_id
                                LEFT JOIN po as p ON p.po_id = po_att.po_id
                                WHERE po_att.crclm_id = "'.$crclm_id.'"
                                AND po_att.crclm_term_id IN('.$term.') GROUP BY po_att.po_id'; 
    }
    
    $po_attainment = $this->db->query($po_attainment_query);
    $po_attainment_res = $po_attainment->result_array();
    
   
    return $po_attainment_res;
    
}

public function getCoursePOAttainment($crclm,$term, $po_id,$qp_type,$po_attainment_type,$core_crs_id) {
	if($term == 'All'){
		$qptype_val = NULL;
		if($qp_type == 1){
			$qptype_val = 'TEE';
		} else if($qp_type == 2) {
			$qptype_val = 'CIA';
		} else {
			$qptype_val = NULL;
		}
		if($po_attainment_type=="CO_TARGET"){
			$r = $this->db->query("call getPOAttainmentFromCO(".$crclm.",'".$term."','".$core_crs_id."',".$po_id.")");
		}else{
			$r = $this->db->query("call getCoursePOAttainment(".$crclm.",NULL,".$po_id.",'".$qptype_val."')");
		}
		
		return $r->result_array();
	} else {
		$qptype_val = NULL;
		if($qp_type == 1){
			$qptype_val = 'TEE';
		} else if($qp_type == 2) {
			$qptype_val = 'CIA';
		} else {
			$qptype_val = NULL;
		}
		if($po_attainment_type=="CO_TARGET"){
			$r = $this->db->query("call getPOAttainmentFromCO(".$crclm.",'".$term."','".$core_crs_id."',".$po_id.")");
		}else{
			$r = $this->db->query("call getCoursePOAttainment(".$crclm.",'".$term."',".$po_id.",'".$qptype_val."')");	
		}
		
		return $r->result_array();
	}
}

public function tier1_getCoursePOAttainment($crclm, $term, $po_id, $core_crs_id) {
        $result = $this->db->query("call getTier_1_DrillDownForPO($crclm,'" . $term . "',$core_crs_id,$po_id)");
        return $result->result_array();
    }

public function qp_direct_indirect($crs){
	
	return $this->db->select('qpd_id')
	->where('crs_id',$crs)
	->where('qp_rollout > 1')
	->where('qpd_type',5)
	->get('qp_definition')
	->result_array();

}
//Edited By Shivaraj code starts here
public function getDirectIndirectPOAttaintmentData($crclm_id,$term_id,$qpd_id,$usn,$qpd_type,$direct_attainment,$indirect_attainment,$survey_id_arr,$survey_perc_arr,$po_attainment_type,$core_courses_cbk) {
	$qp_type = "BOTH";
	if($qpd_type == 1){
		$qp_type = "TEE";
	}else if($qpd_type == 2){
		$qp_type = "CIA";
	}
	if($po_attainment_type=="CO_TARGET"){
		$r = $this->db->query("call getDirectIndirectPOAttaintmentDataFromCO(".$crclm_id.",'".$term_id."',".$core_courses_cbk.",".$direct_attainment.",".$indirect_attainment.",('".$survey_id_arr."'),('".$survey_perc_arr."'))");	
	}else{
		$r = $this->db->query("call getDirectIndirectPOAttaintmentData(".$crclm_id.",'".$term_id."',".$core_courses_cbk.",NULL,NULL,NULL,'".$qp_type."',".$direct_attainment.",".$indirect_attainment.",('".$survey_id_arr."'),('".$survey_perc_arr."'))");	
	}

	return $r->result_array();
}
//code ends here

public function survey_drop_down_fill($crclm_id) {
	return $this->db->select('survey_id, name')
	->where('crclm_id',$crclm_id)        		
	->where('su_for',7)        		
	->get('su_survey')
	->result_array();
}

public function getDirectIndirectCOAttaintmentData($course_id,$qpd_id,$usn,$qpd_type,$direct_attainment,$indirect_attainment,$survey_id) {
	$result = $this->db->query("call getDirectIndirectCOAttaintmentData(".$course_id.",".$qpd_id.",NULL,".$qpd_type.",".$direct_attainment.",".$indirect_attainment.",".$survey_id.")");

	return $result->result_array();
}

	//Edited by Shivaraj B

public function getSurveyNames(){
	$result = $this->db->get('su_survey');
	return $result->result_array();
}

public function getPOAttainmentType(){
	$result = $this->db->select('value')->where('config','PO_ATTAINMENT_TYPE')->get('org_config');
	return $result->result_array();
}

/*
	Function to insert the PO finalized data
*/
public function insert_po_finalize_data($da_weightage,$ia_weightage,$threshold_da_attainment,$threshold_ia_attainment, $average_da_attainment, $average_ia_attainment, $threshold_po_overall_attainment, $average_po_overall_attainment, $po_id, $crclm_id, $term_id){
	// Delete data from tier1_po_overall_attainment using crclm_id
	$delete_query = 'DELETE FROM tier1_po_overall_attainment WHERE crclm_id = "'.$crclm_id.'"';
	$delete_success = $this->db->query($delete_query);
	
	$da_weight = explode(',',$da_weightage);
	$ia_weight = explode(',',$ia_weightage);
	$thr_da_attainmnet = explode(',',$threshold_da_attainment);
	$thr_ia_attainmnet = explode(',',$threshold_ia_attainment);
	$avg_da_attainment = explode(',',$average_da_attainment);
	$avg_ia_attainment = explode(',',$average_ia_attainment);
	$thr_po_overall_attainment = explode(',',$threshold_po_overall_attainment);
	$avg_po_overall_attainment = explode(',',$average_po_overall_attainment);
	$po_ids = explode(',',$po_id);
			$po_id_array_seze = count($po_ids);
			for($i=0;$i<$po_id_array_seze;$i++){
				$insert_array = array(
					'crclm_id' => $crclm_id,
					'crclm_term_id' => NULL,
					'po_id' => $po_ids[$i],
					'da_weightage' => $da_weight[$i],
					'ia_weightage' => $ia_weight[$i],
					'threshold_da_attainment' => $thr_da_attainmnet[$i],
					'threshold_ia_attainment' => $thr_ia_attainmnet[$i],
					'average_da_attainment' => $avg_da_attainment[$i],
					'average_ia_attainment' => $avg_ia_attainment[$i],
					'threshold_po_overall_attainment' => $thr_po_overall_attainment[$i],
					'average_po_overall_attainment' => $avg_po_overall_attainment[$i],
					'created_by' => $this->ion_auth->user()->row()->id,
					'created_date' => date('y-m-d'),
					'modified_by' => $this->ion_auth->user()->row()->id,
					'modified_date' => date('y-m-d'),
				);
				$this->db->insert('tier1_po_overall_attainment',$insert_array);
			}
			return true;
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
               
               $po_attain_query1 = 'SELECT pod.po_extca_id, pod.rubrics_criteria_id, pod.po_id, ROUND(AVG(pod.po_rubrics_attainment),2) as attainment, '
                                    . ' cri.criteria, p.po_reference, p.po_statement '
                                    . ' FROM po_rubrics_direct_attainment as pod'
                                    . ' JOIN po_rubrics_criteria as cri ON cri.rubrics_criteria_id = pod.rubrics_criteria_id '
                                    . ' JOIN po as p ON p.po_id = pod.po_id '
                                    . ' WHERE pod.po_extca_id = "'.$activity_id.'" GROUP BY p.po_id';
               $po_attain_data1 = $this->db->query($po_attain_query1);
               $po_attain_res1 = $po_attain_data1->result_array();
               $data['table_result'] = $po_attain_res;
               $data['graph_result'] = $po_attain_res1;
               
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
        $terms = implode(",", $term);
        $query = ('SELECT c.crclm_name AS "crclm", GROUP_CONCAT(DISTINCT(ct.term_name)) AS "terms"
                    FROM curriculum c
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
    
    public function fetch_extra_curricular_param_details($params = NULL) {
        extract($params);
        $terms = implode(",", $term_id);
        $query = ('SELECT c.crclm_name AS "crclm_name", GROUP_CONCAT(DISTINCT(ct.term_name)) AS "terms", p.activity_name AS "activity_name"
                    FROM po_extra_cocurricular_activity p
                    LEFT JOIN curriculum c ON c.crclm_id = p.crclm_id
                    LEFT JOIN crclm_terms ct ON ct.crclm_id = c.crclm_id
                    WHERE c.crclm_id = '.$crclm_id.'
                    AND p.po_extca_id = '.$activity_id.'
                    AND ct.crclm_term_id IN ('.$terms.')');
        $param_details = $this->db->query($query);
        return $param_details->row_array();
    }

}// End of class
