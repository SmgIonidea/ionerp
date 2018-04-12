<?php
/*
* Description	:	Attainment Levels

* Created		:	December 9th, 2015

* Author		:	 Shivaraj B

* Modification History:
* Date				Modified By				Description

----------------------------------------------------------------------------------------------*/
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attainment_level_model extends CI_Model{

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
			$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 
						 FROM program 
						 WHERE dept_id = "'.$dept_id.'" 
							AND status = 1 
						 ORDER BY CAST(pgm_acronym AS UNSIGNED)';
		}else{
			$pgm_name = 'SELECT DISTINCT pgm_id, pgm_acronym 
						 FROM program 
						 WHERE status = 1
						 ORDER BY CAST(pgm_acronym AS UNSIGNED)';
		}
		$resx = $this->db->query($pgm_name);
		return $resx->result_array();
	}

	function getCurriculumList(){
		//$crclm_name = 'SELECT DISTINCT crclm_id, crclm_name FROM curriculum WHERE status = 1 ORDER BY crclm_name ASC';
			$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name
								FROM curriculum AS c 
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))  {
		
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p
							    WHERE u.id = "'.$loggedin_user_id.'" 
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
		
		
		$result_data = $this->db->query($curriculum_list);
		$result = $result_data->result_array();
		$crclm_data['crclm_result'] = $result;
		return $result;
	}
	
	function getTermList($crclm_id){
	//	$term_name = 'SELECT crclm_term_id, term_name FROM crclm_terms WHERE crclm_id = "'.$crclm_id.'"';
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
			$term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
						  where c.crclm_term_id = ct.crclm_term_id
						  AND c.clo_owner_id="'.$loggedin_user_id.'"
						  AND c.crclm_id = "'.$crclm_id.'"';
		}else{
			$term_list_query = 'SELECT term_name, crclm_term_id 
						  FROM crclm_terms 
						  WHERE crclm_id = "' . $crclm_id . '"';
		}

		$result = $this->db->query($term_list_query);
		$data = $result->result_array();
		return $data;
	}

	function getCourseList($crclm_id,$term_id){
		$user = $this->ion_auth->user()->row()->id;
		
		if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
		 $course_name = 'SELECT crs_id,crclm_id,crclm_term_id, crs_title 
						 FROM course 
						 WHERE crclm_id = "'.$crclm_id.'" 
							AND crclm_term_id = "'.$term_id.'" 
							AND status = 1 
							AND state_id >= 4 
						 ORDER BY crs_title ASC';
		}else if ($this->ion_auth->in_group('Course Owner')) {
		 $course_name = 'SELECT DISTINCT o.crs_id, c.crs_title,c.state_id 
						 FROM course AS c, course_clo_owner AS o 
						 WHERE o.crclm_term_id = "'.$term_id.'" 
							AND o.clo_owner_id = "'.$user.'" 
							AND c.crclm_id = "'.$crclm_id.'" 
							AND o.crs_id = c.crs_id 
							AND c.state_id >= 4 
						 ORDER BY c.crs_title ASC;';
		}
		$result = $this->db->query($course_name);
		$data = $result->result_array();
		return $data;
	}    

	/*
		* Function is to get all program level assessment list.
		* @param - ------.
		* returns the list of program level assessments.
	*/

	function getProgramLevelAttainment(){
		$pgm_id = $this->input->post('pgm_id');
		$get_program_level_assess_query = $this->db->select('alp_id,pgm_id,assess_level_name,assess_level_name_alias,assess_level_value,student_percentage,cia_target_percentage,created_by,created_date,modified_by,modified_date')
		->where('pgm_id',$pgm_id)->order_by("assess_level_value", "asc")
		->get('attainment_level_program');
		return $get_program_level_assess_query->result_array();

	}
	
	function getCurriculumLevelAttainment($curclm_id){
		$get_curriculum_level_assess_query = $this->db->select('*')
		->where('crclm_id',$curclm_id)->order_by("assess_level_value", "asc")
		->get('attainment_level_crclm');
		return $get_curriculum_level_assess_query->result_array();

	}
	
	function getCourseLevelAttainment($crclm_id,$term_id,$course_id){	
		$get_course_level_assess_query = $this->db->select('*')->where('crclm_id',$crclm_id)->where('crclm_term_id',$term_id)->where('crs_id',$course_id)->order_by("assess_level_value", "asc")->get('attainment_level_course');
		$course_level_attainment_data =  $get_course_level_assess_query->result_array();
                
                $get_course_target_status  = 'SELECT target_status,target_comment FROM course WHERE crs_id = "'.$course_id.'" ';
                $course_target_status = $this->db->query($get_course_target_status);
                $course_target_res =  $course_target_status->row_array();
                
				$mte_flag_org_query = $this->db->query('select * from organisation ');
				$mte_flag_org  = $mte_flag_org_query->result_array();
				
				$mte_flag_course_query = $this->db->query('select mte_flag , cia_flag , tee_flag from course where crs_id = "'. $course_id .'"');
				$type_flag_course = $mte_flag_course_query->result_array();
				
                $data['course_level_attainment_data'] = $course_level_attainment_data;
                $data['course_target_res'] = $course_target_res;
				$data['mte_flag_org'] = $mte_flag_org;
				$data['type_flag_course'] = $type_flag_course;
                return $data;
	}
	
	public function fetch_mte_flag_status($crclm_id  , $term_id , $course_id){
	
				$mte_flag_org_query = $this->db->query('select * from organisation ');
				$mte_flag_org  = $mte_flag_org_query->result_array();
				
				$mte_flag_course_query = $this->db->query('select mte_flag , cia_flag , tee_flag from course where crs_id = "'. $course_id .'"');
				$type_flag_course = $mte_flag_course_query->result_array();

				$data['mte_flag_org'] = $mte_flag_org;
				$data['type_flag_course'] = $type_flag_course;
				return $data;
	}
	
	function insert_pgm_level_attainment($form_data){
		return $this->db->insert('attainment_level_program',$form_data);
	}
	
	function insert_perform_level_attainment($plp_data){
		return $this->db->insert('performance_level_po',$plp_data);
	}

	function get_performance_attainment_list($po_id){
		$result = $this->db->select('*')->where('po_id',$po_id)->order_by('performance_level_value','desc')->get('performance_level_po');
		return $result->result_array();
	}

	function update_program_level_attainment($form_data,$alp_id){
		$this->db->where('alp_id',$alp_id);
		return $this->db->update('attainment_level_program',$form_data);
	}
	
	function update_curriculum_level_attainment($apl_data,$alp_id){
		$this->db->where('al_crclm_id',$alp_id);
		$this->db->update('attainment_level_crclm',$apl_data);
	}
	
	function update_assessment_level_course($form_data,$acl_id,$crs_id){
		$is_updated = $this->db->update('attainment_level_course',$form_data,array('alp_id'=>$acl_id));
		
	/*  Recalculate Attainment code not required as there is an approval & skip approval flow - so it is included over there....
	// Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
		$procedure_call_cia = $this->db->query('CALL tier2_editTarget_recalculateCIA_Attainment('.$crs_id.')');
		$procedure_call_tee = $this->db->query('CALL tier2_editTarget_recalculateTEE_Attainment('.$crs_id.')');
	  
	//update CIA - course attainment finalize status flag to zero
		$ao_data_query = 'UPDATE map_courseto_course_instructor 
						  SET cia_finalise_flag = 0
						  WHERE crs_id ="' . $crs_id . '" ';
		$ao_result = $this->db->query($ao_data_query);
		
	//delete section-wise all AO occasion attainment values from tier_ii_section_clo_overall_cia_attainment attainment table
		$sec_co_ovrll_attnmt_query = ' DELETE FROM tier_ii_section_clo_overall_cia_attainment 
									  WHERE crs_id ="' . $crs_id . '" ';
		$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
	
	//delete overall Course attainment values from tier_ii_crs_clo_overall_attainment table
		$crs_ovrll_attnmt_query = ' DELETE FROM tier_ii_crs_clo_overall_attainment 
									  WHERE crs_id ="' . $crs_id . '" ';
		$crs_ovrll_attnmt_result = $this->db->query($crs_ovrll_attnmt_query); 
	*/
                
		return $is_updated;
		
	}
	
	function insert_curriculum_level_attainment($form_data){
		return $this->db->insert('attainment_level_crclm',$form_data);
	}
	
	function insert_course_level_attainment($apl_data){
		return $this->db->insert('attainment_level_course',$apl_data);
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

	function delete_program_level_attainment($alp_id){
		$query = ' DELETE FROM attainment_level_program WHERE alp_id = "'.$alp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}
	
	function delete_curriculum_level_attainment($alp_id){
		$query = ' DELETE FROM attainment_level_crclm WHERE al_crclm_id = "'.$alp_id.'" ';
		$result = $this->db->query($query);
		return $result;
	}

	function delete_assess_course_level($alp_id){
		$query = ' DELETE FROM attainment_level_course WHERE alp_id = "'.$alp_id.'" ';
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
		$result = $this->db->get_where('attainment_level_course',array('alp_id'=>$id));
		return $result->result_array();
	}
	function get_program_al_by_id($id){
		$result = $this->db->get_where('attainment_level_program',array('alp_id'=>$id));
		return $result->result_array();
	}
	function get_crclm_al_by_id($id){
		$result = $this->db->get_where('attainment_level_crclm',array('al_crclm_id'=>$id));
		return $result->result_array();
	}
	function check_crclm_attain_exits($where){
		$result = $this->db->get_where('attainment_level_course',$where);
		$res_crclm_lvl = $this->db->get_where('attainment_level_crclm',array('crclm_id'=>$where['crclm_id']));
		$n = $result->num_rows();
		$m = $res_crclm_lvl->num_rows();
		if($n <= 0 && $m != 0){
		// change this below code as two extra columns are added
			/* 
			$insert_qry = $this->db->query("
			INSERT INTO attainment_level_course (crclm_id,crclm_term_id,crs_id,assess_level_name,assess_level_name_alias,assess_level_value,cia_direct_percentage,indirect_percentage,conditional_opr,cia_target_percentage,justify) SELECT ".$where['crclm_id'].",".$where['crclm_term_id'].",".$where['crs_id'].", assess_level_name, assess_level_name_alias, assess_level_value, direct_percentage, indirect_percentage, conditional_opr,target_percentage,justify FROM attainment_level_crclm WHERE crclm_id = '".$where['crclm_id']."';"); 
			*/
		}else if($m==0&&$n==0){
			return "nodata";
		}
		return $n;
	}
        
        public function send_for_approve($crclm_id,$term_id,$crs_id){
            $update_script = 'UPDATE course SET target_status = 5 WHERE crs_id = "'.$crs_id.'" ';
            $update_data = $this->db->query($update_script);
            
            $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_title_data = $this->db->query($crs_title_query);
            $crs_title = $crs_title_data->row_array();
            
            $dept_hod_id_query = 'SELECT crclm.crclm_id,crclm.pgm_id,dept.dept_hod_id,pgm.pgm_id,dept.dept_id FROM curriculum as crclm'
                    . ' JOIN program as pgm ON pgm.pgm_id = crclm.pgm_id '
                    . ' JOIN department as dept ON dept.dept_id = pgm.dept_id '
                    . ' WHERE crclm.crclm_id = "'.$crclm_id.'" ';
            $dept_hod_id_data = $this->db->query($dept_hod_id_query);
            $dept_hod_id = $dept_hod_id_data->row_array();
            
            $url = base_url('tier_ii/attainment_level/chairman_review_pending/'.$crclm_id.'/'.$term_id.'/'.$crs_id.'/');

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '6',
                'particular_id' => $crs_id,
                'state' => '5',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $dept_hod_id['dept_hod_id'],
                'url' => $url,
                'description' => 'Targets are defined for the course :- "'.$crs_title['crs_title'].'". Approval is pending.',
            );

            $this->db->insert('dashboard', $dashboard_data);
            
            $attainment_level_course_query = 'SELECT * FROM attainment_level_course WHERE crs_id = "'.$crs_id.'" ';
            $attainmnet_level_course_data = $this->db->query($attainment_level_course_query);
            $attainment_level_course_res = $attainmnet_level_course_data->result_array();
            return $attainment_level_course_res;
        }
        
        /*
         * Function for Skip Review confirmation
         */
        public function skip_review_confirm(){
            $skip_review_query = 'SELECT skip_review FROM entity WHERE entity_id = 16 ';
            $skip_rev_data = $this->db->query($skip_review_query);
            $skip_rev_val = $skip_rev_data->row_array();
            return $skip_rev_val;
        }
        
        
        public function send_for_skip_approve($crclm_id,$term_id,$crs_id){
           $update_script = 'UPDATE course SET target_status = 7 WHERE crs_id = "'.$crs_id.'" ';
            $update_data = $this->db->query($update_script); 
            
            $crs_owner_id = 'SELECT clo_owner_id as course_owner FROM course_clo_owner WHERE crs_id = "'.$crs_id.'" ';
            $crs_owner_data = $this->db->query($crs_owner_id);
            $crs_owner = $crs_owner_data->row_array();
            
            $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_title_data = $this->db->query($crs_title_query);
            $crs_title = $crs_title_data->row_array();
            
            $update_dashboard = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 5 ';
            $update_dash = $this->db->query($update_dashboard); 
            
            $update_dashboard_one = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 6 ';
            $update_dash_one = $this->db->query($update_dashboard_one);
            
            $url = '#';

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '6',
                'particular_id' => $crs_id,
                'state' => '7',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $crs_owner['course_owner'],
                'url' => $url,
                'description' => 'Direct Attainment /Target Levels are accepted for Course :- "'.$crs_title['crs_title'].'".',
            );

            $this->db->insert('dashboard', $dashboard_data);
			
		// Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
			$procedure_call_cia = $this->db->query('CALL tier2_editTarget_recalculateCIA_Attainment('.$crs_id.')');
			$procedure_call_tee = $this->db->query('CALL tier2_editTarget_recalculateTEE_Attainment('.$crs_id.')');
		  
		//update CIA - course attainment finalize status flag to zero
			$ao_data_query = 'UPDATE map_courseto_course_instructor 
							  SET cia_finalise_flag = 0
							  WHERE crs_id ="' . $crs_id . '" ';
			$ao_result = $this->db->query($ao_data_query);
			
		//delete section-wise all AO occasion attainment values from tier_ii_section_clo_overall_cia_attainment attainment table
			$sec_co_ovrll_attnmt_query = ' DELETE FROM tier_ii_section_clo_overall_cia_attainment 
										  WHERE crs_id ="' . $crs_id . '" ';
			$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
		
		//delete overall Course attainment values from tier_ii_crs_clo_overall_attainment table
			$crs_ovrll_attnmt_query = ' DELETE FROM tier_ii_crs_clo_overall_attainment 
										  WHERE crs_id ="' . $crs_id . '" ';
			$crs_ovrll_attnmt_result = $this->db->query($crs_ovrll_attnmt_query);
            
            return true;
        }
        
        /*
         * Function to display the course target content to review and accept to the chairman
         * @param: crclm_id, term_id, crs_id
         * @return:
         */
        public function chairman_review_pending($crclm_id,$term_id,$crs_id){
            
                $meta_data = 'SELECT crclm.crclm_name, crs.crs_title, term.term_name FROM curriculum as crclm '
                        . ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$term_id.'" '
                        . ' JOIN course as crs ON crs.crs_id = "'.$crs_id.'" '
                        . ' WHERE crclm.crclm_id = "'.$crclm_id.'" ';
                $meta_data_details = $this->db->query($meta_data);
                $meta_data_res = $meta_data_details->row_array();
                $data['meta_data'] = $meta_data_res;
                
                $comment_data_query = 'SELECT target_comment FROM course WHERE crs_id = "'.$crs_id.'" ';
                $comment_data = $this->db->query($comment_data_query);
                $comment = $comment_data->row_array();
                
                $get_course_level_assess_query = $this->db->select('*')->where('crclm_id',$crclm_id)->where('crclm_term_id',$term_id)->where('crs_id',$crs_id)->order_by("assess_level_value", "asc")->get('attainment_level_course');
		$course_level_attainment_data =  $get_course_level_assess_query->result_array();
                $data['course_level_attainment_data'] = $course_level_attainment_data;
                $data['comment'] = $comment['target_comment'];
                return $data;
            
        }
        
        /*
         * Function to display the course target content to review and accept to the chairman
         * @param: crclm_id, term_id, crs_id
         * @return:
         */
        public function course_owner_rework($crclm_id,$term_id,$crs_id){
            
                $meta_data = 'SELECT crclm.crclm_name, crs.crs_title, term.term_name FROM curriculum as crclm '
                        . ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$term_id.'" '
                        . ' JOIN course as crs ON crs.crs_id = "'.$crs_id.'" '
                        . ' WHERE crclm.crclm_id = "'.$crclm_id.'" ';
                $meta_data_details = $this->db->query($meta_data);
                $meta_data_res = $meta_data_details->row_array();
                $data['meta_data'] = $meta_data_res;
                $comment_data_query = 'SELECT target_comment FROM course WHERE crs_id = "'.$crs_id.'" ';
                $comment_data = $this->db->query($comment_data_query);
                $comment = $comment_data->row_array();
                $get_course_level_assess_query = $this->db->select('*')->where('crclm_id',$crclm_id)->where('crclm_term_id',$term_id)->where('crs_id',$crs_id)->order_by("assess_level_value", "asc")->get('attainment_level_course');
		$course_level_attainment_data =  $get_course_level_assess_query->result_array();
                $data['course_level_attainment_data'] = $course_level_attainment_data;
                $data['comment'] = $comment['target_comment'];
                return $data;
            
        }
        
        /*
         * Function to accept the course target levels
         * @param: crclm_id, term_id, crs_id,
         * @return:
         */
        public function target_accept_function($crclm_id,$term_id,$crs_id, $justification){
           $update_script = 'UPDATE course SET target_status = 7, target_comment = "'.$justification.'" WHERE crs_id = "'.$crs_id.'" ';
            $update_data = $this->db->query($update_script); 
            
            $crs_owner_id = 'SELECT clo_owner_id as course_owner FROM course_clo_owner WHERE crs_id = "'.$crs_id.'" ';
            $crs_owner_data = $this->db->query($crs_owner_id);
            $crs_owner = $crs_owner_data->row_array();
            
            $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_title_data = $this->db->query($crs_title_query);
            $crs_title = $crs_title_data->row_array();
            
            $update_dashboard = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 5 ';
            $update_dash = $this->db->query($update_dashboard); 
            
            $update_dashboard_one = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 6 ';
            $update_dash_one = $this->db->query($update_dashboard_one);
            
            $url = '#';

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '6',
                'particular_id' => $crs_id,
                'state' => '7',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $crs_owner['course_owner'],
                'url' => $url,
                'description' => 'Direct Attainment /Target Levels are accepted for Course :- "'.$crs_title['crs_title'].'".',
            );

            $this->db->insert('dashboard', $dashboard_data);
            
		// Recalculate CIA & TEE Attainment w.r.t new threshold values for the QPs for which marks have been uploaded
			$procedure_call_cia = $this->db->query('CALL tier2_editTarget_recalculateCIA_Attainment('.$crs_id.')');
			$procedure_call_mte = $this->db->query('CALL tier2_editTarget_recalculateMTE_Attainment('.$crs_id.')');
			$procedure_call_tee = $this->db->query('CALL tier2_editTarget_recalculateTEE_Attainment('.$crs_id.')');
		  
		//update CIA - course attainment finalize status flag to zero
			$ao_data_query = 'UPDATE map_courseto_course_instructor 
							  SET cia_finalise_flag = 0
							  WHERE crs_id ="' . $crs_id . '" ';
			$ao_result = $this->db->query($ao_data_query);
			
		//delete section-wise all AO occasion attainment values from tier_ii_section_clo_overall_cia_attainment attainment table
			$sec_co_ovrll_attnmt_query = ' DELETE FROM tier_ii_section_clo_overall_cia_attainment 
										  WHERE crs_id ="' . $crs_id . '" ';
			$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
		
		//delete overall Course attainment values from tier_ii_crs_clo_overall_attainment table
			$crs_ovrll_attnmt_query = ' DELETE FROM tier_ii_crs_clo_overall_attainment 
										  WHERE crs_id ="' . $crs_id . '" ';
			$crs_ovrll_attnmt_result = $this->db->query($crs_ovrll_attnmt_query);
			
            return true;
        }
        
        
         /*
         * Function to accept the course target levels
         * @param: crclm_id, term_id, crs_id,
         * @return:
         */
        public function target_rework_function($crclm_id,$term_id,$crs_id, $justification){
           $update_script = 'UPDATE course SET target_status = 6, target_comment = "'.$justification.'" WHERE crs_id = "'.$crs_id.'" ';
            $update_data = $this->db->query($update_script); 
            
            $crs_owner_id = 'SELECT clo_owner_id as course_owner FROM course_clo_owner WHERE crs_id = "'.$crs_id.'" ';
            $crs_owner_data = $this->db->query($crs_owner_id);
            $crs_owner = $crs_owner_data->row_array();
            
            $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_title_data = $this->db->query($crs_title_query);
            $crs_title = $crs_title_data->row_array();
            
            $update_dashboard = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 5 ';
            $update_dash = $this->db->query($update_dashboard); 
            
            $update_dashboard_one = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 6 ';
            $update_dash_one = $this->db->query($update_dashboard_one);
            
            $url = base_url('tier_ii/attainment_level/course_owner_rework/'.$crclm_id.'/'.$term_id.'/'.$crs_id.'/');

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '6',
                'particular_id' => $crs_id,
                'state' => '6',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $crs_owner['course_owner'],
                'url' => $url,
                'description' => 'Course :- "'.$crs_title['crs_title'].'" Targets Levels are sent for the Rework.',
            );

            $this->db->insert('dashboard', $dashboard_data);
            
            return true;
        }
        
        /*
         * Function to Send for rework. Approved Target Levels
         */
        public function open_for_rework_function($crclm_id,$term_id,$crs_id){
            $update_script = 'UPDATE course SET target_status = 6 WHERE crs_id = "'.$crs_id.'" ';
            $update_data = $this->db->query($update_script);
            
            $crs_owner_id = 'SELECT clo_owner_id as course_owner FROM course_clo_owner WHERE crs_id = "'.$crs_id.'" ';
            $crs_owner_data = $this->db->query($crs_owner_id);
            $crs_owner = $crs_owner_data->row_array();
            
            $crs_title_query = 'SELECT crs_title FROM course WHERE crs_id = "'.$crs_id.'" ';
            $crs_title_data = $this->db->query($crs_title_query);
            $crs_title = $crs_title_data->row_array();
            
           // $update_dashboard = 'UPDATE dashboard SET status = 0 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 5 ';
          //  $update_dash = $this->db->query($update_dashboard); 
            
            // $update_dashboard = 'UPDATE dashboard SET status = 1 WHERE crclm_id = "'.$crclm_id.'" AND particular_id = "'.$crs_id.'" AND entity_id = 6 AND state = 6 ';
           // $update_dash = $this->db->query($update_dashboard);
            
            $url = base_url('tier_ii/attainment_level/course_owner_rework/'.$crclm_id.'/'.$term_id.'/'.$crs_id.'/');

            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '6',
                'particular_id' => $crs_id,
                'state' => '6',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $crs_owner['course_owner'],
                'url' => $url,
                'description' => 'Course :- "'.$crs_title['crs_title'].'" Targets Levels are sent for the Rework.',
            );

            $this->db->insert('dashboard', $dashboard_data);
            
            return true;
            
        }
		
				/**
	 * Function to fetch bloom level threshold details
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
	public function bloom_level_model_data($crclm_id, $term_id ,$crs_id) {

			$query = $this->db->query('SELECT CONCAT(COALESCE(cognitive_domain_flag,""),",",COALESCE(affective_domain_flag,""),",",COALESCE(psychomotor_domain_flag,""))  crs_id from course where crs_id= "'.$crs_id.'"');
		$re = $query->result_array();
		if(!empty($re)){$count = count($re[0]['crs_id']);			
			$set_data = (explode(",",$re[0]['crs_id']));
		}
		$sk=0;
		$bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
		foreach($bloom_domain_data as $bdd){
			
			if ($set_data[$sk] == 1 && $bdd['status'] == 1) {
				$bld_id [] = $bdd['bld_id'];
				
			}
		$sk++;
		}
		$Bld_id_data = implode (",", $bld_id);
		$bld_id_single = str_replace("'", "", $Bld_id_data);	
		$bloom_level_threshold_query = 'SELECT mcb.bloom_id, description, cia_bloomlevel_minthreshhold, mte_bloomlevel_minthreshhold ,tee_bloomlevel_minthreshhold 
											,bloomlevel_studentthreshhold, justify, level, bloom_actionverbs
										FROM map_course_bloomlevel AS mcb, bloom_level as bl
										WHERE bl.bld_id IN('.$bld_id_single.')
										AND mcb.crclm_id = "' . $crclm_id . '"
										AND bl.bloom_id = mcb.bloom_id';
		$bloom_level_threshold_data = $this->db->query($bloom_level_threshold_query);
		$bloom_level_threshold = $bloom_level_threshold_data->result_array();
		$data['bloom_level_threshold'] = $bloom_level_threshold;
		return $data;
	}
	
	public function save_bloom_course_wise($crclm_id, $term_id , $bl_min , $mte_min , $bl_stud , $bl_justify , $crs_id_data ,$bloom_id , $tee_min){
			$query = $this->db->query('SELECT count(map_course_blm_id) 
										FROM map_course_bloomlevel m 
										WHERE crs_id = "'.$crs_id_data.'" 
										AND crclm_id = "'.$crclm_id.'" 
										AND term_id="'.$term_id.'"');
			$re = $query->result_array();
			$kmax = sizeof($bloom_id);
				for ($k = 0; $k < $kmax; $k++) {
				$threshold_data = array(
							'crclm_id'   => $crclm_id,
							'crs_id'  	 => $crs_id_data,				
							'bloom_id'     => $bloom_id[$k],
							'cia_bloomlevel_minthreshhold' => $bl_min[$k],
							'mte_bloomlevel_minthreshhold' => $mte_min[$k],
                            'tee_bloomlevel_minthreshhold' => $tee_min[$k],
							'bloomlevel_studentthreshhold' => $bl_stud[$k],
							'justify' => $bl_justify[$k],                
							'term_id'       => $term_id,
							'created_by'    => $this->ion_auth->user()->row()->id,
							'created_date'  => date('Y-m-d') ,
							'modified_by'	=>  $this->ion_auth->user()->row()->id,
							'modified_date' =>  date('Y-m-d') );
						if($re[0]['count(map_course_blm_id)'] == 0){	
								$result = $this->db->insert('map_course_bloomlevel' , $threshold_data);
						} else { 

						$result = $this->db->query('update map_course_bloomlevel set cia_bloomlevel_minthreshhold="'.$bl_min[$k].'" , mte_bloomlevel_minthreshhold="'.$mte_min[$k].'" , tee_bloomlevel_minthreshhold = "'. $tee_min[$k].'" , bloomlevel_studentthreshhold="'.$bl_stud[$k].'" ,  justify="'.$bl_justify[$k].'" where crclm_id="'.$crclm_id.'" and term_id = "'.$term_id.'" and bloom_id = "'.$bloom_id[$k].'" and crs_id="'.$crs_id_data.'"');
						}
					}	
			if($result){return '1';} else {return '0';}
	}

	
	
		
	public function check_course_bloom_level($crclm_id, $term_id,$crs_id_data){	
			$query = $this->db->query('SELECT * FROM map_course_bloomlevel m ,bloom_level bl where crs_id = "'.$crs_id_data.'" and crclm_id = "'.$crclm_id.'" and term_id="'.$term_id.'" and m.bloom_id = bl.bloom_id');
			 $bloom_level_threshold = $query->result_array();	
			 $data['bloom_level_threshold'] = $bloom_level_threshold;
			 return $data;
	}
	
	public function fetch_bloom_detail($crclm_id, $term_id,$crs_id){
		$query_crclm = $this->db->query('SELECT c.crs_title,ct.term_name ,crclm.crclm_name FROM course c , crclm_terms ct ,curriculum crclm  where crs_id="'.$crs_id.'" and ct.crclm_term_id="'.$term_id.'" and crclm.crclm_id="'.$crclm_id.'"');
		return $query_crclm->result_array();
	}
	
}//end of class assessment_level_model