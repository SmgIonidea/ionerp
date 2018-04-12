<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for List of Topics, Provides the fecility to Edit and Delete the particular Topic and its Contents.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S           Added file headers, function headers & comments. 
 * 11-04-2014		Jevi V G     	        Added help entity.
 * 08-05-2015		Abhinay B Angadi     	Included Delivery methods under list, add & edit views.
  ---------------------------------------------------------------------------------------------------------------------------------
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topic_model extends CI_Model {
    /*
     * Function is to fetch Topic data.
     * @param - ------.
     * returns -------.
     */

    public function topic_list() {
        $topic_list = 'SELECT topic_id,topic_title
                       FROM  topic';
        $topic_list_result = $this->db->query($topic_list);
        $topic_list_data = $topic_list_result->result_array();
        $topic_list_return['topic_list_data'] = $topic_list_data;
        return $topic_list_return;
    }

    /*
     * Function is to fetch curriculum details.
     * @param - ------.
     * returns -------.
     */

    public function curriculum_details() {
        $curriculum_data_query = 'SELECT c.crclm_id, c.crclm_name, t.topic_title 
                                  FROM curriculum AS c,topic AS t
								  ORDER BY c.crclm_name ASC';
        $curriculum_details_data = $this->db->query($curriculum_data_query);
        $curriculum_details_result = $curriculum_details_data->result_array();

        return $curriculum_details_result;
    }

    /*
     * Function is to fetch Topic details.
     * @param - course id is used to fetch the particular topic content
     * returns.
     */

    public function topic_details($course_id) {

        $topic_data_query = 'SELECT topic.state_id,topic_title,topic_id,topic_content,topic_hrs 
								FROM topic 
								WHERE category_id IS NULL
								AND course_id ="' . $course_id . '" ';
        $topic_data_data = $this->db->query($topic_data_query);
        $topic_data_result = $topic_data_data->result_array();
        $topic_data_result['topic_data'] = $topic_data_result;


        $course_state_query = 'SELECT  	topic_publish_flag 
								FROM course 
								WHERE crs_id ="' . $course_id . '" ';
        $course_state_data = $this->db->query($course_state_query);
        $course_state_result = $course_state_data->result_array();
        if (!empty($course_state_result)) {
            $topic_data_result['topic_flag'] = $course_state_result;
        } else {
            $topic_data_result['topic_flag'] = 0;
        }

        return $topic_data_result;
    }

    /*
     * Function is to fetch Topic details.
     * @param - course id is used to fetch the particular topic content
     * returns.
     */

    public function unit_wise_topic_details($course_id) {

        $topic_data_query = 'SELECT topic.state_id,topic_title,topic_id,topic_content,topic_hrs, topic_code, tu.t_unit_name, tu.t_unit_id 
								FROM topic 
                                                                JOIN topic_unit as tu ON tu.t_unit_id = topic.t_unit_id
								WHERE category_id IS NULL
								AND course_id = "' . $course_id . '" ORDER BY LENGTH(topic_code) ASC, topic_code ASC';
        $topic_data_data = $this->db->query($topic_data_query);
        $topic_data_result = $topic_data_data->result_array();
        $topic_data_result['topic_data'] = $topic_data_result;


        $course_state_query = 'SELECT  	topic_publish_flag 
                                FROM course 
                                WHERE crs_id ="'.$course_id.'" ';
        $course_state_data = $this->db->query($course_state_query);
        $course_state_result = $course_state_data->result_array();
        if (!empty($course_state_result)) {
            $topic_data_result['topic_flag'] = $course_state_result;
        } else {
            $topic_data_result['topic_flag'] = 0;
        }

        return $topic_data_result;
    }

	public function fetch_delivery_methods($topic_id){
		$query = $this->db->query('SELECT delivery_mtd_id FROM topic_delivery_method t where topic_id = "'.$topic_id.'"');
		return $query->result_array();
	}
	
    /*
     * Function is to update the program status.
     * @param - topic id and status value is used to update the particular program status
     * returns.
     */

    public function pgm_status($topic_id, $status) {
        $pgm_status_query = 'UPDATE topic SET status = "' . $status . '" where topic_id = "' . $topic_id . '" ';
        $pgm_status_data = $this->db->query($pgm_status_query);
        $pgm_status_result = $pgm_status_data->result_array();
        $pgm_status_data['pgm_status'] = $pgm_status_result;
        return $pgm_status_data;
    }

    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crclm_drop_down_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT c.crclm_id, c.crclm_name
								FROM curriculum AS c 
								WHERE c.status = 1
								ORDER BY c.crclm_name ASC';
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p
							    WHERE u.id = "' . $loggedin_user_id . '" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.status = 1
								   ORDER BY c.crclm_name ASC';
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							    FROM curriculum AS c, users AS u, program AS p ,course_clo_owner AS clo
							    WHERE u.id = "' . $loggedin_user_id . '" 
								   AND u.user_dept_id = p.dept_id
								   AND c.pgm_id = p.pgm_id
								   AND c.status = 1
								   AND u.id = clo.clo_owner_id
								   AND c.crclm_id = clo.crclm_id
								   ORDER BY c.crclm_name ASC';
        }

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $crclm_data['curriculum_list'] = $curriculum_list_result;

        return $curriculum_list_result;
    }

    /*
     * Function is to fetch the list of terms.
     * @param - curriculum id is used fetch the particular curriculum term list.
     * returns list of term names.
     */

    public function term_drop_down_fill($curriculum_id) {

        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('Course Owner') && !$this->ion_auth->in_group('admin') && !$this->ion_auth->in_group('Chairman') && !$this->ion_auth->in_group('Program Owner')) {
            $term_name = 'select DISTINCT ct.crclm_id, ct.term_name, ct.crclm_term_id ,c.clo_owner_id from course_clo_owner AS c, crclm_terms AS ct
					  where c.crclm_term_id = ct.crclm_term_id
					  AND c.clo_owner_id="' . $loggedin_user_id . '"
					  AND c.crclm_id = "' . $curriculum_id . '"';
        } else {
            $term_name = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
        }

        $term_list_data = $this->db->query($term_name);
        $term_list_result = $term_list_data->result_array();
        $term_data['term_list'] = $term_list_result;
        return $term_data;
    }

    /*
     * Function is to fetch the list of courses.
     * @param - term id and user id is used to fetch the particular term courses.
     * returns list of term courses.
     */

    public function course_drop_down_fill($term_id, $user) {
        if (!($this->ion_auth->is_admin() || $this->ion_auth->in_group('Program Owner'))) {
            $user_id = $this->ion_auth->user()->row()->id;
            $course_list_query = 'SELECT distinct(o.crs_id),c.crs_title  
								  FROM course as c, course_clo_owner as o
								  WHERE c.crs_mode IN (0,2)
								  AND o.crclm_term_id = c.crclm_term_id
								  AND o.crclm_term_id="' . $term_id . '" 
								  AND o.crs_id=c.crs_id 
								  AND c.status = 1
								  AND o.clo_owner_id = "' . $user_id . '" 
								  ORDER BY c.crs_title ASC';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_result = $course_list_data->result_array();
            $course_data['course_list'] = $course_list_result;
            return $course_data;
        } else {
            $course_list_query = 'SELECT distinct(o.crs_id),c.crs_title  
								 FROM course as c, course_clo_owner as o
								 WHERE c.crs_mode IN (0,2)
								 AND o.crclm_term_id = c.crclm_term_id
								 AND o.crclm_term_id="' . $term_id . '" 
								 AND o.crs_id=c.crs_id 
								 AND c.status = 1
								 ORDER BY c.crs_title ASC';

            $course_list_data = $this->db->query($course_list_query);
            $course_list_result = $course_list_data->result_array();
            $course_data['course_list'] = $course_list_result;
            return $course_data;
        }
    }

    /*
     * Function is to fetch the list of terms.
     * @param - curriculum id is used fetch the particular curriculum term list.
     * returns list of term names.
     */

    public function unit_drop_down_fill($crclm_id,$crs_id) {
        
        $check_crs_status = 'SELECT state_id FROM course where crs_id = "'.$crs_id.'"';
        $crs_stat_data = $this->db->query($check_crs_status);
        $crs_status = $crs_stat_data->row_array();
        
        $unit_query = 'SELECT pgm.pgm_id, pgm.total_topic_units, crclm.pgm_id 
						FROM program as pgm, curriculum as crclm
						where crclm.pgm_id = pgm.pgm_id AND crclm_id = "' . $crclm_id . '" ';
        $unit_data = $this->db->query($unit_query);
        $unit_result = $unit_data->result_array();

        $fetch_unit_query = 'SELECT t_unit_id, t_unit_name FROM topic_unit WHERE t_unit_id <= "' . $unit_result[0]['total_topic_units'] . '"';
        $fetch_unit_data = $this->db->query($fetch_unit_query);
        $fetch_unit_result = $fetch_unit_data->result_array();

        $data['crs_status'] = $crs_status['state_id'];
        $data['fetch_unit_result'] = $fetch_unit_result;
        return $data;
    }

    /* Function is used to fetch the Course state details from course table.
     * @param - course id.
     * @returns- a array of value course state details.
     */

    public function check_course_readiness($crclm_id, $crs_id) {
        $state_id = ' SELECT state_id
						FROM course
						WHERE crs_id = "' . $crs_id . '" 
						AND status = 1 ';
        $state_id = $this->db->query($state_id);
        $result['state_id'] = $state_id->result_array();

        $course_owner_query = 'SELECT o.clo_owner_id, u.title, u.first_name, u.last_name, c.crclm_name
								FROM  course_clo_owner AS o, users AS u, curriculum AS c
								WHERE o.crs_id = "' . $crs_id . '" 
								AND o.clo_owner_id = u.id 
								AND c.crclm_id = "' . $crclm_id . '" ';
        $course_owner = $this->db->query($course_owner_query);
        $result['course_owner'] = $course_owner->result_array();

        return $result;
    }

// End of function check_course_readiness.

    /* Function is used to fetch the Course topic publish flag details for add more topics from course table.
     * @param - course id.
     * @returns- a array of value course topic publish flag details.
     */

    public function check_course_delivery_publish_flag($crs_id) {
        return $this->db->select('topic_publish_flag')
                        ->where('crs_id', $crs_id)
                        ->where('status', 1)
                        ->get('course')
                        ->result_array();
    }

// End of function check_course_delivery_publish_flag.

    /* Function is used to insert the entry onto dashboard after the initiation of Course Delivery Planning is done.
     * @param - curriculum id, entity id, course id & many more.
     * @returns- an array of values of the term & course details.
     */

    public function finalized_publish_course($crclm_id, $entity_id, $particular_id, $sender_id, $receiver_id, $url, $description, $state, $status, $crclm_term_id) {
        // Dashboard entry to Initiate Course Delivery Planning 
        $crs_publish_data = array(
            'crclm_id' => $crclm_id,
            'entity_id' => $entity_id,
            'particular_id' => $particular_id,
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $description,
            'state' => $state,
            'status' => $status
        );
        $this->db->insert('dashboard', $crs_publish_data);
        $select = 'SELECT term_name FROM crclm_terms WHERE crclm_term_id = "' . $crclm_term_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $crs_publish_data['term'] = $row;
        $select = 'SELECT crs_title FROM course WHERE crs_id = "' . $particular_id . '" ';
        $select = $this->db->query($select);
        $row = $select->result_array();
        $crs_publish_data['course'] = $row;

        return $crs_publish_data;
    }

// End of function finalized_publish_course.	

    /* Function is used to update the course status after initiation of CLO creation is done.
     * @param - course id.
     * @returns- a boolean value.
     */

    public function publish_course_update_topic_flag_status($crs_id, $flag_value) {
        $update_topic_publish_flag = 'UPDATE course SET topic_publish_flag = "' . $flag_value . '" WHERE crs_id = "' . $crs_id . '" ';
        $result = $this->db->query($update_topic_publish_flag);
        return $result;
    }

// End of function publish_course_update_topic_flag_status.	

    public function add_books($course_id, $book_sl_no, $book_author, $book_title, $book_edition, $book_publication, $book_publication_year) {

        $this->db->trans_start();
        $kmax = sizeof($book_sl_no);

        for ($k = 0; $k < $kmax; $k++) {
            $book_data = array(
                'book_sl_no' => $book_sl_no[$k],
                'book_author' => $book_author[$k],
                'book_title' => $book_title[$k],
                'book_edition' => $book_edition[$k],
                'book_publication' => $book_publication[$k],
                'book_publication_year' => $book_publication_year[$k],
                'crs_id' => $course_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );



            $this->db->insert('book', $book_data);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function add_evaluation($course_id, $assessment_name, $assessment_mode, $weightage_in_marks) {
        $this->db->trans_start();
        $kmax = sizeof($assessment_name);

        for ($k = 0; $k < $kmax; $k++) {

            $evaluation_data = array(
                'assessment_name' => $assessment_name[$k],
                'assessment_mode' => $assessment_mode[$k],
                'weightage_in_marks' => $weightage_in_marks[$k],
                'crs_id' => $course_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );

            $this->db->insert('cie_evaluation_scheme', $evaluation_data);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function to fetch help related details for topic, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function topic_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 10';
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
     * Function to fetch help related to topic to display
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

    /*
     * Function to generate CIE evaluation table
     * @parameters: cie count val
     * @return: CIE evaluation table
     */

    public function generate_cie_table($cie_count, $crclm_id, $term_id, $crs_id) {
        $course_mode_query = 'SELECT crs_mode 
                                    FROM course 
                                    WHERE crs_id="' . $crs_id . '"';
        $course_mode_data = $this->db->query($course_mode_query);
        $course_mode = $course_mode_data->result_array();

        $course_mode = $course_mode[0]['crs_mode'];
        if ($course_mode == 1) {
            $generate_table_query = 'SELECT DISTINCT t.category_id as t_unit_id, tu.mt_details_name as t_unit_name 
									FROM topic as t, master_type_details as tu 
									WHERE t.category_id = tu.mt_details_id 
									AND  t.curriculum_id ="' . $crclm_id . '"
									AND t.term_id = "' . $term_id . '" 
									AND t.course_id = "' . $crs_id . '" ';

            $generate_table_data = $this->db->query($generate_table_query);

            $generate_table_result = $generate_table_data->result_array();
            $topic_table_query = 'SELECT category_id as t_unit_id, topic_id, topic_title, num_of_sessions as topic_hrs
								FROM topic
								WHERE curriculum_id ="' . $crclm_id . '" 
								AND term_id = "' . $term_id . '" 
								AND course_id = "' . $crs_id . '" ';
            $topic_table_data = $this->db->query($topic_table_query);
            $topic_table_result = $topic_table_data->result_array();
        } else {
            $generate_table_query = 'SELECT DISTINCT t.t_unit_id, tu.t_unit_name 
									FROM topic as t, topic_unit as tu 
									WHERE t.t_unit_id = tu.t_unit_id 
									AND  t.curriculum_id ="' . $crclm_id . '"
									AND t.term_id = "' . $term_id . '" 
									AND t.course_id = "' . $crs_id . '" ';

            $generate_table_data = $this->db->query($generate_table_query);
            $generate_table_result = $generate_table_data->result_array();

            $topic_table_query = 'SELECT t_unit_id, topic_id, topic_title, topic_hrs
								FROM topic
								WHERE curriculum_id ="' . $crclm_id . '" 
								AND term_id = "' . $term_id . '" 
								AND course_id = "' . $crs_id . '" ';
            $topic_table_data = $this->db->query($topic_table_query);
            $topic_table_result = $topic_table_data->result_array();
        }
        $cie_table_details['topic_unit_data'] = $generate_table_result;

        $cie_table_details['topic_data'] = $topic_table_result;

        return $cie_table_details;
    }

    /* Function is used to insert the entry onto dashboard after the initiation of Course Delivery Planning is done.
     * @param - curriculum id, entity id, course id & many more.
     * @returns- an array of values of the term & course details.
     */

    public function add_course_unitization($array_data, $course_id, $book_sl_no, $book_author, $book_title, $book_edition, $book_publication, $book_publication_year, $book_type, $assessment_name, $assessment_mode, $weightage_in_marks, $cie_counter_val, $check_count) {

        $array_size = sizeof($cie_counter_val);
        $array_data_size = sizeof($array_data);
        $topic_array_size = sizeof($array_data[0]['topic_id']);
        $k = 1;
        for ($i = 0; $i < $array_size; $i++) {
            $evaluation_data = array(
                'assessment_name' => $assessment_name[$i],
                'assessment_mode' => $assessment_mode[$i],
                'weightage_in_marks' => $weightage_in_marks[$i],
                'crs_id' => $course_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d')
            );

            $result = $this->db->insert('cie_evaluation_scheme', $evaluation_data);
            $assessment_id = $this->db->insert_id();
            $assessment_mode_query = 'SELECT assessment_mode 
										FROM cie_evaluation_scheme 
										WHERE assessment_id ="' . $assessment_id . '" ';
            $assessment_mode_data = $this->db->query($assessment_mode_query);
            $assessment_mode_result = $assessment_mode_data->result_array();

            if ($assessment_mode_result[0]['assessment_mode'] != 1) {

                for ($j = 0; $j < $topic_array_size; $j++) {

                    $course_unitization = array(
                        't_unit_id' => $array_data[$k - 1]['unit_id'][$j],
                        'topic_id' => $array_data[$k - 1]['topic_id'][$j],
                        'crs_id' => $course_id,
                        'no_of_questions' => $array_data[$k - 1]['question_id'][$j],
                        'assessment_id' => $assessment_id,
                        'created_by' => 1,
                        'modified_by' => 1,
                        'created_date' => 1,
                        'modified_date' => 1,
                    );
                    $this->db->insert('course_unitization', $course_unitization);
                }

                if ($k < ($array_size - $check_count)) {
                    $k++;
                }
            }
        }
    }

// End of function finalized_publish_course.

    public function update_course_unitization($array_data, $course_id, $book_id, $book_sl_no, $book_author, $book_title, $book_edition, $book_publication, $book_publication_year, $book_type, $assessment_id, $assessment_name, $assessment_mode, $weightage_in_marks, $cie_counter_val, $check_count, $assess_del_val) {

        $array_size = sizeof($cie_counter_val);
        $array_data_size = sizeof($array_data);

        $topic_array_size = sizeof($array_data[0]['topic_id']);
        $l = 1;
        $size_of_assessment_id = sizeof($assessment_name);

        if (!empty($assess_del_val)) {
            $assess_del_array_size = sizeof($assess_del_val);
            for ($del = 0; $del < $assess_del_array_size; $del++) {
                $cie_delete_query = 'DELETE FROM cie_evaluation_scheme WHERE assessment_id = "' . $assess_del_val[$del] . '"';
                $cie_delete_data = $this->db->query($cie_delete_query);

                $assess_delete_query = 'DELETE FROM course_unitization WHERE assessment_id = "' . $assess_del_val[$del] . '"';
                $assess_delete_data = $this->db->query($assess_delete_query);
            }

            for ($j = 0; $j < $size_of_assessment_id; $j++) {
                if ($assessment_id[$j] != false) {

                    $assessment_data = array(
                        'assessment_name' => $assessment_name[$j],
                        'assessment_mode' => $assessment_mode[$j],
                        'weightage_in_marks' => $weightage_in_marks[$j],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'modified_date' => date('Y-m-d'));
                    $this->db
                            ->where('assessment_id', $assessment_id[$j])
                            ->update('cie_evaluation_scheme', $assessment_data);


                    if ($assessment_mode[$j] != 1) {

                        $delete_query = 'Delete FROM course_unitization WHERE assessment_id = "' . $assessment_id[$j] . '" ';
                        $delete_data = $this->db->query($delete_query);
                        if ($array_data[$l - 1]['topic_id'] != false && $array_data[$l - 1]['question_id'] != false) {
                            for ($k = 0; $k < $topic_array_size; $k++) {

                                $course_unitization = array(
                                    'topic_id' => $array_data[$l - 1]['topic_id'][$k],
                                    'crs_id' => $course_id,
                                    'no_of_questions' => $array_data[$l - 1]['question_id'][$k],
                                    'assessment_id' => $assessment_id[$j],
                                    'created_by' => $this->ion_auth->user()->row()->id,
                                    'modified_by' => $this->ion_auth->user()->row()->id,
                                    'created_date' => date('Y-m-d'),
                                    'modified_date' => date('Y-m-d'));		
                                $this->db->insert('course_unitization', $course_unitization);
                            }
                        } else {
                            
                        }
                        if ($l < ($array_size - $check_count)) {
                            $l++;
                        }
                    }
                } else {
                    $evaluation_data = array(
                        'assessment_name' => $assessment_name[$j],
                        'assessment_mode' => $assessment_mode[$j],
                        'weightage_in_marks' => $weightage_in_marks[$j],
                        'crs_id' => $course_id,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('Y-m-d'));
                    $result = $this->db->insert('cie_evaluation_scheme', $evaluation_data);
                    $assessment_id = $this->db->insert_id();

                    if ($assessment_mode[$j] != 1) {
                        if ($array_data[$l - 1]['topic_id'] != false && $array_data[$l - 1]['question_id'] != false) {
                            for ($m = 0; $m < $topic_array_size; $m++) {

                                $course_unitization = array(
                                    'topic_id' => $array_data[$l - 1]['topic_id'][$m],
                                    'crs_id' => $course_id,
                                    'no_of_questions' => $array_data[$l - 1]['question_id'][$m],
                                    'assessment_id' => $assessment_id,
                                    'created_by' => 1,
                                    'modified_by' => 1,
                                    'created_date' => 1,
                                    'modified_date' => 1,
                                );
                                $this->db->insert('course_unitization', $course_unitization);
                            }
                        }
                        if ($l < ($array_size - $check_count)) {
                            $l++;
                        }
                    }
                }
            }
        } else {

            for ($j = 0; $j < $size_of_assessment_id; $j++) {
                if ($assessment_id[$j] != false) {

                    $assessment_data = array(
                        'assessment_name' => $assessment_name[$j],
                        'assessment_mode' => $assessment_mode[$j],
                        'weightage_in_marks' => $weightage_in_marks[$j],
                        'modified_by' => $this->ion_auth->user()->row()->id,
                        'modified_date' => date('Y-m-d'));
                    $this->db
                            ->where('assessment_id', $assessment_id[$j])
                            ->update('cie_evaluation_scheme', $assessment_data);


                    if ($assessment_mode[$j] != 1) {

                        $delete_query = 'Delete FROM course_unitization WHERE assessment_id = "' . $assessment_id[$j] . '" ';
                        $delete_data = $this->db->query($delete_query);
                        for ($k = 0; $k < $topic_array_size; $k++) {
                            //echo $array_data[$l-1]['question_id'][$k]."<br>".$assessment_id[$j];

                            $course_unitization = array(
                                'topic_id' => $array_data[$l - 1]['topic_id'][$k],
                                'crs_id' => $course_id,
                                'no_of_questions' => $array_data[$l - 1]['question_id'][$k],
                                'assessment_id' => $assessment_id[$j],
                                'created_by' => $this->ion_auth->user()->row()->id,
                                'modified_by' => $this->ion_auth->user()->row()->id,
                                'created_date' => date('Y-m-d'),
                                'modified_date' => date('Y-m-d'));
                            $this->db->insert('course_unitization', $course_unitization);
                        }

                        if ($l < ($array_size - $check_count)) {
                            $l++;
                        }
                    }
                } else {
                    $evaluation_data = array(
                        'assessment_name' => $assessment_name[$j],
                        'assessment_mode' => $assessment_mode[$j],
                        'weightage_in_marks' => $weightage_in_marks[$j],
                        'crs_id' => $course_id,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'created_date' => date('Y-m-d'));
                    $result = $this->db->insert('cie_evaluation_scheme', $evaluation_data);
                    $assessment_id = $this->db->insert_id();

                    if ($assessment_mode[$j] != 1) {
                        for ($m = 0; $m < $topic_array_size; $m++) {

                            $course_unitization = array(
                                'topic_id' => $array_data[$l - 1]['topic_id'][$m],
                                'crs_id' => $course_id,
                                'no_of_questions' => $array_data[$l - 1]['question_id'][$m],
                                'assessment_id' => $assessment_id,
                                'created_by' => 1,
                                'modified_by' => 1,
                                'created_date' => 1,
                                'modified_date' => 1,
                            );
                            $this->db->insert('course_unitization', $course_unitization);
                        }
                        if ($l < ($array_size - $check_count)) {
                            $l++;
                        }
                    }
                }
            }
        }
    }

    /*
      Function to check the data is already inserted for the particular course or not.
     */

    public function add_books_eval($course_id) {

        $course_unitization_result = '';
        $book_query = 'SELECT b.crs_id, cie.crs_id 
						FROM cie_evaluation_scheme as cie, book as b 
						WHERE cie.crs_id = b.crs_id AND cie.crs_id = "' . $course_id . '" ';
        $book_val = $this->db->query($book_query);
        $book_result = $book_val->result_array();

        $book_query1 = 'SELECT crs_id
						FROM cie_evaluation_scheme 
						WHERE crs_id = "' . $course_id . '" ';
        $book_val1 = $this->db->query($book_query1);
        $book_result1 = $book_val1->result_array();

        $reference_book_query = 'SELECT book_id, book_sl_no, book_author, book_title, book_edition, book_publication,
									book_publication_year, crs_id, book_type
									From book
									Where crs_id = "' . $course_id . '"';
        $reference_book_data = $this->db->query($reference_book_query);
        $reference_book_result = $reference_book_data->result_array();

        $cie_evaluation_query = 'SELECT assessment_id, assessment_name, assessment_mode, weightage_in_marks, crs_id
								  From cie_evaluation_scheme
								  WHERE crs_id = "' . $course_id . '"';
        $cie_evaluation_data = $this->db->query($cie_evaluation_query);
        $cie_evaluation_result = $cie_evaluation_data->result_array();

        $cie_id_query = 'SELECT assessment_id 
							From cie_evaluation_scheme
							 WHERE crs_id = "' . $course_id . '" AND assessment_mode = 0 ';
        $cie_id_data = $this->db->query($cie_id_query);
        $cie_id_result = $cie_id_data->result_array();

        foreach ($cie_id_result as $cie_id) {

            $crs_unitization = 'SELECT cu.crs_unitization_id, cu.topic_id, cu.no_of_questions, cu.assessment_id, cu.crs_id, t.topic_id, t.topic_title, cie.assessment_name
										From course_unitization as cu
										JOIN topic as t ON cu.topic_id = t.topic_id
										JOIN cie_evaluation_scheme as cie ON cu.assessment_id = cie.assessment_id
										WHERE cu.assessment_id = "' . $cie_id['assessment_id'] . '" ';
            $course_unitization_data = $this->db->query($crs_unitization);
            $course_unitization_result[] = $course_unitization_data->result_array();
        }
        $course_mode_query = 'SELECT crs_mode 
                                    FROM course 
                                    WHERE crs_id="' . $course_id . '"';
        $course_mode_data = $this->db->query($course_mode_query);
        $course_mode = $course_mode_data->result_array();

        $course_mode = $course_mode[0]['crs_mode'];

        if ($course_mode == 1) {
            $topic_data_query = 'SELECT t.topic_id, t.category_id AS t_unit_id, t.topic_title,t.num_of_sessions as topic_hrs, unit.mt_details_name  as t_unit_name
								FROM topic as t, master_type_details as unit 
								WHERE t.category_id = unit.mt_details_id
								AND t.course_id = "' . $course_id . '"';
            $topic_data = $this->db->query($topic_data_query);
            $topic_result = $topic_data->result_array();
        } else {
            $topic_data_query = 'SELECT t.topic_id, t.t_unit_id, t.topic_title,t.topic_hrs, unit.t_unit_name
								FROM topic as t, topic_unit as unit 
								WHERE t.t_unit_id = unit.t_unit_id
								AND t.course_id = "' . $course_id . '"';
            $topic_data = $this->db->query($topic_data_query);
            $topic_result = $topic_data->result_array();
        }
        $course_title_query = 'SELECT crs_title FROM course where crs_id = "' . $course_id . '"';
        $course_title_data = $this->db->query($course_title_query);
        $course_title_result = $course_title_data->result_array();
        $book_data['crs_data_exist'] = $book_result;
        $book_data['exist'] = $book_result1;
        $book_data['ref_book_data'] = $reference_book_result;
        $book_data['cie_scheme_data'] = $cie_evaluation_result;
        $book_data['unitization_data'] = $course_unitization_result;
        $book_data['topic_data'] = $topic_result;
        $book_data['crs_title'] = $course_title_result;
        return $book_data;
    }

    /* Function to count the number of topics within course */

    public function topic_count_data($crclm_id, $term_id, $crs_id) {

        $count_query = 'SELECT topic_id FROM topic WHERE curriculum_id = "' . $crclm_id . '" 
					AND term_id = "' . $term_id . '"
					AND course_id = "' . $crs_id . '" ';
        $count_data = $this->db->query($count_query);
        $count_result = $count_data->num_rows();
        return $count_result;
    }

    /* Function is used to fetch the delivery method for a specific Topic.
     * @param - .
     * @returns- a array value of the delivery method details.
     */

    public function topic_delivery_method_details($topic_id) {
        $topic_delivery_method_query = $this->db->query('SELECT d.crclm_dm_id, d.delivery_mtd_name
													FROM topic_delivery_method m
													LEFT JOIN map_crclm_deliverymethod d ON m.delivery_mtd_id = d.crclm_dm_id
													WHERE m.topic_id =' . $topic_id . '
													ORDER BY d.delivery_mtd_name ASC');
        $topic_delivery_method_data = $topic_delivery_method_query->result_array();
        return $topic_delivery_method_data;
    }

// End of function topic_delivery_method_details.	

    /*
     * Function is to fetch Topic details.
     * @param - course id is used to fetch the particular topic content
     * returns.
     */

    public function topic_details_new($course_id) {
        $topic_data_query = 'SELECT  t.state_id,topic_title,t.topic_id,topic_content,topic_hrs,t.state_id, t.topic_code, tu.t_unit_name, tu.t_unit_id 
								FROM topic  AS t 
								JOIN workflow_state AS w on t.state_id = w.state_id
								LEFT JOIN tlo as tl on t.topic_id=tl.topic_id
                                                                JOIN topic_unit as tu ON tu.t_unit_id = topic.t_unit_id
								WHERE t.category_id IS NULL
								AND t.course_id ="' . $course_id . '"
								GROUP BY tl.topic_id ORDER BY t.topic_code
								';
        $topic_data_data = $this->db->query($topic_data_query);
        $topic_data_result = $topic_data_data->result_array();
        $topic_data_result['topic_data'] = $topic_data_result;

        $course_state_query = 'SELECT  	topic_publish_flag 
								FROM course 
								WHERE crs_id ="' . $course_id . '" ';
        $course_state_data = $this->db->query($course_state_query);
        $course_state_result = $course_state_data->result_array();

        if (!empty($course_state_result)) {
            $topic_data_result['topic_flag'] = $course_state_result;
        } else {
            $topic_data_result['topic_flag'] = 0;
        }
        return $topic_data_result;
    }

    public function topic_test($topic_id, $course_id) {
        $topic_data_query = 'select * from tlo where topic_id="' . $topic_id . '"';
        $topic_data_data = $this->db->query($topic_data_query);
        $topic_data_result = $topic_data_data->result_array();
        $count = count($topic_data_result);
        return $count;
    }

    public function generae_book_table($course_id, $ref) {
        $query = 'select * from book where crs_id="' . $course_id . '" and book_type="' . $ref . '"';
        $query1 = $this->db->query($query);
        return $query1->result_array();
    }

    // Function to save book list
    public function save_book_list($course_id, $book_sl_no, $book_author, $book_title, $book_website, $book_edition, $book_publication, $book_type, $book_publication_year) {
        if ($book_publication_year == '') {
            $book_publication_year = NULL;
        }
        $book_list = array(
            'book_sl_no' => $book_sl_no,
            'book_author' => $book_author,
            'book_title' => $book_title,
	    'book_website' => $book_website,
            'book_edition' => $book_edition,
            'book_publication' => $book_publication,
            'book_publication_year' => $book_publication_year,
            'crs_id' => $course_id,
            'book_type' => $book_type,
            'created_by' => $this->ion_auth->user()->row()->id,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
            'modified_date' => date('Y-m-d'),
        );
        $this->db->insert('book', $book_list);
    }

    public function generate_book_list($course_id) {
        $query = 'select * from book where crs_id="' . $course_id . '"';
        $query1 = $this->db->query($query);
        return $query1->result_array();
    }

    public function delete_book($book_id) {
        $query = 'Delete FROM book WHERE book_id = "' . $book_id . '"';
        return $delete_data = $this->db->query($query);
    }

    public function update_book_list($course_id, $book_sl_no, $book_author, $book_title, $book_website, $book_edition, $book_publication, $book_type, $book_publication_year, $book_id) {
        if ($book_publication_year == '') {
            $book_publication_year = NULL;
        }
        $book_list = array(
            'book_sl_no' => $book_sl_no,
            'book_author' => $book_author,
            'book_title' => $book_title,
	    'book_website' => $book_website,
            'book_edition' => $book_edition,
            'book_publication' => $book_publication,
            'book_publication_year' => $book_publication_year,
            'book_type' => $book_type,
            'created_by' => $this->ion_auth->user()->row()->id,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
            'modified_date' => date('Y-m-d'));
        $this->db
                ->where('book_id', $book_id)
                ->update('book', $book_list);
    }

    public function proceed_tlo_co($topic_id) {
        $query = 'update topic set state_id=3 where topic_id="' . $topic_id . '"';
        return $this->db->query($query);
    }

    public function count_topic($topic_id) {
        
    }

    public function search_topics($topic_id) {
        $query = 'select * from tlo as  t
	 join  topic_lesson_schedule as tl ON t.topic_id=tl.topic_id
	 join  topic_question as tq ON t.topic_id=tq.topic_id
	where t.topic_id="' . $topic_id . '"';
        $data = $this->db->query($query);

        $query1 = 'select state_id from topic t where t.topic_id="' . $topic_id . '"';
        $data1 = $this->db->query($query1);

        $result['count_ls'] = $data->result_array();
        $val = $data1->result_array();
        $result['topic_state'] = $val[0];
        return $result;
    }

}

?>
