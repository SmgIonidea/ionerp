<?php
/**
		* Description	:	Generates Internal & Final Exam Report
		
		* Created		:	December 15th, 2015
		
		* Author		:	Arihant Prasad D
		
		* Modification History:
		*   Date                Modified By                         Description
		* 26-02-2016		Shayista Mulla			Modified the occasion_fill function query.
	------------------------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Internal_final_exam_report_model extends CI_Model {
	
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
		
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
				//logged in user is other than admin
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			
			$curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
				FROM curriculum AS c, program AS p
				WHERE c.status = 1
				AND c.pgm_id = p.pgm_id
				AND p.dept_id = "'.$dept_id.'"
				ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_details);
			$curriculum = $curriculum_list_data->result_array();
			
			return $curriculum;
		}else{
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
		$term_name_result = $this->db->query($term_list_query);
		$term_name_result = $term_name_result->result_array();
		$data['term_name_result'] = $term_name_result;
		
		return $data;
	}
	
	/** 
	 * Function to fetch course details using curriculum id and term id from course table
	 * @parameters: curriculum id, term id
	 * @return: course id and course title
	*/
	public function fetch_course($curriculum_id, $term_id) {
		$user_id = $this->ion_auth->ion_auth->user()->row()->id;
		
		if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
			$course_name_query = 'SELECT crs_id, crs_title
								  FROM course
								  WHERE crclm_id = "' . $curriculum_id . '"
									AND crclm_term_id = "' . $term_id . '"
									AND status = 1
								  ORDER BY crs_title ASC';
		} else {
			$course_name_query = 'SELECT co.crs_id, c.crs_title 
								  FROM course c, course_clo_owner co 
								  WHERE c.crclm_id = "' . $curriculum_id . '"
									AND c.crclm_term_id = "' . $term_id . '"
									AND c.status = 1 
									AND co.crs_id = c.crs_id 
									AND co.clo_owner_id = "'.$user_id.'" 
								  ORDER BY c.crs_title ASC';
		}
		
		$course_name_result = $this->db->query($course_name_query);
		$course_list = $course_name_result->result_array();
		$data['course_list'] = $course_list;
		
		return $data;
	}
	
	/** 
	 * Function to fetch assessment type details
	 * @parameters: curriculum id, term id, course id
	 * @return: assessment type details
	*/
	public function occasion_fill($crclm_id, $term_id, $crs_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id, a.ao_description
													FROM assessment_occasions a, qp_definition q
													WHERE a.crclm_id = '.$crclm_id.'
														AND a.term_id = '.$term_id.'
														AND a.crs_id = '.$crs_id.'
														AND a.ao_type_id != 2
														AND q.qpd_id = a.qpd_id
														AND q.qp_rollout >=1
														AND q.qpd_type = 3
														AND a.mte_flag = 0
														AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
	}

	/** 
	 * Function to fetch assessment type details
	 * @parameters: curriculum id, term id, course id
	 * @return: assessment type details
	*/
	public function occasion_fill_mte($crclm_id, $term_id, $crs_id) {
		$fetch_course_query = $this->db->query('SELECT a.qpd_id, a.ao_description
													FROM assessment_occasions a, qp_definition q
													WHERE a.crclm_id = '.$crclm_id.'
														AND a.term_id = '.$term_id.'
														AND a.crs_id = '.$crs_id.'
														AND a.ao_type_id != 2
														AND q.qpd_id = a.qpd_id
														AND q.qp_rollout >=1
														AND q.qpd_type = 3
														AND a.mte_flag = 1
														AND a.qpd_id IS NOT NULL');
		return  $fetch_course_query->result_array();
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
	
	
	/** 
			* Function to fetch internal exam question paper details
			* @parameters: curriculum id, term id, course id
			* @return: question paper details
		*/
	public function fetch_internal_final_exam_details($crclm_id, $term_id, $crs_id, $occasion_id,$ao_type_id) {
		if($occasion_id != 0) {
			//internal exam question paper basic details - title, hours, course title, course code etc
			$model_qp_meta_data_query = 'SELECT qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing,
												qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
											FROM qp_definition AS qpd
											JOIN course AS crs ON qpd.crs_id = crs.crs_id
											WHERE qpd.crclm_id = "'.$crclm_id.'"
												AND qpd.crclm_term_id = "'.$term_id.'"
												AND qpd.crs_id = "'.$crs_id.'" 
												AND qpd.qpd_type = 3
												AND qpd.qpd_id = "'.$occasion_id.'"';
			$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
			$model_qp_meta_data_res = $model_qp_meta_data->result_array();
			$data['qp_basic_details'] = $model_qp_meta_data_res;
			
			//serial numbers and related questions
			$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion,
											mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
										FROM qp_unit_definition AS unit
										JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
										WHERE unit.qpd_id = "'.$occasion_id.'"
										ORDER BY  mq.qp_mq_code';
			$qpd_unit_data = $this->db->query($qpd_unit_data_query);
			$qpd_unit_data_res = $qpd_unit_data->result_array();
			$data['qp_questions_marks'] = $qpd_unit_data_res;
			
			return $data;
		} elseif($ao_type_id==1) {
			//final exam question paper basic details - title, hours, course title, course code etc
			$model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing,
												qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, 
												crs.crs_title, crs.crs_code
											FROM qp_definition AS qpd JOIN course AS crs ON qpd.crs_id = crs.crs_id
											WHERE qpd.crclm_id = "'.$crclm_id.'"
												AND qpd.crclm_term_id = "'.$term_id.'"
												AND qpd.crs_id = "'.$crs_id.'" 
												AND qpd.qpd_type = 5 
												AND qpd.qp_rollout = 2';										
			$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
			$model_qp_meta_data_res = $model_qp_meta_data->result_array();
			$data['qp_basic_details'] = $model_qp_meta_data_res;
			
			if(!empty($model_qp_meta_data_res)) {
				//serial numbers and related questions
				$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id,
													mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
											FROM qp_unit_definition AS unit
											JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
											WHERE unit.qpd_id = "'.$model_qp_meta_data_res[0]['qpd_id'].'" 
											 ORDER BY mq.qp_mq_code';
				$qpd_unit_data = $this->db->query($qpd_unit_data_query);
				$qpd_unit_data_res = $qpd_unit_data->result_array();
				$data['qp_questions_marks'] = $qpd_unit_data_res;
			}
			
			return $data;
		}else if($ao_type_id==3) {
			//final exam question paper basic details - title, hours, course title, course code etc
			$model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing,
												qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, 
												crs.crs_title, crs.crs_code
											FROM qp_definition AS qpd JOIN course AS crs ON qpd.crs_id = crs.crs_id
											WHERE qpd.crclm_id = "'.$crclm_id.'"
												AND qpd.crclm_term_id = "'.$term_id.'"
												AND qpd.crs_id = "'.$crs_id.'" 
												AND qpd.qpd_type = 4';										
			$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
			$model_qp_meta_data_res = $model_qp_meta_data->result_array();
			$data['qp_basic_details'] = $model_qp_meta_data_res;
			
			if(!empty($model_qp_meta_data_res)) {
				//serial numbers and related questions
				$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id,
													mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
											FROM qp_unit_definition AS unit
											JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
											WHERE unit.qpd_id = "'.$model_qp_meta_data_res[0]['qpd_id'].'" 
											ORDER BY  mq.qp_mq_code';
				$qpd_unit_data = $this->db->query($qpd_unit_data_query);
				$qpd_unit_data_res = $qpd_unit_data->result_array();
				$data['qp_questions_marks'] = $qpd_unit_data_res;
			}
			
			return $data;
		}
	}
	
	
	public function fetch_type_data($crclm_id, $term_id, $course_id){
	
		$query  = $this->db->query('select cia_flag , mte_flag , tee_flag from course where crs_id = "'. $course_id .'" and crclm_id = "'. $crclm_id .'" and crclm_term_id = "'. $term_id.'" ');
		$type_data =  $query->result_array();
		$types = array();
		$key = array();
		$t ='';
		foreach($type_data as $type ){
			if($type['cia_flag'] == 1){$types ['3'] = 'CIA';}
			if($type['mte_flag'] == 1){$types ['6'] = 'MTE';}
			$types ['4'] = 'Model TEE';
			if($type['tee_flag'] == 1){$types ['5'] = 'TEE';}			
			
		}	
			return $types;
			
	}
	
	public function fetch_mte_flag_status(){
	
		$query = $this->db->query('select mte_flag from organisation');
		$mte_flag =  $query->result_array();
		return $mte_flag[0]['mte_flag'];
	}
}
?>
