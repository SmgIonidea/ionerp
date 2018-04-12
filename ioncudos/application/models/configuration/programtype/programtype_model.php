<?php

/**
 * Description	:	Model Logic for Program Type Module (List, Add, Edit, Enable/Disable).
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
 * 27-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  ------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Programtype_model extends CI_Model {
    /* Function is used to fetch all the program type details from program type table.
     * @param - 
     * @returns- a array of values of all the program type details.
     */

    public function program_type_list() {
        $query = 'SELECT pgmtype_id, pgmtype_name, pgmtype_description, status 
					FROM program_type';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
    }

// End of function program_type_list.

    /* Function is used to insert a new program type onto program type table.
     * @param - program type name, program type description.
     * @returns- a boolean value.
     */

    public function program_type_insert($pgmtype_name, $pgmtype_description, $pgm_type) {
        $pgmtype_name = $this->db->escape_str($pgmtype_name);
        $pgmtype_description = $this->db->escape_str($pgmtype_description);
        $query = $this->db->get_where('program_type', array('pgmtype_name' => $pgmtype_name));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $this->db->insert('program_type', array('pgmtype_name' => $pgmtype_name,
            'pgmtype_description' => $pgmtype_description,
            'pgm_type_id' => $pgm_type,
            'created_by' => $loggedin_user_id,
            'create_date' => date('Y-m-d')
                )
        );
        return TRUE;
    }

// End of function program_type_insert.

    /* Function is used to update program type details from program type table.
     * @param - program type id, program type name, program type description.
     * @returns- a boolean value.
     */

    public function program_type_update($pgmtype_id, $pgmtype_name, $pgmtype_description, $pgm_type) {
        $pgmtype_name = $this->db->escape_str($pgmtype_name);
        $pgmtype_description = $this->db->escape_str($pgmtype_description);
        $query = 'SELECT pgmtype_name 
					FROM program_type 
					WHERE pgmtype_name = "' . $pgmtype_name . '" 
					AND pgmtype_id != "' . $pgmtype_id . '" ';
        $query_result = $this->db->query($query);
        // Checks & weather the duplicate entry for same program type name exist with different program type id.
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $date = date('Y-m-d');
            $query = 'UPDATE program_type
			SET pgmtype_name =  "' . $pgmtype_name . '", pgmtype_description = "' . $pgmtype_description . '", pgm_type_id="' . $pgm_type . '",
			modified_by = "' . $loggedin_user_id . '", modify_date = "' . $date . '" 
                        WHERE pgmtype_id = "' . $pgmtype_id . '" ';
            $result = $this->db->query($query);
            return TRUE;
        }
    }

// End of function program_type_update.

    /* Function is used to fetch a program type details from program type table.
     * @param - program type id.
     * @returns- a array of values of a program type.
     */

    public function program_type_search_by_id($pgmtype_id) {
        $query = 'SELECT pgmtype_id, pgmtype_name, pgmtype_description,pgm_type_id 
					FROM program_type 
					WHERE pgmtype_id = "' . $pgmtype_id . '" ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        if ($query_result->num_rows() > 0) {
            return $result;
        }
    }

// End of function program_type_search_by_id.

    /* Function is used to update status of a program type from program type table.
     * @param - program type id & status.
     * @returns- a boolean value.
     */

    public function program_type_update_status($pgmtype_id, $status) {
        if ($status) {
            $update_query = 'UPDATE program_type 
							SET status = 1 
							WHERE pgmtype_id = "' . $pgmtype_id . '" ';
            $update_query_result = $this->db->query($update_query);
        } else {
            $update_query = 'UPDATE program_type 
							SET status = 0 
							WHERE pgmtype_id = "' . $pgmtype_id . '" ';
            $update_query_result = $this->db->query($update_query);
        }
        return $update_query_result;
    }

// End of function program_type_update_status.

    /* Function is used to count no. of rows with a same program type name from program type table.
     * @param - program type name.
     * @returns- a row count value.
     */

    public function add_search_program_type_by_name($pgmtype_name) {
        $query = 'SELECT count(pgmtype_name) as  pgmtype_name_count
					FROM program_type 
					WHERE pgmtype_name = "' . $pgmtype_name . '" ';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['pgmtype_name_count'];
    }

// End of function add_search_program_type_by_name.

    /* Function is used to count no. of rows with a same program type name from program type table.
     * @param - program type name.
     * @returns- a row count value.
     */

    public function program_type_search_by_name($pgmtype_id, $pgmtype_name) {
        $query = 'SELECT count(pgmtype_name) as  pgmtype_name_count
					FROM program_type 
					WHERE pgmtype_name = "' . $pgmtype_name . '" 
					AND pgmtype_id != "' . $pgmtype_id . '"';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['pgmtype_name_count'];
    }

// End of function program_type_search_by_name.

    /* Function is used to count no. of rows with a same program type id from program table.
     * @param - program type id.
     * @returns- a row count value.
     */

    public function program_type_name_is_used($pgmtype_id) {
        $query = 'SELECT count(pgmtype_id) as  pgmtype_id_count
					FROM program 
					WHERE pgmtype_id = "' . $pgmtype_id . '" ';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['pgmtype_id_count'];
    }

// End of function program_type_name_is_used.
    /**
     * Function to fetch pgm_type_list from master table
     * @parameters  :
     * @return      : array
     */
    public function fetch_pgm_type_list() {
        $pgm_type_list_data = $this->db->query('SELECT master_type_id,master_type_name 
                                    FROM master_type
                                    WHERE master_type_id in(42,43,44,45)');
        $pgm_type_list = $pgm_type_list_data->result_array();
        return $pgm_type_list;
    }

}

// End of Class Programtype_model.
?>