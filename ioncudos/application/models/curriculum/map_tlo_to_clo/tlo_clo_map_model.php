<?php

/**
 * Description	:	Model(Database) Logic for TLO(Topic Learning Objectives) to CLO(Course Learning Objectives) 
 * 					Mapping Topic-wise.
 * Created		:	29-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 18-09-2013		Abhinay B.Angadi        Added file headers, public function headers, indentations & comments.
 * 19-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 27-01-2015		Jyoti					Modified for add,edit and delete of unit outcome
 * 02-11-2015		Bhagyalaxmi	S S 		Added justification methods
 * 05-2-2015			Bhagyalaxmi S S			Update state_id of topic
 *06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tlo_clo_map_model extends CI_Model {
    /* Function is used to fetch the curriculum id, name, state of TLO to CLO mapping.
     * @param - curriculum id, term id, course id & topic id.
     * @returns- an array of values of curriculum & state of TLO to CLO mapping.
     */

    public function tlo_state($crclm_id, $term, $course, $topic) {

	if ($this->ion_auth->is_admin()) {

	    $query = ' SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
		       FROM curriculum AS c JOIN dashboard AS d on d.entity_id=2
		       AND d.status=1
		       WHERE c.status = 1
		       GROUP BY c.crclm_id
		       ORDER BY c.crclm_name ASC';
	    $query_result = $this->db->query($query);
	    $result = $query_result->result_array();
	    $crclm_fetch_data['res2'] = $result;

	    $state = 'SELECT state 
		      FROM dashboard 
		      WHERE entity_id = 17 
		      AND status = 1 
		      AND crclm_id = "' . $crclm_id . '" 
		      AND particular_id = "' . $topic . '" ';

	    $query_result = $this->db->query($state);
	    $query_result = $query_result->result_array();

	    $query_tlo_mapping = 'SELECT crm.crclm_name, term.term_name,  crs.crs_title, t.topic_title,
				  o.clo_owner_id, u.title, u.first_name, u.last_name
				  FROM curriculum as crm, crclm_terms as term, course as 	crs, topic as t, course_clo_owner AS o, users AS u
				  WHERE crm.crclm_id = "' . $crclm_id . '"
				  AND o.crs_id = "' . $course . '" 
				  AND o.clo_owner_id = u.id 
				  AND term.crclm_term_id = "' . $term . '" 
				  AND crs.crs_id = "' . $course . '" 
				  AND t.topic_id = "' . $topic . '" ';
	    $tlo_clo_map_data = $this->db->query($query_tlo_mapping);
	    $tlo_clo_map_data_result = $tlo_clo_map_data->result_array();
	    $crclm_fetch_data['tlo_map_title'] = $tlo_clo_map_data_result;


	    if ($query_result) {
		$crclm_fetch_data['res3'] = $query_result;
		return $crclm_fetch_data;
	    } else {
		$crclm_fetch_data['res3'] = 0;
		return $crclm_fetch_data;
	    }
	} else if($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')){

	    $loggedin_user_id = $this->ion_auth->user()->row()->id;
	    $query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
		      FROM curriculum AS c, users AS u, program AS p
		      WHERE u.id = "' . $loggedin_user_id . '" 
		      AND u.user_dept_id = p.dept_id
		      AND c.pgm_id = p.pgm_id
		      AND c.status = 1 
		      ORDER BY c.crclm_name ASC';
	    $query_result = $this->db->query($query);
	    $result = $query_result->result_array();
	    $crclm_fetch_data['res2'] = $result;
	    $state = 'SELECT state 
		      FROM dashboard 
		      WHERE entity_id = 17 
		      AND status = 1 
		      AND crclm_id = "' . $crclm_id . '" 
		      AND particular_id = "' . $topic . '" ';

	    $query_result = $this->db->query($state);
	    $query_result = $query_result->result_array();

	    $query_tlo_mapping = 'SELECT crm.crclm_name, term.term_name,  crs.crs_title, t.topic_title,
				  o.clo_owner_id, u.title, u.first_name, u.last_name
				  FROM curriculum as crm, crclm_terms as term, course as 	crs, topic as t, course_clo_owner AS o, users AS u
				  WHERE crm.crclm_id = "' . $crclm_id . '"
				  AND o.crs_id = "' . $course . '" 
				  AND o.clo_owner_id = u.id 
				  AND term.crclm_term_id = "' . $term . '" 
				  AND crs.crs_id = "' . $course . '" 
				  AND t.topic_id = "' . $topic . '" ';
	    $tlo_clo_map_data = $this->db->query($query_tlo_mapping);
	    $tlo_clo_map_data_result = $tlo_clo_map_data->result_array();
	    $crclm_fetch_data['tlo_map_title'] = $tlo_clo_map_data_result;

	    if ($query_result) {
		$crclm_fetch_data['res3'] = $query_result;
		return $crclm_fetch_data;
	    } else {
		$crclm_fetch_data['res3'] = 0;
		return $crclm_fetch_data;
	    }
	} else {
		
	    $loggedin_user_id = $this->ion_auth->user()->row()->id;
	    $query = 'SELECT DISTINCT c.crclm_id, c.crclm_name
		      FROM curriculum AS c, users AS u, program AS p , course_clo_owner AS clo
		      WHERE u.id = "' . $loggedin_user_id . '" 
		      AND u.user_dept_id = p.dept_id
		      AND c.pgm_id = p.pgm_id
		      AND c.status = 1 
			  AND u.id = clo.clo_owner_id
			  AND c.crclm_id = clo.crclm_id
		      ORDER BY c.crclm_name ASC';
	    $query_result = $this->db->query($query);
	    $result = $query_result->result_array();
	    $crclm_fetch_data['res2'] = $result;
	    $state = 'SELECT state 
		      FROM dashboard 
		      WHERE entity_id = 17 
		      AND status = 1 
		      AND crclm_id = "' . $crclm_id . '" 
		      AND particular_id = "' . $topic . '" ';

	    $query_result = $this->db->query($state);
	    $query_result = $query_result->result_array();

	    $query_tlo_mapping = 'SELECT crm.crclm_name, term.term_name,  crs.crs_title, t.topic_title,
				  o.clo_owner_id, u.title, u.first_name, u.last_name
				  FROM curriculum as crm, crclm_terms as term, course as 	crs, topic as t, course_clo_owner AS o, users AS u
				  WHERE crm.crclm_id = "' . $crclm_id . '"
				  AND o.crs_id = "' . $course . '" 
				  AND o.clo_owner_id = u.id 
				  AND term.crclm_term_id = "' . $term . '" 
				  AND crs.crs_id = "' . $course . '" 
				  AND t.topic_id = "' . $topic . '" ';
	    $tlo_clo_map_data = $this->db->query($query_tlo_mapping);
	    $tlo_clo_map_data_result = $tlo_clo_map_data->result_array();
	    $crclm_fetch_data['tlo_map_title'] = $tlo_clo_map_data_result;

	    if ($query_result) {
		$crclm_fetch_data['res3'] = $query_result;
		return $crclm_fetch_data;
	    } else {
		$crclm_fetch_data['res3'] = 0;
		return $crclm_fetch_data;
	    }
	
	}
    }

// End of function tlo_state.

    /* Function is used to fetch the term id & term name from crclm_terms table.
     * @param - curriculum id.
     * @returns- an array of values of term details.
     */

    public function clo_po_select($crclm_id) {
	$term_name = ' SELECT term_name, crclm_term_id 
		       FROM crclm_terms 
		       WHERE crclm_id = "' . $crclm_id . '" ';
	$query_result = $this->db->query($term_name);
	$result = $query_result->result_array();
	$term_data['res2'] = $result;
	return $term_data;
    }

// End of function clo_po_select.

    /* Function is used to check the user group & fetches the course details from course table
     * @param - term id & user id.
     * @returns- an array of values of course details.
     */

    public function course_fill($term_id, $user) {
	if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Program Owner')) {
	    $course_name = ' SELECT distinct(c.crs_id), c.crs_title  
			     FROM course as c
			     WHERE c.crclm_term_id = "' . $term_id . '" 
			     AND c.state_id >= 4 
			     AND c.status = 1 
			     ORDER BY c.crs_title ASC ';
	    $query_result = $this->db->query($course_name);
	    $result = $query_result->result_array();
	    $course_data['res2'] = $result;
	    return $course_data;
	} else if ($this->ion_auth->in_group('Course Owner')) {
	    $course_name = 'SELECT distinct(o.crs_id), c.crs_title  
							FROM course as c, course_clo_owner as o 
							WHERE o.crclm_term_id = "' . $term_id . '"  
							AND o.clo_owner_id = "' . $user . '" 
							AND o.crs_id = c.crs_id 
							AND c.state_id >= 4 
							AND c.status = 1
							ORDER BY c.crs_title ASC ';
	    $query_result = $this->db->query($course_name);
	    $result = $query_result->result_array();
	    $course_data['res2'] = $result;
	    return $course_data;
	}
    }

// End of function course_fill.

    /* Function is used to fetch the topic details from topic table.
     * @param - course id.
     * @returns- an array of values of topic details.
     */

    public function topic_fill($crs_id) {
	$topic_name = 'SELECT topic_id, topic_title, course_id  
						FROM topic 
						WHERE course_id = "' . $crs_id . '" ';
	$query_result = $this->db->query($topic_name);
	$result = $query_result->result_array();
	$topic_data['res2'] = $result;
	return $topic_data;
    }

// End of function topic_fill.

    /* Function is used to fetch the complete TLO (course id, course title, 
     * topic id, topic title, CLO id, CLO statements, TLO id & TLO statements) 
     * details from topic, clo, tlo, dashboard & tlo_clo_map tables.
     * @param - curriculum id, course id & topic id.
     * @returns- an array of values of tlo, clo, course & dashboard details.
     */

    //already mapped checkbox details
    public function tlo_details($crclm_id, $crs_id, $topic_id) {
	$data['crclm_id'] = $crclm_id;
	$data['crs_id'] = $crs_id;
	$topic_query = 'SELECT course_id, topic_id, topic_title, state_id
						FROM topic 
						WHERE topic_id = "' . $topic_id . '" ';
	$topic_list = $this->db->query($topic_query);
	$topic_list_data = $topic_list->result_array();
	$data['topic_list'] = $topic_list_data;
	$tlo = 'SELECT tlo_id, tlo_code, topic_id, tlo_statement ,bloom_id
				FROM tlo 
				WHERE topic_id = "' . $topic_id . '" ';

	$tlo_list = $this->db->query($tlo);
	$tlo_list = $tlo_list->result_array();
	$data['tlo_list'] = $tlo_list;
	$clo = 'SELECT clo_id, clo_code, clo_statement 
				FROM clo 
				WHERE crs_id = "' . $crs_id . '" ';
	$clo_list = $this->db->query($clo);
	$clo_list = $clo_list->result_array();
	$data['clo_list'] = $clo_list;
	$map = 'SELECT outcome_element , pi , pi_codes , tlo_map_id, tlo_id, clo_id, curriculum_id ,justification , created_date 
				FROM tlo_clo_map 
				WHERE course_id = "' . $crs_id . '" ';
	$map_list = $this->db->query($map);
	$map_list = $map_list->result_array();
	$data['map_list'] = $map_list;
	$state = ' SELECT state 
					FROM dashboard 
					WHERE particular_id = "' . $topic_id . '" 
					AND crclm_id = "' . $crclm_id . '" 
					AND status = 1 
					AND entity_id = 17 ';
	$query_result = $this->db->query($state);
	$result = $query_result->result_array();
	$data['map_state'] = $result;
	
	$query = $this->db->query('select indv_mapping_justify_flag from organisation');
	$data['indv_mappig_just'] = $query->result_array();
	
	return $data;
    }

// End of function tlo_details.

    /* Function is used to insert the mapping(clo id, tlo id, curriculum id, topic id & course id) 
     * details onto the table tlo_clo-map.
     * @param - curriculum id, clo id, tlo id, topic id & course id.
     * @returns- a boolean value.
     */

    public function oncheck_save_db($crclmid, $clo_id, $tlo_id, $topic_id, $course_id) {
	//save the mapped values, pi AND its measures in the database
	$delete_query = 'DELETE FROM tlo_clo_map 
						 WHERE tlo_id = "' . $tlo_id . '" ';
	$tlo_delete_success = $this->db->query($delete_query);
	$add_tlo_clo_map = array(
	    'clo_id' => $clo_id,
	    'tlo_id' => $tlo_id,
	    'curriculum_id' => $crclmid,
	    'topic_id' => $topic_id,
	    'course_id' => $course_id
	);
	$this->db->insert('tlo_clo_map', $add_tlo_clo_map);
    }

// End of function oncheck_save_db.

    /* Function is used to delete the mapping between TLO & CLO from tlo_clo_map table.
     * @param - tlo id, clo id.
     * @returns- a boolean value.
     */

    public function unmap_db($tlo_id, $clo_id) {
	$query = 'DELETE FROM tlo_clo_map 
					WHERE clo_id = "' . $clo_id . '" 
					AND tlo_id = "' . $tlo_id . '" ';
	$query_result = $this->db->query($query);
    }

// End of function unmap_db.

    /* Function is used to fetch the Reviewer of mapping between TLO & CLO from course_clo_validator table.
     * @param - course id.
     * @returns- a array of values of reviewer details.
     */

    public function tlo_reviewer($course_id) {
	$course_reviewer_name = ' SELECT c.validator_id ,u.username 
								FROM course_clo_validator as c JOIN users as u ON  c.validator_id = u.id
								WHERE c.crs_id = "' . $course_id . '" ';
	$query_result = $this->db->query($course_reviewer_name);
	$result = $query_result->result_array();
	return $result;
    }

// End of function tlo_reviewer.	

    /* Function is used to update the previous status of mapping of TLO to CLO & inserts a 
     * new entry of status onto the dashboard table.
     * @param - curriculum id, topic id, receiver id(Course-Reviewer), course id & term id.
     * @returns- a boolean value.
     */

    public function dashboard_data_for_review($crclm_id, $topic_id, $receiver_id, $course_id, $term_id) {
	$sender_id = $this->ion_auth->user()->row()->id;
	$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "' . $term_id . '"';
	$term_name_details = $this->db->query($term_name_data);
	$term_name_result = $term_name_details->result_array();
	$term_name = $term_name_result[0]['term_name'];

	$course_title = ' SELECT crs_title 
							   FROM course 
							   WHERE crs_id = "' . $course_id . '" ';
	$course_query = $this->db->query($course_title);
	$course_name_result = $course_query->result_array();
	$course_name = $course_name_result[0]['crs_title'];

	$topic_title = ' SELECT topic_title 
							   FROM topic 
							   WHERE topic_id = "' . $topic_id . '" ';
	$topic_query = $this->db->query($topic_title);
	$topic_name_result = $topic_query->result_array();
	$topic_name = $topic_name_result[0]['topic_title'];

	$update_query = ' UPDATE dashboard 
							SET status = 0
							WHERE particular_id = "' . $topic_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND entity_id = 10';
	$update_data = $this->db->query($update_query);

	$update_query1 = ' UPDATE dashboard 
							SET status = 0
							WHERE particular_id = "' . $topic_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND entity_id = 17';
	$update_data1 = $this->db->query($update_query1);

	$update_topic_data = array(
	    'state_id' => '5'
	);
	$this->db
		->where('curriculum_id', $crclm_id)
		->where('course_id', $course_id)
		->where('term_id', $term_id)
		->where('topic_id', $topic_id)
		->update('topic', $update_topic_data);

	$url = base_url('curriculum/tloclo_map_review/review' . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_id);
	$dashboard_data = array(
	    'crclm_id' => $crclm_id,
	    'particular_id' => $topic_id,
	    'sender_id' => $sender_id,
	    'receiver_id' => $receiver_id,
	    'entity_id' => '17',
	    'url' => $url,
	    'description' => 'Term:- ' . $term_name . ', Course:- ' . $course_name . ', ' . $this->lang->line('entity_topic') . ':- ' . $topic_name . ' :- Mapping between ' . $this->lang->line('entity_tlo') . 's and COs is sent for review.',
	    'state' => '2',
	    'status' => '1'
	);
	$this->db->insert('dashboard', $dashboard_data);
	$select = ' SELECT term_name 
					FROM crclm_terms 
					WHERE crclm_term_id = "' . $term_id . '" ';
	$select = $this->db->query($select);
	$row = $select->result_array();
	$data['term'] = $row;
	$select = ' SELECT crs_title 
					FROM course 
					WHERE crs_id = "' . $course_id . '" ';
	$select = $this->db->query($select);
	$row = $select->result_array();
	$data['course'] = $row;
	$select = ' SELECT topic_title 
					FROM topic 
					WHERE topic_id = "' . $topic_id . '" ';
	$select = $this->db->query($select);
	$row = $select->result_array();
	$data['topic'] = $row;
	$data['url'] = $url;
	return $data;
    }

// End of function dashboard_data_for_review.

    /* Function is used to update the previous status of mapping of TLO to CLO & inserts a 
     * new entry of status onto the dashboard table.
     * @param - curriculum id, topic id, receiver id(Course-Owner), course id & term id.
     * @returns- a boolean value.
     */

    public function dashboard_data_for_review_rework($crclm_id, $topic_id, $receiver_id, $course_id, $term_id) {
	$sender_id = $this->ion_auth->user()->row()->id;

	$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "' . $term_id . '"';
	$term_name_details = $this->db->query($term_name_data);
	$term_name_result = $term_name_details->result_array();
	$term_name = $term_name_result[0]['term_name'];
	$data['term'] = $term_name_result;

	$course_title = ' SELECT crs_title 
							   FROM course 
							   WHERE crs_id = "' . $course_id . '" ';
	$course_query = $this->db->query($course_title);
	$course_name_result = $course_query->result_array();
	$course_name = $course_name_result[0]['crs_title'];
	$data['course'] = $course_name_result;

	$topic_title = ' SELECT topic_title 
							   FROM topic 
							   WHERE topic_id = "' . $topic_id . '" ';
	$topic_query = $this->db->query($topic_title);
	$topic_name_result = $topic_query->result_array();
	$topic_name = $topic_name_result[0]['topic_title'];
	$data['topic'] = $topic_name_result;

	$update_query = ' UPDATE dashboard 
							SET status = 0
							WHERE particular_id = "' . $topic_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND entity_id = 17
							AND status = 1 ';
	$update_data = $this->db->query($update_query);

	$update_topic_data = array(
	    'state_id' => '3'
	);
	$this->db
		->where('curriculum_id', $crclm_id)
		->where('course_id', $course_id)
		->where('term_id', $term_id)
		->where('topic_id', $topic_id)
		->update('topic', $update_topic_data);

	$course_owner = ' SELECT clo_owner_id 
							FROM course_clo_owner 
							WHERE crs_id = "' . $course_id . '" ';
	$receiver_id = $this->db->query($course_owner);
	$row = $receiver_id->result_array();

	$url = base_url('curriculum/tlo_clo_map/map_tlo_clo' . '/' . $crclm_id . '/' . $term_id . '/' . $course_id . '/' . $topic_id);
	$dashboard_data = array(
	    'crclm_id' => $crclm_id,
	    'particular_id' => $topic_id,
	    'sender_id' => $sender_id,
	    'receiver_id' => $row[0]['clo_owner_id'],
	    'entity_id' => '17',
	    'url' => $url,
	    'description' => 'Term:- ' . $term_name . ', Course:- ' . $course_name . ',' . $this->lang->line('entity_topic') . ':- ' . $topic_name . ' :- Mapping between' . $this->lang->line('entity_tlo') . 's and COs is sent back for rework.',
	    'state' => '3',
	    'status' => '1'
	);
	$this->db->insert('dashboard', $dashboard_data);


	$data['url'] = $url;
	$data['crs_owner_id'] = $row[0]['clo_owner_id'];
	return $data;
    }

// End of function dashboard_data_for_review_rework.

    /* Function is used to update the previous status of mapping of TLO to CLO & inserts a 
     * new entry of status onto the dashboard table.
     * @param - curriculum id, topic id, receiver id(Course-Owner), course id & term id.
     * @returns- a boolean value.
     */

    public function dashboard_data_for_review_accept($crclm_id, $topic_id, $receiver_id, $course_id, $term_id) {
	$sender_id = $this->ion_auth->user()->row()->id;

	$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "' . $term_id . '"';
	$term_name_details = $this->db->query($term_name_data);
	$term_name_result = $term_name_details->result_array();
	$term_name = $term_name_result[0]['term_name'];
	$data['term'] = $term_name_result;

	$course_title = ' SELECT crs_title 
							   FROM course 
							   WHERE crs_id = "' . $course_id . '" ';
	$course_query = $this->db->query($course_title);
	$course_name_result = $course_query->result_array();
	$course_name = $course_name_result[0]['crs_title'];
	$data['course'] = $course_name_result;

	$topic_title = ' SELECT topic_title 
							   FROM topic 
							   WHERE topic_id = "' . $topic_id . '" ';
	$topic_query = $this->db->query($topic_title);
	$topic_name_result = $topic_query->result_array();
	$topic_name = $topic_name_result[0]['topic_title'];
	$data['topic'] = $topic_name_result;

	$course_owner = ' SELECT clo_owner_id 
							FROM course_clo_owner 
							WHERE crs_id = "' . $course_id . '" ';
	$receiver_id = $this->db->query($course_owner);
	$row = $receiver_id->result_array();

	$update_query1 = ' UPDATE dashboard 
							SET status = 0
							WHERE particular_id = "' . $topic_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND entity_id = 17 
							AND status = 1 ';
	$update_data1 = $this->db->query($update_query1);

	$update_topic_data = array(
	    'state_id' => '4'
	);
	$this->db
		->where('curriculum_id', $crclm_id)
		->where('course_id', $course_id)
		->where('term_id', $term_id)
		->where('topic_id', $topic_id)
		->update('topic', $update_topic_data);

	$dashboard_data = array(
	    'crclm_id' => $crclm_id,
	    'particular_id' => $topic_id,
	    'sender_id' => $sender_id,
	    'receiver_id' => $row['0']['clo_owner_id'],
	    'entity_id' => '17',
	    'url' => '#',
	    'description' => 'Term:- ' . $term_name . ', Course:- ' . $course_name . ',' . $this->lang->line('entity_topic') . ':- ' . $topic_name . ' :- ' . $this->lang->line('entity_tlo') . 's to COs Mapping Review is Accepted.',
	    'state' => '4',
	    'status' => '1'
	);
	$this->db->insert('dashboard', $dashboard_data);

	$data['url'] = '#';
	$data['receiver_id'] = $row['0']['clo_owner_id'];
	return $data;
    }

// End of function dashboard_data_for_review_accept.

    /* Function is used to fetch the state id of TLO to CLO mapping from the dashboard table. 
     * @param - curriculm id, course id & topic id.
     * @returns- a array of state id value.
     */

    public function check_state($crclm_id, $course_id, $topic_id) {
	$state = ' SELECT state 
					FROM dashboard 
					WHERE crclm_id = "' . $crclm_id . '" 
					AND particular_id = "' . $topic_id . '" 
					AND entity_id = 10 
					AND state = 1 ';
	$query_result = $this->db->query($state)->num_rows();
	if ($query_result == 1) {
	    $state1 = ' SELECT state 
						FROM dashboard 
						WHERE crclm_id = "' . $crclm_id . '" 
						AND particular_id = "' . $topic_id . '" 
						AND entity_id = 17
						AND status = 1 ';
	    $query_result = $this->db->query($state1);
	    $query_result = $query_result->result_array();
	    return $query_result;
	}
    }

// End of function check_state.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function review_accept_db($crclmid) {
	$map_review = "INSERT INTO workflow_history(entity_id, crclm_id, state_id) values (17,'" . $crclmid . "',4)";
	$review = $this->db->query($map_review);
    }

// End of function review_accept_db.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function review_rework_db($crclmid) {
	$map_review = "INSERT INTO workflow_history(entity_id, crclm_id, state_id) values (17,'" . $crclmid . "',3)";
	$review = $this->db->query($map_review);
    }

// End of function review_rework_db.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function review_db($crclm_id, $crs_id) {
	$course_reviwer_query = 'SELECT o.validator_id , u.title, u.first_name, u.last_name, c.crclm_name
									FROM  course_clo_validator AS o, users AS u, curriculum AS c
									WHERE o.crs_id = "' . $crs_id . '" 
									AND o.validator_id 	 = u.id 
									AND c.crclm_id = "' . $crclm_id . '" ';
	$course_viewer = $this->db->query($course_reviwer_query);
	$course_viewer = $course_viewer->result_array();
	return $course_viewer;
    }

// End of function review_db.

    /* Function is used to insert TLO to CLO mapping current state id entry onto the workflow_history table.
     * @param - curriculum  id.
     * @returns- a boolean value.
     */

    public function approve_db($crclmid) {
	$map_approve = "INSERT INTO workflow_history(entity_id, crclm_id, state_id) values (17,'" . $crclmid . "',5)";
	$approve = $this->db->query($map_approve);
    }

// End of function approve_db.

    /* Function is used to updates if TLO to CLO mapping comments entry is present or else
     * it inserts an new entry for comments onto the notes table.
     * @param - curriculum  id, term id, topic id & comment statement.
     * @returns- a boolean value.
     */

    //save textarea (id = comment_notes_id) in the database
    public function save_txt_db($crclm_id, $term_id, $topic_id, $text) {
	$note_query = ' SELECT notes_id 
						FROM notes WHERE crclm_id = "' . $crclm_id . '" 
						AND term_id = "' . $term_id . '" 

						AND particular_id = "' . $topic_id . '" 
						AND entity_id = 17 ';
	$count = $this->db->query($note_query)->num_rows();

	$data = array(
	    'notes' => $text,
	    'crclm_id' => $crclm_id,
	    'term_id' => $term_id,
	    'particular_id' => $topic_id,
	    'entity_id' => '17'
	);
	if ($count > 0) {
	    $update_query = ' UPDATE notes 
								SET notes = "' . $text . '" 
								WHERE crclm_id = "' . $crclm_id . '" 
								AND term_id = "' . $term_id . '" 
								AND particular_id = "' . $topic_id . '" 
								AND entity_id = 17 ';
	    $notesdata = $this->db->query($update_query);
	    return $notesdata;
	} else {
	    $notesdata = $this->db->insert('notes', $data);
	    return $notesdata;
	}
    }

// End of function save_txt_db.

    /* Function is used to fetch the previously entered comments for TLO to CLO mapping form notes table.
     * @param - curriculum id, topic id & term id.
     * @returns- an object.
     */

    //textarea
    public function text_details($crclmid, $topic_id, $term_id) {
	$notes = ' SELECT notes 
					FROM notes 
					WHERE crclm_id = "' . $crclmid . '" 
					AND term_id = "' . $term_id . '" 
					AND entity_id = 17 
					AND particular_id = "' . $topic_id . '" ';
	$notes = $this->db->query($notes);
	$notes1 = $notes->result_array();

	if (isset($notes1[0]['notes'])) {
	    header('Content-Type: application/x-json; charset=utf-8');
	    echo(json_encode($notes1[0]['notes']));
	} else {
	    header('Content-Type: application/x-json; charset=utf-8');
	    $temp = 'Enter text here..';
	    echo(json_encode($temp));
	}
    }

// End of function text_details.

    public function text_details_onload($crclmid, $topic_id, $term_id) {
	$notes = ' SELECT notes 
					FROM notes 
					WHERE crclm_id = "' . $crclmid . '" 
					AND term_id = "' . $term_id . '" 
					AND entity_id = 17 
					AND particular_id = "' . $topic_id . '" ';
	$notes = $this->db->query($notes);
	$notes1 = $notes->result_array();

	if (isset($notes1[0]['notes'])) {
	    // header('Content-Type: application/x-json; charset=utf-8');
	    return (($notes1[0]['notes']));
	} else {
	    //  header('Content-Type: application/x-json; charset=utf-8');
	    $temp = 'Enter text here..';
	    return (($temp));
	}
    }

    /**
     * Function to fetch help related details for curriculum, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function tlo_clo_map_help() {
	$help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 17';
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
     * Function to fetch help related to curriculum to display
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
      Function to Load the Outcome Elements and PIs in Modal
      @parameters : clo_id, crclm_id
      @return : list of OE and PIS
     */

    public function oe_pi_select($tlo_id, $clo_id, $crclm_id, $course_id, $topic_id, $term_id) {
	$clo_po_map_oe_query = 'SELECT DISTINCT cpm.pi_id, cpm.msr_id, pi.pi_id, pi.pi_statement, msr.msr_id, msr.msr_statement, pi_codes
								FROM clo_po_map AS cpm, performance_indicator AS pi, measures AS msr
								WHERE cpm.crclm_id = "' . $crclm_id . '"
									AND cpm.crs_id = "' . $course_id . '"
									AND cpm.clo_id = "' . $clo_id . '"
									AND cpm.pi_id = pi.pi_id
									AND cpm.msr_id = msr.msr_id';
	$oe_pi_list_data = $this->db->query($clo_po_map_oe_query);
	$oe_pi_list_result = $oe_pi_list_data->result_array();

	if (empty($oe_pi_list_result)) {
	//var_dump($oe_pi_list_result);
	    // OE & PIs optional
	    // Delete earlier entry of po_id onto table clo_po_map
	    $query = ' DELETE FROM  tlo_clo_map 
						WHERE tlo_id = "' . $tlo_id . '"  ';
	    $result = $this->db->query($query);
	    // Insert only po_id onto table clo_po_map
	    $add_clo_po_map = array(
		'tlo_id' => $tlo_id,
		'clo_id' => $clo_id,
		'curriculum_id' => $crclm_id,
		'course_id' => $course_id,
		'topic_id' => $topic_id,
		'created_by' => $this->ion_auth->user()->row()->id,
		'created_date' => date('Y-m-d')
	    );
	    $this->db->insert('tlo_clo_map', $add_clo_po_map);
	    return false;
	} else {
	
	
	    return $oe_pi_list_result;
	}
    }

    /*
     * Function is to save the Outcome Elements  & PIS oncheck of check box.
     * @param curriculum id, tlo id, clo id, pi id course id and oe id used to save oncheck of check box.
     * returns ----.
     */

    //This function has to be modify
    public function oe_pi_oncheck_save_db($crclmid, $crsid, $topic_id, $tlo_id, $clo_id, $outcome_ele, $pis) {
	$map_del_query = 'DELETE FROM tlo_clo_map 
						WHERE tlo_id = "' . $tlo_id . '"';
	$map_del_result = $this->db->query($map_del_query);

	$oe_id = sizeof($outcome_ele);

	$pi_id = sizeof($pis);
	for ($j = 0; $j < $oe_id; $j++) {

	    $count = 0;
	    for ($m = 0; $m < $pi_id; $m++) {
		$oe_pi_query = 'SELECT msr_id, pi_codes
								FROM measures 
								WHERE pi_id = "' . $outcome_ele[$j] . '"
									AND msr_id = "' . $pis[$m] . '" ';
		$oe_pi_data = $this->db->query($oe_pi_query);
		$oe_pi_result = $oe_pi_data->result_array();

		if ($oe_pi_result) {
		    $count++;
		    $oepis[$count] = $oe_pi_result;
		}
	    }



	    $clo_id_count_query = 'SELECT clo_id 
									FROM tlo_clo_map
									WHERE tlo_id= "' . $tlo_id . '"
										AND clo_id ="' . $clo_id . '"';
	    $clo_id_count_data = $this->db->query($clo_id_count_query);
	    $clo_id_count = $clo_id_count_data->result_array();


	    if (empty($clo_id_count)) {
		for ($k = 1; $k <= $count; $k++) {
		    $add_clo_po_map = array(
			'clo_id' => $clo_id,
			'tlo_id' => $tlo_id,
			'curriculum_id' => $crclmid,
			'course_id' => $crsid,
			'topic_id' => $topic_id,
			'outcome_element' => $outcome_ele[$j],
			'pi' => $oepis[$k][0]['msr_id'],
			'pi_codes' => $oepis[$k][0]['pi_codes'],
			'created_by' => $this->ion_auth->user()->row()->id,
			'created_date' => date('Y-m-d'));

		    $this->db->insert('tlo_clo_map', $add_clo_po_map);
		}
	    } else {

		for ($k = 1; $k <= $count; $k++) {
		    $add_clo_po_map = array(
			'clo_id' => $clo_id,
			'tlo_id' => $tlo_id,
			'curriculum_id' => $crclmid,
			'course_id' => $crsid,
			'topic_id' => $topic_id,
			'outcome_element' => $outcome_ele[$j],
			'pi' => $oepis[$k][0]['msr_id'],
			'pi_codes' => $oepis[$k][0]['pi_codes'],
			'created_by' => $this->ion_auth->user()->row()->id,
			'created_date' => date('Y-m-d'));

		    $this->db->insert('tlo_clo_map', $add_clo_po_map);
		}
	    }
	}

	return true;
    }

    /**
     * Function to fetch saved outcome elements and its performance indicator from tlo clo map table and 
      performance indicator & measures table respectively
     * @parameters: curriculum id, term id, course id, topic learning outcomes id and course learning outcomes id
     * @return: outcome elements and performance indicator statements
     */
    public function modal_display_pm_model($curriculum_id, $term_id, $course_id, $topic_id, $clo_id, $tlo_id) {
	$tlo_clo_map_pm = 'SELECT DISTINCT tcm.outcome_element, tcm.pi, pi.pi_id, pi.pi_statement,
								msr.msr_id, msr.msr_statement, msr.pi_codes
						   FROM tlo_clo_map AS tcm, performance_indicator AS pi, measures AS msr
						   WHERE tcm.curriculum_id = "' . $curriculum_id . '"
							AND tcm.clo_id = "' . $clo_id . '"
							AND tcm.tlo_id = "' . $tlo_id . '"
							AND tcm.outcome_element = pi.pi_id
							AND tcm.pi = msr.msr_id';
	$tlo_clo_map_list_pm = $this->db->query($tlo_clo_map_pm);
	$map_list_pm = $tlo_clo_map_list_pm->result_array();
	$data['map_list_pm'] = $map_list_pm;

	return $data;
    }

    public function get_clo_val($tlo_id) {
	$clo_query = 'SELECT clo_id FROM tlo_clo_map WHERE tlo_id = "' . $tlo_id . '" GROUP BY clo_id';
	$clo_data = $this->db->query($clo_query);
	$clo_result = $clo_data->result_array();
	if (!empty($clo_result)) {
	    return $clo_result[0]['clo_id'];
	} else {
	    return $clo_result = 0;
	}
    }

    /* Function is used to fetch the Curse Reviewer User for COs to TLOs Mapping from course_clo_validator table.
     * @param - curriculum id.
     * @returns- a array value of the BoS details.
     */

    public function fetch_course_reviewer($curriculum_id, $crs_id) {
	$course_reviwer_query = 'SELECT o.validator_id , u.title, u.first_name, u.last_name, c.crclm_name
									FROM  course_clo_validator AS o, users AS u, curriculum AS c
									WHERE o.crs_id = "' . $crs_id . '" 
									AND o.validator_id 	 = u.id 
									AND c.crclm_id = "' . $curriculum_id . '" ';
	$course_viewer = $this->db->query($course_reviwer_query);
	$course_viewer = $course_viewer->result_array();
	return $course_viewer;
    }

// End of function fetch_course_reviewer.	

    public function skip_review_flag_fetch() {
	return $this->db->select('skip_review')
			->where('entity_id', 17)
			->get('entity')
			->result_array();
    }

    /**
     * Function to update the tlo statement
     * @parameters: tlo id and tlo statement
     * @return: 
     */
    public function update_tlo_statement($tlo_id, $updated_tlo_statement, $bloom_id) {
	$update_clo_statement_query = $this->db->query('UPDATE tlo SET tlo_statement="' . $updated_tlo_statement . '",bloom_id = ' . $bloom_id . ', modified_by = "' . $this->ion_auth->user()->row()->id . '", modified_date = "' . date('Y-m-d') . '" WHERE tlo_id=' . $tlo_id);
	return true;
    }

//End of update_tlo_statement function

    /**
     * Function to get Blooms Level
     * @parameters: 
     * @return: 
     */
    public function getBloomsLevel() {
	$get_bloom_level_query = $this->db->query('SELECT bloom_id, level, description FROM bloom_level');
	return $get_bloom_level_query->result_array();
    }

//End of getBloomsLevel function

    /**
     * Function to get Blooms Level ActionVerb
     * @parameters: 
     * @return: 
     */
    public function getBloomsLevelActionVerb($bloom_id) {
	$get_bloom_action_verb = $this->db->query('SELECT bloom_actionverbs FROM bloom_level where bloom_id = ' . $bloom_id);
	return $get_bloom_action_verb->result_array();
    }

//End of getBloomsLevelActionVerb function

    /**
     * Function to delete TLO
     * @parameters: 
     * @return: 
     */
    public function delete_tlo_statement($tlo_id) {
	$delete_tlo_query = $this->db->query('DELETE FROM tlo where tlo_id = ' . $tlo_id);
	return $delete_tlo_query;
    }

//End of delete_tlo_statement function

    /**
     * Function to add Unit Outcome statement
     * @parameters: curriculum_id,term_id,course_id,co_stmt,topic id
     * @return: 
     */
    public function add_more_tlo_statement($curriculum_id, $term_id, $course_id, $topic_id, $tlo_stmt, $bloom_id) {
	$add_tlo_data = array(
	    'tlo_statement' => $tlo_stmt,
	    'curriculum_id' => $curriculum_id,
	    'term_id' => $term_id,
	    'course_id' => $course_id,
	    'topic_id' => $topic_id,
	    'bloom_id' => $bloom_id,
	    'created_by' => $this->ion_auth->user()->row()->id,
	    'created_date' => date('Y-m-d')
	);
	$add_tlo_query = $this->db->insert('tlo', $add_tlo_data);
	$tlo_fetch = $this->db
		->select('tlo_id')
		->where('course_id', $course_id)
		->where('term_id', $term_id)
		->where('curriculum_id', $curriculum_id)
		->where('topic_id', $topic_id)
		->get('tlo')
		->result_array();
	$i = 1;
	$tlo_alias = $this->lang->line('entity_tlo');
	foreach ($tlo_fetch as $tlo_data) {
	    $tlo_code_update = $this->db
		    ->set('tlo_code', $tlo_alias . $i)
		    ->where('tlo_id', $tlo_data['tlo_id'])
		    ->update('tlo');
	    $i++;
	}

	return $add_tlo_query;
    }

//End of add_more_co_statement function

    public function fetch_oe_pi_flag($crclm_id) {
	return $this->db->select('oe_pi_flag')
			->where('crclm_id', $crclm_id)
			->get('curriculum')
			->result_array();
    }
	
	public function fetch_oe_pi_count($crclm_id, $crs_id, $topic_id){
	$query = $this->db->query('SELECT count(tlo_map_id) FROM tlo_clo_map  where topic_id = 6107 and outcome_element!="" and  pi != "" and  pi_codes != "" and curriculum_id = "'.$crclm_id.'" and course_id = "'.$crs_id.'"');
	$result = $query->result_array();
	return ($result);	
	}

    public function update_topic($topic_id) {
	$query = 'update topic set state_id=4 where topic_id="' . $topic_id . '"';
	return $this->db->query($query);
    }
	
	public function save_justification($data){   
		$result = $this->db->query('update tlo_clo_map set justification ="'.$data['justification'].'" where  tlo_map_id="'.$data['tlo_map_id'].'"');
			if($result){return 1;}else{return 0;}
	}
	
	public function fetch_tlo_co_mapping_justification($clo_id, $tlo_id, $curriculum_id ,$tlo_map_id) {
	$select_comment_data_query = 'SELECT  *
				      FROM tlo_clo_map 
				      WHERE curriculum_id = "' . $curriculum_id . '" AND clo_id ="' . $clo_id . '" AND tlo_id ="' . $tlo_id . '"';
	$comment_data = $this->db->query($select_comment_data_query);
	$comment_data_result = $comment_data->result_array();


	return $comment_data_result;
    }

}

// End of Class Tlo_clo_map_model.
?>
