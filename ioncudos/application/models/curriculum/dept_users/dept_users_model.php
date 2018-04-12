<?php

/**
 * Description	:	Department users list, add, edit - Model
 * Created		:	27-03-2013
 * Author		:	Arihant Prasad
 * Modification History:
 *   Date                Modified By                			Description
 *  4/12/2016			Bhagyalaxmi S S							Addedd faculty  Contribution Column
  --------------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dept_users_model extends CI_Model {

    /**
     * Function to user details
     * @parameters: deptartment id
     * @return: returns array of assessment method id ,name and description
     */
    public function dept_user_list() {
        $user_dept_id = $this->ion_auth->user()->row()->user_dept_id;

        $dept_user_query = 'SELECT u.id, u.title, u.first_name, u.last_name, u.email, u.base_dept_id, u.active, d.dept_name, ud.designation_name
							FROM users u, department d, user_designation ud
							WHERE u.base_dept_id = d.dept_id
								AND u.designation_id = ud.designation_id
								AND d.dept_id = "' . $user_dept_id . '"								
								AND u.designation_id != 11';
        $dept_user_data = $this->db->query($dept_user_query);
        $dept_user_result['users'] = $dept_user_data->result_array();

        return $dept_user_result;
    }

    /**
     * Function to fetch group list, to allocate a group to the user
     * @parameters:
     * @return: group id and group name
     */
    function group_list() {
        $group_list_query = 'SELECT id, name
							 FROM groups
						 	  WHERE id NOT IN (1,2,3)
							 ORDER BY name ASC';
        $group_list_data = $this->db->query($group_list_query);
        $group_list_result = $group_list_data->result_array();

        return $group_list_result;
    }

    /**
     * Function to fetch designation for the user while creating new user
     * @parameters:
     * return: designation id and designation name
     */
    function designation_list() {
        $designation_list_query = 'SELECT designation_id, designation_name
								   FROM user_designation
								   where UPPER(designation_name)  != "STUDENT"
								   ORDER BY designation_name ASC';
        $designation_list_data = $this->db->query($designation_list_query);
        $designation_list_result = $designation_list_data->result_array();

        return $designation_list_result;
    }

    /* Function to fetch list of department with status '1'
     * @parameters:
     * @return: department id and department name
     */

    function department_list() {
        $user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        $department_list_query = 'SELECT dept_id, dept_name
								  FROM department
								  WHERE dept_id = "' . $user_dept_id . '"';
        $department_list_data = $this->db->query($department_list_query);
        $department_list_result = $department_list_data->result_array();

        return $department_list_result;
    }

    /**
     * From user edit call : to delete user_id, dept_id, role_id from `map_user_dept_role` table - multi-user dept model.
     * @parameters: user id and user department id
     * @return: boolean
     */
    public function delete_user_dept_role($user_id, $user_dept_id) {
        $user_dept_data = array(
            'user_id' => $user_id,
            'dept_id' => $user_dept_id
        );
        $this->db->where($user_dept_data);
        $this->db->delete('map_user_dept_role');

        return true;
    }

    /**
     * From user edit call : to insert user_id, dept_id, role_id onto `map_user_dept_role` table - multi-user dept model.
     * @parameters: user department role data
     * @return: boolean
     */
    public function insert_user_dept_role($user_dept_role_data) {
        $this->db->insert('map_user_dept_role', $user_dept_role_data);
        return true;
    }

    /**
     * Function to fetch help details from the help table
     * @parameters  :
     * @return      : help details
     */
    function dept_user_help() {
        $help = 'SELECT serial_no, entity_data, help_desc 
				 FROM help_content 
				 WHERE entity_id = 28';
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

    /*
     * Function is to fetch help cotent details of Department user list.
     * @parameters  :
     * returns      : an object.
     */

    function help_content($id) {
        $help = 'SELECT  entity_data, help_desc
				 FROM help_content
				 WHERE serial_no = "' . $id . '"';
        $reslt = $this->db->query($help);
        $row = $reslt->result_array();

        return $row;
    }

    /*
     * Function is to deactivate user.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function deactivate($user_id) {
        $deactivate_query = 'UPDATE users SET active=0 WHERE id="' . $user_id . '"';
        $result = $this->db->query($deactivate_query);

        return $result;
    }

    /*
     * Function is to activate user.
     * @parameters  :
     * returns      : a boolean value.
     */

    public function activate($user_id) {
        $deactivate_query = 'UPDATE users SET active=1 WHERE id="' . $user_id . '"';
        $result = $this->db->query($deactivate_query);

        return $result;
    }

    public function enable_user_list($user_id) {
        $query = 'SELECT * FROM curriculum WHERE crclm_owner = ' . $user_id;
        $query_data = $this->db->query($query);

        $data_count = $query_data->num_rows();
        if ($data_count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}
?>