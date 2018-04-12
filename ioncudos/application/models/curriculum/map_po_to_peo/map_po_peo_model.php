<?php

/**
 * Description          :	Model(Database) Logic for PO(Student Outcomes) to PEO(Program Educational Objectives) Mapping Module.
 * Created		:	25-03-2013. 
 * Modification History:
 * Date			  Modified By				Description
 * 24-09-2013		Abhinay B.Angadi                Added file headers, function headers, indentations & comments.
 * 25-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
 * 22-10-2015		Bhagyalaxmi S S
 * 06-15-2015		Bhagyalaxmi S S			Added justification for each mapping	
  -------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Map_po_peo_model extends CI_Model {
    /* Function to fetch the state from the dashboard whose status is 1
     * @parameters: curriculum id
     * @return: an array of dashboard details.
     */

    public function get_state($curriculum_id) {
        $get_state = 'SELECT count(state), state 
						FROM dashboard 
						WHERE status = 1 
						AND crclm_id = "' . $curriculum_id . '" 
						AND entity_id = 13 
						GROUP BY state';
        $get_state = $this->db->query($get_state);
        $get_state = $get_state->result_array();

        $data['state_id'] = $get_state;
        return $data;
    }

    /* Function to fetch po details, peo details, po_peo mapping details from po, peo and po_peo_map tables.
     * @parameters: curriculum id
     * @return: an array of po, peo, po_peo_map details.
     */

    public function map_po_peo($curriculum_id) {
        $po = 'SELECT po_id,pso_flag, po_reference, po_statement, crclm_id 
				FROM po 
				WHERE crclm_id = "' . $curriculum_id . '" 
				ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $number_of_po = $po_list->num_rows();
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;
        $data['number_of_po'] = $number_of_po;

        $peo = 'SELECT peo_id, peo_statement , peo_reference
				FROM peo 
				WHERE crclm_id = "' . $curriculum_id . '" ';
        $peo_list = $this->db->query($peo);
        $number_of_peo = $peo_list->num_rows();
        $peo_list = $peo_list->result_array();
        $data['peo_list'] = $peo_list;
        $data['number_of_peo'] = $number_of_peo;


        $mapped_po_peo = 'select p.pp_id, p.justification,p.peo_id, p.po_id, p.crclm_id, p.created_date, po.po_reference, peo.peo_reference, p.map_level,  m.map_level_short_form, m.status, m.map_level_name

			    from po_peo_map as p, po as po, peo as peo, map_level_weightage as m

			    where p.crclm_id = "' . $curriculum_id . '" 

			    and po.po_id= p.po_id

			    and peo.peo_id = p.peo_id

			    and m.map_level=p.map_level;
			     ';
        $mapped_po_peo = $this->db->query($mapped_po_peo);
        $mapped_po_peo = $mapped_po_peo->result_array();
        $data['mapped_po_peo'] = $mapped_po_peo;

        $peo_po_comment_query = 'SELECT crclm_id, entity_id, cmt_statement FROM comment WHERE crclm_id = "' . $curriculum_id . '" AND entity_id = "13"';
        $peo_po_comment_data = $this->db->query($peo_po_comment_query);
        $peo_po_comment_result = $peo_po_comment_data->result_array();
        $data['peo_po_comment'] = $peo_po_comment_result;

        $peo_po_notes_query = 'SELECT notes, notes_id FROM notes WHERE crclm_id ="' . $curriculum_id . '" AND entity_id ="13" ';
        $peo_po_notes_data = $this->db->query($peo_po_notes_query);
        $peo_po_notes_result = $peo_po_notes_data->result_array();
        $data['peo_po_notes_data'] = $peo_po_notes_result;

        $weightage_data = 'select * from map_level_weightage where status=1';
        $weightage_data = $this->db->query($weightage_data);
        $weightage_data = $weightage_data->result_array();
        $data['weightage_data'] = $weightage_data;

        $query = $this->db->query('select indv_mapping_justify_flag from organisation');
        $data['indv_mappig_just'] = $query->result_array();

        return $data;
    }

    /* Function to fetch po details, peo details, po_peo mapping details from po, peo and po_peo_map tables.
     * @parameters: curriculum id
     * @return: an array of po, peo, po_peo_map details.
     */

    public function map_po_peo_new($curriculum_id) {
        $po = 'SELECT po_id, pso_flag, po_reference, po_statement, crclm_id 
				FROM po 
				WHERE crclm_id = "' . $curriculum_id . '" 
				ORDER BY LENGTH(po_reference), po_reference';
        $po_list = $this->db->query($po);
        $number_of_po = $po_list->num_rows();
        $po_list = $po_list->result_array();
        $data['po_list'] = $po_list;
        $data['number_of_po'] = $number_of_po;

        $peo = 'SELECT peo_id, peo_statement, peo_reference 
				FROM peo 
				WHERE crclm_id = "' . $curriculum_id . '" ';
        $peo_list = $this->db->query($peo);
        $number_of_peo = $peo_list->num_rows();
        $peo_list = $peo_list->result_array();
        $data['peo_list'] = $peo_list;
        $data['number_of_peo'] = $number_of_peo;

        $mapped_po_peo = 'SELECT pp_id,justification,peo_id, po_id, crclm_id ,p.map_level, m.map_level_short_form, m.status, m.map_level_name, p.created_date
							FROM po_peo_map as p
							LEFT JOIN map_level_weightage as m ON m.map_level=p.map_level
							WHERE crclm_id = "' . $curriculum_id . '" ';
        $mapped_po_peo = $this->db->query($mapped_po_peo);
        $mapped_po_peo = $mapped_po_peo->result_array();
        $data['mapped_po_peo'] = $mapped_po_peo;


        $weightage_data = 'select * from map_level_weightage where status=1';
        $weightage_data = $this->db->query($weightage_data);
        $weightage_data = $weightage_data->result_array();
        $data['weightage_data'] = $weightage_data;


        $peo_po_comment_query = 'SELECT crclm_id, entity_id, cmt_statement FROM comment WHERE crclm_id = "' . $curriculum_id . '" AND entity_id = "13"';
        $peo_po_comment_data = $this->db->query($peo_po_comment_query);
        $peo_po_comment_result = $peo_po_comment_data->result_array();
        $data['peo_po_comment'] = $peo_po_comment_result;

        $peo_po_notes_query = 'SELECT notes, notes_id FROM notes WHERE crclm_id ="' . $curriculum_id . '" AND entity_id ="13" ';
        $peo_po_notes_data = $this->db->query($peo_po_notes_query);
        $peo_po_notes_result = $peo_po_notes_data->result_array();
        $data['peo_po_notes_data'] = $peo_po_notes_result;

        $query = $this->db->query('select indv_mapping_justify_flag from organisation');
        $data['indv_mappig_just'] = $query->result_array();
        return $data;
    }

    public function peo_po_comment_data($curriculum_id) {

        $peo_po_comment_query = 'SELECT crclm_id, entity_id, cmt_statement FROM comment WHERE crclm_id = "' . $curriculum_id . '" AND entity_id = "13"';
        $peo_po_comment_data = $this->db->query($peo_po_comment_query);
        $peo_po_comment_result = $peo_po_comment_data->result_array();
        $data['peo_po_comment'] = $peo_po_comment_result;
        return $data;
    }

    /**
     * Function to fetch curriculum details from curriculum table
     * @parameters: user id
     * @return: an array of curriculum details.
     */
    public function list_department_curriculum($user_id) {
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, users AS u,program AS p, dashboard AS d 
								WHERE u.id = "' . $user_id . '" 
								AND u.user_dept_id = p.dept_id 
								AND c.pgm_id = p.pgm_id 
								AND d.crclm_id = c.crclm_id 
								AND entity_id = 13 
								AND d.status = 1 
								AND c.status = 1 
								ORDER BY c.crclm_name ASC ';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();

        return $curriculum_list;
    }

    /**
     * Function to inserts the comments notes(text) details onto the notes table
     * @parameters: curriculum id and notes(text) statement.
     * @return: boolean
     */
    public function save_notes_in_database($curriculum_id, $text) {
        $query = ' SELECT COUNT(notes_id) 
						FROM notes
						WHERE crclm_id = "' . $curriculum_id . '" ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        $temp = $result[0]['COUNT(notes_id)'];
        $query_curriculum_id = 'SELECT notes_id, crclm_id 
									FROM notes
									WHERE crclm_id = "' . $curriculum_id . '"';
        $result_curriculum_id = $this->db->query($query_curriculum_id);
        $result_curriculum_id = $result_curriculum_id->result_array();
        $success = 0;
        if ($temp != 0) {
            if ($result_curriculum_id[0]['crclm_id'] == $curriculum_id) {
                $query = 'UPDATE notes 
							SET notes = "' . $text . '", 
								entity_id = 13 
							WHERE crclm_id = "' . $curriculum_id . '"';
                $query = $this->db->query($query);
                $success = 1;
                break;
            }
        }

        if ($temp == 0 || $success == 0) {
            $data = array(
                'notes' => $text,
                'crclm_id' => $curriculum_id,
            );

            $this->db->insert('notes', $data);
        }
        return true;
    }

    /* Function to fetch notes (text) of a particular curriculum from the notes table
     * @parameters: curriculum id
     * @return: JSON_ENCODE object.
     */

    public function text_details($curriculum_id) {
        $notes = 'SELECT notes 
					FROM notes 
					WHERE crclm_id = "' . $curriculum_id . '" 
					AND entity_id = 13 ';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();
        if (!empty($notes[0]['notes'])) {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode($notes[0]['notes']));
        } else {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode("1"));
        }
    }

    /** dummy
     * Function to fetch program outcome and program educational objective approver details
     * @parameters: curriculum id and user id
     * @return: program outcome program educational objective approver id, entity id, department id,
      created by, modified by, created date, last date and modified date
     */
    public function check_user_permission($curriculum_id, $user_id) {
        $check_user_id = 'SELECT * 
							FROM peo_po_approver 
							WHERE entity_id = 13 
							AND crclm_id = "' . $curriculum_id . '" 
							AND approver_id = "' . $user_id . '" ';
        $check_user_id = $this->db->query($check_user_id);
        $number_check_user_id = $check_user_id->num_rows();
        echo $number_check_user_id;
    }

    /* Function to insert work flow history details onto workflow_history table
     * @parameters: curriculum id, state id and user id
     * @return: an array of 
     */

    public function send_for_Approve($curriculum_id, $state_id, $user_id) {
        /* $insert_into_workflow_history = 'INSERT INTO workflow_history(entity_id, crclm_id, state_id) 
          VALUES (13,"'.$curriculum_id.'", "'.$state_id .'")';
          $insert_into_workflow_history = $this->db->query($insert_into_workflow_history); */

        if ($state_id == 5 || $state_id == 6 || $state_id == 7) {
            if ($state_id == '5') {
                $update = 'UPDATE dashboard 
							SET status = 0 
							WHERE crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 13 
							AND status = 1 ';
                $update = $this->db->query($update);
                $select_user_id = 'SELECT approver_id 
									FROM peo_po_approver 
									WHERE entity_id = 13 
									AND crclm_id = "' . $curriculum_id . '"';
                $select_user_id = $this->db->query($select_user_id);
                $select_user_id = $select_user_id->result_array();
                $receiver_id = $select_user_id[0]['approver_id'];
                $data['receiver_id'] = $receiver_id;

                $data['state'] = $state_id;
                $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs is sent for your approval.';
                $url = base_url('curriculum/map_po_peo/approval_page') . '/' . $curriculum_id;
                $insert = ' INSERT INTO dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
													url, description, state, status) 
							VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 13, "' . $user_id . '", "' .
                        $receiver_id . '", "' . $url . '", "' . $description . '", "' . $state_id . '", 1) ';
                $insert = $this->db->query($insert);
                $data['receiver_id'] = $receiver_id;
                $data['state'] = $state_id;
                $data['url'] = $url;
            } else if ($state_id == '6') {
                $update = ' UPDATE dashboard 
								SET status = 0 
								WHERE crclm_id = "' . $curriculum_id . '" 
								AND entity_id = 13 
								AND status = 1 ';
                $select_user_id = $this->db->query($update);

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

                $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs is sent for rework';
                $url = base_url('curriculum/map_po_peo/index') . '/' . $curriculum_id;
                $insert = 'INSERT into dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
													url, description, state, status) 
							VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 13, "' . $user_id . '", "' .
                        $receiver_id . '", "' . $url . '", "' . $description . '", "' . $state_id . '", 1) ';
                $insert = $this->db->query($insert);
                $data['receiver_id'] = $receiver_id;
                $data['state'] = $state_id;
                $data['url'] = $url;
            } else if ($state_id == '7') {
                /* $update = 'UPDATE curriculum 
                  SET status = 1
                  WHERE crclm_id = "' . $curriculum_id . '" ';
                  $update = $this->db->query($update); */

                $update = 'UPDATE dashboard 
							SET status = 0 
							WHERE crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 13 
							AND status = 1 ';
                $select_user_id = $this->db->query($update);

                //fetch program owner
                $program_owner_details = 'SELECT c.crclm_owner, u.username, u.first_name, u.last_name 
										  FROM curriculum AS c, users AS u 
										  WHERE c.crclm_id = "' . $curriculum_id . '" 
											AND u.id = c.crclm_owner ';
                $program_owner_id = $this->db->query($program_owner_details);
                $program_owner = $program_owner_id->result_array();

                $receiver_program_owner = $program_owner[0]['crclm_owner'];

                //fetch chairman (hod) user id
                $chairman_details = 'SELECT d.dept_hod_id, c.pgm_id, p.dept_id, u.username, u.first_name, u.last_name 
									 FROM curriculum AS c, users AS u, program as p, department as d
									 WHERE c.crclm_id = "' . $curriculum_id . '" 
										AND u.id = d.dept_hod_id
										AND p.dept_id = d.dept_id
										AND c.pgm_id = p.pgm_id';
                $chairman_data = $this->db->query($chairman_details);
                $chairman = $chairman_data->result_array();

                $chairman_receiver_id = $chairman[0]['dept_hod_id'];

                $description = $this->lang->line('sos') . ' to PEOs Mapping Approval is Accepted.';
                $mapping_state_id = 7;
                $url = base_url('curriculum/pi_and_measures/index') . '/' . $curriculum_id;

                //insert into to dashboard table for program owner
                $insert = 'INSERT INTO dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
													url, description, state, status) 
							VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 13, "' . $user_id . '", "' .
                        $receiver_program_owner . '", "' . $url . '", "' . $description . '", "' . $mapping_state_id . '", 1) ';
                $insert = $this->db->query($insert);

                //insert into to dashboard table for chairman
                $url = '#';
                /*  Segregating the dashboard data depending on oe_pi_flag
                  Abhinay B. Angadi */
                $query = 'select oe_pi_flag from curriculum where crclm_id="' . $curriculum_id . '"';
                $re = $this->db->query($query);
                $result = $re->result_array();
                if ($result[0]['oe_pi_flag'] == 0) {
                    $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs Approval is Accepted. Now ' . $this->lang->line('program_owner_full') . ' of this Curriculum will be able to with creation of Courses under each Terms.';
                } else {
                    $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs Approval is Accepted. Now ' . $this->lang->line('program_owner_full') . ' of this Curriculum will be able to proceed with creation of ' . $this->lang->line('outcome_element_plu_full') . ' & Performance Indicators(PIs).';
                }
                /*  Segregating the dashboard data depending on oe_pi_flag
                  Abhinay B. Angadi */
                $pi_measures_state_id = 1;
                // $url = base_url('curriculum/pi_and_measures/index').'/'. $curriculum_id;
                $insert = 'INSERT INTO dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
													url, description, state, status) 
							VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 20, "' . $user_id . '", "' .
                        $chairman_receiver_id . '", "' . $url . '", "' . $description . '", "' . $pi_measures_state_id . '", 1) ';
                $insert = $this->db->query($insert);

                /* query to fetch the oe_pi_flag 
                  Bhagya S S */
                $query = 'select oe_pi_flag from curriculum where crclm_id="' . $curriculum_id . '"';
                $re = $this->db->query($query);
                $result = $re->result_array();
                $data['oe_pi_flag'] = $result[0]['oe_pi_flag'];
                /*  Segregating the dashboard data depending on oe_pi_flag
                  Bhagya S S
                 */

                if ($result[0]['oe_pi_flag'] == 0) {
                    $url = base_url('curriculum/course/add_course') . '/' . $curriculum_id;
                    $state_id = 1;
                    $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs Approval is Accepted. Proceed with creation of Courses under each Terms.';
                    $insert_po_peo_data_into_dashboard = 'INSERT INTO dashboard(crclm_id, entity_id, particular_id,
															sender_id, receiver_id, url, description, state, status)
														  VALUES("' . $curriculum_id . '", 4, "' . $curriculum_id . '", "' . $user_id . '",
															"' . $receiver_program_owner . '", "' . $url . '", "' . $description . '", "' .
                            $state_id . '", 1)';
                    $insert_po_peo_dashboard = $this->db->query($insert_po_peo_data_into_dashboard);

                    $data['entity_id'] = 4;
                    $data['receiver_id'] = $receiver_program_owner;
                    $data['state'] = 1;
                    $data['url'] = $url;
                } else {
                    $url = base_url('curriculum/pi_and_measures/index') . '/' . $curriculum_id;
                    //displayed in program owner action item
                    $description = $this->lang->line('sos') . ' to PEOs Mapping Approval is Accepted, Proceed with creation of ' . $this->lang->line('outcome_element_plu_full') . ' & Performance Indicators(PIs).';
                    $pi_measures_state_id = 1;
                    $url = base_url('curriculum/pi_and_measures/index') . '/' . $curriculum_id;
                    $insert = 'INSERT INTO dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
														url, description, state, status) 
								VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 20, "' . $user_id . '", "' .
                            $receiver_program_owner . '", "' . $url . '", "' . $description . '", "' . $pi_measures_state_id . '", 1) ';
                    $insert = $this->db->query($insert);

                    $data['receiver_id'] = $receiver_program_owner;
                    $data['state'] = $state_id;
                    $data['url'] = $url;
                }

                $select_user_id = 'SELECT approver_id 
								   FROM peo_po_approver 
								   WHERE entity_id = 13 
									  AND crclm_id = "' . $curriculum_id . '" ';
                $select_user_id = $this->db->query($select_user_id);

                $select_user_id = $select_user_id->result_array();
                $receiver_id = $select_user_id[0]['approver_id'];
            }
        }

        return $data;
    }

    /* Function to insert po to peo mapping entry from po_peo_table table.
     * @parameters: curriculum id, po_peo_mapping details
     * @return:a boolean value.
     */

    public function add_po_peo_mapping($po_peo_mapping, $curriculum_id) {
        $po_peo_array = explode("|", $po_peo_mapping);
        $add_po_peo_mapping = 'INSERT INTO po_peo_map (peo_id, po_id, crclm_id) 
								VALUES ("' . $po_peo_array[1] . '", "' . $po_peo_array[0] . '", "' . $curriculum_id . '")';
        $add_po_peo_mapping = $this->db->query($add_po_peo_mapping);
        return true;
    }

    /* Function to delete po to peo mapping entry from po_peo_table table.
     * @parameters: po id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap($po, $curriculum_id) {
        $po_peo_array = explode("|", $po);
        $delete_po_peo_mapping = 'DELETE FROM po_peo_map 
								  WHERE peo_id = "' . $po_peo_array[1] . '" 
									AND po_id = "' . $po_peo_array[0] . '" 
									AND crclm_id = "' . $curriculum_id . '" ';
        $curriculum_list = $this->db->query($delete_po_peo_mapping);
        return true;
    }

    /* Function to fetch curriculum details from curriculum table whose status is 1
     * @return: an array of curriculum details.
     */

    public function list_curriculum() {
        $logged_in_user_id = $this->ion_auth->user()->row()->id;
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c, dashboard AS d 
							WHERE d.crclm_id = c.crclm_id 
							AND entity_id = 13 
							AND d.status = 1 
							AND c.status = 1 
							ORDER BY c.crclm_name ASC ';
        //AND d.receiver_id = "'.$logged_in_user_id.'" ';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();
        return $curriculum_list;
    }

    /* Function to fetch help details of Po to PEO mapping from help content table
     * @return: help content details.
     */

    public function peo_po_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 13';
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

    /* Function to insert comment and its related details onto the comment table.
     * @parameters: curriculum id, po id, peo id and po_peo comment statement.
     * @return: a boolean value.
     */

    public function po_peo_comment_insert($curriculum_id, $po_id, $peo_id, $po_peo_comment) {
        $insert_comment = 'INSERT INTO comment (entity_id, po_id, clo_id, crclm_id, cmt_statement) 
							VALUES (13, "' . $po_id . '", "' . $peo_id . '", "' . $curriculum_id . '", 
									"' . $po_peo_comment . '") ';
        $insert_comment = $this->db->query($insert_comment);
        return true;
    }

    /* Function is used to fetch the Approver details of mapping between PO & PEO from peo_po_approver table.
     * @param - curriculum id.
     * @returns- a array od approver details.
     */

    public function check_for_approver($curriculum_id) {
        $select_approver_id = 'SELECT approver_id 
								FROM peo_po_approver 
								WHERE entity_id = 13 
								AND crclm_id = "' . $curriculum_id . '" ';
        $select_approver_id = $this->db->query($select_approver_id);
        $select_approver_id = $select_approver_id->result_array();
        $approver_id = $select_approver_id[0]['approver_id'];
        return $approver_id;
    }

    /* Function is used to fetch the current state of POs to PEOs Mapping from dashboard table.
     * @param - curriculum id.
     * @returns- a array value of the current state details.
     */

    public function fetch_po_peo_map_current_state($curriculum_id) {
        $po_peo_map_data = 'SELECT ws.state_id, ws.status as state_name, d.state
								FROM  workflow_state AS ws, dashboard AS d 
								WHERE d.crclm_id = "' . $curriculum_id . '"
								AND d.status = 1
								AND d.entity_id = 13
								AND d.particular_id = "' . $curriculum_id . '"
								AND d.state = ws.state_id ';
        $po_peo_map_result = $this->db->query($po_peo_map_data);
        $po_peo_map_state_data = $po_peo_map_result->result_array();
        return $po_peo_map_state_data;
    }

// End of function fetch_po_peo_map_current_state.	

    /**
     * Function to inserts the comments notes(text) details onto the comment table
     * @parameters: curriculum id and notes(text) statement.
     * @return: boolean
     */
    public function peo_po_comment_save($curriculum_id, $peo_po_comment) {
        $delete_query = 'DELETE FROM comment WHERE crclm_id ="' . $curriculum_id . '" AND entity_id = "13" ';
        $query_result = $this->db->query($delete_query);
        $data_array = array(
            'entity_id' => 13,
            'crclm_id' => $curriculum_id,
            'cmt_statement' => $peo_po_comment,
        );

        $this->db->insert('comment', $data_array);
        return true;
    }

    /* Function is used to fetch the BoS User for POs to PEOs Mapping from peo_po_approver table.
     * @param - curriculum id.
     * @returns- a array value of the BoS details.
     */

    public function fetch_bos_user($curriculum_id) {
        $bos_user_query = 'SELECT a.approver_id, u.title, u.first_name, u.last_name, c.crclm_name
							FROM peo_po_approver AS a, users AS u, curriculum AS c
							WHERE a.crclm_id = "' . $curriculum_id . '" 
							AND a.approver_id = u.id 
							AND c.crclm_id = "' . $curriculum_id . '" ';
        $bos_user = $this->db->query($bos_user_query);
        $bos_user = $bos_user->result_array();
        return $bos_user;
    }

// End of function fetch_bos_user.	

    /* Function is used to fetch the Chairman User for POs to PEOs Mapping from peo_po_approver table.
     * @param - curriculum id.
     * @returns- a array value of the Chairman details.
     */

    public function fetch_chairman_user($curriculum_id) {
        $chairman_user_query = 'SELECT d.dept_hod_id, u.title, u.first_name, u.last_name, c.crclm_name, p.pgm_id
							FROM department AS d, users AS u, curriculum AS c, program AS p
							WHERE c.crclm_id = "' . $curriculum_id . '" 
							AND c.pgm_id = p.pgm_id 
							AND p.dept_id = d.dept_id 
							AND d.dept_hod_id = u.id ';
        $chairman_user = $this->db->query($chairman_user_query);
        $chairman_user = $chairman_user->result_array();
        return $chairman_user;
    }

// End of function fetch_chairman_user.	

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

    public function skip_approval_flag_fetch() {
        return $this->db->select('skip_approval')
                        ->where('entity_id', 13)
                        ->get('entity')
                        ->result_array();
    }

    /* Function to insert work flow history details onto workflow_history table
     * @parameters: curriculum id, state id and user id
     * @return: an array of 
     */

    public function send_for_remap($curriculum_id, $state_id, $user_id) {
        $update = ' UPDATE dashboard 
								SET status = 0 
								WHERE crclm_id = "' . $curriculum_id . '" 
								AND entity_id = 13 
								AND status = 1 ';
        $select_user_id = $this->db->query($update);

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

        $description = 'Mapping between ' . $this->lang->line('sos') . ' and PEOs has to be revisited';
        $url = '#';
        $insert = 'INSERT into dashboard (crclm_id, particular_id, entity_id, sender_id, receiver_id, 
													url, description, state, status) 
							VALUES ("' . $curriculum_id . '", "' . $curriculum_id . '", 13, "' . $user_id . '", "' .
                $receiver_id . '", "' . $url . '", "' . $description . '", "' . $state_id . '", 1) ';
        $insert = $this->db->query($insert);
        $data['receiver_id'] = $receiver_id;
        $data['state'] = $state_id;
        $data['url'] = '#';

        return $data;
    }

    /*     * ***************************************************************************************************************************** */

    /* Function to insert po to peo mapping entry from po_peo_table table.
     * @parameters: curriculum id, po_peo_mapping details
     * @return:a boolean value.
     */

    public function add_po_peo_mapping_new($po_peo_mapping, $curriculum_id, $map_level) {
        $po_peo_array = explode("|", $po_peo_mapping);

        $query = 'select * from po_peo_map where po_id="' . $po_peo_array[0] . '" AND peo_id="' . $po_peo_array[1] . '"';
        $query_re = $this->db->query($query);
        $result = $query_re->result_array();
        $count = count($result);
        if ($count == 0) {
            $add_po_peo_mapping = 'INSERT INTO po_peo_map (peo_id, po_id, crclm_id,map_level,created_by,created_date) 
				VALUES ("' . $po_peo_array[1] . '", "' . $po_peo_array[0] . '", "' . $curriculum_id . '","' . $po_peo_array[2] . '","' . $this->ion_auth->user()->row()->id . '","' . date('Y-m-d') . '")';
            $add_po_peo_mapping = $this->db->query($add_po_peo_mapping);
            return true;
        } else {

            $add_po_peo_mapping = 'UPDATE po_peo_map set  map_level="' . $po_peo_array[2] . '",modified_by="' . $this->ion_auth->user()->row()->id . '", modified_date="' . date('Y-m-d') . '" where peo_id="' . $po_peo_array[1] . '" AND po_id="' . $po_peo_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
            $add_po_peo_mapping = $this->db->query($add_po_peo_mapping);
            return true;
        }
    }

    /* Function to delete po to peo mapping entry from po_peo_table table.
     * @parameters: po id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap_new($po, $curriculum_id) {
        $po_peo_array = explode("|", $po);
        /*         $add_po_peo_mapping = 'UPDATE po_peo_map set  map_level=" ",modified_by="' . $this->ion_auth->user()->row()->id . '", modified_date="' . date('Y-m-d') . '" where peo_id="' . $po_peo_array[1] . '" AND po_id="' . $po_peo_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
          $add_po_peo_mapping = $this->db->query($add_po_peo_mapping); */

        $this->db->where('peo_id', $po_peo_array[0]);
        $this->db->where('po_id', $po_peo_array[1]);
        $this->db->where('crclm_id', $curriculum_id);
        $this->db->delete('po_peo_map');
        return true;

        return true;
    }

    /* Function to fetch mapped data
     * @param:
     * @return:
     */

    public function fetch_map_data($globalid) {
        $po_peo_array = explode("|", $globalid);
        $query = 'select * from po_peo_map where peo_id="' . $po_peo_array[1] . '" AND po_id="' . $po_peo_array[0] . '"';
        $re = $this->db->query($query);
        $result = $re->result_array();
        return ($result);
    }

    /*
     * 	Function to  update justification data
     *
     */
    /*
      public function save_justification($data) {

      $this->db->where('peo_id', $data['peo_id']);
      $this->db->where('po_id', $data['po_id']);
      $this->db->where('crclm_id', $data['crclm_id']);
      $this->db->where('pp_id', $data['pp_id']);
      $result = $this->db->update('po_peo_map', $data);
      } */

    public function save_justification($data) {
        $result = $this->db->query('update po_peo_map set justification ="' . $this->db->escape($data['justification']) . '" where  po_id="' . $data['po_id'] . '" and peo_id="' . $data['peo_id'] . '" and crclm_id ="' . $data['crclm_id'] . '" ');
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    public function fetch_justification($peo_id, $crclm_id, $po_id, $pp_id) {
        $select_comment_data_query = 'SELECT  *
				      FROM po_peo_map 
				      WHERE crclm_id = "' . $crclm_id . '" AND peo_id ="' . $peo_id . '" AND po_id ="' . $po_id . '" AND pp_id="' . $pp_id . '"';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();


        return $comment_data_result;
    }

}
?>
