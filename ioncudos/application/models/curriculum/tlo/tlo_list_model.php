<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description		: Model Logic for TLO Listing and Deleting  operations performed through this file.	  
 * Modification History :
 * Date				Modified By                                 Description
 * 05-09-2013      		Mritunjay B S                           Added file headers, function headers & comments. 
 * 11-04-2014			Jevi V G     	        		Added help entity.
 * 11-12-2015			Shayista Mulla				Added toolpit to dropdowns,column's to Add Review/Assignment Question.
 * 20-10-2016			Neha Kulkarni				Addition of new column - actual delivery date
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo_list_model extends CI_Model {
    /*
     * Function is to  fetch the topic list .
     * @param - ------.
     * returns list of topics.
     */

    public function topic_list() {
        $topic_list = 'SELECT t.topic_id,t.topic_title,o.tlo_statement
			FROM  topic as t, tlo as o
			WHERE t.topic_id = o.topic_id';
        $topic_list_result = $this->db->query($topic_list);
        $topic_list_data = $topic_list_result->result_array();
        $topic_list_return['topic_list_data'] = $topic_list_data;
        return $topic_list_return;
    }

    /*
     * Function is to  fetch the curriculum data .
     * @param - ------.
     * returns curriculum details.
     */

    public function fetch_crclm_name() {

        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $curriculum_name_query = 'SELECT c.crclm_id, c.crclm_name 
									FROM curriculum AS c, users AS u, program AS p
									WHERE u.id = "' . $loggedin_user_id . '" 
									AND u.user_dept_id = p.dept_id 
									AND c.pgm_id = p.pgm_id 
									AND c.status = 1 
									ORDER BY c.crclm_name ASC ';
        $curriculum_name_data = $this->db->query($curriculum_name_query);
        $curriculum_name_result = $curriculum_name_data->result_array();
        $curriculum_data['curriculum_name'] = $curriculum_name_result;

        return $curriculum_data;
    }

    /*
     * Function is to  fetch the topic details.
     * @param - topic id used to fetch the particular topic data.
     * returns topic details.
     */

    public function topic_details($topic_id) {

        $topic_data_query = ' SELECT topic_title , state_id
                              FROM topic 
                              WHERE topic_id = "' . $topic_id . '" ';
        $topic_data = $this->db->query($topic_data_query);
        $topic_details_result = $topic_data->result_array();
        $topic_fetch_data['topic'] = $topic_details_result;

        $tlo_query = 'SELECT t.tlo_id, t.tlo_code, t.tlo_statement, bl.level, bl.bloom_actionverbs, bl.description
                      FROM tlo t,bloom_level bl
                      WHERE t.bloom_id = bl.bloom_id
					  AND topic_id = "' . $topic_id . '"
					  ORDER BY LPAD(LOWER(tlo_code),5,0) ASC';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();
        $topic_fetch_data['tlo_result'] = $tlo_result;

        return $topic_fetch_data;
    }

    /*
     * Function is to  fetch the topic details.
     * @param - topic id used to fetch the particular topic data.
     * returns topic details.
     */

    public function topic_details_edit($topic_id) {

        $topic_data_query = ' SELECT topic_title , state_id
                              FROM topic 
                              WHERE topic_id = "' . $topic_id . '" ';
        $topic_data = $this->db->query($topic_data_query);
        $topic_details_result = $topic_data->result_array();
        $topic_fetch_data['topic'] = $topic_details_result;

        $tlo_query = 'SELECT t.tlo_code,t.tlo_id,t.tlo_statement,t.delivery_approach,t.course_id,t.bloom_id,t.term_id,
									t.topic_id,t.curriculum_id, dmtd.delivery_mtd_id,md.delivery_mtd_name,dmtd.map_tlo_dm_id,dmtd.delivery_mtd_id,t.bloom_id,md.crclm_dm_id
							FROM tlo as t
							LEFT JOIN bloom_level as b on b.bloom_id = t.bloom_id
							LEFT JOIN map_tlo_delivery_method dmtd on dmtd.tlo_id = t.tlo_id
							LEFT JOIN map_crclm_deliverymethod md on md.crclm_dm_id = dmtd.delivery_mtd_id
							WHERE t.topic_id = "' . $topic_id . '" 
					  ORDER BY LPAD(LOWER(tlo_code),5,0) ASC';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();
        $topic_fetch_data['tlo_result'] = $tlo_result;

        return $topic_fetch_data;
    }

    /*
     * Function is to set the topic status.
     * @param - topic id used to set the particular topic status.
     * returns topic details.
     */

    function topic_status($topic_id, $status) {
        $pgm_status_query = ' UPDATE topic SET status = "' . $status . '" 
                              WHERE topic_id = "' . $topic_id . '" ';
        $pgm_status_data = $this->db->query($pgm_status_query);
        $pgm_status_result = $pgm_status_data->result_array();
        $ret['rows'] = $pgm_status_result;
        return $ret;
    }

    /*
     * Function is to fetch the curriculum data to fill the curriculum drop down box.
     * @param - -----.
     * returns the curriculum list.
     */

    public function crclm_drop_down_fill() {

        if ($this->ion_auth->is_admin()) {
            $crclm_details_query = ' SELECT c.crclm_id, c.crclm_name 
										FROM curriculum AS c,  program AS p
										WHERE c.pgm_id = p.pgm_id 
										AND c.status = 1
										ORDER BY c.crclm_name ASC ';
            $crclm_details_data = $this->db->query($crclm_details_query);
            $crclm_details_result = $crclm_details_data->result_array();
            $crclm_data['crclm_details'] = $crclm_details_result;
            return $crclm_data;
        } else {
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $crclm_details_query = ' SELECT c.crclm_id, c.crclm_name 
										FROM curriculum AS c, users AS u, program AS p
										WHERE u.id = "' . $loggedin_user_id . '" 
										AND u.user_dept_id = p.dept_id 
										AND c.pgm_id = p.pgm_id 
										AND c.status = 1
										ORDER BY c.crclm_name ASC ';
            $crclm_details_data = $this->db->query($crclm_details_query);
            $crclm_details_result = $crclm_details_data->result_array();
            $crclm_data['crclm_details'] = $crclm_details_result;
            return $crclm_data;
        }
    }

    /*
     * Function is to fetch the term list to fill the term drop down box.
     * @param - curriculum id is used to get the particular curriculum term list.
     * returns the term list.
     */

    public function term_drop_down_fill($crclm_id) {
        $term_details_query = ' SELECT term_name,crclm_term_id  
                                FROM crclm_terms 
                                WHERE crclm_id = "' . $crclm_id . '" ';
        $term_details_data = $this->db->query($term_details_query);
        $term_details_result = $term_details_data->result_array();
        $term_data['term_details'] = $term_details_result;
        return $term_data;
    }

    /*
     * Function is to fetch the course list to fill the term drop down box.
     * @param - term id and user id used to fetch the user associative course.
     * returns the course list.
     */

    public function course_drop_down_fill($term_id, $user) {
        $user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner')) {
            $course_name_query = 'SELECT distinct(o.crs_id),c.crs_title  
								 FROM course as c, course_clo_owner as o
								 WHERE o.crclm_term_id="' . $term_id . '" 
								 AND o.crs_id=c.crs_id 
								 AND c.state_id >= 4 
								 AND c.status = 1
								 AND c.crs_mode = 0
								 ORDER BY c.crs_title ASC ';
            $course_name_data = $this->db->query($course_name_query);
            $course_name_result = $course_name_data->result_array();

            $course_data['course_details'] = $course_name_result;

            return $course_data;
        } else if ($this->ion_auth->in_group('Course Owner')) {
            $course_name_query = 'SELECT distinct(o.crs_id),c.crs_title  
								  FROM course as c, course_clo_owner as o
								  WHERE o.crclm_term_id="' . $term_id . '" 
								  AND o.crs_id=c.crs_id 
								  AND c.state_id >= 4 
								  AND c.status = 1
								  AND c.crs_mode = 0
								  AND o.clo_owner_id = "' . $user_id . '" 
								  ORDER BY c.crs_title ASC ';
            $course_name_data = $this->db->query($course_name_query);
            $course_name_result = $course_name_data->result_array();

            $course_data['course_details'] = $course_name_result;

            return $course_data;
        }
    }

    /*
     * Function is to fetch the course list for static view page to fill the term drop down box.
     * @param - term id and user id used to fetch the user associative course.
     * returns the course list.
     */

    public function static_course_fill($term_id, $crclm_id) {

        $course_name_query = '  SELECT crs_id,crs_title  
                                FROM course  
                                WHERE crclm_term_id = "' . $term_id . '" 
								AND crclm_id = "' . $crclm_id . '" ';
        $course_name_data = $this->db->query($course_name_query);
        $course_name_result = $course_name_data->result_array();
        $course_data['course_details'] = $course_name_result;
        return $course_data;
    }

    /*
     * Function is to fetch the topic list to fill the term drop down box.
     * @param - course id is used to fetch the topic list of particular course.
     * returns the topic list.
     */

    public function topic_drop_down_fill($crs_id) {
        $topic_details_query = 'SELECT topic_id,topic_title,course_id  
                                FROM topic 
                                WHERE course_id = "' . $crs_id . '" ';
        $topic_details_data = $this->db->query($topic_details_query);
        $topic_details_result = $topic_details_data->result_array();
        $topic_data['topic_details'] = $topic_details_result;
        return $topic_data;
    }

    /**
     * Function to fetch help related details for tlo, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function tlo_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 12';
        $result = $this->db->query($help);
        $row = $result->result_array();
        $data['help_data'] = $row;

        if (!empty($data['help_data'])) {
            $help_entity_id = $row[0]['serial_no'];

            $file_query = 'SELECT help_entity_id, file_path
						   FROM uploads 
						   WHERE help_entity_id = "' . $help_entity_id . '"';
            $file_data = $this->db->query($file_query);
            $file_name = $file_data->result_array();
            $data['file'] = $file_name;

            return $data;
        } else {
            return $data;
        }
    }

    /**
     * Function to fetch help related to tlo to display
      the help contents in a new window
     * @parameters: help id
     * @return: entity data and help description
     */
    public function help_content($help_id) {
        $help = 'SELECT entity_data, help_desc 
				 FROM help_content 
				 WHERE serial_no = "' . $help_id . '"';
        $result_help = $this->db->query($help);
        $row = $result_help->result_array();

        return $row;
    }

    public function topic_details_by_topic_id($topic_id = NULL) {
        $topic_data_query = 'SELECT t.topic_title,t.topic_hrs
		  FROM `topic` as t
		  WHERE t.topic_id = "' . $topic_id . '"';
        $topic_data = $this->db->query($topic_data_query);
        $topic_result = $topic_data->result_array();
        return $topic_result;
    }

    public function tlo_details($curriculum_id, $term_id, $course_id, $topic_id) {
        $data['tlo_details'] = $this->db
                ->select('topic_title, topic_hrs, topic_content,t_unit_id')
                ->where('topic_id', $topic_id)
                ->get('topic')
                ->result_array();
        $data['curriculum_details'] = $this->db
                ->select('crclm_name')
                ->where('crclm_id', $curriculum_id)
                ->get('curriculum')
                ->result_array();
        $data['term_details'] = $this->db
                ->select('term_name')
                ->where('crclm_term_id', $term_id)
                ->get('crclm_terms')
                ->result_array();
        $data['course_details'] = $this->db
                ->select('crs_title,crs_mode, crs_code')
                ->where('crs_id', $course_id)
                ->get('course')
                ->result_array();
        return $data;
    }

    public function topic_tlo_ids($topic_id) {

        return $this->db
                        ->select('tlo_id')
                        ->where('topic_id', $topic_id)
                        ->get('topic_question')
                        ->result_array();
    }

    public function bloom_level() {

        return $this->db
                        ->select('bloom_id, level')
                        ->get('bloom_level')
                        ->result_array();
    }

    public function pi_code($course_id) {
        $pi_code_data = $this->db
                ->select('measures.pi_codes')
                ->join('clo_po_map', 'clo_po_map.msr_id = measures.msr_id')
                ->where('clo_po_map.crs_id', $course_id)
                ->group_by('measures.pi_codes')
                ->get('measures')
                ->result_array();

        return $pi_code_data;
    }

    public function add_lesson_schedule($topic_hrs, $question_counter, $assignment_counter, $portion_per_hour, $curriculum_id, $term_id, $course_id, $topic_id, $review_question, $tlo_ids, $assignment_question, $image_hidden, $bloom_id, $pi_id) {

        $kmax = $topic_hrs;
        for ($k = 0; $k < $kmax; $k++) {
            $lesson_data = array(
                'portion_per_hour' => $portion_per_hour[$k],
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );

            $this->db->insert('topic_lesson_schedule', $lesson_data);
        }
        $review_question_size = sizeof($review_question);

        for ($i = 0; $i < $review_question_size; $i++) {
            $question_data = array(
                'review_question' => $review_question[$i],
                'exercise_question' => 0,
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'tlo_id' => @$tlo_ids[$i],
                'bloom_id' => @$bloom_id[$i],
                'pi_codes' => @$pi_id[$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );

            $this->db->insert('topic_question', $question_data);

            $question_id = $this->db->insert_id();

            $image_size = sizeof($image_hidden[$i]);
            if ($image_hidden[$i] != false) {
                for ($j = 0; $j < $image_size; $j++) {
                    $image_map_data = array(
                        'question_id' => $question_id,
                        'image_name' => $image_hidden[$i][$j],
                        'curriculum_id' => $curriculum_id,
                        'term_id' => $term_id,
                        'course_id' => $course_id,
                        'topic_id' => $topic_id,
                        'uploaded_by' => $this->ion_auth->user()->row()->id,
                        'uploaded_date' => date('Y-m-d')
                    );
                    $this->db->insert('upload_image', $image_map_data);
                }
            } else {
                
            }
        }

        $kmax = sizeof($assignment_counter);

        for ($i = 0; $i < $kmax; $i++) {
            $assignment_data = array(
                'assignment_content' => $assignment_question[$i],
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );
            $this->db->insert('topic_assignments', $assignment_data);
        }
    }

    /**
     * Function to fetch TLO  type details
     * @return: TLO id and TLO name
     */
    function list_tlo($topic_id) {
        return $this->db
                        ->select('tlo_id, tlo_statement')
                        ->where('topic_id', $topic_id)
                        ->get('tlo')
                        ->result_array();
    }

    public function edit_lesson_schedule($topic_id) {

        $lesson_data['portion_details'] = $this->db
                ->select('portion_per_hour, topic_id')
                ->where('topic_id', $topic_id)
                ->get('topic_lesson_schedule')
                ->result_array();
        $lesson_data['review_question_details'] = $this->db
                ->select('review_question,question_id, tlo_id,bloom_id,pi_codes')
                ->where('topic_id', $topic_id)
                ->get('topic_question')
                ->result_array();

        foreach ($lesson_data['review_question_details'] as $img_fetch) {
            $lesson_data['image_for_questions'][] = $this->db
                    ->select('image_name, question_id')
                    ->where('question_id', $img_fetch['question_id'])
                    ->get('upload_image')
                    ->result_array();
        }

        $lesson_data['assignment_details'] = $this->db
                ->select('assignment_content')
                ->where('topic_id', $topic_id)
                ->get('topic_assignments')
                ->result_array();

        $lesson_data['org_details'] = $this->db
                ->select('oe_pi_flag')
                ->get('organisation')
                ->result_array();

        return $lesson_data;
    }

    /*
     * Function to update lesson schedule
     * @param ---portion,portionId
     * return --count
     */

    public function update_lesson_schedule_data($portion_slNo, $portion, $portionId, $topic_id, $curriculumId, $termId, $courseId, $conduct_date, $actual_delivery_date) {

        $portion = $this->db->escape_str($portion);
        $query = 'select * from topic_lesson_schedule where portion_per_hour="' . $portion . '" and lesson_schedule_id!="' . $portionId . '" and curriculum_id="' . $curriculumId . '" and term_id="' . $termId . '"and course_id="' . $courseId . '" and topic_id="' . $topic_id . '"';
        $lesson_data = $this->db->query($query);
        $count = $lesson_data->num_rows();
        if ($count == 0) {
            //$modified_by   => $this->ion_auth->user()->row()->id;
            //$modified_date => date('Y-m-d');
            $lesson_schedule_query = ' UPDATE topic_lesson_schedule SET portion_ref = "' . $portion_slNo . '",portion_per_hour = "' . $portion . '", modified_by = "' . $this->ion_auth->user()->row()->id . '", modified_date = "' . date('Y-m-d') . '", conduction_date="' . $conduct_date . '" , actual_delivery_date="' . $actual_delivery_date . '"
                              WHERE lesson_schedule_id = "' . $portionId . '" ';
            $lesson_schedule_data = $this->db->query($lesson_schedule_query);
        } else {
            
        }
        return $count;
    }

    public function update_lesson_schedule_data_old($topic_hrs, $rvw_cntr_size, $assn_cntr_size, $portion_per_hour, $curriculum_id, $term_id, $course_id, $topic_id, $review_question, $image_hidden, $tlo_ids, $assignment_question, $bloom_id, $pi_code) {

        $delete_query = 'DELETE FROM topic_lesson_schedule WHERE topic_id = "' . $topic_id . '" ';
        $delete_data = $this->db->query($delete_query);

        for ($i = 0; $i < $topic_hrs; $i++) {
            $lesson_data = array(
                'portion_per_hour' => $portion_per_hour[$i],
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );

            $this->db->insert('topic_lesson_schedule', $lesson_data);
        }
        $delete_question_query = 'DELETE FROM topic_question WHERE topic_id = "' . $topic_id . '" ';
        $delete_question_data = $this->db->query($delete_question_query);

        for ($i = 0; $i < $rvw_cntr_size; $i++) {

            $question_data = array(
                'review_question' => $review_question[$i],
                'exercise_question' => 0,
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'tlo_id' => $tlo_ids[$i],
                'bloom_id' => $bloom_id[$i],
                'pi_codes' => $pi_code[$i],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));

            $this->db->insert('topic_question', $question_data);

            $question_id = $this->db->insert_id();

            $image_size = sizeof($image_hidden[$i]);
            for ($j = 0; $j < $image_size; $j++) {
                $image_map_data = array(
                    'question_id' => $question_id,
                    'image_name' => $image_hidden[$i][$j],
                    'curriculum_id' => $curriculum_id,
                    'term_id' => $term_id,
                    'course_id' => $course_id,
                    'topic_id' => $topic_id,
                    'uploaded_by' => $this->ion_auth->user()->row()->id,
                    'uploaded_date' => date('Y-m-d')
                );
                $this->db->insert('upload_image', $image_map_data);
            }
        }
        $delete_ass_question_query = 'DELETE FROM topic_assignments WHERE topic_id = "' . $topic_id . '" ';
        $delete_ass_question_data = $this->db->query($delete_ass_question_query);


        for ($i = 0; $i < $assn_cntr_size; $i++) {

            $assignment_data = array(
                'assignment_content' => $assignment_question[$i],
                'curriculum_id' => $curriculum_id,
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));

            $this->db->insert('topic_assignments', $assignment_data);
        }

        return true;
    }

    /*
     *  Function is to uplaod the help document for the particular entity.
     *  @param - entity id and file name is used to upload the document.
     *  returns the success message.
     */

    public function upload_data($file_name, $review_question) {
        $img_data_insert_query = 'INSERT INTO topic_question(review_question, image_path) 
		                            VALUES("' . $review_question . '", "' . $file_name . '")';
        $data = $this->db->query($img_data_insert_query);
        return true;
    }

    /*
     * Function is to fetch the term list to fill the term drop down box.
     * @param - curriculum id is used to get the particular curriculum term list.
     * returns the term list.
     */

    public function bl_drop_down_fill($tlo_id) {
        $bl_details_query = ' SELECT b.bloom_id, b.level, b.description ,b.bloom_actionverbs
                                FROM map_tlo_bloom_level as map
                                    INNER JOIN bloom_level  AS b
                                    ON b.bloom_id=map.bloom_id
                                    WHERE tlo_id="' . $tlo_id . '" ';

        $bl_details_data = $this->db->query($bl_details_query);
        $bl_details = $bl_details_data->result_array();

        return $bl_details;
    }

    /*
     * Function is to fetch the term list to fill the term drop down box.
     * @param - curriculum id is used to get the particular curriculum term list.
     * returns the term list.
     */

    public function pi_drop_down_fill($course_id) {
        $pi_code_data = $this->db
                ->select('measures.pi_codes,clo_po_map.crs_id,measures.msr_statement')
                ->join('clo_po_map', 'clo_po_map.msr_id = measures.msr_id')
                ->where('clo_po_map.crs_id', $course_id)
                ->group_by('measures.pi_codes')
                ->get('measures')
                ->result_array();

        return $pi_code_data;
    }

    /* Function is used to fetch the Course Owner User for POs to PEOs Mapping from course_clo_owner table.
     * @param - curriculum id, crs_id.
     * @returns- a array value of the Course Owner details.
     */

    public function fetch_course_owner($curriculum_id, $crs_id) {
        $course_owner_query = 'SELECT o.clo_owner_id, u.title, u.first_name, u.last_name, c.crclm_name
								FROM  course_clo_owner AS o, users AS u, curriculum AS c
								WHERE o.crs_id = "' . $crs_id . '" 
								AND o.clo_owner_id = u.id 
								AND c.crclm_id = "' . $curriculum_id . '" ';
        $course_owner = $this->db->query($course_owner_query);
        $course_owner = $course_owner->result_array();
        return $course_owner;
    }

// End of function fetch_course_owner.	

    /*
     * Function to insert lesson schedule
     * @param ---topic_hrs,portion_per_hour,topicId,curriculumId,termId,courseId
     * return ---count
     */

    public function insert_schedule($portion_slNo, $portion_per_hour, $conduction_date, $actual_delivery_date_1, $topicId, $curriculumId, $termId, $courseId) {
        $query = 'update topic set state_id=2 where topic_id="' . $topicId . '"';
        $this->db->query($query);
        $portion_per_hour = $this->db->escape_str($portion_per_hour);
        $this->db->select('*')
                ->from(' topic_lesson_schedule')
                ->where('portion_per_hour', $portion_per_hour)
                ->where('curriculum_id', $curriculumId)
                ->where('term_id', $termId)
                ->where('course_id', $courseId)
                ->where('topic_id', $topicId);
        $result = $this->db->get();
        $count = $result->num_rows();


        if ($count == 0) {

            $lesson_data = array(
                'topic_id' => $topicId,
                'portion_ref' => $portion_slNo,
                'portion_per_hour' => $portion_per_hour,
                'curriculum_id' => $curriculumId,
                'term_id' => $termId,
                'course_id	' => $courseId,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'),
                'conduction_date' => $conduction_date,
                'actual_delivery_date' => $actual_delivery_date_1
            );
            $this->db->insert('topic_lesson_schedule', $lesson_data);
        } else {
            
        }


        return $count;
    }

    /*
     * Function to fetch lesson schedule
     * @param ---topic_id
     * return ---lesson schedule details
     */

    public function lesson_schedule_data($topic_id) {
        $fetch_lesson_query = 'SELECT l.portion_ref,l.portion_per_hour,l.topic_id,l.curriculum_id,l.term_id,l.course_id,l.lesson_schedule_id,l.conduction_date, l.actual_delivery_date
							  FROM topic_lesson_schedule as l where topic_id="' . $topic_id . '" order by lesson_schedule_id';
        $fetch_lesson_data = $this->db->query($fetch_lesson_query);
        $fetch_lesson_details = $fetch_lesson_data->result_array();
        /* 	$query='update topic set state_id=2 where topic_id="'.$topic_id.'"';
          $this->db->query($query); */
        return $fetch_lesson_details;
    }

    /*
     * Function to delete lesson schedule data
     * @param ---portion_id
     * return ---lesson schedule details
     */

    public function delete_schedule($portion_id) {
        $delete_query = 'DELETE FROM topic_lesson_schedule WHERE lesson_schedule_id = "' . $portion_id . '" ';
        $delete_data = $this->db->query($delete_query);
        return true;
    }

    /*
     * Function update review/assignment question detail
     * @param ---question,questionId,queId,topic_id,curriculumId,termId,courseId,tlo_id,blo_id
     * return ---number of questions
     */

    public function update_question_data($question, $questionId, $queId, $tlo_id, $blo_id, $pi_id, $curriculumId, $termId, $courseId, $topic_id) {
        $question = $this->db->escape_str($question);
        if(!$tlo_id){
            $tlo_id = 'NULL';
        }
        if(!$blo_id){
            $blo_id = 'NULL';
        }
        if(!$pi_id){
            $pi_id = 'NULL';
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $date = date('y-m-d');
        if ($queId == 1) {
            $query = 'select * from topic_question where review_question="' . $question . '" and question_id!="' . $questionId . '" and curriculum_id="' . $curriculumId . '"and term_id="' . $termId . '" and course_id="' . $courseId . '"and topic_id="' . $topic_id . '"';
            $question_data = $this->db->query($query);
            $count = $question_data->num_rows();
            if ($count == 0) {
                $question_query = "UPDATE topic_question SET assignment_question=NULL, que_id=$queId, review_question = '".$question."', tlo_id=$tlo_id ,pi_codes=$pi_id, bloom_id=$blo_id,  modified_by = $user_id, modified_date = $date WHERE question_id =$questionId";
                $question_data = $this->db->query($question_query);
            }
        } else {
            $query = 'select * from topic_question where assignment_question="' . $question . '" and question_id!="' . $questionId . '" and curriculum_id="' . $curriculumId . '"and term_id="' . $termId . '" and course_id="' . $courseId . '"and topic_id="' . $topic_id . '"';
            $question_data = $this->db->query($query);
            $count = $question_data->num_rows();
            if ($count == 0) {
                $question_query = "UPDATE topic_question SET assignment_question = '".$question."', que_id=$queId, review_question=NULL, tlo_id=$tlo_id ,pi_codes=$pi_id, bloom_id=$blo_id,  modified_by = $user_id, modified_date =$date WHERE question_id =$questionId";
                $question_data = $this->db->query($question_query);
            }
        }
        return $count;
    }

    /*
     * Function to fetch all questions
     * @param ---topic_id
     * return --- question details
     */

    public function all_questions($topic_id) {
        $question_list = 'select tlo_id,que_id,question_id,topic_question.bloom_id,pi_codes,COALESCE(review_question,assignment_question) 			question,bloom_level.level from topic_question join bloom_level on bloom_level.bloom_id=topic_question.bloom_id where topic_id="' . $topic_id . '"  order by question_id';
        $question_list_result = $this->db->query($question_list);
        $question_list_data = $question_list_result->result_array();
        return $question_list_data;
    }

    // Start of Added/Edited functions by chetan 
    /*
     * Function to insert review/assignment question
     * @param ---tlo_id,blo_id,question,question_id,topicId,curriculumId,termId,courseId
     * return ---number of questions
     */
    public function insert_question($pi_id, $tlo_id, $blo_id, $question, $question_id, $topicId, $curriculumId, $termId, $courseId) {
        $question = $this->db->escape_str($question);
        if ($question_id == 1) {
            $this->db->select('*')
                    ->from('topic_question')
                    ->where('review_question', $question)
                    ->where('curriculum_id', $curriculumId)
                    ->where('term_id', $termId)
                    ->where('course_id', $courseId)
                    ->where('topic_id', $topicId);
            $result = $this->db->get();
            $count = $result->num_rows();
            if ($count == 0) {
                $question_details = array(
                    'review_question' => $question,
                    'que_id' => $question_id,
                    'topic_id' => $topicId,
                    'tlo_id' => @$tlo_id,
                    'bloom_id' => @$blo_id,
                    'curriculum_id' => $curriculumId,
                    'term_id' => $termId,
                    'pi_codes' => @$pi_id,
                    'course_id' => $courseId,
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );
                $this->db->insert('topic_question', $question_details);
            }
        } else {
            $this->db->select('*')
                    ->from('topic_question')
                    ->where('assignment_question', $question)
                    ->where('curriculum_id', $curriculumId)
                    ->where('term_id', $termId)
                    ->where('course_id', $courseId)
                    ->where('topic_id', $topicId);
            $result = $this->db->get();
            $count = $result->num_rows();
            if ($count == 0) {
                $question_details = array(
                    'assignment_question' => $question,
                    'que_id' => $question_id,
                    'topic_id' => $topicId,
                    'tlo_id' => @$tlo_id,
                    'bloom_id' => @$blo_id,
                    'curriculum_id' => $curriculumId,
                    'term_id' => $termId,
                    'pi_codes' => @$pi_id,
                    'course_id' => $courseId,
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );
                $this->db->insert('topic_question', $question_details);
            }
        }
        $query = 'update topic set state_id=2 where topic_id="' . $topicId . '"';
        $this->db->query($query);
        return $count;
    }

    /*
     * Function to delete review/assignment question
     * @param ---question_id
     * return ---question details
     */

    public function delete_question($question_id, $curriculumId, $termId, $courseId, $topic_id) {
        $delete_data = $this->db->delete('topic_question', array('question_id' => $question_id));
        return $delete_data;
    }

    /* Function to fetch performance indicator table data to show data in toolpit.
     * @param 
     * return --- performance indicator table column details
     */

    public function pi_drop_down_tooltip($course_id) {
        $pi_details_query = ' SELECT po.po_statement,performance_indicator.pi_statement,clo_po_map.crs_id 
                                FROM clo_po_map
				inner join po on clo_po_map.po_id=po.po_id
				inner join performance_indicator on clo_po_map.pi_id=performance_indicator.pi_id
				where clo_po_map.crs_id="' . $course_id . '"';

        $pi_details_data = $this->db->query($pi_details_query);
        return $pi_details_result = $pi_details_data->result_array();
    }

//End of the function pi_drop_down_tooltip

    /* Function to fetch TLO,PI,bloom's level table data to show with questions.
     * @param 
     * return --- tables column data
     */

    public function list_questions($topic_id) {
        
       // if ($query_result->num_rows() > 0) {
            $list_questions_query = 'SELECT tq.question_id, tq.que_id, 
                                        CONCAT(IF(tq.review_question is NULL OR tq.review_question="0","",tq.review_question),
                                        IF(tq.assignment_question is NULL OR tq.assignment_question="0","",tq.assignment_question)) as question, 
                                        tlo.tlo_statement,msr.msr_statement, 
                                        bloom_level.level,bloom_level.bloom_id,
                                        bloom_level.description,bloom_level.bloom_actionverbs,
                                        tlo.tlo_id,tlo.tlo_code, msr.pi_codes
					FROM topic_question as tq
					LEFT join  bloom_level on bloom_level.bloom_id=tq.bloom_id
					LEFT join tlo on tlo.tlo_id=tq.tlo_id
                                        LEFT join measures as msr ON msr.pi_codes = tq.pi_codes
					where tq.topic_id="' . $topic_id . '"';

            $list_questions_data = $this->db->query($list_questions_query);
            return $list_questions_result = $list_questions_data->result_array();
//        } else {
//            $list_questions_query = 'SELECT question_id, CONCAT(IF(tq.review_question is NULL OR tq.review_question="0","",tq.review_question),IF(tq.assignment_question is NULL OR tq.assignment_question="0","",tq.assignment_question)) as question, tlo.tlo_statement,bloom_level.level,bloom_level.bloom_id,bloom_level.description,bloom_level.bloom_actionverbs,tlo.tlo_id
//					FROM topic_question as tq
//					LEFT join  bloom_level on bloom_level.bloom_id=tq.bloom_id
//					LEFT join tlo on tlo.tlo_id=tq.tlo_id
//					where tq.topic_id="' . $topic_id . '"';
//
//            $list_questions_data = $this->db->query($list_questions_query);
//            return $list_questions_result = $list_questions_data->result_array();
//        }
    }

// End of the function list_questions.
    /* Function is used to fetch the mapped bloom's level details.
     * @param - .
     * @returns- a array value of the bloom's levels  .
     */

    public function tlo_bloom_level_details($tlo_id) {
        $tlo_bloom_level_query = $this->db->query('SELECT b.level, b.description, b.bloom_actionverbs
						   FROM map_tlo_bloom_level m
						   LEFT JOIN bloom_level b ON m.bloom_id = b.bloom_id
						   WHERE m.tlo_id =' . $tlo_id);
        $tlo_bloom_level_data = $tlo_bloom_level_query->result_array();
        return $tlo_bloom_level_data;
    }

}
?>
