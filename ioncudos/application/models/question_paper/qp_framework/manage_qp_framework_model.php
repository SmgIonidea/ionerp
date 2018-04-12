<?php
/**
* Description	:	Model(Database) Logic for Model QP Framework Module(List, Edit & Delete).
* Created		:	28-07-2014. 
* Modification History:
* Date				Modified By				Description
* 30-07-2014		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage_qp_framework_model extends CI_Model {

	/* Function is used to fetch the qp framework details from qp framework table.
	* @param - .
	* @returns- a array of values of qp framework details.
	*/	
    public function qp_framework_list() {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$loggedin_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
		
		if($this->ion_auth->is_admin()) {
			$query = 'SELECT qpf_id, pgmtype_id, qpf_title, qpf_num_units, qpf_gt_marks, qpf_max_marks, qpf_notes, qpf_instructions	
					  FROM qp_framework
					  ORDER BY qpf_title ASC';
		} else {
			$query = 'SELECT q.qpf_id, q.pgmtype_id, q.qpf_title, q.qpf_num_units, q.qpf_gt_marks, q.qpf_max_marks, 
							q.qpf_notes, q.qpf_instructions	
					  FROM qp_framework as q, department AS d, program AS p
					  WHERE q.pgmtype_id = p.pgm_id
					  AND p.dept_id = d.dept_id
					  AND d.dept_id = "'.$loggedin_user_dept_id.'" 
					  ORDER BY q.qpf_title ASC';
		}
	
		$row = $this->db->query($query);
        $row = $row->result_array();
        $result['rows'] = $row;
        return $result;
    }// End of function qp_framework_list.
    
	/* Function is used to delete a qp framework from qp framework table.
	* @param - qpf id.
	* @returns- a boolean value.
	*/
	public function qp_delete($qpf_id) {

        $query = ' DELETE FROM qp_framework WHERE qpf_id = "'.$qpf_id.'" ';
        $result = $this->db->query($query);
        return $result;
    }// End of function po_delete.
	
	
	/*
	Function to fetch the program type from the program type master table to generate program type drop down.
	Param @: ----
	*/
	public function program_type(){
	if($this->ion_auth->is_admin()){
		$program_type_query = 'SELECT p.pgm_id AS pgmtype_id, trim(p.pgm_acronym) AS pgmtype_name
								FROM program p
								WHERE p.pgm_id
								NOT IN (SELECT q.pgmtype_id
										FROM qp_framework q )
								ORDER BY p.pgm_acronym ASC';
	}else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
		$logged_in_user_dept = $this->db->query('SELECT u.user_dept_id FROM users u WHERE u.email = "'.$this->session->userdata('email').'"');
		$logged_in_user_dept = $logged_in_user_dept->row_array();
		$program_type_query = 'SELECT p.pgm_id AS pgmtype_id, trim(p.pgm_acronym) AS pgmtype_name
								FROM program p
								WHERE p.dept_id = '.$logged_in_user_dept['user_dept_id'].'
								AND p.pgm_id
								NOT IN (SELECT q.pgmtype_id
										FROM qp_framework q )
								ORDER BY p.pgm_acronym ASC';
							
	}
	$program_type_data = $this->db->query($program_type_query);
	$program_type_result = $program_type_data->result_array();
	return $program_type_result;
	}

	public function bloom_level_data(){
	$bloom_query = 'SELECT bloom_id, level, description, bloom_actionverbs 
						FROM bloom_level
						ORDER BY level ASC';
	$bloom_data = $this->db->query($bloom_query);
	$bloom_result = $bloom_data->result_array();
	return $bloom_result;
	}

/**
Function to insert the question paper frame data into different tables.
**/
public function insert_qp_data($pgmtype_id, $qp_title, $qp_section, $qp_grand_total, $qp_max_marks, $note, $instruction, $sub_section, $section_name, $section_marks, $main_question_num, $main_que_marks, $bloom_ids, $bloom_percent){
	// echo '<pre>';
	// print_r($sub_section);
	// print_r($main_question_num);
	// print_r($main_que_marks);
	// exit;
	$qp_meta_data = array(
		'pgmtype_id' 		=> $pgmtype_id,
		'qpf_title' 			=> $qp_title,
		'qpf_num_units' 		=> $qp_section,
		'qpf_gt_marks' 		=> $qp_grand_total,
		'qpf_max_marks' 		=> $qp_max_marks,
		'qpf_notes' 			=> $note,
		'qpf_instructions' 	=> $instruction );
		$this->db->insert('qp_framework',$qp_meta_data);
	
	$qp_fmwrk_id = $this->db->insert_id();
	
	$section_size = sizeof($sub_section);
	for($i = 0; $i < $section_size; $i++){
		$section_insert = array(
		'qpf_id' 			=> $qp_fmwrk_id,
		'qpf_unit_code' 		=> $section_name[$i],
		'qpf_num_mquestions' => $sub_section[$i],
		'qpf_utotal_marks' 		=> $section_marks[$i] );
		
		$this->db->insert('qpf_unit',$section_insert);
		$qp_unit_code_id = $this->db->insert_id();
		
		for($j = 0; $j < $sub_section[$i]; $j++){
			
			$qp_mn_question_data = array(
			'qpf_id' 		=> $qp_fmwrk_id,
			'qpf_unit_id' 		=> $qp_unit_code_id,
			'qpf_unit_code' 	=> $section_name[$i],
			'qpf_mq_code' 	=> $main_question_num[$i][$j],
			'qpf_mtotal_marks' 	=> $main_que_marks[$i][$j] );
			
			$this->db->insert('qpf_mquestion',$qp_mn_question_data);
		}
		
		
	}
	
		$bloom_size = sizeof($bloom_ids);
		for($bl = 0; $bl < $bloom_size; $bl++){
		$bloom_level_data = array(
		'qpf_id' => $qp_fmwrk_id,
		'bloom_id' => $bloom_ids[$bl],
		'qpf_bl_weightage' => $bloom_percent[$bl] );
		
		$this->db->insert('qpf_bloom_level',$bloom_level_data);
		}
	
	return true;

}

/*
Function to fetch the question paper framework data
*/
	public function framewor_edit_records($qpf_id){
				$program_type_result = $this->db->select('program.pgm_acronym,program.pgm_id, qp_framework.pgmtype_id')
									 ->join('qp_framework','program.pgm_id = qp_framework.pgmtype_id')
									 ->where('qp_framework.qpf_id',$qpf_id)
									 ->get('program')
									 ->result_array();
				/* 		 
				$program_type_query = 'SELECT pgmtype_id, pgmtype_name FROM program_type';
				$program_type_data = $this->db->query($program_type_query);
				$program_type_result = $program_type_data->result_array(); */
				
				$qp_framework_query = 'SELECT qpf_id, qpf_title, pgmtype_id, qpf_num_units, qpf_gt_marks, qpf_max_marks, qpf_notes, qpf_instructions FROM qp_framework WHERE qpf_id ="'.$qpf_id.'" ';
				$qp_framework_data = $this->db->query($qp_framework_query);
				$qp_framework_result = $qp_framework_data->result_array();
				
				$qp_main_question_query = 'SELECT qpf_unit_id, qpf_id, qpf_unit_code, qpf_num_mquestions, qpf_utotal_marks FROM qpf_unit WHERE qpf_id = "'.$qpf_id.'" ';
				$qp_main_question_data = $this->db->query($qp_main_question_query);
				$qp_main_question_result = $qp_main_question_data->result_array();
				
				$qp_sub_question_query = 'SELECT qpf_mq_id, qpf_id, qpf_unit_id, qpf_unit_code, qpf_mq_code, qpf_mtotal_marks FROM qpf_mquestion WHERE qpf_id = "'.$qpf_id.'" ';
				$qp_sub_question_data = $this->db->query($qp_sub_question_query);
				$qp_sub_question_result = $qp_sub_question_data->result_array();
				
				$bloom_level_percent = 'SELECT qpf_bl.qpf_bl_id, qpf_bl.qpf_id, qpf_bl.bloom_id, qpf_bl.qpf_bl_weightage, bl.bloom_id, bl.level, bl.description, bl.bloom_actionverbs FROM qpf_bloom_level as qpf_bl JOIN bloom_level as bl ON qpf_bl.bloom_id = bl.bloom_id 
				WHERE qpf_id = "'.$qpf_id.'" ';
				$bloom_level_percent_data = $this->db->query($bloom_level_percent);
				$bloom_level_percent_res = $bloom_level_percent_data->result_array();
				
				$qp_data['qpf_meta_data'] = $qp_framework_result;
				$qp_data['qp_main_question_data'] = $qp_main_question_result;
				$qp_data['qp_subquestion_data'] = $qp_sub_question_result;
				$qp_data['pgm_type'] = $program_type_result;
				$qp_data['bloom_percent'] = $bloom_level_percent_res;
				
				return $qp_data;
				
	}
/*
Function to update the QP framework details
*/
	public function update_qp_data($qpf_id, $pgmtype_id, $qp_title, $qp_section, $qp_grand_total, $qp_max_marks, $note, $instruction, $sub_section, $section_name, $section_marks, $main_question_num, $main_que_marks, $bloom_ids, $bloom_percent){
	
	$this->db->trans_start();
	$delete_qpf_query = 'DELETE FROM qp_framework WHERE qpf_id = "'.$qpf_id.'" ';
	$delete_qpf_data = $this->db->query($delete_qpf_query);
	
	$delete_qp_unit_query = 'DELETE FROM qpf_unit WHERE qpf_id = "'.$qpf_id.'" ';
	$delete_qp_unit_data = $this->db->query($delete_qp_unit_query);
	
	$delete_qp_subquestion_query = 'DELETE FROM qpf_mquestion WHERE qpf_id = "'.$qpf_id.'" ';
	$delete_qp_subquestion_data = $this->db->query($delete_qp_subquestion_query);
	
	$qpf_bloom_level_query = 'DELETE FROM qpf_bloom_level WHERE qpf_id = "'.$qpf_id.'" ';
	$qpf_bloom_level_data = $this->db->query($qpf_bloom_level_query);
	
	$qp_meta_data = array(
		'pgmtype_id' 		=> $pgmtype_id,
		'qpf_title' 			=> $qp_title,
		'qpf_num_units' 		=> $qp_section,
		'qpf_gt_marks' 		=> $qp_grand_total,
		'qpf_max_marks' 		=> $qp_max_marks,
		'qpf_notes' 			=> $note,
		'qpf_instructions' 	=> $instruction );
		$this->db->insert('qp_framework',$qp_meta_data);
	
	$qp_fmwrk_id = $this->db->insert_id();
	
	$section_size = sizeof($sub_section);
	for($i = 0; $i < $section_size; $i++){
		$section_insert = array(
		'qpf_id' 			=> $qp_fmwrk_id,
		'qpf_unit_code' 		=> $section_name[$i],
		'qpf_num_mquestions' => $sub_section[$i],
		'qpf_utotal_marks' 		=> $section_marks[$i] );
		
		$this->db->insert('qpf_unit',$section_insert);
		$qp_unit_code_id = $this->db->insert_id();
		
		for($j = 0; $j < $sub_section[$i]; $j++){
			
			$qp_mn_question_data = array(
			'qpf_id' 		=> $qp_fmwrk_id,
			'qpf_unit_id' 		=> $qp_unit_code_id,
			'qpf_unit_code' 	=> $section_name[$i],
			'qpf_mq_code' 	=> $main_question_num[$i][$j],
			'qpf_mtotal_marks' 	=> $main_que_marks[$i][$j] );
			
			$this->db->insert('qpf_mquestion',$qp_mn_question_data);
		}
	}
	
	$bloom_size = sizeof($bloom_ids);
		for($bl = 0; $bl < $bloom_size; $bl++){
		$bloom_level_data = array(
		'qpf_id' => $qp_fmwrk_id,
		'bloom_id' => $bloom_ids[$bl],
		'qpf_bl_weightage' => $bloom_percent[$bl] );
		
		$this->db->insert('qpf_bloom_level',$bloom_level_data);
		}
		
		$update_query = 'UPDATE qp_definition SET qpf_id = "'.$qp_fmwrk_id.'" where qpf_id = "'.$qpf_id.'" ';
		$update_result = $this->db->query($update_query);
		
			
	$this->db->trans_complete();
	
	return true;
	}
	
	public function check_qp_framework_used($type_val) {
		$query_res = $this->db->query('SELECT COUNT(qpd.qpf_id) as qpf_id_cnt
								FROM qp_definition qpd
								WHERE qpd.qpf_id = '.$type_val);
		return $query_res->result();
	}
	
}// End of Class Manage_qp_framework_model.
?>