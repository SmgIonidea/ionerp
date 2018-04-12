<?php
/*
* Description	:	Attainment Levels

* Created		:	September 30th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description
7-12-2015			Shivaraj B				Removed add more option and added new columns
----------------------------------------------------------------------------------------------*/
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assessment_level extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('assessment_attainment/assessment_level/assessment_level_model','',TRUE);
	}

	function index(){
		$data['title'] = "Attainment Levels | IonCUDOS";
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}else if(!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') )) {
			redirect('configuration/users/blank');
		}else{
			$data['program_list'] = $this->assessment_level_model->getProgramList();
			$this->load->view('assessment_attainment/assessment_level/assessment_level_vw',$data);
		}
	}//End of index()

	/*
	* function : Function to display Attainment level program list by user
	* Parameters: user id
	* returns : list of Attainment level programs associated with user id
	*/
	
	function getProgramListByUser(){
		$data['program_level_assess_list']  = $this->assessment_level_model->getProgramLevelAssessment();
		$this->load->view('assessment_attainment/assessment_level/static_prm_list_vw',$data);
	}

	/*
	* function : Function to display Attainment level curriculum list by curriculum
	* Parameters: curriculum id
	* returns : list of Attainment level curriculum associated with curriculum id
	*/
	function getCurriculumListByProgram(){
		$curclm_id = $this->input->post('curlm_id');
		$data['assess_level_curriculum_list']  = $this->assessment_level_model->getCurriculumLevelAssessment($curclm_id);	
		$this->load->view('assessment_attainment/assessment_level/static_curlm_list_vw',$data);	
	}

	function getPoListByCurriculum(){
		$curclm_id = $this->input->post('curlm_id');
		$data['po_list'] = $this->assessment_level_model->getPoListByCurriculum($curclm_id);
		$this->load->view('assessment_attainment/assessment_level/static_po_list_vw',$data);	
	} 

	/*
	* function : Function to display Attainment level course list by Term
	* Parameters: curriculum id,term id
	* returns : list of Attainment level course associated with curriculum id and term id
	*/
	function getCourseListByTerm(){

		$crclm_id = $this->input->post('curlm_id');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$data['assess_level_course_list']  = $this->assessment_level_model->getCourseLevelAssessment($crclm_id,$term_id,$course_id);
		$this->load->view('assessment_attainment/assessment_level/static_course_list_vw',$data);	
	}

	/*
	* function : Function to display curriculum in dropdown
	* Parameters: program id
	* returns : list of curriculums associated with program id
	*/

	function select_curriculum(){
		$pgm_id = $this->input->post('pgm_id');

		$curriculum_list = $this->assessment_level_model->getCurriculumList($pgm_id);
		$i = 0;
		$list[$i] = '<option value="">Select Curriculum</option>';
		$i++;
		foreach($curriculum_list as $data) {
			$list[$i] = "<option value ='".$data['crclm_id']."'>" . $data['crclm_name'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}

	/*
	* function : Function to display Terms in dropdown
	* Parameters: curriculum id 
	* returns : list of terms associated with curriculum id
	*/
	function select_term(){
		$crclm_id = $this->input->post('curlm_id');
		$term_list = $this->assessment_level_model->getTermList($crclm_id);
		$i = 0;
		$list[$i] = '<option value="">Select Term</option>';
		$i++;
		foreach($term_list as $data) {
			$list[$i] = "<option value = " . $data['crclm_term_id'] . ">" . $data['term_name'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}

	/*
	* function : Function to display courses in dropdown
	* Parameters: curriculum id , term id
	* returns : list of courses associated with curriculum id
	*/
	function select_course(){
		$crclm_id = $this->input->post('curlm_id');
		$crlm_term_id = $this->input->post('term_id');
		$term_list = $this->assessment_level_model->getCourseList($crclm_id,$crlm_term_id);
		$i = 0;
		$list[$i] = '<option value="">Select Course</option>';
		$i++;
		foreach($term_list as $data) {
			$list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}	
	
	/*
	* function : Function to insert Attainment Level Program 
	* Parameters: 
	* returns :
	*/
	function insert_progm_level_assess(){
		$form_data = array(
		'pgm_id'=>$this->input->post('apl_id_pgm'),
		'assess_level_name' =>$this->input->post('apl_level_name'),
		'assess_level_name_alias' => $this->input->post('apl_level_name'),
		'assess_level_value' => $this->input->post('apl_level_value'),
		'student_percentage' => $this->input->post('apl_student_perc'),
		'cia_target_percentage' => $this->input->post('apl_target_perc'),
		);
		$is_added = ($this->assessment_level_model->insert_assess_pgm_level($form_data));
		if($is_added){
			echo "success";
		}else{
			echo "failed";
		}
	}
	
	/*
	* function : Function to Update Attainment Level Program 
	* Parameters: 
	* returns :
	*/
	function update_assessment_level_pgm(){
		$form_data = array(
		'assess_level_name' =>$this->input->post('et_apl_level_name'),
		'assess_level_name_alias' => $this->input->post('et_apl_level_name'),
		'assess_level_value' => $this->input->post('et_apl_level_value'),
		'student_percentage' => $this->input->post('et_apl_student_perc'),
		'cia_target_percentage' => $this->input->post('et_apl_target_perc'),
		);
		$is_updated = ($this->assessment_level_model->update_assessment_level_progm($form_data,$this->input->post('et_apl_id')));
		if($is_updated){
			echo "success";
		}else{
			echo "failed";
		}
	}

	/*
	* function : Function to add Attainment Level Curriculum 
	* Parameters: 
	* returns :
	*/
	
	function insert_curriculum_level_assess(){
		$form_data = array(
		'crclm_id'=>$this->input->post('crclm_id'),
		'assess_level_name' =>$this->input->post('acl_level_name'),
		'assess_level_name_alias' => $this->input->post('acl_level_name'),
		'assess_level_value' => $this->input->post('acl_level_value'),
		'student_percentage' => $this->input->post('acl_student_perc'),
		'cia_target_percentage' => $this->input->post('acl_target_perc'),
		);
		$is_added = ($this->assessment_level_model->insert_assess_curriculum_level($form_data));
		if($is_added){
			echo "success";
		}else{
			echo "failed";
		}
	}	
	
	/*
	* function : Function to update curriculum Attainment level 
	* Parameters: 
	* returns :
	*/
	function update_curriculum_assess(){
		$form_data = array(
		'assess_level_name' =>$this->input->post('acl_level_name'),
		'assess_level_name_alias' => $this->input->post('acl_level_name'),
		'assess_level_value' => $this->input->post('acl_level_value'),
		'student_percentage' => $this->input->post('acl_student_perc'),
		'cia_target_percentage' => $this->input->post('acl_target_perc'),
		);
		$is_updated = ($this->assessment_level_model->update_assessment_level_curriculum($form_data,$this->input->post('acl_id')));
		if($is_updated){
			echo "success";
		}else{
			echo "failed";
		}
	}

	/*
	* function : Function to insert course level Attainment 
	* Parameters: 
	* returns :
	*/

	function insert_course_level_assess(){
		$counter = $this->input->post('course_counter');
		$term_id = $this->input->post('term_id');
		$course_id = $this->input->post('course_id');
		$form_data = array(
		'crclm_id' => $this->input->post('curriculum_id'),
		'crclm_term_id' => $this->input->post('term_id'),
		'crs_id' => $this->input->post('course_id'),
		'assess_level_name' => $this->input->post('acrsl_level_name'),
		'assess_level_name_alias' => $this->input->post('acrsl_level_name'),
		'assess_level_value' => $this->input->post('acrsl_level_val'),
		'direct_percentage' => $this->input->post('acrsl_direct_perc'),
		'indirect_percentage' => $this->input->post('acrsl_indirect_perc'),
		'conditional_opr' => $this->input->post('acrsl_conditional_opr'),
		'cia_target_percentage' => $this->input->post('acrsl_target_perc'),
		);
		$is_added = ($this->assessment_level_model->insert_assess_course_level($form_data));
		if($is_added){
			echo "success";
		}else{
			echo "failed";
		}
	}

	/*
	* function : function to update Attainment level courses
	* Parameters: 
	* returns :
	*/

	function update_course_assess(){
		$form_data = array(
		'assess_level_name'=>$this->input->post('et_acl_level_name'),
		'assess_level_name_alias'=>$this->input->post('et_acl_level_name'),
		'assess_level_value'=>$this->input->post('et_acl_level_val'),
		'cia_direct_percentage'=>$this->input->post('et_acl_direct_perc'),
		'indirect_percentage'=>$this->input->post('et_acl_indirect_perc'),
		'cia_target_percentage'=>$this->input->post('et_acl_target_perc'),
		);
		$is_updated = ($this->assessment_level_model->update_assessment_level_course($form_data,$this->input->post('et_acl_id')));
		if($is_updated){
			echo "success";
		}else{
			echo "failed";
		}
	}

	/*
	* function : To insert performance Attainment levels
	* Parameters: plp_id
	* returns :
	*/

	function insert_perform_level_assess(){
		$form_data = array(
		'crclm_id' => $this->input->post('per_crclm_id'),
		'po_id' => $this->input->post('po_id'),
		'performance_level_name' => $this->input->post('plp_name'),
		'performance_level_name_alias' => $this->input->post('plp_name'),
		'performance_level_value' => $this->input->post('plp_level_val'),
		'description' => $this->input->post('plp_desc'),
		'start_range' => $this->input->post('plp_start_range'),
		'end_range' => $this->input->post('plp_end_range'),
		'conditional_opr' => $this->input->post('plp_conditional_opr'),
		);
		$is_added = ($this->assessment_level_model->insert_perform_assess_level($form_data));
		if($is_added){
			echo "success";
		}else{
			echo "failed";
		}
	}

	function update_perform_level_assess(){
		$count = $this->input->post('plp_count_val');
		
		for($i=0;$i<$count;$i++){
			$plp_id[] = $this->input->post('po_id'.$i); 
			$plp_name[] = $this->input->post('plp_name'.$i);
			$plp_level[] = $this->input->post('plp_level_val'.$i);
			$plp_desc[] = $this->input->post('plp_desc'.$i);
			$plp_start_rng[] = $this->input->post('plp_start_range'.$i);
			$plp_opr[] = $this->input->post('plp_conditional_opr'.$i);
			$plp_end_rng[] = $this->input->post('plp_end_range'.$i);
		}
		$is_added = ($this->assessment_level_model->update_performance_assessment_level_po($plp_id,$plp_name,$plp_level,$plp_desc,$plp_start_rng,$plp_opr,$plp_end_rng));
		redirect('assessment_attainment/assessment_level/','refresh');
	}

	/*
	* function : Get Attainments related to PO
	* Parameters: List
	* returns :
	*/
	function get_performance_level_assessments_by_po(){
		$plp_id_pgm = $this->input->post('po_id');
		$po_list = $this->assessment_level_model->get_performance_assess_list($plp_id_pgm);
		echo json_encode($po_list);

	}

	/*
	* function : Used to delete Attainment Level Program 
	* Parameters: apl_id
	* returns :
	*/

	function assessment_level_program_delete(){
		if (!$this->ion_auth->logged_in()) {
			echo "ERROR!!!";
		} elseif (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			echo "ERROR!!!";
		}
		//permission_end
		else {
			$alp_id = $this->input->post('alp_id');
			$delete_result = $this->assessment_level_model->delete_assess_program_level($alp_id);
			if($delete_result){
				echo "success";
			}else{
				echo "failed";
			}
		}
	}

	/*
	* function : Used to delete Attainment Level Curriculum 
	* Parameters: apl_id
	* returns :
	*/
	function assessment_level_curriculum_delete(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		}
		//permission_end
		else {

			$alp_id = $this->input->post('alp_id');
			$delete_result = $this->assessment_level_model->delete_assess_curriculum_level($alp_id);
			if($delete_result){
				echo "success";
			}else{
				echo "failed";
			}
		}
	}

	/*
	* function : Used to delete Attainment Level Course 
	* Parameters: apl_id
	* returns :
	*/
	function assessment_level_course_delete(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			echo "falied";
		}
		//permission_end
		else {

			$alp_id = $this->input->post('alp_id');
			$delete_result = $this->assessment_level_model->delete_assess_course_level($alp_id);
			if($delete_result){
				echo "success";
			}else{
				echo "failed";
			}
		}
	}

	function performance_assess_lvl_delete(){
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		} elseif (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			redirect('configuration/users/blank', 'refresh');
		}
		//permission_end
		else {

			$alp_id = $this->input->get('id');
			$delete_result = $this->assessment_level_model->delete_perform_assess_level($alp_id);
			if($delete_result){
				echo "success";
			}else{
				echo "failed";
			}
		}
	}
	//For editing
	function get_course_level_assessment_by_id(){
		$apl_id = $this->input->post('acrsl_id');
		$data = ($this->assessment_level_model->get_course_al_by_id($apl_id));
		echo json_encode;
	}//End of get_course_level_Attainment_by_id()
	
	function get_program_level_assessment_by_id(){
		$apl_id = $this->input->post('apl_id');
		echo json_encode($this->assessment_level_model->get_program_al_by_id($apl_id));
	}//End of get_program_level_Attainment_by_id()
	
	function get_curriculum_level_assessment_by_id(){
		$apl_id = $this->input->post('acl_id');
		echo json_encode($this->assessment_level_model->get_crclm_al_by_id($apl_id));
	}//End of get_program_level_Attainment_by_id()

}//End of class Attainment_level