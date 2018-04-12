<?php
/**
* Description	:	Import CIA Data List View
* Created		:	09-10-2014. 
* Author 		:   Abhinay B.Angadi
* Modification History:
* Date				Modified By				Description
 13-11-2014		  Arihant Prasad		Permission setting, indentations, comments & Code cleaning
 22-01-2015			Jyoti				Modified to View QP of CIA
 28-08-2015		  Arihant Prasad		Provision for entering student-wise marks for individual type
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class import_cia_data_model extends CI_Model {

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
/* 		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		$crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "'.$pgm_id.'" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
		$resx = $this->db->query($crclm_name);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data; */
		
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if ($this->ion_auth->is_admin()) {
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
	} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

	    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
	    $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				ORDER BY c.crclm_name ASC';
	}else{			 
//			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
//				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
//				WHERE u.id = "' . $loggedin_user_id . '" 
//				AND u.user_dept_id = p.dept_id
//				AND c.pgm_id = p.pgm_id
//				AND u.id = clo.clo_owner_id
//				AND c.crclm_id = clo.crclm_id
//				AND c.status = 1
//				AND c.pgm_id = "'.$pgm_id.'"
//				ORDER BY c.crclm_name ASC';
                        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , map_courseto_course_instructor AS map
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = map.course_instructor_id
				AND c.crclm_id = map.crclm_id
				AND c.status = 1
				AND c.pgm_id = "'.$pgm_id.'"
				ORDER BY c.crclm_name ASC';
	}

	$curriculum_list_data = $this->db->query($curriculum_list);
	$crclm_data['crclm_result'] =  $curriculum_list_data->result_array();
	return $crclm_data; 
		
	}
    
	/* Function is used to fetch the term id & name from crclm_terms table.
	* @param - curriculum id.
	* @returns- a array of values of the term details.
	*/
    public function term_fill($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
//       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
//					  where c.crclm_term_id = ct.crclm_term_id
//					  AND c.clo_owner_id="'.$loggedin_user_id.'"
//					  AND c.crclm_id = "'.$curriculum_id.'"';
         $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,map.course_instructor_id from map_courseto_course_instructor AS map, crclm_terms AS ct
					  where map.crclm_term_id = ct.crclm_term_id
					  AND map.course_instructor_id = "'.$loggedin_user_id.'"
					  AND map.crclm_id = "'.$curriculum_id.'"';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
				
	}
        $result = $this->db->query($term_list_query);
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
//			$crs_list = 'SELECT ao.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
//								tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
//								c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, ao.status AS a_status, t.term_name, 
//								u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
//						FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
//							course_type ct, assessment_occasions AS ao
//						WHERE ao.crclm_id = "'.$crclm_id.'" 
//						AND ao.term_id = "'.$term_id.'" 
//						AND c.status = 1
//						AND c.state_id >= 4
//						AND t.crclm_term_id = "'.$term_id.'"  
//						AND u.crs_id = c.crs_id 
//						AND ao.crs_id = c.crs_id 
//						AND r.crs_id = c.crs_id 
//						AND s.id = u.clo_owner_id 
//						AND ct.crs_type_id = c.crs_type_id 
//						GROUP BY ao.crs_id';
                     $crs_list = 'SELECT map_ci.course_instructor_id, map_ci.crs_id, map_ci.section_id,
                                crs.crs_title,crs_code, crs.crs_id, crs.crs_mode,crs.see_marks, crs.total_marks, crs.contact_hours, crs.see_duration, crs.lect_credits,	
                                crs.tutorial_credits, crs.practical_credits, crs.self_study_credits, crs.total_credits, crs.cie_marks,
                                crs_type.crs_type_name,
                                mtd.mt_details_name as section_name,
                                usr.username,usr.title,usr.first_name,usr.last_name,usr.id,
                                ao.status as a_status, CONCAT_ws("",map_ci.crs_id,map_ci.section_id) as ao_crs_section_id
                                FROM map_courseto_course_instructor as map_ci
                                LEFT JOIN course as crs ON crs.crs_id = map_ci.crs_id
                                LEFT JOIN users as usr ON usr.id = map_ci.course_instructor_id
                                LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map_ci.section_id
                                LEFT JOIN course_type as crs_type ON crs_type.crs_type_id = crs.crs_type_id
                                LEFT JOIN assessment_occasions as ao ON ao.crs_id = map_ci.crs_id and ao.section_id = map_ci.section_id
                                WHERE crs.crclm_id = "'.$crclm_id.'"
                                AND crs.crclm_term_id = "'.$term_id.'"
                                AND crs.status=1
								AND ao.mte_flag = 0
                                AND crs.state_id >= 4 GROUP BY ao_crs_section_id';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		} else {
//			$crs_list = 'SELECT DISTINCT ao.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
//								tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
//								c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, ao.status  AS a_status, t.term_name, 
//								u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
//						FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
//							course_type ct, assessment_occasions AS ao
//						WHERE ao.crclm_id = "'.$crclm_id.'" 
//						AND ao.term_id = "'.$term_id.'" 
//						AND c.status = 1
//						AND c.state_id >= 4
//						AND t.crclm_term_id = "'.$term_id.'"  
//						AND u.crs_id = c.crs_id 
//						AND ao.crs_id = c.crs_id 
//						AND u.clo_owner_id = "' . $user . '" 
//						AND r.crs_id = c.crs_id 
//						AND s.id = u.clo_owner_id 
//						AND ct.crs_type_id = c.crs_type_id ';
                     $crs_list = 'SELECT map_ci.course_instructor_id, map_ci.crs_id, map_ci.section_id,
                                crs.crs_title,crs_code, crs.crs_id, crs.crs_mode,crs.see_marks, crs.total_marks, crs.contact_hours, crs.see_duration, crs.lect_credits,	
                                crs.tutorial_credits, crs.practical_credits, crs.self_study_credits, crs.total_credits, crs.cie_marks,
                                crs_type.crs_type_name,
                                mtd.mt_details_name as section_name,
                                usr.username,usr.title,usr.first_name,usr.last_name,usr.id,
                                ao.status as a_status, CONCAT_ws("",map_ci.crs_id,map_ci.section_id) as ao_crs_section_id
                                FROM map_courseto_course_instructor as map_ci
                                LEFT JOIN course as crs ON crs.crs_id = map_ci.crs_id
                                LEFT JOIN users as usr ON usr.id = map_ci.course_instructor_id
                                LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = map_ci.section_id
                                LEFT JOIN course_type as crs_type ON crs_type.crs_type_id = crs.crs_type_id
                                LEFT JOIN assessment_occasions as ao ON ao.crs_id = map_ci.crs_id and ao.section_id = map_ci.section_id
                                WHERE crs.crclm_id = "'.$crclm_id.'"
                                AND crs.crclm_term_id = "'.$term_id.'"
                                AND map_ci.course_instructor_id = "'.$user.'"
                                AND crs.status=1
								AND ao.mte_flag = 0
                                AND crs.state_id >= 4 GROUP BY ao_crs_section_id';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		}
		
        return $crs_list_return;
    }
	
	/* Function is to fetch department name, program name, curriculum name, term name
	 * @param - department id, program id, curriculum id, term id
	 * @returns - department name, program name, curriculum name, term name
	 */	
	public function fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id) {
            
		$dept_prog_query = 'SELECT d.dept_name, p.pgm_acronym
							FROM program AS p, department as d
							WHERE d.dept_id = "' . $dept_id . '"
								AND p.pgm_id = "' . $prog_id . '"';
		$dept_prog_data = $this->db->query($dept_prog_query);
		$dept_prog_name = $dept_prog_data->result_array();
		$data['dept_prog_name'] = $dept_prog_name;
		
		$crclm_term_crs_query = 'SELECT cr.crclm_name, ct.term_name, c.crs_title
								 FROM curriculum AS cr, crclm_terms AS ct, course AS c
								 WHERE cr.crclm_id = "' . $crclm_id . '"
									AND ct.crclm_term_id = "' . $term_id . '"
									AND c.crs_id = "' . $crs_id . '"';
		$crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_name = $crclm_term_crs_data->result_array();
		$data['crclm_term_crs_name'] = $crclm_term_crs_name;
		
		return $data;
	}
	
	/* Function is used to fetch the course details from course & CIA tables.
	* @param - course id.
	* @returns- a array of values of the course details.
	*/
        public function fetch_cia_marks($dept_id, $prog_id, $crclm_id, $term_id, $crs_id,$section_id) {
			$cia = 'SELECT DISTINCT ao.ao_id, ao.qpd_id, ao.ao_type_id, ao.ao_name, ao.ao_description, aom.ao_method_name, mtd.mt_details_name,
							ao.pi_code, ao.weightage, ao.max_marks, ao.avg_cia_marks
					FROM assessment_occasions AS ao, ao_method AS aom, master_type_details AS mtd,qp_definition q
					WHERE ao.crs_id = "' . $crs_id . '"
						AND ao.crclm_id = "' . $crclm_id . '"
						AND ao.term_id = "' . $term_id . '"
                        AND ao.section_id = "'.$section_id.'"
						AND ao.ao_method_id = aom.ao_method_id
						AND ao.ao_type_id = mtd.mt_details_id 						
						AND q.cia_model_qp = 0  AND q.qp_rollout > 0 ORDER BY ao.ao_name';
        $result = $this->db->query($cia);
        $cia_result = $result->result_array();
        $data['cia_result'] = $cia_result;
		
		$dept_prog_query = 'SELECT d.dept_name, p.pgm_acronym
							FROM program AS p, department as d
							WHERE d.dept_id = "' . $dept_id . '"
								AND p.pgm_id = "' . $prog_id . '"';
		$dept_prog_data = $this->db->query($dept_prog_query);
		$dept_prog_name = $dept_prog_data->result_array();
		$data['dept_prog_name'] = $dept_prog_name;
		
		$crclm_term_crs_query = 'SELECT cr.crclm_name, ct.term_name, c.crs_title, c.crs_code
								 FROM curriculum AS cr, crclm_terms AS ct, course AS c
								 WHERE cr.crclm_id = "' . $crclm_id . '"
									AND ct.crclm_term_id = "' . $term_id . '"
									AND c.crs_id = "' . $crs_id . '"';
		$crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_name = $crclm_term_crs_data->result_array();
		$data['crclm_term_crs_name'] = $crclm_term_crs_name;
                
                $section_name_query = 'SELECT mt_details_id, mt_details_name as section_name FROM master_type_details WHERE mt_details_id = "'.$section_id.'" ';
                $section_data = $this->db->query($section_name_query);
                $section_name = $section_data->row_array();
                $data['section_name'] = $section_name;
		return $data;
    }
	
	/* Function is used to fetch the course details from course & CIA tables.
	* @param - course id.
	* @returns- a array of values of the course details.
	*/
    public function update_cia_marks($ao_id, $avg_cia_marks) {
		$user_id = $this->ion_auth->user()->row()->id;
        $date = date('Y-m-d');
		$count = count($ao_id);
		for($i=0; $i<$count; $i++) {
				$query = ' UPDATE assessment_occasions 
							SET avg_cia_marks =  "'.$avg_cia_marks[$i].'",
								modified_by = "'.$user_id.'", 
								modified_date = "'.$date.'",
								status = 1 
							WHERE ao_id = "'.$ao_id[$i].'" ';
				$result = $this->db->query($query);	
		}
		return true;
	}
	
	/* Function is used to fetch the CIA details from CIA tables.
	* @param - course id.
	* @returns- a array of values of the course details.
	*/
    public function get_cia_details($crs_id,$section_id) {
	
		$crclm_term_crs_query = 'SELECT c.crs_title, c.crs_code
								 FROM course AS c
								 WHERE c.crs_id = "' . $crs_id . '"';
		$crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_name = $crclm_term_crs_data->result_array();
		$data['crs_data'] = $crclm_term_crs_name;
		
		$cia = 'SELECT DISTINCT ao.ao_id, ao.qpd_id,ao.crclm_id,ao.term_id,ao.crs_id, ao.ao_name, ao.ao_description, 
                                        aom.ao_method_id, aom.ao_method_name, mtd.mt_details_name,
					ao.pi_code, ao.weightage, ao.max_marks, ao.avg_cia_marks
					FROM assessment_occasions AS ao, ao_method AS aom, master_type_details AS mtd,qp_definition as q
					WHERE ao.crs_id = "' . $crs_id . '"
                                        AND ao.section_id = "'.$section_id.'"
					AND ao.ao_method_id = aom.ao_method_id
					AND ao.ao_type_id = mtd.mt_details_id					
					AND q.cia_model_qp = 0 ORDER BY ao.ao_name';
        $result = $this->db->query($cia);
        $cia_result = $result->result_array();
        $data['cia_result'] = $cia_result;
		
		return $data;
	}
        
        /*
     * Function to get the organisation type
     * @param: 
     * @return:
     */
    public function get_organisation_type($crclm_id,$term_id,$crs_id){
            $org_type = 'SELECT org_type FROM organisation';
            $org_data = $this->db->query($org_type);
            $org_data_res = $org_data->result_array();
            if($org_data_res[0]['org_type'] == 'TIER-II'){
                    $target_approve_query = 'SELECT cia_target_percentage FROM attainment_level_course WHERE crs_id = "'.$crs_id.'" ';
                    $target_data = $this->db->query($target_approve_query);
                    $target_data_res = $target_data->result_array();
                    $counter = 0;
					
					$query = 	$this->db->query('select target_status from course where crs_id = "'. $crs_id .'"');
					$query_re = $query->result_array();
					$target_status = 0;
					if(!empty($query_re)){ $target_status = $query_re[0]['target_status'];} 
					
                    foreach($target_data_res as $target_data){
					//|| $target_data['cia_target_percentage'] == 0
                        if($target_data['cia_target_percentage'] == NULL  ){
                            $counter =0;
                            break;
                        }else{
                            $counter = 1;
                        }
                        
                    }
                    if($counter>0){
                        $data['target_or_threshold_size'] = $counter;
                    }else{
                        $data['target_or_threshold_size'] = $counter;
                    }
					$data['course_target_status'] = $target_status;
                    $data['org_type'] = 'org2';
                
            }else if($org_data_res[0]['org_type'] == 'TIER-I'){
                
                    $threshold_query = 'SELECT IF(cia_course_minthreshhold is NULL,0,cia_course_minthreshhold) as cia_course_minthreshhold FROM course WHERE crs_id = "'.$crs_id.'" ';
                    $threshold_data = $this->db->query($threshold_query);
                    $threshold_data_res = $threshold_data->row_array();
                    if($threshold_data_res['cia_course_minthreshhold']== 0){
                       $data['target_or_threshold_size'] = 0; 
                    }else{
                       $data['target_or_threshold_size'] = $threshold_data_res['cia_course_minthreshhold'];
                    }
                    $data['org_type'] = 'org1';
					$data['course_target_status'] = '';
                }
            return $data;
    }
    /*
     * Function to check the student is uploaded for the respective curriculum or not
     * @param: crclm_id
     * @return:
     */
    public function check_student_uploaded_or_not($crclm_id){
       $students_query = 'SELECT COUNT(ssd_id) as student_count FROM su_student_stakeholder_details WHERE crclm_id = "'.$crclm_id.'" AND status_active = 1  ';
       $student_data = $this->db->query($students_query);
       $student_res = $student_data->row_array();
       return $student_res;
       
    }
	
	public function check_main_question($qpd_id){
		$query = $this->db->query('SELECT * FROM qp_mainquestion_definition q join  qp_unit_definition as qu on q.qp_unitd_id = qu.qpd_unitd_id
								   join qp_definition  as qp on qp.qpd_id = qu.qpd_id
								  where qp.qpd_id = "'. $qpd_id .'"');
		return $query->result_array();
	
	}
    
        /*
     * Function to get the save rubrics details
     * @param: ao_method_id
     * @return: List of Rubrics
     */
    public function get_saved_rubrics($ao_method_id){
        $get_criteria_list = 'SELECT crt.rubrics_criteria_id, crt.criteria, clo_code_concat(crt.rubrics_criteria_id) as co_code FROM ao_rubrics_criteria as crt
                              WHERE crt.ao_method_id = "'.$ao_method_id.'" ';
        $criteria_details = $this->db->query($get_criteria_list);
        $criteria_res = $criteria_details->result_array();
        
        $get_criteria_range_query = 'SELECT rng.rubrics_range_id, rng.ao_method_id, rng.criteria_range_name, rng.criteria_range, dsc.rubrics_range_id, dsc.rubrics_criteria_id, dsc.criteria_description FROM ao_rubrics_range as rng '
                . ' JOIN ao_rubrics_criteria_desc as dsc ON dsc.rubrics_range_id = rng.rubrics_range_id '
                . ' WHERE rng.ao_method_id = "'.$ao_method_id.'" '; 
        $get_criteria_range_data = $this->db->query($get_criteria_range_query);
        $criteria_range = $get_criteria_range_data->result_array();
        
        $get_rubrics_range = 'SELECT rng.rubrics_range_id, rng.ao_method_id, rng.criteria_range_name, rng.criteria_range FROM ao_rubrics_range as rng'
                . ' WHERE rng.ao_method_id = "'.$ao_method_id.'" GROUP BY rng.criteria_range';
        $rubric_range_data = $this->db->query($get_rubrics_range);
        $rubrics_range = $rubric_range_data->result_array();
        
        $data['criteria_clo'] = $criteria_res;
        $data['criteria_desc'] = $criteria_range;
        $data['rubrics_range'] = $rubrics_range;
        return $data;
        
    }
    
/*
* Function to check the question paper is created for the rubrics occasion or not
* @param: ao_id
* @return
*/
public function check_question_paper_created($ao_id){
  $check_qp_existance = 'SELECT qpd_id, rubrics_qp_status FROM assessment_occasions WHERE ao_id = "'.$ao_id.'" ';
  $check_qp = $this->db->query($check_qp_existance);
  $chek_qp_res = $check_qp->row_array();
  return $chek_qp_res;
}

  /*
   * Function to get the Meta Data for PDF report
   * @param: ao_id
   * @return:
   */
  public function pdf_report_meta_data($ao_id){
      $get_the_meta_data_query = 'SELECT occ.crclm_id, occ.term_id, occ.crs_id, occ.section_id, occ.ao_description, cr.crclm_name,term.term_name, crs.crs_title, sec.mt_details_name FROM assessment_occasions as occ'
              . ' JOIN curriculum as cr ON cr.crclm_id = occ.crclm_id'
              . ' JOIN crclm_terms as term ON term.crclm_term_id = occ.term_id'
              . ' JOIN course as crs ON crs.crs_id = occ.crs_id '
              . ' JOIN master_type_details as sec ON sec.mt_details_id = occ.section_id'
              . ' WHERE occ.ao_id = "'.$ao_id.'" ';
      $meta_data_data = $this->db->query($get_the_meta_data_query);
      $meta_data = $meta_data_data->row_array();
      return $meta_data;
  } 

}
?>