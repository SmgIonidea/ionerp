<?php
/*
--------------------------------------------------------------------------------------------------------------------------------
* Description	: Assessment level 
* Modification History:
* Author : Shivaraj B
* Date				Modified By				Description
---------------------------------------------------------------------------------------------------------------------------------
*/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assessment_level_model extends CI_Model{

	/*
		* Function is to get all programs list.
		* @param - ------.
		* returns the list of programs
	*/

	function getProgramList(){
		if (!$this->ion_auth->is_admin()) {
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
			$dept_data = $this->db->select('base_dept_id')
			->where('id',$loggedin_user_id)
			->limit(1)
			->get('users');
			$dept_data = $dept_data->result_array();
			$dept_id = $dept_data[0]['base_dept_id'];
			$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym FROM program WHERE dept_id = "' . $dept_id . '" AND status = 1 ORDER BY pgm_acronym ASC';
		}else{
			$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym FROM program WHERE status = 1 ORDER BY pgm_acronym ASC';
		}
		$resx = $this->db->query($pgm_name);
		return $resx->result_array();
	}

	function getCurriculumList($pgm_id){
		$crclm_name = 'SELECT DISTINCT crclm_id, crclm_name
	FROM curriculum
	WHERE pgm_id = "'.$pgm_id.'" 
	AND status = 1 
	ORDER BY crclm_name ASC';
		$result_data = $this->db->query($crclm_name);
		$result = $result_data->result_array();
		$crclm_data['crclm_result'] = $result;
		return $result;
	}
	
	function getTermList($crclm_id){
		$term_name = 'SELECT crclm_term_id, term_name 
	FROM crclm_terms 
	WHERE crclm_id = "'.$crclm_id.'"';
		$result = $this->db->query($term_name);
		$data = $result->result_array();
		return $data;
	}

	function getCourseList($crclm_id,$term_id){
		$course_name = 'SELECT crs_id,crclm_id,crclm_term_id, crs_title 
	FROM course 
	WHERE crclm_id = "'.$crclm_id.'" AND crclm_term_id = "'.$term_id.'" AND status = 1';
		$result = $this->db->query($course_name);
		$data = $result->result_array();
		return $data;
	}    

	/*
		* Function is to get all program level assessment list.
		* @param - ------.
		* returns the list of program level assessments.
	*/

	function getProgramLevelAssessment(){
		$pgm_id = $this->input->post('pgm_id');
		$get_program_level_assess_query = $this->db->select('alp_id,pgm_id,assess_level_name,assess_level_name_alias,assess_level_value,student_percentage,cia_target_percentage,created_by,created_date,modified_by,modified_date')
		->where('pgm_id',$pgm_id)->order_by("assess_level_value", "asc")
		->get('assessment_level_program');
		return $get_program_level_assess_query->result_array();

	}
	
	function getCurriculumLevelAssessment($curclm_id){
		$get_curriculum_level_assess_query = $this->db->select('alp_id,crclm_id,assess_level_name,assess_level_name_alias,assess_level_value,student_percentage,cia_target_percentage,created_by,created_date,modified_by,modified_date')
		->where('crclm_id',$curclm_id)->order_by("assess_level_value", "asc")
		->get('assessment_level_crclm');
		return $get_curriculum_level_assess_query->result_array();

	}
	
	function getCourseLevelAssessment($crclm_id,$term_id,$course_id){	
	$get_course_level_assess_query = $this->db->select('*')->where('crclm_id',$crclm_id)->where('crclm_term_id',$term_id)->where('crs_id',$course_id)->order_by("assess_level_value", "asc")->get('assessment_level_course');
		return $get_course_level_assess_query->result_array();
	}
	
	function insert_assess_pgm_level($form_data){
		return $this->db->insert('assessment_level_program',$form_data);
	}
	
	function insert_perform_assess_level($plp_data){
		return $this->db->insert('performance_level_po',$plp_data);
	}

	function get_performance_assess_list($po_id){
		$result = $this->db->select('*')->where('po_id',$po_id)->get('performance_level_po');
		return $result->result_array();
	}

	function update_assessment_level_progm($form_data,$alp_id){
		$this->db->where('alp_id',$alp_id);
		return $this->db->update('assessment_level_program',$form_data);
	}
	
	function update_assessment_level_curriculum($apl_data,$alp_id){
		$this->db->where('alp_id',$alp_id);
		$this->db->update('assessment_level_crclm',$apl_data);
	}
	
	function update_assessment_level_course($form_data,$acl_id){
		return $this->db->update('assessment_level_course',$form_data,array('alp_id'=>$acl_id));
	}
	
	function insert_assess_curriculum_level($form_data){
		return $this->db->insert('assessment_level_crclm',$form_data);
	}
	
	function insert_assess_course_level($apl_data){
		return $this->db->insert('assessment_level_course',$apl_data);
	}

	function update_performance_assessment_level_po($plp_id,$plp_name,$plp_level,$plp_desc,$plp_start_rng,$plp_opr,$plp_end_rng){
		
		$this->db->trans_start();
		$max = sizeof($plp_name);
		for($i=0;$i<$max;$i++){
			$apl_data = array(
			'performance_level_name'=>$plp_name[$i],
			'performance_level_name_alias'=>$plp_name[$i],
			'performance_level_value'=>$plp_level[$i],
			'description'=>$plp_desc[$i],
			'start_range'=>$plp_start_rng[$i],
			'end_range'=>$plp_end_rng[$i],
			'conditional_opr'=>$plp_opr[$i],
			);
			$this->db->where('plp_id',$plp_id[$i]);
			$this->db->update('performance_level_po',$apl_data);
			
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() == FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function delete_assess_program_level($alp_id){
		$query = ' DELETE FROM assessment_level_program WHERE alp_id = "'.$alp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}
	
	function delete_assess_curriculum_level($alp_id){
		$query = ' DELETE FROM assessment_level_crclm WHERE alp_id = "'.$alp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}

	function delete_assess_course_level($alp_id){
		$query = ' DELETE FROM assessment_level_course WHERE alp_id = "'.$alp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}

	function delete_perform_assess_level($plp_id){
		$query = ' DELETE FROM performance_level_po WHERE plp_id = "'.$plp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}

	function getPoListByCurriculum($crclm_id){
		$result = $this->db->where('crclm_id',$crclm_id)->get('po');
		return $result->result_array();
	}
	
	function get_course_al_by_id($id){
		$result = $this->db->get_where('assessment_level_course',array('alp_id'=>$id));
		return $result->result_array();
	}
	function get_program_al_by_id($id){
		$result = $this->db->get_where('assessment_level_program',array('alp_id'=>$id));
		return $result->result_array();
	}
	function get_crclm_al_by_id($id){
		$result = $this->db->get_where('assessment_level_crclm',array('alp_id'=>$id));
		return $result->result_array();
	}

}//end of class assessment_level_model