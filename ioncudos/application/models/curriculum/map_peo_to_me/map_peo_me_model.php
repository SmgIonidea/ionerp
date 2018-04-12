<?php
/**

/**
* Description	:	Model(Database) Logic for PEOs to MEs Mapping Module.
* Created		:	22-12-2014 
* Modification History:
* Date				Modified By				Description
* 23-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/?>

<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map_peo_me_model extends CI_Model {

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

        $mapped_peo_me = 'SELECT pm_id, peo_id, me_id, dept_id 
							FROM peo_me_map 
							WHERE crclm_id = "' . $curriculum_id . '" AND dept_id="'. $dept_id.'"' ;
        $mapped_peo_me = $this->db->query($mapped_peo_me);
        $mapped_peo_me = $mapped_peo_me->result_array();
        $data['mapped_peo_me'] = $mapped_peo_me;
        return $data;
    }
	
	
	 /* Function to fetch curriculum details from curriculum table 
     * @return: an array of curriculum details.
     */
    public function list_curriculum() {
		$logged_in_user_id = $this->ion_auth->user()->row()->id;
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
							FROM curriculum AS c
							ORDER BY c.crclm_name ASC ';
							//AND d.receiver_id = "'.$logged_in_user_id.'" ';
        $curriculum_list = $this->db->query($curriculum_list);
        $curriculum_list = $curriculum_list->result_array();
        return $curriculum_list;
    }
    /**
     * Function to fetch curriculum details from curriculum table
     * @parameters: user id
     * @return: an array of curriculum details.
     */
    public function list_department_curriculum($user_id) {
        $curriculum_list = 'SELECT DISTINCT c.crclm_id, c.crclm_name 
								FROM curriculum AS c, users AS u, program AS p, dashboard AS d 
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
            echo(json_encode($temp));
        }
    }



    /* Function to insert po to peo mapping entry from po_peo_table table.
     * @parameters: curriculum id, peo_me_mapping details
     * @return:a boolean value.
     */
    public function add_peo_me_mapping($peo_me_mapping, $curriculum_id, $dept_id) {
        $peo_me_array = explode("|", $peo_me_mapping);
		//var_dump($peo_me_array);exit;
        $add_peo_me_mapping = 'INSERT INTO peo_me_map (peo_id, me_id, dept_id, crclm_id) 
								VALUES ("' . $peo_me_array[1] . '", "' . $peo_me_array[0] . '", "' . $dept_id . '","' . $curriculum_id . '")';
        $add_peo_me_mapping = $this->db->query($add_peo_me_mapping);
		//var_dump($add_peo_me_mapping);exit;
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


	
	public function curriculum_dept($crclm_id){
		return $this->db->select('program.dept_id')
						->join('program','program.pgm_id = curriculum.pgm_id')
						->where('crclm_id',$crclm_id)
						->get('curriculum')
						->result_array();
	
	}
}
?>