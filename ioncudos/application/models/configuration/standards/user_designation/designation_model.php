<?php
/**
* Description	:	Model Logic for User Designation Module (List, Add, Edit & Delete).
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 22-08-2013		Abhinay B.Angadi        Added file headers, function headers, indentations & comments.
* 29-08-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
* 26-03-2014		Jevi V. G				Added description field for designation
------------------------------------------------------------------------------------------------
*/
?>
<?php if (!defined('BASEPATH'))    exit('No direct script access allowed');

class Designation_model extends CI_Model {

	/* Function is used to fetch all the user designation details from user designation table.
	* @param - 
	* @returns- a array of values of all the user designation details.
	*/     
	function designation_list() {
        $query = 'SELECT designation_id,designation_name,designation_description, (SELECT count(designation_id) FROM users WHERE users.designation_id=user_designation.designation_id) as is_designation
					FROM user_designation';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
    }// End of function designation_list.
	
	/* Function is used to insert a new user designation onto user designation table.
	* @param - user designation name.
	* @returns- a boolean value.
	*/     
    function designation_insert($designation_name, $designation_description) {
		$designation_name = $this->db->escape_str($designation_name);
		$designation_description = $this->db->escape_str($designation_description);
        $query = $this->db->get_where('user_designation', array('designation_name' => $designation_name, 'designation_description' => $designation_description));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('user_designation', array(
						'designation_name' 	=> $designation_name,
						'designation_description' => $designation_description,
						'created_by' 		=> $created_by,
						'created_date'		=> $created_date,
                        ));
        return TRUE;
    }// End of function designation_insert.

	/* Function is used to update user designation details from user designation table.
	* @param - user designation id, user designation name.
	* @returns- a boolean value.
	*/    
	function designation_update($designation_id, $designation_name, $designation_description) {
		$designation_name = $this->db->escape_str($designation_name);
		$designation_description = $this->db->escape_str($designation_description);
		$query = ' SELECT designation_id 
					FROM user_designation 
					WHERE designation_name = "'.$designation_name.'" 
					AND designation_id != "'.$designation_id.'" ';
        $query_result = $this->db->query($query);
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = ' UPDATE user_designation
						SET designation_name = "'.$designation_name.'", designation_description = "'.$designation_description.'", modified_by = "'.$modified_by.'", 
							modified_date = "'.$modified_date.'" 
						WHERE designation_id = "'.$designation_id.'" ';
            $result = $this->db->query($query);
            return $result;
        }
    }// End of function designation_update.
	
	/* Function is used to fetch a user designation details from user designation table.
	* @param - user designation id.
	* @returns- a array of values of a user designation.
	*/    
	function search_designation($designation_id) {
        $query = ' SELECT designation_id, designation_name, designation_description
					FROM user_designation 
					WHERE designation_id = "'.$designation_id.'" ';
		$query_result = $this->db->query($query);
        $result = $query_result->result_array();
        if ($query_result->num_rows() > 0) {
			return $result;
        }
    }// End of function search_designation.

	/* Function is used to delete a user designation from user designation table.
	* @param - user designation id.
	* @returns- a boolean value.
	*/    
	function designation_delete($designation_id) {
        $query = 'DELETE FROM user_designation 
					WHERE designation_id = "'.$designation_id.'" ';
        $result = $this->db->query($query);
        return $result;
    }// End of function designation_delete.

	/* Function is used to count no. of rows with a same user designation name from user designation table.
	* @param - user designation name.
	* @returns- a row count value.
	*/    
	function check_user_designation_name($designation_name) {
        $query = 'SELECT count(designation_name) as  user_designation_name_count
					FROM user_designation 
					WHERE designation_name = "'.$designation_name.'" ';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['user_designation_name_count']);
    }// End of function check_user_designation_name.
	
	/* Function is used to count no. of rows with a same user designation name from user designation table.
	* @param - user designation name.
	* @returns- a row count value.
	*/    
	function search_user_designation_name($designation_id,$designation_name) {
        $query = 'SELECT count(designation_name) as  user_designation_name_count
					FROM user_designation 
					WHERE designation_name = "'.$designation_name.'" 
					AND designation_id != "'.$designation_id.'"';
        $res = $this->db->query($query);
        $data = $res->row_array();
        return ($data['user_designation_name_count']);
    }// End of function search_user_designation_name.	

}// End of Class Designation_model.
?>