<?php

/**
 * Description		:	Model Logic for lab category Module (List, Add, Edit, Delete).
 * Created		:	07-11-2015 
 * Author		:	Shayista Mulla
 * Modification History:
 * Date				Modified By				Description
  ------------------------------------------------------------------------------------------------
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lab_category_model extends CI_Model {
    /* Function is used to fetch all the lab category details from master type details table.
     * @param - 
     * @returns- a array of values of all the lab category details.
     */

    public function lab_category_list() {
        $list_lab_category_query = 'SELECT mt_details_id, mt_details_name,mt_details_name_desc
		      FROM master_type_details
		      WHERE master_type_id = "9" ORDER BY mt_details_name';
        $list_lab_category_result = $this->db->query($list_lab_category_query);
        $list_lab_category_result = $list_lab_category_result->result_array();
        $data['rows'] = $list_lab_category_result;
        return $data;
    }

//End of function lab_category_list.

    /* Function is used to count no. of rows with a same lab category name from master type details table.
     * @param - lab category name.
     * @returns- a row count value.
     */

    public function add_search_lab_category_by_name($lab_category_name) {
        $search_lab_category_name_query = 'SELECT count(mt_details_name) as  lab_category_name_count
			FROM master_type_details
			WHERE mt_details_name = "' . $lab_category_name . '" AND master_type_id="9"';
        $search_lab_category_name_result = $this->db->query($search_lab_category_name_query);
        $data = $search_lab_category_name_result->row_array();
        return $data['lab_category_name_count'];
    }

// End of function add_search_lab_category_by_name.

    /* Function is used to insert lab category name from master type details table.
     * @param - lab category name,lab category description .
     * @returns- string value.
     */

    public function lab_category_insert($lab_category_name, $lab_category_description) {
        $lab_category_name = $this->db->escape_str($lab_category_name);
        $lab_category_description = $this->db->escape_str($lab_category_description);
        $loggedin_user_id = $this->ion_auth->user()->row()->id;
        $this->db->insert('master_type_details', array('mt_details_name' => $lab_category_name,
            'mt_details_name_desc' => $lab_category_description,
            'master_type_id' => 9, 'created_by' => $loggedin_user_id, 'created_date' => date('Y-m-d'))
        );
        return TRUE;
    }

// End of function lab_category_insert.

    /* Function is used to fetch a lab category details from master type details table.
     * @param - lab category type id.
     * @returns- a array of values of a lab category.
     */

    public function lab_category_search_by_id($lab_category_id) {
        $search_lab_category_id_query = 'SELECT mt_details_id, mt_details_name, mt_details_name_desc
			FROM master_type_details
			WHERE mt_details_id= "' . $lab_category_id . '" ';
        $search_lab_category_id_result = $this->db->query($search_lab_category_id_query);
        $search_lab_category_id_result = $search_lab_category_id_result->result_array();
        return $search_lab_category_id_result;
    }

// End of function lab_category_search_by_id.

    /* Function is used to count no. of rows with a same lab category name from master type details table.
     * @param - lab category name.
     * @returns- a row count value.
     */

    public function lab_category_search_by_name($lab_category_id, $lab_category_name) {
        $search_lab_category_name_query = 'SELECT * FROM master_type_details
			WHERE (mt_details_name= "' . $lab_category_name . '" 
			AND mt_details_id!= "' . $lab_category_id . '" AND master_type_id="9")';
        $search_lab_category_name_result = $this->db->query($search_lab_category_name_query);
        return $search_lab_category_name_result->num_rows();
    }

// End of function lab_category_search_by_name.

    /* Function is used to update lab category details from master type details table.
     * @param - lab category id, lab category name, lab category description.
     * @returns- a boolean value.
     */

    public function lab_category_update($lab_category_id, $lab_category_name, $lab_category_description) {
        $lab_category_name = $this->db->escape_str($lab_category_name);
        $lab_category_description = $this->db->escape_str($lab_category_description);
        $update_lab_category_query = 'UPDATE master_type_details
                                    SET mt_details_name=  "' . $lab_category_name . '", mt_details_name_desc= "' . $lab_category_description . '",
                                    modified_by="' . $this->ion_auth->user()->row()->id . '", modified_date="' . date('Y-m-d') . '"
                                    WHERE mt_details_id = "' . $lab_category_id . '" ';
        $update_lab_category_result = $this->db->query($update_lab_category_query);
        return TRUE;
    }

// End of function lab_category_update.

    /* Function is used to check whether lab category details are using in lab experiments.
     * @param - lab category id
     * @returns- a boolean value.
     */

    public function lab_category_delete_record_check($lab_category_id) {
        $check_lab_category_delete_query = 'SELECT * FROM topic
			WHERE category_id = "' . $lab_category_id . '"';
        $check_lab_category_delete_result = $this->db->query($check_lab_category_delete_query);
        if ($check_lab_category_delete_result->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

// End of function lab_category_delete_record_check.

    /* Function is used to delete lab category details from master type details table.
     * @param - lab category id
     * @returns- a boolean value.
     */

    public function lab_category_delete_record($lab_category_id) {
        $this->db->delete('master_type_details', array('mt_details_id' => $lab_category_id));
        return true;
    }

}
?>
