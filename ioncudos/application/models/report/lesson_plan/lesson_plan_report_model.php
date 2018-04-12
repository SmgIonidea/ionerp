<?php
/**
 * Description	:	Generates Lesson Plan

 * Created		:	October 24th, 2013

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lesson_plan_report_model extends CI_Model {

    public $lesson_plan = "lesson_plan";

	/**
	 * Function to fetch curriculum details from curriculum table
	 * @parameter: user id and curriculum id
	 * @return: curriculum id and curriculum name
	 */
    function lesson_plan_curriculum($user_id, $curriculum_id) {
		if ($this->ion_auth->is_admin()) {
			//logged in user is admin
			$curriculum_query = 'SELECT crclm_id, crclm_name
								 FROM curriculum
								 WHERE status = 1
								 ORDER BY crclm_name ASC';
			$curriculum_result_data = $this->db->query($curriculum_query);
			$curriculum_result = $curriculum_result_data->result_array();
			
			$curriculum_fetch_data['curriculum_result'] = $curriculum_result;
			
			if ($curriculum_id) {
				$curriculum_query = 'SELECT start_year, end_year
									 FROM curriculum
									 WHERE crclm_id = "' . $curriculum_id . '"';
				$curriculum_result = $this->db->query($curriculum_query);
				$curriculum_date = $curriculum_result->result_array();
				
				$curriculum_fetch_data['curriculum_date'] = $curriculum_date;
			}
			return $curriculum_fetch_data;
		} else if ($user_id) {
			//logged in user is not admin
			$curriculum_query = 'SELECT c.crclm_id, c.crclm_name, c.start_year, c.end_year
								 FROM curriculum AS c, users AS u, program AS p, department AS d 
								 WHERE u.id = "' . $user_id . '" 
									AND u.user_dept_id = p.dept_id 
									AND c.pgm_id = p.pgm_id 
									AND p.dept_id = d.dept_id 
									AND c.status = 1
								 ORDER BY c.crclm_name ASC';
			$curriculum_result_data = $this->db->query($curriculum_query);
			$curriculum_result = $curriculum_result_data->result_array();
			
			$curriculum_fetch_data['curriculum_result'] = $curriculum_result;
			
			return $curriculum_fetch_data;
		}
		
		if ($curriculum_id) {
			$curriculum_query = 'SELECT start_year, end_year
								 FROM curriculum
								 WHERE crclm_id = "' . $curriculum_id . '"';
			$curriculum_result = $this->db->query($curriculum_query);
			$curriculum_date = $curriculum_result->result_array();
			
			$curriculum_fetch_data['curriculum_date'] = $curriculum_date;
			return $curriculum_fetch_data;
		}
    }
	
	/**
	 * Function to fetch curriculum details from curriculum table (Static Page)
	 * @return: curriculum id and curriculum name
	 */
    function lesson_plan_curriculum_static() {
		$curriculum_query = 'SELECT crclm_id, crclm_name 
							 FROM curriculum';
        $curriculum_result = $this->db->query($curriculum_query);
        $curriculum_result = $curriculum_result->result_array();
		
        $curriculum_fetch_data['curriculum_result'] = $curriculum_result;
        return $curriculum_fetch_data;
    }
		
	/**
	 * Function to fetch term details from curriculum term table
	 * @parameters: curriculum id and term id
	 * @return: term name and curriculum term id
	 */
    public function term_details($curriculum_id, $term_id) {
		if ($curriculum_id) {
			$term_name = 'SELECT term_name, crclm_term_id 
						  FROM crclm_terms 
						  WHERE crclm_id = "' . $curriculum_id . '"';
			$term_result = $this->db->query($term_name);
			$term_result_data = $term_result->result_array();
			$term_data['term_result_data'] = $term_result_data;
			
			return $term_data;
		}
		
		if ($term_id) {
			$term_name = 'SELECT term_name, crclm_term_id 
						  FROM crclm_terms 
						  WHERE crclm_term_id = "' . $term_id . '"';
			$term_result = $this->db->query($term_name);
			$term_result_data = $term_result->result_array();
			$term_data['term_result_data'] = $term_result_data;
			
			return $term_data;
		}
    }

	/**
	 * Function to fetch course details from course table
	 * @parameters: curriculum id and term id
	 * @return: course id and course title
	 */
    public function term_course_details($curriculum_id, $term_id) {	
		$course_name = 'SELECT crs_id, crs_title 
						FROM course 
						WHERE crclm_id = "' . $curriculum_id . '" 
							AND crclm_term_id = "' . $term_id . '" 
							AND status = 1
							AND state_id > 1
							AND crs_mode = 0
						ORDER BY crs_title ASC';
        $course_result = $this->db->query($course_name);
        $course_result_data = $course_result->result_array();
        $course_data['course_result_data'] = $course_result_data;
		
        return $course_data;
    }
	
	/**
	 * Function to fetch course details, course owner and course validator from course table, course clo owner table and
	   course clo validator table respectively
	 * @parameters: 
	 * @return:
	*/
	public function course_plan_details($curriculum_id, $term_id, $course_id) {	
		$course_plan_query = 'SELECT crs_code, crs_title, contact_hours, cie_marks, see_marks, see_duration, create_date
							  FROM course
							  WHERE crclm_id = "' . $curriculum_id . '"
								AND crclm_term_id = "' . $term_id . '"
								AND crs_id = "' . $course_id . '"';
		$course_plan_details = $this->db->query($course_plan_query);
		$course_plan_details_data = $course_plan_details->result_array();
		$data['course_details'] = $course_plan_details_data;
		
		$course_plan_owner = 'SELECT u.title, u.first_name, u.last_name, u.username, o.clo_owner_id
							  FROM course_clo_owner AS o, users AS u
							  WHERE o.crs_id   = "' . $course_id . '"
								AND u.id = o.clo_owner_id';
		$course_plan_author = $this->db->query($course_plan_owner);
		$course_plan_author_data = $course_plan_author->result_array();
		$data['course_owner'] = $course_plan_author_data;
		
		$course_validator = 'SELECT u.title, u.first_name, u.last_name, u.username, o.validator_id, o.last_date
							 FROM course_clo_validator AS o, users AS u
							 WHERE o.crs_id = "' . $course_id . '"
								AND u.id = o.validator_id';
		$course_plan_validator = $this->db->query($course_validator);
		$course_plan_validator_data = $course_plan_validator->result_array();
		$data['course_validator'] = $course_plan_validator_data;
		
		return $data;
	}
	
	/**
	 * Function to fetch prerequisite courses
	 * @parameters: course id
	 * @return: prerequisites id, course id and course title
	*/
	public function prerequisites($course_id) {
		/* $prerequisites_details = 'SELECT pc.predecessor_course_id, c.crs_id, c.crs_title, c.crs_code 
								  FROM predecessor_courses as pc, course as c
								  WHERE pc.crs_id = "' . $course_id . '"
									AND pc.predecessor_course_id = c.crs_id'; */
		$prerequisites_details = 'SELECT predecessor_course
								  FROM predecessor_courses
								  WHERE crs_id = "' . $course_id . '"';
		$prerequisites_data = $this->db->query($prerequisites_details);
		$course_prerequisites_data = $prerequisites_data->result_array();
		$data['course_prerequisites'] = $course_prerequisites_data;

		return $data['course_prerequisites'];
	}
	
	/**
	 * Function to fetch course learning objective details
	 * @parameters: curriculum id, term id and course id
	 * @return: course learning objective statements
	*/
	public function clo_details($curriculum_id, $term_id, $course_id) {
		$course_learning_statements = 'SELECT clo_statement 
									   FROM clo
									   WHERE crclm_id = "' . $curriculum_id . '"
											AND term_id = "' . $term_id . '" 
											AND crs_id = "' . $course_id . '"
									   ORDER BY clo_statement asc';
		$course_learning = $this->db->query($course_learning_statements);
		$course_learning_objectives = $course_learning->result_array();
		$data['course_learning_objectives'] = $course_learning_objectives;
		
		return $data['course_learning_objectives'];
	}
	
	/**
	 * Function to fetch course details from the course table
	 * @parameters: course id
	 * @return: course code
	*/
	public function course_code($course_id) {
		$course_code_details = 'SELECT crs_code
								FROM course
								WHERE crs_id = "'. $course_id .'"';
		$course_code_data = $this->db->query($course_code_details);
		$course_code = $course_code_data->result_array();
		$data['course_code'] = $course_code;
		
		if($data['course_code']) {
			return $data['course_code'][0]['crs_code'];
		}
	}
	
	/**
	 * Function to fetch course credits from course table
	 * @parameters: course id
	 * @return: lecture credits, tutorial credits and practical credits
	*/
	public function l_t_P($course_id) {
		$l_t_p_details = 'SELECT practical_credits, tutorial_credits, lect_credits
						  FROM course
						  WHERE crs_id = "' . $course_id . '"';
		$l_t_p_data = $this->db->query($l_t_p_details);
		$l_t_p = $l_t_p_data->result_array();
		$data['l_t_p'] = $l_t_p;
		
		if($data['l_t_p']) {
			return $data['l_t_p'];
		}
	}
	
	/**
	 * Function to fetch course details from course table
	 * @parameters: course id
	 * @return: course title
	*/
	public function course_title($course_id) {
		$course_title_details = 'SELECT crs_title
								 FROM course
								 WHERE crs_id = "'. $course_id .'"';
		$course_title_data = $this->db->query($course_title_details);
		$course_title = $course_title_data->result_array();
		$data['course_code'] = $course_title;
		
		if($data['course_code']) {
			return $data['course_code'][0]['crs_title'];
		}
	}
	
	/**
	 * Function to fetch continuous internal evaluation marks from course table
	 * @parameters: course id
	 * @return: continuous internal evaluation marks
	 */
	public function cie_marks($course_id) {
		$cie_marks_details = 'SELECT cie_marks
							  FROM course
							  WHERE crs_id = "'. $course_id .'"';
		$cie_marks_data = $this->db->query($cie_marks_details);
		$cie_marks = $cie_marks_data->result_array();
		$data['cie_marks'] = $cie_marks;
		
		if($data['cie_marks']) {
			return $data['cie_marks'][0]['cie_marks'];
		}
	}
	
	/**
	 * Function to fetch semester end exam marks from course table
	 * @parameters: course id
	 * @return: semester end exam marks
	 */
	public function see_marks($course_id) {
		$see_marks_details = 'SELECT see_marks
							  FROM course
							  WHERE crs_id = "'. $course_id .'"';
		$see_marks_data = $this->db->query($see_marks_details);
		$see_marks = $see_marks_data->result_array();
		$data['see_marks'] = $see_marks;
		
		if($data['see_marks']) {
			return $data['see_marks'][0]['see_marks'];
		}
	}
	
	/**
	 * Function to fetch contact hours from course table
	 * @parameters: course id
	 * @return: contact hours
	 */
	public function teaching_hours($course_id) {
		$teaching_hours_details = 'SELECT contact_hours
								   FROM course
								   WHERE crs_id = "'. $course_id .'"';
		$teaching_hours_data = $this->db->query($teaching_hours_details);
		$teaching_hours = $teaching_hours_data->result_array();
		$data['teaching_hours'] = $teaching_hours;
		
		if ($data['teaching_hours']) {
			return $data['teaching_hours'][0]['contact_hours'];
		}
	}
	
	/**
	 * Function to fetch topic details from topic table
	 * @parameters: curriculum id, term id and course id
	 * @return: topic id, topic title, topic content and topic hours
	 */
    public function select_topic($curriculum_id, $term_id, $course_id) {	
		$topic_name = 'SELECT topic_id, topic_title, topic_content, topic_hrs, t_unit_id
					   FROM topic 
					   WHERE curriculum_id = "' . $curriculum_id . '"
							AND term_id = "' . $term_id . '"
							AND course_id = "' . $course_id . '"
					   ORDER BY t_unit_id asc';
        $topic_result = $this->db->query($topic_name);
        $topic_result_data = $topic_result->result_array();
        $data['topic_result_data'] = $topic_result_data;

		
		//fetch all the related textbooks and reference books
		$text_books_details = 'SELECT book_author, book_title, book_edition, book_publication, book_publication_year, book_type
							   FROM book
							   WHERE crs_id = "' . $course_id . '"';
		$text_books_data = $this->db->query($text_books_details);
		$text_books_result = $text_books_data->result_array();
		$data['text_books'] = $text_books_result;
		
        return $data;
    }
	
	/**
	 * Function to fetch text books from book table
	 * @parameters: course id
	 * @return: topic id, topic title, topic content and topic hours
	 */
	public function text_books($course_id) {
		$text_books_details = 'SELECT book_author, book_title, book_edition, book_publication, book_publication_year, book_type
							   FROM book
							   WHERE crs_id = "' . $course_id . '"';
		$text_books_data = $this->db->query($text_books_details);
		$text_books_result = $text_books_data->result_array();
		//$text_book['text_books'] = $text_books_result;
		
		//return $text_book;
		return $text_books_result;
	}
	
	/**
	 * Function to fetch course details and topic details from course table and topic table respectively
		and also to fetch topic learning outcome details and assessment details
	 * @parameters: course id and topic id
	 * @return: course code, course title, topic title, topic hours
	*/
	public function chapter_wise_plan_content($curriculum_id, $term_id, $course_id, $topic_id) {
		//to fetch course details from course table
		$course_plan_query = 'SELECT crs_code, crs_title
							  FROM course
							  WHERE crs_id = "' . $course_id . '"';
		$course_plan_details = $this->db->query($course_plan_query);
		$course_plan_details_data = $course_plan_details->result_array();
		$data['course_details'] = $course_plan_details_data;
		
		//to fetch topic details from topic table
		$topic_details = 'SELECT topic_title, topic_hrs
						  FROM topic
						  WHERE topic_id = "' . $topic_id . '"';
		$topic_data = $this->db->query($topic_details);
		$topic = $topic_data->result_array();
		$data['topic'] = $topic;

		//to fetch blooms details, topic learning details from tlo and bloom level table
		$topic_learning_objectives_details = 'SELECT DISTINCT t.tlo_id, t.bloom_id, t.tlo_statement, bl.bloom_id, bl.level, bl.description, 
												tlo_clo.clo_id, tlo_clo.tlo_id, cl.clo_statement, cl.clo_code, tlo_clo.pi
											  FROM tlo as t, bloom_level as bl, tlo_clo_map as tlo_clo, clo as cl, measures as m
											  WHERE t.course_id = "' . $course_id . '" 
												AND t.topic_id = "' . $topic_id . '"	 
												AND cl.clo_id = tlo_clo.clo_id
												AND t.tlo_id = tlo_clo.tlo_id
												AND t.topic_id = tlo_clo.topic_id
												AND t.bloom_id = bl.bloom_id group by t.tlo_id'  ;
		$topic_learning_objectives_data = $this->db->query($topic_learning_objectives_details);
		$topic_learning_objectives = $topic_learning_objectives_data->result_array();
		$data['topic_learning_objectives'] = $topic_learning_objectives;
				
		//to fetch pi codes using measure id
		$pi_codes_data = array();
		foreach($topic_learning_objectives as $tlo) {
			$pi_codes_query = 'SELECT pi_codes, tlo_id
							   FROM tlo_clo_map
							   WHERE tlo_id = "'.$tlo['tlo_id'].'"';
			$pi_codes_details = $this->db->query($pi_codes_query);
			$pi_codes_data[] = $pi_codes_details->result_array();
		}
			
		//convert two/multi dimensional array to single dimensional array
		$pi_codes_list =  new RecursiveArrayIterator($pi_codes_data);
		$data['pi_codes'] = iterator_to_array($pi_codes_list, false);
		
		//to fetch topic learning outcome id from tlo table
		$assessment_details = 'SELECT tlo_id
							   FROM tlo
							   WHERE curriculum_id = "' . $curriculum_id . '" 
									AND term_id = "' . $term_id . '" 
									AND course_id = "' . $course_id . '" 
									AND topic_id = "' . $topic_id . '"';
		$assessment_data = $this->db->query($assessment_details);
		$assessment = $assessment_data->result_array();
		$assessment_size = sizeof($assessment);
			
		$assess_name = array();
		for($i = 0; $i < $assessment_size; $i++) {
			//to fetch assessment id and topic learning outcome id from tlo assessment mapping table
			$assess_id_details = 'SELECT assess_id, tlo_id
								  FROM tlo_assessment_map 
								  WHERE tlo_id = "' . $assessment[$i]['tlo_id'] . '"';
			$assess_id_data = $this->db->query($assess_id_details);
			$assess_id[$i] = $assess_id_data->result_array();
			
			$assess_id_size = sizeof($assess_id[$i]);
			
			for($j = 0; $j < $assess_id_size; $j++) {
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
		
		//Portion per hour
		$data['lesson_details'] = $this->db
										->select('portion_per_hour')
										->where('topic_id',$topic_id)
										->get('topic_lesson_schedule')
										->result_array();
		$data['pi_code_data'] = $this->db
										->select('measures.pi_codes ')
										->join('measures','tlo_clo_map.pi = measures.pi_id')
										//->join('bloom_level','topic_question.bloom_id = bloom_level.bloom_id')
										->where('tlo_clo_map.topic_id',$topic_id)
										->get('tlo_clo_map')
										->result_array();
		
		//review question	
		$data['review_question_details'] = $this->db
												  ->select('topic_question.question_id, topic_question.review_question, topic_question.tlo_id, topic_question.bloom_id, topic_question.pi_codes, tlo.tlo_statement, bloom_level.level, fetch_images(topic_question.question_id) as image, tlo.tlo_code')
												->join('tlo','tlo.tlo_id = topic_question.tlo_id')
												->join('bloom_level','topic_question.bloom_id = bloom_level.bloom_id')
												//->join('upload_image','topic_question.question_id = upload_image.question_id')
												->where('topic_question.topic_id',$topic_id)
												->get('topic_question')
												->result_array();
		
		return $data;
	}
	
	/**
	 * Function to fetch lesson details for chapter wise plan
	 * @parameters: course id and topic id
	 * @return: course code, course title, topic title, topic hours
	*/
	public function lesson_details($topic_id) {
		//Portion per hour
		$portion_data = $this->db
							 ->select('portion_per_hour')
							 ->where('topic_id',$topic_id)
							 ->get('topic_lesson_schedule')
							 ->result_array();
	
		$review_question_data = $this->db
									 ->select('topic_question.question_id, topic_question.review_question, topic_question.tlo_id, topic_question.bloom_id, topic_question.pi_codes, tlo.tlo_statement, bloom_level.level, tlo.tlo_code')
									 ->join('tlo','tlo.tlo_id = topic_question.tlo_id')
									 ->join('bloom_level','topic_question.bloom_id = bloom_level.bloom_id')
									 ->where('topic_question.topic_id',$topic_id)
									 ->get('topic_question')
									 ->result_array();
										
		$assignment_question_data = $this->db
										 ->select('assignment_content')
										 ->where('topic_id',$topic_id)
									 	 ->get('topic_assignments')
										 ->result_array();
										
		$image_data = $this->db
							->select('question_id, image_name')
							->get('upload_image')
							->result_array();
	
		$data['image_details'] = $image_data;
		$data['assignment_details'] = $assignment_question_data;
		$data['portion_details'] = $portion_data;
		$data['question_details'] = $review_question_data;
		
		return $data;
	}
	
	
	/**
	 * Function to fetch topic learning objectives statements from topic learning objective table
	 * @parameters: curriculum id, term id, course id, topic id
	 * @return: topic learning objective statements
	*/
	public function tlo_details($curriculum_id, $term_id, $course_id, $topic_id) {
		$topic_learning_statements = 'SELECT tlo_statement 
									  FROM tlo
									  WHERE curriculum_id = "' . $curriculum_id . '"
										AND term_id = "' . $term_id . '" 
										AND course_id = "' . $course_id . '"
										AND topic_id = "' . $topic_id . '"
									  ORDER BY tlo_statement asc';
		$topic_learning = $this->db->query($topic_learning_statements);
		$topic_learning_objectives = $topic_learning->result_array();
		$data['topic_learning_objectives'] = $topic_learning_objectives;
		
		return $data['topic_learning_objectives'];
	}
	
	/**
	 * Function to fetch course details and topic details from course table and topic table respectively
		and also to fetch topic learning outcome details and assessment details
	 * @parameters: course id and topic id
	 * @return: course code, course title, topic title, topic hours
	*/
	public function course_unitization_content($curriculum_id, $term_id, $course_id) {	
		//Topic data
		$topic_data_query = 'SELECT t.topic_id, t.topic_hrs, t.t_unit_id, t.topic_title, unit.t_unit_name
							 FROM topic as t, topic_unit as unit 
							 WHERE t.t_unit_id = unit.t_unit_id
								AND t.course_id = "'.$course_id.'"';
		$topic_data = $this->db->query($topic_data_query);
		$topic_result = $topic_data->result_array();
		$data['topic_details'] = $topic_result;
	
		//to fetch assessment details
		$assessment_query = 'SELECT assessment_mode, assessment_id, assessment_name, weightage_in_marks
							 FROM cie_evaluation_scheme
							 WHERE crs_id = "' . $course_id . '"
								AND assessment_mode = 0';
		$assessment_details = $this->db->query($assessment_query);
		$assessment_data = $assessment_details->result_array();
		$data['assessment'] = $assessment_data;
		
		if(!empty($assessment_data)) {
			foreach($assessment_data as $cie_id) {
				$crs_unitization = 'SELECT cu.crs_unitization_id, cu.topic_id, cu.no_of_questions, cu.assessment_id, cu.crs_id, t.topic_id, t.topic_title
									From course_unitization as cu, topic as t
									WHERE cu.topic_id = t.topic_id 
										AND cu.assessment_id = "' . $cie_id['assessment_id'] . '" ';
				$course_unitization_data = $this->db->query($crs_unitization);
				$course_unitization_result[] = $course_unitization_data->result_array();
			}
			$data['crs_unitization'] = $course_unitization_result;
		} else {
			$data['crs_unitization'] = 0;
		}
		
		return $data;
	}
	
	/**
	 * Function to fetch measures and pi statements from measures and performance indicator table
	 * @parameters: course id and topic id
	 * @return: course code, course title, topic title, topic hours
	*/
	public function oe_pi_content($curriculum_id, $term_id, $course_id) {
		$oe_pi_query = 'SELECT DISTINCT msr.msr_statement, msr.pi_codes, pi.pi_statement, clo_po.pi_id, clo_po.msr_id
						FROM measures as msr, clo_po_map as clo_po, performance_indicator as pi
						WHERE clo_po.pi_id = pi.pi_id
							AND clo_po.msr_id = msr.msr_id
							AND clo_po.crs_id = "'.$course_id.'"';
		$oe_pi_data = $this->db->query($oe_pi_query);	
		$oe_pi_result = $oe_pi_data->result_array();
		
		return $oe_pi_result;
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
}

/*
 * End of file lesson_plan_report_model.php
 * Location: .report/clo_po_map/lesson_plan_report_model.php 
 */
?>