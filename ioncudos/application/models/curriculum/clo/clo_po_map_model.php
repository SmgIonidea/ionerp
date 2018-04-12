<?php

/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Model Logic for Mapping Review and Mapping Rework of CLO to PO.	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013       Mritunjay B S           Added file headers, function headers & comments. 
 * 09-04-2015		Jyoti					Added snippets for CO to multiple Bloom's 
  Level and Delivery methods.
 * 29-10-2015		Bhagyalaxmi				Added justification methods
 * 04-12-2015		Bhagyalaxmi S S 		Added function to fetch course mode
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_map_model extends CI_Model {

    public $comment = "comment";

    /*
     * Function is to fetch the CLO PO Mapping details.
     * @param - curriculum id, term id and course id is used to fetch the particular course clo to po mapping details.
     * returns the clo to po mapping details.
     */

    public function clomap_details($crclm_id, $course_id, $term_id) {

        $data['crclm_id'] = $crclm_id;
        $data['course_id'] = $course_id;
        $course_query = 'SELECT  crs_title 
			 FROM course 
			 WHERE crs_id = "' . $course_id . '" ';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();
        $data['course_list'] = $course_list_data;

        $clo_data_query = 'SELECT clo_id, clo_code, crclm_id, clo_statement, term_id 
			   FROM clo 
			   WHERE crs_id = "' . $course_id . '" 
			   ORDER BY clo_code ASC';
        $clo_list = $this->db->query($clo_data_query);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;


        $po_data_query = 'SELECT po_id,pso_flag,crclm_id, po_statement, po_reference 
			  FROM po 
			  WHERE crclm_id = "' . $crclm_id . '"
			  ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po_data_query);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

        $clo_po_map_query = 'SELECT DISTINCT clo_id, po_id, pi_id , msr_id , map_level,justification,clo_po_id, crs_id, create_date
		             FROM clo_po_map 
			     WHERE crclm_id = "' . $crclm_id . '" AND crs_id = "' . $course_id . '" ';
        $clo_po_map_list = $this->db->query($clo_po_map_query);
        $clo_po_map_result = $clo_po_map_list->result_array();
        $data['map_list'] = $clo_po_map_result;

        $select_comment_data_query = 'SELECT  clo_id, po_id, crclm_id, cmt_statement, status 
				      FROM comment 
				      WHERE crclm_id = "' . $crclm_id . '" ';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();
        $data['comment'] = $comment_data_result;

        //extra function and it is used to display the comments.
        $select_query = 'SELECT clo_id, po_id, crclm_id,cmt_statement, status 
			 FROM comment WHERE entity_id = 16 AND crclm_id = "' . $crclm_id . '" ';
        $comment = $this->db->query($select_query);
        $comment_data_result = $comment->result_array();
        $data['comment_data'] = $comment_data_result;


        $clo_po_map_state_query = ' SELECT state 
				    FROM dashboard 
				    WHERE particular_id = "' . $course_id . '" and crclm_id = "' . $crclm_id . '" and entity_id = 16 and status = 1';
        $clo_po_map_state_data = $this->db->query($clo_po_map_state_query);
        $clo_po_map_state_result = $clo_po_map_state_data->result_array();

        $data['map_level'] = $this->db->select('map_level_name,map_level_short_form,map_level,status')
                ->where('status', 1)
                ->get('map_level_weightage')
                ->result_array();
        $oe_pi_flag_query = 'select * from curriculum where crclm_id="' . $crclm_id . '"';
        $oe_pi_flag_data = $this->db->query($oe_pi_flag_query);
        $oe_pi_flag_result = $oe_pi_flag_data->result_array();

        $data['oe_pi_flag'] = $oe_pi_flag_result;
        if (!empty($clo_po_map_state_result)) {
            $data['map_state'] = $clo_po_map_state_result;
        } else {
            $data['map_state'] = array(array('state' => 0));
        }

        $query = $this->db->query('select indv_mapping_justify_flag from organisation');
        $data['indv_mappig_just'] = $query->result_array();

		$org_type = $this->db->query('SELECT org_type FROM organisation o');
		$org_type_data = $org_type->result_array();
		if($org_type_data[0]['org_type'] == 'TIER-I'){
			
			$query = $this->db->query('SELECT * FROM tier1_crs_clo_overall_attainment t where crclm_id = "' . $crclm_id . '" and crclm_term_id ="' . $term_id . '" and crs_id ="' . $course_id . '" ');}else{
			
			$query = $this->db->query('SELECT * FROM tier_ii_crs_clo_overall_attainment t where crclm_id = "' . $crclm_id . '" and crclm_term_id ="' . $term_id . '" and crs_id ="' . $course_id . '" ');
		}
        $result = $query->result_array();
        
		$data['qp_status'] = count($result);
		
		
        return $data;
    }

//comment function

    public function comment_data_fetch($clo_id, $po_id, $crclm_id) {
        $select_comment_data_query = 'SELECT  clo_id, po_id, crclm_id, cmt_statement, status 
				      FROM comment 
				      WHERE crclm_id = "' . $crclm_id . '" AND clo_id ="' . $clo_id . '" AND po_id ="' . $po_id . '" ';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();


        return $comment_data_result;
    }

    public function fetch_co_po_mapping_justification($clo_id, $po_id, $crclm_id, $clo_po_id) {
        $select_comment_data_query = 'SELECT  clo_id, po_id, crclm_id, justification 
				      FROM clo_po_map 
				      WHERE crclm_id = "' . $crclm_id . '" AND clo_id ="' . $clo_id . '" AND po_id ="' . $po_id . '"';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();


        return $comment_data_result;
    }

    /*
     * Function is to fetch the curriculum name and course state.
     * @param - curriculum id, term id and course id is used fetch the curriculum name and course state.
     * returns curriculum name and course status.
     */

    public function crclm_fill_state($crclm_id, $term, $course) {
        // echo $course;
        // exit;
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin')) {
            $crclm_name_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				 FROM curriculum AS c, dashboard AS d, users AS u, program AS p
				 WHERE u.user_dept_id = p.dept_id
				 AND c.pgm_id = p.pgm_id
				 AND c.status = 1
				 AND d.entity_id = 2
				 AND d.status = 1 
				 GROUP BY c.crclm_id
				 ORDER BY c.crclm_name ASC';
            $crclm_name_data = $this->db->query($crclm_name_query);
            $crclm_name_result = $crclm_name_data->result_array();
            $crclm_data['curriculum_data'] = $crclm_name_result;
        } else if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {

            $crclm_name_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				 FROM curriculum AS c, dashboard AS d, users AS u, program AS p
				 WHERE u.id = "' . $loggedin_user_id . '"
				 AND u.user_dept_id = p.dept_id
				 AND c.pgm_id = p.pgm_id
				 AND c.status = 1
				 AND d.entity_id = 2
				 AND d.status = 1 
				 GROUP BY c.crclm_id
				 ORDER BY c.crclm_name ASC';
            $crclm_name_data = $this->db->query($crclm_name_query);
            $crclm_name_result = $crclm_name_data->result_array();
            $crclm_data['curriculum_data'] = $crclm_name_result;
        } else {
            $crclm_name_query = 'SELECT DISTINCT c.crclm_id, c.crclm_name, d.state
				 FROM curriculum AS c, dashboard AS d, users AS u, program AS p ,course_clo_owner AS clo
				 WHERE u.id = "' . $loggedin_user_id . '"
				 AND u.user_dept_id = p.dept_id
				 AND c.pgm_id = p.pgm_id
				 AND c.status = 1
				 AND d.entity_id = 2
				 AND d.status = 1 
				 AND u.id = clo.clo_owner_id
				 AND c.crclm_id = clo.crclm_id
				 GROUP BY c.crclm_id
				 ORDER BY c.crclm_name ASC';
            $crclm_name_data = $this->db->query($crclm_name_query);
            $crclm_name_result = $crclm_name_data->result_array();
            $crclm_data['curriculum_data'] = $crclm_name_result;
        }

        $dashboard_state_query = 'SELECT state 
				  FROM dashboard 
				  WHERE entity_id = 16 and status = 1 and particular_id = "' . $course . '" ';
        $dashboard_state_data = $this->db->query($dashboard_state_query);
        $dashboard_state_result = $dashboard_state_data->result_array();

        $query_clo_mapping = 'SELECT crm.crclm_name, term.term_name, crs.crs_title
			      FROM curriculum AS crm, crclm_terms AS term, course AS crs
			      WHERE crm.crclm_id ="' . $crclm_id . '"
			      AND term.crclm_term_id ="' . $term . '"
			      AND crs.crs_id ="' . $course . '" ';
        $clo_po_map_data = $this->db->query($query_clo_mapping);

        $clo_po_map_data_result = $clo_po_map_data->result_array();
        $crclm_data['clo_map_title'] = $clo_po_map_data_result;

        $query = 'select * from notes where crclm_id="' . $crclm_id . '" AND term_id="' . $term . '" AND particular_id="' . $course . '"';
        $re = $this->db->query($query);
        $crclm_data['notes'] = ($re->result_array());        
        if ($dashboard_state_result) {
            $crclm_data['dashboard_state'] = $dashboard_state_result;
            return $crclm_data;
        } else {
            $crclm_data['dashboard_state'] = 0;
            return $crclm_data;
        }
    }

    /*
     * Function is to fetch the curriculum list.
     * @param -----.
     * returns ----.
     */

    public function crclm_drop_down_fill() {

        $crclm_list_query = 'SELECT crclm_id, crclm_name 
			     FROM curriculum
			     ORDER BY crclm_name ASC';
        $crclm_list_data = $this->db->query($crclm_list_query);
        $crclm_list_result = $crclm_list_data->result_array();
        $crclm_data['curriculum_list'] = $crclm_list_result;
        return $crclm_data;
    }

    /*
     * Function is to fetch the term list.
     * @param curriculum id is used to fetch the respective term list.
     * returns term list.
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
     * Function is to fetch the course list.
     * @param term id is used to fetch the respective course list.
     * returns course list.
     */

    public function course_drop_down_fill($crclm_id, $term_id) {
        $user = $this->ion_auth->user()->row()->id;
        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) {
            $course_list_query = ' SELECT DISTINCT c.crs_id,c.crs_title  
				   FROM course as c, dashboard as d 
				   WHERE d.entity_id NOT IN(2,5,6,13,20)
				   AND c.crclm_term_id = "' . $term_id . '" 
				   AND d.crclm_id = "' . $crclm_id . '" 
				   AND c.state_id != 1 
				   AND d.particular_id = c.crs_id 
				   AND d.status = 1 
				   ORDER BY c.crs_title ASC';
            $course_list_data = $this->db->query($course_list_query);
            $course_list_result = $course_list_data->result_array();
            $course_data['course_list'] = $course_list_result;
            return $course_data;
        } else if ($this->ion_auth->in_group('Course Owner')) {
            $course_info_query = ' SELECT DISTINCT o.crs_id,c.crs_title  
				   FROM course as c, course_clo_owner as o, dashboard as d 
				   WHERE d.entity_id NOT IN(2,5,6,13,20)
				   AND o.crclm_term_id = "' . $term_id . '"  
				   AND d.crclm_id = "' . $crclm_id . '"  
				   AND o.clo_owner_id = "' . $user . '" 
				   AND o.crs_id = c.crs_id 
				   AND c.state_id != 1 
				   AND d.particular_id = o.crs_id 
				   AND d.status = 1
				   ORDER BY c.crs_title ASC';
            $course_info_data = $this->db->query($course_info_query);
            $course_info_result = $course_info_data->result_array();
            $course_data['course_list'] = $course_info_result;
            return $course_data;
        } else {
            $course_info_query = ' SELECT DISTINCT o.crs_id,c.crs_title  
				   FROM course as c, course_clo_owner as o, dashboard as d 
				   WHERE d.entity_id NOT IN(2,5,6,13,20)
				   AND o.crclm_term_id = "' . $term_id . '" 
				   AND o.crs_id = c.crs_id 
				   AND d.particular_id = o.crs_id 
				   AND d.status = 1
				   ORDER BY c.crs_title ASC';
            $course_info_data = $this->db->query($course_info_query);
            $course_info_result = $course_info_data->result_array();
            $course_data['course_list'] = $course_info_result;
            return $course_data;
        }
    }

    /*
     * Function is to fetch the course list.
     * @param term id is used to fetch the respective course list.
     * returns course list.
     */

    public function course_reviewer($course_id) {
        $course_reviewer_query = ' SELECT c.validator_id ,u.username 
				   FROM course_clo_validator as c ,users as u 
				   WHERE c.crs_id = "' . $course_id . '" and c.validator_id = u.id ';
        $course_reviewer_data = $this->db->query($course_reviewer_query);
        $course_reviewer_name = $course_reviewer_data->result_array();
        return $course_reviewer_name;
    }

    /*
     * Function is to fetch the notes added during the mapping.
     * @param curriculum id, course id and term id is used to fetch mapping notes.
     * returns mapping notes.
     */

    public function text_details_old($crclm_id, $term_id, $course_id) {
        $notes_query = ' SELECT notes 
			 FROM notes 
			 WHERE crclm_id="' . $crclm_id . '" AND term_id = "' . $term_id . '" AND particular_id = "' . $course_id . '" AND entity_id = 16 ';
        $notes_data = $this->db->query($notes_query);
        $notes_result = $notes_data->result_array();

        if (isset($notes_result[0]['notes'])) {
            header('Content-Type: application/x-json; charset=utf-8');
            echo(json_encode($notes_result[0]['notes']));
        } else {
            header('Content-Type: application/x-json; charset=utf-8');
            $temp = 'Enter text here...';
            echo(json_encode($temp));
        }
    }

    /*
     * Function is to update the mapping notes.
     * @param curriculum id, course id and term id and text is used to fetch mapping notes.
     * returns mapping notes.
     */

    public function save_txt_db($crclm_id, $term_id, $course_id, $text) {
        $note_query = ' SELECT notes_id 
			FROM notes 
			WHERE crclm_id = "' . $crclm_id . '" and term_id = "' . $term_id . '" and particular_id = "' . $course_id . '" and entity_id = 16 ';
        $count = $this->db->query($note_query)->num_rows();
        $data = array(
            'notes' => $text,
            'crclm_id' => $crclm_id,
            'term_id' => $term_id,
            'particular_id' => $course_id,
            'entity_id' => '16');

        if ($count > 0) {
            $update_query = ' UPDATE notes 
			      SET notes = "' . $text . '" 
			      WHERE crclm_id = "' . $crclm_id . '" AND term_id = "' . $term_id . '" AND particular_id = "' . $course_id . '" AND entity_id = 16 ';
            $notesdata = $this->db->query($update_query);
            return $notesdata;
        } else {
            $notesdata = $this->db->insert('notes', $data);
            return $notesdata;
        }
    }

    /*
     * Function is to fetch the mapping details for the particular user dashboard.
     * @param curriculum id, course id and term id and receiver id is used to fetch mapping data and display in dashboard.
     * returns the mapping data for the particular user.
     */

    public function dashboard_data($crclm_id, $course_id, $term_id) {
        $term_data_query = ' SELECT term_name 
		             FROM crclm_terms 
			     WHERE crclm_term_id = "' . $term_id . '" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
        $data['term'] = $term_result;

        $course_data_query = ' SELECT crs_title 
			       FROM course 
			       WHERE crs_id = "' . $course_id . '" ';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $data['course'] = $course_result;

        $update_query = ' UPDATE dashboard 
			  SET status = 0 
			  WHERE particular_id="' . $course_id . '" 
			  AND crclm_id = "' . $crclm_id . '" 
			  AND entity_id = 11';
        $update_data = $this->db->query($update_query);

        $update_query = ' UPDATE dashboard 
			  SET status = 0 
			  WHERE particular_id="' . $course_id . '" 
			  AND crclm_id = "' . $crclm_id . '" 
			  AND entity_id = 16';
        $update_data = $this->db->query($update_query);

        $clo_owner_id_query = ' SELECT clo_owner_id 
				FROM course_clo_owner 
				WHERE crs_id = "' . $course_id . '" 
				AND crclm_id = "' . $crclm_id . '" ';
        $clo_owner_id_data = $this->db->query($clo_owner_id_query);
        $clo_owner_id_result = $clo_owner_id_data->result_array();

        $clo_validator_id_query = ' SELECT validator_id 
				    FROM course_clo_validator	 
				    WHERE crs_id = "' . $course_id . '" 
				    AND crclm_id = "' . $crclm_id . '" ';
        $clo_validator_id_data = $this->db->query($clo_validator_id_query);
        $clo_validator_id_result = $clo_validator_id_data->result_array();
        $validator_id = $clo_validator_id_result[0]['validator_id'];

        $url = base_url('curriculum/clopomap_review/review' . '/' . $crclm_id . '/' . $term_id . '/' . $course_id);
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => $course_id,
            'sender_id' => $clo_owner_id_result[0]['clo_owner_id'],
            'receiver_id' => $validator_id,
            'entity_id' => '16',
            'url' => $url,
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . ' - Mapping between COs and ' . $this->lang->line('sos') . ' is sent for review.',
            'state' => '2',
            'status' => '1');

        $this->db->insert('dashboard', $dashboard_data);

        $course_state_update_query = ' UPDATE course 
				       SET state_id = 2
				       WHERE crs_id = "' . $course_id . '" 
				       AND crclm_id = "' . $crclm_id . '" ';
        $course_state_update_data = $this->db->query($course_state_update_query);

        $data['url'] = $url;
        $data['receiver_id'] = $validator_id;

        return $data;
    }

    /*
     * Function is to fetch the mapping details for the particular user dashboard.
     * @param curriculum id, course id and term id and receiver id is used to fetch mapping data and display in dashboard.
     * returns the mapping data for the particular user.
     */

    public function rework_dashboard_data($crclm_id, $course_id, $receiver_id, $term_id) {
        $clo_owner_id = ' SELECT clo_owner_id 
			  FROM course_clo_owner 
			  WHERE crs_id = "' . $course_id . '" 
			  AND crclm_id = "' . $crclm_id . '" ';
        $resx = $this->db->query($clo_owner_id);
        $res2 = $resx->result_array();

        $term_data_query = ' SELECT term_name 
		             FROM crclm_terms 
		             WHERE crclm_term_id = "' . $term_id . '" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
        $data['term'] = $term_result;

        $course_data_query = ' SELECT crs_title 
								FROM course 
								WHERE crs_id = "' . $course_id . '" ';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $data['course'] = $course_result;

        $update_query = ' UPDATE dashboard 
						  SET status = 0 
						  WHERE particular_id = "' . $course_id . '" 
							AND crclm_id = "' . $crclm_id . '" 
							AND entity_id = 16';

        $update_data = $this->db->query($update_query);

        $url = base_url('curriculum/clopomap_review/review' . '/' . $crclm_id . '/' . $term_id . '/' . $course_id);
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => $course_id,
            'sender_id' => $res2[0]['clo_owner_id'],
            'receiver_id' => $receiver_id,
            'entity_id' => '16',
            'url' => $url,
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . ' - Mapping between COs and ' . $this->lang->line('sos') . ' is sent for review.',
            'state' => '2',
            'status' => '1');
        $this->db->insert('dashboard', $dashboard_data);

        $course_state_update_query = ' UPDATE course 
									   SET state_id = 3
									   WHERE crs_id = "' . $course_id . '" 
											AND crclm_id = "' . $crclm_id . '" ';
        $course_state_update_data = $this->db->query($course_state_update_query);

        $data['url'] = $url;
        return $data;
    }

    /*
     * Function is to update the clo po mapping data once it is accepted.
     * @param curriculum id, course id and term id  is used to update the clo po mapping data.
     * returns ----.
     */

    public function accept_dashboard_data($crclm_id, $term_id, $course_id) {
        $clo_owner_id_query = ' SELECT clo_owner_id 
								FROM course_clo_owner 
								WHERE crs_id = "' . $course_id . '" 
									AND crclm_id = "' . $crclm_id . '" ';
        $clo_owner_id_data = $this->db->query($clo_owner_id_query);
        $clo_owner_id = $clo_owner_id_data->result_array();

        $curriculum_owner_details = ' SELECT crclm_owner
									  FROM curriculum 
									  WHERE crclm_id = "' . $crclm_id . '" ';
        $curriculum_owner_data = $this->db->query($curriculum_owner_details);
        $curriculum_owner = $curriculum_owner_data->result_array();

        $pgm_owner_id_query = 'SELECT c.crclm_owner 
								FROM curriculum as c 
								WHERE c.crclm_id = "' . $crclm_id . '" ';
        $pgm_owner_id_data = $this->db->query($pgm_owner_id_query);
        $pgm_owner_id_result = $pgm_owner_id_data->result_array();
        $pgm_owner_id = $pgm_owner_id_result[0]['crclm_owner'];

        $term_data_query = 'SELECT term_name 
						   FROM crclm_terms 
						   WHERE crclm_term_id = "' . $term_id . '" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
        $data['term'] = $term_result;

        $course_data_query = 'SELECT crs_title 
							   FROM course 
							   WHERE crs_id = "' . $course_id . '" ';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $data['course'] = $course_result;

        // program owner dashboard entry clearing
        $dashboard_update_query = ' UPDATE dashboard 
									SET status = 0 
									WHERE particular_id = "' . $course_id . '" 
										AND crclm_id = "' . $crclm_id . '" 
										AND entity_id = 16 ';
        $dashboard_update_data = $this->db->query($dashboard_update_query);

        // program owner notification
        $url = base_url('curriculum/clo_po/index' . '/' . $crclm_id . '/' . $term_id);
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => $term_id,
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $curriculum_owner[0]['crclm_owner'],
            'entity_id' => '14',
            'url' => $url,
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . '- Review of mapping between COs and ' . $this->lang->line('sos') . ' is accepted, mapping of COs and ' . $this->lang->line('sos') . '(termwise) is pending.',
            'state' => '7',
            'status' => '1');
        $this->db->insert('dashboard', $dashboard_data);

        // course owner notification
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => $course_id,
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $clo_owner_id[0]['clo_owner_id'],
            'entity_id' => '16',
            'url' => '#',
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . '- Review of mapping between COs and ' . $this->lang->line('sos') . ' is accepted, mapping of COs and ' . $this->lang->line('sos') . '(termwise) is pending. <br>You can still proceed with the creation of ' . $this->lang->line('entity_topic') . ' & their respective ' . $this->lang->line('entity_tlo_full') . ' ',
            'state' => '4',
            'status' => '1');
        $this->db->insert('dashboard', $dashboard_data);

        $course_state_update_query = ' UPDATE course 
									   SET state_id = 4
									   WHERE crs_id = "' . $course_id . '" 
									   AND crclm_id = "' . $crclm_id . '" ';
        $course_state_update_data = $this->db->query($course_state_update_query);

        // Course instructor details dashboard entry clearing
        $dashboard_update_query_one = ' UPDATE dashboard 
                                        SET status = 0 
                                        WHERE particular_id = "' . $course_id . '" 
                                                AND crclm_id = "' . $crclm_id . '" 
                                                AND entity_id = 4 ';
        $dashboard_update_data_one = $this->db->query($dashboard_update_query_one);

        $select_section_ids = 'SELECT course_instructor_id, section_id FROM map_courseto_course_instructor WHERE crs_id = "' . $course_id . '" ';
        $select_section_id_data = $this->db->query($select_section_ids);
        $select_section_id = $select_section_id_data->result_array();

        foreach ($select_section_id as $section) {

            $meta_data_query = ' SELECT crs.crs_title, crclm.crclm_name, term.term_name, mt.mt_details_name as section_name FROM course as crs '
                    . ' JOIN curriculum as crclm ON crclm.crclm_id = "' . $crclm_id . '" '
                    . ' JOIN crclm_terms as term ON term.crclm_term_id = "' . $term_id . '" '
                    . ' JOIN master_type_details as mt ON mt.mt_details_id = "' . $section['section_id'] . '" '
                    . ' WHERE crs.crs_id = "' . $course_id . '" ';
            $meta_data_data = $this->db->query($meta_data_query);
            $meta_data = $meta_data_data->row_array();
            $description = 'Term(Semester):- ' . $meta_data['term_name'];
            $instructor_description = $description . ', Course:- ' . $meta_data['crs_title'] . ' ' . $this->lang->line('entity_clo_singular') . ' to ' . $this->lang->line('so') . ' Mapping review is accepted, proceed with Assessment Planning (' . $this->lang->line('testI') . ' Occasions & ' . $this->lang->line('testI') . ' Question Papers) and Attainment Analysis activities for Section/Division :- ' . $meta_data['section_name'] . '.';
            $dashboard_insert_array = array(
                'crclm_id' => $crclm_id,
                'entity_id' => 4,
                'particular_id' => $course_id,
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $section['course_instructor_id'],
                'url' => base_url('assessment_attainment/cia'),
                'description' => $instructor_description,
                'state' => 1,
                'status' => 1,
            );

            $this->db->insert('dashboard', $dashboard_insert_array);
        }

        $data['url'] = $url;
        $data['receiver_id'] = $clo_owner_id[0]['clo_owner_id'];
        return $data;
    }

    /*
     * Function is to update the clo po mapping data once it is rework is done.
     * @param curriculum id, course id and term id  is used to update the clo po mapping data.
     * returns ----.
     */

    public function rework_dashboard($crclm_id, $course_id, $receiver_id, $term_id) {

        $term_data_query = 'SELECT term_name 
							   FROM crclm_terms 
							   WHERE crclm_term_id = "' . $term_id . '" ';
        $term_data = $this->db->query($term_data_query);
        $term_result = $term_data->result_array();
        $data['term'] = $term_result;

        $course_data_query = 'SELECT crs_title 
							   FROM course 
							   WHERE crs_id = "' . $course_id . '" ';
        $course_data = $this->db->query($course_data_query);
        $course_result = $course_data->result_array();
        $data['course'] = $course_result;

        $clo_owner_id_query = 'SELECT clo_owner_id 
								FROM course_clo_owner 
								WHERE crs_id = "' . $course_id . '" and crclm_id = "' . $crclm_id . '" ';
        $clo_owner_id_data = $this->db->query($clo_owner_id_query);
        $clo_owner_id_result = $clo_owner_id_data->result_array();

        $dashboard_update_query = 'UPDATE dashboard 
									SET status = 0 
									WHERE particular_id = "' . $course_id . '" 
										AND crclm_id = "' . $crclm_id . '"
										AND entity_id = 16';
        $dashboard_update = $this->db->query($dashboard_update_query);

        $url = base_url('curriculum/clopomap_review/rework' . '/' . $crclm_id . '/' . $term_id . '/' . $course_id);
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => $course_id,
            'sender_id' => $receiver_id,
            'receiver_id' => $clo_owner_id_result[0]['clo_owner_id'],
            'entity_id' => '16',
            'url' => $url,
            'description' => $term_result[0]['term_name'] . ' - ' . $course_result[0]['crs_title'] . ' - Mapping between COs and ' . $this->lang->line('sos') . ' is sent back for rework.',
            'state' => '3',
            'status' => '1',
        );
        $this->db->insert('dashboard', $dashboard_data);

        $course_state_update_query = ' UPDATE course 
									   SET state_id = 3
									   WHERE crs_id = "' . $course_id . '" 
									   AND crclm_id = "' . $crclm_id . '" ';
        $course_state_update_data = $this->db->query($course_state_update_query);

        $data['url'] = $url;
        $data['receiver_id'] = $clo_owner_id_result[0]['clo_owner_id'];
        return $data;
    }

    /*
     * Function is to save the pi & measures oncheck of check box.
     * @param curriculum id, po id, clo id, pi id course id and measure id used to save oncheck of check box.
     * returns ----.
     */

    public function oncheck_save_db($crclmid, $po_id, $clo_id, $pi, $msr, $crsid, $map_level, $term_id) {

        $map_del_query = 'DELETE FROM clo_po_map 
							WHERE po_id = "' . $po_id . '" AND clo_id = "' . $clo_id . '" ';
        $map_del_result = $this->db->query($map_del_query);

        $pi_id = sizeof($pi);

        $msr_id = sizeof($msr);
        for ($j = 0; $j < $pi_id; $j++) {

            $count = 0;
            for ($m = 0; $m < $msr_id; $m++) {
                $pi_msr_query = 'SELECT msr_id 
								 FROM measures 
								 WHERE pi_id = "' . $pi[$j] . '" AND msr_id = "' . $msr[$m] . '" ';
                $pi_msr_data = $this->db->query($pi_msr_query);
                $pi_msr_result = $pi_msr_data->result_array();

                if ($pi_msr_result) {
                    $count++;
                    $pimsr[$count] = $pi_msr_result;
                }
            }

            $po_id_count_query = ' SELECT po_id FROM clo_po_map WHERE po_id= "' . $po_id . '" AND clo_id ="' . $clo_id . '"';
            $po_id_count_data = $this->db->query($po_id_count_query);
            $po_id_count = $po_id_count_data->result_array();


            if (empty($po_id_count)) {
                for ($k = 1; $k <= $count; $k++) {
                    $add_clo_po_map = array(
                        'clo_id' => $clo_id,
                        'po_id' => $po_id,
                        'crclm_id' => $crclmid,
                        'crs_id' => $crsid,
                        'pi_id' => $pi[$j],
                        'msr_id' => $pimsr[$k][0]['msr_id'],
                        'map_level' => $map_level,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'create_date' => date('Y-m-d'));

                    $this->db->insert('clo_po_map', $add_clo_po_map);

                    $crs_po_map = array(
                        'crclm_id' => $crclmid,
                        'crclm_term_id' => $term_id,
                        'po_id' => $po_id,
                        'crs_id' => $crsid,
                        'map_level' => $map_level,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'create_date' => date('Y-m-d')
                    );

                    $this->db->insert('course_po_map', $crs_po_map);
                }
            } else {

                for ($k = 1; $k <= $count; $k++) {
                    $add_clo_po_map = array(
                        'clo_id' => $clo_id,
                        'po_id' => $po_id,
                        'crclm_id' => $crclmid,
                        'crs_id' => $crsid,
                        'pi_id' => $pi[$j],
                        'msr_id' => $pimsr[$k][0]['msr_id'],
                        'map_level' => $map_level,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'create_date' => date('Y-m-d'));

                    $this->db->insert('clo_po_map', $add_clo_po_map);

                    $crs_po_map = array(
                        'crclm_id' => $crclmid,
                        'crclm_term_id' => $term_id,
                        'po_id' => $po_id,
                        'crs_id' => $crsid,
                        'map_level' => $map_level,
                        'created_by' => $this->ion_auth->user()->row()->id,
                        'create_date' => date('Y-m-d')
                    );

                    $this->db->insert('course_po_map', $crs_po_map);
                }
            }
        }

        return true;
    }

    /*
     * Function is to load the pi & measures into popup.
     * @param po id is used to fetch the related pi and its measures.
     * returns ----.
     */

    function pi_select($po_id, $clo_id, $map_level, $crs_id, $crclm_id, $term_id) {
        $pi_measure = $this->db->SELECT('msr_id, msr_statement, pi_codes, performance_indicator.pi_id, pi_statement')
                ->JOIN('performance_indicator', 'performance_indicator.pi_id = measures.pi_id')
                ->WHERE('performance_indicator.po_id', $po_id)
                ->GET('measures')
                ->result_array();
        if (empty($pi_measure)) {
            // OE & PIs optional
            // Delete earlier entry of po_id onto table clo_po_map
            $query = ' DELETE FROM clo_po_map 
						WHERE clo_id = "' . $clo_id . '" 
						AND po_id = "' . $po_id . '" ';
            $result = $this->db->query($query);

            // Insert only po_id onto table clo_po_map
            // Insert only po_id onto table clo_po_map		
            $add_new_map = array(
                'crclm_id' => $crclm_id,
                'crclm_term_id' => $term_id,
                'po_id' => $po_id,
                'crs_id' => $crs_id,
                'map_level' => $map_level,
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d'));
            $this->db->insert('course_po_map', $add_new_map);
            //return true;

            $add_clo_po_map = array(
                'clo_id' => $clo_id,
                'po_id' => $po_id,
                'crclm_id' => $crclm_id,
                'crs_id' => $crs_id,
                'map_level' => $map_level,
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d')
            );
            $this->db->insert('clo_po_map', $add_clo_po_map);
            return false;
        } else {
            return $pi_measure;
        }
    }

    /*
     * Function is to delete the clo po mapping details on uncheck of check box.
     * @param po id and clo id is used to delete the clo po mapping details.
     * returns ----.
     */

    function unmap_db($po_id, $clo_id) {
        $map_del_query = 'DELETE FROM clo_po_map 
							WHERE po_id = "' . $po_id . '" AND clo_id = "' . $clo_id . '" ';
        $map_del_result = $this->db->query($map_del_query);
    }

    /*
     * Function is to delete the clo po mapping details.
     * @param curriculum id and course id used to delete the clo po mapping details.
     * returns ----.
     */

    function delete_all($crclm_id, $course_id) {
        return true;
        $clo_po_map_delete_query = 'DELETE FROM clo_po_map 
									WHERE crclm_id ="' . $crclm_id . '" and crs_id = "' . $course_id . '" ';
        $clo_po_map_delete_result = $this->db->query($clo_po_map_delete_query);
    }

    /*
     * Function is to update the curriculum workflow history once clo po maaping approves.
     * @param curriculum id is used to insert the workflow history data to particular curriculum.
     * returns ----.
     */

    function approve_accept_db($crclmid) {
        $clo_po_map_accept_query = ' INSERT INTO workflow_history(entity_id, crclm_id, state_id) 
									 values (16,"' . $crclmid . '",4)';
        $clo_po_map_accept_result = $this->db->query($clo_po_map_accept_query);
    }

    /*
     * Function is to update the curriculum workflow history once clo po maaping sends for rework.
     * @param curriculum id is used to insert the workflow history data to particular curriculum.
     * returns ----.
     */

    function insert_workflow_history($crclmid) {
        $clo_po_map_rework_query = 'INSERT INTO workflow_history(entity_id, crclm_id, state_id) 
						values (16,"' . $crclmid . '",3)';
        $clo_po_map_rework_result = $this->db->query($clo_po_map_rework_query);
    }

    /*
     * Function is to update the curriculum dashboard once clo po maaping approves.
     * @param curriculum id, course id and approver id is used to update the respective user dashboard.
     * returns ----.
     */

    public function approver_dashboard_data($crclm_id, $course_id, $approver_id) {
        $clo_owner_id_query = 'SELECT clo_owner_id 
						 FROM course_clo_owner 
						 WHERE crs_id = "' . $course_id . '" and crclm_id = "' . $crclm_id . '" ';
        $clo_owner_id_data = $this->db->query($clo_owner_id_query);
        $clo_owner_id_result = $clo_owner_id_data->result_array();
        $approve_url = base_url('curriculum/clo_po_approve');
        $dashboard_data = array(
            'crclm_id' => $crclm_id,
            'particular_id' => '0',
            'sender_id' => $res2[0]['clo_owner_id'],
            'receiver_id' => $approver_id,
            'entity_id' => '14',
            'url' => $approve_url,
            'description' => 'COs to ' . $this->lang->line('sos') . ' mapping is sent for approval.',
            'state' => '4');
        $this->db->insert('dashboard', $dashboard_data);
        return;
    }

    /*
     * Function is to check the state of the particular course state which indicates whether it is mapped or unmapped.
     * @param curriculum id, course id and term id is used to check the state of the course.
     * returns course state.
     */

    public function check_state($crclm_id, $course_id, $term_id) {
        $check_state_query = 'SELECT state 	
							  FROM dashboard 
							  WHERE crclm_id = "' . $crclm_id . '" and particular_id = "' . $course_id . '" and entity_id = 16 and state = 1';
        $check_state_result = $this->db->query($check_state_query)->num_rows();


        if ($check_state_result == 1) {
            $dashboard_check_state_query = 'SELECT state 
											 FROM dashboard 
											 WHERE crclm_id = "' . $crclm_id . '" and particular_id = "' . $course_id . '" and entity_id = 16 and status = 1';
            $dashboard_check_state_data = $this->db->query($dashboard_check_state_query);
            $dashboard_check_state_result = $dashboard_check_state_data->result_array();
            return $dashboard_check_state_result;
        }
    }

    /*
     * Function is to add comments to the clo po mapping.
     * @param curriculum id, course id and term id is used to add comments to clo po mapping.
     * returns ----.
     */

    public function cmnt_insert($po_id, $clo_id, $crclm_id, $crs_id, $clo_po_cmt, $cmt_status) {
        $select_query = "select entity_id from entity where entity_name='po_clo_crs'";
        $entity_id = $this->db->query($select_query);
        $entity_id_data = $entity_id->result_array();
        $entity_id1 = $entity_id_data[0]['entity_id'];

        $delete_cmt_query = 'DELETE FROM comment WHERE clo_id = "' . $clo_id . '" AND po_id = "' . $po_id . '" AND crclm_id ="' . $crclm_id . '" ';
        $delete_cmt = $this->db->query($delete_cmt_query);

        $comment_add = array(
            'entity_id' => $entity_id1,
            'actual_id' => $crs_id,
            'po_id' => $po_id,
            'clo_id' => $clo_id,
            'crclm_id' => $crclm_id,
            'cmt_statement' => $clo_po_cmt,
            'commented_by' => $this->ion_auth->user()->row()->id,
            'in_reply_to' => '0',
            'created_by' => $this->ion_auth->user()->row()->id,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
            'modified_date' => date('Y-m-d'),
            'status' => $cmt_status,
        );

        $this->db->insert($this->comment, $comment_add);
    }

    public function cmnt_update($po_id, $clo_id, $crclm_id, $status) {
        $query = "UPDATE comment set status='" . $status . "' where po_id='" . $po_id . "' and clo_id='" . $clo_id . "' and crclm_id='" . $crclm_id . "' ";
        $cmt_update = $this->db->query($query);
    }

    public function comment_fetch($po_id, $clo_id) {
        $i = 1;
        $new_line = '<br>';
        $select_query = "SELECT clo_id, po_id, crclm_id,cmt_statement, status 
						 FROM comment 
						 WHERE po_id='$po_id' 
							AND clo_id='$clo_id'";
        $comment = $this->db->query($select_query);
        $comment_data = $comment->result_array();

        return $comment_data;
    }

    public function button_state($crclm_id, $course_id, $term_id) {
        $clo_po_map_state_query = ' SELECT d.state , ws.status as state_name
									FROM dashboard AS d, workflow_state AS ws
									WHERE d.particular_id = "' . $course_id . '" 
									AND d.crclm_id = "' . $crclm_id . '" 
									AND d.entity_id = 16 
									AND d.state = ws.state_id 
									AND d.status = 1 ';
        $clo_po_map_state_data = $this->db->query($clo_po_map_state_query);
        $clo_po_map_state_result = $clo_po_map_state_data->result_array();
        return $clo_po_map_state_result;
    }

    public function clo_po_grid_status($crclm_id, $course_id, $term_id) {
        $clo_po_map_state_query = ' SELECT c.state_id , ws.status as state_name
									FROM course AS c, workflow_state AS ws
									WHERE c.crs_id = "' . $course_id . '" 
										AND c.crclm_id = "' . $crclm_id . '" 
										AND c.state_id = ws.state_id 
										AND c.status = 1 ';
        $clo_po_map_state_data = $this->db->query($clo_po_map_state_query);
        $clo_po_map_state_result = $clo_po_map_state_data->result_array();

        return $clo_po_map_state_result;
    }

    /**
     * Function to fetch saved performance indicator and measures from clo po map table and 
      performance indicator & measures table respectively
     * @parameters: curriculum id, term id, course id, course outcomes id and program outcomes id
     * @return: performance indicator and measure statements
     */
    public function modal_display_pm_model($curriculum_id, $term_id, $course_id, $clo_id, $po_id) {
        $clo_po_map_pm = 'SELECT DISTINCT cpm.pi_id, cpm.msr_id, pi.pi_id, pi.pi_statement, msr.msr_id, msr.msr_statement, msr.pi_codes
						  FROM clo_po_map AS cpm, performance_indicator AS pi, measures AS msr
						  WHERE cpm.crclm_id = "' . $curriculum_id . '"
							AND cpm.crs_id = "' . $course_id . '"
							AND cpm.clo_id = "' . $clo_id . '"
							AND cpm.po_id = "' . $po_id . '"
							AND cpm.pi_id = pi.pi_id
							AND cpm.msr_id = msr.msr_id';
        $clo_po_map_list_pm = $this->db->query($clo_po_map_pm);
        $map_list_pm = $clo_po_map_list_pm->result_array();
        $data['map_list_pm'] = $map_list_pm;

        return $data;
    }

    public function get_map_level_val($po_id, $clo_id) {
        $map_level_query = 'SELECT map_level FROM clo_po_map WHERE clo_id = "' . $clo_id . '" AND po_id = "' . $po_id . '" Group By map_level ';
        $map_level_data = $this->db->query($map_level_query);
        $map_level_result = $map_level_data->result_array();

        if (!empty($map_level_result)) {
            return $map_level_result[0]['map_level'];
        } else {
            return 0;
        }
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

    /**
     * Function to fetch Course Reviewer details user details from 
     * @parameters: curriculum id
     * @return: bos user name
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

    /* Function is used to fetch curriculum id from curriculum table
     * @parameters: curriculum id
     * @return: curriculum id
     */

    public function fetch_curriculum($curriculum_id) {
        $curriculum_id_query = 'SELECT crclm_name
								FROM curriculum
								WHERE crclm_id = "' . $curriculum_id . '"
								ORDER BY crclm_name ASC';
        $curriculum_id_data = $this->db->query($curriculum_id_query);
        $curriculum_data = $curriculum_id_data->result_array();

        return $curriculum_data;
    }

    /* Function is used to fetch the Program Owner details of OEs & PIs creation.
     * @param - curriculum id.
     * @returns- a array value of the Program Owner details.
     */

    public function fetch_programowner_user($curriculum_id) {
        $programowner_user_query = 'SELECT u.title, u.first_name, u.last_name, c.crclm_name, c.crclm_owner
									FROM users AS u, curriculum AS c
									WHERE c.crclm_id = "' . $curriculum_id . '" 
									AND c.crclm_owner = u.id ';
        $programowner_user = $this->db->query($programowner_user_query);
        $programowner_user = $programowner_user->result_array();
        return $programowner_user;
    }

// End of function fetch_programowner_user.

    /**
     * Function to fetch help details from help table
     * @return: serial number (help id), entity data and help description
     */
    public function clo_po_help() {
        $help = 'SELECT serial_no, entity_data, help_desc
				 FROM help_content 
				 WHERE entity_id = 16';
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
     * Function to fetch help related to course outcomes to program outcomes to display
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

    public function skip_review_flag_fetch() {
        return $this->db->select('skip_review')
                        ->where('entity_id', 16)
                        ->get('entity')
                        ->result_array();
    }

    /**
     * Function to update the clo statement
     * @parameters: clo id and clo statement
     * @return: 
     */
    public function update_clo_statement($clo_id, $updated_clo_statement) {
        $update_clo_statement_query = $this->db->query('UPDATE clo SET clo_statement="' . $updated_clo_statement . '" WHERE clo_id=' . $clo_id);
        return $update_clo_statement_query;
    }

//End of update_clo_statement function

    /**
     * Function to delete the clo statement
     * @parameters: clo id 
     * @return: 
     */
    public function delete_clo_statement($clo_id) {
        $delete_clo_statement_query = $this->db->query('DELETE FROM clo WHERE clo_id=' . $clo_id);
        return $delete_clo_statement_query;
    }

//End of delete_clo_statement_query function

    /**
     * Function to add Course Outcome statement
     * @parameters: curriculum_id,term_id,course_id,co_stmt
     * @return: 
     */
    public function add_more_co_statement($curriculum_id, $term_id, $course_id, $co_stmt, $clo_bloom, $clo_delivery_method) {
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $add_co_data = array(
            'clo_statement' => $co_stmt,
            'crclm_id' => $curriculum_id,
            'term_id' => $term_id,
            'crs_id' => $course_id,
            'created_by' => $created_by,
            'create_date' => $created_date
        );
        $add_co_query = $this->db->insert('clo', $add_co_data);
        $clo_id = $this->db->insert_id();
        $clo_fetch = $this->db
                ->select('clo_id')
                ->where('crs_id', $course_id)
                ->where('term_id', $term_id)
                ->where('crclm_id', $curriculum_id)
                ->get('clo')
                ->result_array();

        if (sizeof($clo_bloom)) {
            for ($j = 0; $j < sizeof($clo_bloom); $j++) {
                $clo_bloom_level_array = array(
                    'clo_id' => $clo_id,
                    'bloom_id' => $clo_bloom[$j],
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
        $i = 1;
        $clo_alias = 'CO';

        foreach ($clo_fetch as $clo_data) {
            $clo_code_update = $this->db
                    ->set('clo_code', $clo_alias . $i)
                    ->where('clo_id', $clo_data['clo_id'])
                    ->update('clo');
            $i++;
        }

        return $add_co_query;
    }

//End of add_more_co_statement function

    /**
     * Function to get Clo Bloom Level & Delivery Methods Info
     * @parameters: clo_id
     * @return: 
     */
    public function getCloBloomDeliveryInfo($crclm_id, $clo_id) {
        $mapped_bloom_level_query = 'SELECT b.bloom_id, m.map_clo_bloom_level_id, b.level, b.bloom_actionverbs
										FROM bloom_level b LEFT OUTER JOIN
										map_clo_bloom_level m
										ON b.bloom_id = m.bloom_id and
										m.clo_id = ' . $clo_id . '';
        $mapped_bloom_level = $this->db->query($mapped_bloom_level_query);
        $mapped_bloom_level_data = $mapped_bloom_level->result_array();
        $clo_result['mapped_bloom_level_data'] = $mapped_bloom_level_data;

        $mapped_delivery_method_query = 'SELECT d.crclm_dm_id, m.map_clo_delivery_method_id, d.delivery_mtd_name
											FROM map_crclm_deliverymethod d 
											LEFT OUTER JOIN map_clo_delivery_method m ON d.crclm_dm_id = m.delivery_method_id 
											AND m.clo_id = ' . $clo_id . '
											WHERE d.crclm_id = ' . $crclm_id . ' 
											ORDER BY d.delivery_mtd_name ASC';
        $mapped_delivery_method = $this->db->query($mapped_delivery_method_query);
        $mapped_delivery_method_data = $mapped_delivery_method->result_array();
        $clo_result['mapped_delivery_method_data'] = $mapped_delivery_method_data;

        return $clo_result;
    }

//End of getCloBloomDeliveryInfo function

    /**
     * Function to update existing course learning objective statement
     * @parameters: course learning objective id, course learning objective statement and course id
     * @return: boolean
     */
    public function clo_update($clo_statement, $clo_id, $course_id, $clo_bloom, $clo_delivery_mtd) {
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        $update_query = 'UPDATE clo 
						 SET clo_statement = "' . $this->db->escape_str($clo_statement) . '", modified_by = "' . $modified_by . '", modify_date = "' . $modified_date . '"
						 WHERE crs_id = "' . $course_id . '" AND clo_id = "' . $clo_id . '"';
        $update_data = $this->db->query($update_query);

        $clo_bloom_cnt = sizeof($clo_bloom);
        $clo_bloom_level_query = $this->db->query('SELECT COUNT(m.map_clo_bloom_level_id) as clo_bloom_count
													FROM map_clo_bloom_level m
													WHERE m.clo_id = ' . $clo_id);
        $clo_bloom_level_data = $clo_bloom_level_query->row_array();
        $mapped_clo_bloom_level_query = $this->db->query('SELECT * FROM map_clo_bloom_level m
															WHERE m.clo_id =' . $clo_id);
        $mapped_clo_bloom_level = $mapped_clo_bloom_level_query->result_array();
        $bloom_levels = '';
        $i = 0;
        $modified_by = $this->ion_auth->user()->row()->id;
        $modified_date = date('Y-m-d');
        if ($clo_bloom_cnt == $clo_bloom_level_data['clo_bloom_count']) {
            foreach ($mapped_clo_bloom_level as $clo_bloom_level) {
                $mapped_clo_bloom_level_id = $clo_bloom_level['map_clo_bloom_level_id'];
                $bloom_id = $clo_bloom[$i];
                $map_clo_bloom_level_array = array(
                    'bloom_id' => $bloom_id,
                    'modified_by' => $modified_by,
                    'modified_date' => $modified_date
                );
                $this->db->where('map_clo_bloom_level_id', $mapped_clo_bloom_level_id);
                $this->db->update('map_clo_bloom_level', $map_clo_bloom_level_array);
                $i++;
            }
        } else if ($clo_bloom_cnt > $clo_bloom_level_data['clo_bloom_count']) {
            $created_by = $this->ion_auth->user()->row()->id;
            $created_date = date('Y-m-d');
            foreach ($mapped_clo_bloom_level as $clo_bloom_level) {
                $mapped_clo_bloom_level_id = $clo_bloom_level['map_clo_bloom_level_id'];
                $bloom_id = $clo_bloom[$i];
                $map_clo_bloom_level_array = array(
                    'bloom_id' => $bloom_id,
                    'modified_by' => $modified_by,
                    'modified_date' => $modified_date
                );
                $this->db->where('map_clo_bloom_level_id', $mapped_clo_bloom_level_id);
                $this->db->update('map_clo_bloom_level', $map_clo_bloom_level_array);
                $i++;
            }for ($j = $i; $j < $clo_bloom_cnt; $j++) {
                $bloom_id = $clo_bloom[$j];
                $map_clo_bloom_level_array = array(
                    'clo_id' => $clo_id,
                    'bloom_id' => $bloom_id,
                    'created_by' => $created_by,
                    'created_date' => $created_date,
                );
                $this->db->insert('map_clo_bloom_level', $map_clo_bloom_level_array);
            }
        } else if ($clo_bloom_cnt < $clo_bloom_level_data['clo_bloom_count']) {
            $delete_clo_bloom = implode(",", $clo_bloom);
            if ($delete_clo_bloom != '') {
                $delete_clo_bloom_query = $this->db->query('DELETE FROM map_clo_bloom_level
												WHERE clo_id = ' . $clo_id . '
												AND bloom_id IN (
												  SELECT b.bloom_id
												  FROM bloom_level b
												  WHERE b.bloom_id NOT IN (' . $delete_clo_bloom . ')
												)');
            } else {
                $delete_clo_bloom_query = $this->db->query('DELETE FROM map_clo_bloom_level
											WHERE clo_id = ' . $clo_id . '');
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
            }for ($k = $j; $k < $clo_delivery_method_cnt; $k++) {
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
												  SELECT d.delivery_mtd_id
												  FROM delivery_method d
												  WHERE d.delivery_mtd_id NOT IN (' . $delete_clo_delivery_method . ')
												)');
            } else {
                $delete_clo_delivery_method_query = $this->db->query('DELETE FROM map_clo_delivery_method
											WHERE clo_id = ' . $clo_id . '');
            }
        }
        return $update_data;
    }

    public function save_notes_in_database($curriculum_id, $text, $term_id, $course_id) {
        $query = ' SELECT COUNT(notes_id) 
						FROM notes
						WHERE crclm_id = "' . $curriculum_id . '" 
						AND particular_id="' . $course_id . '"';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        $temp = $result[0]['COUNT(notes_id)'];
        $query_curriculum_id = 'SELECT notes_id, crclm_id 
									FROM notes
									WHERE crclm_id = "' . $curriculum_id . '"
									AND particular_id="' . $course_id . '"';
        $result_curriculum_id = $this->db->query($query_curriculum_id);
        $result_curriculum_id = $result_curriculum_id->result_array();
        $success = 0;
        if ($temp != 0) {
            if ($result_curriculum_id[0]['crclm_id'] == $curriculum_id) {
                $query = 'UPDATE notes 
							SET notes = "' . $this->db->escape_str($text) . '", 
								term_id="' . $term_id . '",
								particular_id="' . $course_id . '",
								entity_id = 16 
							WHERE crclm_id = "' . $curriculum_id . '"
							AND particular_id="' . $course_id . '"';
                $query = $this->db->query($query);
                $success = 1;
                //break;
            }
        }

        if ($temp == 0 || $success == 0) {
            $data = array(
                'notes' => $text,
                'term_id' => $term_id,
                'crclm_id' => $curriculum_id,
                'particular_id' => $course_id,
                'entity_id' => 16
            );

            $this->db->insert('notes', $data);
        }
        return true;
    }

    /* Function to fetch notes (text) of a particular curriculum from the notes table
     * @parameters: curriculum id
     * @return: JSON_ENCODE object.
     */

    public function text_details($curriculum_id, $term_id, $course_id) {
        $notes = 'SELECT notes 
					FROM notes 
					WHERE crclm_id = "' . $curriculum_id . '" 
					AND entity_id = 16 
					AND term_id="' . $term_id . '"
					AND particular_id="' . $course_id . '"';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();

        if (!empty($notes[0]['notes'])) {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode($notes[0]['notes']));
        } else {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode(""));
        }
    }

    /* Function to fetch course mode of course
     * @parameters:  course_id, term_id, crclm_id
     * @return: crs_mode.
     */

    public function fetch_course_mode($course_id, $term_id, $crclm_id) {
        $data1 = 'select crs_mode from course where crs_id	="' . $course_id . '" and crclm_id="' . $crclm_id . '" and crclm_term_id="' . $term_id . '" ';
        $data2 = $this->db->query($data1);
        $data = $data2->result_array();
        return $data[0]['crs_mode'];
        //
    }

    public function save_justification($data) {
        $result = $this->db->query('update clo_po_map set justification ="' . $this->db->escape($data['justification']) . '" where  po_id="' . $data['po_id'] . '" and clo_id="' . $data['clo_id'] . '" and crclm_id ="' . $data['crclm_id'] . '" ');
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    public function remap_co_po($crclm_id, $crs_id, $term_id) {

//	$query = $this->db->query('delete from clo_po_map where crs_id = "' . $crs_id . '" and crclm_id="' . $crclm_id . '" ');
//	if ($query) 
        {
            $this->db->query('update dashboard set state=3 where crclm_id = "' . $crclm_id . '" and particular_id="' . $crs_id . '" and entity_id=16 ');
            $this->db->query('update course set state_id=3 where crclm_id = "' . $crclm_id . '" and crs_id="' . $crs_id . '"');
        }
    }

    /*
     * Function is to fetch the CLO PO Mapping details.
     * @param - curriculum id, term id and course id is used to fetch the particular course clo to po mapping details.
     * returns the clo to po mapping details.
     */

    public function clomap_details_new($crclm_id, $course_id, $term_id) {

        $data['crclm_id'] = $crclm_id;
        $data['course_id'] = $course_id;
        $course_query = 'SELECT  crs_title 
			 FROM course 
			 WHERE crs_id = "' . $course_id . '" ';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();
        $data['course_list'] = $course_list_data;

        $clo_data_query = 'SELECT clo_id, clo_code, crclm_id, clo_statement, term_id 
			   FROM clo 
			   WHERE crs_id = "' . $course_id . '" 
			   ORDER BY clo_code ASC';
        $clo_list = $this->db->query($clo_data_query);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;


        $po_data_query = 'SELECT po_id,pso_flag,crclm_id, po_statement, po_reference 
			  FROM po 
			  WHERE crclm_id = "' . $crclm_id . '"
			  ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po_data_query);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

        $clo_po_map_query = 'SELECT DISTINCT clo_id, po_id, pi_id , msr_id , map_level,justification,clo_po_id, crs_id, create_date
		             FROM clo_po_map 
			     WHERE crclm_id = "' . $crclm_id . '" AND crs_id = "' . $course_id . '" ';
        $clo_po_map_list = $this->db->query($clo_po_map_query);
        $clo_po_map_result = $clo_po_map_list->result_array();
        $data['map_list'] = $clo_po_map_result;

        $select_comment_data_query = 'SELECT  clo_id, po_id, crclm_id, cmt_statement, status 
				      FROM comment 
				      WHERE crclm_id = "' . $crclm_id . '" ';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();
        $data['comment'] = $comment_data_result;

        //extra function and it is used to display the comments.
        $select_query = 'SELECT clo_id, po_id, crclm_id,cmt_statement, status 
			 FROM comment WHERE entity_id = 16 AND crclm_id = "' . $crclm_id . '" ';
        $comment = $this->db->query($select_query);
        $comment_data_result = $comment->result_array();
        $data['comment_data'] = $comment_data_result;


        $clo_po_map_state_query = ' SELECT state 
				    FROM dashboard 
				    WHERE particular_id = "' . $course_id . '" and crclm_id = "' . $crclm_id . '" and entity_id = 16 and status = 1';
        $clo_po_map_state_data = $this->db->query($clo_po_map_state_query);
        $clo_po_map_state_result = $clo_po_map_state_data->result_array();

        $data['map_level'] = $this->db->select('map_level_name,map_level_short_form,map_level,status')
                ->where('status', 1)
                ->get('map_level_weightage')
                ->result_array();
        $oe_pi_flag_query = 'select * from curriculum where crclm_id="' . $crclm_id . '"';
        $oe_pi_flag_data = $this->db->query($oe_pi_flag_query);
        $oe_pi_flag_result = $oe_pi_flag_data->result_array();

        $data['oe_pi_flag'] = $oe_pi_flag_result;
        if (!empty($clo_po_map_state_result)) {
            $data['map_state'] = $clo_po_map_state_result;
        } else {
            $data['map_state'] = array(array('state' => 0));
        }

        $query = $this->db->query('select indv_mapping_justify_flag from organisation');
        $data['indv_mappig_just'] = $query->result_array();

        return $data;
    }

}

?>  