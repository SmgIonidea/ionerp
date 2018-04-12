<?php
/*
* Description	:	Attainment Levels

* Created		:	December 9th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
* Class to define target levels for attainment calculations
*/
class Attainment_level extends CI_Controller {
	function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}
		$this->load->model('assessment_attainment/tier_ii/attainment_level/attainment_level_model','',TRUE);
	}
	/*
* function to display target levels for selected curriculum, term and course
*/
	function index(){
		$data['title'] = "Attainment Levels | IonCUDOS";
		if (!$this->ion_auth->logged_in()) {
			//redirect them to the login page
			redirect('login', 'refresh');
		}else if(!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
			redirect('configuration/users/blank');
		}else{
			$this->load->view('assessment_attainment/tier_ii/attainment_levels/attainment_level_vw',$data);
		}
	}//End of index()

	/*
	* function : Function to display Attainment level program list by user
	* Parameters: user id
	* returns : list of Attainment level programs associated with user id
	*/
	
	function getProgramListByUser(){
		$data['program_level_assess_list']  = $this->attainment_level_model->getProgramLevelAttainment();
		$this->load->view('assessment_attainment/tier_ii/attainment_levels/pgm_list_vw',$data);
	}

	/*
	* function : Function to display Attainment level curriculum list by curriculum
	* Parameters: curriculum id
	* returns : list of Attainment level curriculum associated with curriculum id
	*/
	function getCurriculumListByProgram(){
		$crclm_id = $this->input->post('curlm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('course_id');
		$data['assess_level_curriculum_list']  = $this->attainment_level_model->getCurriculumLevelAttainment($crclm_id);
		$result = $this->attainment_level_model->fetch_mte_flag_status($crclm_id,$term_id,$crs_id);
				$data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				$data['type_flag_course'] = $result['type_flag_course'];				
		$this->load->view('assessment_attainment/tier_ii/attainment_levels/curlm_list_vw',$data);	
	}

	function getPoListByCurriculum(){
		$crclm_id = $this->input->post('curlm_id');
		$data['po_list'] = $this->attainment_level_model->getPoListByCurriculum($crclm_id);
		$this->load->view('assessment_attainment/tier_ii/attainment_levels/perform_po_list_vw',$data);	
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
		 $result = $this->attainment_level_model->getCourseLevelAttainment($crclm_id,$term_id,$course_id);
                  $data['assess_level_course_list'] = $result['course_level_attainment_data'];
                  $data['target'] = $result['course_target_res'];
                  @$data['comment'] = $result['course_target_res']['target_comment'];
				  $data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				  $data['type_flag_course'] = $result['type_flag_course'];
                  $data['crclm_id'] = $crclm_id;
                  $data['term_id'] = $term_id;
                  $data['crs_id'] = $course_id;				  
                if(@$data['target']['target_status'] == 5){
                  if($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
                      $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_review_with_comment_vw',$data);
                  }else{
                      $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_approve_pending_vw',$data);
                    }                     
				}else if(@$data['target']['target_status'] == 6){
					$this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_approve_rework_with_comment_vw',$data);
				}else if(@$data['target']['target_status'] == 7){
					if($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
						$this->load->view('assessment_attainment/tier_ii/attainment_levels/chairman_course_list_approved_vw',$data);
					}else{
						$this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_approved_vw',$data);
					}
				}else{
                   $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_vw',$data);
                }
			
	}
	
	
		/**
	* Function to fetch bloom level on course bases
	*
	**/
	public function bl_course_wise(){

			$crclm_id = $this->input->post('curlm_id');
			$term_id = $this->input->post('term_id');
			$crs_id = $this->input->post('crs_id');
			
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
			$bloom_level_data = $this->attainment_level_model->bloom_level_model_data($crclm_id, $term_id , $crs_id);

			$bloom_level_data_course = $this->attainment_level_model->check_course_bloom_level($crclm_id, $term_id,$crs_id);
			$bloom_detail = $this->attainment_level_model->fetch_bloom_detail($crclm_id, $term_id,$crs_id);
			$result = $this->attainment_level_model->fetch_mte_flag_status($crclm_id  , $term_id , $crs_id);
				
			if(count($bloom_level_data_course['bloom_level_threshold']) == 0){
				$bloom_level_data['crclm_id'] = $crclm_id;
				$bloom_level_data['term_id'] = $term_id;
				$bloom_level_data['bl_dtl'] = $bloom_detail;
				$bloom_level_data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				$bloom_level_data['type_flag_course'] = $result['type_flag_course'];

				echo  $this->load->view('assessment_attainment/tier_ii/attainment_levels/bl_threshold_course_table_vw', $bloom_level_data, true);		
			} else {		
					$bloom_level_data = $bloom_level_data_course;
					$bloom_level_data['crclm_id'] = $crclm_id;
					$bloom_level_data['term_id'] = $term_id;
					$bloom_level_data['bl_dtl'] = $bloom_detail;
							$bloom_level_data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
			$bloom_level_data['type_flag_course'] = $result['type_flag_course'];				
				echo  $this->load->view('assessment_attainment/tier_ii/attainment_levels/bl_threshold_course_table_vw', $bloom_level_data, true);	
			}
	}
	

	/*
	* function : Function to display curriculum in dropdown
	* Parameters: program id
	* returns : list of curriculums associated with program id
	*/

	function select_curriculum(){
		$curriculum_list = $this->attainment_level_model->getCurriculumList();
		$i = 0;
		$list[$i] = '<option value="0">Select Curriculum</option>';
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
		$term_list = $this->attainment_level_model->getTermList($crclm_id);
		$i = 0;
		$list[$i] = '<option value="0">Select Term</option>';
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
		$term_list = $this->attainment_level_model->getCourseList($crclm_id,$crlm_term_id);
		$i = 0;
		$list[$i] = '<option value="0">Select Course</option>';
		$i++;
		foreach($term_list as $data) {
			$list[$i] = "<option value = " . $data['crs_id'] . ">" . $data['crs_title'] . "</option>";
			$i++;
		}
		$list = implode(" ", $list);
		echo $list;
	}	
	
	/*
	* function : Function to insert Program level attainment
	* Parameters: 
	* returns :
	*/
	function save_progm_level_attainment(){
		$form_data = array(
		'pgm_id'=>$this->input->post('apl_id_pgm'),
		'assess_level_name' =>$this->input->post('apl_level_name'),
		'assess_level_name_alias' => $this->input->post('apl_level_name'),
		'assess_level_value' => $this->input->post('apl_level_value'),
		'student_percentage' => $this->input->post('apl_student_perc'),
		'cia_target_percentage' => $this->input->post('apl_target_perc'),
		);
		$is_added = ($this->attainment_level_model->insert_pgm_level_attainment($form_data));
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
	function update_program_level_attainment(){
		$form_data = array(
		'assess_level_name' =>$this->input->post('et_apl_level_name'),
		'assess_level_name_alias' => $this->input->post('et_apl_level_name'),
		'assess_level_value' => $this->input->post('et_apl_level_value'),
		'student_percentage' => $this->input->post('et_apl_student_perc'),
		'cia_target_percentage' => $this->input->post('et_apl_target_perc'),
		);
		$is_updated = ($this->attainment_level_model->update_program_level_attainment($form_data,$this->input->post('et_apl_id')));
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
	
	function save_curriculum_level_attainment(){
		$form_data = array(
		'crclm_id'=>$this->input->post('crclm_id'),
		'assess_level_name' =>$this->input->post('acl_level_name'),
		'assess_level_name_alias' => $this->input->post('acl_level_name'),
		'assess_level_value' => $this->input->post('acl_level_value'),
		'cia_direct_percentage' => $this->input->post('acl_direct_perc'),
		'conditional_opr' => $this->input->post('acl_conditional_opr'),
		'cia_target_percentage' => $this->input->post('acl_target_perc'),
		'justify' => $this->input->post('acl_justify'),
		);
		$is_added = ($this->attainment_level_model->insert_curriculum_level_attainment($form_data));
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
	function update_curriculum_level_attainment(){
		if($this->input->post('crclm_atn_type')=="indirect"){
			$form_data = array(
			'indirect_percentage' => $this->input->post('crclm_indirect_perc'),
			);
		}else{
			$form_data = array(
			'assess_level_name' =>$this->input->post('acl_level_name'),
			'assess_level_name_alias' => $this->input->post('acl_level_name'),
			'assess_level_value' => $this->input->post('acl_level_value'),
			'cia_direct_percentage' => $this->input->post('acl_direct_perc'),
			//'conditional_opr' => $this->input->post('acl_conditional_opr'),
			'cia_target_percentage' => $this->input->post('acl_target_perc'),
			'justify' => $this->input->post('acl_justify'),
			);
		}
		$is_updated = ($this->attainment_level_model->update_curriculum_level_attainment($form_data,$this->input->post('acl_id')));
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

	function save_course_level_attainment(){
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
		'cia_direct_percentage' => $this->input->post('acrsl_direct_perc'),
		'mte_direct_percentage' => $this->input->post('acrsl_mte_direct_perc'),
		'tee_direct_percentage' => $this->input->post('acrsl_see_direct_perc'),
		'indirect_percentage' => $this->input->post('acrsl_indirect_perc'),
		'conditional_opr' => $this->input->post('acrsl_conditional_opr'),
		'cia_target_percentage' => $this->input->post('acrsl_target_perc'),
		'mte_target_percentage' => $this->input->post('acrsl_mte_target_perc'),
		'tee_target_percentage' => $this->input->post('acrsl_tee_target_perc'),
		'justify'=>$this->input->post('acrsl_justify'),
		);
		$is_added = ($this->attainment_level_model->insert_course_level_attainment($form_data));
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
		if($this->input->post('crs_atn_type')=="indirect"){
			$form_data = array(
			'indirect_percentage'=>$this->input->post('crs_indirect_perc'),
			);
		}else{
			$form_data = array(
			'assess_level_name'=>$this->input->post('et_acl_level_name'),
			'assess_level_name_alias'=>$this->input->post('et_acl_level_name'),
			'assess_level_value'=>$this->input->post('et_acl_level_val'),
			'cia_direct_percentage'=>$this->input->post('et_acl_direct_perc'),
			'mte_direct_percentage'=>$this->input->post('et_acl_mte_direct_perc'),
			'tee_direct_percentage'=>$this->input->post('et_acl_see_direct_perc'),
			'cia_target_percentage'=>$this->input->post('et_acl_target_perc'),
			'mte_target_percentage'=>$this->input->post('et_acl_mte_target_perc'),
			'tee_target_percentage'=>$this->input->post('et_tee_target_perc'),
			'justify'=>$this->input->post('et_acl_justify'),
			);
		}
		$is_updated = ($this->attainment_level_model->update_assessment_level_course($form_data,$this->input->post('et_acl_id'),$this->input->post('et_acl_crs_id')));
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

	function save_perform_level_attainment(){
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
		$is_added = ($this->attainment_level_model->insert_perform_level_attainment($form_data));
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
		$is_added = ($this->attainment_level_model->update_performance_assessment_level_po($plp_id,$plp_name,$plp_level,$plp_desc,$plp_start_rng,$plp_opr,$plp_end_rng));
		//redirect('tier_ii/attainment_level/','refresh');
		if($is_added){
			echo 0;
		}else{
			echo 1;
		}
	}

	/*
	* function : Get Attainments related to PO
	* Parameters: List
	* returns :
	*/
	function get_performance_level_attainments_by_po(){
		$plp_id_pgm = $this->input->post('po_id');
		$po_list = $this->attainment_level_model->get_performance_attainment_list($plp_id_pgm);
		echo json_encode($po_list);

	}

	/*
	* function : Used to delete Attainment Level Program 
	* Parameters: apl_id
	* returns :
	*/

	function delete_program_level_attainment(){
		if (!$this->ion_auth->logged_in()) {
			echo "ERROR!!!";
		} elseif (!$this->ion_auth->is_admin()) {
			//redirect them to the home page because they must be an administrator to view this
			echo "ERROR!!!";
		}
		//permission_end
		else {
			$alp_id = $this->input->post('alp_id');
			$delete_result = $this->attainment_level_model->delete_program_level_attainment($alp_id);
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
	function delete_crclm_level_attainment(){
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
			$delete_result = $this->attainment_level_model->delete_curriculum_level_attainment($alp_id);
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
		} elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner'))) {
			//redirect them to the home page because they must be an administrator to view this
			echo "falied";
		}
		//permission_end
		else {
			$alp_id = $this->input->post('alp_id');
			$delete_result = $this->attainment_level_model->delete_assess_course_level($alp_id);
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
			$alp_id = $this->input->post('id');
			$delete_result = $this->attainment_level_model->delete_perform_assess_level($alp_id);
			//redirect('tier_ii/attainment_level/','refresh');
			if($delete_result){
				echo 0;
			}else{
				echo 1;
			}
		}
	}
	//For editing
	function get_course_level_assessment_by_id(){
		$apl_id = $this->input->post('acrsl_id');
		echo json_encode($this->attainment_level_model->get_course_al_by_id($apl_id));
	}//End of get_course_level_Attainment_by_id()
	
	function get_program_level_assessment_by_id(){
		$apl_id = $this->input->post('apl_id');
		echo json_encode($this->attainment_level_model->get_program_al_by_id($apl_id));
	}//End of get_program_level_Attainment_by_id()
	
	function get_curriculum_level_assessment_by_id(){
		$apl_id = $this->input->post('acl_id');
		echo json_encode($this->attainment_level_model->get_crclm_al_by_id($apl_id));
	}//End of get_program_level_Attainment_by_id()
	
	function is_check_crclm_attainment_level_exists(){
		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('crs_id');
		if($crclm_id == 0 && $term_id == 0 && $crs_id == 0 ){
			$where = array(
			'crclm_id'=>$crclm_id,
			'crclm_term_id'=>$term_id,
			'crs_id'=>$crs_id,
			);
			echo $this->attainment_level_model->check_crclm_attain_exits($where);
		}else{
			echo 'selectempty';
		}
	}
/*
 * Function to send for Approve.
 * @param: crs_id, term_id, crclm_id
 * @return:
 */
public function send_for_approve(){
                $crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('crs_id');
                
                $data['assess_level_course_list'] = $this->attainment_level_model->send_for_approve($crclm_id,$term_id,$crs_id);
				$result = $this->attainment_level_model->fetch_mte_flag_status($crclm_id,$term_id,$crs_id);
				$data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				$data['type_flag_course'] = $result['type_flag_course'];
                $approve_page = $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_approve_pending_vw',$data,true);
                echo $approve_page;
                
    
}

/*
 * function for Skip review confirmation
 * @param:
 * @return:
 */
public function skip_review_confirm(){
     $skip_review_val = $this->attainment_level_model->skip_review_confirm();
     if($skip_review_val){
         echo $skip_review_val['skip_review'];
     }else{
         $skip_val = 0;
         echo $skip_val;
     }
}

/*
 * Function to send for Skip Approve.
 * @param: crs_id, term_id, crclm_id
 * @return:
 */
public function send_for_skip_approve(){
                $crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$crs_id = $this->input->post('crs_id');
                
                $data['assess_level_course_list'] = $this->attainment_level_model->send_for_skip_approve($crclm_id,$term_id,$crs_id);
                if($data['assess_level_course_list'] == true){
                    $data = 'success';
                }else{
                    $data = 'fail';
                }
                echo $data;
                
    
}

/*
         * Function to display the course target content to review and accept to the chairman
         * @param: crclm_id, term_id, crs_id
         * @return:
         */
        public function chairman_review_pending($crclm_id=NULL, $term_id=NULL, $crs_id=NULL){
           
                $result = $this->attainment_level_model->chairman_review_pending($crclm_id,$term_id,$crs_id);
                
                //course dropdown box to replot the table after updating the values
                $select = '';
                $select .= '<option value="'.$crs_id.'" selected="selected">'.$crs_id.'</option>';
             
                $data['assess_level_course_list'] = $result['course_level_attainment_data'];
                $data['comment'] = $result['comment'];
                $data_one['select_box'] = $select;
                $data['crclm_id'] = $crclm_id;
                $data['term_id'] = $term_id;
                $data['crs_id'] = $crs_id;
                $data_one['crclm_id'] = $crclm_id;
                $data_one['term_id'] = $term_id;
                $data_one['crs_id'] = $crs_id;
                $data_one['meta_data'] = $result['meta_data'];
				$result = $this->attainment_level_model->fetch_mte_flag_status($crclm_id,$term_id,$crs_id);
				$data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				$data['type_flag_course'] = $result['type_flag_course'];
                $target_details_data = $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_review_with_comment_vw',$data,true);
                
                $data_one['target_details_data'] = $target_details_data;
                $this->load->view('assessment_attainment/tier_ii/attainment_levels/attainment_level_review_pending_vw',$data_one);
        }
        
    /*
     * Function to accept the course target levels 
     * @param: crclm_id, term_id, crs_id, 
     * @return: 
     */
    public function target_accept_function(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $justification = $this->input->post('justification');
        $result = $this->attainment_level_model->target_accept_function($crclm_id,$term_id,$crs_id, $justification);
        if($result == true){
            $data = 'success';
        }else{
            $data = 'fail';
        }
        echo $data;
    }
    
    /*
     * Function to accept the course target levels 
     * @param: crclm_id, term_id, crs_id, 
     * @return: 
     */
    public function target_rework_function(){
        $crclm_id = $this->input->post('crclm_id');
        $term_id = $this->input->post('term_id');
        $crs_id = $this->input->post('crs_id');
        $justification = $this->input->post('justification');
        $result = $this->attainment_level_model->target_rework_function($crclm_id,$term_id,$crs_id, $justification);
        if($result == true){
            $data = 'success';
        }else{
            $data = 'fail';
        }
        echo $data;
    }
    
/*
         * Function to display the course target for rework.
         * @param: crclm_id, term_id, crs_id
         * @return:
         */
        public function course_owner_rework($crclm_id=NULL, $term_id=NULL, $crs_id=NULL){
           
                $result = $this->attainment_level_model->course_owner_rework($crclm_id,$term_id,$crs_id);
                
                //course dropdown box to replot the table after updating the values
                $select = '';
                $select .= '<option value="'.$crs_id.'" selected="selected">'.$crs_id.'</option>';
             
                $data['assess_level_course_list'] = $result['course_level_attainment_data'];
                $data_one['select_box'] = $select;
                $data_one['crclm_id'] = $crclm_id;
                $data['crclm_id'] = $crclm_id;
                $data_one['term_id'] = $term_id;
                $data['term_id'] = $term_id;
                $data_one['crs_id'] = $crs_id;
                $data['crs_id'] = $crs_id;
                $data['comment'] = $result['comment'];
                $data_one['meta_data'] = $result['meta_data'];
				$result = $this->attainment_level_model->fetch_mte_flag_status($crclm_id,$term_id,$crs_id);
				$data['mte_flag_org'] = $result['mte_flag_org'][0]['mte_flag'];
				$data['type_flag_course'] = $result['type_flag_course'];
                $target_details_data = $this->load->view('assessment_attainment/tier_ii/attainment_levels/course_list_approve_rework_with_comment_vw',$data,true);
                
                $data_one['target_details_data'] = $target_details_data;
                $this->load->view('assessment_attainment/tier_ii/attainment_levels/attainment_level_review_pending_vw',$data_one);
        }
        
        public function open_for_rework_function(){
            $crclm_id = $this->input->post('crclm_id');
            $term_id = $this->input->post('term_id');
            $crs_id = $this->input->post('crs_id');
            $result = $this->attainment_level_model->open_for_rework_function($crclm_id,$term_id,$crs_id);
            
            if($result == true){
                $data = 'success';
            }else{
                $data = 'fail';
            }
            echo $data;
        }
		
		public function  save_bloom_course_wise(){

		$crclm_id = $this->input->post('crclm_id');
		$term_id = $this->input->post('term_id');
		$bl_min = $this->input->post('bl_min');
		$mte_min = $this->input->post('mte_min');
        $tee_min = $this->input->post('tee_min');
		$bl_stud = $this->input->post('bl_stud');
		$bl_justify = $this->input->post('bl_justify');
		$crs_id_data = $this->input->post('crs_id_data');	
		$bloom_id = $this->input->post('bloom_id');	            
		$bloom_level_data = $this->attainment_level_model->save_bloom_course_wise($crclm_id, $term_id , $bl_min , $mte_min , $bl_stud , $bl_justify , $crs_id_data ,$bloom_id , $tee_min);
		echo $bloom_level_data;
	}

}//End of class Attainment_level

/* End of file attainment_level.php */
/* Location: ./application/controllers/tier_ii/attainment_level.php */