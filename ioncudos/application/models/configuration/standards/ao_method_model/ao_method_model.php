<?php

/**
 * Description	:	Assessment Method.
 * 					
 * Created		:	14-08-2014
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 *
  --------------------------------------------------------------------------------------------- */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_method_model extends CI_Model {
    /* Function to fetch all programs from program table
     * @parameter - 
     * @returns- all programs from program table
     */

    public function get_all_program() {
        $program_list = $this->db
                ->select('pgm_id,pgm_title')
                ->order_by('pgm_title', 'asc')
                ->get('program');
        $program_list_result = $program_list->result_array();
        $program_list_data['programs'] = $program_list_result;
        return $program_list_data;
    }

// End of function get_all_program.

    /**
     * Function to fetch assessment method id ,name and description for a program
     * @parameters: program id
     * @return: returns array of assessment method id ,name and description
     */
    public function ao_method_list($program_id) {
        // $ao_method_list = 'SELECT ao.ao_method_id, ao.ao_method_name , ao.ao_method_description
        // FROM ao_method ao
        // WHERE ao_method_pgm_id ='.$program_id.'';
        $ao_method_list = 'SELECT  a.ao_method_id,a.ao_method_name,a.ao_method_description,(SELECT COUNT(r.ao_method_id) 
																							FROM ao_rubrics_range r 
																							WHERE a.ao_method_id = r.ao_method_id) AS isDef
							FROM ao_method a
							WHERE a.ao_method_pgm_id =' . $program_id;
        $ao_method_list_result = $this->db->query($ao_method_list);
        $ao_method_list_data['ao_method'] = $ao_method_list_result->result_array();
        return $ao_method_list_data;
    }

// End of function ao_method_list.

    /* Function is used to count no. of rows with a same assessment method name from ao_method table for a department.
     * @param - program id and assessment method name.
     * @returns- count of assessment method with same name for a program.
     */

    public function add_search_ao_method_name($ao_method_pgm_id, $ao_method_name) {
        $ao_method_name = $this->db->escape_str($ao_method_name);
        $query = $this->db->query(' SELECT count(ao_method_name) as ao_method_name_count
									FROM ao_method 
									WHERE ao_method_name = "' . $ao_method_name . '"
									AND ao_method_pgm_id = ' . $ao_method_pgm_id . '
								');
        $data = $query->row_array();
        return ($data['ao_method_name_count']);
    }

// End of function add_search_ao_method_name.

    /* Function is used to get the name of assessment method.
     * @param - assessment method id.
     * @returns - assessment method name.
     */

    public function ao_name_data($ao_method_id) {
        $query = $this->db->query('SELECT ao_method_name FROM ao_method WHERE ao_method_id=' . $ao_method_id);
        return $query->result_array();
    }

    /* Function is used to insert a new assessment method onto the ao_method table.
     * @param - program id ,assessment method name and description.
     * @returns- a boolean value.
     */

    public function ao_method_insert_record($ao_method_pgm_id, $ao_method_name, $ao_method_description, $is_define_rubrics, $ao_range_value, $ao_criteria_value, $criteria_desc) {
        $ao_method_name = $this->db->escape_str($ao_method_name);
        $ao_method_description = $this->db->escape_str($ao_method_description);
        $query = $this->db->query('SELECT ao_method_pgm_id 
									FROM ao_method
									WHERE ao_method_pgm_id = ' . $ao_method_pgm_id . '
									AND ao_method_name =  "' . $ao_method_name . '"');

        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');

        $this->db->trans_start();

        $this->db->insert('ao_method', array(
            'ao_method_pgm_id' => $ao_method_pgm_id,
            'ao_method_name' => $ao_method_name,
            'ao_method_description' => $ao_method_description,
            'created_by' => $created_by,
            'created_date' => $created_date)
        );

        $method_id_result = $this->db->query('SELECT ao_method_id 
									FROM ao_method
									WHERE ao_method_pgm_id = ' . $ao_method_pgm_id . '
									AND ao_method_name =  "' . $ao_method_name . '"');

        $method_id = $method_id_result->row_array();
        if (($is_define_rubrics) && ($ao_range_value != NULL) && ($ao_criteria_value != NULL) && ($criteria_desc != NULL)) {
            $range_primary_key = array();
            for ($i = 0; $i < count($ao_range_value); $i++) {
                $ao_rubrics_range_data = array(
                    'ao_method_id' => $method_id['ao_method_id'],
                    'criteria_range' => $ao_range_value[$i],
                    'created_by' => $created_by,
                    'created_date' => $created_date
                );
                $this->db->insert('ao_rubrics_range', $ao_rubrics_range_data);
                $range_primary_key[$i] = $this->db->insert_id();
            }
            $criteria_primary_key = array();
            for ($i = 0; $i < count($ao_criteria_value); $i++) {
                $ao_rubrics_criteria_data = array(
                    'ao_method_id' => $method_id['ao_method_id'],
                    'criteria' => $ao_criteria_value[$i],
                    'created_by' => $created_by,
                    'created_date' => $created_date
                );
                $this->db->insert('ao_rubrics_criteria', $ao_rubrics_criteria_data);
                $criteria_primary_key[$i] = $this->db->insert_id();
            }
            for ($x = 0; $x < count($criteria_primary_key); $x++) {
                $rubrics_criteria_id = $criteria_primary_key[$x];
                $i = $x + 1;
                for ($y = 0; $y < count($range_primary_key); $y++) {
                    $rubrics_range_id = $range_primary_key[$y];
                    $j = $y + 1;
                    $criteria_description_data = array(
                        'rubrics_range_id' => $rubrics_range_id,
                        'rubrics_criteria_id' => $rubrics_criteria_id,
                        'criteria_description' => $criteria_desc[$x][$y],
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );
                    $this->db->insert('ao_rubrics_criteria_desc', $criteria_description_data);
                }
            }
        }
        $this->db->trans_complete();
        return TRUE;
    }

// End of function ao_method_insert_record.

    /* Function is used to count no. of rows with a same assessment method name from ao_method table for a specified department.
     * @param - program id,assessment method id and assessment method name.
     * @returns- count of assessment method with same name for a department.
     */

    public function edit_search_ao_method($ao_method_pgm_id, $ao_method_id, $ao_method_name) {
        $ao_method_name = $this->db->escape_str($ao_method_name);
        $query = $this->db->query(' SELECT count(ao_method_name) as ao_method_name_count
								FROM ao_method 
								WHERE ao_method_name = "' . $ao_method_name . '"
								AND ao_method_pgm_id = "' . $ao_method_pgm_id . '"
								AND ao_method_id != "' . $ao_method_id . '"'
        );
        $data = $query->row_array();
        return ($data['ao_method_name_count']);
    }

// End of function edit_search_ao_method.


    /* Function is used to get all the ranges for the assessment method selected for edit.
     * @param - assessment method id.
     * @returns - count and ranges with the assessment method id.
     */

    public function ao_method_get_range($ao_method_id) {
        $query = $this->db->query(' SELECT rubrics_range_id,criteria_range
									FROM ao_rubrics_range
									WHERE ao_method_id = ' . $ao_method_id);

        $data = $query->result_array();
        return $data;
    }

//End of the function ao_method_get_range($ao_method_id).

    /* Function is used to get all the criteria for the assessment method selected for edit.
     * @param - assessment method id.
     * @returns - count and criteria with the assessment method id.
     */

    public function ao_method_get_criteria($ao_method_id) {
        $query = $this->db->query(' SELECT rubrics_criteria_id, criteria
									FROM ao_rubrics_criteria
									WHERE ao_method_id = ' . $ao_method_id);

        $data = $query->result_array();
        return $data;
    }

//End of the function ao_method_get_criteria($ao_method_id).

    /* Function is used to get all the criteria description for the assessment method selected for edit.
     * @param - assessment method id.
     * @returns - criteria description with the assessment method id.
     */

    public function ao_method_get_criteria_desc($ao_method_id) {
        $query = $this->db->query(' SELECT cd.criteria_description_id, cd.rubrics_range_id, r.criteria_range, cd.rubrics_criteria_id, c.criteria, cd.criteria_description
									FROM ao_rubrics_criteria_desc cd
									JOIN ao_rubrics_range r ON r.rubrics_range_id = cd.rubrics_range_id
									JOIN ao_rubrics_criteria c ON c.rubrics_criteria_id = cd.rubrics_criteria_id
									WHERE (
									cd.rubrics_range_id, cd.rubrics_criteria_id
									)
									IN (

									SELECT r.rubrics_range_id, c.rubrics_criteria_id
									FROM ao_rubrics_range r, ao_rubrics_criteria c
									WHERE c.ao_method_id =' . $ao_method_id . '
									AND r.ao_method_id =' . $ao_method_id . '
									)ORDER BY c.rubrics_criteria_id ASC 
								');
        $data = $query->result_array();
        return $data;
    }

//End of the function ao_method_get_criteria_desc($ao_method_id).

    /* Function is is used to edit existing assessment method.
     * @param - assessment method id.
     * @returns- returns row that matches with assessment method id.
     */

    public function ao_method_edit_record($ao_method_id) {
        $ao_method = $this->db->query('select ao_method_pgm_id,ao_method_name,ao_method_description from ao_method where ao_method_id=' . $ao_method_id);
        return $ao_method->row_array();
    }

// End of function ao_method_edit_record.

    /* Function is is used to edit existing assessment method.
     * @param - assessment method id.
     * @returns- returns row that matches with assessment method id.
     */

    public function ao_method_delete_criteria($criteria_id) {
        $query = $this->db->query('delete from ao_rubrics_criteria where rubrics_criteria_id = ' . $criteria_id);
        if ($query) {
            return 1;
        } else {
            return 0;
        }
    }

// End of function ao_method_delete_criteria.

    /* Function is is used to fetch program title and id from program table.
     * @param - program id.
     * @returns- returns row that matches with program id.
     */

    public function get_default_program_details($default_program_id) {
        $default_program_name = $this->db->query('select pgm_id,pgm_title from program where pgm_id=' . $default_program_id);
        $default_program_name_result['result'] = $default_program_name->result();
        return $default_program_name_result['result'];
    }

// End of function get_default_program_details.

    /* Function is used to update assessment method details in ao_method table.
     * @param - assessment method id,program id,assessment name and description.
     * @returns- a boolean value.
     */

    public function ao_method_update_record($ao_method_id, $ao_method_pgm_id, $ao_method_name, $ao_method_description, $is_define_rubrics, $range_count, $ao_method_counter, $range_array, $criteria_array, $criteria_desc) {
        $ao_method_name = $this->db->escape_str($ao_method_name);
        $ao_method_description = $this->db->escape_str($ao_method_description);
        $query_result = $this->db->query(' SELECT ao_method_id 
											FROM ao_method 
											WHERE ao_method_name = "' . $ao_method_name . '" 
											AND ao_method_pgm_id = "' . $ao_method_pgm_id . '"
											AND ao_method_id != "' . $ao_method_id . '" '
        );
        if ($query_result->num_rows == 1) {
            return FALSE;
        } else {
            $created_by = $this->ion_auth->user()->row()->id;
            $created_date = date('Y-m-d');
            $orig_ao_method_id = $ao_method_id;
            $this->db->trans_start();

            $update_ao_method = array(
                'ao_method_pgm_id' => $ao_method_pgm_id,
                'ao_method_name' => $ao_method_name,
                'ao_method_description' => $ao_method_description,
                'modified_by' => $created_by,
                'modified_date' => $created_date
            );
            $this->db->where('ao_method_id', $orig_ao_method_id);
            $this->db->update('ao_method', $update_ao_method);

            $this->db->query('DELETE FROM ao_rubrics_range where ao_method_id = ' . $orig_ao_method_id);
            $this->db->query('DELETE FROM ao_rubrics_criteria where ao_method_id = ' . $orig_ao_method_id);

            if (($is_define_rubrics) && ($range_array != NULL) && ($criteria_array != NULL) && ($criteria_desc != NULL)) {
                $range_primary_key = array();
                for ($i = 0; $i < count($range_array); $i++) {
                    $ao_rubrics_range_data = array(
                        'ao_method_id' => $orig_ao_method_id,
                        'criteria_range' => $range_array[$i],
                        'modified_by' => $created_by,
                        'modified_date' => $created_date
                    );
                    $this->db->insert('ao_rubrics_range', $ao_rubrics_range_data);
                    $range_primary_key[$i] = $this->db->insert_id();
                }
                $criteria_primary_key = array();
                for ($i = 0; $i < count($criteria_array); $i++) {
                    $ao_rubrics_criteria_data = array(
                        'ao_method_id' => $orig_ao_method_id,
                        'criteria' => $criteria_array[$i],
                        'modified_by' => $created_by,
                        'modified_date' => $created_date
                    );
                    $this->db->insert('ao_rubrics_criteria', $ao_rubrics_criteria_data);
                    $criteria_primary_key[$i] = $this->db->insert_id();
                }
                for ($x = 0; $x < count($criteria_primary_key); $x++) {
                    $rubrics_criteria_id = $criteria_primary_key[$x];
                    for ($y = 0; $y < count($range_primary_key); $y++) {
                        $rubrics_range_id = $range_primary_key[$y];
                        $i = $x + 1;
                        $j = $y + 1;
                        $criteria_description_data = array(
                            'rubrics_range_id' => $rubrics_range_id,
                            'rubrics_criteria_id' => $rubrics_criteria_id,
                            'criteria_description' => $criteria_desc[$x][$y],
                            'modified_by' => $created_by,
                            'modified_date' => $created_date
                        );
                        $this->db->insert('ao_rubrics_criteria_desc', $criteria_description_data);
                    }
                }
            }
            // $data = array(
            // 'ao_method_id' => $orig_ao_method_id
            // );
            // $this->db->where('ao_method_id', $new_ao_method_id);
            // $this->db->update('ao_method', $data); 
            $this->db->trans_complete();
            return TRUE;
        }
    }

// End of function ao_method_update_record.

    /**
     * This function is used to delete the existing assessment method
     * @parameters: assessment method id
     * @return: returns boolean 
     */
    function ao_method_delete($ao_method_id) {
        $result = $this->db->query('delete from ao_method where ao_method_id=' . $ao_method_id);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

//End of function ao_method_delete.
}

/**
 * End of file ao_method_model.php 
 * Location: .configuration/standards/ao_method_model.php 
 */
?>