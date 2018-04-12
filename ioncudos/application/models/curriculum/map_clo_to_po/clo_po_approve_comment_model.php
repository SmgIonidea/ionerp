<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
					will display related course. For each course related CO's and PO's
					are displayed for Board of Studies (BOS) member.
					Write comments if required.
					Send for approve on completion or rework for any change.

 * Created		:	June 12th, 2013

 * Author		:	

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clo_po_approve_comment_model extends CI_Model {
    public $clo_po_map = "clo_po_map";
    public $comment = "comment";

	/**
	 * Function to fetch details of the approver from the course clo approver table, state from dashboard table
	   curriculum details from curriculum table and term details from term table
	 * @parameters: curriculum id, user id and term id
	 * @return: approver id, state, curriculum name and term name
	 */
    function clo_po($user_id, $curriculum_id, $term_id) {
        //fetch curriculum details
		$curriculum_list = 'SELECT crclm_id, crclm_name 
							FROM curriculum 
							WHERE crclm_id = "' . $curriculum_id . '"';
		$curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();
		
		//fetch term details
		$term_list = 'SELECT term_name 
					  FROM crclm_terms
					  WHERE crclm_term_id = "' . $term_id . '"';
		$term_list = $this->db->query($term_list);
        $term_list = $term_list->result_array();
		
		//fetching BOS member
        $approver_list = 'SELECT approver_id 
						  FROM `course_clo_approver` 
						  WHERE crclm_id = "' . $curriculum_id . '"';
        $approver_list = $this->db->query($approver_list);
        $approver_list = $approver_list->result_array();

        //state from dashboard
        $dashboard_query = 'SELECT state 
							FROM dashboard 
							WHERE entity_id = 14 
								AND status = 1 
								AND particular_id = "' . $term_id . '"';
        $dashboard_result = $this->db->query($dashboard_query);
        $dashboard_state = $dashboard_result->result_array();

        $curriculum_data['curriculum_list'] = $curriculum_list;
        $curriculum_data['term_list'] = $term_list;
        $curriculum_data['approver_list'] = $approver_list;
        $curriculum_data['dashboard_result'] = $dashboard_state;
		
		if($curriculum_data['dashboard_result']) {
			return $curriculum_data;
		} else ;
    }

	/**
	 * Function to fetch term details from curriculum term table
	 * @parameters: curriculum id
	 * @return: term id and term name
	 */
    public function clo_po_select($curriculum_id) {
        $term_name = 'SELECT term_name, crclm_term_id 
					  FROM crclm_terms 
					  WHERE crclm_id = "' . $curriculum_id . '"';
        $term_name_result = $this->db->query($term_name);
        $term_name_result = $term_name_result->result_array();
        $term_data['term_name_result'] = $term_name_result;

        return $term_data;
    }

	/**
	 * Function to fetch course details from course table, course outcome from 
	   course outcome table, program outcome details from program outcome table,
	   course outcome to program outcome details from course outcome to 
	   program outcome map table, comments from comment table and state from dashboard table
	 * @parameters: curriculum id, term id
	 * @return: curriculum id, course id, course title, course code, course outcome id,
				course id, course outcome statements, program outcome id, program outcome
				statements, course outcome to program outcome id, comments and state
	 */
    public function clo_details($curriculum_id, $term_id) {
        $data['crclm_id'] = $curriculum_id;
		
		//course details
		$course_query = 'SELECT d.state, c.crclm_id, c.crs_id, c.crs_title, c.crs_code  
						 FROM dashboard AS d, course AS c
						 WHERE d.entity_id = 16 
							AND d.crclm_id = "' . $curriculum_id . '"
							AND d.particular_id = c.crs_id
							AND c.crclm_term_id = "' . $term_id . '" 
							AND d.state >= 4
							AND d.status = 1
							ORDER BY c.crs_title ASC';
        $course_list = $this->db->query($course_query);
        $course_list_data = $course_list->result_array();
        $data['course_list'] = $course_list_data;
			
		//course outcome details
        $clo = 'SELECT clo_id, crs_id, clo_statement 
				FROM clo 
				WHERE term_id = "' . $term_id . '"';
        $clo_list = $this->db->query($clo);
        $clo_list = $clo_list->result_array();
        $data['clo_list'] = $clo_list;

		//program outcome details
        $po = 'SELECT po_id, crclm_id, po_statement, po_reference 
			   FROM po 
			   WHERE crclm_id = "' . $curriculum_id . '"
			   ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;

		//mapped course outcome to program outcome details
        $map = 'SELECT DISTINCT clo_id, po_id, crclm_id, map_level
				FROM clo_po_map 
				WHERE crclm_id = "' . $curriculum_id . '"';
        $map_list = $this->db->query($map);
        $map_list = $map_list->result_array();
        $data['map_list'] = $map_list;

		//comments
        $select_query = 'SELECT clo_id, po_id, crclm_id, cmt_statement 
						 FROM comment 
						 WHERE crclm_id = "' . $curriculum_id . '"';
        $comment = $this->db->query($select_query);
        $comment_data = $comment->result_array();
        $data['comment'] = $comment_data;

		//dashboard details
        $dashboard_state_details = 'SELECT state 
							FROM dashboard 
							WHERE entity_id = 14 
								AND crclm_id = "' . $curriculum_id . '" 
								AND particular_id = "' . $term_id . '" 
								AND status = 1';
        $dashboard_state_data = $this->db->query($dashboard_state_details);
        $dashboard_state = $dashboard_state_data->result_array();
        $data['dashboard_state_result'] = $dashboard_state;
		
		//to fetch course id's
		$course_query_ids = 'SELECT c.crs_id
						 FROM dashboard AS d, course AS c
						 WHERE d.entity_id = 16 
							AND d.crclm_id = "' . $curriculum_id . '"
							AND d.particular_id = c.crs_id
							AND c.crclm_term_id = "' . $term_id . '" 
							AND d.state >= 5
							AND d.status = 1';
        $course_query_ids_data = $this->db->query($course_query_ids);
        $course_ids = $course_query_ids_data->result_array();
        $course_size = sizeof($course_ids);
			
		$course_sent_for_approval = 0;
		//course count which are in approval pending
		for($i = 0; $i < $course_size; $i++) {
			$course_count_details = 'SELECT crclm_id
									 FROM dashboard 
									 WHERE particular_id = "'. $course_ids[$i]['crs_id'] . '" 
										AND crclm_id = "' . $curriculum_id . '"
										AND status = 1 
										AND state = 5'; 
			$course_count_data = $this->db->query($course_count_details);
			$course_count = $course_count_data->result_array();
			$data['course_count'] = $course_count;
			
			if ($data['course_count']) {
				$course_sent_for_approval++;
			}
		}
		
		$data['course_sent_for_approval'] = $course_sent_for_approval;
        return $data;
    }

	/**
	 * Function to save the mapped values - performance indicators and its corresponding measures
	 into course outcome to program outcome map table
	 * @parameters: curriculum id, program outcome id, course outcome id, performance
					indicator id and measures
	 * @return: boolean
	 */
    public function oncheck_save_to_database($curriculum_id, $po_id, $clo_id, $pi, $measure) {
        $measure_id_count = sizeof($measure);

        for ($k = 0; $k < $measure_id_count; $k++) {
            $add_clo_po_map = array(
                'clo_id' => $clo_id,
                'po_id' => $po_id,
                'crclm_id' => $curriculum_id,
                'pi_id' => $pi,
                'msr_id' => $measure[$k],
                'create_date' => time(),
                'modify_date' => time()
            );
            $this->db->insert($this->clo_po_map, $add_clo_po_map);
        }
    }

	/**
	 * Function to fetch performance indicator statements and its corresponding measures
	 * @parameters: program outcome id
	 * @return: measure id, measure statement, performance indicator id and performance 
				indicators statements
	 */
    function pi_select($po_id) {
        $pi_measure = $this->db->SELECT('msr_id, msr_statement, performance_indicator.pi_id, pi_statement')
							   ->JOIN('performance_indicator', 'performance_indicator.pi_id = measures.pi_id')
							   ->WHERE('performance_indicator.po_id', $po_id)
							   ->GET('measures')
							   ->result_array();

        return $pi_measure;
    }

	/**
	 * Function to fetch entity details from entity table and to insert comment(s) to comment table
	 * @parameters: program outcome id, course outcome id, curriculum id, course outcome to program outcome comment
	 * @return: boolean
	 */
    public function insert_comment($po_id, $clo_id, $curriculum_id, $clo_po_comment) {
	
        /* $select_query = 'SELECT entity_id 
						 FROM entity 
						 WHERE entity_name = "po_clo"';
        $entity_id = $this->db->query($select_query);
        $entity_id_data = $entity_id->result_array();
        $entity_id = $entity_id_data[0]['entity_id']; */
        $entity_id = 14;

		$delete_cmt_query = 'DELETE FROM comment WHERE clo_id = "'.$clo_id.'" AND po_id = "'.$po_id.'" AND crclm_id ="'.$curriculum_id.'"';
		$delete_cmt = $this->db->query($delete_cmt_query);
		
        $comment_add = array(
            'entity_id' => $entity_id,
            'actual_id' => '',
            'po_id' => $po_id,
            'clo_id' => $clo_id,
            'crclm_id' => $curriculum_id,
            'cmt_statement' => $clo_po_comment,
            'commented_by' => '',
            'in_reply_to' => '',
            'created_by' => '',
            'modified_by' => '',
            'created_date' => time(),
            'modified_date' => time(),
        );

        $this->db->insert($this->comment, $comment_add);
		return true;
    }

	/**
	 * Function to fetch comment(s) from comment table
	 * @parameters: program outcome id and course outcome id
	 * @return: comment(s)
	 */
    public function comment_fetch($po_id, $clo_id) {
        $new_line = '<br>';
        $select_query = 'SELECT clo_id, po_id, crclm_id, cmt_statement 
						 FROM comment 
						 WHERE po_id = "' . $po_id . '" and clo_id = "' . $clo_id . '"';
        $comment = $this->db->query($select_query);
        $comment_data = $comment->result_array();

        return $comment_data;
    }
	
	/**
	 * Function to fetch saved performance indicator and measures from clo po map table and 
	   performance indicator & measures table respectively
	 * @parameters: curriculum id, term id, course id, course outcome id and program outcomes id
	 * @return: performance indicator and measure statements
	 */
	public function modal_display_pm_model($curriculum_id, $term_id, $course_id, $clo_id, $po_id) {
		$clo_po_map_pm = 'SELECT DISTINCT cpm.pi_id, cpm.msr_id, pi.pi_id, pi.pi_statement, msr.msr_id, msr.msr_statement, msr.pi_codes
						  FROM clo_po_map AS cpm, performance_indicator AS pi, measures AS msr
						  WHERE cpm.crclm_id = "'.$curriculum_id.'"
							AND cpm.crs_id = "'.$course_id.'"
							AND cpm.clo_id = "'.$clo_id.'"
							AND cpm.po_id = "'.$po_id.'"
							AND cpm.pi_id = pi.pi_id
							AND cpm.msr_id = msr.msr_id';
        $clo_po_map_list_pm = $this->db->query($clo_po_map_pm);
        $map_list_pm = $clo_po_map_list_pm->result_array();
        $data['map_list_pm'] = $map_list_pm;
		
		return $data;
	}

	/**
	 * Function to fetch course outcome to program outcome owner details from course clo
	   owner table, to fetch course details from course table, to update dashboard table and to fetch
	   approver details and user details from course clo approver table to send mail on approval
	 * @parameters: curriculum id and term id
	 * @return: course outcome owner id, course id, course title, approver id and user name
	 */
    public function bos_approval_accept($curriculum_id, $term_id) {
		$select_user_id = 'SELECT crclm_owner 
						   FROM curriculum 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $select_user_id = $this->db->query($select_user_id);
		$select_user_id = $select_user_id->result_array();
        $receiver_id = $select_user_id[0]['crclm_owner'];
		
		$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "'.$term_id.'"';
        $term_name_details = $this->db->query($term_name_data);
		$term_name_result = $term_name_details->result_array();
		$term_name = $term_name_result[0]['term_name'];
		
        $course_title = 'SELECT crs_id, crs_title 
						 FROM course 
						 WHERE crclm_id = "'.$curriculum_id.'"
							AND crclm_term_id = "'.$term_id.'"
							AND status = 1
							AND state_id = 5
							ORDER BY crs_title ASC';
        $course_query = $this->db->query($course_title);
        $course_title_result = $course_query->result_array();
        $count = $course_query->num_rows();
		for ($i = 0; $i < $count; $i++) {
            $course_reviewer_name = 'SELECT c.clo_owner_id, u.username, u.id
									 FROM course_clo_owner AS c, users AS u 
									 WHERE c.crs_id = "' . $course_title_result[$i]['crs_id'] . '" 
										AND c.clo_owner_id = u.id';
            $course_reviewer_name_result = $this->db->query($course_reviewer_name);
            $course_reviewer[$i] = $course_reviewer_name_result->result_array();
		}
		
		$update_query = 'UPDATE dashboard 
						 SET status = 0 
						 WHERE particular_id = "' . $term_id . '" 
							AND crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 14 
							AND status = 1';
        $update_data = $this->db->query($update_query);
		
		
		// course owner notification
        for ($i = 0; $i < $count; $i++) {
			$update_query = 'UPDATE dashboard 
							 SET status = 0 
							 WHERE entity_id 
								IN (4,11,14,16)
								AND crclm_id = "' . $curriculum_id . '" 
								AND particular_id = "' . $course_title_result[$i]['crs_id'] . '"  
								AND status = 1';
			$update_data = $this->db->query($update_query);
			
			$course_update_query = 'UPDATE course 
									SET state_id = 7
									WHERE crs_id = "' . $course_title_result[$i]['crs_id'] . '" 
									AND crclm_id = "' . $curriculum_id . '" ';
			$course_update_data = $this->db->query($course_update_query);
		
			
            $url = base_url('curriculum/topicadd/index/' . $curriculum_id . '/' . $term_id . '/' . $course_title_result[$i]['crs_id']);
            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $course_title_result[$i]['crs_id'],
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $course_reviewer[$i][0]['clo_owner_id'],
                'entity_id' => '10',
                'url' => $url,
                'description' => $term_name . ' - ' . $course_title_result[$i]['crs_title'] . ' - Mapping between COs and '.$this->lang->line('sos').' is approved, proceed to create topics.',
                'state' => '1',
                'status' => '1',
            );

            $this->db->insert('dashboard', $dashboard_data);
			
        }
		
		//program owner notification after bos term-wise approval
		$program_owner_dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $term_id,
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'entity_id' => '14',
                'url' => '#',
                'description' => $term_name . ' - COs to '.$this->lang->line('sos').' Termwise Mapping is Approved.',
                'state' => '7',
                'status' => '1',
            );

        $this->db->insert('dashboard', $program_owner_dashboard_data);
		
		//program owner notification
		$program_owner_dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'particular_id' => $term_id,
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'entity_id' => '10',
                'url' => '#',
                'description' => $term_name . ' - Mapping between COs and '.$this->lang->line('sos').' (termwise) is approved, now each Course Owners will be able to add topics & its respective.'.$this->lang->line('entity_tlo_singular'),
                'state' => '1',
                'status' => '1',
            );

        $this->db->insert('dashboard', $program_owner_dashboard_data);

        //email
        $clo_po_receiver_name = 'SELECT DISTINCT o.clo_owner_id, u.username, o.crs_id, c.crs_id
								 FROM course_clo_owner AS o 
								 JOIN users AS u ON o.clo_owner_id = u.id
								 JOIN course AS c ON o.crs_id = c.crs_id
								 WHERE o.crclm_term_id = "' . $term_id . '"
									AND c.status = 1';
        $clo_po_receiver_name_result = $this->db->query($clo_po_receiver_name);
        $clo_po_receiver_name_result = $clo_po_receiver_name_result->result_array();
        $count = sizeof($clo_po_receiver_name_result);
		$email_data = array();
        for ($i = 0; $i < $count; $i++) {
            $url = base_url('curriculum/topicadd/index/' . $curriculum_id . '/' . $term_id . '/' . $course_title_result[$i]['crs_id']);
            $email_data[$i] = array(
                'crclm_id' => $curriculum_id,
                'receiver_id' => $clo_po_receiver_name_result[$i]['clo_owner_id'],
                'entity_id' => '10',
                'url' => $url,
                'state' => '1',
            	'term' => $term_name,
            	'course' => $course_title_result[$i]['crs_title']
            );
        }
		
        return $email_data;
    }
	
	/**
	 * Function to fetch course details from course table, course outcome owner details from 
	   course clo owner table, to update dashboard table, to fetch approver details from course clo approver 
	   table to send mail on rework
	 * @parameters: curriculum id and term id
	 * @return: course id, course title, course outcome owner, username and approver id
	 */
    public function dashboard_rework($curriculum_id, $term_id, $crs_id) {
        $select_user_id = 'SELECT crclm_owner 
						   FROM curriculum 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $select_user_id = $this->db->query($select_user_id);
		$select_user_id = $select_user_id->result_array();
        $receiver_id = $select_user_id[0]['crclm_owner'];
		
		$course_query = 'SELECT crs_id, crs_title
						 FROM course 
						 WHERE crclm_id = "' . $curriculum_id . '" 
							AND crclm_term_id = "' . $term_id . '" 
							AND crs_id = "' . $crs_id . '"';
        $course_query_result = $this->db->query($course_query);
        $course_query_result = $course_query_result->result_array();
		
		$course_reviewer_name = 'SELECT c.clo_owner_id, u.username 
								 FROM course_clo_owner AS c, users AS u 
								 WHERE c.crs_id = "' . $crs_id . '" 
									AND c.clo_owner_id = u.id';
		$course_reviewer_name_result = $this->db->query($course_reviewer_name);
		$course_reviewer = $course_reviewer_name_result->result_array();
	
		$update_query = 'UPDATE dashboard 
						 SET status = 0
						 WHERE particular_id = "' . $crs_id . '" 
							AND crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 16';
		$update_data = $this->db->query($update_query);
		
		//course table updation
		$course_update_query = 'UPDATE course 
								 SET state_id = 6
								 WHERE crs_id = "' . $crs_id . '" 
									AND crclm_id = "' . $curriculum_id . '" ';
		$course_update_data = $this->db->query($course_update_query);
		
		$term_name_data = 'SELECT term_name
						   FROM crclm_terms
						   WHERE crclm_term_id = "' . $term_id . '"';
        $term_name_details = $this->db->query($term_name_data);
		$term_name_result = $term_name_details->result_array();
		$term_name = $term_name_result[0]['term_name'];
		
		//program owner notification
		$dashboard_data = array(
							'crclm_id' => $curriculum_id,
							'particular_id' => $crs_id,
							'sender_id' => $this->ion_auth->user()->row()->id,
							'receiver_id' => $receiver_id,
							'entity_id' => '14',
							'url' => '#',
							'description' => $term_name . ' - ' . $course_query_result[0]['crs_title'] . ' - COs to '.$this->lang->line('sos').' mapping sent back for approval Rework.',
							'state' => '6',
							'status' => '1',
						);
			
		$this->db->insert('dashboard', $dashboard_data);
		
		//course owner notification
		$url = base_url('curriculum/clopomap_review/rework' . '/' . $curriculum_id . '/' . $term_id . '/' . $crs_id);
		$dashboard_data = array(
							'crclm_id' => $curriculum_id,
							'particular_id' => $crs_id,
							'sender_id' => $this->ion_auth->user()->row()->id,
							'receiver_id' => $course_reviewer[0]['clo_owner_id'],
							'entity_id' => '16',
							'url' => $url,
							'description' => $term_name . ' - ' . $course_query_result[0]['crs_title'].' - COs to '.$this->lang->line('sos').' mapping sent back for rework.',
							'state' => '6',
							'status' => '1',
						);
			
		$this->db->insert('dashboard', $dashboard_data);
		
		//to fetch BOS approver id
		$clo_po_approver_name_details = 'SELECT c.approver_id, u.username 
										 FROM course_clo_approver AS c, users AS u 
										 WHERE c.crclm_id = "' . $curriculum_id . '" 
											AND c.approver_id = u.id';
        $clo_po_approver_name_data = $this->db->query($clo_po_approver_name_details);
        $clo_po_approver_name = $clo_po_approver_name_data->result_array();
        $approver_id = $clo_po_approver_name[0]['approver_id'];
		
		//BOS status (on approval rework state = 6) updation for program owner
		$bos_update_query = 'UPDATE dashboard 
							 SET state = 6,
							 description = "'.$term_name.' - BOS termwise approval is pending due as Course(s) is/are sent back for rework."
							 WHERE crclm_id = "' . $curriculum_id . '" 
								AND particular_id = "' . $term_id . '" 
								AND receiver_id = "' . $approver_id . '"
								AND entity_id = 14';
		$bos_update_data = $this->db->query($bos_update_query);
		
		$dashboard_data = array(
			'crclm_id' => $curriculum_id,
			'receiver_id' => $course_reviewer[0]['clo_owner_id'],
			'entity_id' => '16',
			'url' => $url,
			'state' => '6',
			'term' => $term_name,
		);
		
		return ($dashboard_data);
    }

	/**
     * Function to fetch user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
	 public function crs_owner_details($curriculum_id, $crs_id) {
		$crs_owner_user_query = 'SELECT u.title, u.first_name, u.last_name
								 FROM  course_clo_owner AS o, users AS u, curriculum AS c
								 WHERE o.crs_id = "' . $crs_id . '" 
									AND o.clo_owner_id = u.id 
									AND c.crclm_id = "' . $curriculum_id . '"';
		$crs_owner_user = $this->db->query($crs_owner_user_query);
		$crs_owner = $crs_owner_user->result_array();
		
		return $crs_owner;
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
}

/*
 * End of file clo_po_approve_comment_model.php
 * Location: .curriculum/map_clo_to_po/clo_po_approve_comment_model.php 
 */
?>