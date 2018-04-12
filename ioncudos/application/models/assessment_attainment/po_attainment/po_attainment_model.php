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
  class Po_attainment_model extends CI_Model 
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
}// End of class
