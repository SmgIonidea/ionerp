<?php

/**
 * Description	:	Controller logic for bloom's level for display, add, edit and delete.
 * 					
 * Created		:	17-09-2014
 *
 * Author		:	Mritunjay B S
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 
 *
  --------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH')){
    exit('No direct script access allowed');
}

class Manage_model_qp_model extends CI_Model {
	
	function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('assessment_attainment/import_cia_data/import_cia_data_model');
    }
    
/* Function is used to fetch the dept id & name from dept table.
	 * @param - 
	* @returns- a array of values of the dept details.
	*/	
	public function dept_fill() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		
		if($this->ion_auth->is_admin()) {
			$dept_name = 'SELECT DISTINCT dept_id, dept_name
						  FROM department 
						  WHERE status = 1
						  ORDER BY dept_name ASC';
		} else {
			$dept_name = 'SELECT DISTINCT d.dept_id, d.dept_name
						  FROM department AS d, users AS u
						  WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = d.dept_id 
							AND d.status = 1 
							ORDER BY dept_name ASC';
		}
		
		$resx = $this->db->query($dept_name);
		$result = $resx->result_array();
		$dept_data['dept_result'] = $result;
		
		return $dept_data;
	}

	/* Function is used to fetch the pgm id & name from program table.
	 * @param - 
	* @returns- a array of values of the program details.
	*/	
	public function pgm_fill($dept_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 	
					 FROM program
					 WHERE dept_id = "' . $dept_id . '"
						AND status = 1
						ORDER BY pgm_acronym ASC';
		$resx = $this->db->query($pgm_name);
		$result = $resx->result_array();
		$pgm_data['pgm_result'] = $result;
		
		return $pgm_data;	
	}
	
	/* Function is used to fetch the curriculum id & name from curriculum table.
	* @param - 
	* @returns- a array of values of the curriculum details.
	*/	
	public function crclm_fill($pgm_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
		$resx = $this->db->query($crclm_name);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data;
		
	}
    
	/* Function is used to fetch the term id & name from crclm_terms table.
	* @param - curriculum id.
	* @returns- a array of values of the term details.
	*/
    public function term_fill($crclm_id) {
        $term_name = 'SELECT crclm_term_id, term_name 
						FROM crclm_terms 
						WHERE crclm_id = "'.$crclm_id.'" ';
        $result = $this->db->query($term_name);
        $data = $result->result_array();
        $term_data['res2'] = $data;

        return $term_data;
    }
	

	/* Function is used to fetch the course, course type, term,course designer & course reviewer details 
	* from course, crclm_terms, course_clo_owner, course_validator, users & curse_type tables.
	* @param - curriculum id & term id.
	* @returns- a array of values of all the course details.
	*/	
    public function course_list($crclm_id, $term_id) {
	
		$user = $this->ion_auth->user()->row()->id;
		
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
			$crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status,c.state_id, t.term_name, 
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
					FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
						course_type ct
					WHERE c.crclm_id = "'.$crclm_id.'" 
					AND c.crclm_term_id = "'.$term_id.'" 
					AND t.crclm_term_id = "'.$term_id.'"   
					AND u.crs_id = c.crs_id 
					AND r.crs_id = c.crs_id 
					AND s.id = u.clo_owner_id 
					AND ct.crs_type_id = c.crs_type_id ';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		} else {
			$crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
							tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
							c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status,c.state_id, t.term_name, 
							u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
						FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
							course_type ct
						WHERE c.crclm_id = "'.$crclm_id.'" 
						AND c.crclm_term_id = "'.$term_id.'" 
						AND t.crclm_term_id = "'.$term_id.'"   
						AND u.crs_id = c.crs_id 
						AND u.clo_owner_id = "' . $user . '" 
						AND r.crs_id = c.crs_id 
						AND s.id = u.clo_owner_id 
						AND ct.crs_type_id = c.crs_type_id ';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		}
		
        return $crs_list_return;
    }

/*
 * Description: Function to fetch the Program Type.
 * @Params : --
 * @Returns : Returns the Program Type ID.
 */
    public function fetch_pgm_type($prog_id){
        $pgm_query = 'SELECT pgmtype_id FROM program WHERE pgm_id = "'.$prog_id.'"';
        $pgm_data = $this->db->query($pgm_query);
        $pgm_result = $pgm_data->result_array();
        return $pgm_result;
	}
	
	/**Function to check whether framework for a course has been set or not**/
	public function check_framework($pgm_id) {
		$pgmtype_fetch_query = 'SELECT pgmtype_id
								FROM qp_framework
								WHERE pgmtype_id = "' . $pgm_id . '"';
		$pgmtype_fetch_result = $this->db->query($pgmtype_fetch_query);
		$pgmtype_data = $pgmtype_fetch_result->result_array();
		
		if(!empty($pgmtype_data)) {
			return '1';
		} else {
			return 0;
		}
	}
	
 public function generate_model_qp($pgm_id){
	$qp_query = 'SELECT  qpf.qpf_id, qpf.qpf_notes, qpf.qpf_gt_marks, qpf.qpf_max_marks, unit.qpf_unit_id, unit.qpf_unit_code, 
					unit.qpf_num_mquestions, unit.qpf_utotal_marks , qpf_mq.qpf_mq_id, qpf_mq.qpf_mq_code, qpf_mq.qpf_mtotal_marks			
					FROM qp_framework as qpf
					JOIN qpf_unit as unit ON  unit.qpf_id = qpf.qpf_id
					JOIN qpf_mquestion as qpf_mq ON  unit.qpf_unit_id = qpf_mq.qpf_unit_id
					WHERE qpf.pgmtype_id = "'.$pgm_id.'" ';
	$qpf_mq_data = $this->db->query($qp_query);
	$qpf_mq_result = $qpf_mq_data->result_array();
	
	$qp_unit_query = 'SELECT  qpf.qpf_id,qpf.qpf_notes, unit.qpf_unit_id, unit.qpf_unit_code, 
					unit.qpf_num_mquestions, unit.qpf_utotal_marks 
					FROM qp_framework as qpf
					JOIN qpf_unit as unit ON  unit.qpf_id = qpf.qpf_id
					WHERE qpf.pgmtype_id = "'.$pgm_id.'" ';
	$qp_unit_data = $this->db->query($qp_unit_query);
	$qp_unit_result = $qp_unit_data->result_array();
	
	$qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 
	ORDER BY qpf_config_orderby';
	$qp_entity_config_data = $this->db->query($qp_entity_config_query);
	$qp_entity_config_result = $qp_entity_config_data->result_array();
	
	// var_dump($qp_entity_config_result);
	// exit;
	 
	$qp_data['qpf_unit_details'] = $qp_unit_result;
	$qp_data['qpf_mq_details'] = 	$qpf_mq_result;
	$qp_data['qp_entity_config'] = 	$qp_entity_config_result;
	return $qp_data;
	
 }
 
 public function model_qp_course_data($crclm_id, $term_id, $crs_id){
		$course_co_data_query = 'SELECT clo_id, clo_code, clo_statement
						FROM clo 
						WHERE crclm_id = "'.$crclm_id.'" 
						AND term_id = "'.$term_id.'" 
						AND crs_id = "'.$crs_id.'"
						ORDER BY clo_code ASC';
		$course_co_data = $this->db->query($course_co_data_query);
		$course_co_data_result = $course_co_data->result_array();
		
		$topic_query = 'SELECT topic_id, topic_title 
						FROM topic 
						WHERE curriculum_id = "'.$crclm_id.'" 
						AND term_id = "'.$term_id.'" 
						AND course_id = "'.$crs_id.'"
						ORDER BY topic_title ASC';
		$topic_data = $this->db->query($topic_query);
		$topic_result = $topic_data->result_array();
		
		$bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs
								FROM bloom_level
								ORDER BY level ASC';
		$bloom_lvl_data = $this->db->query($bloom_lvl_query);
		$bloom_lvl_result = $bloom_lvl_data->result_array();
		
		$crs_title_query = 'SELECT crs_title, crs_code FROM course WHERE crs_id = "'.$crs_id.'"';
		$crs_title_data = $this->db->query($crs_title_query);
		$crs_title_result = $crs_title_data->result_array();
		
		$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "'.$crs_id.'" ORDER BY msr.pi_codes ASC';
		$pi_code_data = $this->db->query($pi_code_query);
		$pi_code_result = $pi_code_data->result_array();
		
		
		$course_data['co_data']		=	$course_co_data_result;
		$course_data['topic_list']	=	$topic_result;
		$course_data['level_list']	=	$bloom_lvl_result;
		$course_data['pi_code_list']=	$pi_code_result;
		$course_data['crs_title']	=	$crs_title_result;
		
		return $course_data;
		
 }
 
 public function model_qp_data_insertion($crclm_id, $term_id, $crs_id, $grand_total_marks,   $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $unit_total_marks, $main_question_val, $co_data , $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $question_name, $compolsury_qsn, $qpd_type){
	
		$logged_in_uid = $this->ion_auth->logged_in();
	// Insertion of data into QP_Defination table
		$qp_definition = array(
		'qpf_id' => $qpf_id,
		'qpd_type' => $qpd_type,
		'crclm_id' => $crclm_id,
		'crclm_term_id' => $term_id,
		'crs_id' => $crs_id,
		'qpd_title' => $qp_title,
		'qpd_timing' => $total_dration,
		'qpd_gt_marks' => $grand_total_marks,
		'qpd_max_marks' => $max_marks,
		'qpd_notes' => $qp_notes,
		'qpd_num_units' => $unit_counter,
		'created_by' => $logged_in_uid,
		'created_date' => date('y-m-d'),
		'modified_by' => $logged_in_uid,
		'modified_date' => date('y-m-d')
		);
		
		$this->db->insert('qp_definition', $qp_definition);
		$qpd_id = $this->db->insert_id();
		
		// Data insertion into QP_Unit_Defination table.

			$array_size = sizeof($main_question_array);
			
			$que_temp = 1;
			$que_size = 0;
			
		
			for($uid = 1; $uid <= $unit_counter; $uid++){
			 
			$qp_unit_definition = array(
				
				'qpd_id' => $qpd_id,
				'qp_unit_code' => $unit_code[$uid],
				'qp_total_unitquestion' => $total_num_questions[$uid],
				'qp_attempt_unitquestion' => $attemptable_questions[$uid],
				'qp_utotal_marks' => $unit_total_marks[$uid],
				'created_by' => $logged_in_uid,
				'created_date' => date('y-m-d'),
				'modified_by' => $logged_in_uid,
				'modified_date' =>date('y-m-d'),
			);
				$this->db->insert('qp_unit_definition', $qp_unit_definition);
				$qpd_unit_id = $this->db->insert_id();
				
			$unit_que_size = sizeof($main_question_array[$uid]);
			$que_size = $que_size + $unit_que_size;
			for($k = $que_temp; $k<=$que_size; $k++){
				$sub_q_size = sizeof($main_question_array[$uid][$k]);
				// var_dump($sub_q_size);
					for($t=0; $t<$sub_q_size; $t++){
					
					$question_name_val = explode('_',$question_name[$uid][$k][$t]);
					//$question = $question_name_val[0]
					
					$qp_mainquestion_definition = array(
						'qp_unitd_id' => $qpd_unit_id,
						'qp_mq_flag' => $compolsury_qsn[$uid][$k][$t],
						'qp_mq_code' => $question_name_val[2],
						'qp_subq_code' => $question_name[$uid][$k][$t],
						'qp_content' => $main_question_val[$uid][$k][$t],
						'qp_subq_marks' =>$mq_marks[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mainquestion_definition', $qp_mainquestion_definition);
					$question_id = $this->db->insert_id();
					
					$insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
					$insert_query.='("'.$question_id.'",11,"'.$co_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					 $insert_query.='("'.$question_id.'",10,"'.$topic_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",23,"'.$bloom_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",6,"'.$po_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",22,"'.$picode_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'")';
					
					
					
					
					$this->db->query($insert_query);
					
					/* $co_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 11,
						'actual_mapped_id' => $co_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $co_qp_mapping_definition);
					
					$topic_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 10,
						'actual_mapped_id' => $topic_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $topic_qp_mapping_definition);
					
					$bloom_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 23,
						'actual_mapped_id' => $bloom_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $bloom_qp_mapping_definition); */
					
					$image_name_array = sizeof($image_names[$uid][$k][$t]);
					//var_dump($image_names[$uid][$k][$t]);
					//var_dump($image_name_array);
					
						for($img = 0; $img < $image_name_array; $img++){
							
								$img_insert_array = array(
									'qp_mq_id' => $question_id,
									'qp_upl_filename' => $image_names[$uid][$k][$t][$img],
									'created_by' => $logged_in_uid,
									'created_date' => date('y-m-d'),
									'modified_by' => $logged_in_uid,
									'modified_date' => date('y-m-d')
								);
							$this->db->insert('qp_mq_upload',$img_insert_array);
							
						}
					
					}
			}
			$que_temp = $k;
			}
			
 }
 
 /*
	Function to insert CIA data
 */
 
  public function cia_qp_data_insertion($crclm_id, $term_id, $crs_id,  $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $main_question_val, $co_data , $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $question_name, $compolsury_qsn, $qpd_type, $ao_id){
	
		$logged_in_uid = $this->ion_auth->logged_in();
	// Insertion of data into QP_Defination table
		$qp_definition = array(
		'qpf_id' => $qpf_id,
		'qp_rollout' => 1,
		'qpd_type' => $qpd_type,
		'crclm_id' => $crclm_id,
		'crclm_term_id' => $term_id,
		'crs_id' => $crs_id,
		'qpd_title' => $qp_title,
		'qpd_timing' => $total_dration,
		'qpd_max_marks' => $max_marks,
		'qpd_notes' => $qp_notes,
		'qpd_num_units' => $unit_counter,
		'created_by' => $logged_in_uid,
		'created_date' => date('y-m-d'),
		'modified_by' => $logged_in_uid,
		'modified_date' => date('y-m-d')
		);
		
		$this->db->insert('qp_definition', $qp_definition);
		$qpd_id = $this->db->insert_id();
		
		// Data insertion into QP_Unit_Defination table.

			$array_size = sizeof($main_question_array);
			
			$que_temp = 1;
			$que_size = 0;
			for($uid = 1; $uid <= $unit_counter; $uid++){
			
			$qp_unit_definition = array(
				
				'qpd_id' => $qpd_id,
				'qp_unit_code' => $unit_code[$uid],
				'qp_total_unitquestion' => $total_num_questions[$uid],
				'qp_attempt_unitquestion' => $attemptable_questions[$uid],
				'created_by' => $logged_in_uid,
				'created_date' => date('y-m-d'),
				'modified_by' => $logged_in_uid,
				'modified_date' =>date('y-m-d'),
			);
				$this->db->insert('qp_unit_definition', $qp_unit_definition);
				$qpd_unit_id = $this->db->insert_id();
				
			$unit_que_size = sizeof($main_question_array[$uid]);
			$que_size = $que_size + $unit_que_size;
			for($k = 0; $k<$que_size; $k++){
				$sub_q_size = sizeof($main_question_array[$uid][$k]);
				// var_dump($sub_q_size);
					for($t=0; $t<$sub_q_size; $t++){
					
					$question_name_val = explode('_',$question_name[$uid][$k][$t]);
					//$question = $question_name_val[0]
					
					$qp_mainquestion_definition = array(
						'qp_unitd_id' => $qpd_unit_id,
						'qp_mq_flag' => $compolsury_qsn[$uid][$k][$t],
						'qp_mq_code' => $question_name_val[2],
						'qp_subq_code' => $question_name[$uid][$k][$t],
						'qp_content' => $main_question_val[$uid][$k][$t],
						'qp_subq_marks' =>$mq_marks[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mainquestion_definition', $qp_mainquestion_definition);
					$question_id = $this->db->insert_id();
					
					$insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
					$insert_query.='("'.$question_id.'",11,"'.$co_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					 $insert_query.='("'.$question_id.'",10,"'.$topic_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",23,"'.$bloom_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",6,"'.$po_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",22,"'.$picode_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'")';
					
					
					
					
					$this->db->query($insert_query);
					
					/* $co_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 11,
						'actual_mapped_id' => $co_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $co_qp_mapping_definition);
					
					$topic_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 10,
						'actual_mapped_id' => $topic_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $topic_qp_mapping_definition);
					
					$bloom_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 23,
						'actual_mapped_id' => $bloom_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $bloom_qp_mapping_definition); */
					
					$image_name_array = sizeof($image_names[$uid][$k][$t]);
					//var_dump($image_names[$uid][$k][$t]);
					//var_dump($image_name_array);
					
						for($img = 0; $img < $image_name_array; $img++){
							
								$img_insert_array = array(
									'qp_mq_id' => $question_id,
									'qp_upl_filename' => $image_names[$uid][$k][$t][$img],
									'created_by' => $logged_in_uid,
									'created_date' => date('y-m-d'),
									'modified_by' => $logged_in_uid,
									'modified_date' => date('y-m-d')
								);
							$this->db->insert('qp_mq_upload',$img_insert_array);
							
						}
					
					}
			}
			$que_temp = $k;
			}
			
			// assessment occasion table updation with maximum marks and qpd_id.
			$update_query = 'UPDATE assessment_occasions 
							SET qpd_id = "'.$qpd_id.'"
							WHERE ao_id = "'.$ao_id.'"';
			$ao_data = $this->db->query($update_query);
			
			$cia_data['qpd_id'] = $qpd_id;
			return $cia_data;
 }
 
  public function model_qp_data_update_insertion($crclm_id, $term_id, $crs_id, $grand_total_marks,  $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $unit_total_marks, $main_question_val, $co_data , $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $qpd_id, $question_name, $compolsury_qsn, $qpd_type){
	
	
	
	$logged_in_uid = $this->ion_auth->logged_in();
	$this->db->trans_start();
	
	$qp_def_delete_query = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" AND qpd_type = "'.$qpd_type.'"';
	$qpd_id_res = $this->db->query($qp_def_delete_query);
	
	// Insertion of data into QP_Defination table
		$qp_definition = array(
		'qpf_id' => $qpf_id,
		'qpd_type' => $qpd_type,
		'crclm_id' => $crclm_id,
		'crclm_term_id' => $term_id,
		'crs_id' => $crs_id,
		'qpd_title' => $qp_title,
		'qpd_timing' => $total_dration,
		'qpd_gt_marks' => $grand_total_marks,
		'qpd_max_marks' => $max_marks,
		'qpd_notes' => $qp_notes,
		'qpd_num_units' => $unit_counter,
		'created_by' => $logged_in_uid,
		'created_date' => date('y-m-d'),
		'modified_by' => $logged_in_uid,
		'modified_date' => date('y-m-d')
		);
		
		$this->db->insert('qp_definition', $qp_definition);
		$qpd_id = $this->db->insert_id();
		
		// Data insertion into QP_Unit_Defination table.

			$array_size = sizeof($main_question_array);
			
			$que_temp = 1;
			$que_size = 0;
			for($uid = 1; $uid <= $unit_counter; $uid++){
			
			$qp_unit_definition = array(
				
				'qpd_id' => $qpd_id,
				'qp_unit_code' => $unit_code[$uid],
				'qp_total_unitquestion' => $total_num_questions[$uid],
				'qp_attempt_unitquestion' => $attemptable_questions[$uid],
				'qp_utotal_marks' => $unit_total_marks[$uid],
				'created_by' => $logged_in_uid,
				'created_date' => date('y-m-d'),
				'modified_by' => $logged_in_uid,
				'modified_date' =>date('y-m-d'),
			);
				$this->db->insert('qp_unit_definition', $qp_unit_definition);
				$qpd_unit_id = $this->db->insert_id();
				
			$unit_que_size = sizeof($main_question_array[$uid]);
			$que_size = $que_size + $unit_que_size;
			for($k = $que_temp; $k<=$que_size; $k++){
				$sub_q_size = sizeof($main_question_array[$uid][$k]);
				// var_dump($sub_q_size);
					for($t=0; $t<$sub_q_size; $t++){
					
					$question_name_val = explode('_',$question_name[$uid][$k][$t]);
					
					
					
					$qp_mainquestion_definition = array(
						'qp_unitd_id' => $qpd_unit_id,
						'qp_mq_flag' => $compolsury_qsn[$uid][$k][$t],
						'qp_mq_code' => $question_name_val[2],
						'qp_subq_code' => $question_name[$uid][$k][$t],
						'qp_content' => $main_question_val[$uid][$k][$t],
						'qp_subq_marks' =>$mq_marks[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mainquestion_definition', $qp_mainquestion_definition);
					$question_id = $this->db->insert_id();
					
					$insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
					$insert_query.='("'.$question_id.'",11,"'.$co_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					 $insert_query.='("'.$question_id.'",10,"'.$topic_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",23,"'.$bloom_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",6,"'.$po_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",22,"'.$picode_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'")';
					
					
					$this->db->query($insert_query);
					
					/* $co_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 11,
						'actual_mapped_id' => $co_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $co_qp_mapping_definition);
					
					$topic_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 10,
						'actual_mapped_id' => $topic_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $topic_qp_mapping_definition);
					
					$bloom_qp_mapping_definition = array(
						'qp_mq_id' => $question_id,
						'entity_id' => 23,
						'actual_mapped_id' => $bloom_data[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mapping_definition', $bloom_qp_mapping_definition); */
					
					$image_name_array = sizeof($image_names[$uid][$k][$t]);
					//var_dump($image_names[$uid][$k][$t]);
					//var_dump($image_name_array);
					
						for($img = 0; $img < $image_name_array; $img++){
							
								$img_insert_array = array(
									'qp_mq_id' => $question_id,
									'qp_upl_filename' => $image_names[$uid][$k][$t][$img],
									'created_by' => $logged_in_uid,
									'created_date' => date('y-m-d'),
									'modified_by' => $logged_in_uid,
									'modified_date' => date('y-m-d')
								);
							$this->db->insert('qp_mq_upload',$img_insert_array);
							
						}
					
					}
			}
			$que_temp = $k;
			}
			$this->db->trans_complete();
			
			$qp_data['qpd_id'] = $qpd_id;
			return $qp_data;
 }
 
 /*
 Function to Update the CIA data
 */
 
 public function cia_qp_data_update_insertion($crclm_id, $term_id, $crs_id,  $main_question_array, $qp_title, $qp_notes, $max_marks, $total_dration, $unit_counter, $total_num_questions, $attemptable_questions, $unit_code, $main_question_val, $co_data , $po_data, $topic_data, $bloom_data, $picode_data, $mq_marks, $image_names, $qpf_id, $qpd_id, $question_name, $compolsury_qsn, $qpd_type, $ao_id){
	
	
	
	$logged_in_uid = $this->ion_auth->logged_in();
	$this->db->trans_start();
	
	$qp_def_delete_query = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" AND qpd_type = "'.$qpd_type.'"';
	$qpd_id_res = $this->db->query($qp_def_delete_query);
	
	// Insertion of data into QP_Defination table
		$qp_definition = array(
		'qpf_id' => $qpf_id,
		'qpd_type' => $qpd_type,
		'qp_rollout' => 1,
		'crclm_id' => $crclm_id,
		'crclm_term_id' => $term_id,
		'crs_id' => $crs_id,
		'qpd_title' => $qp_title,
		'qpd_timing' => $total_dration,
		'qpd_max_marks' => $max_marks,
		'qpd_notes' => $qp_notes,
		'qpd_num_units' => $unit_counter,
		'created_by' => $logged_in_uid,
		'created_date' => date('y-m-d'),
		'modified_by' => $logged_in_uid,
		'modified_date' => date('y-m-d')
		);
		
		$this->db->insert('qp_definition', $qp_definition);
		$qpd_id = $this->db->insert_id();
		
		// Data insertion into QP_Unit_Defination table.

			$array_size = sizeof($main_question_array);
			
			$que_temp = 1;
			$que_size = 0;
			for($uid = 1; $uid <= $unit_counter; $uid++){
			
			$qp_unit_definition = array(
				
				'qpd_id' => $qpd_id,
				'qp_unit_code' => $unit_code[$uid],
				'qp_total_unitquestion' => $total_num_questions[$uid],
				'qp_attempt_unitquestion' => $attemptable_questions[$uid],
				'created_by' => $logged_in_uid,
				'created_date' => date('y-m-d'),
				'modified_by' => $logged_in_uid,
				'modified_date' =>date('y-m-d'),
			);
				$this->db->insert('qp_unit_definition', $qp_unit_definition);
				$qpd_unit_id = $this->db->insert_id();
				
			$unit_que_size = sizeof($main_question_array[$uid]);
			$que_size = $que_size + $unit_que_size;
			for($k = 0; $k<$que_size; $k++){
				$sub_q_size = sizeof($main_question_array[$uid][$k]);
				// var_dump($sub_q_size);
					for($t=0; $t<$sub_q_size; $t++){
					
					$question_name_val = explode('_',$question_name[$uid][$k][$t]);
					
					
					
					$qp_mainquestion_definition = array(
						'qp_unitd_id' => $qpd_unit_id,
						'qp_mq_flag' => $compolsury_qsn[$uid][$k][$t],
						'qp_mq_code' => $question_name_val[2],
						'qp_subq_code' => $question_name[$uid][$k][$t],
						'qp_content' => $main_question_val[$uid][$k][$t],
						'qp_subq_marks' =>$mq_marks[$uid][$k][$t],
						'created_by' => $logged_in_uid,
						'created_date' => date('y-m-d'),
						'modified_by' => $logged_in_uid,
						'modified_date' => date('y-m-d')
					);
					$this->db->insert('qp_mainquestion_definition', $qp_mainquestion_definition);
					$question_id = $this->db->insert_id();
					
					$insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id,entity_id,actual_mapped_id,created_by,created_date,modified_by,modified_date) VALUES';
					$insert_query.='("'.$question_id.'",11,"'.$co_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					 $insert_query.='("'.$question_id.'",10,"'.$topic_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",23,"'.$bloom_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",6,"'.$po_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'"),';
					
					$insert_query.='("'.$question_id.'",22,"'.$picode_data[$uid][$k][$t].'","'.$logged_in_uid.'","'.date('y-m-d').'","'.$logged_in_uid.'","'.date('y-m-d').'")';
					
					
					$this->db->query($insert_query);
					
					
					$image_name_array = sizeof($image_names[$uid][$k][$t]);
					
					
						for($img = 0; $img < $image_name_array; $img++){
							
								$img_insert_array = array(
									'qp_mq_id' => $question_id,
									'qp_upl_filename' => $image_names[$uid][$k][$t][$img],
									'created_by' => $logged_in_uid,
									'created_date' => date('y-m-d'),
									'modified_by' => $logged_in_uid,
									'modified_date' => date('y-m-d')
								);
							$this->db->insert('qp_mq_upload',$img_insert_array);
							
						}
					
					}
			}
			$que_temp = $k;
			}
			// assessment occasion table updation with maximum marks and qpd_id.
			$update_query = 'UPDATE assessment_occasions 
							SET qpd_id = "'.$qpd_id.'", max_marks = "'.$max_marks.'" 
							WHERE ao_id = "'.$ao_id.'"';
			$ao_data = $this->db->query($update_query);
			
			$this->db->trans_complete();
			
		$cia_qp_data['qpd_id'] = $qpd_id;
		return $cia_qp_data;
 }
 
 public function model_qp_existance($crs_id, $qp_type){
 // echo $qp_type;
 // exit;
 if($qp_type==4){
	$crs_qp_count = 'SELECT qpd_id FROM qp_definition WHERE crs_id = "'.$crs_id.'" AND qpd_type = "'.$qp_type.'"';
	$crs_qp_count_data = $this->db->query($crs_qp_count);
	$crs_qp_count_res = $crs_qp_count_data->num_rows();
	return $crs_qp_count_res;
	}else if($qp_type==5){
		$crs_qp_count = 'SELECT qpd_id FROM qp_definition WHERE crs_id = "'.$crs_id.'" AND qpd_type = 4';
		$crs_qp_count_data = $this->db->query($crs_qp_count);
		$crs_qp_count_res = $crs_qp_count_data->num_rows();
		return $crs_qp_count_res;
	}else if($qp_type==3){
		$crs_qp_count = 'SELECT qpd_id FROM qp_definition WHERE crs_id = "'.$crs_id.'" AND qpd_type = "'.$qp_type.'"';
		$crs_qp_count_data = $this->db->query($crs_qp_count);
		$crs_qp_count_res = $crs_qp_count_data->num_rows();
		return $crs_qp_count_res;
	}
 }
 
 /*
	Function to check the CIA qp existence.
	@param : corse id, qp type and qp definition id.
	@return : Qp existence number.
 */
 public function cia_qp_existance($crs_id, $qp_type, $qpd_id){

	$crs_qp_count = 'SELECT qpd_id FROM qp_definition WHERE crs_id = "'.$crs_id.'" AND qpd_type = "'.$qp_type.'" AND qpd_id = "'.$qpd_id.'"';
	$crs_qp_count_data = $this->db->query($crs_qp_count);
	$crs_qp_count_res = $crs_qp_count_data->num_rows();
	return $crs_qp_count_res;
 }
 
	public function isModelQPDefined($crclm_id, $term_id, $crs_id, $qp_type){
		$model_qp_meta_data_query = 'SELECT qpd.qpd_id
		FROM qp_definition AS qpd
		JOIN course AS crs ON qpd.crs_id = crs.crs_id
		WHERE qpd.crclm_id = "'.$crclm_id.'"
		AND qpd.crclm_term_id = "'.$term_id.'"
		AND qpd.crs_id = "'.$crs_id.'" 
		AND qpd.qpd_type = "'.$qp_type.'"'; 
		$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
		return $model_qp_meta_data->result_array();	
	}
	
	
	public function model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type){
	
	$model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
		FROM qp_definition AS qpd
	JOIN course AS crs ON qpd.crs_id = crs.crs_id
	WHERE qpd.crclm_id = "'.$crclm_id.'"
	AND qpd.crclm_term_id = "'.$term_id.'"
	AND qpd.crs_id = "'.$crs_id.'" 
	AND qpd.qpd_type = "'.$qp_type.'"'; 
	
	$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
	$model_qp_meta_data_res = $model_qp_meta_data->result_array();
	if($model_qp_meta_data_res) {
		$qpd_id = $model_qp_meta_data_res[0]['qpd_id'];

		$model_qp_data['qp_meta_data'] = $model_qp_meta_data_res;
		
		$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
		FROM qp_unit_definition AS unit
		JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
		WHERE unit.qpd_id = "'.$qpd_id.'"';
		$qpd_unit_data = $this->db->query($qpd_unit_data_query);
		$qpd_unit_data_res = $qpd_unit_data->result_array();
		
		$model_qp_data['main_qp_data'] = $qpd_unit_data_res;
		
		$qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, unit.qp_utotal_marks
		FROM qp_unit_definition as unit 
		WHERE unit.qpd_id = "'.$qpd_id.'"';
		$qpd_unit = $this->db->query($qpd_unit_query);
		$qpd_unit_res = $qpd_unit->result_array();
		
		$model_qp_data['unit_def_data'] = $qpd_unit_res;
		
		$course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
							FROM clo 
							WHERE crclm_id = "'.$crclm_id.'" 
							AND term_id = "'.$term_id.'" 
							AND crs_id = "'.$crs_id.'"';
			$course_co_data = $this->db->query($course_co_data_query);
			$course_co_data_result = $course_co_data->result_array();
			// var_dump($course_co_data_result);
			// exit;
			$model_qp_data['co_data'] = $course_co_data_result;
			
			$topic_query = 'SELECT topic_id, topic_title 
							FROM topic 
							WHERE curriculum_id = "'.$crclm_id.'" 
							AND term_id = "'.$term_id.'" 
							AND course_id = "'.$crs_id.'"';
			$topic_data = $this->db->query($topic_query);
			$topic_result = $topic_data->result_array();
			
			$model_qp_data['topic_data'] = $topic_result;
			
			$bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
							FROM bloom_level';
			$bloom_lvl_data = $this->db->query($bloom_lvl_query);
			$bloom_lvl_result = $bloom_lvl_data->result_array();
			$model_qp_data['bloom_data'] = $bloom_lvl_result;
			
			$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ORDER BY msr.pi_codes ASC';
			$pi_code_data = $this->db->query($pi_code_query);
			$pi_code_result = $pi_code_data->result_array();
			$model_qp_data['pi_code_list'] = $pi_code_result;
			$qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 
		ORDER BY qpf_config_orderby';
		$qp_entity_config_data = $this->db->query($qp_entity_config_query);
		$qp_entity_config_result = $qp_entity_config_data->result_array();
		
		$model_qp_data['entity_list'] = $qp_entity_config_result;
		return $model_qp_data;
	}
	else {
		return 0;
	}		
	
 }
 
 /*
	Function to create CIA QP edit view.
	@param : course id, qp type and qpd id.
	@return: CIA qp edit details.
 */
 
  public function cia_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){
	
	$model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, crs.crs_title, crs.crs_code
		FROM qp_definition AS qpd
	JOIN course AS crs ON qpd.crs_id = crs.crs_id
	WHERE qpd.crclm_id = "'.$crclm_id.'"
	AND qpd.crclm_term_id = "'.$term_id.'"
	AND qpd.crs_id = "'.$crs_id.'" 
	AND qpd.qpd_type = "'.$qp_type.'"
	AND qpd.qpd_id = "'.$qpd_id.'"'; 
	
	$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
	$model_qp_meta_data_res = $model_qp_meta_data->result_array();
	if($model_qp_meta_data_res) {
		$qpd_id = $model_qp_meta_data_res[0]['qpd_id'];

		$model_qp_data['qp_meta_data'] = $model_qp_meta_data_res;
		
		$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
		FROM qp_unit_definition AS unit
		JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
		WHERE unit.qpd_id = "'.$qpd_id.'"';
		$qpd_unit_data = $this->db->query($qpd_unit_data_query);
		$qpd_unit_data_res = $qpd_unit_data->result_array();
		
		$model_qp_data['main_qp_data'] = $qpd_unit_data_res;
		
		$qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion
		FROM qp_unit_definition as unit 
		WHERE unit.qpd_id = "'.$qpd_id.'"';
		$qpd_unit = $this->db->query($qpd_unit_query);
		$qpd_unit_res = $qpd_unit->result_array();
		
		$model_qp_data['unit_def_data'] = $qpd_unit_res;
		
		$course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
							FROM clo 
							WHERE crclm_id = "'.$crclm_id.'" 
							AND term_id = "'.$term_id.'" 
							AND crs_id = "'.$crs_id.'"';
			$course_co_data = $this->db->query($course_co_data_query);
			$course_co_data_result = $course_co_data->result_array();
			// var_dump($course_co_data_result);
			// exit;
			$model_qp_data['co_data'] = $course_co_data_result;
			
			$topic_query = 'SELECT topic_id, topic_title 
							FROM topic 
							WHERE curriculum_id = "'.$crclm_id.'" 
							AND term_id = "'.$term_id.'" 
							AND course_id = "'.$crs_id.'"';
			$topic_data = $this->db->query($topic_query);
			$topic_result = $topic_data->result_array();
			
			$model_qp_data['topic_data'] = $topic_result;
			
			$bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
							FROM bloom_level';
			$bloom_lvl_data = $this->db->query($bloom_lvl_query);
			$bloom_lvl_result = $bloom_lvl_data->result_array();
			
			$model_qp_data['bloom_data'] = $bloom_lvl_result;
			
			$qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 
		ORDER BY qpf_config_orderby';
		$qp_entity_config_data = $this->db->query($qp_entity_config_query);
		$qp_entity_config_result = $qp_entity_config_data->result_array();
		
		$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ORDER BY msr.pi_codes ASC';
			$pi_code_data = $this->db->query($pi_code_query);
			$pi_code_result = $pi_code_data->result_array();
			$model_qp_data['pi_code_list'] = $pi_code_result;
		
		$model_qp_data['entity_list'] = $qp_entity_config_result;
		return $model_qp_data;
	}
	else {
		return 0;
	}		
	
 }
 
	public function model_qp_edit_details_tee($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){	
		$model_qp_meta_data_query = 'SELECT qpd.qpf_id, qpd.qpd_title, qpd.qpd_timing, qpd.qpd_gt_marks,	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, crs.crs_title,crs.crs_code
			FROM qp_definition AS qpd
		JOIN course AS crs ON qpd.crs_id = crs.crs_id
		WHERE qpd.crclm_id = "'.$crclm_id.'"
		AND qpd.crclm_term_id = "'.$term_id.'"
		AND qpd.crs_id = "'.$crs_id.'" 
		AND qpd.qpd_type = "'.$qp_type.'" 
		AND qpd.qpd_id = "'.$qpd_id.'"'; 
	
		$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
		$model_qp_meta_data_res = $model_qp_meta_data->result_array();
		if($model_qp_meta_data_res) {
			$model_qp_data['qp_meta_data'] = $model_qp_meta_data_res;		
			$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
			FROM qp_unit_definition AS unit
			JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
			WHERE unit.qpd_id = "'.$qpd_id.'"';
			$qpd_unit_data = $this->db->query($qpd_unit_data_query);
			$qpd_unit_data_res = $qpd_unit_data->result_array();
			
			$model_qp_data['main_qp_data'] = $qpd_unit_data_res;
			
		
			$qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion
			FROM qp_unit_definition as unit 
			WHERE unit.qpd_id = "'.$qpd_id.'"';
			$qpd_unit = $this->db->query($qpd_unit_query);
			$qpd_unit_res = $qpd_unit->result_array();
			
			$model_qp_data['unit_def_data'] = $qpd_unit_res;
			
			$course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
								FROM clo 
								WHERE crclm_id = "'.$crclm_id.'" 
								AND term_id = "'.$term_id.'" 
								AND crs_id = "'.$crs_id.'"';
			$course_co_data = $this->db->query($course_co_data_query);
			$course_co_data_result = $course_co_data->result_array();
			// var_dump($course_co_data_result);
			// exit;
			$model_qp_data['co_data'] = $course_co_data_result;
			
			$topic_query = 'SELECT topic_id, topic_title 
							FROM topic 
							WHERE curriculum_id = "'.$crclm_id.'" 
							AND term_id = "'.$term_id.'" 
							AND course_id = "'.$crs_id.'"';
			$topic_data = $this->db->query($topic_query);
			$topic_result = $topic_data->result_array();
			
			$model_qp_data['topic_data'] = $topic_result;
			
			$bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
							FROM bloom_level';
			$bloom_lvl_data = $this->db->query($bloom_lvl_query);
			$bloom_lvl_result = $bloom_lvl_data->result_array();
			
			$model_qp_data['bloom_data'] = $bloom_lvl_result;
			
			$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ORDER BY msr.pi_codes ASC';
			$pi_code_data = $this->db->query($pi_code_query);
			$pi_code_result = $pi_code_data->result_array();
			$model_qp_data['pi_code_list'] = $pi_code_result;
			
			$qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 
										ORDER BY qpf_config_orderby';
			$qp_entity_config_data = $this->db->query($qp_entity_config_query);
			$qp_entity_config_result = $qp_entity_config_data->result_array();
			
			$model_qp_data['entity_list'] = $qp_entity_config_result;
			return $model_qp_data;
	} else {
		return 0;
	}
 }
 
 public function tee_individual_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){
 
		
	$model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 	qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, crs.crs_title, crs.crs_code
		FROM qp_definition AS qpd
	JOIN course AS crs ON qpd.crs_id = crs.crs_id
	WHERE qpd.crclm_id = "'.$crclm_id.'"
	AND qpd.crclm_term_id = "'.$term_id.'"
	AND qpd.crs_id = "'.$crs_id.'" 
	AND qpd.qpd_type = "'.$qp_type.'"
	AND qpd.qpd_id = "'.$qpd_id.'" '; 
	
	$model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
	$model_qp_meta_data_res = $model_qp_meta_data->result_array();
	//$qpd_id = $model_qp_meta_data_res[0]['qpd_id'];
	
	$model_qp_data['qp_meta_data'] = $model_qp_meta_data_res;
	
	$qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
	FROM qp_unit_definition AS unit
	JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
	WHERE unit.qpd_id = "'.$qpd_id.'"';
	$qpd_unit_data = $this->db->query($qpd_unit_data_query);
	$qpd_unit_data_res = $qpd_unit_data->result_array();
	
	$model_qp_data['main_qp_data'] = $qpd_unit_data_res;
	
	
/* 	foreach($qpd_unit_data_res as $mq_data){
		$mapping_data_query = 'SELECT qp_map_id, qp_mq_id, entity_id, actual_mapped_id FROM qp_mapping_definition WHERE  qp_mq_id = "'.$mq_data['qp_mq_id'].'"';
		$mapping_data = $this->db->query($mapping_data_query);
		$mapping_data_res = $mapping_data->result_array();
		$qp_mapping_data[] = $mapping_data_res;
	}
	var_dump($qp_mapping_data); */
	
	$qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, unit.qp_utotal_marks
	FROM qp_unit_definition as unit 
	WHERE unit.qpd_id = "'.$qpd_id.'"';
	$qpd_unit = $this->db->query($qpd_unit_query);
	$qpd_unit_res = $qpd_unit->result_array();
	
	$model_qp_data['unit_def_data'] = $qpd_unit_res;
	
	$course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
						FROM clo 
						WHERE crclm_id = "'.$crclm_id.'" 
						AND term_id = "'.$term_id.'" 
						AND crs_id = "'.$crs_id.'"';
		$course_co_data = $this->db->query($course_co_data_query);
		$course_co_data_result = $course_co_data->result_array();
		
		$model_qp_data['co_data'] = $course_co_data_result;
		
		$topic_query = 'SELECT topic_id, topic_title 
						FROM topic 
						WHERE curriculum_id = "'.$crclm_id.'" 
						AND term_id = "'.$term_id.'" 
						AND course_id = "'.$crs_id.'"';
		$topic_data = $this->db->query($topic_query);
		$topic_result = $topic_data->result_array();
		
		$model_qp_data['topic_data'] = $topic_result;
		
		$bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
						FROM bloom_level';
		$bloom_lvl_data = $this->db->query($bloom_lvl_query);
		$bloom_lvl_result = $bloom_lvl_data->result_array();
		
		$model_qp_data['bloom_data'] = $bloom_lvl_result;
		
		$qp_entity_config_query = 'SELECT entity_id FROM entity WHERE qpf_config = 1 
	ORDER BY qpf_config_orderby';
	$qp_entity_config_data = $this->db->query($qp_entity_config_query);
	$qp_entity_config_result = $qp_entity_config_data->result_array();
	
	$model_qp_data['entity_list'] = $qp_entity_config_result;
	
	$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.crs_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" ORDER BY msr.pi_codes ASC';
			$pi_code_data = $this->db->query($pi_code_query);
			$pi_code_result = $pi_code_data->result_array();
			$model_qp_data['pi_code_list'] = $pi_code_result;
			
		return $model_qp_data;
	
 }
 
  public function po_list($co_id, $crclm_id, $term_id, $crs_id){
 
	$mapped_po_query = 'SELECT copo.po_id, p.po_id, p.po_reference,p.po_statement FROM clo_po_map as copo 
	JOIN po as p ON copo.po_id = p.po_id
	WHERE copo.clo_id = "'.$co_id.'"
	AND copo.crclm_id = "'.$crclm_id.'"
	AND copo.crs_id = "'.$crs_id.'"
	Group By copo.po_id ASC';
	$mapped_po_data = $this->db->query($mapped_po_query);
	$mapped_po_res = $mapped_po_data->result_array();
	$model_qp_data['po_list'] = $mapped_po_res;
	
	$pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							WHERE co_po.clo_id = "'.$co_id.'" AND crclm_id = "'.$crclm_id.'" ORDER BY msr.pi_codes ASC';
			$pi_code_data = $this->db->query($pi_code_query);
			$pi_code_result = $pi_code_data->result_array();
			$model_qp_data['pi_code_list'] = $pi_code_result;
			
	return $model_qp_data;
	
  
  }
  
  /*
	Function to delete individual TEE qp data.
	@param : curriculum id, term id, course id and QP definition id to delete particular QP.
	@return : Success Message.
  */
  public function tee_individual_qp_delete($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){
		$qp_def_delete_query = 'DELETE FROM qp_definition WHERE qpd_id = "'.$qpd_id.'" AND qpd_type = "'.$qp_type.'" AND crclm_id = "'.$crclm_id.'" AND crclm_term_id ="'.$term_id.'" AND crs_id = "'.$crs_id.'"';
		$qpd_id_res = $this->db->query($qp_def_delete_query);
		return true;
  } 
  
  /*
	Function to delete individual TEE qp data.
	@param : curriculum id, term id, course id and QP definition id to delete particular QP.
	@return : Success Message.
  */
  public function tee_qp_rollout($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){
  
		$qp_rollout_query = 'UPDATE qp_definition SET qp_rollout = 0 WHERE qpd_type = "'.$qp_type.'" AND crclm_id = "'.$crclm_id.'" AND crclm_term_id ="'.$term_id.'" AND crs_id = "'.$crs_id.'"';
		$qp_rollout_res = $this->db->query($qp_rollout_query);
  
		$qp_roll_out_update_query = 'UPDATE qp_definition SET qp_rollout = 1 WHERE qpd_id = "'.$qpd_id.'" AND qpd_type = "'.$qp_type.'" AND crclm_id = "'.$crclm_id.'" AND crclm_term_id ="'.$term_id.'" AND crs_id = "'.$crs_id.'"';
		$qp_roll_out_update_res = $this->db->query($qp_roll_out_update_query);
		return true;
  }
  /*Function to fetch TEE qp data
  */
  public function fetch_tee_qp_data($crclm_id, $term_id, $crs_id, $qp_type){
		
		$fetch_qp_data_query = 'SELECT qp.qpd_id, qp.qpf_id,qp.qp_rollout, qp.qpd_type, qp.qpd_title, qp.created_by, qp.created_date, qp.modified_by, qp.modified_date, u.title as utitle, u.first_name as uname, u.last_name as ulastname , mu.title as mtitle, mu.first_name as mfirst, mu.last_name as mlast FROM qp_definition as qp
		JOIN users as u on u.id = qp.created_by 
		JOIN users as mu on mu.id = qp.modified_by
		WHERE qp.crclm_id = "'.$crclm_id.'"
		AND qp.crclm_term_id = "'.$term_id.'"
		AND qp.crs_id = "'.$crs_id.'"
		AND qp.qpd_type = "'.$qp_type.'" ';
		$fetch_qp_data = $this->db->query($fetch_qp_data_query);
		$fetch_qp_data_res = $fetch_qp_data->result_array();
		return $fetch_qp_data_res;
  }
  
  	
	/* Function - To get data from procedure call for BloomsLevelPlannedCoverageDistribution
	* @param - course id and question paper id
	* returns - 
	*/
	public function getBloomsLevelPlannedCoverageDistribution($crs,$qid) {
		$r = $this->db->query("call getBloomsLevelPlannedCoverageDistribution(".$crs.",".$qid.")");
			return $r->result_array();
	}//End of Function getBloomsLevelPlannedCoverageDistribution
	
	/* Function - To get data from procedure call for BloomsLevelMarksDistribution
	* @param - course id and question paper id
	* returns - 
	*/
	public function getBloomsLevelMarksDistribution($crs,$qid) {
		$r = $this->db->query("call getBloomsLevelMarksDistribution(".$crs.",".$qid.")");
			return $r->result_array();
	}//End of function getBloomsLevelMarksDistribution
	
	/* Function - To get data from procedure call for BloomsLevelMarksDistribution_custom
	* @param - course id and question paper id
	* returns - 
	*/
	public function getBloomsLevelMarksDistribution_custom($crs,$qid) {
		$r = $this->db->query("call getBloomsLevelMarksDistribution_custom(".$crs.",".$qid.")");
			return $r->result_array();
	}//End of function getBloomsLevelMarksDistribution
	
	/* Function - To get data from procedure call for COLevelMarksDistribution
	* @param - course id and question paper id
	* returns - 
	*/
	public function getCOLevelMarksDistribution($crs,$qid) {
		$r = $this->db->query("call getCOLevelMarksDistribution(".$crs.",".$qid.")");
			return $r->result_array();
	}//End of function getCOLevelMarksDistribution
	
	/* Function - To get data from procedure call for getTopicCoverageDistribution
	* @param - course id and question paper id
	* returns - 
	*/
	public function getTopicCoverageDistribution($crs,$qid) {
		$r = $this->db->query("call getTopicCoverageDistribution(".$crs.",".$qid.")");
			return $r->result_array();
	}//End of function getTopicCoverageDistribution
	
	/*Funtion - To get QP data of a course
	* @param - course id 
	* returns - 
	*/
	public function getModelQP_qpid($crs_id){
		$model_qp_data = $this->db->query('SELECT q.qpd_id FROM qp_definition q where q.crs_id ='.$crs_id.' and q.qpd_type=4');
		return $model_qp_data->result_array();
	
	}//End of funtion getModelQP_qpid
	
	/*Funtion - To get QP data of a course
	* @param - course id 
	* returns - 
	*/
	public function getTEEQP_qpid($crs_id){
		$tee_qp_data = $this->db->query('SELECT q.qpd_id FROM qp_definition q where q.crs_id ='.$crs_id.' and q.qpd_type=5');
		return $tee_qp_data->result_array();
	
	}//End of funtion getTEEQP_qpid

	public function tee_qp_rollout_status($crclm_id, $term_id, $crs_id, $qp_type, $qpd_id){
	
		$qp_status_query = 'SELECT qp_rollout FROM qp_definition WHERE crclm_id = "'.$crclm_id.'" AND crclm_term_id = "'.$term_id.'" AND crs_id = "'.$crs_id.'" AND qpd_type = "'.$qp_type.'" and qpd_id = "'.$qpd_id.'"';
		$qp_query_data = $this->db->query($qp_status_query);
		
		$qp_query_res = $qp_query_data->result_array();
		
		return $qp_query_res;
	}
	
	/** Function to check if question papaer is defined for TEE
	**@param - crs_id
	**@returns - TRUE if defined
	**/
	public function oneQPDefined($crs_id_val){
		$isQPDefined = $this->db->query('SELECT count(*) as "defined"
						FROM qp_definition q
						WHERE q.qpd_type = 5
						AND q.crs_id = '.$crs_id_val.'');	
		$isQPDefined_res = $isQPDefined->result();
		return $isQPDefined_res;
	}
	//End of function oneQPDefined
 
 
}
?>