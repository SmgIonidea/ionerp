<?php
/**
 * Description	:	To display the existing group and provisions to edit and delete the groups.
					Permission(s) allocated for each group can also be viewed
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 *
  ---------------------------------------------------------------------------------------------- */
?>

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_groups_model extends CI_Model {
	
	/**
	 * This function inserts group name and their description into the group table
	 * @parameters: group name, corresponding description and permissions are to be inserted in the table
	 * @return: boolean value
	 */
    public function user_groups_add($group_name, $group_description, $list) {
        $query = 'INSERT INTO groups (name, description) 
				  VALUES ("' . $group_name . '", "' . $group_description . '")';
        $insert_result = $this->db->query($query);

        $get_id_inserted_value = 'SELECT MAX(id) 
								  FROM groups';
        $select_result = $this->db->query($get_id_inserted_value);
        $select_result = $select_result->result_array();
        $group_id = $select_result[0]['MAX(id)'];

        foreach ($list as $permission) {
            $query = 'INSERT INTO usergroup_permission (usergroup_id, permission_id) 
					  VALUES ("' . $group_id . '", "' . $permission . '")';
            $insert_result = $this->db->query($query);
        }
        
		return true;
    }

	/**
	 * Function to fetch all the selected & stored permissions for a particular user group
	 * @parameters: user group id
	 * @return: will return all the permissions related to the group 
	 */
    public function selected_permission_list($user_group_id) {
        $user_group_id = 'SELECT permission_id 
						  FROM usergroup_permission 
						  WHERE usergroup_id = "' . $user_group_id . '"';
        $user_group_id = $this->db->query($user_group_id);
        $user_group_id = $user_group_id->result_array();
		
        return $user_group_id;
    }

	/**
	 * The function fetches all the permissions that are available in the permission table (while adding new user group)
	 * @return: will return all the permissions to be displayed
	 */
    public function get_permission_list() {
        $permission_list = 'SELECT permission_id, permission_function 
							FROM permission';
        $permission_list = $this->db->query($permission_list);
        $permission_array = $permission_list->result_array();
        
		return $permission_array;
    }

	/**
	 * The function fetches the permission details for a particular user group from the permission table
	 * @parameters: user group id is passed
	 * @return: will return all the permissions related to that user group
	 */
    public function get_permission($user_group_id) {
       $select_result = 'SELECT p.permission_id, p.permission_function 
						 FROM permission AS p, usergroup_permission AS up 
						 WHERE up.usergroup_id = "' . $user_group_id . '" AND up.permission_id = p.permission_id';
        $select_result = $this->db->query($select_result);
        $select_result = $select_result->result_array();
		
        return $select_result;
    }

	/**
	 * This function displays the list of groups and their description
	 * @return: returns all the rows containing user groups and their description
	 */
    public function user_groups_search() {
        $query = $this->db->select('SQL_CALC_FOUND_ROWS name, description, id', false);
        $this->db->from('groups');
        $return_array['rows'] = $query->get()->result_array();
        $query = $this->db->select('FOUND_ROWS() as count', FALSE);
        $tmp = $query->get()->result();
        $return_array['num_rows'] = $tmp[0]->count;
        
		return $return_array;
    }

	/**
	 * This function fetches all the details of the user group that needs to be edited
	 * @parameters: user group id is passed
	 * @return: returns all the details related to that user group from the groups table
	 */
    public function user_groups_edit($user_group_id) {
        $query = 'SELECT * 
				  FROM groups 
				  WHERE id = "' . $user_group_id . '"';
        $result = $this->db->query($query);
        $result = $result->result_array();
        
		return $result;
    }

	/**
	 * The function is used to update the permissions and other details related to usergroup.
	 * @parameters: user group id, name, description and the entire permission list is passed
	 * @return: boolean
	 */
    public function user_groups_update($user_group_id, $group_name, $group_description, $list) {
        $query = 'UPDATE groups 
				  SET name = "' . $group_name . '", description = "' . $group_description . '" 
				  WHERE id = "' . $user_group_id . '"';
        $result = $this->db->query($query);

        $delete_old = 'DELETE FROM usergroup_permission 
					   WHERE usergroup_id = "' . $user_group_id . '"';
        $result = $this->db->query($delete_old);

        foreach ($list as $permission) {
            $query = 'INSERT INTO usergroup_permission (usergroup_id, permission_id) 
					  VALUES ("' . $user_group_id . '", "' . $permission . '")';
            $result = $this->db->query($query);
        }
        
		return true;
    }
}

/*
 * End of file user_group_model.php
 * Location: .configuration/user_groups/user_groups_model.php 
 */
?>