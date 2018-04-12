<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for TLO Adding, Editing  operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013      Mritunjay B S                           Added file headers, function headers & comments. 
 * 05-10-2015		Bhagyalaxmi S S
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo_model extends CI_Model {
    /*
     * Function is to  fetch the enity data .
     * @param - ------.
     * returns entity details.
     */

    public function entity_name_fetch($topic_id, $course_id, $term_id, $crclm_id) {
        $topic_name_query = 'SELECT topic_title 
                             FROM topic 
                             WHERE topic_id = "' . $topic_id . '" ';
        $topic_name_data = $this->db->query($topic_name_query);
        $topic_name_result = $topic_name_data->result_array();
        $name_data['topic_name'] = $topic_name_result;

        $course_name_query = 'SELECT crs_title, crs_code 
                              FROM course 
                              WHERE crs_id = "' . $course_id . '" ';
        $course_name_data = $this->db->query($course_name_query);
        $course_name_result = $course_name_data->result_array();
        $name_data['course_name'] = $course_name_result;

        $term_name_query = 'SELECT term_name 
                            FROM crclm_terms 
                            WHERE crclm_term_id = "' . $term_id . '" ';
        $term_name_data = $this->db->query($term_name_query);
        $term_name_result = $term_name_data->result_array();
        $name_data['term_name'] = $term_name_result;

        $crclm_name_query = 'SELECT crclm_name 
                             FROM curriculum 
                             WHERE crclm_id = "' . $crclm_id . '" ';
        $crclm_name_data = $this->db->query($crclm_name_query);
        $crclm_name_result = $crclm_name_data->result_array();
        $name_data['crclm_name'] = $crclm_name_result;
        return $name_data;
    }

    /*
     * Function is to  fetch the help content for TLO .
     * @param - ------.
     * returns Help data.
     */

    public function tlo_help() {
        $help_query = 'SELECT serial_no, entity_data, help_desc 
                       FROM help_content 
                       WHERE entity_id = 12';
        $help_data = $this->db->query($help_query);
        $help_result = $help_data->result_array();
        $data['help_data'] = $help_result;

		if(!empty($help_result)) {
			$help_entity_id = $help_result[0]['serial_no'];

			$help_file_query = 'SELECT  help_entity_id,file_path 
								FROM uploads 
								WHERE help_entity_id = "' . $help_entity_id . '" ';
			$help_file_data = $this->db->query($help_file_query);
			$help_file_result = $help_file_data->result_array();
			$data['file'] = $help_file_result;
		}
		
		return $data;
    }

	/**
	  * Function to fetch help related to course learning outcomes to program outcomes to display
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

    /*
     * Function is to fill the curriculum drop down box with the curriculum details.
     * @param - ------.
     * returns list of curriculums.
     */

    public function crclm_drop_down_fill() {

        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $crclm_name_query = ' SELECT c.crclm_id, c.crclm_name 
			      FROM curriculum AS c, users AS u, program AS p
			      WHERE u.id = "' . $loggedin_user_id . '" 
			      AND u.user_dept_id = p.dept_id 
			      AND c.pgm_id = p.pgm_id 
			      AND c.status = 1 
			      ORDER BY c.crclm_name ASC ';
        $crclm_name_data = $this->db->query($crclm_name_query);
        $crclm_name_result = $crclm_name_data->result_array();
        $crclm_data['curriculum_name'] = $crclm_name_result;
        return $crclm_data;
    }

    /*
     * Function is to  fetch the data exercise question and review questions of particular topic.
     * @param - topic id is used to fetch the particular topic questions.
     * returns list of questions.
     */

    public function question($topic_id) {
        $fetch_question_query = ' SELECT assignment_question, review_question 
                                  FROM topic_question 
                                  WHERE topic_id = "' . $topic_id . '" ';
        $fetch_question_data = $this->db->query($fetch_question_query);
        $fetch_question_result = $fetch_question_data->result_array();
        return $fetch_question_result;
    }

    /*
     * Function is to add the TLO details.
     * @param - ------.
     * returns the particular data.
     */

    public function tlo_add($tlo, $crclm_id, $term_id, $course_id, $topic_id, $bloom, $delivery_methods, $delivery_approach) {
        $temp_data = $this->question($topic_id);
        $size_of_questions = sizeof($temp_data);
        $this->db->trans_start();
        $kmax = sizeof($tlo);


        for ($k = 0; $k < $kmax; $k++) {
            $tlo_data = array(
                'curriculum_id' => $crclm_id,
                'tlo_statement' => $tlo[$k],
                'delivery_approach' => $delivery_approach[$k],
                'bloom_id' => $bloom[$k],
                'term_id' => $term_id,
                'course_id' => $course_id,
                'topic_id' => $topic_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));

            $this->db->insert('tlo', $tlo_data);
            $tlo_id = $this->db->insert_id();

            $delivery_mtd_map_array = array(
                'tlo_id' => $tlo_id,
                'delivery_mtd_id' => $delivery_methods[$k],
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $this->db->insert('map_tlo_delivery_method', $delivery_mtd_map_array);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    /*
     * Function is to  fetch the particular tlo data to generate edit view page.
     * @param - topic id is used to fetch the particular tlo data.
     * returns the tlo data.
     */

    public function tlo_edit($topic_id) {

        $tlo_data_query = ' SELECT t.tlo_id,t.tlo_statement,t.delivery_approach,t.course_id,t.bloom_id,b.level,t.term_id,
			    t.topic_id,t.curriculum_id, dmtd.delivery_mtd_id, b.bloom_actionverbs
			    FROM tlo as t
			    LEFT JOIN bloom_level as b on b.bloom_id = t.bloom_id
			    LEFT JOIN map_tlo_delivery_method dmtd on dmtd.tlo_id = t.tlo_id
			    WHERE t.topic_id = "' . $topic_id . '" ';
        $tlo_data = $this->db->query($tlo_data_query);
        $tlo_result_data = $tlo_data->result_array();

        $crclm_id = $tlo_result_data[0]['curriculum_id'];

        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();

        $data['tlo_res'] = $tlo_result_data;
        $data['delivery_mtd'] = $delivery_mthd_res;

        return $data;
    }

    /*
     * Function is to  update the tlo data.
     * @param - topic id is used to fetch the particular tlo data.
     * returns the tlo data.
     */

    public function update_tlo($crclm_id, $topic_id, $tlo_id, $tlo_statement, $bloom_level, $review_question, $exercise_question, $term_id, $course_id, $delivery_method, $delivery_approach) {

        $this->db->trans_start();

        //read the ids into an array for comparing if they are deleted if tget exists
        $present_tlo_id = $this->db
                        ->select('tlo_id')
                        ->where('topic_id', $topic_id)
                        ->get('tlo')->result_array();

        //flatten the array for comparison, its associated make it numeral									
        foreach ($present_tlo_id as $id) {
            $present_tlo_id_array[] = $id['tlo_id'];
        }

        //prepare the array of the element that need to be deleted
        $delete_tlo_array = array_values(array_diff($present_tlo_id_array, $tlo_id));
        $kmax = sizeof($tlo_statement);
        for ($k = 0; $k < $kmax; $k++) {

            if (!empty($tlo_id[$k])) {
                $tlo_data = array(
                    'tlo_statement' => $tlo_statement[$k],
                    'delivery_approach' => $delivery_approach[$k],
                    'bloom_id' => $bloom_level[$k],
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'modified_date' => date('Y-m-d'));
                $this->db
                        ->where('tlo_id', $tlo_id[$k])
                        ->update('tlo', $tlo_data);

                $delete_query = 'DELETE FROM map_tlo_delivery_method WHERE tlo_id = "' . $tlo_id[$k] . '"';
                $map_data_res = $this->db->query($delete_query);

                $delivery_mtd_map_array = array(
                    'tlo_id' => $tlo_id[$k],
                    'delivery_mtd_id' => $delivery_method[$k],
                    'modified_by' => $this->ion_auth->user()->row()->id,
                    'modified_date' => date('Y-m-d'));
                $this->db->insert('map_tlo_delivery_method', $delivery_mtd_map_array);
            } else {

                $tlo_data = array(
                    'curriculum_id' => $crclm_id,
                    'topic_id' => $topic_id,
                    'tlo_statement' => $tlo_statement[$k],
                    'delivery_approach' => $delivery_approach[$k],
                    'bloom_id' => $bloom_level[$k],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d'),
                    'term_id' => $term_id,
                    'course_id' => $course_id
                );
                $this->db->insert('tlo', $tlo_data);
                $tlo_id = $this->db->insert_id();

                $delivery_mtd_map_array = array(
                    'tlo_id' => $tlo_id,
                    'delivery_mtd_id' => $delivery_method[$k],
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d'));
                $this->db->insert('map_tlo_delivery_method', $delivery_mtd_map_array);
            }
        };
        $question_data = array(
            'review_question' => $review_question,
            'assignment_question' => $exercise_question,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modified_date' => date('Y-m-d')
        );
        $this->db
                ->where('topic_id', $topic_id)
                ->update('topic_question', $question_data);

        //remove elements that are deleted
        $kmax = sizeof($delete_tlo_array);

        for ($k = 0; $k < $kmax; $k++) {
            $this->db
                    ->where('tlo_id', $delete_tlo_array[$k])
                    ->delete('tlo');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    /*
     * Function is to delete the particular tlo data.
     * @param - tlo id and course id used to delete the particular tlo data.
     * returns the tlo data.
     */

    public function delete_tlo($tlo_id, $course_id) {
        $delete_query = ' DELETE FROM tlo 
                          WHERE course_id = "' . $course_id . '" and tlo_id = "' . $tlo_id . '" ';
        $delete_data = $this->db->query($delete_query);
    }

    /*
     * Function is to get the cuccriculum data.
     * @param - topic id is used to get the particular curriculum name and id.
     * returns the curriculum name and the id.
     */

    public function crclm_name_data($topic_id) {
        $crclm_name_query = ' SELECT t.curriculum_id,c.crclm_name 
                              FROM topic as t, curriculum as c 
                              WHERE t.topic_id = "' . $topic_id . '" and t.curriculum_id = c.crclm_id ';
        $crclm_name_data = $this->db->query($crclm_name_query);
        $crclm_name_result = $crclm_name_data->result_array();

        return $crclm_name_result;
    }

    /*
     * Function is to get the term data.
     * @param - topic id is used to get the particular term name and id.
     * returns the term name and the id.
     */

    public function term_data($topic_id) {
        $term_data_query = ' SELECT t.term_id,c.term_name 
                             FROM crclm_terms as c, topic as t 
                             WHERE t.topic_id = "' . $topic_id . '" and t.term_id = c.crclm_term_id ';
        $term_data = $this->db->query($term_data_query);
        $term_data_result = $term_data->result_array();
        return $term_data_result;
    }

    /*
     * Function is to get the course data.
     * @param - topic id is used to get the particular course name and id.
     * returns the term name and the id.
     */

    public function course_data($topic_id) {
        $course_data_query = 'SELECT t.course_id,c.crs_title 
                              FROM course as c,topic as t 
                              WHERE t.topic_id = "' . $topic_id . '" 
			      AND t.course_id = c.crs_id ';
        $course_data = $this->db->query($course_data_query);
        $course_result_data = $course_data->result_array();
        return $course_result_data;
    }

    /*
     * Function is to get the topic data.
     * @param - topic id is used to get the particular topic name and id.
     * returns the topic name and the id.
     */

    public function topic_data($topic_id) {
        $topic_data_query = 'SELECT topic_id,topic_title 
                             FROM topic 
                             WHERE topic_id = "' . $topic_id . '" ';
        $topic_data = $this->db->query($topic_data_query);
        $topic_result_data = $topic_data->result_array();
        return $topic_result_data;
    }

    /*
     * Function is to publish the tlo data which helps to proceed with the next task.
     * @param - crclm_id, course id , term id and topic id is used to update the status of tlo creation.
     * returns the updated status message.
     */

    public function approve_publish_db($crclm_id, $term_id, $course_id, $topic_id) {
        $course_title = 'SELECT c.crs_id, c.crs_title, t.topic_id , topic_title
			 FROM course AS c, topic AS t 
			 WHERE t.curriculum_id = "' . $crclm_id . '"
			 AND t.term_id = "' . $term_id . '" 
			 AND t.course_id = "' . $course_id . '" 
			 AND t.topic_id = "' . $topic_id . '" 
			 AND t.course_id = c.crs_id	';
        $course_query = $this->db->query($course_title);
        $course_title_result = $course_query->result_array();

        $update_dashboard_data = array(
            'status' => '0'
        );
        $this->db
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', 10)
                ->where('particular_id', $topic_id)
                ->update('dashboard', $update_dashboard_data);

        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $crclm_id)
                        ->where('particular_id', $topic_id)
                        ->where('entity_id', '10')
                        ->where('status', '1')
                        ->where('state', '1')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {

            $url = base_url('curriculum/tlo_clo_map/map_tlo_clo/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_id);
            $dashboard_data = array(
                'crclm_id' => $crclm_id,
                'entity_id' => '17',
                'particular_id' => $topic_id,
                'state' => '1',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $this->ion_auth->user()->row()->id,
                'url' => $url,
                'description' => 'Course:' . $course_title_result[0]['crs_title'] . $this->lang->line('entity_topic') . $course_title_result[0]['topic_title'] . ' :- Finished creation of' . $this->lang->line('entity_tlo') . 's, proceed with mapping between' . $this->lang->line('entity_tlo') . 's and COS.'
            );
            $this->db->insert('dashboard', $dashboard_data);

            $update_dashboard_data = array(
                'status' => '0'
            );
            $this->db
                    ->where('crclm_id', $crclm_id)
                    ->where('entity_id', 17)
                    ->where('particular_id', $topic_id)
                    ->where('state', 2)
                    ->update('dashboard', $update_dashboard_data);


            $update_topic_data = array(
                'state_id' => '2'
            );
            $this->db
                    ->where('curriculum_id', $crclm_id)
                    ->where('course_id', $course_id)
                    ->where('term_id', $term_id)
                    ->where('topic_id', $topic_id)
                    ->update('topic', $update_topic_data);

            $tlo_fetch = $this->db
                    ->select('tlo_id')
                    ->where('curriculum_id', $crclm_id)
                    ->where('term_id', $term_id)
                    ->where('course_id', $course_id)
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
            return true;
        }
    }

    /* public function fetch_ation_verbs($bloom_id) {
      $query = 'SELECT bloom_actionverbs FROM bloom_level WHERE bloom_id = "' . $bloom_id . '"';
      $bloom_data = $this->db->query($query);
      $bloom_res = $bloom_data->result_array();
      return $bloom_res;
      } */

    public function tlo_status_roll_back($crclm_id, $term_id, $course_id, $topic_id) {

        $query = 'UPDATE topic SET state_id = 1 WHERE curriculum_id = "' . $crclm_id . '" AND term_id = "' . $term_id . '" AND course_id = "' . $course_id . '" AND topic_id = "' . $topic_id . '"';
        $roll_back_data = $this->db->query($query);
        return true;
    }

    public function tlo_list_data($topic_id, $course_id, $term_id, $crclm_id) {
        $tlo_query = 'SELECT t.tlo_id, t.tlo_statement,t.tlo_code, b.level,b.description,b.bloom_actionverbs
			FROM tlo as t
			JOIN bloom_level as b ON b.bloom_id = t.bloom_id  
			WHERE curriculum_id = "' . $crclm_id . '"
			AND term_id = "' . $term_id . '"
			AND course_id = "' . $course_id . '"
			AND topic_id = "' . $topic_id . '"';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();

        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();

        $data['tlo_result'] = $tlo_result;
        $data['delivery_mthd'] = $delivery_mthd_res;
        
        
        return $data;
    }

    /*     * **************************************************************************************************************************** */

    public function tlo_list_data_new($topic_id, $course_id, $term_id, $crclm_id) {
        $tlo_query = ' SELECT t.tlo_code,t.tlo_id,t.tlo_statement,t.delivery_approach,t.course_id,t.bloom_id,b.level,t.term_id,
			t.topic_id,t.curriculum_id,md.crclm_dm_id ,dmtd.delivery_mtd_id,md.delivery_mtd_name,dmtd.map_tlo_dm_id, b.bloom_actionverbs,b.description,dmtd.delivery_mtd_id,t.bloom_id	
			FROM tlo as t
			LEFT JOIN bloom_level as b on b.bloom_id = t.bloom_id
			LEFT JOIN map_tlo_delivery_method dmtd on dmtd.tlo_id = t.tlo_id
			LEFT JOIN map_crclm_deliverymethod md on md.crclm_dm_id = dmtd.delivery_mtd_id
			WHERE t.topic_id = "' . $topic_id . '" 
			ORDER BY t.tlo_code ASC';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();
        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();


        $data['delivery_mthd'] = $delivery_mthd_res;
        $data['tlo_result'] = $tlo_result;
        return $data;
    }

    /*
     * Function to delete TLO
     * @param:
     * @return
     */

    public function delete_tlo_new($tlo_id, $tlo_dm_id) {
        $delete_tlo_query = ' DELETE FROM tlo 
                          WHERE  tlo_id = "' . $tlo_id . '" ';
        $delete_tlo_dm_query = 'DELETE FROM map_tlo_delivery_method WHERE map_tlo_dm_id="' . $tlo_dm_id . '"';
        $delete_tlo_data = $this->db->query($delete_tlo_query);
        $delete_dm_data = $this->db->query($delete_tlo_dm_query);
        return true;
    }

    /*
     * Function is to add the TLO details.
     * @param - ------.
     * returns the particular data.
     */

    public function tlo_add_new($tlo, $crclm_id, $term_id, $course_id, $topic_id, $tlo_bloom, $delivery_methods, $delivery_approach, $bld_id, $tlo_bloom_1, $tlo_bloom_2) {

        $temp_data = $this->question($topic_id);
        $size_of_questions = sizeof($temp_data);
        $this->db->trans_start();
        $kmax = sizeof($tlo);
        $tlo_data = array(
            'curriculum_id' => $crclm_id,
            'tlo_statement' => $tlo,
            'delivery_approach' => $delivery_approach,
            'term_id' => $term_id,
            'course_id' => $course_id,
            'topic_id' => $topic_id,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'));

        $this->db->insert('tlo', $tlo_data);
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
                ->where('curriculum_id', $crclm_id)
                ->where('term_id', $term_id)
                ->where('course_id', $course_id)
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

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * Function is to  fetch the enity data .
     * @param - ------.
     * returns entity details.
     */

    public function entity_name_fetch_new($topic_id, $course_id, $term_id, $crclm_id) {
        $topic_name_query = 'SELECT topic_title 
                             FROM topic 
                             WHERE topic_id = "' . $topic_id . '" ';
        $topic_name_data = $this->db->query($topic_name_query);
        $topic_name_result = $topic_name_data->result_array();
        $name_data['topic_name'] = $topic_name_result;

        $course_name_query = 'SELECT crs_title 
                              FROM course 
                              WHERE crs_id = "' . $course_id . '" ';
        $course_name_data = $this->db->query($course_name_query);
        $course_name_result = $course_name_data->result_array();
        $name_data['course_name'] = $course_name_result;

        $term_name_query = 'SELECT term_name 
                            FROM crclm_terms 
                            WHERE crclm_term_id = "' . $term_id . '" ';
        $term_name_data = $this->db->query($term_name_query);
        $term_name_result = $term_name_data->result_array();
        $name_data['term_name'] = $term_name_result;

        $crclm_name_query = 'SELECT crclm_name 
                             FROM curriculum 
                             WHERE crclm_id = "' . $crclm_id . '" ';
        $crclm_name_data = $this->db->query($crclm_name_query);
        $crclm_name_result = $crclm_name_data->result_array();
        $name_data['crclm_name'] = $crclm_name_result;
        return $name_data;
    }

    /*
     * Function is to add the TLO details.
     * @param - ------.
     * returns the particular data.
     */

    public function tlo_list_data_edit($topic_id, $course_id, $term_id, $crclm_id) {
        $tlo_query = ' SELECT t.tlo_code,t.tlo_id,t.tlo_statement,t.delivery_approach,t.course_id,t.bloom_id,b.level,t.term_id,
			t.topic_id,t.curriculum_id, dmtd.delivery_mtd_id,md.delivery_mtd_name,dmtd.map_tlo_dm_id, b.bloom_actionverbs,b.description,dmtd.delivery_mtd_id,t.bloom_id,md.crclm_dm_id
			FROM tlo as t
			LEFT JOIN bloom_level as b on b.bloom_id = t.bloom_id
			LEFT JOIN map_tlo_delivery_method dmtd on dmtd.tlo_id = t.tlo_id
			LEFT JOIN map_crclm_deliverymethod md on md.crclm_dm_id = dmtd.delivery_mtd_id
			WHERE t.topic_id = "' . $topic_id . '" ';
        $tlo_data = $this->db->query($tlo_query);
        $tlo_result = $tlo_data->result_array();
        //var_dump($tlo_result);
        $delivery_mthd_query = 'SELECT * FROM map_crclm_deliverymethod WHERE crclm_id = "' . $crclm_id . '" order by delivery_mtd_name';
        $delivery_mthd_data = $this->db->query($delivery_mthd_query);
        $delivery_mthd_res = $delivery_mthd_data->result_array();

        $data['tlo_result'] = $tlo_result;
        $data['delivery_mthd'] = $delivery_mthd_res;
        return $data;
    }

    public function tlo_edit_new($tlo_code, $tlo_description, $tlo_id, $delivery_mtd_id, $tlo_dm_id, $dlma, $level, $level_1, $level_2, $bld_id) {

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
            $query_tlo = 'UPDATE tlo SET tlo_code="' . $tlo_code . '",tlo_statement="' . $tlo_description . '",delivery_approach="' . $dlma . '", modified_by = "' . $modified_by . '", modified_date = "' . $modified_date . '" where tlo_id="' . $tlo_id . '"';
            $this->db->query($query_tlo);
            return true;
        } else {

            $query_tlo = 'UPDATE tlo SET tlo_code="' . $tlo_code . '",tlo_statement="' . $tlo_description . '",delivery_approach="' . $dlma . '", modified_by = "' . $modified_by . '", modified_date = "' . $modified_date . '" where tlo_id="' . $tlo_id . '"';
            $query_dlm = 'UPDATE map_tlo_delivery_method set delivery_mtd_id="' . $delivery_mtd_id . '" where tlo_id="' . $tlo_id . '" and map_tlo_dm_id="' . $tlo_dm_id . '"';
            $this->db->query($query_tlo);
            $this->db->query($query_dlm);
            return true;
        }
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
