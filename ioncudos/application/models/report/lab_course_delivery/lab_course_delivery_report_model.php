<?php
/**
 * Description	:	Generates Course Delivery Report

 * Created		:	June 27th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_course_delivery_report_model extends CI_Model {
	
	/*
     * Function to fetch all the curriculum details for the logged in user
	 * @parameter: 
     * @return: dashboard state and status
     */
	public function fetch_curriculum() {
			//logged in user is other than admin
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
		if (($this->ion_auth->is_admin())) {
			//logged in user is admin
			$curriculum_list_details = 'SELECT crclm_id, crclm_name
									    FROM curriculum
									    WHERE status = 1
										ORDER BY crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_details);
			$curriculum = $curriculum_list_data->result_array();
			
			return $curriculum;
			
		} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){
		//logged in user is other than admin
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;
			
			$curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
									    FROM curriculum AS c, program AS p
									    WHERE c.status = 1
											AND c.pgm_id = p.pgm_id
											AND p.dept_id = "'.$dept_id.'"
										ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_details);
			$curriculum = $curriculum_list_data->result_array();
			
			return $curriculum;		
		}else if($this->ion_auth->in_group('Student')){
                    $user_id = $this->ion_auth->user()->row()->id;
                    $stud_crclm_res = $this->db->query("SELECT crclm_id FROM su_student_stakeholder_details where user_id='".$user_id."' AND status_active=1");
                    $stud_crclm = $stud_crclm_res->row();
                    $crclm_id = $stud_crclm->crclm_id;
                    $dept_id = $this->ion_auth->user()->row()->user_dept_id;
			
			$curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
									    FROM curriculum AS c, program AS p
									    WHERE c.status = 1
											AND c.pgm_id = p.pgm_id
											AND p.dept_id = "'.$dept_id.'"
                                                                                        AND c.crclm_id = "'.$crclm_id.'"
										ORDER BY c.crclm_name ASC';
			$curriculum_list_data = $this->db->query($curriculum_list_details);
			$curriculum = $curriculum_list_data->result_array();
			
			return $curriculum;
                } else {
		$curriculum_list_details = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
				  $curriculum_list_data = $this->db->query($curriculum_list_details);
            $curriculum = $curriculum_list_data->result_array();

            return $curriculum;
		}
	}
	/** 
	 * Function to fetch term details using curriculum id from curriculum terms table
	 * @parameters: curriculum id
	 * @return: term id and term name
	 */
    public function fetch_term($curriculum_id) {
	$loggedin_user_id = $this->ion_auth->user()->row()->id;
	if($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')){
       $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="'.$loggedin_user_id.'"
					  AND c.crclm_id = "'.$curriculum_id.'"';
	}else{
		 $term_list_query = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
				
	}
        $term_name_result = $this->db->query($term_list_query);
        $term_name_result = $term_name_result->result_array();
        $data['term_name_result'] = $term_name_result;
		
        return $data;
    }
	
	/** 
	 * Function to fetch course details using curriculum id and term id from course table
	 * @parameters: curriculum id, term id
	 * @return: course id and course title
	 */
    public function fetch_lab_course($curriculum_id, $term_id) {
        $course_name_query = 'SELECT crs_id, crs_title
							  FROM course
							  WHERE crclm_id = "' . $curriculum_id . '"
								AND crclm_term_id = "' . $term_id . '"
								AND crs_mode >= 1
								AND status = 1
							   ORDER BY crs_title ASC';
        $course_name_result = $this->db->query($course_name_query);
        $course_list = $course_name_result->result_array();
        $data['course_list'] = $course_list;
		
        return $data;
    }
	
	/**
	 * Function to fetch lab course plan details
	 * @parameters: curriculum id, term id, course id
	 * @return: lab course plan details
	 */
	public function fetch_lab_course_plan($curriculum_id, $term_id, $course_id) {
		//fetch curriculum details
		$fetch_curriculum_detail = 'SELECT crclm_name
									FROM curriculum
									WHERE crclm_id = "' . $curriculum_id . '"';
									$curriculum_detail_data = $this->db->query($fetch_curriculum_detail);
		$curriculum_detail = $curriculum_detail_data->result_array();
		$data['lab_curriculum_detail'] = $curriculum_detail;

		//course outcomes
		$course_learning_statements = 'SELECT c.clo_code, c.clo_statement, t.term_name
									   FROM clo AS c
									   JOIN crclm_terms AS t ON t.crclm_term_id = "' . $term_id . '" 
									   WHERE c.crclm_id = "' . $curriculum_id . '"
											AND c.term_id = "' . $term_id . '" 
											AND c.crs_id = "' . $course_id . '"
									   ORDER BY LPAD(LOWER(c.clo_code),5,0) ASC';
		$course_learning = $this->db->query($course_learning_statements);
		$course_learning_objectives = $course_learning->result_array();
		$data['lab_course_learning_objectives'] = $course_learning_objectives;
		
		//fetch course details
		$course_plan_query = 'SELECT crs_code, crs_title, contact_hours,
								lect_credits, tutorial_credits, practical_credits, self_study_credits, total_credits, 
								cie_marks, see_marks, see_duration, ss_marks, total_marks,
								modify_date, create_date
							  FROM course
							  WHERE crclm_id = "' . $curriculum_id . '" 
								AND crclm_term_id = "' . $term_id . '"
								AND crs_id = "' . $course_id . '"';
		$course_plan_details = $this->db->query($course_plan_query);
		$course_plan_details_data = $course_plan_details->result_array();
		$data['lab_course_details'] = $course_plan_details_data;
		
		//fetch course owner details
		$course_plan_owner = 'SELECT u.title, u.first_name, u.last_name, u.username, o.clo_owner_id
							  FROM course_clo_owner AS o, users AS u
							  WHERE o.crs_id   = "' . $course_id . '"
								AND u.id = o.clo_owner_id';
		$course_plan_author = $this->db->query($course_plan_owner);
		$course_plan_author_data = $course_plan_author->result_array();
		$data['lab_course_owner'] = $course_plan_author_data;
		
		//fetch course validator details
		$course_validator = 'SELECT u.title, u.first_name, u.last_name, u.username, o.validator_id, o.last_date
							 FROM course_clo_validator AS o, users AS u
							 WHERE o.crs_id = "' . $course_id . '"
								AND u.id = o.validator_id';
		$course_plan_validator = $this->db->query($course_validator);
		$course_plan_validator_data = $course_plan_validator->result_array();
		$data['lab_course_validator'] = $course_plan_validator_data;
		
		//course learning objectives details
        $clo = 'SELECT clo_id, crs_id, clo_statement 
				FROM clo 
				WHERE term_id = "' . $term_id . '"
					AND crs_id = "' . $course_id . '"';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['lab_clo_list'] = $clo_list;
		
		//to fetch program outcomes
        $po_list_query = 'SELECT po_id, crclm_id, po_reference, po_statement 
                          FROM po
                          WHERE crclm_id = "'.$curriculum_id.'"
						  ORDER BY LPAD(LOWER(po_reference),5,0) ASC';
        $po_list_data = $this->db->query($po_list_query);
        $po_list_result = $po_list_data->result_array();
        $data['lab_po_list'] = $po_list_result;
		
		//to fetch course title, code, curriculum and term name
		$course_query = $this->db
							 ->select('crs_id, crs_title, crs_code')
						 	 ->join('curriculum', 'curriculum.crclm_id = course.crclm_id')
							 ->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
						 	 ->order_by("course.crclm_term_id", "asc")
							 ->where('crs_id', $course_id)
							 ->get('course')
							 ->result_array();

		$data['lab_course_list'] = $course_query;
		
		//mapped course learning objectives to program outcome details
        $map = 'SELECT DISTINCT clo_id, po_id, map_level
				FROM clo_po_map 
				WHERE crclm_id = "' . $curriculum_id . '"';
        $map_list = $this->db->query($map);
        $map_list = $map_list->result_array();
        $data['lab_clo_po_map_details'] = $map_list;
	
		//outcome elements and performance indicators
		$oe_pi_query = 'SELECT DISTINCT msr.msr_statement, msr.pi_codes, pi.pi_statement, clo_po.pi_id, clo_po.msr_id
						FROM measures as msr, clo_po_map as clo_po, performance_indicator as pi
						WHERE clo_po.pi_id = pi.pi_id
							AND clo_po.msr_id = msr.msr_id
							AND clo_po.crs_id = "'.$course_id.'"
						ORDER BY clo_po.pi_id, LPAD(LOWER(clo_po.msr_id),10,0) ASC';
		$oe_pi_data = $this->db->query($oe_pi_query);	
		$oe_pi_result = $oe_pi_data->result_array();
		$data['lab_oe_pi'] = $oe_pi_result;
	
		$this->db->simple_query('SET SESSION group_concat_max_len=1000000');
		
		//contains all the data required for lab experiments - category, lab no., expt details, sessions, marks, tlo details, correlation
		$category_detail_query = 'SELECT mtd.mt_details_id, mtd.mt_details_name, 
									top.topic_id, top.topic_title, top.topic_content, top.category_id,
									top.num_of_sessions, top.marks_expt, top.correlation_with_theory,
									tlo.tlo_id, group_concat(tlo.tlo_statement separator "||") AS gc_tlo_stmt
								  FROM master_type_details AS mtd
								  LEFT JOIN topic AS top ON top.category_id = mtd.mt_details_id
								  LEFT JOIN tlo AS tlo ON tlo.topic_id = top.topic_id
								  WHERE mtd.master_type_id = 9
									AND top.course_id = "'.$course_id.'"
								  GROUP BY top.topic_id
								  ORDER BY mtd.mt_details_id, LPAD(LOWER(top.topic_title),5,0) ASC';
		$category_data = $this->db->query($category_detail_query);
		$category = $category_data->result_array();
		$data['category'] = $category;

		//total weightage and total session
		$total_weightage_session_query = 'Select topic_id, category_id,
											SUM(num_of_sessions) AS num_of_sessions, SUM(marks_expt) AS marks_expt
										  FROM (SELECT distinct top.topic_id, top.category_id, top.num_of_sessions, top.marks_expt
										  FROM master_type_details AS mtd
										  LEFT Join topic top ON  top.category_id = mtd.mt_details_id
										  WHERE top.course_id = "'.$course_id.'" AND mtd.master_type_id = 9
										 ) A
 										 GROUP BY category_id';
		$total_weightage_session_data = $this->db->query($total_weightage_session_query);
		$total_weightage_session = $total_weightage_session_data->result_array();
		$data['total_weightage_session'] = $total_weightage_session;
		
		return $data;
	}
	
	/*
	 * Function to fetch the Department name from the table 'department' using the curriculum id
	 * @parm: crclm_id
	 * @return: department name
	 */
	public function dept_name_by_crclm_id($crclm_id) {
		$dept_name_qry = 'SELECT dept_name 
						  FROM department 
						  WHERE dept_id = (SELECT dept_id 
										   FROM program 
										   WHERE pgm_id = (SELECT pgm_id 
														   FROM curriculum 
														   WHERE crclm_id= "' . $crclm_id . '"))';
		$dept_name_object = $this->db->query($dept_name_qry);
		$dept_name_array = $dept_name_object->result_array();
	
		return $dept_name_array[0]['dept_name'];
	}
	
	/**
	 * Function to fetch course details, course owner and course validator from course table, course clo owner table and
	   course clo validator table respectively
	 * @parameters: 
	 * @return:
	*/
	public function course_plan_details($curriculum_id, $term_id, $course_id) {	
		$course_plan_query = 'SELECT c.crs_code, c.crs_title, t.term_name
							  FROM course AS c, crclm_terms AS t
							  WHERE c.crclm_id = "' . $curriculum_id . '"
								AND c.crclm_term_id = "' . $term_id . '"
								AND c.crs_id = "' . $course_id . '"
								AND c.crclm_term_id = t.crclm_term_id';
		$course_plan_details = $this->db->query($course_plan_query);
		$course_plan_details_data = $course_plan_details->result_array();
		$data['course_details'] = $course_plan_details_data;
		
		return $data;
	}
}
?>