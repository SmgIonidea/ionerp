<?php
/**
 * Description	:	Import Assessment Data List View
 * Created		:	30-09-2014. 
 * Author 		:   Abhinay B.Angadi
 * Modification History:
 * Date				Modified By				Description
 * 01-10-2014	   Arihant Prasad		Import and Export features,
										Permission setting, Added file headers, function headers,
										indentations, comments, variable naming,
										function naming & Code cleaning
 * 27-09-2016	   Bhagyalaxmi S S												
 -------------------------------------------------------------------------------------------------*/
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class import_assessment_data_model extends CI_Model {

    /**
     * Function is to fetch department details from department table
     * @parameters:
     * @return: department details
     */
    public function dept_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->is_admin()) {
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
     * Function is to fetch curriculum details
     * @parameters: program id
     * @return: curriculum details
     */
    public function crclm_fill_middle($pgm_id) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $crclm_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "' . $pgm_id . '" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
        $resx = $this->db->query($crclm_name);
        $result = $resx->result_array();
        $crclm_data['crclm_result'] = $result;
        return $crclm_data;
    }

    /**
     * Function is to fetch curricula details from curriculum table
     * @parameters: program id
     * @return: curriculum details
     */
    public function crclm_fill($pgm_id) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        
        if ($this->ion_auth->is_admin()) {
            $curriculum_list_query = 'SELECT DISTINCT crclm_id, crclm_name 
									  FROM curriculum AS c
									  WHERE status = 1
									  ORDER BY c.crclm_name ASC';
        } elseif ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
					   FROM curriculum AS c
					   WHERE c.pgm_id = "' . $pgm_id . '" 
						  AND c.status = 1 
						  ORDER BY c.crclm_name ASC';
        } else {
            $curriculum_list_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				AND c.pgm_id = "' . $pgm_id . '"
				ORDER BY c.crclm_name ASC';
        }
        $resx = $this->db->query($curriculum_list_query);
        $result = $resx->result_array();
        $crclm_data['crclm_result'] = $result;
        return $crclm_data;
    }

    /**
     * Function is to fetch term details from curriculum term table
     * @parameters:
     * @return: an object
     */
    public function term_fill($curriculum_id) {
	
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.course_instructor_id from map_courseto_course_instructor AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.course_instructor_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$curriculum_id.'" ORDER BY ct.crclm_term_id';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
				
	}
		
        $result = $this->db->query($term_list_query);
        $data  =  $result->result_array();
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
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $crs_list = 'SELECT q.qpd_id, q.qp_rollout, c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
									tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
									c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status, t.term_name, 
									u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name, q.rubrics_flag
							FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
								course_type ct, qp_definition AS q
							WHERE c.crclm_id = "' . $crclm_id . '"
							AND c.crclm_term_id = "' . $term_id . '"
							AND t.crclm_term_id = "' . $term_id . '"
							AND q.crclm_id = "' . $crclm_id . '"
							AND q.crclm_term_id = "' . $term_id . '"
							AND q.crs_id = c.crs_id
							AND q.qp_rollout >= 1
							AND q.qpd_type = 5
							AND u.crs_id = c.crs_id
							AND r.crs_id = c.crs_id
							AND s.id = u.clo_owner_id
							AND ct.crs_type_id = c.crs_type_id ';
            $crs_list_result = $this->db->query($crs_list);
            $crs_list_data = $crs_list_result->result_array();
            $crs_list_return['crs_list_data'] = $crs_list_data;
        } else {
            $crs_list = 'SELECT q.qpd_id, q.qp_rollout, c.crs_id, c.crs_title, c.crs_type_id, c.crs_acronym, c.crs_code, lect_credits, 
									tutorial_credits, practical_credits, c.self_study_credits, c.total_credits, c.cie_marks, 
									c.see_marks, c.total_marks, c.contact_hours, c.see_duration, c.crs_mode, c.status, t.term_name, 
									u.clo_owner_id, r.validator_id, s.title, s.username, s.first_name, s.last_name, ct.crs_type_name, q.rubrics_flag
							FROM  course AS c, crclm_terms AS t, course_clo_owner AS u, course_clo_validator AS r, users AS s, 
								course_type ct, qp_definition AS q
							WHERE c.crclm_id = "' . $crclm_id . '"
							AND c.crclm_term_id = "' . $term_id . '"
							AND t.crclm_term_id = "' . $term_id . '"
							AND q.crclm_id = "' . $crclm_id . '"
							AND q.crclm_term_id = "' . $term_id . '"
							AND q.crs_id = c.crs_id
							AND q.qp_rollout >= 1
							AND q.qpd_type = 5
							AND u.crs_id = c.crs_id
							AND u.clo_owner_id = "' . $user_id . '" 
							AND r.crs_id = c.crs_id
							AND s.id = u.clo_owner_id
							AND ct.crs_type_id = c.crs_type_id ';
            $crs_list_result = $this->db->query($crs_list);
            $crs_list_data = $crs_list_result->result_array();
            $crs_list_return['crs_list_data'] = $crs_list_data;
        }
        return $crs_list_return;
    }

    /**
     * Function is to fetch .csv file header from qp definition, unit definition and main question definition tables
     * @parameters: question paper id
     * @return: .csv file header
     */
    public function attainment_template($qpd_id, $section_id = null, $csv_flag = true) {
     
	  //convert to array to csv 
        $this->load->helper('array_to_csv');

        //fetch question no with the mark for template header
        $header_query = "
            SELECT concat(qp_subq_code,'(',q.qp_subq_marks,'m)') as qstn_mark
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

        if ($header) {
            //prepare header            
            $header = array_column($header, 'qstn_mark');
            array_unshift($header, 'student_name', 'student_usn');
            array_push($header, 'total_marks');

            //fetch the student name and usn
            $std_usn_query = "
                SELECT 
                    concat(IF(su_stk.title is null,'',su_stk.title),' ',IF(su_stk.first_name is null,'',su_stk.first_name),' ',IF(su_stk.last_name is null,'',su_stk.last_name)) as student_name
                    ,su_stk.student_usn
                    FROM qp_definition AS qd, 
                    qp_unit_definition AS qu, 
                    qp_mainquestion_definition AS q, 
                    su_student_stakeholder_details su_stk
                    WHERE qu.qpd_unitd_id = q.qp_unitd_id 
                    AND qu.qpd_id = qd.qpd_id 
                    AND qd.qpd_id = '$qpd_id' 
                    AND qd.qp_rollout >= 1 
                    AND su_stk.crclm_id=qd.crclm_id
					 AND status_active = 1 ";
            if ($section_id) {
                $std_usn_query.=" AND su_stk.section_id='$section_id'";
            }
            $std_usn_query.="
                GROUP BY student_usn
                ORDER BY LENGTH(student_usn),student_usn,qp_subq_code ASC;";
            $student_det = $this->db->query($std_usn_query)->result_array();
            //if header lengh and student column lenght is differ
            $new_keys = array();
            if ($student_det) {
                $keys = array_keys($student_det[0]);
                foreach ($header as $head) {
                    if (!in_array($head, $keys)) {
                        $new_keys[$head] = '';
                    }
                }

                //if there is difference fill with blank " "
                foreach ($student_det as $key => $detail) {
                    $student_det[$key] = array_merge($student_det[$key], $new_keys);
                }
            }
            //add header for csv file
            array_unshift($student_det, $header);            
            if ($csv_flag) {
                return array_to_csv($student_det);
            }

            return $student_det;
        }
        return false;
    }

    /**
     * Function is to fetch curriculum name & course code which will be used as .csv file name
     * @parameters: course id
     * @return: .csv file name
     */
    public function file_name_query($crs_id, $qpd_id, $ao_id = NULL) {
	
        $file_name_query = 'SELECT c.crs_code, curr.crclm_name
						    FROM qp_definition AS qpd, course AS c, curriculum AS curr
						    WHERE qpd.crs_id = "' . $crs_id . '"
								AND qpd.qpd_id = "' . $qpd_id . '"
								AND qpd.qp_rollout >= 1
								AND qpd.crs_id = c.crs_id
								AND curr.crclm_id = c.crclm_id';
        $file_name_data = $this->db->query($file_name_query);
        $file_name = $file_name_data->result_array();
        if ($ao_id != NULL) {
            $assessment_occasions_query = 'SELECT ao.ao_description, ao.section_id, mtd.mt_details_id, mtd.mt_details_name as section_name
                                                        FROM assessment_occasions as ao
                                                        LEFT JOIN master_type_details as mtd ON mtd.mt_details_id = ao.section_id
                                                        WHERE ao.crs_id = "' . $crs_id . '"
                                                        AND ao.qpd_id = "' . $qpd_id . '"
                                                             AND ao.ao_id = "' . $ao_id . '"';
            $assessment_occasions_data = $this->db->query($assessment_occasions_query);
            $assessment_occasions_name = $assessment_occasions_data->result_array();
        }

        if (!empty($file_name)) {
            if ($ao_id != NULL) {
                //CIA test
                $export_file_name = $file_name[0]['crclm_name'] . '_' . $file_name[0]['crs_code'] . '_' . $assessment_occasions_name[0]['section_name'] . '_' . $assessment_occasions_name[0]['ao_description'];
            } else {
                //TEE test
                $final_exam = $this->lang->line('entity_see_full');
                $export_file_name = $file_name[0]['crclm_name'] . '_' . $file_name[0]['crs_code'] . '_' . $final_exam;
            }
            return $export_file_name;
        } else {
            //Unknown course id results in unknown file name
            return $file_name = 5;
        }
    }

    /**
     * Function is to create temporary student data table using imported .csv file
     * @parameters: course id, file name, file header details & question paper id
     * @return: temporary table details
     */
    public function load_csv_data_temp_table($crs_id, $file_name, $name, $file_header_array, $qpd_id, $ao_id = NULL) {
        /*         * *** Start Create table Structure **** */
        $this->load->dbforge();
        if (!empty($file_header_array)) {
            $col_array = array();
            $temp_table_name = "temp_upload_marks" . $crs_id;

            $this->db->query("DROP TABLE IF EXISTS " . $temp_table_name . "");

            $temp_table_structure = $unique_prim_field = $set_primary = '';
            $temp_table_structure.='CREATE TABLE ' . $temp_table_name . '(';
            $temp_table_structure.='Remarks TEXT DEFAULT NULL,';

            foreach ($file_header_array as $field_name) {
                $temp_table_structure.='`' . trim($field_name) . '`  ' . 'VARCHAR(200)' . ',';
                $col_array[] = '`' . trim($field_name) . '`';
            }

            $temp_table_structure = substr($temp_table_structure, 0, -1);
            $temp_table_structure.=')';

            $this->db->query($temp_table_structure);

            /*             * *** End Create table Structure **** */

            //upload data into temporary table 
            //while uploading files in Linux machine change "LOAD DATA LOCAL INFILE" TO "LOAD DATA INFILE" (if required)
            $path = './uploads/' . $name;
            $this->db->query("LOAD DATA LOCAL INFILE '" . $path . "'
							  IGNORE INTO TABLE " . $temp_table_name . "
							  FIELDS TERMINATED BY ','  ENCLOSED BY '\"'
							  LINES TERMINATED BY '" . PHP_EOL . "'
							  IGNORE 1 LINES
							  (" . implode(', ', $col_array) . ")
							");

            //get the count of values in the temporary table that was created
            $count_query = $this->db->query('SELECT COUNT(*) AS rowexist FROM ' . $temp_table_name);
           

            foreach ($file_header_array as $field_name) {
                if (strpos($field_name, '(') !== false) {
                    $field_name_array = explode('(', $field_name);
                    $this->db->query('ALTER TABLE ' . $temp_table_name . ' CHANGE COLUMN `' . $field_name . '` `' . $field_name_array[0] . '` VARCHAR(200) DEFAULT NULL');
                }
            }
            /* call function for data validation */
            $this->validate_data($temp_table_name, $qpd_id, $ao_id);

            return $temp_table_name;
        }
    }

    /*
     * Fuctioto fetch the  section id
     */

    public function fetch_section_id($ao_id) {
        $section_query = 'SELECT section_id  FROM  assessment_occasions WHERE ao_id= "' . $ao_id . '"';
        $section_data = $this->db->query($section_query);
        $section_id = $section_data->row_array();
        return $section_id['section_id'];
    }

    /**
     * Function is to validate temporary table details before uploading
     * @parameters: temporary table name & question paper id
     * @return: validation errors
     */
    public function validate_data($temp_table_name, $qpd_id, $ao_id) {
        $field_names = $this->db->list_fields($temp_table_name);

        //if only 1 question is created then that question will be considered as total_marks
        if (count($field_names) == 4) {
            $total_marks_field = $field_names[3];
        } else {
            $total_marks_field = 'total_marks';
        }

        //check if total marks column is left empty
        $this->db->query('UPDATE ' . $temp_table_name . '
						  SET Remarks = "Total Marks is missing. "
						  WHERE ' . $total_marks_field . ' IS NULL 
							OR ' . $total_marks_field . ' = ""');

        //check if student usn column is left empty
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

        //total marks should not cross maximum marks
        if ($ao_id) {
            //for CIA
            $this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Total marks is greater than max marks. ')
							  WHERE " . $total_marks_field . " > (SELECT qpd_max_marks
									  FROM qp_definition
									  WHERE qpd_id = " . $qpd_id . ")");
        } else {
            //for TEE
            $this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Total marks is greater than max marks. ')
							  WHERE " . $total_marks_field . " > (SELECT qpd_max_marks
									  FROM qp_definition
									  WHERE qpd_id = " . $qpd_id . ")");
        }

        //each question marks should not cross maximum marks
        $subq_code_max_marks_query = 'SELECT qp_subq_code, qp_subq_marks ,qp_unitd_id 
									  FROM qp_definition AS qd, qp_unit_definition AS qu, qp_mainquestion_definition AS q
									  WHERE qu.qpd_unitd_id = q.qp_unitd_id
										AND qu.qpd_id = qd.qpd_id
										AND qd.qpd_id = "' . $qpd_id . '"
										AND qd.qp_rollout >= 1';
        $subq_code_max_marks_data = $this->db->query($subq_code_max_marks_query);
        $subq_code_max_marks = $subq_code_max_marks_data->result_array();
		
        foreach ($subq_code_max_marks as $subq_code_marks) {
			$this->db->query("UPDATE " . $temp_table_name . " tc
							  JOIN qp_mainquestion_definition q ON q.qp_subq_code = '" . $subq_code_marks['qp_subq_code'] . "'
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . str_replace("Q_No_", "", $subq_code_marks['qp_subq_code']) . " is greater than max marks. ')
							  WHERE `" . $subq_code_marks['qp_subq_code'] . "` > " . $subq_code_marks['qp_subq_marks']);

            //check if entered marks are integer or not						  
			$this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . $subq_code_marks['qp_subq_code'] . " needs to be integer or decimal value. ')
							  WHERE `" . $subq_code_marks['qp_subq_code'] . "` not REGEXP '^([ 0-9]{1,2}(\.[ 0-9]{1,6})?)?$'");
							 
/* 			$fetch_all_questions = $this->db->query('select group_concat(qp_subq_code SEPARATOR  '+') as qp_subq_code_gp from  qp_mainquestion_definition q where q.qp_unitd_id = '. $subq_code_marks['qp_unitd_id'] .'');				
			$all_question_head = $fetch_all_questions->result_array();
							  
		 	$this->db->query("UPDATE " . $temp_table_name . " tc
							  SET Remarks = CONCAT(COALESCE(remarks,''),'Secured marks for " . $subq_code_marks['qp_subq_code'] . " needs to be integer or decimal value. ')
							  group by student_usn having SUM(1a+1b+1c+2a+2b+2c+3a+3b+3c) != `total_marks` ");  */
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

        $crclm_term_crs_query = 'SELECT cr.crclm_name, ct.term_name, c.crs_title, c.crs_code
								 FROM curriculum AS cr, crclm_terms AS ct, course AS c
								 WHERE cr.crclm_id = "' . $crclm_id . '"
									AND ct.crclm_term_id = "' . $term_id . '"
									AND c.crs_id = "' . $crs_id . '"';
        $crclm_term_crs_data = $this->db->query($crclm_term_crs_query);
        $crclm_term_crs_name = $crclm_term_crs_data->result_array();
        $data['crclm_term_crs_name'] = $crclm_term_crs_name;

        if ($ao_id != NULL) {
			$mte_flag = $this->db->query('select mte_flag from assessment_occasions where  crclm_id = "' . $crclm_id . '"
											AND term_id = "' . $term_id . '"
											AND crs_id = "' . $crs_id . '"
											AND ao_id = "' . $ao_id . '" group by ao_id');
			$mte_flag_re = $mte_flag->result_array();
             $assessment_occasions_query = 'SELECT a.ao_id , a.ao_description, a.section_id , m.mt_details_name , a.mte_flag
										   FROM assessment_occasions as a  , master_type_details as m
										   WHERE  a.section_id = m.mt_details_id 
                                                                                        AND crclm_id = "' . $crclm_id . '"
											AND term_id = "' . $term_id . '"
											AND crs_id = "' . $crs_id . '"
											AND ao_id = "' . $ao_id . '" group by a.ao_id ';
                                                                                                                                                            										
            $assessment_occasions_data = $this->db->query($assessment_occasions_query);
            $assessment_occasions_name = $assessment_occasions_data->result_array();
            $data['ao_description'] = $assessment_occasions_name;
			$data['mte_flag'] = $mte_flag_re[0]['mte_flag'];
        }

		// $mte_of_course  = $this->db->query('select mte_flag from organisation');
		// $mte_flag_re = $mte_of_course->result_array();
		// $data['mte_flag'] = $mte_flag_re[0]['mte_flag'];
        return $data;
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
     * Function is to fetch course code and insert student details into student assessment (main) table
     * @parameters: question paper id & course id
     * @return: boolean
     */
    public function insert_into_student_table($qpd_id, $crs_id, $ao_id = NULL, $crclm_id, $term_id, $section_id) {
        // to get course instructor details
        $sec_id = $section_id;
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

        $field_names = $this->db->list_fields($temp_table_name);

        //if only 1 question is created then that question will be considered as total_marks
        if (count($field_names) == 4) {
            $total_marks_field = $field_names[3];
        } else {
            $total_marks_field = 'total_marks';
        }

		if ($ao_id != NULL) {
			$query = $this->db->query('select * from assessment_occasions where ao_id = "'. $ao_id .'"');
			$re = $query->result_array();
			if($re[0]['mte_flag'] == 1){ $cia_tee = "'MTE'";  $section_id = 'NULL'; }else if($re[0]['mte_flag'] == 0){ $cia_tee = "'CIA'";}
			$qpd_type = 3;
        } else {
            $cia_tee = "'TEE'";
			$qpd_type = 5;
			$section_id = 'NULL';
			$ao_id = 'NULL';
		}
		
        //fetch qp details, student usn, total marks (temp table) & insert into student_assessment, student_assessment_totalmarks tables
        $crs_co_level_attnmt = $this->db->query("call student_assessment_total_marks(" . $crs_id . ", " . $qpd_type . ", " . $qpd_id . ", '" . $total_marks_field . "')");

        //drop temporary table
        $this->drop_temp_table($crs_id);

        //on data insert to student assessment table set roll out to 2
        $insert_into_rollout_query = 'UPDATE qp_definition
									  SET qp_rollout = 2
									  WHERE qpd_id = "' . $qpd_id . '"';
        $insert_complete = $this->db->query($insert_into_rollout_query);
       
	    $query_org_tier = $this->db->query('SELECT * FROM organisation');
	    $org_type = $query_org_tier->result_array();
	    
		if($org_type[0]['org_type'] == 'TIER-I') {
			//tier i course co level attainment procedure is executed - tier1_section_clo_ao_attainment  tier1_student_section_clo_ao_attainment table are updated
			$section_co_attainment = $this->db->query("call tier_i_create_section_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " ,". $section_id .", " . $qpd_type . " ,". $ao_id .", " . $qpd_id . ", " . $cia_tee . ", NULL)");
			$co_section_data = $section_co_attainment->result_array();

			$student_CO_Attainment = $this->db->query("call tier_i_create_student_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " ,". $section_id .", " . $qpd_type . " ,". $ao_id .", " . $qpd_id . ", " . $cia_tee . ", NULL)");
			$student_attainment_data = $student_CO_Attainment->result_array();
                 
			//Dashboard Update Need to be done
			$get_course_instructor_id = 'SELECT clo_owner_id FROM course_clo_owner WHERE crs_id = "'.$crs_id.'" ';
			$this_course_instructor_id_data = $this->db->query($get_course_instructor_id);
			$instructor_id = $this_course_instructor_id_data->row_array();
		
			$update_dashboard = 'UPDATE dashboard SET status = 0 WHERE receiver_id = "'.$instructor_id['clo_owner_id'].'" AND particular_id = "'.$crs_id.'" AND crclm_id = "'.$crclm_id.'" AND entity_id = 4 ';
			$update_dash = $this->db->query($update_dashboard);
			
			$meta_data_query = ' SELECT crs.crs_title, crclm.crclm_name, term.term_name  FROM course as crs '
								. ' JOIN curriculum as crclm ON crclm.crclm_id = "'.$crclm_id.'" '
								. ' JOIN crclm_terms as term ON term.crclm_term_id = "'.$term_id.'" '
								. ' WHERE crs.crs_id = "'.$crs_id.'" ';
            $meta_data_data = $this->db->query($meta_data_query);
            $meta_data = $meta_data_data->row_array();
            
            $description = 'Term(Semester):- ' . $meta_data['term_name'];
            $instructor_description = $description . ', Course:- ' . $meta_data['crs_title'] . ' - '.$this->lang->line('entity_cie').' & '.$this->lang->line('entity_see').' Attainment is not finalized.';
            
			$dashboard_insert_array = array(
                'crclm_id' =>$crclm_id,
                'entity_id' =>4,
                'particular_id' =>$crs_id,
                'sender_id' =>$this->ion_auth->user()->row()->id,
                'receiver_id' =>$instructor_id['clo_owner_id'],
                'url' => base_url('assessment_attainment/cia'),
                'description' =>$instructor_description,
                'state' =>1,
                'status' =>1,
            );
            $this->db->insert('dashboard', $dashboard_insert_array);
        } else if($org_type[0]['org_type'] == 'TIER-II') {
			//tier ii course co level attainment procedure is executed and tier ii co occasion attainment table is updated
			$section_co_attainment = $this->db->query("call tier_ii_create_section_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " ,". $section_id .", " . $qpd_type . " ,". $ao_id .", " . $qpd_id . ", " . $cia_tee . ", NULL)");
			$co_section_data = $section_co_attainment->result_array();

			$student_CO_Attainment = $this->db->query("call tier_ii_create_student_CO_Attainment(" . $crclm_id . ", " . $term_id . "," . $crs_id . " ,". $section_id .", " . $qpd_type . " ,". $ao_id .", " . $qpd_id . ", " . $cia_tee . ", NULL)");
			$student_attainment_data = $student_CO_Attainment->result_array();
			
			//Dashboard Update Need to be done
			$get_course_instructor_id = 'SELECT clo_owner_id 
										 FROM course_clo_owner 
										 WHERE crs_id = "'.$crs_id.'"';
			$this_course_instructor_id_data = $this->db->query($get_course_instructor_id);
			$instructor_id = $this_course_instructor_id_data->row_array();
		
			$update_dashboard = 'UPDATE dashboard 
								 SET status = 0 
								 WHERE receiver_id = "'.$instructor_id['clo_owner_id'].'" 
									AND particular_id = "'.$crs_id.'" 
									AND crclm_id = "'.$crclm_id.'" 
									AND entity_id = 4';
			$update_dash = $this->db->query($update_dashboard);
		}			
        
		return '1';
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
     * Function is to discard student data from main table (student assessment table)
     * @parameters: question paper id
     * @return: boolean value
     */
    public function drop_main_table($qpd_id) {
        $student_assessment_fetch_query = 'SELECT q.qp_mq_id, qd.qpd_type
										   FROM qp_definition AS qd, qp_unit_definition AS qu, qp_mainquestion_definition AS q
										   WHERE qu.qpd_unitd_id = q.qp_unitd_id
											  AND qu.qpd_id = qd.qpd_id
											  AND qd.qpd_id = "' . $qpd_id . '"
											  AND qd.qp_rollout >= 1';
        $student_assessment_fetch_data = $this->db->query($student_assessment_fetch_query);
        $student_assessment_fetch = $student_assessment_fetch_data->result_array();

        $student_data_size = sizeof($student_assessment_fetch);

		//fetch tier1 or tier2 details from organization table
		$val = $this->ion_auth->user()->row();
		$org_name = $val->org_name->org_name;
		$org_type = $val->org_name->org_type;

        for ($i = 0; $i < $student_data_size; $i++) {
            $student_data_delete_query = 'DELETE FROM student_assessment
										  WHERE qp_mq_id = "' . $student_assessment_fetch[$i]['qp_mq_id'] . '"';
            $student_data_delete_result = $this->db->query($student_data_delete_query);
        }

		//Check TEE (5) or CIA (3) and fetch the respective course details
		if($student_assessment_fetch[0]['qpd_type'] == 3) {
			$ao_table_query = 'SELECT ao_id, crs_id, section_id , mte_flag 
							   FROM assessment_occasions
							   WHERE qpd_id = "' . $qpd_id . '"';
			$ao_table_data = $this->db->query($ao_table_query);
			$ao_table_fetch = $ao_table_data->result_array();
			
			$ao_id = $ao_table_fetch[0]['ao_id'];
			$crs_id = $ao_table_fetch[0]['crs_id'];
			if($ao_table_fetch[0]['mte_flag'] == 0){
				$section_id = $ao_table_fetch[0]['section_id'];
			}else{
				$section_id = NULL;
			}

			if($org_type == "TIER-I") {
				//CIA for TIER 1
				//delete AO occassion attainment values from tier1 clo ao attainment table
				if($ao_table_fetch[0]['mte_flag'] == 0){
				$sec_ao_attnmt_query = 'DELETE FROM tier1_section_clo_ao_attainment 
										WHERE crs_id ="' . $crs_id . '"
											AND ao_id ="' . $ao_id . '"
											AND section_id IS NOT NULL
											'
											;
				}else{
						$sec_ao_attnmt_query = 'DELETE FROM tier1_section_clo_ao_attainment 
										WHERE crs_id ="' . $crs_id . '"
											AND ao_id ="' . $ao_id . '"
											AND section_id IS NULL
											'
											;
				}
				$sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
				
				
				
				//update course attainment finalize status flag to zero
				if($ao_table_fetch[0]['mte_flag'] == 0){
				
				//delete section-wise all AO occassion attainment values from tier1 clo attainment table
				$sec_co_ovrll_attnmt_query = 'DELETE FROM tier1_section_clo_overall_cia_attainment 
											  WHERE crs_id ="' . $crs_id . '"
												AND section_id ="' . $section_id . '"';
				$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
				
							$ao_data_query = 'UPDATE map_courseto_course_instructor 
								  SET cia_finalise_flag = 0
								  WHERE crs_id ="' . $crs_id . '"
									AND section_id ="' . $section_id . '"';
				}else{
				
				//delete section-wise all AO occassion attainment values from tier1 clo attainment table
				$sec_co_ovrll_attnmt_query = 'DELETE FROM tier1_section_clo_overall_cia_attainment 
											  WHERE crs_id ="' . $crs_id . '"
												AND section_id IS NULL';
				$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
				
				
						$ao_data_query = 'UPDATE course_clo_owner 
											SET mte_finalize_flag = 0
											WHERE crs_id ="' . $crs_id . '"';
				}
				$ao_result = $this->db->query($ao_data_query);
			} else {
				//CIA for TIER 2
				//delete AO occassion attainment values from tier2 clo ao attainment table
				$sec_ao_attnmt_query = 'DELETE FROM tier_ii_section_clo_ao_attainment
										WHERE crs_id ="' . $crs_id . '"
											AND ao_id ="' . $ao_id . '"';
				$sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
				
				//delete section-wise all AO occassion attainment values from tier2 clo attainment table
				$sec_co_ovrll_attnmt_query = 'DELETE FROM tier_ii_section_clo_overall_cia_attainment 
											  WHERE crs_id ="' . $crs_id . '"
												AND section_id ="' . $section_id . '"';
				$sec_co_ovrll_attnmt_result = $this->db->query($sec_co_ovrll_attnmt_query);
				
				//update course attainment finalize status flag to zero
				$ao_data_query = 'UPDATE map_courseto_course_instructor 
								  SET cia_finalise_flag = 0
								  WHERE crs_id ="' . $crs_id . '"
									AND section_id ="' . $section_id . '"';
				$ao_result = $this->db->query($ao_data_query);
			}
		} else {
			//TEE for TIER 1
			$tee_table_query = 'SELECT crs_id
							    FROM qp_definition
							    WHERE qpd_id = "' . $qpd_id . '"';
			$tee_table_data = $this->db->query($tee_table_query);
			$tee_table_fetch = $tee_table_data->result_array();
			
			$crs_id = $tee_table_fetch[0]['crs_id'];
			
			//delete AO occassion attainment values from tier1 clo ao attainment table
			$sec_ao_attnmt_query = 'DELETE FROM tier1_section_clo_ao_attainment 
									WHERE crs_id = "' . $crs_id . '"
										AND assess_type = 5';
			$sec_ao_attnmt_result = $this->db->query($sec_ao_attnmt_query);
		}
		
        //on data discard from student assessment table set roll out to 1
        $insert_into_rollout_query = 'UPDATE qp_definition
									  SET qp_rollout = 1
									  WHERE qpd_id = "' . $qpd_id . '"';
        $insert_complete = $this->db->query($insert_into_rollout_query);

        return true;
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
     * Function is to perform student data analysis
     * @parameters: question paper id
     * @return: 
     */
    public function dataAnalysis($qpd_id) {
        $this->load->library('database_result');
        $marks = $this->db->query("call getAttainmentAnalysis(" . $qpd_id . ")");
        $data = $marks->result_array();
        $this->database_result->next_result();

        return $data;
    }
	
	/**
	 * Function to update student marks - inline edit
	 * @parameters: 
	 * @return: boolean value
	 */
    public function update_student_mark($data) {
        if ($data) {
            $this->db->trans_begin();
            foreach ($data as $key => $val) {                
                $this->db->where('assessment_id', $val['assessment_id']);
                $this->db->update('student_assessment', $val);                
            }            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return json_encode(array('key'=>'Success','message'=>'Record successfully updated'));
            }
        }
        return json_encode(array('key'=>'Error','message'=>'No data found to update!'));
    }

	/**
	 * Function to delete student marks/record - inline delete
	 * @parameters:
	 * @return: boolean value
	 */
    public function delete_student_mark($data) {
        if ($data) {
            $this->db->trans_begin();
            foreach ($data as $key => $val) {     
                if($val['assessment_id']){
                    $this->db->where('assessment_id', $val['assessment_id']);
                    $this->db->delete('student_assessment');                      
                }
            }            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                return json_encode(array('key'=>'Success','message'=>'Record successfully deleted'));
            }
        }
        return json_encode(array('key'=>'Error','message'=>'No data found to delete!'));
    }
    
    /*
     * Function to check the student is uploaded for the respective curriculum or not
     * @param: crclm_id
     * @return:
     */
    public function check_student_uploaded_or_not($crclm_id){
       $students_query = 'SELECT COUNT(ssd_id) as student_count FROM su_student_stakeholder_details WHERE crclm_id = "'.$crclm_id.'"  AND   status_active = 1 ';
       $student_data = $this->db->query($students_query);
       $student_res = $student_data->row_array();
       return $student_res;
       
    }
    
    /*
     * Function to Fetch the ao_method_id
     * @param:
     * @return: 
     */
    public function fetch_ao_method_id($crs_id){
        $get_ao_method_id_query = 'SELECT ao_method_id FROM ao_method WHERE crs_id = "'.$crs_id.'" AND section_id is NULL ';
        $get_ao_method_id = $this->db->query($get_ao_method_id_query);
        $ao_method_id = $get_ao_method_id->row_array();
        return $ao_method_id;
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
        
        $get_criteria_range_query = 'SELECT rng.rubrics_range_id, rng.ao_method_id, rng.criteria_range, dsc.rubrics_range_id, dsc.rubrics_criteria_id, dsc.criteria_description FROM ao_rubrics_range as rng '
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
   * Function to get the Meta Data for PDF report
   * @param: ao_id
   * @return:
   */
  public function pdf_report_meta_data($qpd_id){
      $get_the_meta_data_query = 'SELECT qp.crclm_id, qp.crclm_term_id, qp.crs_id, cr.crclm_name,term.term_name, crs.crs_title FROM qp_definition as qp'
              . ' JOIN curriculum as cr ON cr.crclm_id = qp.crclm_id'
              . ' JOIN crclm_terms as term ON term.crclm_term_id = qp.crclm_term_id'
              . ' JOIN course as crs ON crs.crs_id = qp.crs_id '
              . ' WHERE qp.qpd_id = "'.$qpd_id.'" ';
      $meta_data_data = $this->db->query($get_the_meta_data_query);
      $meta_data = $meta_data_data->row_array();
      return $meta_data;
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
                    foreach($target_data_res as $target_data){
                        if($target_data['cia_target_percentage'] == NULL ){
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
                    if($threshold_data_res['tee_course_minthreshhold']== 0){
                       $data['target_or_threshold_size'] = 0; 
                    }else{
                       $data['target_or_threshold_size'] = $threshold_data_res['tee_course_minthreshhold'];
                    }
                    $data['org_type'] = 'org1';
                }
            return $data;
    }
}
?>
