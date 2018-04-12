<?php

/**
 * Description	:	Course learning objective grid provides the list of course learning
  objective statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:-
 *   Date                Modified By                         Description
 * 15-09-2013		      Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 31-03-2015				Jyoti					Added snippets for CO to multiple Bloom's 
  Level and Delivery methods.
 * 08-05-2015		Abhinay B Angadi		Query changes done on pre-requisite field
 * 28-12-2015		Bahgyalaxmi S S			
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_model extends CI_Model {

    public $comment = "comment";

    /**
     * Function to fetch course and course learning objectives details
     * @return: course title, course learning objective details, course learning objectives id
      and course learning objective count
     */
    public function clo_list() {
        $clo_list = 'SELECT c.clo_statement, c.clo_id, p.crs_title
					 FROM  clo AS c, course AS p
					 WHERE c.crs_id = p.crs_id
					 ORDER BY c.clo_code ASC';
        $clo_list_result = $this->db->query($clo_list);
        $clo_list_data = $clo_list_result->result_array();

        $clo_count = $this->db->select('COUNT(*) AS count', FALSE)
                ->from('clo');
        $clo_count_result = $clo_count->get()->result();
        $clo_list_return['num_rows'] = $clo_count_result[0]->count;
        $clo_list_return['clo_list_data'] = $clo_list_data;

        return $clo_list_return;
    }

    /**
     * Function to fetch all the courses and course learning objectives details
     * @return: curriculum id, curriculum name, course id, course name
     */
    public function fetch_list() {
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;

        $curriculum = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
					   FROM curriculum AS c, program AS p, dashboard AS d
					   WHERE p.dept_id = "' . $logged_in_user_dept_id . '"
							AND d.crclm_id = c.crclm_id
							AND p.pgm_id = c.pgm_id
							AND d.entity_id = 2
							AND d.status = 1
							ORDER BY c.crclm_name ASC';
        $curriculum_result = $this->db->query($curriculum);
        $result = $curriculum_result->result_array();
        $fetch_data['result'] = $result;

        return $fetch_data;
    }

    /*     * * */

    public function clo_import_manage($curriculum_id, $term_id, $course_id) {
        $query = 'select count(qpd_id) from qp_definition q where crs_id="' . $course_id . '" and crclm_term_id="' . $term_id . '" and crclm_id="' . $curriculum_id . '"';
        $query_data = $this->db->query($query);
        return $query_data->result_array();
    }

    public function clo_delete_manage($clo_id, $course_id) {
        $query = 'select count(q.qp_mq_id) from qp_mainquestion_definition as q join qp_mapping_definition as qp ON qp.qp_mq_id = q.qp_mq_id
	where  qp.actual_mapped_id="' . $clo_id . '"';
        $query_data = $this->db->query($query);
        return $query_data->result_array();
    }

    /**
     * Function to fetch course learning objective details
     * @parameters: course id
     * @return: course learning objective id and course learning objective statements
     */
    public function clo_details($curriculum_id, $term_id, $course_id) {
        $clo_data = 'SELECT clo_code, clo_statement, clo_id 
					 FROM clo 
					 WHERE crs_id = "' . $course_id . '"
					 ORDER BY LPAD(LOWER(clo_code),5,0) ASC';
        $clo_data_result = $this->db->query($clo_data);
        $result = $clo_data_result->result_array();
        if (!empty($result)) {
            $clo_result['clo_list'] = $result;
        } else {
            $clo_result['clo_list'] = 0;
        }
        $course_state_query = 'SELECT state_id FROM course WHERE crclm_id = "' . $curriculum_id . '" AND crclm_term_id = "' . $term_id . '" AND crs_id ="' . $course_id . '"';
        $course_state_data = $this->db->query($course_state_query);
        $course_state_result = $course_state_data->result_array();
        if (!empty($course_state_result)) {
            $clo_result['course_state'] = $course_state_result;
        } else {
            $clo_result['course_state'] = 0;
        }

        return $clo_result;
    }

    /**
     * Function to fetch curriculum details form curriculum table
     * @return: curriculum id and curriculum name
     */
    public function curriculum_details() {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin')) {
            $curriculum_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, users AS u, program AS p
								WHERE c.pgm_id = p.pgm_id 
								AND c.status = 1
								ORDER BY c.crclm_name ASC';
            $curriculum_name_result = $this->db->query($curriculum_name);
            $curriculum_name_result = $curriculum_name_result->result_array();
            $curriculum_data['curriculum_name_result'] = $curriculum_name_result;

            return $curriculum_data;
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

            $curriculum_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
									FROM curriculum AS c, users AS u, program AS p
									WHERE u.id = "' . $loggedin_user_id . '" 
									AND u.user_dept_id = p.dept_id 
									AND c.pgm_id = p.pgm_id 
									AND c.status = 1
									ORDER BY c.crclm_name ASC';


            $curriculum_name_result = $this->db->query($curriculum_name);
            $curriculum_name_result = $curriculum_name_result->result_array();
            $curriculum_data['curriculum_name_result'] = $curriculum_name_result;
            return $curriculum_data;
        } else {
            // For Course Owner log-in
            $curriculum_name = 'SELECT DISTINCT c.crclm_id, c.crclm_name
							FROM curriculum AS c, users AS u, program AS p ,course_clo_owner AS clo
							WHERE u.id = "' . $loggedin_user_id . '" 
							AND u.user_dept_id = p.dept_id
							AND c.pgm_id = p.pgm_id
							AND c.status = 1
							AND u.id = clo.clo_owner_id
							AND c.crclm_id = clo.crclm_id
							ORDER BY c.crclm_name ASC';
            $curriculum_name_result = $this->db->query($curriculum_name);
            $curriculum_name_result = $curriculum_name_result->result_array();
            $curriculum_data['curriculum_name_result'] = $curriculum_name_result;

            return $curriculum_data;
        }
    }

    /**
     * Function to fetch curriculum details form curriculum table
     * @return: curriculum id and curriculum name
     */
    public function crclm_clo_add($crclm_id, $term_id, $crs_id) {
        $loggedin_user_id = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin')) {

            $curriculum_name = 'SELECT  crclm.crclm_id, crclm.crclm_name, term.term_name, term.crclm_term_id, crs.crs_id,crs.crs_title, crs.crs_code		
								FROM curriculum as crclm  
								JOIN  crclm_terms as term ON term.crclm_term_id = "' . $term_id . '"
								JOIN course as crs ON crs.crs_id = "' . $crs_id . '"
								WHERE crclm.crclm_id = "' . $crclm_id . '" 
								ORDER BY crclm.crclm_name ASC ';

            $curriculum_name_result = $this->db->query($curriculum_name);
            $curriculum_name_result = $curriculum_name_result->result_array();
            $curriculum_data['curriculum_name_result'] = $curriculum_name_result;

            return $curriculum_data;
        } else {

            $curriculum_name = 'SELECT  crclm.crclm_id, crclm.crclm_name, term.term_name, term.crclm_term_id, crs.crs_id, crs.crs_title, crs.crs_code
								FROM curriculum as crclm  
								JOIN  crclm_terms as term ON term.crclm_term_id = "' . $term_id . '"
								JOIN course as crs ON crs.crs_id = "' . $crs_id . '"
								WHERE crclm.crclm_id = "' . $crclm_id . '" 
								ORDER BY crclm.crclm_name ASC';

            $curriculum_name_result = $this->db->query($curriculum_name);
            $curriculum_name_result = $curriculum_name_result->result_array();
            $curriculum_data['curriculum_name_result'] = $curriculum_name_result;

            return $curriculum_data;
        }
    }

    /**
     * Function to fetch term details from curriculum term table
     * @parameters: curriculum id
     * @return: term name and curriculum term id
     */
    public function term_fill($curriculum_id) {
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
        $term_name_result = $this->db->query($term_name);
        $term_name_result = $term_name_result->result_array();
        $term_data['term_name_result'] = $term_name_result;

        return $term_data;
    }

    /**
     * Function to fetch course details from course table
     * @parameters: term id
     * @return: course id and course title
     */
    public function course_fill($curriculum_id, $term_id) {
        $user = $this->ion_auth->user()->row()->id;

        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_name = 'SELECT DISTINCT c.crs_id, c.crs_title, c.crs_mode, c.crs_code
							FROM course AS c, dashboard AS d 
							WHERE d.entity_id NOT IN(2,5,6,13,20)
								AND c.crclm_term_id = "' . $term_id . '" 
								AND d.crclm_id = "' . $curriculum_id . '" 
								AND d.particular_id = c.crs_id 
								AND c.state_id >= 1
								ORDER BY c.crs_title ASC';
            $course_result = $this->db->query($course_name);
            $course_result = $course_result->result_array();
            $course_data['course_result'] = $course_result;

            return $course_data;
        } else if ($this->ion_auth->in_group('Course Owner')) {
            $course_name = 'SELECT DISTINCT o.crs_id, c.crs_title, c.crs_mode 
							FROM course AS c, course_clo_owner AS o, dashboard AS d 
							WHERE d.entity_id NOT IN(2,5,6,13,20)
								AND o.crclm_term_id = "' . $term_id . '" 
								AND o.clo_owner_id = "' . $user . '" 
								AND d.crclm_id = "' . $curriculum_id . '" 
								AND o.crs_id = c.crs_id 
								AND d.particular_id = o.crs_id 
								AND c.state_id >= 1
								ORDER BY c.crs_title ASC';
            $course_name_result = $this->db->query($course_name);
            $course_result = $course_name_result->result_array();
            $course_data['course_result'] = $course_result;
            return $course_data;
        }
    }

    /**
     * Function to add course learning objective statement and update course status
     * @parameters: course learning objective, curriculum id, term id and course id
     * @return boolean
     */
    public function clo_add($clo, $clo_bloom, $clo_delivery_method, $curriculum_id, $term_id, $course_id, $bld_id, $clo_bloom1, $clo_bloom2) {
        $clo_keys = array_keys($clo);
        $clo_size = sizeof($clo_keys);
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $m = 0;
        $query = $this->db->query('select org_type from organisation');
        $result = $query->result_array();
        $tire = $result[0]['org_type'];
        if ($tire == 'TIER-I') {
            $value = 50.00;
        } else {
            $value = 50.00;
        }
        for ($k = 1; $k <= $clo_size; $k++) {
            $clo_data = array(
                'crclm_id' => $curriculum_id,
                'clo_statement' => ucfirst(strtolower($clo[$clo_keys[$k - 1]])),
                'term_id' => $term_id,
                'crs_id' => $course_id,
                'cia_clo_minthreshhold' => $value,
				'mte_clo_minthreshhold' => $value,
                'tee_clo_minthreshhold' => $value,
                'clo_studentthreshhold' => $value,
                'created_by' => $created_by,
                'create_date' => $created_date
            );

            $this->db->insert('clo', $clo_data);

            $clo_id = $this->db->insert_id();

            if (sizeof($clo_bloom)) {
                for ($j = 0; $j < sizeof($clo_bloom); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[0],
                        'bloom_id' => $clo_bloom[$j],
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );
                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
            if (sizeof($clo_bloom1)) {
                for ($j = 0; $j < sizeof($clo_bloom1); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[1],
                        'bloom_id' => $clo_bloom1[$j],
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );
                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
            if (sizeof($clo_bloom2)) {
                for ($j = 0; $j < sizeof($clo_bloom2); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[2],
                        'bloom_id' => $clo_bloom2[$j],
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );
                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
            if (sizeof($clo_delivery_method)) {
                for ($j = 0; $j < sizeof($clo_delivery_method); $j++) {
                    $clo_delivery_method_array = array(
                        'clo_id' => $clo_id,
                        'delivery_method_id' => $clo_delivery_method[$j],
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );
                    $this->db->insert('map_clo_delivery_method', $clo_delivery_method_array);
                }
            }
            $m++;
        }
        $clo_fetch = $this->db
                ->select('clo_id')
                ->where('crs_id', $course_id)
                ->get('clo')
                ->result_array();
        $i = 1;
        $clo_alias = 'CO';
        foreach ($clo_fetch as $clo_data) {
            $clo_code_update = $this->db
                    ->set('clo_code', $clo_alias . $i)
                    ->where('clo_id', $clo_data['clo_id'])
                    ->update('clo');
            $i++;
        }
        return true;
    }

    /**
     * Function to fetch details to edit course learning objective statements
     * @parameters: course learning objective id and course id
     * @return: course learning objective id, course learning objective statement, course id, term id
      curriculum id, curriculum name, course title and term name
     */
    public function clo_edit($clo_id, $course_id) {
        $query = 'SELECT * 
		  FROM clo 
		  WHERE crs_id = "' . $course_id . '" AND clo_id = "' . $clo_id . '"';
        $clo_data = $this->db->query($query);
        $clo_result_data = $clo_data->result_array();
        $clo_result['clo_data'] = $clo_result_data;

        $curriculum_id = $clo_result_data['0']['crclm_id'];
        $term_id = $clo_result_data['0']['term_id'];

        $curriculum_name = 'SELECT crclm_name 
			    FROM curriculum 
			    WHERE crclm_id = "' . $curriculum_id . '"
			    ORDER BY crclm_name ASC';
        $curriculum_data = $this->db->query($curriculum_name);
        $curriculum_result = $curriculum_data->result_array();
        $clo_result['curriculum_name'] = $curriculum_result;

        $course_name = 'SELECT crs_title, crs_code 
			FROM course 
			WHERE crs_id = "' . $course_id . '"';
        $course_data = $this->db->query($course_name);
        $course_result = $course_data->result_array();
        $clo_result['course_name'] = $course_result;

        $term_name = 'SELECT term_name 
		      FROM crclm_terms 
		      WHERE crclm_term_id = "' . $term_id . '"';
        $term_data = $this->db->query($term_name);
        $term_result = $term_data->result_array();
        $clo_result['term_name'] = $term_result;

        $mapped_bloom_level_query = 'SELECT b.bloom_id, m.map_clo_bloom_level_id, b.level, b.learning, b.description, b.bloom_actionverbs
				     FROM bloom_level b 
				     LEFT OUTER JOIN map_clo_bloom_level m
				     ON b.bloom_id = m.bloom_id and
				     m.clo_id = "' . $clo_id . '"';
        $mapped_bloom_level = $this->db->query($mapped_bloom_level_query);
        $mapped_bloom_level_data = $mapped_bloom_level->result_array();
        $clo_result['mapped_bloom_level_data'] = $mapped_bloom_level_data;

        $mapped_delivery_method_query = 'SELECT d.crclm_dm_id, m.map_clo_delivery_method_id, d.delivery_mtd_name
					 FROM map_crclm_deliverymethod d
					 LEFT OUTER JOIN map_clo_delivery_method m
					 ON d.crclm_dm_id = m.delivery_method_id
					 AND m.clo_id = ' . $clo_id . '
					 WHERE d.crclm_id = ' . $curriculum_id . '
					 ORDER BY d.delivery_mtd_name ASC;';
        $mapped_delivery_method = $this->db->query($mapped_delivery_method_query);
        $mapped_delivery_method_data = $mapped_delivery_method->result_array();
        $clo_result['mapped_delivery_method_data'] = $mapped_delivery_method_data;

        return $clo_result;
    }

    public function edit_bloom_level($clo_id, $domain) {
        $mapped_bloom_level_query = 'SELECT b.bloom_id, m.map_clo_bloom_level_id, b.level, b.learning, b.description, b.bloom_actionverbs
				     FROM bloom_level b 
				     LEFT OUTER JOIN map_clo_bloom_level m
				     ON b.bloom_id = m.bloom_id and
				     m.clo_id = "' . $clo_id . '"
                                     WHERE b.bld_id ="' . $domain . '"';
        $mapped_bloom_level = $this->db->query($mapped_bloom_level_query);
        $mapped_bloom_level_data = $mapped_bloom_level->result_array();
        return $mapped_bloom_level_data;
    }

    /**
     * Function to update existing course learning objective statement
     * @parameters: course learning objective id, course learning objective statement and course id
     * @return: boolean
     */
    public function clo_update($clo_statement, $clo_id, $course_id, $clo_bloom, $clo_delivery_mtd, $clo_code, $bld_id, $clo_bloom1, $clo_bloom2) {

        $course_data_query = 'SELECT crclm_id, crclm_term_id FROM course WHERE crs_id ="' . $course_id . '"';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $crclm_id = $course_result[0]['crclm_id'];

        $crs_tbl_update = 'UPDATE course 
			   SET state_id = 1
			   WHERE crs_id = "' . $course_id . '"';
        $crs_tbl = $this->db->query($crs_tbl_update);

        $topic_tbl_update = 'UPDATE topic 
			     SET state_id = 1
			     WHERE course_id = "' . $course_id . '"
			     AND curriculum_id = "' . $course_result[0]['crclm_id'] . '"';
        $topic_tbl = $this->db->query($topic_tbl_update);


        $dashboard_update_query = 'UPDATE dashboard 
				   SET status = 0 
				   WHERE particular_id="' . $course_id . '" 
				   AND crclm_id = "' . $course_result[0]['crclm_id'] . '" 
				   AND entity_id = 16';
        $dashboard_update_data = $this->db->query($dashboard_update_query);

        //dashboard update for all topic entries
        $topic_data_query = 'SELECT curriculum_id, topic_id FROM topic WHERE course_id ="' . $course_id . '"';
        $topic_data = $this->db->query($topic_data_query);
        $topic_result = $topic_data->result_array();
        foreach ($topic_result as $topic) {
            $topic_dashboard_update_query = ' UPDATE dashboard 
					      SET status = 0
					      WHERE particular_id = "' . $topic['topic_id'] . '" 
					      AND crclm_id = "' . $topic['curriculum_id'] . '" 
					      AND entity_id = 17
					      AND status = 1 ';
            $update_data = $this->db->query($topic_dashboard_update_query);
        }
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        $update_query = 'UPDATE clo 
			 SET clo_statement = "' . ucfirst(strtolower($this->db->escape_str($clo_statement))) . '", clo_code="' . $clo_code . '",modified_by ="' . $modified_by . '", modify_date = "' . $modified_date . '"
			 WHERE crs_id = "' . $course_id . '" AND clo_id = "' . $clo_id . '"';

        $update_data = $this->db->query($update_query);

        $clo_bloom_cnt = sizeof($clo_bloom);
        $delete_bloom_map = 'DELETE FROM map_clo_bloom_level
                                       WHERE clo_id="' . $clo_id . '"';
        $delete_bloom_map = $this->db->query($delete_bloom_map);
        if ($delete_bloom_map) {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            if (sizeof($clo_bloom)) {
                for ($j = 0; $j < sizeof($clo_bloom); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[0],
                        'bloom_id' => $clo_bloom[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );
                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
            if (sizeof($clo_bloom1)) {
                for ($j = 0; $j < sizeof($clo_bloom1); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[1],
                        'bloom_id' => $clo_bloom1[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );

                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
            if (sizeof($clo_bloom2)) {
                for ($j = 0; $j < sizeof($clo_bloom2); $j++) {
                    $clo_bloom_level_array = array(
                        'clo_id' => $clo_id,
                        'bld_id' => $bld_id[2],
                        'bloom_id' => $clo_bloom2[$j],
                        'modified_by' => $modified_by,
                        'modified_date' => $modified_date
                    );

                    $this->db->insert('map_clo_bloom_level', $clo_bloom_level_array);
                }
            }
        }

        $clo_delivery_method_cnt = sizeof($clo_delivery_mtd);
        $clo_delivery_method_query = $this->db->query('SELECT COUNT(m.map_clo_delivery_method_id) as clo_delivery_method_count
						       FROM map_clo_delivery_method m
						       WHERE m.clo_id = ' . $clo_id);
        $clo_delivery_method_data = $clo_delivery_method_query->row_array();
        $mapped_clo_delivery_method_query = $this->db->query('SELECT * FROM map_clo_delivery_method m
															WHERE m.clo_id =' . $clo_id);
        $mapped_clo_delivery_method = $mapped_clo_delivery_method_query->result_array();
        $delivery_method = '';
        $j = 0;
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');

        if ($clo_delivery_method_cnt == $clo_delivery_method_data['clo_delivery_method_count']) {

            foreach ($mapped_clo_delivery_method as $clo_delivery_method) {

                $mapped_clo_delivery_method_id = $clo_delivery_method['map_clo_delivery_method_id'];
                $delivery_method_id = $clo_delivery_mtd[$j];
                $map_clo_delivery_method_array = array(
                    'delivery_method_id' => $delivery_method_id,
                    'modified_by' => $modified_by,
                    'modified_date' => $modified_date
                );
                $this->db->where('map_clo_delivery_method_id', $mapped_clo_delivery_method_id);
                $this->db->update('map_clo_delivery_method', $map_clo_delivery_method_array);
                $j++;
            }
        } else if ($clo_delivery_method_cnt > $clo_delivery_method_data['clo_delivery_method_count']) {

            $created_by = $this->ion_auth->user()->row()->id;
            $created_date = date('Y-m-d');

            foreach ($mapped_clo_delivery_method as $clo_delivery_method) {
                $mapped_clo_delivery_method_id = $clo_bloom_level['map_clo_delivery_method_id'];
                $delivery_method_id = $clo_delivery_mtd[$j];
                $map_clo_delivery_method_array = array(
                    'delivery_method_id' => $delivery_method_id,
                    'modified_by' => $modified_by,
                    'modified_date' => $modified_date
                );
                $this->db->where('map_clo_delivery_method_id', $mapped_clo_delivery_method_id);
                $this->db->update('map_clo_delivery_method', $map_clo_delivery_method_array);
                $j++;
            }

            for ($k = $j; $k < $clo_delivery_method_cnt; $k++) {
                $delivery_method_id = $clo_delivery_mtd[$k];
                $map_clo_delivery_method_array = array(
                    'clo_id' => $clo_id,
                    'delivery_method_id' => $delivery_method_id,
                    'created_by' => $created_by,
                    'created_date' => $created_date,
                );
                $this->db->insert('map_clo_delivery_method', $map_clo_delivery_method_array);
            }
        } else if ($clo_delivery_method_cnt < $clo_delivery_method_data['clo_delivery_method_count']) {
            $delete_clo_delivery_method = implode(",", $clo_delivery_mtd);

            if ($delete_clo_delivery_method != '') {
                $delete_clo_delivery_method_query = $this->db->query('DELETE FROM map_clo_delivery_method
								      WHERE clo_id = ' . $clo_id . '
								      AND delivery_method_id IN (
								      SELECT d.crclm_dm_id
								      FROM map_crclm_deliverymethod d
								      WHERE d.crclm_dm_id NOT IN (' . $delete_clo_delivery_method . ')
								      AND d.crclm_id = ' . $crclm_id . ')');
            } else {
                $delete_clo_delivery_method_query = $this->db->query('DELETE FROM map_clo_delivery_method
								      WHERE clo_id = ' . $clo_id . '');
            }
        }
        return $update_data;
    }

    /**
     * Function to delete course learning objective statement
     * @parameters: course learning objective id and course id
     */
    public function delete_clo($clo_id, $course_id) {
        $delete_query = 'DELETE FROM clo 
			 WHERE crs_id = "' . $course_id . '" AND clo_id = "' . $clo_id . '"';
        $delete_data = $this->db->query($delete_query);

        $clo_fetch = $this->db
                ->select('clo_id')
                ->where('crs_id', $course_id)
                ->get('clo')
                ->result_array();
        $i = 1;
        $clo_alias = 'CO';
        foreach ($clo_fetch as $clo_data) {
            $clo_code_update = $this->db
                    ->set('clo_code', $clo_alias . $i)
                    ->where('clo_id', $clo_data['clo_id'])
                    ->update('clo');
            $i++;
        }
        $count = 'SELECT COUNT(crs_id) 
		  FROM clo 
		  WHERE crs_id = "' . $course_id . '"';
        $count_data = $this->db->query($count);
        $count_result = $count_data->result_array();
    }

    /**
     * Function to publish the course learning objective statements and update dashboard
     * @parameters: curriculum id, term id, course id
     * @return: boolean
     */
    public function approve_publish_db($crclm_id, $term_id, $course_id) {

        $term_data_query = ' SELECT term_name 
			     FROM crclm_terms 
			     WHERE crclm_term_id = "' . $term_id . '" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
        $data['term'] = $term_result[0]['term_name'];

        $course_data_query = ' SELECT crs_title 
			       FROM course 
			       WHERE crs_id = "' . $course_id . '" ';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $data['course'] = $course_result;

        $clo_owner_id_query = ' SELECT clo_owner_id 
				FROM course_clo_owner 
				WHERE crs_id = "' . $course_id . '" 
				AND crclm_id = "' . $crclm_id . '" ';
        $clo_owner_id_data = $this->db->query($clo_owner_id_query);
        $clo_owner_id_result = $clo_owner_id_data->result_array();

        $update_dashboard_data = array(
            'status' => '0'
        );
        $this->db
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', 16)
                ->where('particular_id', $course_id)
                ->update('dashboard', $update_dashboard_data);

        $url = base_url('curriculum/clo_po_map/map_po_clo/' . $crclm_id . '/' . $term_id . '/' . $course_id);
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'entity_id' => '16',
            'particular_id' => $course_id,
            'state' => '1',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $clo_owner_id_result[0]['clo_owner_id'],
            'url' => $url,
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . ' - Finished creation of COs, proceed with mapping between COs and ' . $this->lang->line('sos') . '.'
        );
        $this->db->insert('dashboard', $dashboard_data);

        $update_dashboard_data = array(
            'status' => '0'
        );
        $this->db
                ->where('crclm_id', $crclm_id)
                ->where('entity_id', 4)
                ->where('particular_id', $course_id)
                ->where('state', 1)
                ->update('dashboard', $update_dashboard_data);


        $update_course_data = 'UPDATE course SET state_id = 2 
			       WHERE crclm_id = "' . $crclm_id . '" 
			       AND crclm_term_id = "' . $term_id . '" 
			       AND crs_id = "' . $course_id . '"';
        $this->db->query($update_course_data);


        $clo_fetch = $this->db
                ->select('clo_id')
                ->where('crs_id', $course_id)
                ->where('term_id', $term_id)
                ->where('crclm_id', $crclm_id)
                ->get('clo')
                ->result_array();
        $i = 1;
        $clo_alias = 'CO';
        foreach ($clo_fetch as $clo_data) {
            $clo_code_update = $this->db
                    ->set('clo_code', $clo_alias . $i)
                    ->where('clo_id', $clo_data['clo_id'])
                    ->update('clo');
            $i++;
        }
        $dashboard_data['course'] = $course_result[0]['crs_title'];
        $dashboard_data['term'] = $data['term'];

        return $dashboard_data;
    }

    /**
     * Function to fetch help details from help table
     * @return: serial number (help id), entity data and help description
     */
    public function clo_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
		 FROM help_content 
		 WHERE entity_id = 11';
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

    /* Function is used to fetch the Course Owner User for POs to PEOs Mapping from course_clo_owner table.
     * @param - curriculum id, crs_id.
     * @returns- a array value of the Course Owner details.
     */

    public function fetch_course_owner($curriculum_id, $crs_id) {

        $course_owner_query = 'SELECT o.clo_owner_id, u.title, u.first_name, u.last_name, c.crclm_name, crs.crclm_term_id, ct.term_name, crs.crs_title, crs.crs_code

			       FROM  course_clo_owner AS o, users AS u, curriculum AS c, course as crs, crclm_terms as ct

			       WHERE o.crs_id = "' . $crs_id . '"

			       AND o.clo_owner_id = u.id 

                                and o.crs_id = crs.crs_id

				and crs.crclm_term_id = ct.crclm_term_id

			       AND c.crclm_id ="' . $curriculum_id . '"

			       ORDER BY c.crclm_name ASC';

        $course_owner = $this->db->query($course_owner_query);
        $course_owner = $course_owner->result_array();
        return $course_owner;
    }

// End of function fetch_course_owner.	

    public function fetch_pre_requisite($crs_id) {
        $query = $this->db->query('SELECT predecessor_course
				   FROM predecessor_courses
				   WHERE crs_id =' . $crs_id);
        $query_result = $query->result_array();
        return $query_result;
    }

    public function manage_pre_requisite($crs_id, $predecessor_course) {
        $delete_pre_requisite = $this->db->query('DELETE FROM predecessor_courses WHERE crs_id = ' . $crs_id);

        $add_data = array(
            'crs_id' => $crs_id,
            'predecessor_course' => $predecessor_course,
            'created_by' => $this->ion_auth->user()->row()->id,
            'create_date' => date('Y-m-d')
        );
        $result_data = $this->db->insert('predecessor_courses', $add_data);

        return $result_data;
    }

    /* Function is used to fetch the bloom level domain from bloom_domain table.
     * @param - .
     * @returns- a array value of the bloom's domain details.
     */

    public function get_all_bloom_domain() {
        $bloom_domain_query = $this->db->query('SELECT bld_id,bld_name,status
                                                    FROM bloom_domain');
        $bloom_domain_data = $bloom_domain_query->result_array();
        return $bloom_domain_data;
    }

    /* Function is used to fetch the bloom level data from bloom_level table.
     * @param - .
     * @returns- a array value of the bloom_level details.
     */

    public function get_all_bloom_level() {
        $bloom_level_query = $this->db->query('SELECT b.bloom_id, b.level, b.learning, b.description, b.bloom_actionverbs
                                                FROM bloom_level b');
        $bloom_level_data = $bloom_level_query->result_array();
        return $bloom_level_data;
    }

    // End of function get_all_bloom_level.	

    /* Function is used to fetch the bloom level data from bloom_level table.
     * @param - .
     * @returns- a array value of the bloom_level details.
     */

    public function get_all_bloom_level_clo($domain_id) {
        $bloom_level_query = $this->db->query('SELECT b.bloom_id, b.level, b.learning, b.description, b.bloom_actionverbs
                                                FROM bloom_level AS b 
                                                WHERE b.bld_id="' . $domain_id . '"');
        $bloom_level_data = $bloom_level_query->result_array();
        return $bloom_level_data;
    }

    // End of function get_all_bloom_level_clo.

    /* Function is used to fetch the delivery_method data from delivery_method table.
     * @param - .
     * @returns- a array value of the delivery_method details.
     */

    public function get_all_delivery_method($crclm_id) {
        $delivery_method_query = $this->db->query('SELECT d.crclm_dm_id, d.delivery_mtd_name 
						   FROM map_crclm_deliverymethod d
						   WHERE d.crclm_id = "' . $crclm_id . '"
						   ORDER BY d.delivery_mtd_name ASC');
        $delivery_method_data = $delivery_method_query->result_array();
        return $delivery_method_data;
    }

    // End of function get_all_delivery_method.

    /* Function is used to fetch the blooms levels for a course outcome.
     * @param - .
     * @returns- a array value of the blomm's level details.
     */

    public function clo_bloom_level_details($clo_id) {
        $clo_bloom_level_query = $this->db->query('SELECT b.level, b.description, b.bloom_actionverbs
						   FROM map_clo_bloom_level m
						   LEFT JOIN bloom_level b ON m.bloom_id = b.bloom_id
						   WHERE m.clo_id =' . $clo_id);
        $clo_bloom_level_data = $clo_bloom_level_query->result_array();
        return $clo_bloom_level_data;
    }

    // End of function clo_bloom_level_details.

    /* Function is used to fetch the delivery method for a course outcome.
     * @param - .
     * @returns- a array value of the delivery method details.
     */

    public function clo_delivery_method_details($crclm_id, $clo_id) {
        $clo_delivery_method_query = $this->db->query('SELECT d.delivery_mtd_name
						       FROM map_clo_delivery_method m
						       LEFT JOIN map_crclm_deliverymethod d ON m.delivery_method_id = d.crclm_dm_id
						       AND d.crclm_id = ' . $crclm_id . '
						       WHERE m.clo_id =' . $clo_id . '
						       ORDER BY d.delivery_mtd_name ASC');
        $clo_delivery_method_data = $clo_delivery_method_query->result_array();
        return $clo_delivery_method_data;
    }

    // End of function clo_delivery_method_details.
    // function to fetch the course _come statements

    public function fetch_clo($clo, $curriculum_id, $term_id, $course_id) {
        $clo_keys = array_keys($clo);
        $clo_size = sizeof($clo_keys);
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $m = 0;

        for ($k = 1; $k <= $clo_size; $k++) {
            $clo_data = array(
                'crclm_id' => $curriculum_id,
                'clo_statement' => $clo[$clo_keys[$k - 1]],
                'term_id' => $term_id,
                'crs_id' => $course_id
            );

            $val = $this->db->get_where('clo', $clo_data);
            return $val->num_rows();
        }
    }

    public function fetch_clo1($clo_statement, $crclm_id, $term_id, $course_id, $clo_id) {
        $query = 'select * from clo where clo_code="' . $clo_statement . '" and crclm_id="' . $crclm_id . '" and term_id="' . $term_id . '" and crs_id="' . $course_id . '" and clo_id !="' . $clo_id . '"';
        $query1 = $this->db->query($query);
        $q = $query1->result_array();
        return (count($q));
    }

    public function edit_clo_check($clo_statement, $clo_id, $course_id, $clo_code) {
        $query = 'select * from clo where (clo_statement="' . $clo_statement . '" and crs_id="' . $course_id . ' " and clo_id!="' . $clo_id . '")';
        $result = $this->db->query($query);
        return $result->num_rows();
    }

    /* Function is used to fetch course details.
     * @param - .
     * @returns- a array value of the course details.
     */

    public function course_details($crs_id) {
        $course_details_query = 'SELECT cognitive_domain_flag, affective_domain_flag, psychomotor_domain_flag , clo_bl_flag
                                    FROM course 
                                    WHERE crs_id="' . $crs_id . '"';
        $course_details_data = $this->db->query($course_details_query);
        $course_details = $course_details_data->result_array();
        return $course_details;
    }
	
	public function check_bloom_status( $crs_id ){
		$query = $this->db->query('select CASE WHEN (cognitive_domain_flag = 0 and  affective_domain_flag = 0 and psychomotor_domain_flag = 0) then 0 else 1 end as clo_bl_flag  from course where crs_id = "'. $crs_id .'"');
		return $query->result_array();
	}
	
	public function edit_clo_check_co_map($curriculum_id, $term_id, $course_id , $clo_id){
	
		   $query = $this->db->query('SELECT count(q.actual_mapped_id) as clo_count FROM qp_mapping_definition q
									join qp_mainquestion_definition as mq on mq.qp_mq_id = q.qp_mq_id
									join qp_unit_definition as qu on qu.qpd_unitd_id = qp_unitd_id
									join qp_definition as qd on qd.qpd_id = qu.qpd_id
									where q.actual_mapped_id = "'. $clo_id .'" AND qd.crclm_id = "'. $curriculum_id.'" 
									AND qd.crclm_term_id = "'. $term_id .'" AND  qd.crs_id = "'. $course_id .'"');
									
			return $query->result_array();
	
	}
	
}

/*
 * End of file clo_model.php
 * Location: .curriculum/clo/clo_model.php 
 */
?>