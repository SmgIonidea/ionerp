<?php
/**
* Description	:	Tier II - Import Course wise Data List View
* Created		:	29-12-2015
* Author 		:   Arihant Prasad
* Modification History:
* Date				Modified By				Description
* 
-------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import_coursewise_tee_data_model extends CI_Model {

	/**
	 * Function is to fetch department details from department table
	 * @parameters:
	 * @return: department details
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

	/**
	 * Function is to fetch program details from program table
	 * @parameters: department id
	 * @return: program details
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
	
	/**
	 * Function is to fetch curricula details from curriculum table
	 * @parameters: program id
	 * @return: curriculum details
	 */
	public function crclm_fill($pgm_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name
								FROM curriculum AS c 
								WHERE c.status = 1
									AND c.pgm_id = "'.$pgm_id.'"
								ORDER BY c.crclm_name ASC';
        } else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner'))  {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p
							    WHERE u.id = "'.$loggedin_user_id.'" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.pgm_id = "'.$pgm_id.'"
								   AND c.status = 1
								   ORDER BY c.crclm_name ASC';
        } else {
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			$curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
						FROM curriculum AS c, users AS u, program AS p ,course_clo_owner AS clo
						WHERE u.id = "'.$loggedin_user_id.'" 
						   AND u.user_dept_id = p.dept_id
						   AND c.pgm_id = p.pgm_id
						   AND c.status = 1
						   AND c.pgm_id = "'.$pgm_id.'"
						   AND u.id = clo.clo_owner_id
						   AND c.crclm_id = clo.crclm_id
						   ORDER BY c.crclm_name ASC';
		}						  
		$resx = $this->db->query($curriculum_list);
		$result = $resx->result_array();
		$crclm_data['crclm_result'] = $result;
		return $crclm_data;
		
	}
    
	/**
	 * Function is to fetch term details from curriculum term table
	 * @parameters:
	 * @return: an object
	 */
    public function term_fill($crclm_id) {
		$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
			$term_list_query = 'SELECT DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id 
								FROM course_clo_owner AS c, crclm_terms AS ct
								WHERE c.crclm_term_id = ct.crclm_term_id
								  AND c.clo_owner_id="'.$loggedin_user_id.'"
								  AND c.crclm_id = "'.$crclm_id.'"';
		} else {
			$term_list_query = 'SELECT term_name, crclm_term_id 
								FROM crclm_terms 
								WHERE crclm_id = "' . $crclm_id . '"';
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
		$user_id = $this->ion_auth->user()->row()->id;

		//if logged in user is admin, chairman or program owner
		if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
			$crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
								tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
								c.see_marks, c.ss_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status, t.term_name, 
								u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
						FROM course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
							course_type ct
						WHERE c.crclm_id = "'.$crclm_id.'"
							AND c.crclm_term_id = "'.$term_id.'"
							AND t.crclm_term_id = "'.$term_id.'"
							AND u.crs_id = c.crs_id
							AND r.crs_id = c.crs_id
							AND s.id = u.clo_owner_id
							AND ct.crs_type_id = c.crs_type_id
							AND c.status = 1
							AND c.state_id >= 4';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		} else {
			//if logged in user is course owner
			$crs_list = 'SELECT c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
								tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
								c.see_marks, c.ss_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status, t.term_name, 
								u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name
						FROM course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
							course_type ct
						WHERE c.crclm_id = "'.$crclm_id.'"
							AND c.crclm_term_id = "'.$term_id.'"
							AND t.crclm_term_id = "'.$term_id.'"
							AND u.crs_id = c.crs_id
							AND u.clo_owner_id = "' . $user_id . '" 
							AND r.crs_id = c.crs_id
							AND s.id = u.clo_owner_id
							AND ct.crs_type_id = c.crs_type_id
							AND c.status = 1
							AND c.state_id >= 4';
			$crs_list_result = $this->db->query($crs_list);
			$crs_list_data = $crs_list_result->result_array();
			$crs_list_return['crs_list_data'] = $crs_list_data;
		}
		
		return $crs_list_return;
    }
	
	/**
	 * Function is to fetch qp details
	 * @parameters: curriculum id, term id, course id
	 * @return: qpd_id
	 */
    public function qp_id_details($crclm_id, $term_id, $crs_id) {
        $qp_details_query = 'SELECT qpd_id
							 FROM qp_definition
							 WHERE qpd_type = 5
								 AND qp_rollout >= 1
								 AND crs_id = "'.$crs_id.'" ';
        $qp_result = $this->db->query($qp_details_query);
        $qp_data = $qp_result->result_array();
		
		//check qpd id exists or not
		if(!empty($qp_data)) {
			//return existing qpd id
			return $qp_data[0]['qpd_id'];
		}
		
		return false;
	}
	
	/**
	 * Function is to fetch qp details
	 * @parameters: curriculum id, term id, course id
	 * @return: qpd_id
	 */
    public function qp_insert_details($crclm_id, $term_id, $crs_id) {
		//logged in user id and current date
		$logged_in_uid = $this->ion_auth->user()->row()->id;
		$date = date('y-m-d');
		
        $qp_details_query = 'SELECT qpd_id
							 FROM qp_definition
							 WHERE qpd_type = 5
								AND qp_rollout >= 1 
								AND crs_id = "'.$crs_id.'" ';
        $qp_result = $this->db->query($qp_details_query);
        $qp_data = $qp_result->result_array();
		
		//query to fetch max marks that was inserted while creating course
		$course_name_query = 'SELECT see_marks, ss_marks
							  FROM course
							  WHERE crs_id = "'.$crs_id.'" ';
        $course_name_result = $this->db->query($course_name_query);
        $course_name_data = $course_name_result->result_array();
		$see_marks = empty($course_name_data[0]['see_marks'])? 0 : $course_name_data[0]['see_marks'];
		$ss_marks =  empty($course_name_data[0]['ss_marks'])? 0 : $course_name_data[0]['ss_marks'];
		$max_marks = $see_marks + $ss_marks;
		
		//query to fetch COs related to course id
		$co_query = 'SELECT clo_id
					 FROM clo
					 WHERE crs_id = "'.$crs_id.'" ';
        $co_result = $this->db->query($co_query);
        $co_list = $co_result->result_array();
		
		//check qpd id exists or not
		if(!empty($qp_data)) {
			//return existing qpd id
			return $qp_data[0]['qpd_id'];
		} else {
			//individual
			//qp definition table
			$qp_defn_data_query = 'INSERT INTO qp_definition(qpd_type, qp_rollout, crclm_id, crclm_term_id, crs_id, qpd_title, qpd_num_units, qpd_max_marks,
										created_by, created_date, modified_by, modified_date) 
								   VALUES (5, 1, "'.$crclm_id.'", "'.$term_id.'", "'.$crs_id.'", "TEE Individual Assessment", 1, "'.$max_marks.'", 
										"'.$logged_in_uid.'", "'.$date.'", "'.$logged_in_uid.'", "'.$date.'")';
			$qp_defn_result = $this->db->query($qp_defn_data_query);
			
			//newly inserted qp id
			$last_insert_qp_defn_id = $this->db->insert_id();
			$data['pk_qpd_id'] = $last_insert_qp_defn_id;
			
			//qp unit definition table
			$qp_unit_defn_data_query = 'INSERT INTO qp_unit_definition(qpd_id, qp_unit_code, qp_total_unitquestion, 
											qp_attempt_unitquestion, qp_utotal_marks, FM) 
										VALUES ("'.$last_insert_qp_defn_id.'", "Unit - I", 1, 1, "'.$max_marks.'", 0)';
			$qp_unit_defn_result = $this->db->query($qp_unit_defn_data_query);
			
			//newly inserted qp unit id
			$last_insert_qp_unit_defn_id = $this->db->insert_id();
			
			//qp main question definition table
			$qp_mainquest_defn_data_query = 'INSERT INTO qp_mainquestion_definition(qp_unitd_id, qp_mq_code, qp_subq_code, 
												qp_content, qp_subq_marks) 
											 VALUES ("'.$last_insert_qp_unit_defn_id.'", 1, "Total_Marks", "Individual Assessment", "'.$max_marks.'")';
			$qp_mainquest_defn_result = $this->db->query($qp_mainquest_defn_data_query);
			
			//newly inserted qp main question id
			$last_insert_qp_mainquest_defn_id = $this->db->insert_id();
			$data['main_qp_id'] = $last_insert_qp_mainquest_defn_id;
			
			//get size of CO and PO list
			$co_size = sizeof($co_list);
			
			//insert CO into qp mapping definition table
			for($i = 0; $i < $co_size; $i++ ) {
				$insert_query = 'INSERT INTO qp_mapping_definition(qp_mq_id, entity_id, actual_mapped_id, mapped_marks, mapped_percentage) 
								 VALUES("'.$last_insert_qp_mainquest_defn_id.'", 11, "'.$co_list[$i]['clo_id'].'", "'.$max_marks.'", 100)';
				$this->db->query($insert_query);
			}
			
			return $last_insert_qp_defn_id;
		}
    }
	
	/**
	 * Function is to fetch .csv file header from qp definition, unit definition and main question definition tables
	 * @parameters: question paper id
	 * @return: .csv file header
	 */
	public function attainment_template($qpd_id, $csv_flag = true) {
		//convert to array to csv 
        $this->load->helper('array_to_csv');

		//fetch question no. with marks for template header
		$header_query = "SELECT if(qp_subq_code='Total_Marks', 1, 0) as total_flag, concat(qp_subq_code,'(',q.qp_subq_marks,'m)') as qstn_mark
						 FROM qp_definition AS qd, 
							qp_unit_definition AS qu, 
							qp_mainquestion_definition AS q, 
							su_student_stakeholder_details su_stk
						 WHERE qu.qpd_unitd_id = q.qp_unitd_id 
							AND qu.qpd_id = qd.qpd_id 
							AND qd.qpd_id = '$qpd_id' 
							AND qd.qp_rollout >= 1 
							AND su_stk.crclm_id=qd.crclm_id
							 AND status_active = 1
						 GROUP BY qp_subq_code
						 ORDER BY CAST(student_usn AS UNSIGNED),student_usn,CAST(qp_subq_code AS UNSIGNED),qp_subq_code ASC";
		$header = $this->db->query($header_query)->result_array();
                
		if($header) {
			//prepare header            
            $total_flag = $header[0]['total_flag'];
			
			if(count($header) == 1 && $total_flag == 1) {
				//when their only 1 question - default question paper with single question as qp code = total marks column
				$header = array_column($header, 'qstn_mark');
				array_unshift($header, 'student_name','student_usn');				
				//array_push($header);
			} else {
				//when question paper is created (user defined question paper) - multiple questions
				$header = array_column($header, 'qstn_mark');
				array_unshift($header, 'student_name','student_usn');
				array_push($header,'Total_Marks');
			}
                    
			//fetch the student name and usn
			$std_usn_query = "SELECT concat(if(su_stk.title is null,'',su_stk.title),' ',if(su_stk.first_name is null,'',su_stk.first_name),' ',if(su_stk.last_name is null,'',su_stk.last_name)) as student_name, su_stk.student_usn
							  FROM qp_definition AS qd, 
								qp_unit_definition AS qu, 
								qp_mainquestion_definition AS q, 
								su_student_stakeholder_details su_stk
							  WHERE qu.qpd_unitd_id = q.qp_unitd_id 
								AND qu.qpd_id = qd.qpd_id 
								AND qd.qpd_id = '$qpd_id' 
								AND qd.qp_rollout >= 1 
								AND su_stk.crclm_id=qd.crclm_id
								 AND status_active = 1
							  GROUP BY student_usn
							  ORDER BY student_usn,qp_subq_code ASC;";
			$student_det = $this->db->query($std_usn_query)->result_array();

			//if header lengh and student column lenght is differ
			$new_keys = array();
			if($student_det) {
				$keys = array_keys($student_det[0]);
				foreach($header as $head) {
					if(!in_array($head, $keys)) {
						$new_keys[$head] = '';
					}
				}
				//if there is difference fill with blank " "
				foreach($student_det as $key => $detail) {
					$student_det[$key] = array_merge($student_det[$key], $new_keys);
				}
			}

			//add header for csv file
			array_unshift($student_det, $header);
			
			if($csv_flag){
				return array_to_csv($student_det);
			}
			
            return $student_det;
        }
        return false;
    }

    /**
	 * Function is to fetch curriculum name & course code which will be used as .csv file name
	 * @parameters: course id and qpd id
	 * @return: .csv file name
	 */
	public function file_name_query($crs_id, $qpd_id) {
		$file_name_query = 'SELECT c.crs_code, curr.crclm_name
						    FROM qp_definition AS qpd, course AS c, curriculum AS curr
						    WHERE qpd.crs_id = "'.$crs_id.'"
								AND qpd.qpd_id = "'.$qpd_id.'"
								AND qpd.crs_id = c.crs_id
								AND curr.crclm_id = c.crclm_id';
		$file_name_data = $this->db->query($file_name_query);
		$file_name = $file_name_data->result_array();
		
		if(!empty($file_name)) {			
			//TEE exam
			$final_exam = $this->lang->line('entity_see_full');
			$export_file_name = $file_name[0]['crclm_name'] . '_' . $file_name[0]['crs_code'] . '_' . $final_exam;
			
			return $export_file_name;
		} else {
			//Unknown course id results in unknown file name
			return $file_name = 5;
		}
	}
	
	/**
	 * Function is to fetch qp details
	 * @parameters: curriculum id, term id, course id
	 * @return: qpd_id
	 */
    public function qp_details($crs_id) {
        $term_name_query = 'SELECT qpd_id
							FROM qp_definition
							WHERE qpd_type = 5
								AND qp_rollout >= 1 
								AND crs_id = "'.$crs_id.'"';
        $qp_result = $this->db->query($term_name_query);
        $qp_data = $qp_result->result_array();
		
		if(!empty($qp_data)) {
			//if qpd id exists
			$qpd_id = $qp_data[0]['qpd_id'];
			return $qpd_id;
		} else {
			//if qpd id does not exist
			return 0;
		}
	}
	
	/* Function is to fetch department details, program details, curriculum details, term details
		from department, program, curriculum and curriculum term tables
	 * @parameter - department id, program id, curriculum id, term id
	 * @returns - department details, program details, curriculum details, term details
	 */	
	public function fetch_all_details($dept_id, $prog_id, $crclm_id, $term_id, $crs_id, $ao_id = NULL) {
		$dept_prog_query = 'SELECT d.dept_name, p.pgm_acronym
							FROM program AS p, department as d
							WHERE d.dept_id = "' . $dept_id . '"
								AND p.pgm_id = "' . $prog_id . '"';
		$dept_prog_data = $this->db->query($dept_prog_query);
		$dept_prog_name = $dept_prog_data->result_array();
		$data['dept_prog_name'] = $dept_prog_name;
		
		$crclm_term_crs_query = 'SELECT cr.crclm_name, ct.term_name, c.crs_title, c.crs_code, c.see_marks
								 FROM curriculum AS cr, crclm_terms AS ct, course AS c
								 WHERE cr.crclm_id = "' . $crclm_id . '"
									AND ct.crclm_term_id = "' . $term_id . '"
									AND c.crs_id = "' . $crs_id . '"';
		$crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
		$crclm_term_crs_name = $crclm_term_crs_data->result_array();
		$data['crclm_term_crs_name'] = $crclm_term_crs_name;
			
		return $data;
	}
	
	/**
	 * Function is to discard temporary table once student details are inserted into student assessment table
		or discard temporary table on cancel
	 * @parameters: course id
	 * @return: boolean value
	 */
	public function drop_temp_table($crs_id) {
		$this->load->dbforge();
		$temp_table_name = "temp_upload_marks" . $crs_id;
		
		$this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");
		
		return true;
	}
	
	/**
     * Function is to fetch course code and insert student details (from temporary table) into student assessment (main) table
     * @parameters: curriculum id, term id, course id, question paper id
     * @return: boolean
     */
	public function insert_into_student_table($qpd_id, $crs_id , $crclm_id , $term_id ) {
        //fetch course title
        $temp_table_name = "temp_upload_marks" . $crs_id;

        //check if temporary table exists
        $temp = $this->db->query("SHOW TABLES LIKE '$temp_table_name'");
        $temp_remarks = $temp->result_array();

        //if temporary table does not exist return
        if (empty($temp_remarks)) {
            return '2';
        }

        //fetch remarks from temporary table
        $temp_remarks_query = 'SELECT Remarks
							   FROM ' . $temp_table_name . '
							   WHERE Remarks IS NOT NULL';
        $temp_remarks_data = $this->db->query($temp_remarks_query);
        $temp_remarks = $temp_remarks_data->result_array();

        if (!empty($temp_remarks)) {
            return '0';
        }

        //if student data already exists in the main table, then delete and insert once again
        $student_assessment_fetch_query = 'SELECT q.qp_mq_id
										   FROM qp_definition AS qd, qp_unit_definition AS qu, qp_mainquestion_definition AS q
										   WHERE qu.qpd_unitd_id = q.qp_unitd_id
											  AND qu.qpd_id = qd.qpd_id
											  AND qd.qpd_id = "' . $qpd_id . '"
											  AND qd.qp_rollout >= 1';
        $student_assessment_fetch_data = $this->db->query($student_assessment_fetch_query);
        $student_assessment_fetch = $student_assessment_fetch_data->result_array();

        $student_data_size = sizeof($student_assessment_fetch);

        for ($i = 0; $i < $student_data_size; $i++) {
            $student_data_delete_query = 'DELETE FROM student_assessment
										  WHERE qp_mq_id = "' . $student_assessment_fetch[$i]['qp_mq_id'] . '"';

			$student_data_delete_result = $this->db->query($student_data_delete_query);
		}
		
		//fetch qp id, main qp id and sub question qp id
		$fetch_insert_element_query = 'SELECT qp_mq_id, qp_mq_code, qp_subq_code
									   FROM qp_mainquestion_definition q
									   LEFT JOIN qp_unit_definition qu ON qu.qpd_unitd_id = q.qp_unitd_id
									   WHERE qu.qpd_id = "'.$qpd_id.'"';
		$fetch_insert_element_data = $this->db->query($fetch_insert_element_query);
		$fetch_insert_element = $fetch_insert_element_data->result_array();
		
		$total_elements = sizeof($fetch_insert_element);
		
        $field_names = $this->db->list_fields($temp_table_name);

        //if only 1 question is created then that question will be considered as total_marks
        if (count($field_names) == 4) {
            $total_marks_field = $field_names[3];
        } else {
            $total_marks_field = 'total_marks';
        }

		$cia_tee = "'TEE'";
		$qpd_type = 5;
        //fetch qp details, student usn, total marks (temp table) & insert into student_assessment, student_assessment_totalmarks tables
        $crs_co_level_attnmt = $this->db->query("call student_assessment_total_marks(" . $crs_id . ", " . $qpd_type . ", " . $qpd_id . ", '" . $total_marks_field . "')");

		$cia_tee = "'TEE'";
		$qpd_type = 5;

        //drop temporary table
        $this->drop_temp_table($crs_id);

        //on data insert to student assessment table set roll out to 2
        $insert_into_rollout_query = 'UPDATE qp_definition
									  SET qp_rollout = 2
									  WHERE qpd_id = "' . $qpd_id . '"';
        $insert_complete = $this->db->query($insert_into_rollout_query);     
        
		//used to calculate CO attainment
		$section_co_attainment = $this->db->query("call tier_ii_create_section_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " , NULL , " . $qpd_type . " , NULL , " . $qpd_id . ", " . $cia_tee . ", NULL)");
        $co_section_data = $section_co_attainment->result_array();
	
		//used to calculate student CO attainment
		$student_CO_Attainment = $this->db->query("call tier_ii_create_student_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " , NULL , " . $qpd_type . " , NULL , " . $qpd_id . ", " . $cia_tee . ", NULL)");
        $student_attainment_data = $student_CO_Attainment->result_array();
        return '1';
    }
	
	/**
	 * Function is to create temporary student data table using imported .csv file
	 * @parameters: course id, file name, file header details & question paper id
	 * @return: temporary table details
	 */
	public function load_csv_data_temp_table($crs_id, $file_name, $name, $file_header_array, $qpd_id) {
		/***** Start Create table Structure *****/
        $this->load->dbforge();
        if(!empty($file_header_array)){
			$col_array = array();
			$temp_table_name = "temp_upload_marks" . $crs_id;
			
			$this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

			$temp_table_structure = $unique_prim_field = $set_primary = '';
			$temp_table_structure.='CREATE TABLE ' . $temp_table_name . '(';
			$temp_table_structure.='Remarks TEXT DEFAULT NULL,';
			
			foreach ($file_header_array as $field_name) {
				$temp_table_structure.='`'.trim($field_name) . '`  ' . 'VARCHAR(200)' . ',';
				$col_array[] = '`'.trim($field_name) . '`';
			}
		
			$temp_table_structure = substr($temp_table_structure, 0, -1);
			$temp_table_structure.=')';
				   
			$this->db->query($temp_table_structure);

			/***** End Create table Structure *****/
			
			//upload data into temporary table 
			//while uploading files in Linux machine change "LOAD DATA LOCAL INFILE" TO "LOAD DATA INFILE" (if required)
			$path = './uploads/'.$name;
			$data_query = $this->db->query("LOAD DATA LOCAL INFILE '".$path."'
							  IGNORE INTO TABLE ".$temp_table_name."
							  FIELDS TERMINATED BY ','ENCLOSED BY '\"'
							  LINES TERMINATED BY '".PHP_EOL."'
							  IGNORE 1 LINES
							  (".implode(', ', $col_array) .")
							");
			$query = $this->db->query("update ". $temp_table_name ." set total_marks = replace(total_marks,'\r','')");
			
			
			 //get the count of values in the temporary table that was created
            $count_query = $this->db->query('SELECT COUNT(*) AS rowexist FROM ' . $temp_table_name);
           				
			foreach ($file_header_array as $field_name) {
                if (strpos($field_name, '(') !== false) {
                    $field_name_array = explode('(', $field_name);
				
                    $this->db->query('ALTER TABLE ' . $temp_table_name . ' CHANGE COLUMN `' . $field_name . '` `' . $field_name_array[0] . '` VARCHAR(200) DEFAULT NULL');
                }
            }							
										
			/* call function for data validation*/
			$this->validate_data($temp_table_name, $qpd_id , $file_header_array);
			
			return $temp_table_name;
		}
	}
	
	/**
	 * Function is to validate temporary table details before uploading
	 * @parameters: temporary table name & question paper id
	 * @return: validation errors
	 */
	public function validate_data($temp_table_name, $qpd_id, $file_header_array) {	
		//check if student usn block is left empty
	
		$this->db->query('UPDATE ' . $temp_table_name . '
						  SET Remarks = "Student USN is missing"
						  WHERE student_usn IS NULL 
							OR student_usn = ""');
		
		//check for duplicate student usn entry							
		$this->db->query("UPDATE " . $temp_table_name . "
						  SET Remarks = CONCAT(COALESCE(remarks,''), 'Repeated entry for unique field student USN')
						  WHERE student_usn IN (SELECT student_usn 
                                                    FROM (SELECT $temp_table_name.student_usn 
                                                        FROM $temp_table_name
                                                        INNER JOIN (SELECT student_usn 
                                                        FROM $temp_table_name
                                                        GROUP BY student_usn having count(student_usn)>1) dup 
                                                        ON $temp_table_name.student_usn = dup.student_usn 
                                                              AND $temp_table_name.student_usn !='' ) A ) ");
						  
		//should not cross maximum marks
		$subq_code_max_marks_query = 'SELECT qp_subq_code, qp_subq_marks , qpd_max_marks
									  FROM qp_definition AS qd, qp_unit_definition AS qu, qp_mainquestion_definition AS q
									  WHERE qu.qpd_unitd_id = q.qp_unitd_id
										AND qu.qpd_id = qd.qpd_id
										AND qd.qpd_id = "' . $qpd_id . '"
										AND qd.qp_rollout >= 1';
		$subq_code_max_marks_data = $this->db->query($subq_code_max_marks_query);
		$subq_code_max_marks = $subq_code_max_marks_data->result_array();


		if(count($file_header_array) == 3) {
				 $check_query="UPDATE " . $temp_table_name . " tc
                                            SET Remarks = CONCAT(COALESCE(remarks,''),'A Total secured marks needs to be integer or decimal value. ')
                                            WHERE `total_marks` "." not  REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$' ";

			$this->db->query($check_query);

			// To validate Total marks with marx marks 
					foreach($subq_code_max_marks as $subq_code_marks) {
	
						$this->db->query("UPDATE " . $temp_table_name . " tc
							  JOIN qp_mainquestion_definition q ON q.qp_subq_code = '" . $subq_code_marks['qp_subq_code'] . "'
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . str_replace("Q_No_", "", $subq_code_marks['qp_subq_code']) . " is greater than max marks. ')
							  WHERE `" . $subq_code_marks['qp_subq_code'] . "` > " . $subq_code_marks['qp_subq_marks']);
					}
			
		} else {
	
				$check_query="UPDATE " . $temp_table_name . " tc
                                            SET Remarks = CONCAT(COALESCE(remarks,''),'A Total secured marks needs to be integer or decimal value. ')
                                            WHERE `total_marks` "." not  REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$' ";

				$this->db->query($check_query);
			foreach($subq_code_max_marks as $subq_code_marks) {
				// to validate individual question 
				$this->db->query("UPDATE " . $temp_table_name . " tc
							  JOIN qp_mainquestion_definition q ON q.qp_subq_code = '" . $subq_code_marks['qp_subq_code'] . "'
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . str_replace("Q_No_", "", $subq_code_marks['qp_subq_code']) . " is greater than max marks. ')
							  WHERE `" . $subq_code_marks['qp_subq_code'] . "` > " . $subq_code_marks['qp_subq_marks']);
			
				$this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . $subq_code_marks['qp_subq_code'] . " needs to be integer or decimal value. ')
							  WHERE `" . $subq_code_marks['qp_subq_code'] . "` not REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$'");
							  
										 
			}
			
			$this->db->query("UPDATE " . $temp_table_name . " tc							 
							  SET Remarks = CONCAT(COALESCE(remarks,''),'The TOTAL MARKS is greater than Maximum Marks. ')
							  WHERE `total_marks` > " . $subq_code_max_marks[0]['qpd_max_marks']);
		}
	}
	
	/**
	 * Function is to fetch student details from temporary table
	 * @parameters: file details
	 * @return: student USN & student marks
	 */
	public function fetch_student_marks($results) {
		$fetch_student_marks_query = "SELECT *
									  FROM $results";
		$fetch_student_marks_data = $this->db->query($fetch_student_marks_query);
		$fetch_student_marks = $fetch_student_marks_data->result_array();
		$data['fetch_student_marks'] = $fetch_student_marks;
				
		return $data;
	}
	
	/**
	 * Function is to perform student data analysis
	 * @parameters: question paper id
	 * @return: 
	 */
	public function dataAnalysis($qpd_id) {
		$this->load->library('database_result');
		$marks = $this->db->query("call getAttainmentAnalysis(".$qpd_id.")");
		$data = $marks->result_array();
		$this->database_result->next_result();
		
		return $data;
	}
	
	/**
	 * Function is to fetch students data from student assessment table
	 * @parameters: question paper id
	 * @return: student USN & student marks
	 */
    public function student_marks($qpd_id) {
        $this->load->library('database_result');
        $marks = $this->db->query("call getStudentMarks(" . $qpd_id . ")");
        $data = $marks->result_array();
        $this->database_result->next_result();

        return $data;
    }
	
	/**
	 * Function is to discard student data from main table (student assessment table)
	 * @parameters: question paper id
	 * @return: boolean value
	 */
	public function drop_main_table($qpd_id) {
		$student_assessment_fetch_query = 'SELECT q.qp_mq_id
										   FROM qp_definition AS qd, qp_unit_definition AS qu, qp_mainquestion_definition AS q
										   WHERE qu.qpd_unitd_id = q.qp_unitd_id
											  AND qu.qpd_id = qd.qpd_id
											  AND qd.qpd_id = "'.$qpd_id.'"
											  AND qd.qp_rollout >= 1';
		$student_assessment_fetch_data = $this->db->query($student_assessment_fetch_query);
		$student_assessment_fetch = $student_assessment_fetch_data->result_array();
	
		$student_data_size = sizeof($student_assessment_fetch);
	
		for($i = 0; $i < $student_data_size; $i++) {
			$student_data_delete_query = 'DELETE FROM student_assessment
										  WHERE qp_mq_id = "' . $student_assessment_fetch[$i]['qp_mq_id'] . '"';
			$student_data_delete_result = $this->db->query($student_data_delete_query);
		}
		
		//fetch crs id details to delete from tier2 clo ao attainment table
		$tee_table_query = 'SELECT crs_id
							FROM qp_definition
							WHERE qpd_id = "' . $qpd_id . '"';
		$tee_table_data = $this->db->query($tee_table_query);
		$tee_table_fetch = $tee_table_data->result_array();
		
		$crs_id = $tee_table_fetch[0]['crs_id'];
		
		//delete AO occassion attainment values from tier1 clo ao attainment table
		$sec_ao_attnmt_query = 'DELETE FROM tier_ii_section_clo_ao_attainment 
								WHERE crs_id = "' . $crs_id . '"
									AND assess_type = 5';
		$sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
		
		//on data discard from student assessment table set roll out to 1
		$insert_into_rollout_query = 'UPDATE qp_definition
									  SET qp_rollout = 1
									  WHERE qpd_id = "' . $qpd_id . '"';
		$insert_complete = $this->db->query($insert_into_rollout_query);
		
		return true;
	}

	public function qp_marks($crs_id, $qpd_id) {
		$marks = 'SELECT qud.qpd_unitd_id, qmd.qp_subq_marks, qd.qpd_max_marks
				  FROM qp_mainquestion_definition AS qmd, qp_unit_definition AS qud, qp_definition AS qd
				  WHERE qd.crs_id = "'.$crs_id.'" AND qd.qpd_id = "'.$qpd_id.'"
						AND qud.qpd_id = "'.$qpd_id.'"
						AND qmd.qp_unitd_id = qud.qpd_unitd_id';
		$marks_data = $this->db->query($marks);
		$final_marks = $marks_data->result_array();
		
		return $final_marks;
	}

	public function save_marks_db($test,$unit_id, $qpd_id) { 
		$test_data = 'UPDATE qp_mainquestion_definition 
					  SET qp_subq_marks= "'.$test.'" 
					  WHERE qp_unitd_id = "'.$unit_id.'"';
					  
		$query = 'UPDATE qp_unit_definition 
				  SET qp_utotal_marks = "'.$test.'" 
				  WHERE qpd_unitd_id = "'.$unit_id.'"';
				  
		$qry = 'UPDATE qp_definition 
				SET qpd_max_marks = "'.$test.'" 
				WHERE qpd_id = "'.$qpd_id.'"';
                
		$fetch_mq_ids_query = 'SELECT qp_mq_id 
							   FROM qp_mainquestion_definition 
							   WHERE qp_unitd_id = "'.$unit_id.'"';
		$mq_ids_data = $this->db->query($fetch_mq_ids_query);
		$mq_ids = $mq_ids_data->result_array();
		
		if(!empty($mq_ids)) {
			foreach($mq_ids as $mq_id) {
				$mapping_update_query = 'UPDATE qp_mapping_definition 
										 SET mapped_marks= "'.$test.'" 
										 WHERE qp_mq_id = "'.$mq_id['qp_mq_id'].'" ';  
				$mapped_update_data = $this->db->query($mapping_update_query);
			}
		}
		$test_query = $this->db->query($query);
		$test_qry = $this->db->query($qry);
		$test_value =  $this->db->query($test_data); 
		
		return $test_value; 
	}

	/**
	 * Function is to fetch file_name from qp_tee_upload
	 * @parameters: course id
	 * @return: file_name
	 */
	public function filename($crs_id){
		$qp_file = 'SELECT file_name FROM qp_tee_upload WHERE crs_id = "'.$crs_id.'"';
		$qp_file_val = $this->db->query($qp_file);
		$qp_file_result = $qp_file_val->num_rows();
		return $qp_file_result;
		
	}

	/**
	 * Function is to fetch qp details
	 * @parameters: curriculum id, term id, course id
	 * @return: qpd_id
	 */
	public function qp_detail($crs_id) {
		$term_name_query = 'SELECT qpd_id 
							FROM qp_definition 
							WHERE qpd_type = 5
								AND qp_rollout >= 1 
								AND crs_id = "'.$crs_id.'"';
		$qp_result = $this->db->query($term_name_query);
		$qp_data = $qp_result->result_array(); 
		
		if(!empty($qp_data)) {
			//if qpd id exists
			$qpd_id = $qp_data[0]['qpd_id'];
			return $qpd_id;
		} else {
			//if qpd id does not exist
			return 2;
		}
	}

	/**
	 * Function is to crclm_term_id from qp_definition table
	 * @parameters: crs_id, qpd_type
	 * @return: 
	 */
	public function show($crs_id, $qpd_type){
		$result = $this->db->query('SELECT crclm_term_id 
									FROM qp_definition 
									WHERE qpd_type="'.$qpd_type.'" 
										AND crs_id= "'.$crs_id.'" '); 
		$val = $result->result_array();
		return $val;
	}

	/**
	 * Function is to insert the data into the qp_tee_upload table
	 * @parameters: file_name
	 * @return: 
	 */
	public function add_file($file_name) {
		$query = $this->db->insert('qp_tee_upload', $file_name);
		return $query;	
	}

	/**
	 * Function is to fetch the qpd_id from qp_definition table
	 * @parameters: crclm_id, term_id, crs_id, qpd_type
	 * @return: qpd_id
	 */
	public function fetch_qp_details($crclm_id, $term_id, $crs_id, $qpd_type) {
        	$qp_query = 'SELECT qpd_id 
						 FROM qp_definition 
						 WHERE crs_id = "'.$crs_id.'" 
							AND qpd_type="'.$qpd_type.'" 
							AND qp_rollout >= 1 ';
        	$qp_value = $this->db->query($qp_query);
        	$qp_val = $qp_value->result_array();
		return $qp_val;
	}

	/**
	 * Function is to fetch the file_name from qp_tee_upload table
	 * @parameters: crs_id, qpd_id
	 * @return: file name
	 */
	public function upload_qp($crs_id, $qpd_id) {
		$qp_upload = 'SELECT file_name 
					  FROM qp_tee_upload 
					  WHERE crs_id = "'.$crs_id.'" 
						AND qpd_id = "'.$qpd_id.'"';
		$qp_upload_result = $this->db->query($qp_upload);
		$qp_upload_value = $qp_upload_result->result_array();
		return $qp_upload_value;
	}

	/**
	 * Function is to update file_name from qp_tee_upload
	 * @parameters: course id, datetime, 
	 * @return: 
	 */
	public function update_file($update_new_file, $crs_id) {
		$update = 'UPDATE qp_tee_upload 
				   SET file_name = "'.$update_new_file.'" 
				   WHERE crs_id = "'.$crs_id.'"'; 
		$update_qry = $this->db->query($update);
		return true;
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
                
                    $target_approve_query = 'SELECT tee_target_percentage FROM attainment_level_course WHERE crs_id = "'.$crs_id.'" ';
                    $target_data = $this->db->query($target_approve_query);
                    $target_data_res = $target_data->result_array();
                    $counter = 0;
                    foreach($target_data_res as $target_data){
                        if($target_data['tee_target_percentage'] == NULL ){
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
                    $data['org_type'] = 'org2';
                
            }else if($org_data_res[0]['org_type'] == 'TIER-I'){
                
                    $threshold_query = 'SELECT IF (tee_course_minthreshhold is NULL,0,tee_course_minthreshhold) as tee_course_minthreshhold  FROM course WHERE crs_id = "'.$crs_id.'" ';
                    $threshold_data = $this->db->query($threshold_query);
                    $threshold_data_res = $threshold_data->row_array();
                    if($threshold_data_res['tee_course_minthreshhold']==0){
                       $data['target_or_threshold_size'] = 0; 
                    }else{
                       $data['target_or_threshold_size'] = $threshold_data_res['tee_course_minthreshhold'];
                    }
                    $data['org_type'] = 'org1';
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
    
    /**
	 * Function is to fetch students data from student assessment table for CIA and TEE consoliadted
	 * @parameters: question paper id
	 * @return: student USN & student marks
	 */
    public function consolidated_student_marks($crclm_id, $term_id, $crs_id, $qpd_id) {
        $this->load->library('database_result');
        $marks = $this->db->query("call getCiaTeeConsoliatedStudentMarks(" . $crclm_id . ', ' . $term_id . ', ' . $crs_id . ")");
        $data = $marks->result_array();
        $this->database_result->next_result();

        return $data;
    }
    
     
    /**
     * Function is to fetch students data from student assessment table
     * @parameters: crclm id, term id, course id and qpdid;
     * @return: student USN & student name
     */
    public function get_assessment_student($qpd_id, $crclm_id = null, $term_id = null, $crs_id = null) {
        $this->load->library('database_result');
        $marks = $this->db->query("call getCiaTeeConsoliatedStudentNames(" . $crclm_id . ', ' . $term_id . ', ' . $crs_id . ")");
        $data = $marks->result_array();
        $this->database_result->next_result();

        return $data;
    }
}
?>
