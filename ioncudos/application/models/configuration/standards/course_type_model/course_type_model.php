<?php

/**
 * Description	:	Displays the course type list along with add, edit and delete options.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 27-03-2014		   Jevi V. G				Added description field for course_type		
 * 29-09-2014			Jyoti					Added Weightage section for course_type
 * 29-10-2014		   Shayista Mulla			Add curriculum component name dropdown list 
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Course_type_model extends CI_Model {

    /**
     * This function is used to check for uniqueness of the course type and to insert
     * the course type into the course type table 
     * @parameters: course type name (should be unique) 
     * @return: if unique it will return true else it will return false 
     */
    function course_insert($course_type_name, $course_type_description, $import, $crclm_component_name) {

        $query = $this->db->get_where('course_type', array('crs_type_name' => $course_type_name, 'crs_type_description' => $course_type_description));

        if ($query->num_rows == 1) {
            return FALSE;
        }

        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('course_type', array(
            'crs_type_name' => $course_type_name,
            'crs_type_description' => $course_type_description,
            'crs_import_flag' => $import,
            'created_by' => $created_by,
            'created_date' => $created_date,
            'crclm_component_id' => $crclm_component_name
        ));

        return TRUE;
    }

    /**
     * This function will fetch the existing course type
     * @parameters: course type id is passed
     * @return: returns the course type pertaining to that id 
     */
    function course_edit($course_type_id) {

        $query = "SELECT crclm_component_id,crs_type_name, crs_type_description ,crs_import_flag FROM course_type WHERE crs_type_id = " . $course_type_id;
        $row = $this->db->query($query);
        return $row->row_array();
    }

    /**
     * This function is used to update the existing code
     * @parameters: course type id and course name 
     * @return: boolean or the updated data will be returned back 
     */
    function course_update($course_type_id, $course_type_name, $course_type_description,$import, $crclm_component_name) {
        $course_type_name = $this->db->escape_str($course_type_name);
        $course_type_description = $this->db->escape_str($course_type_description);
        $query = $this->db->query("SELECT * FROM course_type WHERE crs_type_name = '$course_type_name' AND crs_type_id != '$course_type_id'");

        if ($query->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $query = "UPDATE `course_type` SET `crs_type_name` = '$course_type_name', `crs_type_description` = '$course_type_description',`crs_import_flag` = '$import',`modified_by` = '$modified_by', `modified_date` = '$modified_date',`crclm_component_id`='$crclm_component_name' WHERE `crs_type_id` = '$course_type_id'";

            $result = $this->db->query($query);


            return $result;
        }
    }

    /**
     * This function is used fetch and display the course type in the grid
     * @parameters: 
     * @return: returns all the course type id and course type name from the course type table 
     */
    function course_type_list() {
        $query = "SELECT c.crclm_component_id,c.crs_type_id, c.crs_type_name,c.crs_type_description,(SELECT count(crs_type_id) FROM course WHERE course.crs_type_id = c.crs_type_id) as is_course_type FROM course_type AS c ";
        $row = $this->db->query($query);
        $row = $row->result_array();
        $ret['rows'] = $row;
        $query = $this->db->select('COUNT(*) as count', FALSE)
                ->from('course_type');
        $tmp = $query->get()->result();
        $ret['num_rows'] = $tmp[0]->count;

        return $ret;
    }

    /**
     * This function is used to delete the existing course type
     * @parameters: course type id
     * @return: returns boolean 
     */
    function course_delete($course_type_id) {
        $query = "DELETE FROM course_type WHERE crs_type_id = '$course_type_id'";
        $result = $this->db->query($query);

        return $result;
    }

    /**
     * This function checks for the uniqueness of the course type while adding the course type
      i.e to check whether the course type already exists or not
     * @parameters: course type id
     * @return: returns all the details from the course type table for the existing course type 
     */
    function add_unique_course_type($course_type_name) {
        $query = "SELECT count(crs_type_name) FROM course_type WHERE crs_type_name = '$course_type_name' ";
        $result = $this->db->query($query);
        $data = $result->row_array();

        return ($data['count(crs_type_name)']);
    }

    /**
     * This function checks for the uniqueness of the course type while editing the course type
      i.e to check whether the course type already exists or not
     * @parameters: course type id
     * @return: returns all the details from the course type table for the existing course type 
     */
    function unique_course_type($crs_type_id, $course_type_name) {
        $query = "SELECT count(crs_type_name) FROM course_type WHERE crs_type_name = '$course_type_name' AND crs_type_id != '$crs_type_id' ";
        $result = $this->db->query($query);
        $data = $result->row_array();

        return ($data['count(crs_type_name)']);
    }

    /*
     * This function is used fetch and display the curriculum components
     * @parameters:
     * @return: returns curriculum component id,curriculum components name
     */

    function curriculum_component_name_list() {
        $crclm_component_name = 'SELECT DISTINCT cc_id,crclm_component_name
				FROM crclm_component
				ORDER BY crclm_component_name ASC';
        $crclm_component_name_result = $this->db->query($crclm_component_name);
        $crclm_component_name_result = $crclm_component_name_result->result_array();
        $crclm_component_data['crclm_component_name_result'] = $crclm_component_name_result;

        return $crclm_component_data;
    }

    //End of the function curriculum_component_name_list
}

/**
 * End of file course_type_model.php 
 * Location: .configuration/standards/course_type_model.php 
 */
?>
