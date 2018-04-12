<?php

/**
 * Description          :	PEO grid displays the PEO statements and PEO type.
  PEO statements can be deleted individually and can be edited in bulk.
  Notes can be added, edited or viewed.
  PEO statements will be published for final approval.
 * 					
 * Created		:	02-04-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *    Date                  Modified By                	     Description
 * 05-09-2013		   Arihant Prasad               File header, function headers, indentation and comments.
 *
  ---------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Add_peo_model extends CI_Model {

    /**
     * Function to fetch group id and group name
     * @return: group id, group name
     */
    public function peo_list() {
        return $this->db->select('id, name')->get('groups')->result_array();
    }

    /**
     * Function to insert program educational objective statement, entity id, attendees, notes,
      curriculum id and program educational objective type into their respective tables. Also
      update dashboard and work flow table
     * @parameters: program educational objective statement, entity id, attendees, notes,
      curriculum id and program educational objective type.
     * @return: boolean
     */
    public function insert($peo_statement, $peo_reference, $entity_id, $attendees, $notes, $curriculum_id) {
        $temp = $this->attendees_data($curriculum_id);
        $size_of_attendees = sizeof($temp);
        $this->db->trans_start();
        $peo_statement_size = sizeof($peo_statement);

        for ($k = 0; $k < $peo_statement_size; $k++) {
            $peo_data = array(
                'crclm_id' => $curriculum_id,
                'peo_statement' => $peo_statement[$k],
                'peo_reference' => $this->db->escape_str($peo_reference[$k]),
                'state_id' => '2',
                'created_by' => $this->ion_auth->user()->row()->id,
                'created_date' => date('Y-m-d'));
            $check = $this->db->insert('peo', $peo_data);
        }

        $attendees_data = array(
            'crclm_id' => $curriculum_id,
            'attendees_name' => $attendees,
            'attendees_notes' => $notes,
            'created_by' => $this->ion_auth->user()->row()->id,
            'created_date' => date('Y-m-d'),
        );

        if ($size_of_attendees == 0) {
            $this->db->insert('attendees_notes', $attendees_data);
        } else {
            $this->db->where('crclm_id', $curriculum_id)
                    ->update('attendees_notes', $attendees_data);
        }

        $count = $this->db->select('dashboard_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('particular_id', $curriculum_id)
                        ->where('entity_id', '5')
                        ->where('state', 2)
                        ->where('status', '1')
                        ->get('dashboard')->num_rows();

        if ($count == 0) {
            $update_dashboard_data = array(
                'status' => '0'
            );
            $this->db->where('crclm_id', $curriculum_id)
                    ->where('entity_id', 5)
                    ->where('particular_id', $curriculum_id)
                    ->where('state', 1)
                    ->where('status', 1)
                    ->update('dashboard', $update_dashboard_data
            );
            $dashboard_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '5',
                'particular_id' => $curriculum_id,
                'state' => '2',
                'status' => '1',
                'sender_id' => $this->ion_auth->user()->row()->id,
                'receiver_id' => $this->ion_auth->user()->row()->id,
                'url' => '#',
                'description' => 'PEOs creation in-progress, release to creation of POs is pending.'
            );
            $this->db->insert('dashboard', $dashboard_data);
        }

        $count = $this->db->select('history_id')
                        ->where('crclm_id', $curriculum_id)
                        ->where('entity_id', '6')
                        ->where('state_id', '7')
                        ->get('workflow_history')->num_rows();

        if ($count == 0) {
            $workflow_data = array(
                'crclm_id' => $curriculum_id,
                'entity_id' => '5',
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
     * Function to update existing program educational statements
     * @parameters: curriculum id, program educational objective id, program educational objective statement, program educational
      objective type, attendees name, attendees notes, program educational objective new statement, new program educational
      objective name
     * @return: boolean
     */
    public function edit_peo($curriculum_id, $peo_id, $peo_statement, $attendees_name, $attendees_notes, $peo_reference) {
        $this->db->trans_start();
        $present_peo_id = $this->db->select('peo_id')
                        ->where('crclm_id', $curriculum_id)
                        ->get('peo')->result_array();


        foreach ($present_peo_id as $current_peo_id) {
            $present_peo_id_array[] = $current_peo_id['peo_id'];
        }

        $delete_peo_array = array_values(array_diff($present_peo_id_array, $peo_id));
        $peo_statement_size = sizeof($peo_statement);

        for ($k = 0; $k < $peo_statement_size; $k++) {
            if (!empty($peo_id[$k])) {
                $peo_data = array(
                    'peo_statement' => $peo_statement[$k],
                    'peo_reference' => $peo_reference[$k]
                );
                $this->db->where('peo_id', $peo_id[$k])
                        ->update('peo', $peo_data);
            } else {
                $peo_data = array(
                    'crclm_id' => $curriculum_id,
                    'peo_statement' => $peo_statement[$k],
                    'peo_reference' => $peo_reference[$k]
                );

                $this->db->insert('peo', $peo_data);
            }
        }

        $attendees_data = array(
            'attendees_name' => $attendees_name,
            'attendees_notes' => $attendees_notes,
        );

        $this->db->where('crclm_id', $curriculum_id)
                ->update('attendees_notes', $attendees_data);
        $peo_array_size = sizeof($delete_peo_array);

        for ($k = 0; $k < $peo_array_size; $k++) {
            $this->db->where('peo_id', $delete_peo_array[$k])
                    ->delete('peo');
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function to fetch existing details from the program educational objective table, attendees notes table and 
      program educational objective type table for editing
     * @parameter: curriculum_id
     * @return: curriculum id, program educational objective id, program educational objective statement, program educational
      objective statement, attendees name, attendees notes
     */
    public function peo_edit($curriculum_id) {
        $peo_data = 'SELECT p.crclm_id, p.peo_reference, p.peo_id, p.peo_statement, a.attendees_name, a.attendees_notes
					 FROM peo AS p, attendees_notes AS a
					 WHERE p.crclm_id = a.crclm_id AND p.crclm_id = "' . $curriculum_id . '"';

        $peo_data_result = $this->db->query($peo_data);
        $peo_data = $peo_data_result->result_array();

        return $peo_data;
    }

    /**
     * Function to fetch curriculum id from the program educational objective table
     * @return: curriculum id
     */
    public function peo_fetch() {
        $curriculum_id = 'SELECT crclm_id 
						  FROM peo';
        $curriculum_id_result = $this->db->query($curriculum_id);
        $curriculum_id_data = $curriculum_id_result->result_array();
        $peo_fetch_return['crclm_id_data'] = $curriculum_id_data;

        return $peo_fetch_return;
    }

    /**
     * Function	to fetch curriculum details from the curriculum table
     * @return: curriculum id and curriculum name
     */
    public function curriculum_name_data() {
        $curriculum_fetch_data = 'SELECT crclm_id, crclm_name 
								  FROM curriculum
								  ORDER BY crclm_name ASC';
        $curriculum_fetch_data = $this->db->query($curriculum_fetch_data);
        $curriculum_data = $curriculum_fetch_data->result_array();

        return $curriculum_data;
    }

    /**
     * Function to fetch attendees name, attendees notes and curriculum id from attendees notes table
     * @parameters: curriculum id
     * @return: attendees name, attendees notes and curriculum id
     */
    public function attendees_data($curriculum_id) {
        $attendees_data = 'SELECT attendees_name, crclm_id, attendees_notes 
						   FROM attendees_notes 
						   WHERE crclm_id = "' . $curriculum_id . '"';
        $attendees_data = $this->db->query($attendees_data);
        $attendees_data = $attendees_data->result_array();

        return $attendees_data;
    }

    public function search_statement($peo_id, $crclm_id, $peo_ref, $peo_data) {
        $search_query = 'SELECT peo_statement FROM peo WHERE crclm_id = ' . $crclm_id . ' and peo_id != ' . $peo_id . ' and peo_statement like "' . $peo_data . '"';
        $search_data = $this->db->query($search_query);
        $count = $search_data->num_rows();

        if ($count == 1) {
            return $count;
        } else {
            return 0;
        }
    }

    public function statement_search($crclm_id, $peo_data) {
        $search_query = 'SELECT peo_statement FROM peo WHERE crclm_id = ' . $crclm_id . ' and peo_statement like "' . $peo_data . '"';
        $search_data = $this->db->query($search_query);
        $count = $search_data->num_rows();

        if ($count == 1) {
            return $count;
        } else {
            return 0;
        }
    }

}

/* End of file peo_model.php */
/* Location: ./controllers/peo/add_peo_model.php */
?>
