<?php

/**
 * Description	:	Generates Course Delivery Report

 * Created		:	March 24th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------ */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_delivery_report_model extends CI_Model {
    /*
     * Function to fetch all the curriculum details for the logged in user
     * @return: dashboard state and status
     */

    public function fetch_curriculum() {
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
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            //logged in user is other than admin
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
									    FROM curriculum AS c, program AS p
									    WHERE c.status = 1
											AND c.pgm_id = p.pgm_id
											AND p.dept_id = "' . $dept_id . '"
										ORDER BY c.crclm_name ASC';
            $curriculum_list_data = $this->db->query($curriculum_list_details);
            $curriculum = $curriculum_list_data->result_array();

            return $curriculum;
        } else if ($this->ion_auth->in_group('Student')) {
            $user_id = $this->ion_auth->user()->row()->id;
            $stud_crclm_res = $this->db->query("SELECT crclm_id FROM su_student_stakeholder_details where user_id='" . $user_id . "' AND status_active=1");
            $stud_crclm = $stud_crclm_res->row();
            $crclm_id = $stud_crclm->crclm_id;
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;

            $curriculum_list_details = 'SELECT c.crclm_id, c.crclm_name
									    FROM curriculum AS c, program AS p
									    WHERE c.status = 1
											AND c.pgm_id = p.pgm_id
											AND p.dept_id = "' . $dept_id . '"
											AND c.crclm_id = "' . $crclm_id . '"
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
        if ($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
            $term_list_query = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="' . $loggedin_user_id . '"
					  AND c.crclm_id = "' . $curriculum_id . '"';
        } else {
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
     * Function to program outcomes details from po table
     * @parameters: curriculum id
     * @return: program outcome statements, program outcome references
     */
    public function fetch_po_statement($curriculum_id) {
        //program outcomes details
        $po_details_list = 'SELECT po_statement, po_reference
							FROM po 
							WHERE crclm_id = "' . $curriculum_id . '"';
        $po_list_data = $this->db->query($po_details_list);
        $po_list = $po_list_data->result_array();

        return $po_list;
    }

    /**
     * Function to course learning outcomes details from clo table
     * @parameters: curriculum id, term id, course id
     * @return: program outcome statements, program outcome references
     */
    public function fetch_clo_statement($curriculum_id, $term_id, $course_id) {
        //course learning outcomes details
        $clo_details_list = 'SELECT clo_statement
							 FROM clo
							 WHERE crclm_id = "' . $curriculum_id . '"
								AND term_id = "' . $term_id . '"
								AND crs_id = "' . $course_id . '"';
        $clo_list_data = $this->db->query($clo_details_list);
        $clo_list = $clo_list_data->result_array();

        return $clo_list;
    }

    /**
     * Function to fetch course details using curriculum id and term id from course table
     * @parameters: curriculum id, term id
     * @return: course id and course title
     */
    public function fetch_course($curriculum_id, $term_id) {
        $course_name_query = 'SELECT crs_id, crs_title
							  FROM course
							  WHERE crs_mode IN (0,2)
								AND crclm_id = "' . $curriculum_id . '"
								AND crclm_term_id = "' . $term_id . '"
								AND status = 1
							   ORDER BY crs_title ASC';
        $course_name_result = $this->db->query($course_name_query);
        $course_list = $course_name_result->result_array();
        $data['course_list'] = $course_list;

        return $data;
    }

    /**
     * Function to fetch course plan details, mapping and attainment details
     * @parameters: curriculum id, term id, course id
     * @return: course plan details
     */
    public function fetch_course_plan($curriculum_id, $term_id, $course_id) {
	
	$this->db->simple_query('SET SESSION group_concat_max_len=50000');
        //fetch curriculum details
        $fetch_curriculum_detail = 'SELECT crclm_name
									FROM curriculum
									WHERE crclm_id = "' . $curriculum_id . '"';
        $curriculum_detail_data = $this->db->query($fetch_curriculum_detail);
        $curriculum_detail = $curriculum_detail_data->result_array();
        $data['curriculum_detail'] = $curriculum_detail;

        //fetch term details
        $fetch_term_detail = 'SELECT term_name, academic_year
							  FROM crclm_terms
							  WHERE crclm_term_id = "' . $term_id . '"';
        $term_detail_data = $this->db->query($fetch_term_detail);
        $term_detail = $term_detail_data->result_array();
        $data['term_detail'] = $term_detail;

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
        $data['course_learning_objectives'] = $course_learning_objectives;

        //prerequisite courses
        $prerequisites_details = 'SELECT predecessor_course
								  FROM predecessor_courses
								  WHERE crs_id = "' . $course_id . '"';
        $prerequisites_data = $this->db->query($prerequisites_details);
        $course_prerequisites_data = $prerequisites_data->result_array();
        $data['course_prerequisites'] = $course_prerequisites_data;

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
        $data['course_details'] = $course_plan_details_data;

        //fetch course owner details
        $course_plan_owner = 'SELECT u.title, u.first_name, u.last_name, u.username, o.clo_owner_id, c.co_crs_owner
							  FROM course_clo_owner AS o, users AS u, course AS c
							  WHERE o.crs_id   = "' . $course_id . '"
								AND u.id = o.clo_owner_id
								AND c.crs_id = o.crs_id';
        $course_plan_author = $this->db->query($course_plan_owner);
        $course_plan_author_data = $course_plan_author->result_array();
        $data['course_owner'] = $course_plan_author_data;

        //fetch course validator details
        $course_validator = 'SELECT u.title, u.first_name, u.last_name, u.username, o.validator_id, o.last_date
							 FROM course_clo_validator AS o, users AS u
							 WHERE o.crs_id = "' . $course_id . '"
								AND u.id = o.validator_id';
        $course_plan_validator = $this->db->query($course_validator);
        $course_plan_validator_data = $course_plan_validator->result_array();
        $data['course_validator'] = $course_plan_validator_data;

        //course learning objectives details
        $clo = 'SELECT clo_id, crs_id, clo_code, clo_statement 
				FROM clo 
				WHERE term_id = "' . $term_id . '"
					AND crs_id = "' . $course_id . '"';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;

        //fetch topic details
        $topic_name = 'SELECT t.topic_id, t.topic_hrs, t.t_unit_id, t.topic_title, t.topic_content, unit.t_unit_name
					   FROM topic as t, topic_unit as unit 
					   WHERE t.t_unit_id = unit.t_unit_id
						  AND t.course_id = "' . $course_id . '"
					   GROUP BY t.topic_title
					   ORDER BY LPAD(LOWER(t.t_unit_id),5,0) ASC';
        $topic_result = $this->db->query($topic_name);
        $topic_result_data = $topic_result->result_array();
        $data['topic_result_data'] = $topic_result_data;

        //fetch all the related textbooks and reference books
        $text_books_details = 'SELECT book_sl_no,book_author, book_title, book_edition, book_publication, book_publication_year, book_type
							   FROM book
							   WHERE crs_id = "' . $course_id . '"';
        $text_books_data = $this->db->query($text_books_details);
        $text_books_result = $text_books_data->result_array();
        $data['text_books'] = $text_books_result;

        //fetch all the topics for a particular curriculum, term and its course
        $fetch_all_topics = 'SELECT topic_id, topic_title, topic_hrs, t_unit_id
							 FROM topic
							 WHERE curriculum_id = "' . $curriculum_id . '"
								AND term_id = "' . $term_id . '"
								AND course_id = "' . $course_id . '"
								AND t_unit_id IS NOT NULL
							 GROUP BY topic_title
							 ORDER BY LPAD(LOWER(topic_title),10,0) ASC';
        $fetch_topics_data = $this->db->query($fetch_all_topics);
        $fetch_topics = $fetch_topics_data->result_array();
        $data['topics_data_details'] = $fetch_topics;

        $tlo_data_result = array();
        $question_data_result = array();
        $ls_questions = array();

        foreach ($fetch_topics as $topic_dta) {
            $tlo_data_query = 'SELECT DISTINCT t.tlo_id,t.topic_id, t.bloom_id, t.tlo_code, t.tlo_statement,GROUP_CONCAT( m.bloom_id) given_answers,
									bl.bloom_id,GROUP_CONCAT( bl.level) bloom_lvel, bl.level, bl.description,
									tlo_clo.clo_id, tlo_clo.tlo_id, cl.clo_statement, cl.clo_code,
									fetch_pi_code(t.tlo_id,"' . $course_id . '") AS pi_codes
							   FROM tlo AS t
							   LEFT JOIN tlo_clo_map AS tlo_clo ON t.tlo_id = tlo_clo.tlo_id
									AND t.topic_id = tlo_clo.topic_id
							   LEFT JOIN clo AS cl ON cl.clo_id = tlo_clo.clo_id
							   LEFT JOIN map_tlo_bloom_level as m ON (m.tlo_id= t.tlo_id)
							   LEFT JOIN bloom_level AS bl ON m.bloom_id =bl.bloom_id
							   WHERE t.course_id = "' . $course_id . '"
									AND t.topic_id = "' . $topic_dta['topic_id'] . '" 
							   GROUP BY t.tlo_id';

            $fetch_tlo_data = $this->db->query($tlo_data_query);
            $tlo_data_result[] = $fetch_tlo_data->result_array();

            $question_data_query = 'SELECT DISTINCT top.question_id, top.review_question, top.assignment_question, 
										top.course_id, top.topic_id, tls.portion_ref,
										top.tlo_id, top.bloom_id, top.pi_codes, tl.tlo_statement, tl.tlo_code,
										bloom.level, fetch_images(top.question_id) AS image,
										group_concat(tls.conduction_date separator "|$|") AS conduction,
                                                                                group_concat(tls.actual_delivery_date separator "|$|") AS actual_date,
										group_concat(tls.portion_per_hour separator "||") AS schedule,
										group_concat(tls.portion_ref ORDER BY LPAD(LOWER(tls.portion_ref),5,0) ASC separator "##") AS portion_ref
									FROM topic_question AS top
									JOIN bloom_level AS bloom ON top.bloom_id = bloom.bloom_id
									JOIN tlo AS tl ON top.tlo_id = tl.tlo_id
									LEFT JOIN topic_lesson_schedule AS tls on top.topic_id = tls.topic_id
									WHERE top.topic_id = "' . $topic_dta['topic_id'] . '"
										AND top.course_id = "' . $course_id . '" 
									GROUP BY top.question_id';
            $fetch_question_data = $this->db->query($question_data_query);
            $question_data_result[] = $fetch_question_data->result_array();

            $ls_qn = 'SELECT DISTINCT tls.course_id, tls.topic_id, tls.portion_ref,
						group_concat(tls.conduction_date separator "|$|") AS conduction,
						group_concat(tls.actual_delivery_date separator "|$|") AS actual_date,
						group_concat(tls.portion_per_hour separator "||") AS schedule,
						group_concat(tls.portion_ref ORDER BY LPAD(LOWER(tls.portion_ref),5,0) ASC separator "##") AS portion_ref
					  From  topic_lesson_schedule AS tls
					  WHERE tls.topic_id = "' . $topic_dta['topic_id'] . '"
						AND tls.course_id = "' . $course_id . '" ';
            $ls_fetch_data = $this->db->query($ls_qn);
            $ls_data_result[] = $ls_fetch_data->result_array();

            $assignment_data_query = 'SELECT DISTINCT top_assign.assignment_content,top_assign.topic_id
									FROM topic_assignments AS top_assign
									LEFT JOIN topic_lesson_schedule AS tls on top_assign.topic_id = tls.topic_id
									WHERE top_assign.topic_id = "' . $topic_dta['topic_id'] . '"
										AND top_assign.course_id = "' . $course_id . '" ';
            $fetch_assignment_data = $this->db->query($assignment_data_query);
            $assignment_data_result[] = $fetch_assignment_data->result_array();
            
			//lesson schedule
            $lesson_schedule_query = 'SELECT DISTINCT tls.lesson_schedule_id, tls.portion_per_hour, tls.conduction_date, tls.actual_delivery_date
									  FROM topic_lesson_schedule AS tls
									  JOIN topic AS top ON top.topic_id = tls.topic_id
									  WHERE top.topic_id = "' . $topic_dta['topic_id'] . '"
										AND top.course_id = "' . $course_id . '"';
            $lesson_schedule_data = $this->db->query($lesson_schedule_query);
            $lesson_schedule[] = $lesson_schedule_data->result_array();
        }

        if (!empty($tlo_data_result)) {
            $data['tlo_bl_data'] = $tlo_data_result;
        } else {
            $data['tlo_bl_data'] = "No Data";
        }

        if (!empty($question_data_result)) {
            $data['question_data'] = $question_data_result;
        } else {
            $data['question_data'] = "No Data";
        }

        if (!empty($ls_data_result)) {
            $data['lesson_qn_data'] = $ls_data_result;
        } else {
            $data['lesson_qn_data'] = "No Data";
        }

        if (!empty($assignment_data_result)) {
            $data['assignment_data'] = $assignment_data_result;
            $data['assgn_title'] = "Assignment Questions";
        } else {
            $data['assgn_title'] = "";
            $data['assignment_data'] = "No Data";
        }

        //to fetch blooms details, topic learning details from tlo and bloom level table
        $topic_learning_objectives_details = 'SELECT t.topic_id, t.bloom_id, t.tlo_id, t.tlo_statement, bl.bloom_id, bl.level, bl.description
											  FROM tlo as t, bloom_level as bl
											  WHERE course_id = "' . $course_id . '"
												  AND t.bloom_id = bl.bloom_id
											  ORDER BY tlo_statement';
        $topic_learning_objectives_data = $this->db->query($topic_learning_objectives_details);
        $topic_learning_objectives = $topic_learning_objectives_data->result_array();
        $data['topic_learning_objectives'] = $topic_learning_objectives;

        //to fetch topic learning outcome id from tlo table
        $assessment_details = 'SELECT tlo_id
							   FROM tlo
							   WHERE curriculum_id = "' . $curriculum_id . '" 
									AND term_id = "' . $term_id . '" 
									AND course_id = "' . $course_id . '"';
        $assessment_data = $this->db->query($assessment_details);
        $assessment = $assessment_data->result_array();
        $assessment_size = sizeof($assessment);

        $assess_name = array();
        for ($i = 0; $i < $assessment_size; $i++) {
            //to fetch assessment id and topic learning outcome id from tlo assessment mapping table
            $assess_id_details = 'SELECT assess_id, tlo_id
								  FROM tlo_assessment_map 
								  WHERE tlo_id = "' . $assessment[$i]['tlo_id'] . '"';
            $assess_id_data = $this->db->query($assess_id_details);
            $assess_id[$i] = $assess_id_data->result_array();

            $assess_id_size = sizeof($assess_id[$i]);

            for ($j = 0; $j < $assess_id_size; $j++) {
                //to fetch assessment details from assessment methods table
                $assess_name_details = 'SELECT distinct a.assess_id, a.assess_name, t.tlo_id 
										FROM assessment_methods AS a, tlo_assessment_map AS t
										WHERE a.assess_id = "' . $assess_id[$i][$j]['assess_id'] . '"
											AND t.tlo_id = "' . $assess_id[$i][$j]['tlo_id'] . '"';
                $assess_name_data = $this->db->query($assess_name_details);
                $assess_name[$i][$j] = $assess_name_data->result_array();
            }
        }
        $data['assess_name'] = $assess_name;

        /**
         * Fetch topic learning outcomes mapped to course learning outcomes details from topic
          learning outcomes to course learning outcomes mapping table
         */
        $tlo_clo_map = 'SELECT DISTINCT tlo_id, clo_id
					    FROM tlo_clo_map
					    WHERE curriculum_id = "' . $curriculum_id . '"
							AND course_id = "' . $course_id . '"';
        $tlo_clo_map_details = $this->db->query($tlo_clo_map);
        $tlo_clo_map_list = $tlo_clo_map_details->result_array();
        $data['tlo_clo_map_list'] = $tlo_clo_map_list;

        //po to peo mapping details
        $data['curriculum_id'] = $curriculum_id;

        $course_query = 'SELECT crclm_id, crs_id, crs_title, crs_code
						 FROM course 
						 WHERE crclm_term_id = "' . $term_id . '" 
							AND crclm_id = "' . $curriculum_id . '" ';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();

        //to fetch program educational objective details
        $peo_query = 'SELECT peo_id, peo_statement, crclm_id
					  FROM peo
					  WHERE crclm_id = "' . $curriculum_id . '" ';
        $peo_list = $this->db->query($peo_query);
        $peo_list_data = $peo_list->result_array();
        $data['po_peo_course_list'] = $peo_list_data;

        //to fetch program outcomes
        $po_list_query = 'SELECT po_id, crclm_id, po_reference, po_statement 
                          FROM po
                          WHERE crclm_id = "' . $curriculum_id . '"
						  ORDER BY LPAD(LOWER(po_reference),5,0) ASC';
        $po_list_data = $this->db->query($po_list_query);
        $po_list_result = $po_list_data->result_array();
        $data['po_list'] = $po_list_result;

        //po peo mapping details
        $course_po_map_query = 'SELECT pp_id, peo_id, po_id, crclm_id 
								FROM po_peo_map 
								WHERE crclm_id = "' . $curriculum_id . '"';
        $course_po_map_data = $this->db->query($course_po_map_query);
        $course_po_map_result = $course_po_map_data->result_array();

        //clo to po mapping details
        $data['curriculum_id'] = $curriculum_id;

        //to fetch course title, code, curriculum and term name
        $course_query = $this->db
                ->select('crs_id, crs_title, crs_code')
                ->join('curriculum', 'curriculum.crclm_id = course.crclm_id')
                ->join('crclm_terms', 'crclm_terms.crclm_term_id = course.crclm_term_id')
                ->order_by("course.crclm_term_id", "asc")
                ->where('crs_id', $course_id)
                ->get('course')
                ->result_array();

        $data['course_list'] = $course_query;

        //program outcome details
        $po = 'SELECT po_id, crclm_id, po_reference, po_statement 
			   FROM po 
			   WHERE crclm_id = "' . $curriculum_id . '"
			   ORDER BY LPAD(LOWER(po_reference),5,0) ASC';
        $po_list = $this->db->query($po);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

        //mapped course learning objectives to program outcome details
        $map = 'SELECT DISTINCT clo_id, po_id, map_level
				FROM clo_po_map 
				WHERE crclm_id = "' . $curriculum_id . '"';
        $map_list = $this->db->query($map);
        $map_list = $map_list->result_array();
        $data['clo_po_map_details'] = $map_list;

        //evaluation scheme & course unitization
        //to fetch assessment details
        $assessment_query = 'SELECT assessment_mode, assessment_id, assessment_name, weightage_in_marks
							 FROM cie_evaluation_scheme
							 WHERE crs_id = "' . $course_id . '"
								AND assessment_mode != 2';
        $assessment_details = $this->db->query($assessment_query);
        $assessment_data = $assessment_details->result_array();
        $data['assessment'] = $assessment_data;

        if (!empty($assessment_data)) {
            foreach ($assessment_data as $cie_id) {
                $crs_unitization = 'SELECT cu.crs_unitization_id, cu.topic_id, cu.no_of_questions, 
									cu.assessment_id, cu.crs_id, t.topic_id, t.topic_title
									From course_unitization as cu, topic as t
									WHERE cu.topic_id = t.topic_id 
										AND cu.assessment_id = "' . $cie_id['assessment_id'] . '" ';
                $course_unitization_data = $this->db->query($crs_unitization);
                $course_unitization_result[] = $course_unitization_data->result_array();
            }
            $data['crs_unitization'] = $course_unitization_result;
        } else {
            $data['crs_unitization'] = 'No data for this Course';
        }

        //outcome elements and performance indicators
        $oe_pi_query = 'SELECT DISTINCT msr.msr_statement, msr.pi_codes, pi.pi_statement, clo_po.pi_id, clo_po.msr_id
						FROM measures as msr, clo_po_map as clo_po, performance_indicator as pi
						WHERE clo_po.pi_id = pi.pi_id
							AND clo_po.msr_id = msr.msr_id
							AND clo_po.crs_id = "' . $course_id . '"
						ORDER BY clo_po.pi_id, LPAD(LOWER(clo_po.msr_id),10,0) ASC';
        $oe_pi_data = $this->db->query($oe_pi_query);
        $oe_pi_result = $oe_pi_data->result_array();
        $data['oe_pi'] = $oe_pi_result;

        if (!empty($course_po_map_result)) {
            $data['po_peo_map_list'] = $course_po_map_result;
            return $data;
        } else {
            $data['po_peo_map_list'] = NULL;
            return $data;
        }
    }

    /**
      Function to fetch oe_pi_glag
     * */
    function fetch_org_re() {
        $org_query = 'SELECT oe_pi_flag
					  FROM organisation';
        $org_data = $this->db->query($org_query);
        return $org_result = $org_data->result_array();
    }

    /**
     * Function to fetch SEE model qp
     * @parameters: curriculum id, term id, course id and qp type
     * @return: array
     */
    public function model_qp_edit_details($crclm_id, $term_id, $crs_id, $qp_type) {
        $org_query = 'SELECT oe_pi_flag
					  FROM organisation';
        $org_data = $this->db->query($org_query);
        $org_result = $org_data->result_array();
        $model_qp_data['org_result'] = $org_result;

        $model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, qpd.qpd_max_marks, qpd.qpd_notes, 
										qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
									FROM qp_definition AS qpd
									JOIN course AS crs ON qpd.crs_id = crs.crs_id
									WHERE qpd.crclm_id = "' . $crclm_id . '"
										AND qpd.crclm_term_id = "' . $term_id . '"
										AND qpd.crs_id = "' . $crs_id . '" 
										AND qpd.qpd_type = "' . $qp_type . '"';
        $model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
        $model_qp_meta_data_res = $model_qp_meta_data->result_array();

        if ($model_qp_meta_data_res) {
            $qpd_id = $model_qp_meta_data_res[0]['qpd_id'];
            $model_qp_data['qp_meta_data'] = $model_qp_meta_data_res;

            $qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, unit.qp_attempt_unitquestion, 
										mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data_val(mq.qp_mq_id) as mapping_data, fetch_qp_images(mq.qp_mq_id) as image_name
									FROM qp_unit_definition AS unit
									JOIN qp_definition AS qpd ON qpd.qpd_id = "' . $qpd_id . '"
									JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
									WHERE unit.qpd_id = "' . $qpd_id . '"
									ORDER BY CAST(mq.qp_subq_code AS UNSIGNED), mq.qp_subq_code ASC';
            $qpd_unit_data = $this->db->query($qpd_unit_data_query);
            $qpd_unit_data_res = $qpd_unit_data->result_array();

            $model_qp_data['main_qp_data'] = $qpd_unit_data_res;

            $qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, 
									unit.qp_attempt_unitquestion, unit.qp_utotal_marks
							   FROM qp_unit_definition as unit 
							   WHERE unit.qpd_id = "' . $qpd_id . '"';
            $qpd_unit = $this->db->query($qpd_unit_query);
            $qpd_unit_res = $qpd_unit->result_array();
            $model_qp_data['unit_def_data'] = $qpd_unit_res;

            $course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
									 FROM clo 
									 WHERE crclm_id = "' . $crclm_id . '" 
										AND term_id = "' . $term_id . '" 
										AND crs_id = "' . $crs_id . '"';
            $course_co_data = $this->db->query($course_co_data_query);
            $course_co_data_result = $course_co_data->result_array();
            $model_qp_data['co_data'] = $course_co_data_result;

            $topic_query = 'SELECT topic_id, topic_title 
							FROM topic 
							WHERE curriculum_id = "' . $crclm_id . '" 
								AND term_id = "' . $term_id . '" 
								AND course_id = "' . $crs_id . '"';
            $topic_data = $this->db->query($topic_query);
            $topic_result = $topic_data->result_array();
            $model_qp_data['topic_data'] = $topic_result;

            $bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
							    FROM bloom_level';
            $bloom_lvl_data = $this->db->query($bloom_lvl_query);
            $bloom_lvl_result = $bloom_lvl_data->result_array();
            $model_qp_data['bloom_data'] = $bloom_lvl_result;

            $pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes FROM clo_po_map as co_po
							  JOIN measures as msr ON msr.msr_id = co_po.msr_id 
							  WHERE co_po.crs_id = "' . $crs_id . '" 
								AND crclm_id = "' . $crclm_id . '" 
							  ORDER BY msr.pi_codes ASC';
            $pi_code_data = $this->db->query($pi_code_query);
            $pi_code_result = $pi_code_data->result_array();
            $model_qp_data['pi_code_list'] = $pi_code_result;

            $qp_entity_config_query = 'SELECT entity_id 
									   FROM entity WHERE qpf_config = 1
									   ORDER BY qpf_config_orderby';
            $qp_entity_config_data = $this->db->query($qp_entity_config_query);
            $qp_entity_config_result = $qp_entity_config_data->result_array();
            $model_qp_data['entity_list'] = $qp_entity_config_result;

            return $model_qp_data;
        } else {
            return 0;
        }
    }

    /**
     * Function to fetch CIA model qp
     * @parameters: curriculum id, term id, course id and qp type
     * @return: array
     */
    public function model_qp_course_data($crclm_id, $term_id, $crs_id) {
        $model_qp_meta_data_query = 'SELECT qpd.qpd_id, qpd.qpf_id, qpd.qpd_type, qpd.qpd_title, qpd.qpd_timing, 
										qpd.qpd_max_marks, qpd.qpd_notes, qpd.qpd_num_units, qpd.qpd_gt_marks, crs.crs_title, crs.crs_code
									 FROM qp_definition AS qpd
									 JOIN course AS crs ON qpd.crs_id = crs.crs_id
									 WHERE qpd.crclm_id = "' . $crclm_id . '"
										AND qpd.crclm_term_id = "' . $term_id . '"
										AND qpd.crs_id = "' . $crs_id . '" 										
										AND qpd.qpd_type = 3
										AND qpd.qpf_id >=0 
										AND qpd.cia_model_qp = 1 ';
        $model_qp_meta_data = $this->db->query($model_qp_meta_data_query);
        $model_qp_meta_data_res = $model_qp_meta_data->result_array();

        $course_data['qp_meta_data'] = $model_qp_meta_data_res;

        if (!empty($model_qp_meta_data_res)) {
            $size = count($model_qp_meta_data_res);
            for ($i = 0; $i < $size; $i++) {
                if (!empty($model_qp_meta_data_res[$i]['qpd_id'])) {
                    $qpd_unit_data_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, 
												unit.qp_attempt_unitquestion, mq.qp_mq_id, mq.qp_unitd_id, mq.qp_mq_flag, mq.qp_mq_code, 
												mq.qp_subq_code, mq.qp_content, mq.qp_subq_marks, fetch_qp_mapping_data_val(mq.qp_mq_id) AS mapping_data, fetch_qp_images(mq.qp_mq_id) AS image_name
											FROM qp_unit_definition AS unit
											JOIN qp_mainquestion_definition AS mq ON unit.qpd_unitd_id = mq.qp_unitd_id
											WHERE unit.qpd_id = "' . $model_qp_meta_data_res[$i]['qpd_id'] . '"';
                    $qpd_unit_data = $this->db->query($qpd_unit_data_query);
                    $qpd_unit_data_res[] = $qpd_unit_data->result_array();

                    $qpd_unit_query = 'SELECT unit.qpd_unitd_id, unit.qp_unit_code, unit.qp_total_unitquestion, 
											unit.qp_attempt_unitquestion, unit.qp_utotal_marks
									   FROM qp_unit_definition as unit 
									   WHERE unit.qpd_id = "' . $model_qp_meta_data_res[$i]['qpd_id'] . '"';
                    $qpd_unit = $this->db->query($qpd_unit_query);
                    $qpd_unit_res[] = $qpd_unit->result_array();

                    $result = $this->po_list($model_qp_meta_data_res[$i]['qpd_id']);
                    $po_data[] = $result['po_data'];
                } else {
                    
                }
            }
        } else {
            $qpd_unit_data_res[] = '';
            $qpd_unit_res = '';
        }
//var_dump($qpd_unit_data_res);
        if (!empty($po_data)) {
            $course_data['po_data'] = $po_data;
        } else {
            $course_data['po_data'] = '';
        }

        if (!empty($qpd_unit_data_res)) {
            $course_data['main_qp_data'] = $qpd_unit_data_res;
        } else {
            $course_data['main_qp_data'] = '';
        }
        if (!empty($qpd_unit_res)) {
            $course_data['unit_cia_data'] = $qpd_unit_res;
        } else {
            $course_data['unit_cia_data'] = '';
        }
        $course_co_data_query = 'SELECT clo_id, clo_code, clo_statement, fetch_po_list_data(clo_id) as po_data
								 FROM clo 
								 WHERE crclm_id = "' . $crclm_id . '" 
									AND term_id = "' . $term_id . '" 
									AND crs_id = "' . $crs_id . '"';
        $course_co_data = $this->db->query($course_co_data_query);
        $course_co_data_result = $course_co_data->result_array();
        $course_data['co_data'] = $course_co_data_result;

        $topic_query = 'SELECT topic_id, topic_title 
						FROM topic 
						WHERE curriculum_id = "' . $crclm_id . '" 
							AND term_id = "' . $term_id . '" 
							AND course_id = "' . $crs_id . '"';
        $topic_data = $this->db->query($topic_query);
        $topic_result = $topic_data->result_array();
        $course_data['topic_data'] = $topic_result;

        $bloom_lvl_query = 'SELECT bloom_id, level, bloom_actionverbs 
							FROM bloom_level';
        $bloom_lvl_data = $this->db->query($bloom_lvl_query);
        $bloom_lvl_result = $bloom_lvl_data->result_array();
        $course_data['bloom_data'] = $bloom_lvl_result;

        $pi_code_query = 'SELECT DISTINCT co_po.msr_id, crs_id, msr.msr_statement, msr.pi_codes 
						  FROM clo_po_map as co_po
						  JOIN measures as msr ON msr.msr_id = co_po.msr_id 
						  WHERE co_po.crs_id = "' . $crs_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
						  ORDER BY msr.pi_codes ASC';
        $pi_code_data = $this->db->query($pi_code_query);
        $pi_code_result = $pi_code_data->result_array();
        $course_data['pi_code_list'] = $pi_code_result;

        $qp_entity_config_query = 'SELECT entity_id 
								   FROM entity 
								   WHERE qpf_config = 1 
								   ORDER BY qpf_config_orderby';
        $qp_entity_config_data = $this->db->query($qp_entity_config_query);
        $qp_entity_config_result = $qp_entity_config_data->result_array();
        $course_data['entity_list'] = $qp_entity_config_result;

        return $course_data;
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

    /**
     * function to fetch qpd_id
     * @parameters:curriculum id ,term id course id
     * @return:array
     */
    public function qpd_id($curriculum_id, $term_id, $course_id) {
        $crs_qp_count = 'SELECT qpd_id 
						 FROM qp_definition 
						 WHERE crs_id = "' . $course_id . '" 
							AND qpd_type = 4 
							AND crclm_id = "' . $curriculum_id . '" 
							AND crclm_term_id = "' . $term_id . '"';
        $crs_qp_count_data = $this->db->query($crs_qp_count);
        $qpd_id_r = $crs_qp_count_data->result_array();
        return($qpd_id_r);
    }

    /**
     * function to fetch selected po's in QP.
     * @parameters:qpd_id
     * @return:array
     */
    public function po_list($qpd_id) {
        $query = 'SELECT qpd_unitd_id 
				  FROM qp_unit_definition 
				  WHERE qpd_id = "' . $qpd_id . '"';
        $query_re = $this->db->query($query);
        $count = $query_re->num_rows();
        $qpd_unitd_id = $query_re->result_array();

        $po_result = array();
        $po_data_string = array();

        for ($i = 0; $i < $count; $i++) {
            $data1 = 'SELECT * from qp_unit_definition AS qm
					  JOIN qp_mainquestion_definition AS q ON qm.qpd_unitd_id = q.qp_unitd_id
					  WHERE q.qp_unitd_id = "' . $qpd_unitd_id[$i]['qpd_unitd_id'] . '"
						AND qm.qpd_id = "' . $qpd_id . '"
					  ORDER BY CAST(q.qp_subq_code AS UNSIGNED), q.qp_subq_code ASC';
            $data1 = $this->db->query($data1);
            $data[$i] = $data1->result_array();

            $size = count($data[$i]);

            for ($p = 0; $p < $size; $p++) {
                $po_query = 'SELECT qp_map.actual_mapped_id, po.po_reference, po.po_statement 
							 FROM qp_mapping_definition as qp_map 
							 JOIN po as po ON po.po_id = qp_map.actual_mapped_id
							 WHERE qp_map.qp_mq_id = "' . $data[$i][$p]['qp_mq_id'] . '" 
								AND qp_map.entity_id =6';
                $po_data = $this->db->query($po_query);
                $po_result[] = $po_data->result_array();
            }
        }

        $po_data_array = array();
        $po_siz = count($po_result);

        for ($po = 0; $po < $po_siz; $po++) {
            $po_count = count($po_result[$po]);

            for ($p = 0; $p < $po_count; $p++) {
                $po_data_array[$po][] = $po_result[$po][$p]['po_reference'];
            }

            if (!empty($po_data_array[$po])) {
                $po_data_string[] = implode(",", $po_data_array[$po]);
            } else {
                $po_data_string[] = '';
            }
        }

        $val['po_data'] = $po_data_string;

        return $val;
    }

    /* Function to fetch Justification of a particular curriculum from the notes table
     * @parameters: curriculum id ,term id, course id
     * @return: Justification data or null .
     */

    public function justification_details($curriculum_id, $term_id, $course_id) {
        $notes = 'SELECT notes 
				  FROM notes
				  WHERE crclm_id = "' . $curriculum_id . '" 
					AND entity_id = 16 
					AND term_id="' . $term_id . '"
					AND particular_id="' . $course_id . '"';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();

        if (!empty($notes[0]['notes'])) {
            return ($notes[0]['notes']);
        } else {
            return null;
        }
    }

}
?>

