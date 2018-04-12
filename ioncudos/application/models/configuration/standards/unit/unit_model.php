<?php

/**
 * Description	:	Model Logic for Unit Module (List, Add, Edit & Delete).
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 26-08-2013       Abhinay B.Angadi        Added file headers, function headers & comments.
 * 03-09-2013       Mritunjay B S           Changed Function name, Variable names.
 * 28-03-2014		   Jevi V. G				Added description field for unit		
  ----------------------------------------------------------------------------------------------------------------------
 */
?>

<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Unit_model extends CI_Model {
    
    /* Function is to insert a new unit onto unit table.
     * @param - unit name.
     * @returns- a boolean value.
     */
    public function unit_insert($unit_name, $unit_description) {
		$unit_name = $this->db->escape_str($unit_name);
		$unit_description = $this->db->escape_str($unit_description);
        $query = $this->db->get_where('unit', array('unit_name' => $unit_name, 'unit_description' => $unit_description));
        if ($query->num_rows == 1) {
            return FALSE;
        }
        $created_by = $this->ion_auth->user()->row()->id;
        $created_date = date('Y-m-d');
        $this->db->insert('unit', array(
                          'unit_name'       => $unit_name,
						  'unit_description'=> $unit_description,
                          'created_by'      => $created_by,
                          'created_date'    => $created_date,
        ));
        return TRUE;
    }
    
     /* Function Ends Here. */
    

    /* Function is to fetch a unit details from unit table.
     * @param - unit id.
     * @returns- a array of values of unit details.
     */

    public function unit_edit($unit_id) {

        $unit_edit_query = 'SELECT unit 
                            WHERE unit_id="'.$unit_id.'" ';
        $unit_data = $this->db->query($unit_edit_query);
        return $unit_data->row_array();
    }
    
    /* Function is to update unit details from unit table.
     * @param - unit id, unit name.
     * @returns- a boolean value.
     */

    public function unit_update($unit_id, $unit_name, $unit_description) {
		$unit_name = $this->db->escape_str($unit_name);
		$unit_description = $this->db->escape_str($unit_description);
        $unit_update_query = 'SELECT unit_id 
                              FROM unit 
                              WHERE unit_name = "'.$unit_name.'" AND unit_id != "'.$unit_id.'" ';
        $unit_update_data = $this->db->query($unit_update_query);
        if ($unit_update_data->num_rows == 1) {
            return FALSE;
        } else {
            $modified_by = $this->ion_auth->user()->row()->id;
            $modified_date = date('Y-m-d');
            $update_query = 'UPDATE unit SET  unit_name = "'.$unit_name.'",unit_description = "'.$unit_description.'", modified_by = "'.$modified_by.'", modified_date = "'.$modified_date.'" 
                             WHERE  unit_id = "'.$unit_id.'" ';
            $update_result = $this->db->query($update_query);
            return $update_result;
        }
    }

    /* Function is to fetch all the unit details from unit table.
     * @param - 
     * @returns- a array of values of all the unit details.
     */

    public function unit_list($order, $offset) {
        $unit_list_query = 'SELECT unit_id,unit_name, unit_description  FROM unit';
        $unit_list_data = $this->db->query($unit_list_query);
        $unit_list_result = $unit_list_data->result_array();
        $unit_result['rows'] = $unit_list_result;
        $unit_query = $this->db->select('COUNT(*) as count', FALSE)
							->from('unit');
        $unit_temp_data = $unit_query->get()->result();
        $unit_result['num_rows'] = $unit_temp_data[0]->count;
		
		
        return $unit_result;
    }

    /* Function is to fetch a unit details from unit table.
     * @param - unit id.
     * @returns- a array of values of a unit.
     */

   public function unit_unique_search($unit_id) {
        $unit_unique_query = 'SELECT * FROM unit WHERE unit_id = "'.$unit_id.'" ';
		$unit_unique_result = $this->db->query($unit_unique_query);
        $unit_unique_data = $unit_unique_result->result_array();
        if ($unit_unique_result->num_rows() > 0) {
        return $unit_unique_data;
        }
    }
    
    /* Function is to delete a unit from unit table.
     * @param - unit id.
     * @returns- a boolean value.
     */

   public function unit_delete($unit_id) {
 
			$unit_delete_query = 'DELETE FROM unit WHERE unit_id = "'.$unit_id.'" ';
			$unit_delete_result = $this->db->query($unit_delete_query);
			return $unit_delete_result; 
			
    }
	
	public function unit_delete_msg($unit_id) {
	
		$unit_count_query = 'SELECT count(*) FROM program WHERE max_unit_id = "'.$unit_id.'" OR term_max_unit_id = "'.$unit_id.'" ';
		$unit_count_data = $this->db->query($unit_count_query);
		$unit_count = $unit_count_data->result_array();
		$unit_count_result = $unit_count[0]['count(*)'];
		
		$crclm_unit_count_query = 'SELECT count(*) FROM crclm_terms WHERE unit_id = "'.$unit_id.'" ';
		$crclm_unit_count_data = $this->db->query($crclm_unit_count_query);
		$crclm_unit_count = $crclm_unit_count_data->result_array();
		$crclm_unit_count_result = $crclm_unit_count[0]['count(*)'];
		
		if($unit_count_result == 0 && $crclm_unit_count_result == 0){
		echo 1;
		}
		else{
		echo 0;
		}
	
	}
	
	 public function check_unique_unit($unit) {
        $unit_unique_query = 'select count(unit_name) from unit where unit_name = "'.$unit.'"';
        $unit_unique_result = $this->db->query($unit_unique_query);
        $unit_unique_data = $unit_unique_result->row_array();
        return ($unit_unique_data['count(unit_name)']);
    }

    /* Function is to count no. of rows with a same unit name from unit table.
     * @param - unit name.
     * @returns- a row count value.
     */
   public function edit_check_unique_unit($unit,$unit_id) {
        $unit_unique_query = 'select count(unit_name) from unit where unit_name = "'.$unit.'" AND unit_id != "'.$unit_id.'"';
        $unit_unique_result = $this->db->query($unit_unique_query);
        $unit_unique_data = $unit_unique_result->row_array();
        return ($unit_unique_data['count(unit_name)']);
    }

}
?>

