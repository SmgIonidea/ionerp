<?php

/**
 * Description	:	To display, add and edit Student Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 21-10-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_model extends CI_Model {

    public $po_table = "po";
    public $curriculum_table = "curriculum";
    public $po_type_table = "po_type";

    /**
     * Function to fetch curriculum details from curriculum table
     * @return: curriculum details
     */
    function list_curriculum() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, dashboard AS d
								WHERE d.crclm_id = c.crclm_id
									AND d.entity_id = 6
									AND d.status = 1
									AND c.status = 1
									ORDER BY c.crclm_name ASC';
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE p.dept_id = "' . $dept_id . '"
									AND d.crclm_id = c.crclm_id
									AND p.pgm_id = c.pgm_id
									AND d.entity_id = 6
									AND d.status = 1
									AND c.status = 1
									ORDER BY c.crclm_name ASC';
        }

        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        return $curriculum_list;
    }

    /**
     * Function to add new program outcome statement
     * @parameters: curriculum id, program outcome statement, program outcome type
     * @return: boolean
     */
    public function add_po($curriculum_id, $po_name, $po_statement, $po_types, $ga_data, $pso_cb, $po_state) {
        $this->db->trans_start();
        $kmax = sizeof($po_statement);
        if ($po_state == 7) {
            $po_state_id = 7;
        } else {
            $po_state_id = 1;
        }
        for ($k = 0; $k < $kmax; $k++) {
            $po_data = array(
                'crclm_id' => $curriculum_id,
                'po_reference' => $po_name[$k],
                'pso_flag' => $pso_cb[$k],
                'po_statement' => $po_statement[$k],
                'po_type_id' => $po_types[$k],
                'po_minthreshhold'=>50,
                'po_studentthreshhold' => 50,
                'state_id' => $po_state_id,
                'created_by' => $this->ion_auth->user()->row()->id,
                'create_date' => date('Y-m-d')
            );



            $tst = $this->db->insert($this->po_table, $po_data);
            $insert_id = $this->db->insert_id();

            $ga_size = count($ga_data[$k][0]);

            for ($ga = 0; $ga < $ga_size; $ga++) {
                $ga_insertion_data = array(
                    'ga_id' => $ga_data[$k][0][$ga],
                    'po_id' => $insert_id,
                    'crclm_id' => $curriculum_id,
                    'map_level' => 0,
                    'created_by' => $this->ion_auth->user()->row()->id,
                    'created_date' => date('Y-m-d')
                );

                $this->db->insert('ga_po_map', $ga_insertion_data);
            }
        }

        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('particular_id', $curriculum_id)
                        ->where('entity_id', '6')
                        ->where('state', 2)
                        ->where('status', '1')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {
            $update_dashboard_data = array(
                'status' => '0'
            );
            $this->db
                    ->where('crclm_id', $curriculum_id)
                    ->where('entity_id', 6)
                    ->where('particular_id', $curriculum_id)
                    ->where('state', 1)
                    ->where('status', 1)
                    ->update('dashboard', $update_dashboard_data);
            $url = base_url('curriculum/po/index/' . $curriculum_id);

            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '6',
                'particular_id' => $curriculum_id,
                'state' => '2',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $this->ion_auth->user()->row()->id,
                'url' => $url,
                'description' => $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' creation is in progress, mapping between ' . $this->lang->line('sos') . ' and PEOs is pending.'
            );

            $this->db->insert('dashboard', $dashboard_data);
        }

        //workflow insertion taken 
        $count = $this->db
                        ->select('history_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('entity_id', '6')
                        ->where('state_id', '7')
                        ->get('workflow_history')->num_rows();

        if ($count == 0) {
            $workflow_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '6',
                'state_id' => '2',
            );
            $this->db->insert('workflow_history', $workflow_data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function to fetch curriculum details from curriculum table
     * @parameters: curriculum id
     * @return: curriculum details
     */
    public function curriculum_list($curriculum_id = 'NULL') {
        return $this->db
                        ->select('crclm_id, crclm_name')
                        ->get($this->curriculum_table)
                        ->order_by('crclm_name', 'ASC')
                        ->result_array();
    }

    /**
     * Function to update program outcome statement
     * @parameters: program outcome id, program outcome statement, program outcome type
     * @return: boolean
     */
    public function update_po_individual($po_id, $po_name, $po_statement, $po_type_id, $ga_data, $pso_data) {
        $po_data = array(
            'po_reference' => $po_name,
            'pso_flag' => $pso_data,
            'po_statement' => $po_statement,
            'po_type_id' => $po_type_id,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'modify_date' => date('Y-m-d')
        );
        $this->db
                ->where('po_id', $po_id)
                ->update($this->po_table, $po_data);

        $crclm_id_query = 'SELECT crclm_id FROM po WHERE po_id = "' . $po_id . '"';
        $crclm_data = $this->db->query($crclm_id_query);
        $crclm_id_res = $crclm_data->result_array();
        $curriculum_id = $crclm_id_res[0]['crclm_id'];

        $ga_size = count($ga_data);

        $delete_query = 'DELETE FROM ga_po_map WHERE po_id = "' . $po_id . '"';
        $delete_data = $this->db->query($delete_query);

        for ($ga = 0; $ga < $ga_size; $ga++) {
            $ga_update_array = array(
                'ga_id' => $ga_data[$ga],
                'po_id' => $po_id,
                'crclm_id' => $curriculum_id,
                'map_level' => 0,
                'modified_by' => $this->ion_auth->user()->row()->id,
                'modified_date' => date('Y-m-d')
            );
            $this->db->insert('ga_po_map', $ga_update_array);
        }

        return true;
    }

    /**
     * Function to fetch details for loading edit page
     * @parameters: program outcome id
     * @return: program outcome statement, program outcome type id
     */
    function edit_po($po_id) {
        $data['po_data'] = $this->db
                ->select('po_reference')
                ->select('po_statement')
                ->select('po_type_id')
                ->select('pso_flag')
                ->where('po_id', $po_id)
                ->get($this->po_table)
                ->result_array();


        $mapping_query = 'SELECT ga_id FROM ga_po_map WHERE po_id = "' . $po_id . '"';
        $mapping_data = $this->db->query($mapping_query);
        $mapping_result = $mapping_data->result_array();
        $data['ga_map_data'] = $mapping_result;

        if ($data['po_data'] == null) {
            $data['po_data'] = $this->db
                    ->select('po_statement')
                    ->select('po_type_id')
                    ->where('po_id', $po_id)
                    ->get($this->po_table)
                    ->result_array();
        }

        $data['curriculum_id'] = $this->db
                ->select('curriculum.crclm_id, curriculum.crclm_name')
                ->select('po_id')
                ->where('po_id', $po_id)
                ->join('curriculum', 'po.crclm_id = curriculum.crclm_id')
                ->get('po')
                ->result_array();

        $bos_comment_details = 'SELECT cmt_statement
								FROM comment
								WHERE entity_id = 6
									AND po_id = "' . $po_id . '"';
        $bos_comment_data = $this->db->query($bos_comment_details);
        $bos_comment = $bos_comment_data->result_array();

        if (!empty($bos_comment)) {
            $data['bos_comment'] = $bos_comment;
            return $data;
        } else {
            $bos_comment = "No BOS Comments";
            $data['bos_comment'][0]['cmt_statement'] = $bos_comment;
            return $data;
        }
    }

    /**
     * Function to fetch program outcome details from program outcome table
     * @parameters: curriculum id
     * @return: curriculum id, program id, program statement, 
     */
    function list_po($curriculum_id) {
        $po_list = 'SELECT p.crclm_id, p.po_id, p.pso_flag, p.po_reference, p.po_statement, p.po_type_id, pt.po_type_name,p.state_id
					FROM po as p, po_type as pt
					WHERE crclm_id = "' . $curriculum_id . '"
					AND p.po_type_id = pt.po_type_id
					ORDER BY LENGTH(p.po_reference), p.po_reference';

	/*$po_list = 'SELECT p.crclm_id, p.po_id, p.pso_flag, p.po_reference, p.po_statement, p.po_type_id, pt.po_type_name,p.state_id
					FROM po as p, po_type as pt
					WHERE crclm_id = "' . $curriculum_id . '"
					AND p.po_type_id = pt.po_type_id
					ORDER BY RIGHT(p.po_reference, 4)';*/

        $po_list = $this->db->query($po_list);
        $po_list = $po_list->result_array();

        $po_list['po_list'] = $po_list;

        $po_comment_query = 'SELECT po_id, actual_id, crclm_id, status, cmt_statement 
							 FROM comment 
							 WHERE crclm_id = "' . $curriculum_id . '"
								AND entity_id = 6';
        $po_comment = $this->db->query($po_comment_query);
        $po_cmt_data = $po_comment->result_array();
        $po_list['po_comment'] = $po_cmt_data;

        $po_data = 'SELECT ws.state_id, ws.status as state_name, d.state
						FROM  workflow_state AS ws, dashboard AS d 
						WHERE d.crclm_id = "' . $curriculum_id . '"
						AND d.status = 1
						AND d.entity_id = 6
						AND d.particular_id = "' . $curriculum_id . '"
						AND d.state = ws.state_id ';
        $po_result = $this->db->query($po_data);
        $po_state_data = $po_result->result_array();
        $po_list['po_state'] = $po_state_data;

        return $po_list;
    }

    /**
     * Function to delete program outcome from the program outcome table
     * @parameters: program outcome id
     * @return: boolean
     */
    function delete_po($po_id) {
        $delete_po = 'DELETE FROM po 
					  WHERE po_id = "' . $po_id . '"';
        $delete_po = $this->db->query($delete_po);
        return $delete_po;
    }

    /**
     * Function to fetch help details from the help table
     * @return: help details
     */
    function po_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 6';
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

    function help_content($id) {
        $help = 'SELECT  entity_data, help_desc
				 FROM help_content
				 WHERE serial_no = "' . $id . '"';
        $reslt = $this->db->query($help);
        $row = $reslt->result_array();

        return $row;
    }

    /**
     * Function to update the dashboard details when sent for approval 
     * @parameters: curriculum id
     * @return: boolean
     */
    public function po_creator_approve_publish_db($curriculum_id) {
        $update_query = 'UPDATE dashboard
						 SET status = 0
						 WHERE crclm_id = "' . $curriculum_id . '" 
							 AND entity_id = 6
							 AND particular_id = "' . $curriculum_id . '"';
        $update_data = $this->db->query($update_query);

        $po_approver_id = $this->db
                ->select('approver_id')
                ->where('crclm_id', $curriculum_id)
                ->get('peo_po_approver')
                ->result_array();
        $approver_id = $po_approver_id[0]['approver_id'];

        $url = base_url('curriculum/po/po_approval_grid/' . $curriculum_id);
        $dashboard_data = array(
            'crclm_id' => $curriculum_id,
            'entity_id' => '6',
            'particular_id' => $curriculum_id,
            'state' => '5',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $approver_id,
            'url' => $url,
            'description' => $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' are sent for approval.'
        );

        $this->db->insert('dashboard', $dashboard_data);

        return $dashboard_data;
    }

    /**
     * Function to update dashboard on approval by the BOS member
     * @parameters: curriculum id
     * @return: a array of values of all the dashboard details
     */
    function bos_approve_publish_db($curriculum_id) {
        $update_query = 'UPDATE dashboard
						 SET status = 0
						 WHERE crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 6
							AND particular_id = "' . $curriculum_id . '"
							AND status = 1';
        $update_data = $this->db->query($update_query);

        /* $select_user_id = 'SELECT crclm_owner 
          FROM curriculum
          WHERE crclm_id = "' . $curriculum_id . '"';
          $select_user_id = $this->db->query($select_user_id);
          $select_user_id = $select_user_id->result_array();

          $receiver_id = $select_user_id[0]['crclm_owner']; */

        //fetch chairman (hod) user id
        $chairman_details = 'SELECT d.dept_hod_id, c.pgm_id, p.dept_id, u.username, u.first_name, u.last_name 
							 FROM curriculum AS c, users AS u, program as p, department as d
							 WHERE c.crclm_id = "' . $curriculum_id . '" 
								AND u.id = d.dept_hod_id
								AND p.dept_id = d.dept_id
								AND c.pgm_id = p.pgm_id';
        $chairman_data = $this->db->query($chairman_details);
        $chairman = $chairman_data->result_array();

        $receiver_id = $chairman[0]['dept_hod_id'];

        $data['receiver_id'] = $receiver_id;
        $url = base_url('curriculum/map_po_peo/index/' . $curriculum_id);

        $dashboard_data = array(
            'crclm_id' => $curriculum_id,
            'entity_id' => '6',
            'particular_id' => $curriculum_id,
            'state' => '7',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' Approval Accepted.'
        );

        $this->db->insert('dashboard', $dashboard_data);

        $dashboard_data_po_peo_mapping = array(
            'crclm_id' => $curriculum_id,
            'entity_id' => '13',
            'particular_id' => $curriculum_id,
            'state' => '1',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' are approved, proceed with mapping between ' . $this->lang->line('sos') . ' and PEOs.'
        );

        $this->db->insert('dashboard', $dashboard_data_po_peo_mapping);

        $data['dashboard_data_po_peo_mapping'] = $dashboard_data_po_peo_mapping;
        return $data;
    }

    /**
     * Function to insert new comments
     * @parameters: program outcome id, curriculum id, performance indicator comment, comment status
     * @return: boolean
     */
    public function comment_insert($po_id, $curriculum_id, $po_comment, $comment_status) {
        $select_query = 'SELECT entity_id
						 FROM entity 
						 WHERE entity_name = "po"';
        $entity_id = $this->db->query($select_query);
        $entity_id_data = $entity_id->result_array();

        $entity_id_entry = $entity_id_data[0]['entity_id'];
        $comment_add = array(
            'entity_id' => $entity_id_entry,
            'actual_id' => $po_id,
            'po_id' => $po_id,
            'crclm_id' => $curriculum_id,
            'cmt_statement' => $po_comment,
            'commented_by' => $this->ion_auth->user()->row()->id,
            'in_reply_to' => '0',
            'created_by' => $this->ion_auth->user()->row()->id,
            'modified_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
            'modified_date' => date('Y-m-d'),
            'status' => $comment_status,
        );

        $this->db->insert('comment', $comment_add);
        return true;
    }

    /**
     * Function to update dashboard with rework details
     * @parameters: curriculum id
     * @return: boolean
     */
    public function po_rework_dashboard_entry($curriculum_id) {
        $update_query = 'UPDATE dashboard
						 SET status = 0
						 WHERE crclm_id = "' . $curriculum_id . '" 
						    AND entity_id = 6
							AND status = 1 
							AND particular_id = "' . $curriculum_id . '"';
        $update_query = $this->db->query($update_query);

        /* $crclm_owner = $this->db
          ->select('crclm_owner')
          ->where('crclm_id', $curriculum_id)
          ->get('curriculum')
          ->result_array();
          $curriculum_owner = $crclm_owner[0]['crclm_owner']; */

        //fetch chairman (hod) user id
        $chairman_details = 'SELECT d.dept_hod_id, c.pgm_id, p.dept_id, u.username, u.first_name, u.last_name 
							 FROM curriculum AS c, users AS u, program as p, department as d
							 WHERE c.crclm_id = "' . $curriculum_id . '" 
								AND u.id = d.dept_hod_id
								AND p.dept_id = d.dept_id
								AND c.pgm_id = p.pgm_id';
        $chairman_data = $this->db->query($chairman_details);
        $chairman = $chairman_data->result_array();

        $receiver_id = $chairman[0]['dept_hod_id'];

        $url = base_url('curriculum/po/index/' . $curriculum_id);
        $dashboard_data = array(
            'crclm_id' => $curriculum_id,
            'entity_id' => '6',
            'particular_id' => $curriculum_id,
            'state' => '6',
            'status' => '1',
            'sender_id' => $this->ion_auth->user()->row()->id,
            'receiver_id' => $receiver_id,
            'url' => $url,
            'description' => $this->lang->line('student_outcomes_full') . ' ' . $this->lang->line('student_outcomes') . ' are sent back for rework.'
        );

        $this->db->insert('dashboard', $dashboard_data);
        return $dashboard_data;
    }

    /**
     * Function to fetch curriculum details and program outcome details for approve page
     * @parameters: curriculum id
     * @return: curriculum id, curriculum name, program outcome id and program outcome statements
     */
    public function po_approve_grid($curriculum_id) {
        $crclm_query = $this->db
                ->select('crclm_name, crclm_id')
                ->where('crclm_id', $curriculum_id)
                ->get('curriculum')
                ->result_array();
        $po_approval_data['crclm_name'] = $crclm_query;

        $po_list_query = 'SELECT p.crclm_id, p.po_id, p.pso_flag, p.po_reference, p.po_statement, p.po_type_id, pt.po_type_name
						  FROM po as p, po_type as pt
						  WHERE crclm_id = "' . $curriculum_id . '"
							AND p.po_type_id = pt.po_type_id
						  ORDER BY LENGTH(p.po_reference), p.po_reference';

	 /*$po_list_query = 'SELECT p.crclm_id, p.po_id, p.pso_flag, p.po_reference, p.po_statement, p.po_type_id, pt.po_type_name,p.state_id
					FROM po as p, po_type as pt
					WHERE crclm_id = "' . $curriculum_id . '"
					AND p.po_type_id = pt.po_type_id
					ORDER BY RIGHT(p.po_reference, 4)';*/


        $po_list_details = $this->db->query($po_list_query);
        $po_list = $po_list_details->result_array();
        $po_approval_data['po_data'] = $po_list;

        $po_comment_query = 'SELECT po_id, crclm_id, cmt_statement 
							 FROM comment 
							 WHERE crclm_id = "' . $curriculum_id . '" 
								AND entity_id ="6"';
        $po_comment_data = $this->db->query($po_comment_query);
        $po_comment_list = $po_comment_data->result_array();
        $po_approval_data['po_comment_result'] = $po_comment_list;

        return $po_approval_data;
    }

    /**
     * Function to fetch program outcome type details
     * @return: program outcome type id and program outcome type name
     */
    function list_po_types($curriculum_id) {
        $curriculum_details = 'SELECT crclm_name
							   FROM curriculum
							   WHERE crclm_id = "' . $curriculum_id . '"';
        $curriculum_data = $this->db->query($curriculum_details);
        $curriculum = $curriculum_data->result_array();
        $data['curriculum_name'] = $curriculum;

        $data['po_types_id_names'] = $this->db
                ->select('po_type_id')
                ->select('po_type_name')
                ->get($this->po_type_table)
                ->result_array();

        $accredit_type_query = 'SELECT atype_id, atype_name 
								FROM accreditation_type';
        $accredit_type_data = $this->db->query($accredit_type_query);
        $accredit_type_result = $accredit_type_data->result_array();
        $data['accredit_type'] = $accredit_type_result;

        $query = 'SELECT crclm.pgm_id, ga.ga_id, ga.ga_reference, ga.ga_statement, pgm.pgmtype_id FROM curriculum as crclm 
						JOIN program as pgm ON pgm.pgm_id = crclm.pgm_id
						JOIN graduate_attributes as ga ON ga.pgmtype_id = pgm.pgmtype_id
						WHERE crclm.crclm_id = "' . $curriculum_id . '"';
        $ga_data = $this->db->query($query);
        $ga_result = $ga_data->result_array();

        $data['ga_data'] = $ga_result;

        return $data;
    }

    /* Testing Function
     *
     *
     *
     *
     */

    public function list_po_types_new($curriculum_id) {
        $curriculum_details = 'SELECT * FROM curriculum as c
							join program as p ON p.pgm_id = c.pgm_id
							join program_type as pt ON pt.pgmtype_id = p.pgmtype_id
							WHERE crclm_id =  "' . $curriculum_id . '"';
        $curriculum_data = $this->db->query($curriculum_details);
        $curriculum = $curriculum_data->result_array();
        $data['curriculum_name'] = $curriculum[0]['crclm_name'];
        $data['pgm'] = $curriculum[0]['pgm_id'];
        $data['pgm_type'] = $curriculum[0]['pgmtype_id'];
        //var_dump($curriculum);
        $data['po_types_id_names'] = $this->db
                ->select('po_type_id')
                ->select('po_type_name')
                ->get($this->po_type_table)
                ->result_array();

        $accredit_type_query = 'SELECT atype_id, atype_name 
								FROM accreditation_type';
        $accredit_type_data = $this->db->query($accredit_type_query);
        $accredit_type_result = $accredit_type_data->result_array();
        $data['accredit_type'] = $accredit_type_result;

        //$query= 'SELECT ga_id, ga_reference, ga_statement FROM graduate_attributes';
        $query = 'SELECT ga_id, ga_statement, ga_reference 
				FROM graduate_attributes as g
				JOIN program as p ON p.pgmtype_id=g.pgmtype_id
				JOIN curriculum as c ON p.pgm_id=c.pgm_id 
				where c.pgm_id="' . $data['pgm'] . '" AND
				g.pgmtype_id="' . $data['pgm_type'] . '" AND 
				crclm_id="' . $curriculum_id . '"';
        $ga_data = $this->db->query($query);
        $ga_result = $ga_data->result_array();
        //	var_dump($ga_result);
        $data['ga_data'] = $ga_result;
        return $data;
    }

    /* Function is used to fetch the current state of POs from dashboard table..
     * @parameter - curriculum id.
     * @returns- a array value of the current state details.
     */

    public function fetch_po_current_state($curriculum_id) {
        $po_data = 'SELECT ws.state_id, ws.status as state_name, d.state
						FROM  workflow_state AS ws, dashboard AS d 
						WHERE d.crclm_id = "' . $curriculum_id . '"
							AND d.status = 1
							AND d.entity_id = 6
							AND d.particular_id = "' . $curriculum_id . '"
							AND d.state = ws.state_id ';
        $po_result = $this->db->query($po_data);
        $po_state_data = $po_result->result_array();
        return $po_state_data;
    }

    /* Function to fetch accreditation details and program outcome type from accreditation type and program outcome table
     * @parameter - accreditation type id
     * @returns- accreditation and program outcome type details
     */

    public function fetch_accredit_count($atype_id, $crclm_id) {

        $curriculum_details = 'SELECT * FROM curriculum as c
							join program as p ON p.pgm_id = c.pgm_id
							join program_type as pt ON pt.pgmtype_id = p.pgmtype_id
							WHERE crclm_id =  "' . $crclm_id . '"';
        $curriculum_data = $this->db->query($curriculum_details);
        $curriculum = $curriculum_data->result_array();

        $data['curriculum_name'] = $curriculum[0]['crclm_name'];
        $data['pgm'] = $curriculum[0]['pgm_id'];
        $data['pgm_type'] = $curriculum[0]['pgmtype_id'];
        $accreditation_grid_query = 'SELECT atype_details_name, atype_details_description, po_type_id
									 FROM accreditation_type_details
									 WHERE atype_id = "' . $atype_id . '" ';
        $accreditation_grid_data = $this->db->query($accreditation_grid_query);
        $accreditation_grid_result = $accreditation_grid_data->result_array();
        $data['accreditation_data'] = $accreditation_grid_result;

        $data['po_types_id_names'] = $this->db
                ->select('po_type_id')
                ->select('po_type_name')
                ->get($this->po_type_table)
                ->result_array();
        //	$query= 'SELECT ga_id, ga_reference, ga_statement FROM graduate_attributes';

        $query = 'SELECT ga_id, ga_statement, ga_reference 
				FROM graduate_attributes as g
				JOIN program as p ON p.pgmtype_id=g.pgmtype_id
				JOIN curriculum as c ON p.pgm_id=c.pgm_id 
				where c.pgm_id="' . $data['pgm'] . '" AND
				g.pgmtype_id="' . $data['pgm_type'] . '" AND 
				crclm_id="' . $crclm_id . '"';
        $ga_data = $this->db->query($query);
        $ga_result = $ga_data->result_array();

        $data['ga_data'] = $ga_result;


        return $data;
    }

    /* Function to fetch program outcome type from accreditation type and program outcome table
     * @parameter - accreditation type id
     * @returns- accreditation and program outcome type details
     */

    public function po_type_list($curriculum_id) {
        $po_types_id_names = $this->db
                ->select('po_type_id')
                ->select('po_type_name')
                ->get($this->po_type_table)
                ->result_array();
        $data['po_type_id'] = $po_types_id_names;

        $query = 'SELECT crclm.pgm_id, ga.ga_id, ga.ga_reference, ga.ga_statement, pgm.pgmtype_id FROM curriculum as crclm 
						JOIN program as pgm ON pgm.pgm_id = crclm.pgm_id
						JOIN graduate_attributes as ga ON ga.pgmtype_id = pgm.pgmtype_id
						WHERE crclm.crclm_id = "' . $curriculum_id . '"';
        $ga_data = $this->db->query($query);
        $ga_result = $ga_data->result_array();

        $data['ga_data'] = $ga_result;
        return $data;
    }

    /**
     * Function to inserts the comments notes(text) details onto the comment table
     * @parameters: curriculum id and notes(text) statement.
     * @return: boolean
     */
    public function po_comment_save($curriculum_id, $po_id, $po_comment) {

        $delete_query = 'DELETE FROM comment WHERE crclm_id ="' . $curriculum_id . '" AND po_id = "' . $po_id . '" AND entity_id = "6" ';
        $query_result = $this->db->query($delete_query);
        $data_array = array(
            'entity_id' => 6,
            'po_id' => $po_id,
            'crclm_id' => $curriculum_id,
            'cmt_statement' => $po_comment,
        );

        $this->db->insert('comment', $data_array);
        return true;
    }

    /**
     * Function to fetch bos user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
    public function bos_user($curriculum_id) {
        $bos_user_query = 'SELECT a.approver_id, u.title, u.first_name, u.last_name 
						   FROM peo_po_approver AS a, users AS u
						   WHERE crclm_id = "' . $curriculum_id . '" 
							AND a.approver_id = u.id ';
        $bos_user = $this->db->query($bos_user_query);
        $bos_user = $bos_user->result_array();

        return $bos_user;
    }

    /**
     * Function to fetch chairman user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
    public function chairman_user($curriculum_id) {
        $chairman_user_query = 'SELECT u.title, u.first_name, u.last_name 
							    FROM curriculum AS c, users AS u, program as p, department as d
							    WHERE c.crclm_id = "' . $curriculum_id . '" 
								  AND u.id = d.dept_hod_id
								  AND p.dept_id = d.dept_id
								  AND c.pgm_id = p.pgm_id';
        $chairman_user = $this->db->query($chairman_user_query);
        $chairman = $chairman_user->result_array();

        return $chairman;
    }

    /* Function is used to fetch curriculum id from curriculum table
     * @parameters: curriculum id
     * @return: curriculum id
     */

    public function fetch_curriculum($curriculum_id) {
        $curriculum_id_query = 'SELECT crclm_name
								FROM curriculum
								WHERE crclm_id = "' . $curriculum_id . '"';
        $curriculum_id_data = $this->db->query($curriculum_id_query);
        $curriculum_data = $curriculum_id_data->result_array();

        return $curriculum_data;
    }

    public function skip_approval_flag_fetch() {
        return $this->db->select('skip_approval')
                        ->where('entity_id', 6)
                        ->get('entity')
                        ->result_array();
    }

    public function po_state_update($curriculum_id) {
        $data = array(
            'state_id' => 7
        );

        $this->db->where('crclm_id', $curriculum_id);
        //Add po attainment levels once PO is approved
        $this->db->query("call po_attainment_levels('" . $curriculum_id . "')");
        return $this->db->update('po', $data);
    }

    public function po_state_approval_update($curriculum_id) {
        $data = array(
            'state_id' => 5
        );

        $this->db->where('crclm_id', $curriculum_id);
        return $this->db->update('po', $data);
    }

}

/*
 * End of file po_model.php
 * Location: .curriculum/pi_measures/po_model.php 
 */
?>
