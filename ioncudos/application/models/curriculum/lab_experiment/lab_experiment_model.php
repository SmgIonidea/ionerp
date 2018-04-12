<?php

/**
 * Description	:	Lab experiment controller model and view

 * Created		:	March 24th, 2015

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_experiment_model extends CI_Model {
    /*
     * Function is to fetch the curriculum details.
     * @param - -----.
     * returns list of curriculum names.
     */

    public function crclm_drop_down_fill() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
				AND d.status=1
				WHERE c.status = 1
				GROUP BY c.crclm_id
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
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
				WHERE u.id = "' . $loggedin_user_id . '" 
				AND u.user_dept_id = p.dept_id
				AND c.pgm_id = p.pgm_id
				AND u.id = clo.clo_owner_id
				AND c.crclm_id = clo.crclm_id
				AND c.status = 1
				ORDER BY c.crclm_name ASC';
        }

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $crclm_data['curriculum_list'] = $curriculum_list_result;

        return $crclm_data;
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
				WHERE o.crclm_term_id="' . $term_id . '" 
				AND o.crs_id=c.crs_id 
				AND c.state_id >= 4
				AND c.status = 1 
				AND o.clo_owner_id = "' . $user_id . '" 
				AND c.crs_mode >= 1 
				ORDER BY c.crs_title ASC';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_result = $course_list_data->result_array();
            $course_data['course_list'] = $course_list_result;
            return $course_data;
        } else {
            $course_list_query = 'SELECT distinct(o.crs_id),c.crs_title  
				FROM course as c, course_clo_owner as o
				WHERE o.crclm_term_id="' . $term_id . '" 
				AND o.crs_id=c.crs_id 
				AND c.state_id >= 4
				AND c.status = 1
				AND c.crs_mode >= 1 
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

    public function category_drop_down_fill() {
        $category_query = 'SELECT mt_details_id, mt_details_name
			FROM master_type_details 
			WHERE master_type_id = 9 
			ORDER BY mt_details_id ASC';
        $category_data = $this->db->query($category_query);
        $category_result = $category_data->result_array();

        return $category_result;
    }

    /**
     * Function to fetch TLO details of corresponding Experiments from topic table
     * @parameters: Experiment id
     * @return: an array value of TLO details
     */
    function get_tlo_details($expt_id, $category_id) {
        $query = 'SELECT top.topic_id, top.topic_title, top.topic_content, top.category_id, tlo.tlo_id, tlo.tlo_statement, 
			tlo.tlo_code, mtd.mt_details_name
			FROM topic AS top
			JOIN master_type_details AS mtd ON mtd.mt_details_id = top.category_id
			LEFT JOIN tlo AS tlo ON tlo.topic_id = top.topic_id 
			WHERE top.topic_id = "' . $expt_id . '"
			ORDER BY LPAD(LOWER(tlo.tlo_code),5,0) ASC ';
        $data_rows = $this->db->query($query);
        $data = $data_rows->result_array();

        return $data;
    }

    /*
     * Function is to delete the  particular experiment details.
     * @param - experiment id and course id is used to delete the particular experiment contents.
     * returns the updated experiment contents.
     */

    public function delete_experiment($experiment_id, $course_id) {
        $delete_query = "DELETE FROM topic WHERE topic_id='$experiment_id' AND course_id='$course_id'";
        $data = $this->db->query($delete_query);
        return true;
    }

    /*
     * Function is to fetch Topic details.
     * @param - course id is used to fetch the particular topic content
     * returns.
     */

    public function experiment_details($course_id, $category_id) {

        $experiment_data_query = 'SELECT e.topic_id,e.state_id, e.category_id , e.topic_title, e.topic_content, e.topic_hrs, e.num_of_sessions, 
										e.marks_expt, e.correlation_with_theory
										FROM topic AS e
										JOIN master_type_details AS m 
										WHERE e.course_id ="' . $course_id . '" 
										AND e.category_id = "' . $category_id . '" 
										AND e.category_id = m.mt_details_id';
        $experiment_data_data = $this->db->query($experiment_data_query);
        $experiment_data_result = $experiment_data_data->result_array();
        $experiment_data_result['experiment_data'] = $experiment_data_result;

        return $experiment_data_result;
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

    public function get_dropdown_values($crclm_id, $term_id, $crs_id, $category) {
        $crclm_name_query = $this->db->query('SELECT c.crclm_name FROM curriculum c WHERE c.crclm_id = ' . $crclm_id);
        $crclm_name = $crclm_name_query->row_array();
        $term_name_query = $this->db->query('SELECT c.term_name FROM crclm_terms c WHERE c.crclm_term_id = ' . $term_id);
        $term_name = $term_name_query->row_array();
        $course_title_query = $this->db->query('SELECT c.crs_title, c.crs_code FROM course c WHERE c.crs_id = ' . $crs_id);
        $course_title = $course_title_query->row_array();
        $category_query = $this->db->query('SELECT m.mt_details_name FROM master_type_details m WHERE m.master_type_id = 9 AND m.mt_details_id = ' . $category);
        $category = $category_query->row_array();
        $data = array(
            'crclm_name' => $crclm_name['crclm_name'],
            'term_name' => $term_name['term_name'],
            'course_title' => $course_title['crs_title'],
            'course_code' => $course_title['crs_code'],
            'category' => $category['mt_details_name']
        );
        return $data;
    }

    public function insert_lab_experiment($crclm_id, $term_id, $crs_id, $category_id, $expt_no, $sessions, $marks, $experiment_description, $correlation, $counter_count) {
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        for ($i = 0; $i < $counter_count; $i++) {
            $lab_experiment_data = array(
                'topic_title' => $expt_no[$i],
                'topic_content' => $experiment_description[$i],
                'num_of_sessions' => $sessions[$i],
                'marks_expt' => $marks[$i],
                'correlation_with_theory' => $correlation[$i],
                'category_id' => $category_id,
                'curriculum_id' => $crclm_id,
                'term_id' => $term_id,
                'course_id' => $crs_id,
                'state_id' => 1,
                'created_by' => $created_by,
                'created_date' => $created_date
            );
            $this->db->insert('topic', $lab_experiment_data);
        }
        return TRUE;
    }

    public function get_lab_experiment_details($crclm_id, $term_id, $crs_id, $category_id, $topic_id) {
        $lab_experiment_details = $this->db->query('SELECT t.topic_id, t.topic_title, t.topic_content, t.num_of_sessions,
			t.marks_expt, t.correlation_with_theory,
			c.crclm_name as curriculum_id,ct.term_name as term_id, crs.crs_title as course_id, crs.crs_code as crs_code,
			m.mt_details_id as category_id, m.mt_details_name as category_name
			FROM topic t, curriculum c, crclm_terms ct, course crs, master_type_details m
			WHERE t.curriculum_id = c.crclm_id
			AND t.term_id = ct.crclm_term_id
			AND t.course_id = crs.crs_id
			AND t.category_id = m.mt_details_id
			AND m.master_type_id = 9
			AND t.topic_id =' . $topic_id);
        $lab_experiment_details = $lab_experiment_details->row_array();
        return $lab_experiment_details;
    }

    public function update_lab_experiment($data) {
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        $lab_experiment_data = array(
            'topic_title' => $data['expt_no'],
            'topic_content' => $data['experiment_description'],
            'num_of_sessions' => $data['sessions'],
            'marks_expt' => $data['marks'],
            'correlation_with_theory' => $data['correlation'],
            'category_id' => $data['category_id'],
            'modified_by' => $modified_by,
            'modified_date' => $modified_date
        );
        $this->db->where('topic_id', $data['topic_id']);
        $this->db->update('topic', $lab_experiment_data);
        return TRUE;
    }

    /*     * *********************************************LAB EXPERIMENT TLO********************************************* */

    public function insert_lab_experiment_tlo($crclm_id, $term_id, $crs_id, $category_id, $topic_id, $count, $tlo_stmt_array, $delivery_methods, $delivery_approach, $bld_id, $tlo_bloom, $tlo_bloom_1, $tlo_bloom_2) {
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        for ($i = 0; $i < $count; $i++) {

            $lab_experiment_tlo_data = array(
                'tlo_statement' => $tlo_stmt_array,
                'delivery_approach' => $delivery_approach,
                'curriculum_id' => $crclm_id,
                'term_id' => $term_id,
                'course_id' => $crs_id,
                'topic_id' => $topic_id,
                'created_by' => $created_by,
                'created_date' => $created_date
            );
            $this->db->insert('tlo', $lab_experiment_tlo_data);
        }

        $tlo_id = $this->db->insert_id();

        if (sizeof($tlo_bloom)) {

            for ($j = 0; $j < sizeof($tlo_bloom); $j++) {

                $tlo_bloom_level_array = array(
                    'tlo_id' => $tlo_id,
                    'bld_id' => $bld_id[0],
                    'bloom_id' => $tlo_bloom[$j],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );

                $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
            }
        }

        if (sizeof($tlo_bloom_1)) {

            for ($j = 0; $j < sizeof($tlo_bloom_1); $j++) {

                $tlo_bloom_level_array = array(
                    'tlo_id' => $tlo_id,
                    'bld_id' => $bld_id[1],
                    'bloom_id' => $tlo_bloom_1[$j],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );

                $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
            }
        }
        if (sizeof($tlo_bloom_2)) {

            for ($j = 0; $j < sizeof($tlo_bloom_2); $j++) {

                $tlo_bloom_level_array = array(
                    'tlo_id' => $tlo_id,
                    'bld_id' => $bld_id[2],
                    'bloom_id' => $tlo_bloom_2[$j],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );
                $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
            }
        }

        if ($delivery_methods != '') {

            $delivery_mtd_map_array = array(
                'tlo_id' => $tlo_id,
                'delivery_mtd_id' => $delivery_methods,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $this->db->insert('map_tlo_delivery_method', $delivery_mtd_map_array);
        }
        $tlo_fetch = $this->db
                ->select('tlo_id')
                ->where('course_id', $crs_id)
                ->where('term_id', $term_id)
                ->where('curriculum_id', $crclm_id)
                ->where('topic_id', $topic_id)
                ->get('tlo')
                ->result_array();
        $i = 1;
        $tlo_alias = $this->lang->line('entity_tlo_singular');
        foreach ($tlo_fetch as $tlo_data) {
            $tlo_code_update = $this->db
                    ->set('tlo_code', $tlo_alias . $i)
                    ->where('tlo_id', $tlo_data['tlo_id'])
                    ->update('tlo');
            $i++;
        }

        // Dashboard entry to proceed to TLO to CO mapping Topic-wise
        $update_dashboard_data = array('status' => '0');
        $this->db
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', 10)
                ->where('particular_id', $topic_id)
                ->update('dashboard', $update_dashboard_data);
        // check dashboard entry exists for mapping 			
        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('particular_id', $topic_id)
                        ->where('entity_id', '17')
                        ->where('status', '1')
                        ->where('state', '1')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {
            // Fetch Course details
            $course_title = 'SELECT c.crs_id, c.crs_title, t.topic_id , topic_title
				FROM course AS c, topic AS t 
				WHERE t.curriculum_id = "' . $crclm_id . '"
				AND t.term_id = "' . $term_id . '" 
				AND t.course_id = "' . $crs_id . '" 
				AND t.topic_id = "' . $topic_id . '" 
				AND t.course_id = c.crs_id	';
            $course_query = $this->db->query($course_title);
            $course_title_result = $course_query->result_array();

            // Insert into dashboard table
            $url = base_url('curriculum/tlo_clo_map/map_tlo_clo/' . $crclm_id . '/' . $term_id . '/' . $crs_id . '/' . $topic_id);
            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '17',
                'particular_id' => $topic_id,
                'state' => '2',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $this->ion_auth->user()->row()->id,
                'url' => $url,
                'description' => 'Course:' . $course_title_result[0]['crs_title'] . $this->lang->line('entity_topic') . $course_title_result[0]['topic_title'] . ' :- Finished creation of Lab Experiments, proceed with mapping between' . $this->lang->line('entity_tlo_singular') . 's and COS.'
            );
            $this->db->insert('dashboard', $dashboard_data);
        }
        // Topic table update
        $update_topic_data = array('state_id' => '1');
        $this->db
                ->where('topic_id', $topic_id)
                ->update('topic', $update_topic_data);
        return TRUE;
    }

    public function get_lab_experiment_tlo_details($topic_id) {
        $lab_experiment_tlo_details = $this->db->query('SELECT tlo_id, tlo_statement, tlo_code
			FROM tlo t
			WHERE t.topic_id =' . $topic_id);
        $lab_experiment_tlo_details = $lab_experiment_tlo_details->result_array();
        return $lab_experiment_tlo_details;
    }

    public function delete_lab_experiment_tlo($tlo_id) {
        $delete_tlo = $this->db->query('DELETE FROM tlo WHERE tlo_id = ' . $tlo_id);
        return $delete_tlo;
    }

    public function edit_lab_experiment_tlo($tlo_id, $tlo_statement, $delivery_mtd_id, $tlo_dm_id, $dlma, $tlo_code, $level, $level_1, $level_2, $bld_id) {
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        $delete_bloom_map = 'DELETE FROM map_tlo_bloom_level
                                       WHERE tlo_id="' . $tlo_id . '"';
        $delete_bloom_map = $this->db->query($delete_bloom_map);

        if ($delete_bloom_map) {

            if (sizeof($level)) {

                for ($j = 0; $j < sizeof($level); $j++) {

                    $tlo_bloom_level_array = array(
                        'tlo_id' => $tlo_id,
                        'bld_id' => $bld_id[0],
                        'bloom_id' => $level[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );
                    $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
                }
            }

            if (sizeof($level_1)) {

                for ($j = 0; $j < sizeof($level_1); $j++) {

                    $tlo_bloom_level_array = array(
                        'tlo_id' => $tlo_id,
                        'bld_id' => $bld_id[1],
                        'bloom_id' => $level_1[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );
                    $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
                }
            }

            if (sizeof($level_2)) {

                for ($j = 0; $j < sizeof($level_2); $j++) {

                    $tlo_bloom_level_array = array(
                        'tlo_id' => $tlo_id,
                        'bld_id' => $bld_id[2],
                        'bloom_id' => $level_2[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );
                    $this->db->insert('map_tlo_bloom_level', $tlo_bloom_level_array);
                }
            }
        }

        $select_query = 'select * from map_tlo_delivery_method where tlo_id="' . $tlo_id . '"';
        $re = $this->db->query($select_query);
        $result = $re->result_array();

        $count = count($result);
        if ($count == 0 && $delivery_mtd_id != '') {
            $delivery_mtd_map_array = array(
                'tlo_id' => $tlo_id,
                'delivery_mtd_id' => $delivery_mtd_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $this->db->insert('map_tlo_delivery_method', $delivery_mtd_map_array);
        }
        if ($delivery_mtd_id == '') {

            $delete_query = 'DELETE FROM map_tlo_delivery_method WHERE tlo_id = "' . $tlo_id . '"';
            $map_data_res = $this->db->query($delete_query);
            $query_tlo = 'UPDATE tlo SET tlo_code="' . $tlo_code . '",tlo_statement="' . $tlo_statement . '",delivery_approach="' . $dlma . '", modified_by = "' . $this->ion_auth->user()->row()->id . '", modified_date = "' . date('Y-m-d') . '"  where tlo_id="' . $tlo_id . '"';
            $this->db->query($query_tlo);
            return true;
        } else {

            $query_tlo = 'UPDATE tlo SET tlo_code="' . $tlo_code . '",tlo_statement="' . $tlo_statement . '",delivery_approach="' . $dlma . '" where tlo_id="' . $tlo_id . '"';
            $query_dlm = 'UPDATE map_tlo_delivery_method set delivery_mtd_id="' . $delivery_mtd_id . '" where tlo_id="' . $tlo_id . '" ';
            $this->db->query($query_tlo);
            $this->db->query($query_dlm);
            return true;
        }
    }

    /*
     * Function to find out number of experiments under category for that curriculum
     * @parameters: curriculum id, term id and course id
     * returns: experiments count
     */

    public function expt_count($crclm_id, $term_id, $crs_id) {
        /* $count_query = 'SELECT topic_id FROM topic WHERE curriculum_id = "'.$crclm_id.'" 
          AND term_id = "'.$term_id.'"
          AND course_id = "'.$crs_id.'" ';
          $count_data = $this->db->query($count_query);
          $curriculum_list_result = $count_data->result_array();
          $count_result = $count_data->num_rows();

          return $count_result; */
    }

    /*
     * Function is to  fetch the list of the blooms level.
     * @param - ------.
     * returns entity details.
     */

    public function bloom_level() {
        $bloom_level_query = 'SELECT bloom_id,level,description, bloom_actionverbs
                              FROM bloom_level ';
        $bloom_level_data = $this->db->query($bloom_level_query);
        $bloom_level_result = $bloom_level_data->result_array();
        return $bloom_level_result;
    }

    public function get_delivery_method($crclm_id) {
        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();
        return $delivery_mthd_res;
    }

    public function tlo_list_data_new($topic_id, $course_id, $term_id, $crclm_id) {
        $tlo_query = ' SELECT t.tlo_code,t.tlo_id,t.tlo_statement,t.delivery_approach,t.course_id,t.bloom_id,b.level,t.term_id,
		       t.topic_id,t.curriculum_id,md.crclm_dm_id ,dmtd.delivery_mtd_id,md.delivery_mtd_name,dmtd.map_tlo_dm_id, b.bloom_actionverbs,b.description,dmtd.delivery_mtd_id,t.bloom_id	
		       FROM tlo as t
		       LEFT JOIN bloom_level as b on b.bloom_id = t.bloom_id
		       LEFT JOIN map_tlo_delivery_method dmtd on dmtd.tlo_id = t.tlo_id
		       LEFT JOIN map_crclm_deliverymethod md on md.crclm_dm_id = dmtd.delivery_mtd_id
		       WHERE t.topic_id = "' . $topic_id . '" ';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();
        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();
        $data['delivery_mthd'] = $delivery_mthd_res;
        $data['tlo_result'] = $tlo_result;
        return $data;
    }

    /* Function is used to fetch the bloom level domain from bloom_domain table.
     * @param - .
     * @returns- a array value of the bloom's domain details.
     */

    public function get_all_bloom_domain() {
        $bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
        return $bloom_domain_data;
    }

    /* Function is used to fetch the bloom level data from bloom_level table.
     * @param - .
     * @returns- a array value of the bloom's level details.
     */

    public function get_all_bloom_level($domain_id) {
        $bloom_level_query = $this->db->query('SELECT b.bloom_id, b.level, b.learning, b.description, b.bloom_actionverbs FROM bloom_level b WHERE bld_id="' . $domain_id . '"');
        $bloom_level_data = $bloom_level_query->result_array();
        return $bloom_level_data;
    }

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

    /* Function is used to fetch the mapped bloom's level id to tlo.
     * @param - .
     * @returns- a array value of the bloom's level id .
     */

    public function mapped_bloom_levels($id, $bld_id) {
        $mapped_bloom_levels_result = $this->db->query('SELECT bloom_id '
                . '                 From map_tlo_bloom_level '
                . '                 WHERE tlo_id="' . $id . '" '
                . '                 AND bld_id="' . $bld_id . '"');
        $mapped_bloom_levels_data = $mapped_bloom_levels_result->result_array();
        $mapped_bloom_levels_array = array(
            'mapped_bloom_levels' => $mapped_bloom_levels_data
        );
        return $mapped_bloom_levels_array;
    }

    /* Function is used to fetch the mapped bloom's level details for particular bloom's domain.
     * @param - .
     * @returns- a array value of the bloom's levels  .
     */

    public function bloom_level_details($tlo_id, $bld_id) {
        $tlo_bloom_level_query = $this->db->query('SELECT b.level, b.description, b.bloom_actionverbs
						   FROM map_tlo_bloom_level m
						   LEFT JOIN bloom_level b ON m.bloom_id = b.bloom_id
						   WHERE m.tlo_id ="' . $tlo_id . '" AND b.bld_id="' . $bld_id . '"');
        $tlo_bloom_level_data = $tlo_bloom_level_query->result_array();
        return $tlo_bloom_level_data;
    }

    /* Function is used to fetch course details.
     * @param - .
     * @returns- a array value of the course details.
     */

    public function course_details($crs_id) {
        $course_details_query = 'SELECT cognitive_domain_flag, affective_domain_flag, psychomotor_domain_flag ,clo_bl_flag 
                                    FROM course 
                                    WHERE crs_id="' . $crs_id . '"';
        $course_details_data = $this->db->query($course_details_query);
        $course_details = $course_details_data->result_array();
        return $course_details;
    }

}
?>
