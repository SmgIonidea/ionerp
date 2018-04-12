<?php

/**
 * Description		:	PEO grid displays the PEO statements and PEO type.
                                PEO statements can be deleted individually and can be edited in bulk.
                                Notes can be added, edited or viewed.
                                PEO statements will be published for final approval.
 * 					
 * Created		:	01-03-2013
 *
 * Author		:	
 * 		  
 * Modification History :
 *    Date			    Modified By                			Description
 *  05-09-2013		   	 Arihant Prasad				File header, function headers, indentation and comments.
 *  18/01/2016			 Bhagyalaxmi S S			Modified Peo_list function and added update_peo function.
 *  21-04-2016			 Bhagyalaxmi S S			Addedd map_level weightage to the peo to me mapping. 
 *  06-15-2015			 Bhagyalaxmi S S			Added justification for each mapping.	
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Peo_model extends CI_Model {

    /**
     * Function to fetch curriculum details for admin or for other logged in user
     * @return: curriculum id and curriculum name
     */
    public function peo_curriculum_index() {
        if ($this->ion_auth->is_admin()) {
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name
				FROM curriculum AS c, dashboard AS d
				WHERE d.entity_id = 5
				AND c.crclm_id = d.crclm_id
				AND d.status = 1
				AND c.status = 1
				GROUP BY c.crclm_id
				ORDER BY c.crclm_name ASC';
        } else {
            $dept_id = $this->ion_auth->user()->row()->user_dept_id;
            $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, program AS p, dashboard AS d
								WHERE p.dept_id = "' . $dept_id . '" 
									AND p.pgm_id = c.pgm_id
									AND c.crclm_id = d.crclm_id
									AND d.entity_id = 5
									AND c.status = 1 
									GROUP BY c.crclm_id
									ORDER BY c.crclm_name ASC';
        }

        $curriculum_list_data = $this->db->query($curriculum_list);
        $curriculum_list_result = $curriculum_list_data->result_array();
        $curriculum_fetch_data['result_curriculum_list'] = $curriculum_list_result;

        return $curriculum_fetch_data;
    }

    /**
     * Function to fetch program educational objective details and its count
     * @parameters: curriculum id
     * @return: program educational objective id, program educational objective statement, 
      program educational objective type name
     */
    public function peo_list($curriculum_id) {
        $peo_list = 'SELECT p.peo_reference,p.peo_statement, p.peo_id, atten.attendees_name,atten.attendees_notes,
					 (SELECT count(peo_id)
					 FROM po_peo_map 
					 WHERE peo_id = p.peo_id)cur_peo
					 FROM peo AS p
					JOIN attendees_notes AS atten ON p.crclm_id = atten.crclm_id
					 WHERE p.crclm_id = "' . $curriculum_id . '"';

        $peo_list_result = $this->db->query($peo_list);
        $peo_list_data = $peo_list_result->result_array();

        $peo_count = $this->db->select('COUNT(peo_id) as count', FALSE)
                ->from('peo');
        $peo_count_result = $peo_count->get()->result();
        $peo_list_return['num_rows'] = $peo_count_result[0]->count;

        if (!empty($peo_list_data)) {
            $peo_list_return['peo_list_data'] = $peo_list_data;
        } else {
            $peo_list_return['peo_list_data'] = 0;
        }

        $peo_data = 'SELECT ws.state_id, ws.status as state_name, d.state
					 FROM  workflow_state AS ws, dashboard AS d 
					 WHERE d.crclm_id = "' . $curriculum_id . '"
						AND d.status = 1
						AND d.entity_id = 5
						AND d.particular_id = "' . $curriculum_id . '"
						AND d.state = ws.state_id ';
        $peo_result = $this->db->query($peo_data);
        $peo_state_data = $peo_result->result_array();

        if (!empty($peo_state_data)) {
            $peo_list_return['peo_state'] = $peo_state_data;
        } else {
            $peo_list_return['peo_state'] = 0;
        }

        return $peo_list_return;
    }

    /**
     * Function to delete program educational objective details from program educational 
      objectives table
     * @parameters: program educational objective id
     */
    public function peo_delete($peo_id) {
        $peo_delete = 'DELETE FROM peo 
					   WHERE peo_id = "' . $peo_id . '"';
        $this->db->query($peo_delete);
    }

    /**
     * Function to fetch help related details for program educational objectives, which is
      used to provide link to help page
     * @return: serial number (help id), entity data, help description, help entity id and file path
     */
    public function peo_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 5';
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
     * Function to fetch help related to program educational objectives to display
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

    /**
     * Function to insert the details into dashboard and workflow history
     * @parameters: curriculum id
     * @return: curriculum id, entity id, particular id, state, status, sender id,
      receiver id, url/path and description
     */
    public function approve_publish_db($curriculum_id) {
        $update_query = 'UPDATE dashboard
						 SET status = 0
						 WHERE crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 2
							AND particular_id = "' . $curriculum_id . '"
							AND status = 1
							AND state = 2';
        $update_data = $this->db->query($update_query);

        $update_query = 'UPDATE dashboard
						 SET status = 0
						 WHERE crclm_id = "' . $curriculum_id . '" 
							AND entity_id = 5
							AND particular_id = "' . $curriculum_id . '"
							AND status = 1';
        $update_data = $this->db->query($update_query);

        $chairman_details = 'SELECT d.dept_hod_id, c.pgm_id, p.dept_id, u.username, u.first_name, u.last_name 
							 FROM curriculum AS c, users AS u, program as p, department as d
							 WHERE c.crclm_id = "' . $curriculum_id . '" 
								AND u.id = d.dept_hod_id
								AND p.dept_id = d.dept_id
								AND c.pgm_id = p.pgm_id';
        $chairman_data = $this->db->query($chairman_details);
        $chairman = $chairman_data->result_array();

        $receiver_id = $chairman[0]['dept_hod_id'];

        $count = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('particular_id', $curriculum_id)
                        ->where('entity_id', '5')
                        ->where('status', '1')
                        ->where('state', '7')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {
            $url = base_url('curriculum/po/add_po') . '/' . $curriculum_id;
            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '5',
                'particular_id' => $curriculum_id,
                'state' => '7',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'url' => $url,
                'description' => 'Program Educations Objectives(PEOs) are Approved.'
            );
            $this->db->insert('dashboard', $dashboard_data);
            $update_dashboard_data = array(
                'status' => '0',
            );
            $this->db
                    ->where('crclm_id', $curriculum_id)
                    ->where('entity_id', 5)
                    ->where('particular_id', $curriculum_id)
                    ->where('state', 2)
                    ->update('dashboard', $update_dashboard_data);
        }

        $count_next = $this->db
                        ->select('dashboard_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('particular_id', $curriculum_id)
                        ->where('entity_id', '6')
                        ->where('status', '1')
                        ->where('state', '1')
                        ->get('dashboard')->num_rows();

        if ($count_next == 0) {
            $url = base_url('curriculum/po/add_po/' . $curriculum_id);
            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '6',
                'particular_id' => $curriculum_id,
                'state' => '1',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $receiver_id,
                'url' => $url,
                'description' => 'PEOs are approved, proceed with creation of POs.'
            );
            $this->db->insert('dashboard', $dashboard_data);

            $count = $this->db
                            ->select('history_id')
                            ->where('crclm_id', $curriculum_id)
                            ->where('entity_id', '5')
                            ->where('state_id', '7')
                            ->get('workflow_history')->num_rows();

            if ($count == 0) {
                $workflow_data = array(
                    'crclm_id' => $curriculum_id,
                    'entity_id' => '5',
                    'state_id' => '7'
                );
                $this->db->insert('workflow_history', $workflow_data);
            }
        }

        return $dashboard_data;
    }

    /* Function is used to fetch the current state of PEOs from dashboard table..
     * @param - curriculum id.
     * @returns- a array value of the current state details.
     */

    public function fetch_peo_current_state($curriculum_id) {
        $peo_data = 'SELECT ws.state_id, ws.status as state_name, d.state
						FROM  workflow_state AS ws, dashboard AS d 
						WHERE d.crclm_id = "' . $curriculum_id . '"
						AND d.status = 1
						AND d.entity_id = 5
						AND d.particular_id = "' . $curriculum_id . '"
						AND d.state = ws.state_id ';
        $peo_result = $this->db->query($peo_data);
        $peo_state_data = $peo_result->result_array();
        return $peo_state_data;
    }

// End of function fetch_peo_current_state.

    /**
     * Function to fetch user details from 
     * @parameters: curriculum id
     * @return: bos user name
     */
    public function chairman_user($curriculum_id) {
        $bos_user_query = 'SELECT u.title, u.first_name, u.last_name 
						   FROM curriculum AS c, users AS u, program as p, department as d
						   WHERE c.crclm_id = "' . $curriculum_id . '" 
							  AND u.id = d.dept_hod_id
							  AND p.dept_id = d.dept_id
							  AND c.pgm_id = p.pgm_id';
        $bos_user = $this->db->query($bos_user_query);
        $bos_user = $bos_user->result_array();

        return $bos_user;
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

    /* Function to fetch me details, peo details, peo_me mapping details from me, peo and peo_me_map tables.
     * @parameters: curriculum id, dept_id
     * @return: an array of me, peo, peo_me_map details.
     */

    public function map_peo_me($curriculum_id, $dept_id) {
        $me = 'SELECT dept_me_map_id,dept_me
				FROM dept_mission_element_map 
				WHERE dept_id = "' . $dept_id . '" ';
        $me_list = $this->db->query($me);
        $number_of_me = $me_list->num_rows();
        $me_list = $me_list->result_array();
        $data['me_list'] = $me_list;
        $data['number_of_me'] = $number_of_me;
        $peo = 'SELECT peo_id, peo_statement 
				FROM peo 
				WHERE crclm_id = "' . $curriculum_id . '" ';
        $peo_list = $this->db->query($peo);
        $number_of_peo = $peo_list->num_rows();
        $peo_list = $peo_list->result_array();
        $data['peo_list'] = $peo_list;
        $data['number_of_peo'] = $number_of_peo;

        $mapped_peo_me = 'SELECT * 
							FROM peo_me_map 
							WHERE crclm_id = "' . $curriculum_id . '" AND dept_id="' . $dept_id . '"';
        $mapped_peo_me = $this->db->query($mapped_peo_me);
        $mapped_peo_me = $mapped_peo_me->result_array();

        $weightage_data = 'select * from map_level_weightage where status=1';
        $weightage_data = $this->db->query($weightage_data);
        $weightage_data = $weightage_data->result_array();
        $data['weightage_data'] = $weightage_data;

        $peo_me_notes_query = 'SELECT notes, notes_id FROM notes WHERE crclm_id ="' . $curriculum_id . '" AND entity_id =27';
        $peo_me_notes_data = $this->db->query($peo_me_notes_query);
        $peo_me_notes_result = $peo_me_notes_data->result_array();
        $data['peo_me_notes_data'] = $peo_me_notes_result;

        $data['mapped_peo_me'] = $mapped_peo_me;

        $query = $this->db->query('select indv_mapping_justify_flag from organisation');
        $data['indv_mappig_just'] = $query->result_array();
        return $data;
    }

    /* Function to insert po to peo mapping entry from po_peo_table table.
     * @parameters: curriculum id, peo_me_mapping details
     * @return:a boolean value.
     */

    public function add_peo_me_mapping($peo_me_mapping, $curriculum_id, $dept_id) {
        $peo_me_array = explode("|", $peo_me_mapping);
        $add_peo_me_mapping = 'INSERT INTO peo_me_map (peo_id, me_id, dept_id, crclm_id, created_by, created_date) 
								VALUES ("' . $peo_me_array[1] . '", "' . $peo_me_array[0] . '", "' . $dept_id . '","' . $curriculum_id . '","' . $this->ion_auth->user()->row()->id . '","' . date('Y-m-d') . '")';
        $add_peo_me_mapping = $this->db->query($add_peo_me_mapping);
        return true;
    }

    /* Function to delete peo to me mapping entry from peo_me_table table.
     * @parameters: me id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap($me, $curriculum_id, $dept_id) {
        $peo_me_array = explode("|", $me);
        $delete_peo_me_mapping = 'DELETE FROM peo_me_map 
								  WHERE peo_id = "' . $peo_me_array[1] . '" 
									AND me_id = "' . $peo_me_array[0] . '" 
									AND crclm_id = "' . $curriculum_id . '"
									AND dept_id = "' . $dept_id . '"';
        $curriculum_list = $this->db->query($delete_peo_me_mapping);
        return true;
    }

    public function update_peo($id, $crclm, $peo_ref, $peo_stmt, $attendees_notes, $attendees_name) {
        $query = 'select * from peo where peo_reference = "' . $peo_ref . '" and crclm_id = "' . $crclm . '" AND peo_id = "' . $id . '"';
        $num = $this->db->query($query);
        $re = $num->result_array();
        $num_n = $num->num_rows();
        if ($num_n < 2 || $re[0]['peo_reference'] == $peo_ref) {
            $update_query = 'UPDATE peo
						 SET peo_reference = "' . $peo_ref . '", peo_statement="' . $this->db->escape_str($peo_stmt) . '",modified_by="' . $this->ion_auth->user()->row()->id . '",modify_date="' . date('Y-m-d') . '"
						 WHERE crclm_id = "' . $crclm . '" 
							AND peo_id = "' . $id . '"';
            $update_attendees = 'UPDATE attendees_notes SET 
								attendees_name="' . $attendees_name . '",attendees_notes="' . $attendees_notes . '",modified_date="' . date('Y-m-d') . '",modified_by="' . $this->ion_auth->user()->row()->id . '"
								WHERE crclm_id="' . $crclm . '"';
            $update_data = $this->db->query($update_query);
            return $update = $this->db->query($update_attendees);
        } else {
            return false;
        }
    }

    /* Function to fetch the state from the dashboard whose status is 1
     * @parameters: curriculum id
     * @return: an array of dashboard details.
     */

    public function get_state($curriculum_id) {
        $get_state = 'SELECT count(state), state 
						FROM dashboard 
						WHERE status = 1 
						AND crclm_id = "' . $curriculum_id . '" 
						AND entity_id = 27 
						GROUP BY state';
        $get_state = $this->db->query($get_state);
        $get_state = $get_state->result_array();

        $data['state_id'] = $get_state;
        return $data;
    }

    /*     * Function to add mapping level weighatage to peo_me mapping* */

    public function add_peo_me_mapping_new($data, $curriculum_id) {

        $peo_me_array = explode("|", $data);
        $query = 'select * from peo_me_map where me_id="' . $peo_me_array[0] . '" AND peo_id="' . $peo_me_array[1] . '"';
        $query_re = $this->db->query($query);
        $result = $query_re->result_array();
        $count = count($result);

        $dept_id = 'SELECT d.dept_id FROM curriculum c 
				   join program as p ON p.pgm_id = c.pgm_id
				   join department as d ON d.dept_id = p.dept_id
                   where crclm_id="' . $curriculum_id . '"';
        $dept_id = $this->db->query($dept_id);
        $deptid_re = $dept_id->result_array();
        if ($count == 0) {
            $add_peo_me_mapping = 'INSERT INTO peo_me_map (peo_id, me_id, crclm_id,map_level,dept_id,created_by,created_date) 
								VALUES ("' . $peo_me_array[1] . '", "' . $peo_me_array[0] . '", "' . $curriculum_id . '","' . $peo_me_array[2] . '","' . $deptid_re[0]['dept_id'] . '","' . $this->ion_auth->user()->row()->id . '","' . date('Y-m-d') . '")';
            $add_peo_me_mapping = $this->db->query($add_peo_me_mapping);
            return true;
        } else {

            $add_peo_me_mapping = 'UPDATE peo_me_map set  map_level="' . $peo_me_array[2] . '" where peo_id="' . $peo_me_array[1] . '" AND me_id="' . $peo_me_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
            $add_peo_me_mapping = $this->db->query($add_peo_me_mapping);
            return true;
        }
    }

    public function fetch_dept($curriculum_id) {
        $dept_id = 'SELECT d.dept_id FROM curriculum c 
				   join program as p ON p.pgm_id = c.pgm_id
				   join department as d ON d.dept_id = p.dept_id
                   where crclm_id="' . $curriculum_id . '"';
        $dept_id = $this->db->query($dept_id);
        return $deptid_re = $dept_id->result_array();
    }

    /*
     * 	Function to  update justification data
     *
     */

    public function save_justification($data) {
        $this->db->where('peo_id', $data['peo_id']);
        $this->db->where('me_id', $data['me_id']);
        $this->db->where('crclm_id', $data['crclm_id']);
        $this->db->where('pm_id', $data['pm_id']);
        $result = $this->db->update('peo_me_map', $data);
    }

    /* Function to delete po to peo mapping entry from po_peo_table table.
     * @parameters: po id and curriculum id .
     * @return: a boolean value.
     */

    public function unmap_new($peo, $curriculum_id) {
        $peo_me_array = explode("|", $peo);
        /* $add_peo_me_mapping = 'UPDATE peo_me_map set  map_level=" " where peo_id="' . $peo_me_array[1] . '" AND me_id="' . $peo_me_array[0] . '" AND crclm_id="' . $curriculum_id . '"';
          $add_peo_me_mapping = $this->db->query($add_peo_me_mapping);
          return true; */
        $this->db->where('peo_id', $peo_me_array[1]);
        $this->db->where('me_id', $peo_me_array[0]);
        $this->db->where('crclm_id', $curriculum_id);
        $this->db->delete('peo_me_map');
        return true;
    }

    /**
     * Function to inserts the comments notes(text) details onto the notes table
     * @parameters: curriculum id and notes(text) statement.
     * @return: boolean
     */
    public function save_notes_in_database($curriculum_id, $text) {
        $query = ' SELECT COUNT(notes_id) 
						FROM notes
						WHERE crclm_id = "' . $curriculum_id . '" 
						AND entity_id = 27 
						';
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
								entity_id = 27 
							WHERE crclm_id = "' . $curriculum_id . '"';
                $query = $this->db->query($query);
                $success = 1;
                //break;
            }
        }
        if ($temp == 0 || $success == 0) {
            $data = array(
                'notes' => $text,
                'crclm_id' => $curriculum_id,
                'entity_id' => 27
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
					AND entity_id = 27 ';
        $notes = $this->db->query($notes);
        $notes = $notes->result_array();
        if (!empty($notes[0]['notes'])) {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode($notes[0]['notes']));
        } else {
            header('Content-Type: application/x-json; charset = utf-8');
            echo(json_encode("Enter text here "));
        }
    }

    /**
     * Function to update awards
     * @parameters:
     * @return: 
     */
    public function save_comment_peo_me($data) {
        $this->db->where('pm_id', $data['pm_id']);
        $this->db->where('peo_id', $data['peo_id']);
        $this->db->where('me_id', $data['me_id']);
        $this->db->where('crclm_id', $data['crclm_id']);
        $result = $this->db->update('peo_me_map', $data);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    public function fetch_justification($peo_id, $crclm_id, $me_id, $pm_id) {
        $select_comment_data_query = 'SELECT  *
				      FROM peo_me_map 
				      WHERE crclm_id = "' . $crclm_id . '" AND peo_id ="' . $peo_id . '" AND me_id ="' . $me_id . '" AND pm_id="' . $pm_id . '" ';
        $comment_data = $this->db->query($select_comment_data_query);
        $comment_data_result = $comment_data->result_array();


        return $comment_data_result;
    }

}

/* End of file peo_model.php */
/* Location: ./controllers/peo/peo_model.php */
?>
