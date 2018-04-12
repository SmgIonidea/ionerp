<?php

class Host_survey_model extends CI_Model{

	public $obj=array();
	public function __construct() {
		parent::__construct();
		$this->load->model('survey/other/department');
		$this->load->model('survey/other/program');
		$this->load->model('survey/stakeholder_group');
		$this->load->model('survey/other/master_type_detail');
		$this->load->model('survey/other/course');
		$this->load->model('survey/answer_option');
		$this->load->model('survey/question_type');
		$this->load->model('/survey/Survey_User');
        $this->load->model('/email/email_model');
		$this->load->model('/survey/Survey_Response');
		$this->load->model('/survey/survey');
	}
	
	public function survey_list($user_id){ 
		$crclm_list = $this->curriculum_dropdown($user_id);
		return $crclm_list;
	}
		
	public function curriculum_dropdown($user_id){
		if($this->ion_auth->in_group('admin')){
			$curriculum_query = 'SELECT crclm_id, crclm_name, crclm_owner, crclm_release_status, pgm_id, create_date, created_by, modify_date, modified_by, status, oe_pi_flag FROM curriculum WHERE status = 1 ORDER BY crclm_name';
			$curriculum_data = $this->db->query($curriculum_query);
			$curriculum_list_result = $curriculum_data->result_array();
		}else{
					$curriculum_query = 'SELECT usr.user_dept_id, pgm.pgm_id, cr.crclm_id, cr.crclm_name, cr.crclm_owner, cr.crclm_release_status, cr.pgm_id, cr.create_date, cr.created_by, cr.modify_date, cr.modified_by, cr.status, cr.oe_pi_flag FROM curriculum as cr JOIN users as usr ON usr.id = "'.$user_id.'" JOIN program as pgm ON pgm.dept_id = usr.user_dept_id WHERE cr.status = 1 AND cr.pgm_id = pgm.pgm_id';
					$curriculum_data = $this->db->query($curriculum_query);
					$curriculum_list_result = $curriculum_data->result_array();
		}
		return $curriculum_list_result;
		
	}
	
	public function get_survey_for_list(){
	$query = $this->db->query("select * from master_type_details where master_type_id = 4 ");
	return $query->result_array();
	}
	
	/*
		Function to get the list of surveys
	*/
	
	public function get_list_of_surveys($crclm_id,$survey_for_id,$term_id){
		if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
            if($survey_for_id == -1){
                       $survey_list_query = 'SELECT su.crclm_term_id , su.section_id,su.survey_id, su.section_id , su.name, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id WHERE su.crclm_id = "'.$crclm_id.'" and su.crclm_term_id = "'. $term_id .'" group by su.section_id ORDER BY survey_id ASC';
			$survey_list_data = $this->db->query($survey_list_query);
			$survey_list_result = $survey_list_data->result_array();
			
			// $survey_user_count = 'SELECT su.survey_id,COUNT(s_usr.survey_user_id) as su_user_count FROM su_survey as su JOIN su_survey_users as s_usr ON s_usr.survey_id = su.survey_id WHERE su.crclm_id = "'.$crclm_id.'" ORDER BY survey_id ASC';
			// $survey_user_data = $this->db->query($survey_user_count);
			// $survey_user_res = $survey_user_data->row_array();
			
			$data['survey_list_result'] = $survey_list_result;
			// $data['survey_user_data'] = $survey_user_res; 
                    }else{
                        if($term_id != 0){
						$survey_list_query = 'SELECT c.crs_title, c.crs_code ,mt.mt_details_name as mt_sec,su.crclm_term_id,su.survey_id, su.name,su.section_id, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id left join master_type_details as mt ON  su.section_id = mt.mt_details_id join course as c ON c.crs_id =su.crs_id  WHERE su.crclm_id = "'.$crclm_id.'" AND su.su_for = "'.$survey_for_id.'" and su.crclm_term_id = "'. $term_id .'"  ORDER BY survey_id ASC';
						}else{ $survey_list_query = 'SELECT su.crclm_term_id , su.section_id,su.survey_id, su.name, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id WHERE su.crclm_id = "'.$crclm_id.'" AND su.su_for = "'.$survey_for_id.'"   ORDER BY survey_id ASC'; }
						$survey_list_data = $this->db->query($survey_list_query);
						$survey_list_result = $survey_list_data->result_array();
			
			// $survey_user_count = 'SELECT su.survey_id,COUNT(s_usr.survey_user_id) as su_user_count FROM su_survey as su JOIN su_survey_users as s_usr ON s_usr.survey_id = su.survey_id WHERE su.crclm_id = "'.$crclm_id.'" ORDER BY survey_id ASC';
			// $survey_user_data = $this->db->query($survey_user_count);
			// $survey_user_res = $survey_user_data->row_array();
			
			$data['survey_list_result'] = $survey_list_result;
			// $data['survey_user_data'] = $survey_user_res;
                        
                    }			
		}else{

				if($survey_for_id == -1){
				$survey_list_query = 'SELECT su.crclm_term_id,su.survey_id, su.section_id,su.name, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id WHERE su.crclm_id = "'.$crclm_id.'" AND su.su_for = 8  ORDER BY survey_id ASC';
				$survey_list_data = $this->db->query($survey_list_query);
				$survey_list_result = $survey_list_data->result_array();
				$data['survey_list_result'] = $survey_list_result;				
				}else{ 
//var_dump($crclm_id."/".$survey_for_id."/".$term_id);				
				if($term_id != "" && $crclm_id !='-1'){
					$survey_list_query = 'SELECT su.crclm_term_id,su.survey_id, su.section_id,su.name, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id WHERE su.crclm_id = "'.$crclm_id.'" AND su.su_for = 8  ORDER BY survey_id ASC';
					}else if($term_id == "" && $crclm_id !='-1'){
						$survey_list_query = 'SELECT mt.mt_details_name as mt_sec,su.crclm_term_id,su.survey_id, su.name,su.section_id, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id left join master_type_details as mt ON  su.section_id = mt.mt_details_id WHERE su.crclm_id = "'.$crclm_id.'" AND su.su_for = "'.$survey_for_id.'"   ORDER BY survey_id ASC';
					}else{
					$survey_list_query = 'SELECT mt.mt_details_name as mt_sec,su.crclm_term_id,su.survey_id, su.name,su.section_id, su.sub_title, su.template_id, su.dept_id, su.pgm_id, su.crs_id, su.su_type_id, su.su_for, su.intro_text, su.end_text, su.start_date, su.end_date, su.status, su.threshold_value, su.su_ans_tmplts, su.su_stakeholder_group, su.created_on, su.created_by, su.modified_on, su.modified_by, su.crclm_id, mtd.mt_details_name,fetch_survey_user_count(su.survey_id) as survey_user_count FROM su_survey as su JOIN master_type_details as mtd ON mtd.mt_details_id = su.su_type_id left join master_type_details as mt ON  su.section_id = mt.mt_details_id WHERE  su.su_for = "'.$survey_for_id.'"  ORDER BY survey_id ASC';
					}
				$survey_list_data = $this->db->query($survey_list_query);
				$survey_list_result = $survey_list_data->result_array();
				$data['survey_list_result'] = $survey_list_result;
				}				
		}
		return $data;
	}
	
	/* 
		Function to get the list of po or peo
	*/
	public function get_list_of_po_peo($crclm_id,$su_for,$survey_id){
		if($su_for==6){
			$peo_query = 'SELECT peo_id, peo_reference, peo_statement, peo_type_id, state_id, crclm_id, created_date, created_by, modify_date, modified_by FROM peo WHERE crclm_id = "'.$crclm_id.'"';
			$peo_data = $this->db->query($peo_query);
			$peo_list = $peo_data->result_array();
			$data['peo_po_list'] = $peo_list;
			
			$peo_indirect_attainment = 'SELECT ia_id, survey_id, crclm_id, crs_id, entity_id, actual_id, ia_percentage, attainment_level FROM indirect_attainment WHERE crclm_id = "'.$crclm_id.'" AND entity_id = 5 AND survey_id="'.$survey_id.'"';
			$peo_indirect_attainment_data = $this->db->query($peo_indirect_attainment);
			$peo_indirect_attainment_res = $peo_indirect_attainment_data->result_array();
			$data['peo_po_indirect_attainment'] = $peo_indirect_attainment_res;
			
			
		}
		if($su_for==7){
			$po_query = 'SELECT po_id, po_reference, pso_flag, po_statement, po_type_id, crclm_id, state_id, create_date, created_by, modify_date, modified_by, po_minthreshhold, po_studentthreshhold, justify FROM po WHERE crclm_id = "'.$crclm_id.'"';
			$po_data = $this->db->query($po_query);
			$po_list = $po_data->result_array();
			$data['peo_po_list'] = $po_list;
			
			$po_indirect_attainment = 'SELECT ia_id, survey_id, crclm_id, crs_id, entity_id, actual_id, ia_percentage, attainment_level FROM indirect_attainment WHERE crclm_id = "'.$crclm_id.'" AND entity_id = 6 AND survey_id="'.$survey_id.'" ';
			$po_indirect_attainment_data = $this->db->query($po_indirect_attainment);
			$po_indirect_attainment_res = $po_indirect_attainment_data->result_array();
			$data['peo_po_indirect_attainment'] = $po_indirect_attainment_res;
		}
		return $data;
	}
	
	/*
		Function to get the list of CO 
	*/
	public function get_list_of_co($crclm_id,$crs_id, $survey_id){
		$co_query = 'SELECT clo_id, clo_statement, clo_code, crclm_id, term_id, crs_id, created_by, modified_by, create_date, modify_date, cia_clo_minthreshhold, clo_studentthreshhold, justify FROM clo WHERE crclm_id="'.$crclm_id.'" AND crs_id="'.$crs_id.'"';
		$co_data = $this->db->query($co_query);
		$co_list = $co_data->result_array();
		$data['co_list'] = $co_list;
		
		$co_indirect_query = 'SELECT ia_id, survey_id, crclm_id, crs_id, entity_id, actual_id, ia_percentage, attainment_level FROM indirect_attainment WHERE crclm_id = "'.$crclm_id.'" AND crs_id = "'.$crs_id.'" AND entity_id = 11 AND survey_id="'.$survey_id.'"';
			$co_indirect_data = $this->db->query($co_indirect_query);
			$co_indirect_data_res = $co_indirect_data->result_array();
			$data['co_indirect_attainment'] = $co_indirect_data_res;
		return $data;
	}
	
	/*
		Function to insert stakeholder data.
	*/
	public function save_stakeholder_entry($su_survey_usr,$survey_id,$survey_for,$std_grp_val){
		
		// $delete_query = 'DELETE FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
		// $delete_success = $this->db->query($delete_query);
		
		
		
		$delete_upload_docs = 'DELETE FROM su_response_uploads WHERE survey_id = "'.$survey_id.'"';
		$delete_upload_success = $this->db->query($delete_upload_docs);
		
		$size = count($su_survey_usr);
		
		$survey_query = 'SELECT * FROM su_survey WHERE survey_id = "'.$survey_id.'"';
		$survey_query_data = $this->db->query($survey_query);
		$survey_query_result = $survey_query_data->row_array();
		$status = $survey_query_result['status'];
		if($status == 1){
			
			for($i=0;$i<$size;$i++){
				$this->db->insert('su_survey_users', $su_survey_usr[$i]);
			}
			// enter users to survey email table.
			$surveyUserList = $this->Survey_User->surveyUsersById($survey_id);
			
                        $survey_name = $this->survey->getSurveyName($survey_id);
                            foreach($su_survey_usr as $surveyUserDetails){
                                for($j=0;$j<$size;$j++){
                                    if($surveyUserDetails['stakeholder_detail_id'] === $su_survey_usr[$j]['stakeholder_detail_id'])
                                    {
                                            $key = $surveyUserDetails['link_key'];
                                            $user = $this->Survey_User->fetchUserDetailsByKey($key);                        
                                            $to = $user[0]['email'];
                                            $stakeholder_id = $user[0]['stakeholder_id'];
                                            $stakeholder_group_id = $user[0]['stakeholder_group_id'];
                                            $name =  $user[0]['first_name']." ".$user[0]['last_name']; 
                                            //$this->Survey->send_survey_mail($to,$key,$name,$survey_name);
                                            $this->survey->send_survey_mail($to,$key,$name,$survey_name,'',$survey_id, $survey_for, $stakeholder_id, $stakeholder_group_id);//Added by Mritunjay B S.
                                    }
                                }
                            }
							
			$data = 'true';
		}else{
			$delete_query = 'DELETE FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
			$delete_success = $this->db->query($delete_query);
			for($i=0;$i<$size;$i++){
				$this->db->insert('su_survey_users', $su_survey_usr[$i]);
			}
			
			$data = 'true';
		}
		
		
			$update_query = 'UPDATE su_survey SET su_stakeholder_group = "'.$std_grp_val.'" WHERE survey_id = "'.$survey_id.'"';
			$update_data = $this->db->query($update_query);
			
			$delete_survey_indirect_attainment = 'DELETE FROM indirect_attainment WHERE survey_id = "'.$survey_id.'"';
			$survey_result = $this->db->query($delete_survey_indirect_attainment);
			
			// Session Destroy
			// $data_one['crclm_id'] = $this->session->userdata('crclm_id');
			// $data_one['survey_id'] = $this->session->userdata('survey_id');
			// $data_one['crs_id'] = $this->session->userdata('crs_id');
			// $data_one['su_for'] = $this->session->userdata('su_for');
			
			
			
		return $data;
	}
	/* Function to Add Survey Responses */
	public function save_survey_response_entry($input_val,$actual_ids,$crclm_id,$survey_id,$survey_for,$crs_id,$status){
		
		$coloumn_name = '';
		$table_name = '';
		
		if($survey_for == 6){
			$entity_id = 5;
			$coloumn_name = 'assess_level_value,indirect_percentage';
			$table_name = 'attainment_level_crclm';
			$where = 'crclm_id = '.$crclm_id.' AND survey_id = '.$survey_id.' AND entity_id = '.$entity_id.' ';
			$assement_level_where = 'crclm_id = '.$crclm_id.'';
		}
		if($survey_for == 7){
			$entity_id = 6;
			$coloumn_name = 'assess_level_value,indirect_percentage';
			$table_name = 'attainment_level_crclm';
			$where = 'crclm_id = '.$crclm_id.' AND survey_id = '.$survey_id.' AND entity_id = '.$entity_id.' ';
			$assement_level_where = 'crclm_id = '.$crclm_id.' ';
		}
		
		if($survey_for == 8){
			$entity_id = 11;
			$coloumn_name = 'assess_level_value,indirect_percentage';
			$table_name = 'attainment_level_course';
			$where = 'crclm_id = '.$crclm_id.' AND survey_id = '.$survey_id.' AND entity_id = '.$entity_id.' AND crs_id = '.$crs_id.'';
			
			$assement_level_where = 'crclm_id = '.$crclm_id.' AND crs_id = '.$crs_id.' ';
			
			
		}
		
		for($i=0;$i<count($input_val);$i++){
			//$assessment_level = 'SELECT '.$coloumn_name.' FROM '.$table_name.' WHERE crclm_id = "'.$crclm_id.'" AND crs_id = "'.$crs_id.'"';
			
			$delete_query = 'DELETE FROM indirect_attainment WHERE '.$where.' AND actual_id = "'.$actual_ids[$i].'" ';
			$delete_data = $this->db->query($delete_query);
			
			$email_delete_query = 'DELETE FROM su_email_scheduler WHERE su_survey_id = "'.$survey_id.'" ';
			$email_delete_data = $this->db->query($email_delete_query);
			
			$assessment_level = 'SELECT '.$coloumn_name.' FROM '.$table_name.' WHERE '.$assement_level_where.'';
			$assessment_level_data = $this->db->query($assessment_level);
			$assessment_level_res = $assessment_level_data->result_array();
			
			$attainment_level = '';
			for($j=0;$j<count($assessment_level_res);$j++){
				 if($input_val[$i] >= $assessment_level_res[$j]['indirect_percentage']){
					 $attainment_level = $assessment_level_res[$j]['assess_level_value'];
				 }
				
			}
			
			$indirect_attainment_insert = array(
			'survey_id' => $survey_id,
			'crclm_id' => $crclm_id,
			'crs_id' => $crs_id,
			'entity_id' => $entity_id,
			'actual_id' => $actual_ids[$i],
			'ia_percentage' => $input_val[$i],
			'attainment_level' => $attainment_level );
			$this->db->insert('indirect_attainment', $indirect_attainment_insert);
		}
		$update_query = 'UPDATE su_survey SET status = 1, su_stakeholder_group = 0 WHERE survey_id = "'.$survey_id.'"';
		$update_table = $this->db->query($update_query);
		
		$delete_survey_users = 'DELETE FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
		$survey_users = $this->db->query($delete_survey_users);
		
		if($update_query == true){
			$data_one['crclm_id'] = $this->session->userdata('crclm_id');
			$data_one['survey_id'] = $this->session->userdata('survey_id');
			$data_one['crs_id'] = $this->session->userdata('crs_id');
			$data_one['su_for'] = $this->session->userdata('su_for');
			$data_one['status'] = $this->session->userdata('status');
			
			$data_array = array('crclm_id'=>$data_one['crclm_id'],
								'survey_id'=>$data_one['survey_id'],
								'crs_id'=>$data_one['crs_id'],
								'su_for'=>$data_one['su_for'],
								'status'=>$data_one['status']);
			
			$data = 'true';
			return $data;
		}else{
			$data = 'false';
			return $data;
		}
		
		
	}
	
	public function user_count($crclm_id,$survey_id,$crs_id,$su_for){
		$user_count_query = 'SELECT count(survey_id) as user_count FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
		$user_count_data = $this->db->query($user_count_query);
		$user_count_res = $user_count_data->row_array();
		return $user_count_res;
		
	}
	
	public function get_stakeholder_group($survey_id,$crclm_id,$survey_for){
		$select_query_data = 'SELECT su_stakeholder_group FROM su_survey WHERE survey_id ="'.$survey_id.'" AND crclm_id = "'.$crclm_id.'" AND su_for = "'.$survey_for.'"';
		$stakeholder_group_data = $this->db->query($select_query_data);
		$stakeholder_group_res = $stakeholder_group_data->row_array();
		return $stakeholder_group_res;
	}
	
	public function reset_survey_data($crclm_id,$survey_id,$survey_for,$crs_id,$status){
		$delete_query = 'DELETE FROM indirect_attainment WHERE survey_id = "'.$survey_id.'" ';
		$delete_data = $this->db->query($delete_query);
		
		$email_delete_query = 'DELETE FROM su_email_scheduler WHERE su_survey_id = "'.$survey_id.'" ';
		$email_delete_data = $this->db->query($email_delete_query);
		
		// $delete_users_one = 'DELETE FROM su_survey_response WHERE survey_id = "'.$survey_id.'"';
		// $delete_data_one = $this->db->query($delete_users_one);
		
		$delete_users = 'DELETE FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
		$delete_data = $this->db->query($delete_users);
		
		$update_query = 'UPDATE su_survey SET status = 0, su_stakeholder_group = 0 WHERE crclm_id = "'.$crclm_id.'" AND survey_id = "'.$survey_id.'" ';
		$update_data = $this->db->query($update_query);
		
		return true;
	}
	
	public function check_indirect_attainment_existance($crclm_id,$survey_id,$survey_for,$crs_id,$status){
		$status_query = 'SELECT SUM(ia_percentage) as ia_percentage_count FROM indirect_attainment WHERE survey_id = "'.$survey_id.'"';
		$status_data = $this->db->query($status_query);
		$status_res = $status_data->row_array();
		
		$user_count = 'SELECT COUNT(survey_user_id) as user_count FROM su_survey_users WHERE survey_id = "'.$survey_id.'"';
		$user_data = $this->db->query($user_count);
		$result = $user_data->row_array();
		$data['indirect_attainment'] = $status_res;
		$data['users_existance'] = $result;
		return $data;
	}
	
	public function survey_files_upload_database($file_details,$up_crclm_id,$up_survey_id,$up_crs_id,$su_for){
		
		$no_of_files = count($file_details['my_file_selector']['name']);
		if($su_for == 6){
			$entity_id = 6;
		}
		if($su_for == 7){
			$entity_id = 5;
		}
		if($su_for == 8){
			$entity_id = 11;
		}
		for($i=0; $i<$no_of_files; $i++) {
			//Get the temp file path
			$tmpFilePath = $file_details['my_file_selector']['tmp_name'][$i];
			if ($tmpFilePath != ""){
				$file_name = $file_details["my_file_selector"]["name"][$i];
				
				$insert_array = array(
					'survey_id' => $up_survey_id,
					'crclm_id' => $up_crclm_id,
					'crs_id' => $up_crs_id,
					'entity_id' => $entity_id,
					'file_path' => $file_name,
					'uploaded_by' => $this->ion_auth->user()->row()->id,
					'uploaded_date' => date('y-m-d')
				);
				$this->db->insert('su_response_uploads', $insert_array);
				/* $ext = end((explode(".", $file_name)));
				if($ext=="csv"){
					if($this->is_valid_file($file_name)){
						echo "valid";
					}else{
						echo "not valid";
					}
					$newFilePath = "./uploads/survey_uploads/" .$file_name;
					//move_uploaded_file($tmpFilePath, $newFilePath);
				}else{
					rename($tmpFilePath,"./uploads/survey_rejected_files/".$file_name);
				} */
			}
		}
		$list_of_files_query = 'SELECT * FROM su_response_uploads WHERE survey_id = "'.$up_survey_id.'" ';
		$list_of_files_data = $this->db->query($list_of_files_query);
		$list_of_files = $list_of_files_data->result_array();
		
		return $list_of_files;
		
	}
	
	public function upload_docs($crclm_id,$survey_id){
		$list_of_files_query = 'SELECT * FROM su_response_uploads WHERE survey_id = "'.$survey_id.'" AND crclm_id = "'.$crclm_id.'" ';
		$list_of_files_data = $this->db->query($list_of_files_query);
		$list_of_files = $list_of_files_data->result_array();
		
		return $list_of_files;
	}
	
	public function delete_survey_record($survey_id){
		$delete_query = 'call delete_survey('.$survey_id.')';
		$delete_data = $this->db->query($delete_query);
		return true;
	}
	
	public function course_data($crclm_id,$crs_id,$survey_id){
		$get_course_detail_query = 'SELECT cr.crclm_term_id ,sur.section_id ,cr.crs_title, cr.crs_code, crclm.crclm_name,tr.term_name,sur.name as survey_name FROM course as cr JOIN curriculum as crclm ON cr.crclm_id = crclm.crclm_id JOIN su_survey as sur on sur.survey_id = "'.$survey_id.'" JOIN crclm_terms as tr ON cr.crclm_term_id = tr.crclm_term_id AND cr.crclm_id = tr.crclm_id WHERE cr.crs_id = "'.$crs_id.'" AND cr.crclm_id = "'.$crclm_id.'" ';
		$course_data = $this->db->query($get_course_detail_query);
		$course_res = $course_data->row_array();
		return $course_res;
	}
	
	public function curriculum_data($crclm_id,$survey_id){
		$curriculum_data = 'SELECT cr.crclm_name, sur.name as survey_name FROM curriculum as cr JOIN su_survey as sur on sur.survey_id = "'.$survey_id.'" WHERE cr.crclm_id= "'.$crclm_id.'"  ';
		$curriculum_name = $this->db->query($curriculum_data);
		$curriculum_res = $curriculum_name->row_array();
		return $curriculum_res;
	}
	
	public function delete_uploaded_files($survey_id,$file_id){
		$delete_query = 'DELETE FROM su_response_uploads WHERE survey_id = "'.$survey_id.'" AND resp_id = "'.$file_id.'" ';
		$delete_data = $this->db->query($delete_query);
		return $delete_data;
	}
	public function  fetch_term($crclm_id){
	
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
	 if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {	
		$query = $this->db->query('SELECT crclm_term_id , term_name  FROM crclm_terms c where crclm_id = "' . $crclm_id . '" ');
	}else{
			$query = $this->db->query(' SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,map.course_instructor_id 
								FROM map_courseto_course_instructor AS map, crclm_terms AS ct
								WHERE map.crclm_term_id = ct.crclm_term_id
								  AND map.course_instructor_id ="'.$loggedin_user_id.'"
								   AND  ct.crclm_id = "' . $crclm_id . '" 
								   ');
	}
	return $query->result_array();
	}
	public function fetch_section_name($section_id){
	$query = $this->db->query('select * from master_type_details where mt_details_id ="'.$section_id.'"');
	return $query->result_array();
	}
}
?>