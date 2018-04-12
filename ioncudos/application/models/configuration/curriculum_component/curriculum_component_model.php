<?php

/**
 * Description	:	Model Logic for curriculum component Module (List, Add, Edit, Delete).
 * Created		:	22-10-2015 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
  ------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Curriculum_component_model extends CI_Model {
    /* Function is used to fetch all the curriculum component details from curriculum component table.
     * @param - 
     * @returns- a array of values of all the curriculum component details.
     */

    public function curriculum_component_list() {
        $query = 'SELECT cc_id, crclm_component_name, crclm_component_desc 
					FROM crclm_component';
        $result = $this->db->query($query);
        $result = $result->result_array();
        $data['rows'] = $result;
        return $data;
    }

//End of function curriculum_component_list.

    /* Function is used to count no. of rows with a same curriculum component name from curriculum component table.
     * @param - curriculum component name.
     * @returns- a row count value.
     */

    public function add_search_curriculum_component_by_name($curriculum_component_name) {
        $query = 'SELECT count(crclm_component_name) as  curriculum_component_name_count
					FROM crclm_component
					WHERE crclm_component_name = "' . $curriculum_component_name . '" ';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['curriculum_component_name_count'];
    }

// End of function add_search_curriculum_component_by_name.

    /* Function is used to insert curriculum component name from curriculum component table.
     * @param - curriculum component name,curriculum component description .
     * @returns- string value.
     */

    public function curriculum_component_insert($curriculum_component_name, $curriculum_component_description) {
        $course_component_name = $this->db->escape_str($curriculum_component_name);
        $course_component_description = $this->db->escape_str($curriculum_component_description);
        $query = $this->db->get_where('crclm_component', array('crclm_component_name' => $curriculum_component_name));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $this->db->insert('crclm_component', array('crclm_component_name' => $curriculum_component_name,
            'crclm_component_desc' => $curriculum_component_description,
            'created_by' => $loggedin_user_id,
            'created_date' => date('Y-m-d')
                )
        );
        return TRUE;
    }

// End of function curriculum_component_insert.

    /* Function is used to fetch a curriculum component details from curriculum component table.
     * @param - curriculum component id.
     * @returns- a array of values of a curriculum component.
     */

    public function curriculum_component_search_by_id($pgmtype_id) {
        $query = 'SELECT cc_id, crclm_component_name, crclm_component_desc
					FROM crclm_component
					WHERE cc_id= "' . $pgmtype_id . '" ';
        $query_result = $this->db->query($query);
        $result = $query_result->result_array();
        if ($query_result->num_rows() > 0) {
            return $result;
        }
    }

// End of function curriculum_component_search_by_id.

    /* Function is used to count no. of rows with a same curriculum component name from curriculum component table.
     * @param - curriculum component name.
     * @returns- a row count value.
     */

    public function curriculum_component_search_by_name($curriculum_component_id, $curriculum_component_name) {
        $query = 'SELECT count(crclm_component_name) as  crclm_component_name_count
					FROM crclm_component
					WHERE crclm_component_name= "' . $curriculum_component_name . '" 
					AND cc_id != "' . $curriculum_component_id . '"';
        $result = $this->db->query($query);
        $data = $result->row_array();
        return $data['crclm_component_name_count'];
    }

// End of function curriculum_component_search_by_name.

    /* Function is used to update curriculum component details from curriculum component table.
     * @param - curriculum component id, curriculum component name, curriculum component description.
     * @returns- a boolean value.
     */

    public function curriculum_component_update($curriculum_id, $curriculum_name, $curriculum_description) {
        $curriculum_name = $this->db->escape_str($curriculum_name);
        $curriculum_description = $this->db->escape_str($curriculum_description);
        $query = 'SELECT crclm_component_name
					FROM crclm_component 
					WHERE crclm_component_name = "' . $curriculum_name . '" 
					AND cc_id!= "' . $curriculum_id . '" ';
        $query_result = $this->db->query($query);
        // Checks & weather the duplicate entry for same curriculum component name exist with different curriculum component id.
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $loggedin_user_id = $this->ion_auth->user()->row()->id;
            $date = date('Y-m-d');
            $query = 'UPDATE crclm_component
					SET crclm_component_name =  "' . $curriculum_name . '", crclm_component_desc= "' . $curriculum_description . '", 
						modified_by = "' . $loggedin_user_id . '", modified_date = "' . $date . '" 
					WHERE cc_id  = "' . $curriculum_id . '" ';
            $result = $this->db->query($query);
            return TRUE;
        }
    }

// End of function curriculum_component_update.

    /* Function is used to delete curriculum component details from curriculum component table.
     * @param - curriculum component id
     * @returns- a boolean value.
     */

    public function curriculum_component_delete_record($curriculum_component_id) {
        $this->db->delete('crclm_component', array('cc_id' => $curriculum_component_id));
        return TRUE;
    }

// End of function curriculum_component_delete_record
}
?>
