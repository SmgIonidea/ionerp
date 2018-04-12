<?php
/**
* Description	:	Model Logic for Program Mode Module (List, Add, Edit & Delete).
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 21-08-2013		Abhinay B.Angadi        Added file headers, public function headers, indentations & comments.
* 28-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
------------------------------------------------------------------------------------------------
*/
?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Program_mode_model extends CI_Model {

	/* Function is used to fetch all the program mode details from program mode table.
	* @param - 
	* @returns- a array of values of all the program mode details.
	*/    
	public function program_mode_list() {
        $query = ' SELECT p.mode_id, p.mode_name, mode_description,(SELECT count(mode_id) FROM program WHERE program.mode_id = p.mode_id) as is_program_mode FROM program_mode AS p ';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
    }// End of function program_mode_list.

	/* Function is used to insert a new program mode onto program mode table.
	* @param - program mode name.
	* @returns- a boolean value.
	*/    
    public function program_mode_insert($program_mode_name,$program_mode_description) {
		$program_mode_name = $this->db->escape_str($program_mode_name);
		$program_mode_description = $this->db->escape_str($program_mode_description);
        $query = $this->db->get_where('program_mode', array('mode_name' => $program_mode_name));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('program_mode', array(
            'mode_name' => $program_mode_name,
			'mode_description' => $program_mode_description,
            'created_by' => $created_by,
            'created_date' => $created_date,
            ));
        return TRUE;
    }// End of function program_mode_insert.

	/* Function is used to update program mode details from program mode table.
	* @param - program mode id, program mode name.
	* @returns- a boolean value.
	*/    
	public function program_mode_update($program_mode_id, $program_mode_name,$program_mode_description) {
		$program_mode_name = $this->db->escape_str($program_mode_name);
		$program_mode_description = $this->db->escape_str($program_mode_description);
		$query = 'SELECT mode_id 
					FROM program_mode 
					WHERE mode_name = "'.$program_mode_name.'" 
					AND mode_id != "'.$program_mode_id.'" ';
        $query_result = $this->db->query($query);
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = 'UPDATE program_mode
						SET mode_name = "'.$program_mode_name.'", mode_description = "'.$program_mode_description.'", modified_by = "'.$modified_by.'", 
							modified_date = "'.$modified_date.'" 
						WHERE mode_id = "'.$program_mode_id.'" ';
            $result = $this->db->query($query);
            return $result;
        }
    }// End of function program_mode_update.

	/* Function is used to fetch a program mode details FROM program mode table.
	* @param - program mode id.
	* @returns- a array of values of a program mode.
	*/    
	public function program_mode_search($program_mode_id) {
		$query = 'SELECT mode_id, mode_name, mode_description 
					FROM program_mode 
					WHERE mode_id = "'.$program_mode_id.'" ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        if ($query_result->num_rows() > 0) {
            return $result;
        }
    }// End of function program_mode_search.

	/* Function is used to delete a program mode from program mode table.
	* @param - program mode id.
	* @returns- a boolean value.
	*/	    
	public function program_mode_delete($program_mode_id) {
        $query = 'DELETE FROM program_mode 
					WHERE mode_id = "'.$program_mode_id.'" ';
        $result = $this->db->query($query);
        return $result;
    }// End of function program_mode_delete.

	/* Function is used to count no. of rows with a same program mode name from program mode table.
	* @param - program mode name.
	* @returns- a row count value.
	*/    
	
	public function program_mode_name_search($program_mode_name) {
        $query = 'SELECT count(mode_name) as program_mode_name_count
					FROM program_mode 
					WHERE mode_name = "'.$program_mode_name.'"';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return ($data['program_mode_name_count']);
    }// End of function program_mode_name_search.
	
	public function edit_program_mode_name_search($mode_id, $program_mode_name) {
        $query = 'SELECT count(mode_name) as program_mode_name_count
					FROM program_mode 
					WHERE mode_name = "'.$program_mode_name.'" 
					AND mode_id != "'.$mode_id.'"';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return ($data['program_mode_name_count']);
    }// End of function program_mode_name_search.

}// End of Class Program_mode_model.
?>